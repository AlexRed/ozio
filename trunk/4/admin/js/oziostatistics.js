jQuery(document).ready(function ($)
{
 	var photos=[];
 	var photos_per_album=1000;
 	var remainingphotos=0;
 	var max_remainingphotos=1;
 	var strings = {
 			picasaUrl:"http://picasaweb.google.com/data/feed/api/user/"
 		}; 	
	for (var i=0;i<g_parameters.length;i++){
		g_parameters[i].views=0;
		load_album_data(i,1);
	}
	update_albums_stats();
	
	function load_album_data(i,start_index){
		var obj={'album_index':i};
		remainingphotos+=photos_per_album;
		update_remainingphotos();
		
		GetAlbumData({
				//mode: 'album_data',
				username: g_parameters[i]['params']['userid'],
				album:  (g_parameters[i]['params']['albumvisibility'] == "public" ? g_parameters[i]['params']['gallery_id'] : g_parameters[i]['params']['limitedalbum']),
				authKey: g_parameters[i]['params']['limitedpassword'],
				StartIndex: start_index,
				beforeSend: OnBeforeSend,
				success: OnLoadSuccess,
				error: OnLoadError, /* "error" is deprecated in jQuery 1.8, superseded by "fail" */
				complete: OnLoadComplete,
	
				// Tell the library to ignore parameters through GET ?par=...
				useQueryParameters: false,
				keyword:'',
				thumbSize:72,
				thumbCrop:false,
				photoSize:"auto",
				
				
				context:obj
			});
		
	}
	
	function update_remainingphotos(){
		if (remainingphotos>max_remainingphotos){
			max_remainingphotos=remainingphotos;
		}
		var perc=100-100*remainingphotos/max_remainingphotos;
		$('#remainingphotos').text(perc.toFixed(2)+" %");
	}
	
	function update_photos_stats(){
		photos.sort(function(a,b){return b.views - a.views});
		photos=photos.slice(0,10);
		$('#first10photos > tbody').html('');
		for (var i=0;i<photos.length ;i++){
			var a=$('<a target="_blank"></a>');
			a.attr('href',photos[i].link);
			a.text(photos[i].title);
			
			var img=$('<img>');
			img.attr('src','../components/com_oziogallery3/views/00fuerte/img/progress.gif');
			
			var a_album=$('<a target="_blank"></a>');
			a_album.attr('href',photos[i].album_link);
			a_album.text(photos[i].album_title);
			
			
			var tr=$('<tr></tr>');
			tr.append($('<td></td>').append(img));
			tr.append($('<td></td>').append(a));
			tr.append($('<td></td>').text(photos[i].summary));
			tr.append($('<td></td>').append(a_album));
			tr.append($('<td></td>').text(photos[i].views));
			$('#first10photos > tbody').append(tr);
			img.attr('src',photos[i].thumb);
		}
		
	}
	
	function update_albums_stats(){
		var albums=[];
		for (var i=0;i<g_parameters.length;i++){
			albums.push({
				'views':g_parameters[i].views,
				'title':g_parameters[i].title,
				'link':'../'+g_parameters[i].link+'&Itemid='+g_parameters[i].id//+'&tmpl=component'
			});
		}
		//ordino in base al numero di visite
		albums.sort(function(a,b){return b.views - a.views});
		albums=albums.slice(0,10);
		
		//visualizzo i primi 10
		$('#first10albums > tbody').html('');
		for (var i=0;i<albums.length ;i++){
			var a=$('<a target="_blank"></a>');
			a.attr('href',albums[i].link);
			a.text(albums[i].title);
			var tr=$('<tr></tr>');
			tr.append($('<td></td>').append(a));
			tr.append($('<td></td>').text(albums[i].views));
			$('#first10albums > tbody').append(tr);
		}
		
	}
	
	
	function checkPhotoSize(photoSize)
	{
		var $allowedSizes = [94, 110, 128, 200, 220, 288, 320, 400, 512, 576, 640, 720, 800, 912, 1024, 1152, 1280, 1440, 1600];
		if (photoSize === "auto")
		{
			var $windowHeight = $(window).height();
			var $windowWidth = $(window).width();
			var $minSize = ($windowHeight > $windowWidth) ? $windowWidth : $windowHeight;
			for (var i = 1; i < $allowedSizes.length; i++)
			{
				if ($minSize < $allowedSizes[i])
				{
					return $allowedSizes[i - 1];
				}
			}
		}
		else
		{
			return photoSize;
		}
	}


	function GetAlbumData(settings)
	{
		// Aggiunto supporto per album id numerico
		// Pur essendo le foto dai posts un album in formato alfanumerico, va trattato come numerico (|posts)
		var numeric = settings.album.match(/^[0-9]{19}|posts$/);
		var album_type;
		if (numeric) album_type = 'albumid';
		else album_type = 'album';

		var url = strings.picasaUrl + settings.username + ((settings.album !== "") ? '/' + album_type + '/' + settings.album : "") +
			'?imgmax=d' +
			// '&kind=photo' + // https://developers.google.com/picasa-web/docs/2.0/reference#Kind
			'&alt=json' + // https://developers.google.com/picasa-web/faq_gdata#alternate_data_formats
			((settings.authKey !== "") ? "&authkey=Gv1sRg" + settings.authKey : "") +
			((settings.keyword !== "") ? "&tag=" + settings.keyword : "") +
			'&thumbsize=' + settings.thumbSize + ((settings.thumbCrop) ? "c" : "u") + "," + checkPhotoSize(settings.photoSize) +
			((settings.hasOwnProperty('StartIndex')) ? "&start-index=" + settings.StartIndex : "") +
			((settings.hasOwnProperty('MaxResults')) ? "&max-results=" + settings.MaxResults : "");


		// http://api.jquery.com/jQuery.ajax/
		$.ajax({
			'url':url,
			'dataType': 'json', // Esplicita il tipo perche' il riconoscimento automatico non funziona con Firefox
			'beforeSend':settings.beforeSend,
			'success':settings.success,
			'error':settings.error,
			'complete':settings.complete,
			'context':settings.context
		});
	}
	
	
	function OnBeforeSend(jqXHR, settings)
	{
		document.body.style.cursor = "wait";
	}
	function OnLoadViewsAndCommentsComplete(jqXHR, textStatus)
	{
		remainingphotos-=1;
		update_remainingphotos();
	}
	
	
	function OnLoadViewsAndCommentsSuccess(result, textStatus, jqXHR)
	{	
		if (typeof result.entry !== "undefined" && typeof result.entry.gphoto$viewCount !== "undefined" && typeof result.entry.gphoto$viewCount.$t !== "undefined"){
			//$('#photo_info_box .pi-views').text(result.entry.gphoto$viewCount.$t);
			//ho le viste in result.entry.gphoto$viewCount.$t
			//alert(JSON.stringify(result.entry));
			g_parameters[this.album_index].views+=parseInt(result.entry.gphoto$viewCount.$t);
			//alert(this.album_index);
			//alert(this.photo_index);
			
			var seed = result.entry.content.src.substring(0, result.entry.content.src.lastIndexOf("/"))+ "/";
			//seed = seed.substring(0, seed.lastIndexOf("/")) + "/";
			
			photos.push({
				'views':parseInt(result.entry.gphoto$viewCount.$t),
				'summary':result.entry.summary.$t,
				'title':result.entry.title.$t,
				'link':'../'+g_parameters[this.album_index].link+'&Itemid='+g_parameters[this.album_index].id+'#'+(this.photo_index+1),
				'thumb':seed+'h50/',
				'album_title':g_parameters[this.album_index].title,
				'album_link':'../'+g_parameters[this.album_index].link+'&Itemid='+g_parameters[this.album_index].id//+'&tmpl=component'
			});			
			update_photos_stats();
			update_albums_stats();
		}
	}
	function OnLoadViewsAndCommentsError(jqXHR, textStatus, error)
	{	
		console.log( jqXHR.message, textStatus, error);
	}

	function OnLoadSuccess(result, textStatus, jqXHR)
	{//alert(this.album_index);
		if (result.feed.openSearch$startIndex.$t+result.feed.openSearch$itemsPerPage.$t>=result.feed.openSearch$totalResults.$t){
		}else{
			//altra chiamata per il rimanente
			load_album_data(this.album_index,result.feed.openSearch$startIndex.$t+result.feed.openSearch$itemsPerPage.$t);
		}
		
		
		remainingphotos+=result.feed.entry.length;
		update_remainingphotos();
		for (var i = 0; i < result.feed.entry.length; ++i)
		{//alert(JSON.stringify(result.feed.entry[i]));
			if (typeof result.feed.entry[i].link !== "undefined"){
				for (var j=0;j<result.feed.entry[i].link.length;j++){
					if (result.feed.entry[i].link[j].rel=='self' && result.feed.entry[i].link[j].type=='application/atom+xml'){
						var obj={
								'album_index':this.album_index,
								'photo_index':i-1+result.feed.openSearch$startIndex.$t
						};
						$.ajax({
							'url':result.feed.entry[i].link[j].href,
							'dataType': 'json',
							'success': OnLoadViewsAndCommentsSuccess,
							'error': OnLoadViewsAndCommentsError,
							'context':obj,
							'complete':OnLoadViewsAndCommentsComplete
						});
						
						break;
					}
				}
			}
		}
		
		
		
	}

	function OnLoadError(jqXHR, textStatus, error)
	{
		console.log( jqXHR.message, textStatus, error);
	}

	function OnLoadComplete(jqXHR, textStatus)
	{
		document.body.style.cursor = "default";
		remainingphotos-=photos_per_album;
		update_remainingphotos();
		
	}

});

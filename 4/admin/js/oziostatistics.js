jQuery(document).ready(function ($)
{
	
	$('#album-info').on('hidden', function () {
		  $(this).removeData('modal');
		});
	
 	var photos=[];
 	var g_parameters=[];
 	var g_list_nano_options=[];
 	var photos_per_album=1000;
 	var remainingphotos=0;
 	var max_remainingphotos=1;
 	var strings = {
 			picasaUrl: 'index.php?option=com_oziogallery3&view=picasa&format=raw'
 		}; 	
	for (var i=0;i<g_parameters.length;i++){
		g_parameters[i].views=0;
		load_album_data(i,1);
	}
	update_albums_stats();
	for (var i=0;i<g_list_nano_options.length;i++){
		var url='';
		if (g_list_nano_options[i].kind=='picasa'){
			url = strings.picasaUrl+'&ozio_payload='+encodeURIComponent('user_id='+encodeURIComponent(g_list_nano_options[i].userID)+
			'&alt=json&kind=album&access=public&imgmax=d&thumbsize='+g_list_nano_options[i].thumbSize)+'&ozrand='+(new Date().getTime());
		}else{
			url="https://api.flickr.com/services/rest/?&method=flickr.photosets.getList&api_key=" + g_list_nano_options[i].g_flickrApiKey + "&user_id="+g_list_nano_options[i].userID+"&primary_photo_extras=url_"+g_flickrThumbSizeStr+"&format=json&jsoncallback=?";
		}
		jQuery.ajax({
			'url':url,
			'dataType': 'json', // Esplicita il tipo perche' il riconoscimento automatico non funziona con Firefox
			'beforeSend':OnNanoBeforeSend,
			'success':OnNanoSuccess,
			'error':OnNanoError,
			'complete':OnNanoComplete,
			'context':g_list_nano_options[i]
		});
	}
	
	//var a=$('<a href="http://localhost/joomlaozio3/" data-toggle="modal" data-target="#album-info">PROVAPROVAPROVAPROVAPROVAPROVAPROVAPROVAPROVAPROVAPROVAPROVAPROVAPROVAPROVA</a>');
	//a.modal({show:false});
	//$('.span10').append(a);

	
	
	function load_album_data(i,start_index){
		var obj={'album_index':i};
		remainingphotos+=photos_per_album;
		update_remainingphotos();
		
		GetAlbumData({
				//mode: 'album_data',
				username: g_parameters[i]['params']['userid'],
				album:  (!g_parameters[i]['params'].hasOwnProperty('albumvisibility') || g_parameters[i]['params']['albumvisibility'] == "public" ? g_parameters[i]['params']['gallery_id'] : g_parameters[i]['params']['limitedalbum']),
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
			a.attr('href','../'+photos[i].link);
			a.text(photos[i].title);
			
			var img=$('<img>');
			img.attr('src','../media/com_oziogallery3/views/00fuerte/img/progress.gif');
			
			var a_album=$('<a target="_blank"></a>');
			a_album.attr('href','../'+photos[i].album_link);
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
			var parti=g_parameters[i].link.split('#');
			var album_link='';
			if (parti.length>1){
				album_link=parti[0]+'&tmpl=component'+'#'+parti[1];	
			}else{
				album_link=parti[0]+'&tmpl=component';
			}
			
			
			albums.push({
				'views':g_parameters[i].views,
				'title':g_parameters[i].title,
				//'link':'../'+g_parameters[i].link+'&Itemid='+g_parameters[i].id//+'&tmpl=component'
				'link':album_link
			});
		}
		//ordino in base al numero di visite
		albums.sort(function(a,b){return b.views - a.views});
		albums=albums.slice(0,10);
		
		//visualizzo i primi 10
		$('#first10albums > tbody').html('');
		for (var i=0;i<albums.length ;i++){
			//var a=$('<a target="_blank"></a>');/
			var a=$('<a data-toggle="modal" data-target="#album-info" class="iframe_modal"></a>');
			a.attr('href','../'+albums[i].link);
			a.text(albums[i].title);
			//a.modal({show: 'false'});
			
			a.on('click', function(e) {
			    e.preventDefault();

			    $("#album-info .modal-body").height($(window).height()*0.8);
			    
			    //var url = $(this).attr('href');
			    $('#album-info .modal-body').html('<iframe width="100%" height="100%" frameborder="0" scrolling="auto" allowtransparency="true"></iframe>');
			    var src = $(this).attr('href');
			    
			    $("#album-info iframe").attr({'src':src});				
			});
			
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

		var url = strings.picasaUrl + '&ozio_payload='+encodeURIComponent('user_id='+encodeURIComponent(settings.username)+
		
				((settings.album !== "") ? '&album_id=' + encodeURIComponent(settings.album) : "") +
				
				'&imgmax=d' +
		
		
			// '&kind=photo' + // https://developers.google.com/picasa-web/docs/2.0/reference#Kind
			'&alt=json' + // https://developers.google.com/picasa-web/faq_gdata#alternate_data_formats
			((settings.authKey !== "") ? "&authkey=Gv1sRg" + settings.authKey : "") +
			((settings.keyword !== "") ? "&tag=" + settings.keyword : "") +
			'&thumbsize=' + settings.thumbSize + ((settings.thumbCrop) ? "c" : "u") + "," + checkPhotoSize(settings.photoSize) +
			((settings.hasOwnProperty('StartIndex')) ? "&start-index=" + settings.StartIndex : "") +
			((settings.hasOwnProperty('MaxResults')) ? "&max-results=" + settings.MaxResults : ""))+'&ozrand='+(new Date().getTime());


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
	//console.log(JSON.stringify(result));
		
		if (typeof result.feed !== "undefined" && typeof result.feed.gphoto$viewCount !== "undefined" && typeof result.feed.gphoto$viewCount.$t !== "undefined"){
		}else{
			result.feed.gphoto$viewCount = { $t: 0 };
		}
	
		//$('#photo_info_box .pi-views').text(result.feed.gphoto$viewCount.$t);
		//ho le viste in result.feed.gphoto$viewCount.$t
		//alert(JSON.stringify(result.feed));
		g_parameters[this.album_index].views+=parseInt(result.feed.gphoto$viewCount.$t);
		//alert(this.album_index);
		//alert(this.photo_index);
		var oz_gi_thumb_url = result.feed.media$group.media$thumbnail[0].url;
		var seed = oz_gi_thumb_url.substring(0, oz_gi_thumb_url.lastIndexOf("/"));
		seed = seed.substring(0, seed.lastIndexOf("/")) + "/";
		
		
		var photodata={
				'views':parseInt(result.feed.gphoto$viewCount.$t),
				'summary':result.feed.media$group.media$description.$t,
				'title':result.feed.title.$t,
				'link':'#',
				'thumb':seed+'h50/',
				'album_title':g_parameters[this.album_index].title,
				'album_link':g_parameters[this.album_index].link
				//'album_link':'../'+g_parameters[this.album_index].link+'&Itemid='+g_parameters[this.album_index].id//+'&tmpl=component'
			};
		
		if (g_parameters[this.album_index].skin=='00fuerte'){
			photodata.link=g_parameters[this.album_index].link+'#'+(this.photo_index+1);
		}else if (g_parameters[this.album_index].skin=='lightgallery'){
			photodata.link=g_parameters[this.album_index].link+'#lg=1&slide='+this.photo_index;
		}else if (g_parameters[this.album_index].skin=='jgallery'){
			photodata.link=g_parameters[this.album_index].link+'/'+result.feed.gphoto$id.$t;
		}else{
			//nano
			photodata.link=g_parameters[this.album_index].link+'/'+result.feed.gphoto$id.$t;
		}
		
		photos.push(photodata);			
		update_photos_stats();
		update_albums_stats();
		
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
						//https://picasaweb.google.com/data/entry/api/user/114390094116699707674/albumid/6386889168349962385/photoid/6386889168751437810?alt=json
						var parti=result.feed.entry[i].link[j].href.split('/');
						
						var obj_parti = {};
						
						for (var p=0;p<parti.length;p++){
							if (parti[p]=='user'){
								obj_parti.user = parti[p+1];
								p++;
							}else if (parti[p]=='albumid'){
								obj_parti.albumid = parti[p+1];
								p++;
							}else if (parti[p]=='photoid'){
								var photoid = parti[p+1].split('?');
								obj_parti.photoid = photoid[0];
								p++;
							}
						}
						
						
						
						
						$.ajax({
							'url':strings.picasaUrl+'&ozio_payload='+encodeURIComponent('user_id='+encodeURIComponent(obj_parti.user)+'&album_id='+encodeURIComponent(obj_parti.albumid)+'&photo_id='+encodeURIComponent(obj_parti.photoid))+'&ozrand='+(new Date().getTime()),
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

	
	

	
	/*
	 * Nano
	 */
	function OnNanoBeforeSend(jqXHR, settings)
	{
		//document.body.style.cursor = "wait";
	}

	function OnNanoSuccess(data, textStatus, jqXHR)
	{
		var context=this;
		if (context.kind=='picasa'){
			//picasa
		    jQuery.each(data.feed.entry, function(i,data){
		        var filename='';
		        
		        //Get the title 
		        var itemTitle = data.media$group.media$title.$t;

		        //Get the URL of the thumbnail
		        var itemThumbURL = data.media$group.media$thumbnail[0].url;

		        //Get the ID 
		        var itemID = data.gphoto$id.$t;
		        
		        //Get the description
		        var imgUrl=data.media$group.media$content[0].url;
		        var ok=false;
		        if( context.album !== undefined && context.album.length>0){
		        	ok= (context.album==itemID);
		        }else{
		        	ok=CheckAlbumName(itemTitle,context);
		        }

		        if( ok ) {
		        		var deeplink='';
		        		if (context.locationHash){
		        			deeplink='#nanogallery/nanoGallery/'+itemID;
		        		}
						
						var nextI=g_parameters.length;
						g_parameters[nextI]={};
						
						jQuery.extend(g_parameters[nextI],context);
						g_parameters[nextI].skin='nano';
						g_parameters[nextI].views=0;
						g_parameters[nextI].link=context.album_local_url+deeplink;
						g_parameters[nextI].title=itemTitle;
						g_parameters[nextI].params={
							'userid':context.userID,
							'albumvisibility':'public',
							'gallery_id':itemID
						};
						load_album_data(nextI,1);
		        }
		        
		      });				
		}else{
			//flickr
			if( data.stat !== undefined ) {
			      if( data.stat === 'fail' ) {
			        alert("Could not retrieve Flickr photoset list: " + data.message + " (code: "+data.code+").");
			        return;
			      }
			}
		    jQuery.each(data.photosets.photoset, function(i,item){
		          //Get the title 
		          itemTitle = item.title._content;
		          itemID=item.id;
		          //Get the description
		          itemDescription='';
		          if (item.description._content != undefined) {
		            itemDescription=item.description._content;
		          }

		          //itemThumbURL = "http://farm" + item.farm + ".staticflickr.com/" + item.server + "/" + item.primary + "_" + item.secret + "_"+g_flickrThumbSize+".jpg";
		          itemThumbURL=item.primary_photo_extras['url_'+g_flickrThumbSizeStr];
		          var ok=false;
		          if( context.photoset !== undefined && context.photoset.length>0){
		        	ok= (context.photoset==itemID);
		          }else{
		        	ok=CheckAlbumName(itemTitle,context);
		          }

		          if( ok ) {
		        	 //aggiungi l'album
		        		var deeplink='';
		        		if (context.locationHash){
		        			deeplink='#nanogallery/nanoGallery/'+itemID;
		        		}
						var nextI=g_parameters.length;
						g_parameters[nextI]={};
						jQuery.extend(g_parameters[nextI],context);
						g_parameters[nextI].skin='nano';
						g_parameters[nextI].views=0;
						g_parameters[nextI].link=context.album_local_url+deeplink;
						g_parameters[nextI].title=itemTitle;
						g_parameters[nextI].photoset_id=itemID;
						load_album_flickr_data(nextI);
		          }
             });
			
		}
		
	}

	function OnNanoError(jqXHR, textStatus, error)
	{
	}

	function OnNanoComplete(jqXHR, textStatus)
	{
		//document.body.style.cursor = "default";
	}
	
	
	/*
	 * Nano Flickr
	 */
	function load_album_flickr_data(i){
		var obj={'album_index':i};
		url = "https://api.flickr.com/services/rest/?&method=flickr.photosets.getPhotos&api_key=" + g_parameters[i].g_flickrApiKey + "&photoset_id="+g_parameters[i].photoset_id+"&extras=exif,date_taken,tags,machine_tags,geo,description,views,url_o,url_z,url_t,url_sq&format=json&jsoncallback=?";
		$.ajax({
			'url':url,
			'dataType': 'json', // Esplicita il tipo perche' il riconoscimento automatico non funziona con Firefox
			'beforeSend':OnNanoFlickrBeforeSend,
			'success':OnNanoFlickrSuccess,
			'error':OnNanoFlickrError,
			'complete':OnNanoFlickrComplete,
			'context':obj
		});
		
	}	
	function OnNanoFlickrBeforeSend(jqXHR, settings)
	{
		//document.body.style.cursor = "wait";
	}

	function OnNanoFlickrSuccess(data, textStatus, jqXHR)
	{
		
		var obj=this;
		remainingphotos+=data.photoset.photo.length;
		update_remainingphotos();
	   jQuery.each(data.photoset.photo, function(i,item){
				remainingphotos-=1;
				update_remainingphotos();
				//OnLoadedEntry(result.feed.entry[i],obj);
				

				var thumb=item['url_sq'];

				var photolink='';
				photolink=g_parameters[obj.album_index].link+'/'+item.id;
				
				var oziodata={
					'album_title':g_parameters[obj.album_index].title,
					'album_link':g_parameters[obj.album_index].link,//+'&tmpl=component'
					//'albumid':obj.album_index,
					'summary':item.description._content,
					'title':item.title,
					'link':photolink,
					'thumb':thumb,
					'views':0
				};
				if (typeof item.views !== "undefined"){
					oziodata.views=parseInt(item.views);
				}
				//oziodata.views+=100000;//TODO togliere
				
				g_parameters[obj.album_index].views+=oziodata.views;
				photos.push(oziodata);
				update_photos_stats();
				update_albums_stats();

			      
	   });		
	}

	function OnNanoFlickrError(jqXHR, textStatus, error)
	{
	}

	function OnNanoFlickrComplete(jqXHR, textStatus)
	{
		//document.body.style.cursor = "default";
	}	
	
	  // check album name - blackList/whiteList
	  function CheckAlbumName(title,g_options) {
		var g_blackList=null;
		var g_whiteList=null;
		var g_albumList=null;
	    if( g_options.blackList !='' ) { g_blackList=g_options.blackList.toUpperCase().split('|'); }
	    if( g_options.whiteList !='' ) { g_whiteList=g_options.whiteList.toUpperCase().split('|'); }
	    if( g_options.albumList && g_options.albumList !='' ) { g_albumList=g_options.albumList.toUpperCase().split('|'); }
	  
	    var s=title.toUpperCase();

	    if( g_albumList !== null ) {
	      for( var j=0; j<g_albumList.length; j++) {
	        if( s == g_albumList[j].toUpperCase() ) {
	          return true;
	        }
	      }
	    }
	    else {
	      var found=false;
	      if( g_whiteList !== null ) {
	        //whiteList : authorize only album cointaining one of the specified keyword in the title
	        for( var j=0; j<g_whiteList.length; j++) {
	          if( s.indexOf(g_whiteList[j]) !== -1 ) {
	            found=true;
	          }
	        }
	        if( !found ) { return false; }
	      }


	      if( g_blackList !== null ) {
	        //blackList : ignore album cointaining one of the specified keyword in the title
	        for( var j=0; j<g_blackList.length; j++) {
	          if( s.indexOf(g_blackList[j]) !== -1 ) { 
	            return false;
	          }
	        }
	      }
	      
	      return true;
	    }
	  }				
	
});

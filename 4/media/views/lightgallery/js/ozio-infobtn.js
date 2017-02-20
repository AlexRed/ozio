(function($, window, document, undefined) {

    'use strict';

    var defaults = {
		infobtn: true,
		photoData: {}
    };

    /**
     * Creates the autoplay plugin.
     * @param {object} element - lightGallery element
     */
    var OzioInfo = function(element) {

        this.core = $(element).data('lightGallery');

        this.$el = $(element);

        this.core.s = $.extend({}, defaults, this.core.s);

        this.init();

        return this;
    };

    OzioInfo.prototype.init = function() {
        var _this = this;

        // append autoplay controls
        if (_this.core.s.infobtn) {
            _this.infobtnAdd();
        }


    };



    // Manage autoplay via play/stop buttons
    OzioInfo.prototype.infobtnAdd = function() {
        var _this = this;
        var _html = '<span class="lg-ozio-infobtn-button lg-icon"><i class="fa fa-info"></i></span>';

        // Append autoplay controls
        $('.lg-toolbar').append(_html);

        _this.core.$outer.find('.lg-ozio-infobtn-button').on('click.lg', function() {
			//visualizzo il popup
			_this.showInfoBox();
        });
    };

	OzioInfo.prototype.lightgallery_gi_linkify = function(inputText) {
		var replacedText, replacePattern1, replacePattern2, replacePattern3;

		//URLs starting with http://, https://, or ftp://
		replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
		replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank">$1</a>');

		//URLs starting with "www." (without // before it, or it'd re-link the ones done above).
		replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
		replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>');

		//Change email addresses to mailto:: links.
		replacePattern3 = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim;
		replacedText = replacedText.replace(replacePattern3, '<a href="mailto:$1">$1</a>');

		return replacedText;
	};

	OzioInfo.prototype.showInfoBox = function() {
        var _this = this;
		
		var current=_this.core.$slide.eq(_this.core.index).find('.lg-img-wrap > img').attr( 'src' );
		if (!_this.core.s.photoData.hasOwnProperty(current)){
			return;
		}
		
		var photoData=_this.core.s.photoData[current];
		
		var gO=_this.core.s;
		var g_i18nTranslations=gO.i18n;
		var $gE=_this;
		
		
			var lat=photoData.lat;
			var lng=photoData.lng;
			
			var info_box_css='ozio-lightgallery-white-info-box-with-gmap';
			if (lat=='' || lng==''){
				info_box_css='ozio-lightgallery-white-info-box';
			}
	  
			 var html='';
			 html+='<div  class="LightGalleryInfoBox '+info_box_css+' mfp-hide">';
			 html+='<div class="ozio-lightgallery-infobox-middle">';
			 html+='	<dl class="odl-horizontal">';
			 html+=' 		<dt></dt><dd><img class="oimg-polaroid pi-image" alt="preview"/></dd>';
			 if (gO.showInfoBoxAlbum){
				 html+=' 		<dt>'+g_i18nTranslations.infoBoxAlbum+'</dt><dd class="pi-album"></dd>';
			 }
			 if (gO.showInfoBoxPhoto){
			 html+='	<dt>'+g_i18nTranslations.infoBoxPhoto+'</dt><dd class="pi-photo"></dd>';
			 }
			 if (gO.showInfoBoxDate){
			 html+='					<dt>'+g_i18nTranslations.infoBoxDate+'</dt><dd class="pi-date"></dd>';
			 }
			 if (gO.showInfoBoxDimensions){
			 html+='					<dt>'+g_i18nTranslations.infoBoxDimensions+'</dt><dd class="pi-dimensions"></dd>';
			 }
			 if (gO.showInfoBoxFilename){
			 html+='					<dt>'+g_i18nTranslations.infoBoxFilename+'</dt><dd class="pi-filename"></dd>';
			 }
			 if (gO.showInfoBoxFilesize){
			 html+='					<dt>'+g_i18nTranslations.infoBoxFileSize+'</dt><dd class="pi-filesize"></dd>';
			 }
			 if (gO.showInfoBoxCamera){
			 html+='					<dt>'+g_i18nTranslations.infoBoxCamera+'</dt><dd class="pi-camera"></dd>';
			 }
			 if (gO.showInfoBoxFocallength){
			 html+='					<dt>'+g_i18nTranslations.infoBoxFocalLength+'</dt><dd class="pi-focallength"></dd>';
			 }
			 if (gO.showInfoBoxExposure){
			 html+='					<dt>'+g_i18nTranslations.infoBoxExposure+'</dt><dd class="pi-exposure"></dd>';
			 }
			 if (gO.showInfoBoxFNumber){
			 html+='					<dt>'+g_i18nTranslations.infoBoxFNumber+'</dt><dd class="pi-fnumber"></dd>';
			 }
			 if (gO.showInfoBoxISO){
			 html+='					<dt>'+g_i18nTranslations.infoBoxISO+'</dt><dd class="pi-iso"></dd>';
			 }
			 if (gO.showInfoBoxMake){
			 html+='					<dt>'+g_i18nTranslations.infoBoxMake+'</dt><dd class="pi-make"></dd>';
			 }
			 if (gO.showInfoBoxFlash){
			 html+='					<dt>'+g_i18nTranslations.infoBoxFlash+'</dt><dd class="pi-flash"></dd>';
			 }
			 if (gO.showInfoBoxViews){
			 html+='					<dt>'+g_i18nTranslations.infoBoxViews+'</dt><dd class="pi-views"></dd>';
			 }
			 if (gO.showInfoBoxComments){
			 html+='					<dt>'+g_i18nTranslations.infoBoxComments+'</dt><dd class="pi-comments"></dd>';
			 }
			 html+='				</dl>';
			 html+='				<div class="pi-map-container"></div>';
			 html+='				</div>';
			 html+='					<div class="pi-photo-buttons">';
			 if (gO.showInfoBoxLink){
			 html+='					<a href="#" class="btn pi-link" target="_blank">';
			 html+='						↗ Google+';
			 html+='					</a>';
			 }
			 if (gO.showInfoBoxDownload){
			 html+='					<a href="#" class="btn pi-download">';
			 html+='						⬇ Download';
			 html+='					</a>';
			 }
			 html+='					</div>';
					
			  if ($gE.conInfoBox){
			  }else{
					$gE.conInfoBox=jQuery(html).appendTo('body');
			  }
			  
			  $gE.conInfoBox.css('background-image','url(\''+gO.infoboxBgUrl+'\')');

		  $gE.conInfoBox.find('.pi-album').text(photoData.album);
		  $gE.conInfoBox.find('.pi-photo').text(photoData.photo);
		  
		  $gE.conInfoBox.find('.pi-photo').html(_this.lightgallery_gi_linkify($gE.conInfoBox.find('.pi-photo').html()));
		  
		  $gE.conInfoBox.find('.pi-date').text(photoData.date);
		  $gE.conInfoBox.find('.pi-dimensions').text(photoData.dimensions);
		  $gE.conInfoBox.find('.pi-filename').text(photoData.filename);
		  $gE.conInfoBox.find('.pi-filesize').text(photoData.filesize);
		  $gE.conInfoBox.find('.pi-camera').text(photoData.camera);
		  $gE.conInfoBox.find('.pi-focallength').text(photoData.focallength);
		  $gE.conInfoBox.find('.pi-exposure').text(photoData.exposure);
		  $gE.conInfoBox.find('.pi-fnumber').text(photoData.fnumber);
		  $gE.conInfoBox.find('.pi-iso').text(photoData.iso);
		  $gE.conInfoBox.find('.pi-make').text(photoData.make);
		  $gE.conInfoBox.find('.pi-flash').text(photoData.flash);
		  $gE.conInfoBox.find('.pi-views').text(photoData.views);
		  $gE.conInfoBox.find('.pi-comments').text(photoData.comments);
		  $gE.conInfoBox.find('.pi-image').attr('src',photoData.image);
		  $gE.conInfoBox.find('.pi-link').attr('href',photoData.link);
		  $gE.conInfoBox.find('.pi-download').attr('href',photoData.download);
		  
		  jQuery.magnificPopup.open({
			items: {
			  src: $gE.conInfoBox, // can be a HTML string, jQuery object, or CSS selector
			  type: 'inline',
			  closeBtnInside: true,
			  showCloseBtn: true,
			  enableEscapeKey: true,
			  modal: true
			},
			callbacks: {
				open: function(){
					var highest_index=100000;
					//var highest_index=getHighestZIndex( $gE.conVw );
					var $mfpwrap= $gE.conInfoBox.closest('.mfp-wrap');
					var $mfpbg= jQuery('.mfp-bg');
					var $mfpcontent= $gE.conInfoBox.closest('.mfp-content');
					var $mfppreloader=$mfpwrap.find('.mfp-preloader');
					var $mfpclose=$gE.conInfoBox.find('.mfp-close');
					
					$mfpbg.css('z-index',highest_index+1);
					$mfpwrap.css('z-index',highest_index+2);
					$mfppreloader.css('z-index',highest_index+3);
					$mfpcontent.css('z-index',highest_index+4);
					$mfpclose.css('z-index',highest_index+5);
					
					var lat=photoData.lat;
					var lng=photoData.lng;
					if (lat=='' || lng==''){
						$gE.conInfoBox.find('.pi-map-container').html('');
					}else if (typeof google === 'object' && typeof google.maps === 'object'){
						$gE.conInfoBox.find('.pi-map-container').html('<span id="nano-gmap-viewer" style="width:100%; height:400px;"></span>');
						var latLng = new google.maps.LatLng(lat,lng);

						 var map = new google.maps.Map(document.getElementById('nano-gmap-viewer'), {
							zoom: 14,
							center: latLng,
							mapTypeId: google.maps.MapTypeId.MAP,
							scrollwheel: false
						 });	
						 var marker = new google.maps.Marker({
								position: latLng
							});

						 marker.setMap(map);				     
					}	
					var json_details_url=photoData.json_details;
					if (json_details_url!=''){

						var parti=json_details_url.split('/');
						
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
						
						$gE.conInfoBox.find('.pi-views').text('...');
						$gE.conInfoBox.find('.pi-comments').text('...');
						jQuery.ajax({
							
							'url':gO.picasaUrl+'&ozio_payload='+encodeURIComponent('user_id='+encodeURIComponent(obj_parti.user)+'&album_id='+encodeURIComponent(obj_parti.albumid)+'&photo_id='+encodeURIComponent(obj_parti.photoid)),
							
							'dataType': 'json',
							'success': function (result, textStatus, jqXHR){
								if ($gE.conInfoBox!=null){
									if (typeof result.feed !== "undefined" && typeof result.feed.gphoto$commentCount !== "undefined" && typeof result.feed.gphoto$commentCount.$t !== "undefined"){
										$gE.conInfoBox.find('.pi-comments').text(result.feed.gphoto$commentCount.$t);
									}else{
										$gE.conInfoBox.find('.pi-comments').text('-na-');
									}
		
									if (typeof result.feed !== "undefined" && typeof result.feed.gphoto$viewCount !== "undefined" && typeof result.feed.gphoto$viewCount.$t !== "undefined"){
										$gE.conInfoBox.find('.pi-views').text(result.feed.gphoto$viewCount.$t);
									}else{
										$gE.conInfoBox.find('.pi-views').text('-na-');
									}
								}
							},
							'error': function (jqXHR, textStatus, error){
								$gE.conInfoBox.find('.pi-views').text('-na-');
								$gE.conInfoBox.find('.pi-comments').text('-na-');
							}
						});
					}else{
						$gE.conInfoBox.find('.pi-views').text('-na-');
						$gE.conInfoBox.find('.pi-comments').text('-na-');
					}
				},
				afterClose: function() {
					//$gE.conInfoBox.remove();
					//$gE.conInfoBox=null;
					//jQuery('div.LightGalleryInfoBox').remove();
				}
			  }	    
		  });	  
		
		
	},	

    OzioInfo.prototype.destroy = function() {

    };

    $.fn.lightGallery.modules.ozioinfo = OzioInfo;

})(jQuery, window, document);

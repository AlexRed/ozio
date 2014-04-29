/**!
 * @preserve nanoGALLERY v4.3.0
 * Plugin for jQuery by Christophe Brisbois
 * Demo: http://nanogallery.brisbois.fr
 * Sources: https://github.com/Kris-B/nanoGALLERY
 * 
 * External components:
 *  - jQuery (http://www.jquery.com)
 *  - jQuery Color plugin - is embedded
 *  - Transit (http://ricostacruz.com/jquery.transit) - optional
 *  - imagesloaded (https://github.com/desandro/imagesloaded) - optional
 *  - fancybox (http://fancyapps.com) - optional
 *  - http://closure-compiler.appspot.com/home - used to minimize the code
 */


/*

nanoGALLERY v4.3.0 release notes.

##### New features:
- new image display possibilities giving a larger area to the images (customizable position of navigation buttons and labels)
- set the maximum length of title and description to avoid too long content
- display or hide the icons of the thumbnails label and/or navigation breadcrumb
- breadcrumb: new icon for home folder
- sorting of photos and of albums
- preload also previous image
- added Text-Shadow attribute to color schemes
- refinement of the 'light' theme
- new thumbnail hover effects
- added support of Picasa/Google+ albums that are limited to people who have a link with an authkey

##### New options:
- **viewerToolbar**: Display options for toolbar of the viewer (navigation buttons and captions)
	*object; Default: `{position:'bottom', style:'innerImage'}`*
	**position** : Position of the viewer toolbar (possible values: `top`, `bottom`)
	*string; Default: `bottom`*
	**style** : style of the toolbar (possible values: `innerImage`, `stuckImage`, `fullWidth`)
	*string; Default: `innerImage`*
- **thumbnailLabel**: new parameters `titleMaxLength`, `descriptionMaxLength`, `hideIcons` and 'align'
- **galleryToolbarHideIcons**: display or not the icons in the navigation breadcrumb
- **photoSorting**: sort photo albums (possible values: `standard`, `reversed`, `random`) (Flickr/Picasa/Google+)
	*string; Default: `standard`*
- **albumSorting**: sort photos in albums (possible values: `standard`, `reversed`, `random`) (Flickr/Picasa/Google+)
	*string; Default: `standard`*
- **thumbnailHoverEffect**:	new possible values: `labelSplitVert`, `labelSplit4`, `labelAppearSplitVert`, `labelAppearSplit4`, `imageSplitVert`, `imageSplit4`
  
**Visit nanoGALLERY homepage for usage details: [http://nanogallery.brisbois.fr](http://www.nanogallery.brisbois.fr/)**

##### Deprecated options:
- none

##### Misc
- CSS: renamed 'container' to 'nanoGalleryContainerParent'
- remove support of jQuery-JSONP
- bufix incorrect label display under the thumbnail
- minor bugfixes

**Contributors: Giovanni Chiodi and AlexRed --> many thanks!**

*/
 

 
// ##########################################
// ##### nanoGALLERY as a JQUERY PLUGIN #####
// ##########################################

(function ($) {
  jQuery.fn.nanoGallery = function (options) {
    var settings = $.extend(true, {
      // default settings
      userID:'',
      kind:'',
      album:'',
      photoset:'',
      blackList:'scrapbook|profil',
      whiteList:'',
      albumList:'',
      galleryToolbarWidthAligned:true,
      galleryToolbarHideIcons:false,
      displayBreadcrumb:false,
      theme:'default',
      colorScheme:'none',         //'default',
      colorSchemeViewer:'none',   //'default',
      items:null,
      itemsBaseURL:'',
      maxItemsPerLine:0,
      paginationMaxItemsPerPage:0,
      paginationMaxLinesPerPage:0,
      maxWidth:0,
      viewer:'internal',
      viewerDisplayLogo:false,
      viewerToolbar:{position:'bottom', style:'innerImage'},
      thumbnailWidth:230,
      thumbnailHeight:154,
      thumbnailHoverEffect:null,
      thumbnailLabel:{position:'overImageOnBottom',display:true,displayDescription:true, titleMaxLength:0, descriptionMaxLength:0, hideIcons:false},
      thumbnailDisplayInterval:30,
      thumbnailDisplayTransition:true,
      thumbnailLazyLoad:false,
      thumbnailLazyLoadTreshold:100,
      touchAnimation:true,
      useTags:false,
      preset:'none',
      locationHash:false,
      slideshowDelay:3000,
      thumbnailGlobalImageTitle:'',
      thumbnailGlobalAlbumTitle:'',
      photoSorting:'',
      albumSorting:'',
      i18n:{
        'paginationPrevious':'Previous','paginationPrevious_FR':'Pr�c�dent','paginationPrevious_DE':'Zur�ck','paginationPrevious_IT':'Indietro',
        'paginationNext':'Next','paginationNext_FR':'Suivant','paginationNext_DE':'Weiter','paginationNext_IT':'Avanti',
        'thumbnailImageTitle':'',
        'thumbnailAlbumTitle':'',
        'thumbnailImageDescription':'',
        'thumbnailAlbumDescription':'',
        
        'infoBoxPhoto':'Photo',
        'infoBoxDate':'Date',
        'infoBoxAlbum':'Album',
        'infoBoxDimensions':'Dimensions',
        'infoBoxFilename':'Filename',
        'infoBoxFileSize':'File size',
        'infoBoxCamera':'Camera',
        'infoBoxFocalLength':'Focal length',
        'infoBoxExposure':'Exposure',
        'infoBoxFNumber':'F Number',
        'infoBoxISO':'ISO',
        'infoBoxMake':'Make',
        'infoBoxFlash':'Flash',
        'infoBoxViews':'Views',
        'infoBoxComments':'Comments'
        
        }
    }, options );
    
    return this.each(function() {
      var nanoGALLERY_obj = new nanoGALLERY();
      nanoGALLERY_obj.Initiate(this,settings);
    });
  };
}( jQuery ));


// ##############################
// ##### nanoGALLERY script #####
// ##############################

function nanoGALLERY() {
    var g_options=null,
    g_baseControl=null,
    g_baseControlID=null,
    $g_containerThumbnailsParent=null,
    g_containerDemo=null,
    $g_containerThumbnails=null,
    $g_containerThumbnailsHidden=null,
    $g_containerPagination=null,
    $g_containerBreadcrumb=null,
    g_containerTags=null,
    $g_containerNavigationbar=null,
    $g_containerNavigationbarCont=null,
    g_containerNavigationbarContDisplayed=false,
    $g_containerViewerContainer=null,
    $g_containerViewer=null,
    $g_containerInfoBox=null,
    $g_ViewerImagePrevious=null,
    $g_ViewerImageNext=null,
    $g_ViewerImageCurrent=null,
    g_containerViewerDisplayed=false,
    g_containerThumbnailsDisplayed=false,
    $g_containerViewerCloseFloating=null,
    $g_containerViewerContent=null,
    $g_containerViewerToolbar=null,
    $g_containerViewerLogo=null,
    g_path2timthumb="",
    g_ngItems=[],
    g_containerOuterWidth=0,
    g_containerOuterHeight=0,
    g_oneThumbnailWidth=0,
    g_oneThumbnailWidthContainer=0,
    g_oneThumbnailHeight=0,
    g_oneThumbnailHeightContainer=0,
    g_oneThumbnailLabelTitleHeight=0,
    g_blackList=null,
    g_whiteList=null,
    g_albumList=null,
    // ### Picasa/Google+
    // square format : 32, 48, 64, 72, 104, 144, 150, 160 (cropped)
    // details: https://developers.google.com/picasa-web/docs/2.0/reference
    g_picasaThumbSize=64,
    g_picasaThumbAvailableSizes=new Array(32, 48, 64, 72, 94, 104, 110, 128, 144, 150, 160, 200, 220, 288, 320, 400, 512, 576, 640, 720, 800, 912, 1024, 1152, 1280, 1440, 1600),
    g_picasaThumbAvailableSizesCropped=' 32 48 64 72 104 144 150 160 ',
    g_picasaThumbSizeHack=true,
    // ### Flickr
    // Details: http://www.flickr.com/services/api/misc.urls.html
    g_flickrThumbSize='sq',
    g_flickrThumbAvailableSizes=new Array(75,100,150,240,500,640),        //,1024),
    g_flickrThumbAvailableSizesStr=new Array('sq','t','q','s','m','z'),    //,'b'), --> b is not available for photos before 05.25.2010
    g_flickrPhotoSize='sq',
    g_flickrPhotoAvailableSizes=new Array(75,100,150,240,500,640,100000),
    g_flickrPhotoAvailableSizesStr=new Array('sq','t','q','s','m','z','o'),
    g_flickrApiKey="2f0e634b471fdb47446abcb9c5afebdc",
    g_galleryItemsCount=0,
    g_playSlideshow=false,
    g_playSlideshowTimerID=0,
    g_slideshowDelay=3000,
    g_thumbnailDisplayInterval=30,
    g_thumbnailLazyLoadTreshold=100,
    g_supportFullscreenAPI=false,
    g_viewerIsFullscreen=false,
    g_supportTransit=false,
    g_i18nLang='';
    var g_i18nTranslations={'paginationPrevious':'Previous', 'paginationNext':'Next', 'breadcrumbHome':'', 'thumbnailImageTitle':'', 'thumbnailAlbumTitle':'', 'thumbnailImageDescription':'', 'thumbnailAlbumDescription':'' };
    var lastImageChange=0,
    g_paginationLinesMaxItemsPossiblePerLine=1,
    g_paginationMaxItemsPerPage=0,
    g_paginationMaxLinesPerPage=0,
    g_thumbnailHoverEffect=[],
    g_lastOpenAlbumID=-1,
    g_lastLocationHash='',
    g_viewerImageIsChanged=false,
    g_viewerResizeTimerID=-1,
    g_viewerCurrentItemIdx=-1,
    g_albumIdxToOpenOnViewerClose=-1,
    g_custGlobals={},
    g_touched=false,
    g_previous_touched;
 
 
    
  // Color schemes - Gallery
  var g_colorScheme_default = {
    navigationbar : { background:'none', borderTop:'1px solid #555', borderBottom:'1px solid #555', borderRight:'', borderLeft:'', color:'#ccc', colorHover:'#fff' },
    thumbnail : { background:'#000', border:'1px solid #000', labelBackground:'rgba(34, 34, 34, 0.75)', titleColor:'#eee', titleShadow:'', descriptionColor:'#ccc', descriptionShadow:''}
  };
  var g_colorScheme_darkRed = {
    // #ffa3a3 #ff7373 #ff4040 #ff0000 #a60000
    navigationbar : { background:'#a60000', border:'1px dotted #ff0000', color:'#ccc', colorHover:'#fff' },
    thumbnail : { background:'#a60000', border:'1px solid #ff0000', labelBackground:'rgba(134, 0, 0, 0.75)', titleColor:'#eee', titleShadow:'', descriptionColor:'#ccc', descriptionShadow:''}
  };
  var g_colorScheme_darkGreen = {
    // #97e697 #67e667 #39e639 #00cc00 #008500
    navigationbar : { background:'#008500', border:'1px dotted #00cc00', color:'#ccc', colorHover:'#fff' },
    thumbnail : { background:'#008500', border:'1px solid #00cc00', labelBackground:'rgba(0, 105, 0, 0.75)', titleColor:'#eee', titleShadow:'', descriptionColor:'#ccc', descriptionShadow:''}
  };
  var g_colorScheme_darkBlue = {
    // #a0b0d7 #7080d7 #4a60d7 #162ea2 #071871
    navigationbar : { background:'#071871', border:'1px dotted #162ea2', color:'#ccc', colorHover:'#fff' },
    thumbnail : { background:'#071871', border:'1px solid #162ea2', labelBackground:'rgba(7, 8, 81, 0.75)', titleColor:'#eee', titleShadow:'', descriptionColor:'#ccc', descriptionShadow:''}
  };
  var g_colorScheme_darkOrange = {
    // #ffd7b7 #ffd773 #ffc840 #ffb600 #a67600
    navigationbar : { background:'#a67600', border:'1px dotted #ffb600', color:'#ccc', colorHover:'#fff' },
    thumbnail : { background:'#a67600', border:'1px solid #ffb600', labelBackground:'rgba(134, 86, 0, 0.75)', titleColor:'#eee', titleShadow:'', descriptionColor:'#ccc', descriptionShadow:''}
  };
  var g_colorScheme_light = {
    navigationbar : { background:'none', borderTop:'1px solid #ddd', borderBottom:'1px solid #ddd', borderRight:'', borderLeft:'', color:'#777', colorHover:'#eee' },
    thumbnail : { background:'#fff', border:'1px solid #fff', labelBackground:'rgba(60, 60, 60, 0.75)', titleColor:'#fff', titleShadow:'none', descriptionColor:'#eee', descriptionShadow:'none'}  };
  var g_colorScheme_lightBackground = {
    navigationbar : { background:'none', border:'', color:'#000', colorHover:'#444' },
    thumbnail : { background:'#000', border:'1px solid #fff', labelBackground:'rgba(170, 170, 170, 0.85)', titleColor:'#fff', titleShadow:'', descriptionColor:'#eee', descriptionShadow:''}
  };


  // Color schemes - lightbox
  var g_colorSchemeViewer_default = {
    background:'#000', imageBorder:'4px solid #000', imageBoxShadow:'#888 0px 0px 0px', barBackground:'rgba(4, 4, 4, 0.7)', barBorder:'0px solid #111', barColor:'#eee', barDescriptionColor:'#aaa'
    //background:'rgba(1, 1, 1, 0.75)', imageBorder:'4px solid #f8f8f8', imageBoxShadow:'#888 0px 0px 20px', barBackground:'rgba(4, 4, 4, 0.7)', barBorder:'0px solid #111', barColor:'#eee', barDescriptionColor:'#aaa'
  };
  var g_colorSchemeViewer_darkRed = {
    // #ffa3a3 #ff7373 #ff4040 #ff0000 #a60000
    background:'rgba(1, 1, 1, 0.75)', imageBorder:'4px solid #ffa3a3', imageBoxShadow:'#ff0000 0px 0px 20px', barBackground:'#a60000', barBorder:'2px solid #111', barColor:'#eee', barDescriptionColor:'#aaa'
  };
  var g_colorSchemeViewer_darkGreen = {
    // #97e697 #67e667 #39e639 #00cc00 #008500
    background:'rgba(1, 1, 1, 0.75)', imageBorder:'4px solid #97e697', imageBoxShadow:'#00cc00 0px 0px 20px', barBackground:'#008500', barBorder:'2px solid #111', barColor:'#eee', barDescriptionColor:'#aaa'
  };
  var g_colorSchemeViewer_darkBlue = {
    // #a0b0d7 #7080d7 #4a60d7 #162ea2 #071871
    background:'rgba(1, 1, 1, 0.75)', imageBorder:'4px solid #a0b0d7', imageBoxShadow:'#162ea2 0px 0px 20px', barBackground:'#071871', barBorder:'2px solid #111', barColor:'#eee', barDescriptionColor:'#aaa'
  };
  var g_colorSchemeViewer_darkOrange = {
    // #ffd7b7 #ffd773 #ffc840 #ffb600 #a67600
    background:'rgba(1, 1, 1, 0.75)', imageBorder:'4px solid #ffd7b7', imageBoxShadow:'#ffb600 0px 0px 20px', barBackground:'#a67600', barBorder:'2px solid #111', barColor:'#eee', barDescriptionColor:'#aaa'
  };
  var g_colorSchemeViewer_light = {
    background:'rgba(187, 187, 187, 0.75)', imageBorder:'none', imageBoxShadow:'#888 0px 0px 0px', barBackground:'rgba(4, 4, 4, 0.7)', barBorder:'0px solid #111', barColor:'#eee', barDescriptionColor:'#aaa'
  };
  
  
  // class to store one item (= one thumbnail)
  var NGItems = (function () {
    var nextId = 1;   // private static --> all instances

    // constructor
    function NGItems( paramTitle, paramID ) {
      var ID=0;      // private

      // public (this instance only)
      if( paramID === undefined || paramID === null ) {
        ID=nextId++;
      }
      else {
        ID=paramID;
      }
      this.GetID = function () { return ID; };
      // public
      this.title=paramTitle;
      this.thumbsrc='';
      this.thumbWidth=0;
      this.thumbHeight=0;
      this.thumbRealWidth=0;
      this.thumbRealHeight=0;
      this.src='';
      this.width=0;
      this.height=0;
      this.description='';
      this.destinationURL='';
      this.kind='';           // image or album
      this.hovered=false;
      this.$elt=null;
      this.contentIsLoaded=false;
      this.contentLength=0;
      this.imageNumber=0;
      //if( tags.length == 0 ) {
      //  this.tags=null;
      //}
      //else {
      //  this.tags=tags.split(' ');
      //}
      this.albumID=0;
      this.customVars={};
    }
  
    // public static
    NGItems.get_nextId = function () {
      return nextId;
    };

    // public (shared across instances)
    NGItems.prototype = {
      // for future use...
      responsiveURL: function () {
    	  var container_width=0;
    	  var container_height=0;
    	  if ($g_containerViewerContent){
      		container_width=$g_containerViewerContent.width();
    		container_height=$g_containerViewerContent.height();
    	  }
    	if (container_width==0 || container_height==0){
    		container_width=jQuery(window).width();
    		container_height=jQuery(window).height();
    	}

    	var imgUrl=this.src;
    	if (typeof this.albumSource!=='undefined'){
    		if (this.albumSource=='picasa'){
    			
    			var img_h=this.imgSpecificData.img_orig_height*container_width/this.imgSpecificData.img_orig_width;
    			imgUrl=this.imgSpecificData.seed+"w"+container_width+"/";
    			if (img_h>container_height){
    				imgUrl=this.imgSpecificData.seed+"h"+container_height+"/";
    			}
    		}else if (this.albumSource=='flickr'){
    			var img_w=container_width;
    			var img_h=this.imgSpecificData.img_orig_height*container_width/this.imgSpecificData.img_orig_width;
    			if (img_h>container_height){
    				var img_h=container_height;
    				var img_w=this.imgSpecificData.img_orig_width*container_height/this.imgSpecificData.img_orig_height;
    			}
    			if (img_w<=this.imgSpecificData.img_orig_width && img_h<=this.imgSpecificData.img_orig_height){
    				var target_size=Math.max(img_w,img_h);
    				var suffix='';
    				//http://www.flickr.com/services/api/misc.urls.html
    				var flickrThumbAvailableSizes=new Array(100,240,320);
    				var flickrThumbAvailableSizesStr=new Array('t','m','n');
    				
    				for( i=0; i<flickrThumbAvailableSizes.length; i++) {
    				  if( target_size < flickrThumbAvailableSizes[i] ) {
    					suffix=flickrThumbAvailableSizesStr[i];
    					break;
    				  }
    				}
    				if (suffix!=''){
    					imgUrl=this.imgSpecificData.seed+suffix+".jpg";
    				}
    			}
    		}
    	
    	}
    	return imgUrl;    	  
    	  
    	  
    	  //vecchio
        var url='';
        switch(g_options.kind) {
          case '':
            url=this.src;
            break;
          case 'flickr':
            url=this.src;
            break;
          case 'picasa':
          default:
            url=this.src;
            break;
        }
        return url;
      }
    };
    return NGItems;
  })();

    
  // ##########################
  // ##### INITIALIZATION #####
  // ##########################
  
  this.Initiate=function( element, params ) {
    "use strict";
    g_options=params;
    g_baseControl=element;
    g_baseControlID=jQuery(element).attr('id');

    
//     deep linking support only once per page
//    if( g_options.locationHash ) {
//      alert(location.hash);
//      if( location.hash.length > 0 && location.hash.indexOf('#nanogallery/'+g_baseControlID) == 0 ) {
//        g_options.locationHash=false;
//        nanoConsoleLog('locationHash has been disabled in:' + g_baseControlID +'. This option can only be used for one nanoGALLERY per page.');
//      }
//    }
    
    if( jQuery.support.transition ) { g_supportTransit=true; }
    
    // Set theme and colorScheme
    jQuery(element).addClass('nanogallery_theme_'+g_options.theme);
    SetColorScheme(element);

    // Hide icons (thumbnails and breadcrumb)
    if( g_options.thumbnailLabel.hideIcons ) {
      var s1='.nanogallery_thumbnails_icons_off ';
      var s=s1+'.nanoGalleryContainer .nanoGalleryThumbnailContainer .labelImageTitle:before { display:none; }'+'\n';
      s+=s1+'.nanoGalleryContainer .nanoGalleryThumbnailContainer .labelFolderTitle:before { display:none; }'+'\n';
      jQuery('head').append('<style>'+s+'</style>');
      jQuery(element).addClass('nanogallery_thumbnails_icons_off');
    }
    if( g_options.galleryToolbarHideIcons ) {
      var s1='.nanogallery_breadcrumb_icons_off ';
      var s=s1+'.nanoGalleryNavigationbar .folderHome:before { display:none; }'+'\n';
      s+=s1+'.nanoGalleryNavigationbar .folder:before { display:none; }'+'\n';
      jQuery('head').append('<style>'+s+'</style>');
      jQuery(element).addClass('nanogallery_breadcrumb_icons_off');
    }


    if( g_options.thumbnailLabel.align == 'right' ) {
      var s1='.nanogallery_thumbnails_label_align_right ';
      var s=s1+'.nanoGalleryContainer .nanoGalleryThumbnailContainer .labelImage { text-align : right; }'+'\n';
      jQuery('head').append('<style>'+s+'</style>');
      jQuery(element).addClass('nanogallery_thumbnails_label_align_right');
    }

    if( g_options.thumbnailLabel.align == 'center' ) {
      var s1='.nanogallery_thumbnails_label_align_center ';
      var s=s1+'.nanoGalleryContainer .nanoGalleryThumbnailContainer .labelImage { text-align : center; }'+'\n';
      jQuery('head').append('<style>'+s+'</style>');
      jQuery(element).addClass('nanogallery_thumbnails_label_align_center');
    }
      
    if( g_options.thumbnailLabel.align == 'left' ) {
      var s1='.nanogallery_thumbnails_label_align_left ';
      var s=s1+'.nanoGalleryContainer .nanoGalleryThumbnailContainer .labelImage { text-align : left; }'+'\n';
      jQuery('head').append('<style>'+s+'</style>');
      jQuery(element).addClass('nanogallery_thumbnails_label_align_left');
    }
    
    // check parameters
    checkPluginParameters();

    // add the containers
    $g_containerNavigationbarCont=jQuery('<div class="nanoGalleryNavigationbarContainer"></div>').appendTo(element);
    $g_containerNavigationbarCont.hide();//css('visibility','hidden');
    $g_containerNavigationbar=jQuery('<div class="nanoGalleryNavigationbar"></div>').appendTo($g_containerNavigationbarCont);
    $g_containerBreadcrumb=jQuery('<div class="nanoGalleryBreadcrumb"></div>').appendTo($g_containerNavigationbar);
    $g_containerThumbnailsParent=jQuery('<div class="nanoGalleryContainerParent"></div>').appendTo(element);
    $g_containerThumbnails=jQuery('<div class="nanoGalleryContainer"></div>').appendTo($g_containerThumbnailsParent);

    var t1=jQuery('<div class="nanogalleryHideElement '+jQuery(element).attr('class')+'"></div>').appendTo('body');
    var t2=jQuery('<div class="nanoGalleryContainerParent"></div>').appendTo(t1);
    $g_containerThumbnailsHidden=jQuery('<div class="nanoGalleryContainer"></div>').appendTo(t2);

    if( g_paginationMaxItemsPerPage > 0 || g_paginationMaxLinesPerPage > 0 ) {
      $g_containerPagination=jQuery('<div class="nanoGalleryPagination"></div>').appendTo($g_containerThumbnailsParent);
      gestureGallery()
    }
    

    // i18n translations
    i18n();

    // fullscreen API support
    if( document.fullscreenEnabled || document.webkitFullscreenEnabled || document.msFullscreenEnabled || document.mozFullScreenEnabled) {
      g_supportFullscreenAPI=true;
    } else {
      nanoConsoleLog('Your browser doesn�t support the fullscreen API. Fullscreen button will not be displayed.')
    }
    
    
    retrieveThumbnailSizes();
    
    var sizeImageMax=Math.max(window.screen.width, window.screen.height);
    var si=0;
    if( g_options.thumbnailHeight == 'auto' ) {
      si=g_options.thumbnailWidth;
    }
    else if( g_options.thumbnailWidth == 'auto' ) {
        si=g_options.thumbnailHeight;
      }
      else {
        si=Math.max(g_options.thumbnailWidth,g_options.thumbnailHeight);
      }

    switch(g_options.kind) {
      
      // MARKUP / API
      case '':
        NGAddItem(g_i18nTranslations.breadcrumbHome, '', '', '', '', 'album', '', '0', '-1' );
        if( g_options.itemsBaseURL.length >0 ) {g_options.itemsBaseURL+='/';}
        if( g_options.items !== undefined && g_options.items !== null ) {
          ProcessItemOption();
          if( !ProcessLocationHash(false) ) {
            DisplayAlbum(0,false);
          }
        }
        else {
          var elements=jQuery(element).children('a');
          if( elements.length > 0 ) {
            ProcessHREF(elements);
            if( !ProcessLocationHash(false) ) {
              DisplayAlbum(0,false);
            }
          }
          else
            nanoAlert('error: no image to process.');
        }
        break;
      
      // GETSIMPLE CMS
      case 'getsimple':
        return;
        g_path2timthumb = ""; //timthumbFolder; --> for GetSimple CMS
        var url=params.pluginURL+'/nanogallery_getitems.php';
        nanoAlert(url);
        //jQuery.getJSON(url+'/nanogallery_getitems.php', {limit: 1}, function(data) {
        //jQuery.getJSON(url+'/nanogallery_getitems.php', function(data) {
        jQuery.ajaxSetup({ cache: false });
        jQuery.support.cors = true;
        jQuery.getJSON(url, function(data) {
          nanoAlert("ok");
          // data is now an array with all the images
          jQuery.each(data, function(i) {
            nanoAlert(data[i]);
            // do something with each image
            // data[i] will have the image path
          });
        });
        nanoAlert("done");
        break;
      
      // FLICKR
      case 'flickr':
        for( i=0; i<g_flickrThumbAvailableSizes.length; i++) {
          g_flickrThumbSize=g_flickrThumbAvailableSizesStr[i];
          if( si < g_flickrThumbAvailableSizes[i] ) {
            break;
          }
        }
        for( i=0; i<g_flickrPhotoAvailableSizes.length; i++) {
          g_flickrPhotoSize=g_flickrPhotoAvailableSizesStr[i];
          if( sizeImageMax < g_flickrPhotoAvailableSizes[i] ) {
            break;
          }
        }
        if( g_options.photoset.length > 0 ) {
          NGAddItem(g_i18nTranslations.breadcrumbHome, '', '', '', '', 'album', '', g_options.photoset, '-1' );
        }
        else {
          NGAddItem(g_i18nTranslations.breadcrumbHome, '', '', '', '', 'album', '', '0', '-1' );
        }
        FlickrProcessItems(0,true,-1,false);
        break;

      // PICASA/GOOGLE+
      case 'picasa':
      default:
        for(var i=0; i<g_picasaThumbAvailableSizes.length; i++) {
          g_picasaThumbSize=g_picasaThumbAvailableSizes[i];
          if( si < g_picasaThumbAvailableSizes[i] ) {
            if( g_picasaThumbAvailableSizesCropped.indexOf(' '+g_picasaThumbAvailableSizes[i]+'') >= 0 ) {
              g_picasaThumbSizeHack=false;
            }
            break;
          }
        }
        if( g_options.album.length > 0 ) {
          var p=g_options.album.indexOf('&authkey=');
          if( p >= 0 ) {
            var albumId=g_options.album.substring(0,p);
            var opt=g_options.album.substring(p);
            if( opt.indexOf('Gv1sRg') == -1 ) {
              opt='&authkey=Gv1sRg'+opt.substring(9);
            }
            var newItem=NGAddItem(g_i18nTranslations.breadcrumbHome, '', '', '', '', 'album', '', albumId, '-1' );
            newItem.customVars.authkey=opt;
          }
          else {
            NGAddItem(g_i18nTranslations.breadcrumbHome, '', '', '', '', 'album', '', g_options.album, '-1' );
          }
          
          
        }
        else {
          NGAddItem(g_i18nTranslations.breadcrumbHome, '', '', '', '', 'album', '', '0', '-1' );
        }
        //manageGalleryToolbar(0);
        PicasaProcessItems(0,true,-1,false);
        break;
    }
    
    
    // GLOBAL EVENT MANAGEMENT
    // Page resize
    var g_resizeTimeOut=0;
    jQuery(window).resize(function() { 
      if(g_resizeTimeOut) clearTimeout(g_resizeTimeOut);
      if( g_containerViewerDisplayed ) {
          ResizeInternalViewer($g_ViewerImageCurrent);
      }
      else {
        g_resizeTimeOut = setTimeout(function () {
          ResizeGallery();
          thumbnailsLazySetSrc();
          return;
        }, 50);
      }
    });
    
    // page scrolled
    var g_scrollTimeOut=0;
    jQuery(window).on('scroll', function () {
      //if(this.scrollTo) clearTimeout(this.scrollTo);
      //this.scrollTo = setTimeout(function () {
      if(g_scrollTimeOut) clearTimeout(g_scrollTimeOut);
      g_scrollTimeOut = setTimeout(function () {
        thumbnailsLazySetSrc();
        return;
      }, 200);
    });

    // Keyboard management
    jQuery(document).keyup(function(e) {
      if( g_containerViewerDisplayed ) {
        switch( e.keyCode) {
          case 27:    // Esc key
              if( g_options.locationHash ) {
                CloseInternalViewer(true);
              }
              else {
                window.history.back();
              }
            break;
          case 32:    // SPACE
          case 13:    // ENTER
            SlideshowToggle();
            break;
          case 38:    // UP
          case 39:    // RIGHT
          case 33:    // PAGE UP
            DisplayNextImagePart1();
            break;
          case 40:    // DOWN
          case 37:    // LEFT
          case 34:    // PAGE DOWN
            DisplayPreviousImage();
            break;
          case 35:    // END
          case 36:    // BEGIN
        }
      }
    });

    // browser back-button to close the image currently displayed
    jQuery(window).bind( 'hashchange', function( event ) {

      if( g_options.locationHash ) {
        ProcessLocationHash(true);
      }
      else {
        if (location.hash.length == 0) {
          CloseInternalViewer();
        }
      }
      return;
      
    });

    // play the hover out animation on thumbnail --> touchscreen
    // WARNING: this is incorrect and should not be used in this form!!!
    // TODO
    //if( g_options.touchAnimation ) {
    //  jQuery(document.body).click(function(e){
    //    event.preventDefault();
    //    ThumbnailHoverOutAll();
    //    return;
    //  });
    //}

    //jQuery("html").on({
    //  mousemove:function(e){
    //    g_touched=false;
    //  },
    //  click:updatePreviousTouched
    //});
   
    
  };

  
  function updatePreviousTouched(e){
    if(typeof g_previous_touched !== 'undefined' && g_previous_touched !== null && !g_previous_touched.is(jQuery(e.target))){
        g_previous_touched.data('clicked_once', false);
    }
    g_previous_touched = jQuery(e.target);
  }

  function gestureGallery() {
    // GESTURE --> requires HAMMER.JS
    // drag gallery left/right to display previous/next page
    if( typeof(Hammer) !== 'undefined' ) {
      var hammertimeGallery = Hammer($g_containerThumbnailsParent[0], {
        drag:true,
        transform_always_block:true,
        drag_block_horizontal: true,
        //drag_min_distance: 25,
        prevent_default:false,
        drag_lock_min_distance: 20,
        hold: false,
        release: true,
        swipe: true,
        tap: false,
        touch: true,
        transform: false
      });

      var galleryPosX=0, galleryPosY=0;
      hammertimeGallery.on('drag release', function(ev) {
        switch(ev.type) {
          case 'drag':
            galleryPosX = ev.gesture.deltaX;
            galleryPosY = ev.gesture.deltaY;
            if( Math.abs(galleryPosX)  < 25 ) {
              $g_containerThumbnailsParent.css({ 'left': 0 });  
            }
            else {
              $g_containerThumbnailsParent.css({ 'left': galleryPosX });
            }
            break;
          case 'release':
            ev.stopPropagation();
            if( Math.abs(galleryPosX)  < 25 ) {
              $g_containerThumbnailsParent.css({ 'left': 0 });  
            }
            if( galleryPosX < -25 ) {
              paginationNextPage();
            }
            if( galleryPosX > 25 ) {
              paginationPreviousPage();
            }
            galleryPosX=0;
            break;
        }
      });
    }
  }

  // CHECK PLUGIN PARAMETERS CONSISTENCY
  function checkPluginParameters() {

    if( g_options.viewer == 'fancybox' ) {
      if( typeof(jQuery.fancybox) === 'undefined' ) {
        g_options.viewer = 'internal';
        nanoConsoleLog('Fancybox could not be found. Fallback to internal viewer. Please check the file includes of the page.')
      }
    }

    if( g_options.blackList !='' ) { g_blackList=g_options.blackList.toUpperCase().split('|'); }
    if( g_options.whiteList !='' ) { g_whiteList=g_options.whiteList.toUpperCase().split('|'); }
    if( g_options.albumList !='' ) { g_albumList=g_options.albumList.toUpperCase().split('|'); }

    if( g_options.kind=='picasa' || g_options.kind=='flickr' ) {
      g_options.displayBreadcrumb=true;
    }
    if( g_options.photoset !== undefined ) {
      if( g_options.photoset.length > 0) { g_options.displayBreadcrumb=false; }
    }
    else { g_options.photoset=''; }
    if( g_options.album !== undefined ) {
      if( g_options.album.length > 0 ) { g_options.displayBreadcrumb=false; }
    }
    else { g_options.album=''; }

    if( g_options.maxWidth > 0 ) { 
      jQuery(g_baseControl).css('maxWidth',+g_options.maxWidth);
      jQuery(g_baseControl).css('margin-left','auto');
      jQuery(g_baseControl).css('margin-right','auto');
    }
  
    if( toType(g_options.slideshowDelay) == 'number' && g_options.slideshowDelay >= 2000 ) {
      g_slideshowDelay=g_options.slideshowDelay;
    }
    else {
      nanoConsoleLog('Parameter "slideshowDelay" must be an integer >= 2000 ms.');
    }

    if( toType(g_options.thumbnailDisplayInterval) == 'number' && g_options.thumbnailDisplayInterval >= 0 ) {
      g_thumbnailDisplayInterval=g_options.thumbnailDisplayInterval;
    }
    else {
      nanoConsoleLog('Parameter "thumbnailDisplayInterval" must be an integer.');
    }

    if( toType(g_options.thumbnailLazyLoadTreshold) == 'number' && g_options.thumbnailLazyLoadTreshold >= 0 ) {
      g_thumbnailLazyLoadTreshold=g_options.thumbnailLazyLoadTreshold;
    }
    else {
      nanoConsoleLog('Parameter "thumbnailLazyLoadTreshold" must be an integer.');
    }

    if( toType(g_options.paginationMaxItemsPerPage) == 'number' && g_options.paginationMaxItemsPerPage >= 0 ) {
      g_paginationMaxItemsPerPage=g_options.paginationMaxItemsPerPage;
    }
    else {
      nanoConsoleLog('Parameter "paginationMaxItemsPerPage" must be an integer.');
    }

    if( toType(g_options.paginationMaxLinesPerPage) == 'number' && g_options.paginationMaxLinesPerPage >= 0 ) {
      g_paginationMaxLinesPerPage=g_options.paginationMaxLinesPerPage;
    }
    else {
      nanoConsoleLog('Parameter "paginationMaxLinesPerPage" must be an integer.');
    }

    if( g_paginationMaxItemsPerPage > 0 && g_paginationMaxLinesPerPage > 0 ) {
      g_paginationMaxItemsPerPage=0;
    }
    
    if( g_options.thumbnailHeight == 'auto' || g_options.thumbnailWidth == 'auto' ) {
      if( g_options.paginationMaxItemsPerPage >0 ) {
        nanoConsoleLog('Parameters "paginationMaxItemsPerPage" and "thumbnailWidth/thumbnailHeight" on "auto" are not compatible.');
      }
      if( g_options.paginationMaxLinesPerPage >0 ) {
        nanoConsoleLog('Parameters "paginationMaxLinesPerPage" and "thumbnailWidth/thumbnailHeight" on "auto" are not compatible.');
      }
      g_paginationMaxItemsPerPage=0;
      g_paginationMaxLinesPerPage=0;

      if( typeof(imagesLoaded) == 'undefined' ) {
        nanoConsoleLog('Parameter "thumbnailWidth/thumbnailHeight" on "auto" requires the jQuery plugin "imagesLoaded".');
        if( g_options.thumbnailHeight == 'auto' ) {
          g_options.thumbnailHeight=100;
        }
        if( g_options.thumbnailWidth == 'auto' ) {
          g_options.thumbnailWidth=100;
        }
      }
    }

    
    // thumbnails effects
    // easing : jQuery supports only 'swing' and 'linear'
    switch( toType(g_options.thumbnailHoverEffect) ) {
      case 'string':
        var tmp=g_options.thumbnailHoverEffect.split(',');
        for(var i=0; i<tmp.length; i++) {
          g_thumbnailHoverEffect.push({'name':tmp[i],'duration':200,'durationBack':200,'easing':'swing','easingBack':'swing'});
        }
        break;
      case 'object':
        if( g_options.thumbnailHoverEffect.name != undefined ) {
          g_thumbnailHoverEffect.push(jQuery.extend({'duration':200,'durationBack':150,'easing':'swing','easingBack':'swing'},g_options.thumbnailHoverEffect));
        }
        break;
      case 'array':
        for(var i=0; i<g_options.thumbnailHoverEffect.length; i++) {
          if( g_options.thumbnailHoverEffect[i].name != undefined ) {
            g_thumbnailHoverEffect.push(jQuery.extend({'duration':200,'durationBack':150,'easing':'swing','easingBack':'swing'},g_options.thumbnailHoverEffect[i]));
          }
        }
        break;
      case 'null':
        break;
      default:
        nanoAlert('incorrect parameter for "thumbnailHoverEffect".');
    }
    
    // check consistency
    for( var i=0; i<g_thumbnailHoverEffect.length; i++) {
      switch(g_thumbnailHoverEffect[i].name ) {
        case 'slideUp':
        case 'slideDown':
        case 'slideLeft':
        case 'slideRight':
        case 'imageSlideUp':
        case 'imageSlideDown':
        case 'imageSlideLeft':
        case 'imageSlideRight':
        case 'labelAppear':
        case 'labelAppear75':
        case 'labelSlideDown':
        case 'labelSlideUp':
        case 'labelSlideUpTop':
        case 'labelOpacity50':
        case 'borderLighter':
        case 'borderDarker':
        case 'imageInvisible':
        case 'imageOpacity50':
        case 'descriptionSlideUp':
        case 'labelSplitVert':
        case 'labelSplit4':
        case 'labelAppearSplitVert':
        case 'labelAppearSplit4':
        case 'imageSplitVert':
        case 'imageSplit4':
          break;

        case 'imageScale150':
        case 'imageScale150Outside':
        case 'scale120':
        case 'overScale':
        case 'overScaleOutside':
        case 'scaleLabelOverImage':
        case 'imageFlipHorizontal':
        case 'imageFlipVertical':
        //case 'flipHorizontal':    // hover issue
        //case 'flipVertical':
        case 'rotateCornerBR':
        case 'rotateCornerBL':
        case 'imageRotateCornerBR':
        case 'imageRotateCornerBL':
          if ( !g_supportTransit ) {
            nanoConsoleLog('Parameter "'+g_thumbnailHoverEffect[i].name+'" for "thumbnailHoverEffect" requires the additional jQuery plugin "Transit".');
          }
          break;
        default:
          nanoAlert('Unknow parameter "'+g_thumbnailHoverEffect[i].name+'" for "thumbnailHoverEffect".');
          break;
      }
    }
  }  

  function i18n() {

    // browser language
    g_i18nLang = (navigator.language || navigator.userLanguage).toUpperCase();
    if( g_i18nLang === 'undefined') { g_i18nLang=''; }

    if( toType(g_options.i18n) == 'object' ){
      Object.keys(g_options.i18n).forEach(function(key) {
        switch(key) {
          //paginationPrevious
          case 'paginationPrevious_'+g_i18nLang:
            g_i18nTranslations.paginationPrevious=g_options.i18n[key];
            break;
          case 'paginationPrevious':
            g_i18nTranslations.paginationPrevious=g_options.i18n[key];
            break;

          //paginationNext
          case 'paginationNext_'+g_i18nLang:
            g_i18nTranslations.paginationNext=g_options.i18n[key];
            break;
          case 'paginationNext':
            g_i18nTranslations.paginationNext=g_options.i18n[key];
            break;

          //breadcrumbHome
          case 'breadcrumbHome_'+g_i18nLang:
            g_i18nTranslations.breadcrumbHome=g_options.i18n[key];
            break;
          case 'breadcrumbHome':
            g_i18nTranslations.breadcrumbHome=g_options.i18n[key];
            break;

          //thumbnailImageTitle
          case 'thumbnailImageTitle_'+g_i18nLang:
            g_i18nTranslations.thumbnailImageTitle=g_options.i18n[key];
            break;
          case 'thumbnailImageTitle':
            g_i18nTranslations.thumbnailImageTitle=g_options.i18n[key];
            break;
        
          //thumbnailAlbumTitle
          case 'thumbnailAlbumTitle_'+g_i18nLang:
            g_i18nTranslations.thumbnailAlbumTitle=g_options.i18n[key];
            break;
          case 'thumbnailAlbumTitle':
            g_i18nTranslations.thumbnailAlbumTitle=g_options.i18n[key];
            break;
        
          //thumbnailImageDescription
          case 'thumbnailImageDescription_'+g_i18nLang:
            g_i18nTranslations.thumbnailImageDescription=g_options.i18n[key];
            break;
          case 'thumbnailImageDescription':
            g_i18nTranslations.thumbnailImageDescription=g_options.i18n[key];
            break;
        
          //thumbnailAlbumDescription
          case 'thumbnailAlbumDescription_'+g_i18nLang:
            g_i18nTranslations.thumbnailAlbumDescription=g_options.i18n[key];
            break;
          case 'thumbnailAlbumDescription':
            g_i18nTranslations.thumbnailAlbumDescription=g_options.i18n[key];
            break;

            
            
            //gi
          case 'infoBoxPhoto_'+g_i18nLang:
            g_i18nTranslations.infoBoxPhoto=g_options.i18n[key];
            break;
          case 'infoBoxPhoto':
            g_i18nTranslations.infoBoxPhoto=g_options.i18n[key];
            break;
          case 'infoBoxDate_'+g_i18nLang:
        	g_i18nTranslations.infoBoxDate=g_options.i18n[key];
        	break;
    	  case 'infoBoxDate':
    		g_i18nTranslations.infoBoxDate=g_options.i18n[key];
    		break;
    	  case 'infoBoxAlbum_'+g_i18nLang:
    		g_i18nTranslations.infoBoxAlbum=g_options.i18n[key];
    		break;
    	  case 'infoBoxAlbum':
    		g_i18nTranslations.infoBoxAlbum=g_options.i18n[key];
    		break;
    	  case 'infoBoxDimensions_'+g_i18nLang:
    		g_i18nTranslations.infoBoxDimensions=g_options.i18n[key];
    		break;
    	  case 'infoBoxDimensions':
    		g_i18nTranslations.infoBoxDimensions=g_options.i18n[key];
    		break;
    	  case 'infoBoxFilename_'+g_i18nLang:
    		g_i18nTranslations.infoBoxFilename=g_options.i18n[key];
    		break;
    	  case 'infoBoxFilename':
    		g_i18nTranslations.infoBoxFilename=g_options.i18n[key];
    		break;
    	  case 'infoBoxFileSize_'+g_i18nLang:
    		g_i18nTranslations.infoBoxFileSize=g_options.i18n[key];
    		break;
    	  case 'infoBoxFileSize':
    		g_i18nTranslations.infoBoxFileSize=g_options.i18n[key];
    		break;
    	  case 'infoBoxCamera_'+g_i18nLang:
    		g_i18nTranslations.infoBoxCamera=g_options.i18n[key];
    		break;
    	  case 'infoBoxCamera':
    		g_i18nTranslations.infoBoxCamera=g_options.i18n[key];
    		break;
    	  case 'infoBoxFocalLength_'+g_i18nLang:
    		g_i18nTranslations.infoBoxFocalLength=g_options.i18n[key];
    		break;
    	  case 'infoBoxFocalLength':
    		g_i18nTranslations.infoBoxFocalLength=g_options.i18n[key];
    		break;
    	  case 'infoBoxExposure_'+g_i18nLang:
    		g_i18nTranslations.infoBoxExposure=g_options.i18n[key];
    		break;
    	  case 'infoBoxExposure':
    		g_i18nTranslations.infoBoxExposure=g_options.i18n[key];
    		break;
    	  case 'infoBoxFNumber_'+g_i18nLang:
    		g_i18nTranslations.infoBoxFNumber=g_options.i18n[key];
    		break;
    	  case 'infoBoxFNumber':
    		g_i18nTranslations.infoBoxFNumber=g_options.i18n[key];
    		break;
    	  case 'infoBoxISO_'+g_i18nLang:
    		g_i18nTranslations.infoBoxISO=g_options.i18n[key];
    		break;
    	  case 'infoBoxISO':
    		g_i18nTranslations.infoBoxISO=g_options.i18n[key];
    		break;
    	  case 'infoBoxMake_'+g_i18nLang:
    		g_i18nTranslations.infoBoxMake=g_options.i18n[key];
    		break;
    	  case 'infoBoxMake':
    		g_i18nTranslations.infoBoxMake=g_options.i18n[key];
    		break;
    	  case 'infoBoxFlash_'+g_i18nLang:
    		g_i18nTranslations.infoBoxFlash=g_options.i18n[key];
    		break;
    	  case 'infoBoxFlash':
    		g_i18nTranslations.infoBoxFlash=g_options.i18n[key];
    		break;
    	  case 'infoBoxViews_'+g_i18nLang:
    		g_i18nTranslations.infoBoxViews=g_options.i18n[key];
    		break;
    	  case 'infoBoxViews':
    		g_i18nTranslations.infoBoxViews=g_options.i18n[key];
    		break;
    	  case 'infoBoxComments_'+g_i18nLang:
    		g_i18nTranslations.infoBoxComments=g_options.i18n[key];
    		break;
    	  case 'infoBoxComments':
    		g_i18nTranslations.infoBoxComments=g_options.i18n[key];
    		break;            
            
        }
      });
    }
  }
  
  function ProcessLocationHash(eventLocationHash) {

    if( !g_options.locationHash ) { return false; }

    var albumID=null;
    var imageID=null;
    var curGal='#nanogallery/'+g_baseControlID+'/';
    var hash=location.hash;

    if( hash == g_lastLocationHash ) { return; }
    
    if( hash == '' ) {
      if( g_lastOpenAlbumID != -1 ) {
        // back button and no hash --> display first album
        switch(g_options.kind) {
          case '':
            g_lastLocationHash='';
            DisplayAlbum(0,false);
            return true;
            break;
          case 'flickr':
            g_lastLocationHash='';
            FlickrProcessItems(0,false,-1,false);
            return true;
            break;
          case 'picasa':
          default:
            g_lastLocationHash='';
            PicasaProcessItems(0,false,-1,false);
            return true;
            break;
        }
      }
    }
    
    if( hash.indexOf(curGal) == 0 ) {
      var s=hash.substring(curGal.length);
      var p=s.indexOf('/');

      var albumIdx=-1;
      var imageIdx=-1;
      var l=g_ngItems.length;
      
      if( p > 0 ) {
        albumID=s.substring(0,p);
        imageID=s.substring(p+1);
        for(var i=0; i<l; i++ ) {
          if( g_ngItems[i].kind == 'image' && g_ngItems[i].GetID() == imageID ) {
            imageIdx=i;
            break;
          }
        }
      }
      else {
        albumID=s;
      }
      for(var i=0; i<l; i++ ) {
        if( g_ngItems[i].kind == 'album' && g_ngItems[i].GetID() == albumID ) {
          albumIdx=i;
          break;
        }
      }

      if( imageID !== null ) {
        // process IMAGE
        if( !eventLocationHash ) {
          g_albumIdxToOpenOnViewerClose=albumIdx;
        }
        switch(g_options.kind) {
          case '':
            DisplayImage(imageIdx);
            return true;
            break;
          case 'flickr':
            if( imageIdx == -1 ) {
              FlickrProcessItems(albumIdx,false,imageID,eventLocationHash);
              return true;
            }
            else {
              DisplayImage(imageIdx);
              return true;
            }
            break;
          case 'picasa':
          default:
            if( imageIdx == -1 ) {
              PicasaProcessItems(albumIdx,false,imageID,eventLocationHash);
              return true;
            }
            else {
              DisplayImage(imageIdx);
              return true;
            }
            break;
        }
      }
      else {
        // process ALBUM
        switch(g_options.kind) {
          case '':
            DisplayAlbum(albumIdx,false);
            return true;
            break;
          case 'flickr':
            FlickrProcessItems(albumIdx,false,-1,eventLocationHash);
            return true;
          case 'picasa':
          default:
            PicasaProcessItems(albumIdx,false,-1,eventLocationHash);
            return true;
        }
      }
    }
  
    //return {albumID:albID, imageID:imgID};
  }

  // build a dummy thumbnail to get different sizes
  function retrieveThumbnailSizes() {
    g_ngItems=[];


    var item=NGAddItem('dummy', '//:0', '//:0', '', '', 'image', '', '1', '0' );
    var $newDiv=thumbnailBuild(item, 0, 0, false);

    g_containerOuterWidth=$newDiv.find('.imgContainer').outerWidth(true)-$newDiv.find('.imgContainer').width();     //+$newDiv.outerWidth(true)-$newDiv.width();
    g_containerOuterHeight=$newDiv.find('.imgContainer').outerHeight(true)-$newDiv.find('.imgContainer').height();  //+$newDiv.outerHeight(true)-$newDiv.height();

    if( g_options.thumbnailWidth != 'auto' ) {
      g_oneThumbnailWidth=$newDiv.find('.imgContainer').outerWidth(true);
    }
    if( g_options.thumbnailHeight != 'auto' ) {
      g_oneThumbnailHeight=$newDiv.find('.imgContainer').outerHeight(true);
    }
           
    g_oneThumbnailWidthContainer=$newDiv.outerWidth(true);
    g_oneThumbnailHeightContainer=$newDiv.outerHeight(true);

    if( item.kind == 'album' ) {
      g_oneThumbnailLabelTitleHeight=$newDiv.find('.labelFolderTitle').outerHeight(true);
    }
    else {
      g_oneThumbnailLabelTitleHeight=$newDiv.find('.labelImageTitle').outerHeight(true);
    }
    
    // pagination - max lines per page mode
    if( g_paginationMaxLinesPerPage > 0 ) {
      var areaWidth=$g_containerThumbnailsParent.width();
      g_paginationLinesMaxItemsPossiblePerLine=parseInt(areaWidth/g_oneThumbnailWidthContainer);
      if( g_paginationLinesMaxItemsPossiblePerLine < 1 ) {
        g_paginationLinesMaxItemsPossiblePerLine=1;
      }
      if(  g_options.maxItemsPerLine >0 && g_paginationLinesMaxItemsPossiblePerLine >  g_options.maxItemsPerLine ) {
        g_paginationLinesMaxItemsPossiblePerLine=g_options.maxItemsPerLine;
      }
    }

    // backup values used in animations/transitions
    g_custGlobals.oldBorderColor=$newDiv.css('border-color');
    if( g_custGlobals.oldBorderColor == '' || g_custGlobals.oldBorderColor == null || g_custGlobals.oldBorderColor == undefined ) { g_custGlobals.oldBorderColor='#000'; }
    g_custGlobals.oldLabelOpacity=$newDiv.find('.labelImage').css('opacity');
    var c=jQuery.Color($newDiv.find('.labelImage'),'backgroundColor');
    g_custGlobals.oldLabelRed=c.red();
    g_custGlobals.oldLabelGreen=c.green();
    g_custGlobals.oldLabelBlue=c.blue();

    g_ngItems=[];
  }

  
  // ####################################
  // ##### LIST OF ITEMS IN OPTIONS #####
  // ####################################

  function GetI18nItem( item, property ) {
    var s='';
    if( g_i18nLang != '' ) {
      if( item[property+'_'+g_i18nLang] !== undefined && item[property+'_'+g_i18nLang].length>0 ) {
        s=item[property+'_'+g_i18nLang];
        return s;
      }
    }
    s=item[property];
    return s;
  }
  
  function ProcessItemOption() {
    
    var foundAlbumID=false;
    jQuery.each(g_options.items, function(i,item){
      
      var title='';
      title=GetI18nItem(item,'title');
      if( title === undefined ) { title=''; }
      
      var thumbsrc='';
      if( item.srct !== undefined && item.srct.length>0 ) {
        thumbsrc=g_options.itemsBaseURL+item.srct;
      }
      else {
        thumbsrc=g_options.itemsBaseURL+item.src;
      }
      var src=g_options.itemsBaseURL+item.src;
      var description='';     //'&nbsp;';
      description=GetI18nItem(item,'description');
      if( description === undefined ) { description=''; }
      //if( toType(item.description) == 'string' ) {
      //  description=item.description;
      //}

      var destinationURL='';
      if( item.destURL !== undefined && item.destURL.length>0 ) {
        destinationURL=item.destURL;
      }
      var tags='';
      //if( item.tags !== undefined && item.tags.length>0 ) {
      //  tags=item.tags;
      //}
      var tags=GetI18nItem(item,'tags');
      if( tags === undefined ) { tags=''; }

      var albumID=0;
      if( item.albumID !== undefined  ) {
        albumID=item.albumID;
        foundAlbumID=true;
      }
      var ID=null;
      if( item.ID !== undefined ) {
        ID=item.ID;
      }
      var kind='image';
      if( item.kind !== undefined && item.kind.length>0 ) {
        kind=item.kind;
      }
      
      NGAddItem(title, thumbsrc, src, description, destinationURL, kind, tags, ID, albumID );
    });
    
    if( foundAlbumID ) {
      g_options.displayBreadcrumb=true;
    }

    // get the number of images per album for all the items
    var l=g_ngItems.length;
    var nb=0;
    var nbImages=0;
    for( var i=0; i<l; i++ ){
      nb=0;
      nbImages=0;
      for( var j=0; j<l; j++ ){
        if( i!=j && g_ngItems[i].GetID() == g_ngItems[j].albumID ) {
          nb++;
          if( g_ngItems[j].kind == 'image' ) {
            g_ngItems[j].imageNumber=nbImages++;
          }
        }
      }
      g_ngItems[i].contentLength=nb;
    }

  };


  // ###################################
  // ##### LIST OF HREF ATTRIBUTES #####
  // ###################################

  function ProcessHREF(elements) {
    var foundAlbumID=false;
    
    jQuery.each(elements, function(i,item){
      var thumbsrc='';
      if( jQuery(item).attr('data-ngthumb') !== undefined && jQuery(item).attr('data-ngthumb').length>0 ) {
        thumbsrc=g_options.itemsBaseURL+jQuery(item).attr('data-ngthumb');
      }
      if( jQuery(item).attr('data-ngThumb') !== undefined && jQuery(item).attr('data-ngThumb').length>0 ) {
        thumbsrc=g_options.itemsBaseURL+jQuery(item).attr('data-ngThumb');
      }

      src=g_options.itemsBaseURL+jQuery(item).attr('href');
      //newObj.description=jQuery(item).attr('data-ngdesc');
      var description='';
      if( jQuery(item).attr('data-ngdesc') !== undefined && jQuery(item).attr('data-ngdesc').length>0 ) {
        description=jQuery(item).attr('data-ngdesc');
      }
      if( jQuery(item).attr('data-ngDesc') !== undefined && jQuery(item).attr('data-ngDesc').length>0 ) {
        description=jQuery(item).attr('data-ngDesc');
      }

      var destURL='';
      if( jQuery(item).attr('data-ngdest') !== undefined && jQuery(item).attr('data-ngdest').length>0 ) {
        destURL=jQuery(item).attr('data-ngdest');
      }
      if( jQuery(item).attr('data-ngDest') !== undefined && jQuery(item).attr('data-ngDest').length>0 ) {
        destURL=jQuery(item).attr('data-ngDest');
      }

      var albumID=0;
      if( jQuery(item).attr('data-ngalbumid') !== undefined ) {
        albumID=jQuery(item).attr('data-ngalbumid');
        foundAlbumID=true;
      }
      if( jQuery(item).attr('data-ngAlbumID') !== undefined ) {
        albumID=jQuery(item).attr('data-ngAlbumID');
        foundAlbumID=true;
      }
      
      var ID=null;
      if( jQuery(item).attr('data-ngid') !== undefined ) {
        ID=jQuery(item).attr('data-ngid');
      }
      if( jQuery(item).attr('data-ngID') !== undefined ) {
        ID=jQuery(item).attr('data-ngID');
      }

      var kind='image';
      if( jQuery(item).attr('data-ngkind') !== undefined && jQuery(item).attr('data-ngkind').length>0 ) {
        kind=jQuery(item).attr('data-ngkind');
      }
      if( jQuery(item).attr('data-ngKind') !== undefined && jQuery(item).attr('data-ngKind').length>0 ) {
        kind=jQuery(item).attr('data-ngKind');
      }

      //NGAddItem(jQuery(item).text(), thumbsrc, src, description, destURL, 'image', '' );
      NGAddItem(jQuery(item).text(), thumbsrc, src, description, destURL, kind, '', ID, albumID );
    });
    
    jQuery.each(elements, function(i,item){ jQuery(item).remove(); });
    
    if( foundAlbumID ) {
      g_options.displayBreadcrumb=true;
    }

    // get the number of images per album for all the items
    var l=g_ngItems.length;
    var nb=0;
    var nbImages=0;
    for( var i=0; i<l; i++ ){
      nb=0;
      nbImages=0
      for( var j=0; j<l; j++ ){
        if( i!=j && g_ngItems[i].GetID() == g_ngItems[j].albumID ) {
          nb++;
          if( g_ngItems[j].kind == 'image' ) {
            g_ngItems[j].imageNumber=nbImages++;
          }
        }
      }
      g_ngItems[i].contentLength=nb;
    }
    
  };

  
  // ##########################
  // ##### FLICKR STORAGE #####
  // ##########################

  function FlickrProcessItems( albumIdx, processLocationHash, imageID, setLocationHash) {

    if( g_ngItems[albumIdx].contentLength != 0 ) {    // already loaded?
      DisplayAlbum(albumIdx,setLocationHash);
      return;
    }

    var url = '';
    var kind='album';

    if( g_ngItems[albumIdx].GetID() == 0 ) {
      // albums
      url = (location.protocol=='https:'?'https:':'http:')+"//api.flickr.com/services/rest/?&method=flickr.photosets.getList&api_key=" + g_flickrApiKey + "&user_id="+g_options.userID+"&primary_photo_extras=url_"+g_flickrThumbSize+"&format=json&jsoncallback=?";
    }
    else {
      // photos
      url = (location.protocol=='https:'?'https:':'http:')+"//api.flickr.com/services/rest/?&method=flickr.photosets.getPhotos&api_key=" + g_flickrApiKey + "&photoset_id="+g_ngItems[albumIdx].GetID()+"&extras=description,views,url_o,url_z,url_"+g_flickrPhotoSize+",url_"+g_flickrThumbSize+"&format=json&jsoncallback=?";
      kind='image';
    }

    if( g_options.displayBreadcrumb == true ) { $g_containerBreadcrumb.find('.folder').last().addClass('loading'); }

    jQuery.ajaxSetup({ cache: false });
    jQuery.support.cors = true;

    jQuery.getJSON(url, function(data) {
      if( g_options.displayBreadcrumb == true ) { $g_containerBreadcrumb.find('.folder').last().removeClass('loading'); }
      if( kind == 'album' ) {
        FlickrParsePhotoSets(albumIdx, data);
      }
      else {
        FlickrParsePhotos(albumIdx, data);
      }
      if( processLocationHash ) {
        if( !ProcessLocationHash(false) ) {
          DisplayAlbum(albumIdx,setLocationHash);
        }
      }
      else {
        if( imageID != -1 ) {
          var imageIdx=-1;
          var l=g_ngItems.length;
          for(var i=0; i<l; i++ ) {
            if( g_ngItems[i].kind == 'image' && g_ngItems[i].GetID() == imageID ) {
              imageIdx=i;
              break;
            }
          }
          DisplayImage(imageIdx,true);
        }
        else {
          DisplayAlbum(albumIdx,setLocationHash);
        }
      }
    })
    .fail( function(jqxhr, textStatus, error) {
      if( g_options.displayBreadcrumb == true ) { $g_containerBreadcrumb.find('.folder').last().removeClass('loading'); }
      var err = textStatus + ', ' + error;
      nanoAlert("Could not retrieve Flickr photoset list (jQuery): " + err);
    });
  
  }

  
  function FlickrParsePhotoSets( albumIdx, data ) {
    var ok=true;
    if( data.stat !== undefined ) {
      if( data.stat === 'fail' ) {
        nanoAlert("Could not retrieve Flickr photoset list: " + data.message + " (code: "+data.code+").");
        ok=false;
      }
    }

  
    if( ok ) {
      var nb=0;
      
      if( typeof g_options.albumSorting !== 'undefined' ) { //GI
        if( g_options.albumSorting == 'random' ) {
          data.photoset.photo=shuffle(data.photosets.photoset);
        }else if( g_options.albumSorting == 'reversed' ) {
          data.photoset.photo=data.photosets.photoset.reverse();
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
        itemThumbURL=item.primary_photo_extras['url_'+g_flickrThumbSize];

        var tags='';
        if( item.primary_photo_extras !== undefined ) {
          if( item.primary_photo_extras.tags !== undefined ) {
            tags=item.primary_photo_extras.tags;
          }
        }
        if( CheckAlbumName(itemTitle) ) {
          var newItem=NGAddItem(itemTitle, itemThumbURL, '', itemDescription, '', 'album', tags, itemID, g_ngItems[albumIdx].GetID() );
          newItem.thumbWidth=item.primary_photo_extras['width_'+g_flickrThumbSize];
          newItem.thumbHeight=item.primary_photo_extras['height_'+g_flickrThumbSize];
          nb++;
        }
      });
      
      g_ngItems[albumIdx].contentIsLoaded=true;
      g_ngItems[albumIdx].contentLength=nb;
    }
  }

  function FlickrParsePhotos( albumIdx, data ) {
		if( typeof g_options.photoSorting !== 'undefined' ) { //GI
			if( g_options.photoSorting == 'random' ) {
				data.photoset.photo=shuffle(data.photoset.photo);
			}else if( g_options.photoSorting == 'reversed' ) {
				data.photoset.photo=data.photoset.photo.reverse();
			}
		}

    var albumID=g_ngItems[albumIdx].GetID();
    var nb=0;
    jQuery.each(data.photoset.photo, function(i,item){
      //Get the title 
      var itemTitle = item.title;    //._content;
      var itemID=item.id;
      //Get the description
      var itemDescription=item.description._content;
      
      //itemThumbURL = "http://farm" + item.farm + ".staticflickr.com/" + item.server + "/" + item.id +"_" + item.secret + "_"+g_flickrThumbSize+".jpg";
      var itemThumbURL=item['url_'+g_flickrThumbSize];

      var imgUrl='';
      if( g_flickrPhotoSize == 'o' ) {
        if( item.url_o !== undefined ) {
          imgUrl=item.url_o;
        }
        else {
          // original size not available so we use the biggest one available
          imgUrl=item.url_z;
        }
      }
      else {
        imgUrl=item['url_'+g_flickrPhotoSize];
      }
      
	  var flickrSpecificData={
				'seed':(location.protocol=='https:'?'https:':'http:')+"//farm" + item.farm + ".staticflickr.com/" + item.server + "/" + item.id + "_" + item.secret + "_",
				'img_orig_width':0,	
				'img_orig_height':0
			  };
      if( item.url_o !== undefined ) {
    	  flickrSpecificData.img_orig_width=item.width_o;
    	  flickrSpecificData.img_orig_height=item.height_o;
      }
      else {
    	  flickrSpecificData.img_orig_width=item.width_z;
    	  flickrSpecificData.img_orig_height=item.height_z;
      }
      
      
      var newItem=NGAddItem(itemTitle, itemThumbURL, imgUrl, itemDescription, '', 'image', '', itemID, albumID,'flickr', flickrSpecificData  );
      newItem.imageNumber=nb;
      if( item.url_o !== undefined ) {
        newItem.width=item.width_o;
        newItem.height=item.height_o;
      }
      else {
        newItem.width=item.width_z;
        newItem.height=item.height_z;
      }
      newItem.thumbWidth=item['width_'+g_flickrThumbSize];
      newItem.thumbHeight=item['height_'+g_flickrThumbSize];
      nb++;
      
    });
    g_ngItems[albumIdx].contentIsLoaded=true;
    g_ngItems[albumIdx].contentLength=nb;
  }
  

  // ##########################
  // ##### PICASA STORAGE #####
  // ##########################

  function PicasaProcessItems( albumIdx, processLocationHash, imageID, setLocationHash ) {

    if( g_ngItems[albumIdx].contentLength != 0 ) {    // already loaded?
      //renderGallery(albumIdx,0);
      DisplayAlbum(albumIdx,setLocationHash);
      return;
    }
    
    var url='';
    var kind='album';
   
    if( g_ngItems[albumIdx].GetID() == 0 ) {
      // albums
      url = (location.protocol=='https:'?'https:':'http:')+'//picasaweb.google.com/data/feed/api/user/'+g_options.userID+'?alt=json&kind=album&imgmax=d&thumbsize='+g_picasaThumbSize;
    }
    else {
      // photos
      var opt='';
      if( typeof g_ngItems[albumIdx].customVars.authkey !== 'undefined' ) { opt=g_ngItems[albumIdx].customVars.authkey; }
      url = (location.protocol=='https:'?'https:':'http:')+'//picasaweb.google.com/data/feed/api/user/'+g_options.userID+'/albumid/'+g_ngItems[albumIdx].GetID()+'?alt=json&kind=photo'+opt+'&thumbsize='+g_picasaThumbSize+'&imgmax=d';
      kind='image';
    }
    url = url + "&callback=?";
    if( g_options.displayBreadcrumb == true ) { $g_containerBreadcrumb.find('.folder').last().addClass('loading'); }


    // get the content and display it
    jQuery.ajaxSetup({ cache: false });
    jQuery.support.cors = true;

    //jQuery.getJSON(url, function(data) {
    //    })
    //.fail( function(jqxhr, textStatus, error) {
    //  var err = textStatus + ', ' + error;
    //  alert("Error with Picasa: " + err);
    //});

    // use jQuery
    jQuery.getJSON(url, function(data) {
      if( g_options.displayBreadcrumb == true ) { $g_containerBreadcrumb.find('.folder').last().removeClass('loading'); }
      PicasaParseData(albumIdx,data,kind);
      //renderGallery(albumIdx,0);
      if( processLocationHash ) {
        if( !ProcessLocationHash(false) ) {
          DisplayAlbum(albumIdx,setLocationHash);
        }
      }
      else {
      if( imageID != -1 ) {
          var imageIdx=-1;
          var l=g_ngItems.length;
          for(var i=0; i<l; i++ ) {
            if( g_ngItems[i].kind == 'image' && g_ngItems[i].GetID() == imageID ) {
              imageIdx=i;
              break;
            }
          }
          DisplayImage(imageIdx,true);
        }
        else {
          DisplayAlbum(albumIdx,setLocationHash);
        }
      }
    })
    .fail( function(jqxhr, textStatus, error) {
      if( g_options.displayBreadcrumb == true ) { $g_containerBreadcrumb.find('.folder').last().removeClass('loading'); }
      var err = textStatus + ', ' + error;
      nanoAlert("Could not retrieve Picasa/Google+ data (jQuery): " + err);
    });
  };  
  
  //+ Jonas Raoni Soares Silva
  //@ http://jsfromhell.com/array/shuffle [v1.0]
  function shuffle(o){ //v1.0
      for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
      return o;
  };
  
  function PicasaParseData( albumIdx, data, kind ) {
    var nb=0;
    var albumID=g_ngItems[albumIdx].GetID();
    if (kind =='image'){//GI
      if( typeof g_options.photoSorting !== 'undefined' ){//GI
        if( g_options.photoSorting == 'random' ){
          data.feed.entry=shuffle(data.feed.entry);
        }else if( g_options.photoSorting == 'reversed' ){
          data.feed.entry=data.feed.entry.reverse();
        }
      }
    }
    else {
      if ( typeof g_options.albumSorting !== 'undefined' ){//GI
        if( g_options.albumSorting == 'random' ){
          data.feed.entry=shuffle(data.feed.entry);
        }else if( g_options.albumSorting == 'reversed' ){
          data.feed.entry=data.feed.entry.reverse();
        }
      }
    }
    
    jQuery.each(data.feed.entry, function(i,data){
      
      var filename='';
      
      //Get the title 
      var itemTitle = data.media$group.media$title.$t;

      //Get the URL of the thumbnail
      var itemThumbURL = data.media$group.media$thumbnail[0].url;

      //Get the ID 
      var itemID = data.gphoto$id.$t;
      
      //Get the description
      var itemDescription = data.media$group.media$description.$t;
      if( kind == 'image') { 
        filename=itemTitle;
        itemTitle=itemDescription;
        itemDescription='';
      }
      
      var imgUrl=data.media$group.media$content[0].url;
	  var picasaSpecificData={
				'seed':'',
				'img_orig_width':0,
				'img_orig_height':0,
			  };

      var ok=true;
      if( kind == 'album' ) {
        if( !CheckAlbumName(itemTitle) ) { ok=false; }
      }else{
  		var seed = data.content.src.substring(0, data.content.src.lastIndexOf("/"));
		seed = seed.substring(0, seed.lastIndexOf("/")) + "/";
		picasaSpecificData.img_orig_width=data.gphoto$width.$t;
		picasaSpecificData.img_orig_height=data.gphoto$height.$t;
		picasaSpecificData.seed=seed;
      }
     
      var tags='';
      
      if( ok ) {

		var thumbScale=1;
		for( j=0; j<g_thumbnailHoverEffect.length; j++) {
			switch(g_thumbnailHoverEffect[j].name ) {
				case 'imageScale150':
					if (thumbScale<1.5){
						thumbScale=1.5;
					}
					break;
				case 'imageScale150Outside':
					if (thumbScale<1.5){
						thumbScale=1.5;
					}
					break;
				case 'scale120':
					if (thumbScale<1.2){
						thumbScale=1.2;
					}
					break;
			}
		}

        if( g_options.thumbnailHeight == 'auto' ) {
          if( g_picasaThumbSizeHack ) {
            var s=itemThumbURL.substring(0, itemThumbURL.lastIndexOf('/'));
            s=s.substring(0, s.lastIndexOf('/')) + '/';
            itemThumbURL=s+'w'+(g_options.thumbnailWidth*thumbScale)+'/';
          }
        }else if (thumbScale>1){
          if( g_picasaThumbSizeHack ) {
            var s=itemThumbURL.substring(0, itemThumbURL.lastIndexOf('/'));
            s=s.substring(0, s.lastIndexOf('/')) + '/';
            itemThumbURL=s+'s'+(g_picasaThumbSize*thumbScale)+'/';
          }
		
		}

        var src='';
        if( kind == 'album' ) {
          src=itemID;
        }
        else {
          src=imgUrl;
          var s=imgUrl.substring(0, imgUrl.lastIndexOf('/'));
          s=s.substring(0, s.lastIndexOf('/')) + '/';

          if( window.screen.width >  window.screen.height ) {
            src=s+'w'+window.screen.width+'/'+filename;
          }
          else {
            src=s+'h'+window.screen.height+'/'+filename;
          }
        }
        var newItem= NGAddItem(itemTitle, itemThumbURL, src, itemDescription, '', kind, tags, itemID, albumID,'picasa', picasaSpecificData );
        newItem.imageNumber=nb;
        
        if( kind == 'image' ) {
          newItem.width=data.gphoto$width.$t;
          newItem.height=data.gphoto$height.$t;
          newItem.thumbWidth=data.media$group.media$thumbnail[0].width;
          newItem.thumbHeight=data.media$group.media$thumbnail[0].height;
          
          newItem.infobox={};
          newItem.infobox.album='-na-';
          if (g_ngItems[albumIdx].title!=''){
        	  newItem.infobox.album=g_ngItems[albumIdx].title;
          }
          
          newItem.infobox.photo='-na-';
          if (data.summary.$t!=''){
        	  newItem.infobox.photo=data.summary.$t;
          }
          newItem.infobox.date='-na-';
  		  if (typeof data.gphoto$timestamp !== "undefined" && typeof data.gphoto$timestamp.$t !== "undefined"){
	    	  var timestamp=data.gphoto$timestamp.$t;
	    	  var photo_date=new Date();
	    	  photo_date.setTime(timestamp);
	    	  newItem.infobox.date=photo_date.getDate()+'/'+(photo_date.getUTCMonth()+1)+'/'+photo_date.getUTCFullYear()+' '+photo_date.getUTCHours()+':'+photo_date.getUTCMinutes();
		  }
    	  
  		  newItem.infobox.dimensions=data.gphoto$width.$t+' x '+data.gphoto$height.$t;
		  newItem.infobox.filename='-na-';
			if (typeof data.title !== "undefined" && typeof data.title.$t !== "undefined"){
				newItem.infobox.filename=data.title.$t;
			}
  		  
  		  	newItem.infobox.filesize='-na-';
			if (typeof data.gphoto$size !== "undefined" && typeof data.gphoto$size.$t !== "undefined"){
				newItem.infobox.filesize=data.gphoto$size.$t;
				if (newItem.infobox.filesize>(1024*1024)){
					newItem.infobox.filesize=(newItem.infobox.filesize/(1024*1024)).toFixed(2);
					newItem.infobox.filesize=newItem.infobox.filesize+'M';
				}else if (newItem.infobox.filesize>(1024)){
					newItem.infobox.filesize=(newItem.infobox.filesize/(1024)).toFixed(2);
					newItem.infobox.filesize=newItem.infobox.filesize+'K';
				}				
			}
  		  	newItem.infobox.camera='-na-';
     		newItem.infobox.focallength='-na-';
      		newItem.infobox.exposure='-na-';
      		newItem.infobox.fnumber='-na-';
      		newItem.infobox.iso='-na-';
      		newItem.infobox.make='-na-';
      		newItem.infobox.flash='-na-';
			if (typeof data.exif$tags !== "undefined"){
  		  	
				if (typeof data.exif$tags.exif$model !== "undefined" && typeof data.exif$tags.exif$model.$t !== "undefined"){
					newItem.infobox.camera=data.exif$tags.exif$model.$t;
				}
				if (typeof data.exif$tags.exif$exposure !== "undefined" && typeof data.exif$tags.exif$exposure.$t !== "undefined"){
					var photo_exposure_d=Math.round(1/data.exif$tags.exif$exposure.$t);
					newItem.infobox.exposure='1/'+photo_exposure_d+" sec";
				}
				if (typeof data.exif$tags.exif$focallength !== "undefined" && typeof data.exif$tags.exif$focallength.$t !== "undefined"){
					newItem.infobox.focallength=data.exif$tags.exif$focallength.$t+" mm";
				}
				if (typeof data.exif$tags.exif$iso !== "undefined" && typeof data.exif$tags.exif$iso.$t !== "undefined"){
					newItem.infobox.iso=data.exif$tags.exif$iso.$t;
				}
				if (typeof data.exif$tags.exif$make !== "undefined" && typeof data.exif$tags.exif$make.$t !== "undefined"){
					newItem.infobox.make=data.exif$tags.exif$make.$t;
				}
				if (typeof data.exif$tags.exif$flash !== "undefined" && typeof data.exif$tags.exif$flash.$t !== "undefined"){
					newItem.infobox.flash=data.exif$tags.exif$flash.$t?'Yes':'No';
				}
				if (typeof data.exif$tags.exif$fstop !== "undefined" && typeof data.exif$tags.exif$fstop.$t !== "undefined"){
					newItem.infobox.fnumber=data.exif$tags.exif$fstop.$t;
				}
			}
			newItem.infobox.lat='';
			newItem.infobox.long='';
			if (typeof data.georss$where !== "undefined" && typeof data.georss$where.gml$Point !== "undefined" &&
				typeof data.georss$where.gml$Point.gml$pos !== "undefined" && typeof data.georss$where.gml$Point.gml$pos.$t !== "undefined"){
			
				var latlong=data.georss$where.gml$Point.gml$pos.$t.split(" ");
				newItem.infobox.lat=latlong[0];
				newItem.infobox.long=latlong[1];
			}
	  		  
  		  newItem.infobox.comments='-na-';
		  if (typeof data.gphoto$commentCount !== "undefined" && typeof data.gphoto$commentCount.$t !== "undefined"){
			  newItem.infobox.comments=data.gphoto$commentCount;
		  }
  		  	
  		  newItem.infobox.views='...';
  		newItem.infobox.json_details='';
			if (typeof data.link !== "undefined"){
				for (var j=0;j<data.link.length;j++){
					if (data.link[j].rel=='self' && data.link[j].type=='application/atom+xml'){
						newItem.infobox.json_details=data.link[j].href;
						break;
					}
				}
			}
  		  
  		
  		  newItem.infobox.link="https://plus.google.com/photos/"+g_options.userID+"/albums/"+albumID+"/"+itemID;
  		  newItem.infobox.download=picasaSpecificData.seed+ 's0-d/';
  		  newItem.infobox.image= picasaSpecificData.seed+ 's200/';
          
          
        }
        else {
          if( g_options.thumbnailHeight == 'auto' ) {
            newItem.thumbWidth=g_picasaThumbSize;
          }
          if( g_options.thumbnailWidth == 'auto' ) {
            newItem.thumbHeight=g_picasaThumbSize;
          }
        }

        nb++;
      }
      
    });
    g_ngItems[albumIdx].contentIsLoaded=true;
    g_ngItems[albumIdx].contentLength=nb;
    
  }
  
  
  // ################################
  // ##### NGITEMS MANIPULATION #####
  // ################################
  
  function NGAddItem(title, thumbSrc, imageSrc, description, destinationURL, kind, tags, ID, albumID, albumSource, imgSpecificData ) {
    var newObj=new NGItems(title,ID);
    newObj.thumbsrc=thumbSrc;
    newObj.src=imageSrc;
    newObj.description=description;
    newObj.destinationURL=destinationURL;
    newObj.kind=kind;
    newObj.albumID=albumID;
    newObj.albumSource=albumSource;
    newObj.imgSpecificData=imgSpecificData;
    if( tags.length == 0 ) {
      newObj.tags=null;
    }
    else {
      newObj.tags=tags.split(' ');
    }
    g_ngItems.push(newObj);
    return newObj;
  };

  function GetNGItem( ID ) {
    var l=g_ngItems.length;
    for( var i=0; i<l; i++ ) {
      if( g_ngItems[i].GetID() == ID ) {
        return g_ngItems[i];
      }
    }
    return null;
  }

  
  // ###########################
  // ##### GALLERY TOOLBAR #####
  // ###########################

  function DisplayAlbum( albumIdx, setLocationHash ) {
    g_albumIdxToOpenOnViewerClose=-1;

    if( g_containerViewerDisplayed ) {
      CloseInternalViewer(false);
    }
    
    if( albumIdx == g_lastOpenAlbumID ) {
      return;
    }
    
    if( g_options.locationHash ) {
      if( setLocationHash ) {
        var s='nanogallery/'+g_baseControlID+'/'+g_ngItems[albumIdx].GetID();
        g_lastLocationHash='#'+s;
        top.location.hash=s;
      }
    }
    g_lastOpenAlbumID=g_ngItems[albumIdx].GetID();
    manageGalleryToolbar(albumIdx);
    renderGallery(albumIdx,0);
  }
  
  
  // add album to breadcrumb
  function breadcrumbAdd( albumIdx ) {
    
    var cl="folder";
    if(albumIdx == 0 ) {
      cl="folderHome";
    }
    var newDiv =jQuery('<div class="'+cl+'">'+g_ngItems[albumIdx].title+'</div>').appendTo($g_containerBreadcrumb);
    jQuery(newDiv).data('albumIdx',albumIdx);
    newDiv.click(function() {
      var cAlbumIdx=jQuery(this).data('albumIdx');
      jQuery(this).nextAll().remove();
      switch(g_options.kind) {
        case '':
          DisplayAlbum(cAlbumIdx,true);
          break;
        case 'flickr':
          FlickrProcessItems(cAlbumIdx, false, -1, true);
          break;
        case 'picasa':
        default:
          PicasaProcessItems(cAlbumIdx, false, -1, true);
          break;
      }
      return;
    });;        
  }

  // add separator to breadcrumb
  function breadcrumbAddSeparator( lastAlbumID ) {
    var newSep=jQuery('<div class="separator"></div>').appendTo($g_containerBreadcrumb);
    jQuery(newSep).data('albumIdx',lastAlbumID);
    newSep.click(function() {
      var sepAlbumIdx=jQuery(this).data('albumIdx');
      jQuery(this).nextAll().remove();
      jQuery(this).remove();
      switch(g_options.kind) {
        case '':
          DisplayAlbum(sepAlbumIdx,true);
          break;
        case 'flickr':
          FlickrProcessItems(sepAlbumIdx, false, -1, true);
          break;
        case 'picasa':
        default:
          PicasaProcessItems(sepAlbumIdx, false, -1, true);
          break;
      }
      return;
    });;
  }

  
  function manageGalleryToolbar( albumIdx ) {
    var displayToolbar=false;
  
    // Breadcrumb
    if( g_options.displayBreadcrumb == true ) {
      displayToolbar=true;
      var bcItems=$g_containerBreadcrumb.children();
      var l1=bcItems.length;
      if( l1 == 0 ) {
        breadcrumbAdd(0);
      }
      else {
        $g_containerBreadcrumb.children().not(':first').remove();
      }
      if( albumIdx != 0 ) {
        var l=g_ngItems.length;
        var parentID=0;
        var lstItems=[];
        lstItems.push(albumIdx);
        var curIdx=albumIdx;
        while ( g_ngItems[curIdx].albumID != 0 ) {
          for(i=1; i < l; i++ ) {
            if( g_ngItems[i].GetID() == g_ngItems[curIdx].albumID ) {
              curIdx=i;
              lstItems.push(curIdx);
              break;
            }
          }
        }
        for( i=lstItems.length-1; i>=0 ; i-- ) {
          var sepIdx=0
          if( (i-1) >= 0 ) { sepIdx=i-1; }
          breadcrumbAddSeparator(lstItems[sepIdx])
          breadcrumbAdd(lstItems[i]);
        }
      }
    }


    //if( g_options.displayBreadcrumb == true ) {
    if( true == false ) {
      displayToolbar=true;
      var bcItems=$g_containerBreadcrumb.children();
      var l1=bcItems.length;
      var found=false;
      var lastAlbumID=-1;
      for( var i=0; i < l1; i++ ) {
        lastAlbumIdx=jQuery(bcItems[i]).data('albumIdx');
        if( lastAlbumIdx == albumIdx ) {
          $g_containerBreadcrumb.children().slice(i-1);
          found=true;
          break;
        }
      }

      if( !found ) {
        if( albumIdx != 0 ) {
          // add separator
          var newSep=jQuery('<div class="separator"></div>').appendTo($g_containerBreadcrumb);
          jQuery(newSep).data('albumIdx',lastAlbumID);
          newSep.click(function() {
            var sepAlbumIdx=jQuery(this).data('albumIdx');
            jQuery(this).nextAll().remove();
            jQuery(this).remove();
            switch(g_options.kind) {
              case '':
                renderGallery(sepAlbumIdx,0);
                break;
              case 'flickr':
                FlickrProcessItems(sepAlbumIdx, false, -1, true);
                break;
              case 'picasa':
              default:
                PicasaProcessItems(sepAlbumIdx, false, -1, true);
                break;
            }
            return;
          });;
        }
        // add album to breadcrumb
        var newDiv =jQuery('<div class="folder">'+g_ngItems[albumIdx].title+'</div>').appendTo($g_containerBreadcrumb);
        jQuery(newDiv).data('albumIdx',albumIdx);
        newDiv.click(function() {
          var cAlbumIdx=jQuery(this).data('albumIdx');
          jQuery(this).nextAll().remove();
          switch(g_options.kind) {
            case '':
              renderGallery(cAlbumIdx,0);
              break;
            case 'flickr':
              FlickrProcessItems(cAlbumIdx, false, -1, true);
              break;
            case 'picasa':
            default:
              PicasaProcessItems(cAlbumIdx, false, -1, true);
              break;
          }
          return;
        });;        
      }
    }
    
    // Tag-bar
    if( g_options.useTags ) {
      displayToolbar=true;
      if( g_containerTags == null ) {
        g_containerTags =jQuery('<div class="nanoGalleryTags"></div>').appendTo($g_containerNavigationbar);
      }
    }
    
    if( !g_containerNavigationbarContDisplayed && displayToolbar ) {
      g_containerNavigationbarContDisplayed=true;
      $g_containerNavigationbarCont.show();
    }
    
  }

  

  // ##### REPOSITION THUMBNAILS ON SCREEN RESIZE EVENT
  function ResizeGallery() {

    if( g_options.thumbnailHeight == 'auto' ) {
      SetGalleryWidth(0);
      SetGalleryToolbarWidth(0);
      var areaW=$g_containerThumbnailsParent.outerWidth(true);
      var n=0;
      var t=0;
      var col=0;
      var row=0;
      var maxCol=parseInt(areaW/g_oneThumbnailWidthContainer);
      if( g_options.maxItemsPerLine > 0 ) {
        if( maxCol > g_options.maxItemsPerLine ) {
          maxCol=g_options.maxItemsPerLine;
        }
      }

      var colHeight=[];
      $g_containerThumbnails.find('.nanoGalleryThumbnailContainer').each(function() {
        jQuery(this).css('position', 'absolute')
        t=0;
        if( row == 0 ) {
          var l=col*g_oneThumbnailWidthContainer;
          jQuery(this).css({ top: t, left: l });
          colHeight[col]=t+jQuery(this).outerHeight(true);
          col++;
          if( col >= maxCol) { col=0; row++;}
        }
        else {
          var c=0;
          var minColHeight=colHeight[0];
          for( i=1; i<maxCol; i++) {
            if( colHeight[i] < minColHeight ) {
              minColHeight=colHeight[i];
              c=i;
            }
          }
          t=colHeight[c];
          var l=c*g_oneThumbnailWidthContainer;
          jQuery(this).css({ top: t, left: l });
          colHeight[c]=t+jQuery(this).outerHeight(true);
        }
        n++;
      });
      var h=colHeight[0];
      for(i=1;i<maxCol;i++) {
        if( colHeight[i] > h ) { h=colHeight[i]; }
      }
      $g_containerThumbnails.width(areaW).height(h);

    }
    else {
      SetGalleryWidth(0);
      SetGalleryToolbarWidth(0);
      // pagination - max lines per page mode
      if( g_paginationMaxLinesPerPage > 0 ) {
        if( g_oneThumbnailWidthContainer > 0 ) {
          var areaW=$g_containerThumbnailsParent.outerWidth(true);
          var n=parseInt(areaW/g_oneThumbnailWidthContainer);
          if( n < 1 ) { n=1; }
          if( n != g_paginationLinesMaxItemsPossiblePerLine ) {
            var aIdx=$g_containerPagination.data('galleryIdx');
            renderGallery(aIdx,0);
          }
        }
      }
    }

  };


  function SetGalleryWidth( pageNumber ) {
    if( g_oneThumbnailWidthContainer > 0 ) {
      if( g_options.maxWidth > 0 && g_oneThumbnailWidthContainer > g_options.maxWidth ) {
        // set minimum width to display at least one thumbnail
        jQuery(g_baseControl).css('maxWidth',g_oneThumbnailWidthContainer); 
      }
      
      
      var availableWidth=$g_containerThumbnailsParent.outerWidth(true);    // available width (parent container)
      if( g_options.thumbnailWidth != 'auto' ) {
      var setDone=false;
        if( g_options.maxItemsPerLine > 0 ) {
          // set the width to display the maxItemsPerLine number of thumbnails
          if( g_options.maxItemsPerLine*g_oneThumbnailWidthContainer <= availableWidth ) {
            $g_containerThumbnails.css('maxWidth',g_options.maxItemsPerLine*g_oneThumbnailWidthContainer);
            setDone=true;
          }
        }
        if( !setDone ) {
          var w=parseInt(availableWidth/g_oneThumbnailWidthContainer);  // number of thumbnails that can be displayed in 1 row
          if( w > 0 ) { $g_containerThumbnails.css('maxWidth',w*g_oneThumbnailWidthContainer); }
        }
      }
    }
  }

  function SetGalleryToolbarWidth(pageNumber) {
    if( g_options.galleryToolbarWidthAligned ) {
      if( $g_containerNavigationbarCont !== undefined ) {
        var w=$g_containerThumbnails.outerWidth(true);
//        if( pageNumber > 0 ) {
          if( $g_containerNavigationbarCont.width() < w ) {
            $g_containerNavigationbarCont.width(w);
          }
//        }
        else {
          $g_containerNavigationbarCont.width(w);
        }
      }
    }
  }

  // thumbnail image lazy load
  function thumbnailsLazySetSrc() {
//    if( g_containerThumbnailsDisplayed ) {
      //var wp=getViewport();
      var $eltInViewport=$g_containerThumbnails.find('.nanoGalleryThumbnailContainer').filter(function() {
         return inViewport(this, g_thumbnailLazyLoadTreshold);
      });

      jQuery($eltInViewport).each(function(){
          var $image=jQuery(this).find('img');
          if( jQuery($image).attr('src') == "//:0" ) {
            var idx=jQuery(this).data('index')
            jQuery($image).attr('src',g_ngItems[idx].thumbsrc);
          }
      });
//    }
}

  // check album name - blackList/whiteList
  function CheckAlbumName(title) {
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
  };

  
  // ###################
  // ##### GALLERY #####
  // ###################

  
  // Display pagination
  function managePagination( albumIdx, pageNumber ) {
    if( $g_containerPagination == undefined ) return;
    $g_containerPagination.children().remove();

    $g_containerPagination.data('galleryIdx',albumIdx);
    $g_containerPagination.data('currentPageNumber',pageNumber);
    var n2=0;

    var w=0;
    if( pageNumber > 0 ) {
      var eltPrev=jQuery('<div class="paginationPrev">'+g_i18nTranslations.paginationPrevious+'</div>').appendTo($g_containerPagination);
      w+=jQuery(eltPrev).outerWidth(true);
      eltPrev.click(function(e) {
        paginationPreviousPage();
      });
    }

    var firstPage=0
    // pagination - max items per page mode
    if( g_paginationMaxItemsPerPage != 0 ) {
      n2=Math.ceil(g_ngItems[albumIdx].contentLength/g_paginationMaxItemsPerPage);
    }

    // pagination - max lines per page mode
    if( g_paginationMaxLinesPerPage > 0 ) {
      n2=Math.ceil(g_ngItems[albumIdx].contentLength/(g_paginationMaxLinesPerPage*g_paginationLinesMaxItemsPossiblePerLine));
    }

    if( pageNumber >= 5 ) {
      firstPage=pageNumber-5;
      if( n2 > pageNumber+6 ) {
        n2=pageNumber+6;
      }
    }
    else {
      if( n2 > 10 ) {
        n2=10;
      }
    }
    
    // only one page -> do not display anything
    if( n2==1 ) { return; }
    
    for(var i=firstPage; i < n2; i++ ) {
      var c='';
      if( i == pageNumber ) { c=' currentPage'; }
      var elt=jQuery('<div class="paginationItem'+c+'">'+(i+1)+'</div>').appendTo($g_containerPagination);
      jQuery(elt).data('pageNumber',i);
      w+=jQuery(elt).outerWidth(true);
      elt.click(function(e) {
        var aIdx=$g_containerPagination.data('galleryIdx');
        var pn=jQuery(this).data('pageNumber');
        renderGallery(aIdx,pn);
      });

    }

    if( (pageNumber+1) < n2 ) {
      var eltNext=jQuery('<div class="paginationNext">'+g_i18nTranslations.paginationNext+'</div>').appendTo($g_containerPagination);
      w+=jQuery(eltNext).outerWidth(true);
      eltNext.click(function(e) {
        paginationNextPage();
      });
    }

    $g_containerPagination.width(w);

  }

  function paginationNextPage() {
    var aIdx=$g_containerPagination.data('galleryIdx');

    var n1=0;
    // pagination - max items per page mode
    if( g_paginationMaxItemsPerPage != 0 ) {
      n1=g_ngItems[aIdx].contentLength/g_paginationMaxItemsPerPage;
    }
    // pagination - max lines per page mode
    if( g_paginationMaxLinesPerPage > 0 ) {
      n1=g_ngItems[aIdx].contentLength/(g_paginationMaxLinesPerPage*g_paginationLinesMaxItemsPossiblePerLine);
    }
    n2=Math.ceil(n1);
    
    var pn=$g_containerPagination.data('currentPageNumber');
    if( pn < (n2-1) ) {
      pn++;
    }
    else {
      pn=0;
    }
    renderGallery(aIdx,pn);
  }
  
  function paginationPreviousPage() {
    var aIdx=$g_containerPagination.data('galleryIdx');

    var n1=0;
    // pagination - max items per page mode
    if( g_paginationMaxItemsPerPage != 0 ) {
      n1=g_ngItems[aIdx].contentLength/g_paginationMaxItemsPerPage;
    }
    // pagination - max lines per page mode
    if( g_paginationMaxLinesPerPage > 0 ) {
      n1=g_ngItems[aIdx].contentLength/(g_paginationMaxLinesPerPage*g_paginationLinesMaxItemsPossiblePerLine);
    }
    n2=Math.ceil(n1);
    
    var pn=$g_containerPagination.data('currentPageNumber');
    if( pn > 0 ) {
      pn--;
    }
    else {
      pn=n2-1;
    }
    renderGallery(aIdx,pn);
  }

  function renderGallery( albumIdx, pageNumber ) {
    var $elt=$g_containerThumbnails.parent();
    $elt.animate({opacity: 0}, 100).promise().done(function(){

      // remove gallery elements
      g_containerThumbnailsDisplayed=false;
      $g_containerThumbnails.off().empty();
      var l=g_ngItems.length;
      for( var i=0; i < l ; i++ ) {
        g_ngItems[i].hovered=false;
      }

      $g_containerThumbnailsParent.css({ 'left': 0, 'opacity':'1' });
      
      renderGallery2(albumIdx, pageNumber, renderGallery2Complete);
    });
  };


  function renderGallery2( albumIdx, pageNumber, onComplete ) {
    //g_oneThumbnailLabelTitleHeight=0;

    var l=g_ngItems.length;
    // if one description is defined then put a value to those without
    var foundDesc=false;
    /*if( g_options.thumbnailLabel.position == 'onBottom'  ) {
      for(var i=0; i<l; i++ ) {
        if( g_ngItems[i].albumID == g_ngItems[albumIdx].albumID && g_ngItems[i].description.length > 0 ) { foundDesc=true; }
      }
    }*/
    var g_galleryItemsCount=0,
    currentCounter=0,
    firstCounter=0,
    lastCounter=0;
    
    if( g_paginationMaxItemsPerPage > 0 ) {
      firstCounter=pageNumber*g_paginationMaxItemsPerPage;
      lastCounter=firstCounter+g_paginationMaxItemsPerPage;
    }
    if( g_paginationMaxLinesPerPage > 0 ) {
      firstCounter=pageNumber*g_paginationMaxLinesPerPage*g_paginationLinesMaxItemsPossiblePerLine;
      lastCounter=firstCounter+g_paginationMaxLinesPerPage*g_paginationLinesMaxItemsPossiblePerLine;
    }

    if( g_options.displayBreadcrumb == true ) {
      $g_containerBreadcrumb.find('.folder').last().removeClass('loading');
    }

    var eltOpacity='1';
    if( g_options.thumbnailDisplayTransition ) {
      eltOpacity='0';
    }
    
    var endInViewportTest=false,
    startInViewportTest=false,
    idx=0;
    
    (function(){

      for( var i=0; i<100; i++ ) {
        if( idx >= l ) {
          onComplete(albumIdx, pageNumber);
          return;
        }

        var item=g_ngItems[idx];
        if( item.albumID == g_ngItems[albumIdx].GetID() ) {

          currentCounter++;
          // pagination - max items per page mode
          if( g_paginationMaxItemsPerPage > 0 && currentCounter > lastCounter ) {
            onComplete(albumIdx, pageNumber);
            return;
          }

          // pagination - max lines per page mode
          if( g_paginationMaxLinesPerPage > 0 ) {
            if( (g_galleryItemsCount+1) > (g_paginationMaxLinesPerPage*g_paginationLinesMaxItemsPossiblePerLine) ) {
              onComplete(albumIdx, pageNumber);
              return;
            }
          }
          
          if( currentCounter > firstCounter ) {
            g_galleryItemsCount++;
            
            var $newDiv=thumbnailBuild(item, idx, eltOpacity, foundDesc);

            thumbnailPositionContent($newDiv);

            setThumbnailSize($newDiv, item);
            
            var checkImageSize=(g_options.thumbnailHeight == 'auto' && g_ngItems[idx].thumbHeight == 0) || ( g_options.thumbnailWidth == 'auto' && g_ngItems[idx].thumbWidth == 0 ),
            $p=$newDiv.detach();
            if( checkImageSize ) {
              $p.css('opacity',0);
            }
            else {
              $p.css('opacity',eltOpacity);
            }
            $p.appendTo($g_containerThumbnails);

            if( (g_options.thumbnailHeight == 'auto' && item.thumbHeight > 0) || (g_options.thumbnailHeight == 'auto' && item.thumbWidth > 0) ) {
              ResizeGallery();
            }

            $p.removeClass('nanogalleryHideElement');

            // display animation (fadeIn)
            if( !checkImageSize ) {
              if( g_options.thumbnailDisplayTransition ) {
                if( g_thumbnailDisplayInterval > 0 ) {
                  $p.delay(g_galleryItemsCount*g_thumbnailDisplayInterval).fadeTo(150, 1);
                }
                else {
                  $p.fadeTo(150, 1);
                }
              }
            }

            // image lazy load
            if( g_options.thumbnailLazyLoad ) {
              if( !endInViewportTest ) {
                if( inViewport($newDiv, g_thumbnailLazyLoadTreshold) ) {
                  $newDiv.find('img').attr('src',item.thumbsrc);
                  startInViewportTest=true;
                }
                else {
                  if( startInViewportTest ) { endInViewportTest=true; }
                }
              }
            }
            

            // CSS init depending on choosen hover effect
            ThumbnailInit($newDiv);
            
            /*jQuery(newDiv).nanoHoverIntent({
              over: function() {
                //e.preventDefault();
                ThumbnailHover(this);
                return;
              },
              out: function() {
                //e.preventDefault();
                ThumbnailHoverOut(this);
                return;
              },
              interval: 50,
              timeout: 150
            });*/

          }
        }
        idx++;
      }

      if( idx < l ) {
        SetGalleryToolbarWidth(pageNumber);
        setTimeout(arguments.callee,0);
      }
      else {
        onComplete(albumIdx, pageNumber);
      }
    })();
    
  }
  
  function renderGallery2Complete( albumIdx, pageNumber ) {
    $g_containerThumbnails.on('mouseenter','.nanoGalleryThumbnailContainer', function(){
        ThumbnailHover(this);
      }).on('mouseleave','.nanoGalleryThumbnailContainer', function(){
		ThumbnailHoverOut(this);
      }).on('touchstart','.nanoGalleryThumbnailContainer', function(event){
        event.preventDefault();	// --> no click event
        ThumbnailClick(this);
        //ThumbnailHover(this);
        //g_touched=true;
      }).on('click','.nanoGalleryThumbnailContainer', function(event){

        var $this = jQuery(this);

        //updatePreviousTouched(e);

        //if(g_touched) { 
        //  if ($this.data('clicked_once')) {
        //    $this.data('clicked_once', false);
        //      e.stopPropagation();
        //      ThumbnailClick(this);
        //      return true;
        //  }
        //  else {
        //    ThumbnailHover(this);
        //      e.preventDefault();
        //      //$this.trigger("mouseenter").data('clicked_once', true);    
        //  }
        //}
        //g_touched = false;

        ThumbnailClick(this);
        
        return;
      });    
    
    SetGalleryWidth(pageNumber);
    SetGalleryToolbarWidth(pageNumber);

    managePagination(albumIdx,pageNumber);

    
    g_containerThumbnailsDisplayed=true;
    
  }

  function thumbnailBuild( item, idx, eltOpacity, foundDesc ) {
    var newElt=[];
    var newEltIdx=0;
    newElt[newEltIdx++]='<div class="nanoGalleryThumbnailContainer nanogalleryHideElement" style="display:inline-block,opacity:'+eltOpacity+'"><div class="subcontainer" style="display: inline-block">';
    
    var src='';
    if( g_options.thumbnailLazyLoad ) {
      src="//:0";
    }
     else {
      src=item.thumbsrc;
    }
    
    var checkImageSize=false;
    if( g_options.thumbnailHeight == 'auto' ) {
      newElt[newEltIdx++]='<div class="imgContainer" style="width:'+g_options.thumbnailWidth+'px;"><img class="image" src='+src+' style="width:'+g_options.thumbnailWidth+'px;"></div>';
      if( g_ngItems[idx].thumbHeight == 0 ) { checkImageSize=true; }
    }
    else if( g_options.thumbnailWidth == 'auto' ) {
        newElt[newEltIdx++]='<div class="imgContainer" style="height:'+g_options.thumbnailHeight+'px;"><img class="image" src='+src+' style="height:'+g_options.thumbnailHeight+'px;"></div>';
        if( g_ngItems[idx].thumbWidth == 0 ) { checkImageSize=true; }
      }
      else {
        newElt[newEltIdx++]='<div class="imgContainer" style="width:'+g_options.thumbnailWidth+'px;height:'+g_options.thumbnailHeight+'px;"><img class="image" src='+src+' style="max-width:'+g_options.thumbnailWidth+'px;max-height:'+g_options.thumbnailHeight+'px;"></div>';
      }

    var sTitle=getThumbnailTitle(item);
    var sDesc=getTumbnailDescription(item);
    if( item.kind == 'album' ) {
      // ALBUM
      if( g_options.thumbnailLabel.display == true ) {
        newElt[newEltIdx++]='<div class="labelImage" style="width:'+g_options.thumbnailWidth+'px;max-height:'+g_options.thumbnailHeight+'px;"><div class="labelFolderTitle">'+sTitle+'</div><div class="labelDescription">'+sDesc+'</div></div>';
      }
    }
    else {
      // IMAGE
      if( g_options.thumbnailLabel.display == true ) {
        if( foundDesc && sDesc.length == 0 && g_options.thumbnailLabel.position == 'onBottom' ) { sDesc='&nbsp;'; }
        newElt[newEltIdx++]='<div class="labelImage" style="width:'+g_options.thumbnailWidth+'px;max-height:'+g_options.thumbnailHeight+'px;"><div class="labelImageTitle">'+sTitle+'</div><div class="labelDescription">'+sDesc+'</div></div>';
      }
    }
    newElt[newEltIdx++]='</div></div>';
    
    var $newDiv =jQuery(newElt.join('')).appendTo($g_containerThumbnailsHidden); //.animate({ opacity: 1},1000, 'swing');  //.show('slow'); //.fadeIn('slow').slideDown('slow');
    item.$elt=$newDiv;
    $newDiv.data('index',idx);
    $newDiv.find('img').data('index',idx);

    if( checkImageSize ) {
      $newDiv.imagesLoaded().always( function( instance ) {//TODO error with isotope $newDiv.imagesLoaded(...).always is not a function
        var item=g_ngItems[jQuery(instance.images[0].img).data('index')];
        var b=false;

        if( item.thumbHeight != instance.images[0].img.naturalHeight ) {
          item.thumbHeight=instance.images[0].img.naturalHeight;
          var ratio=instance.images[0].img.naturalHeight/instance.images[0].img.naturalWidth;
          var h=g_options.thumbnailWidth*ratio;
          item.$elt.find('.imgContainer').height(h);
          item.thumbRealHeight=h+g_containerOuterHeight;
          item.$elt.height(item.thumbRealHeight);
          b=true;
        }
        if( item.thumbWidth != instance.images[0].img.naturalWidth ) {
          item.thumbWidth=instance.images[0].img.naturalWidth;
          var ratio=instance.images[0].img.naturalWidth/instance.images[0].img.naturalHeight;
          var w=g_options.thumbnailHeight*ratio;
          item.$elt.find('.imgContainer').width(w);
          item.thumbRealWidth=w+g_containerOuterWidth;
          item.$elt.width(item.thumbRealWidth);
          b=true;
        }
        if( b ) {
          ResizeGallery();
          if( g_options.thumbnailDisplayTransition ) {
            if( g_thumbnailDisplayInterval > 0 ) {
              item.$elt.delay(g_galleryItemsCount*g_thumbnailDisplayInterval).fadeTo(150, 1);
            }
            else {
              item.$elt.fadeTo(150, 1);
            }
          }
          else {
            item.$elt.css('opacity',1);
          }
        }
      });
    }
    
    return $newDiv;
  }
  
  function getThumbnailTitle( item ) {
    var sTitle=item.title;
    if( g_options.thumbnailLabel.display == true ) {
      if( sTitle === undefined || sTitle.length == 0 ) { sTitle='&nbsp;'; }

      if( g_i18nTranslations.thumbnailImageTitle != '' ) {
        sTitle=g_i18nTranslations.thumbnailImageTitle;
      }
      if( g_options.thumbnailLabel.titleMaxLength > 3 && sTitle.length > g_options.thumbnailLabel.titleMaxLength ){
        sTitle=sTitle.substring(0,g_options.thumbnailLabel.titleMaxLength)+'...';
      }
    }
    
    return sTitle;
  }

  function getTumbnailDescription( item ) {
    var sDesc='';
    if( g_options.thumbnailLabel.displayDescription == true ) { 
      if( item.kind == 'album' ) {
        if( g_i18nTranslations.thumbnailImageDescription !='' ) {
          sDesc=g_i18nTranslations.thumbnailAlbumDescription;
        }
        else {
          sDesc=item.description;
        }
      }
      else {
        if( g_i18nTranslations.thumbnailImageDescription !='' ) {
          sDesc=g_i18nTranslations.thumbnailImageDescription;
        }
        else {
          sDesc=item.description;
        }
      }
      if( g_options.thumbnailLabel.descriptionMaxLength > 3 && sDesc.length > g_options.thumbnailLabel.descriptionMaxLength ){
        sDesc=sDesc.substring(0,g_options.thumbnailLabel.descriptionMaxLength)+'...';
      }
    }
    
    return sDesc;
  }
  
  function setThumbnailSize( $elt, item ) {
    if( g_options.thumbnailHeight == 'auto' ) {
      if( item.thumbHeight > 0 ) {
        var ratio=item.thumbHeight/item.thumbWidth;
        $elt.find('.imgContainer').height(g_options.thumbnailWidth*ratio);
        item.thumbRealHeight=g_options.thumbnailWidth*ratio+g_containerOuterHeight;
        if( g_options.thumbnailLabel.position == 'onBottom' ) {
          $elt.height(item.thumbRealHeight+$elt.find('.labelImage').outerHeight(true));     // for correct image centering
        }
        else {
          $elt.height(item.thumbRealHeight);
        }
      }
      item.thumbRealWidth=g_oneThumbnailWidth;
    }
    else if( g_options.thumbnailWidth == 'auto' ) {
        if( item.thumbWidth > 0 ) {
          var ratio=item.thumbHeight/item.thumbWidth;
          $elt.find('.imgContainer').width(g_options.thumbnailHeight*ratio);
          item.thumbRealWidth=g_options.thumbnailHeight*ratio+g_containerOuterWidth;
          $elt.width(item.thumbRealWidth);
        }
        item.thumbRealHeight=g_oneThumbnailHeight;
      }
      else {
        if( g_options.thumbnailLabel.position == 'onBottom' ) {
          $elt.width(g_oneThumbnailWidth).height(g_oneThumbnailHeight+$elt.find('.labelImage').outerHeight(true));     // for correct image centering
        }
        else {
          $elt.width(g_oneThumbnailWidth).height(g_oneThumbnailHeight);     // for correct image centering
        }
        item.thumbRealHeight=g_oneThumbnailHeight;
        item.thumbRealWidth=g_oneThumbnailWidth;
      }
  }
  
  function thumbnailPositionContent( $elt ) {
    switch( g_options.thumbnailLabel.position ){
      case 'onBottom':
        $elt.find('.labelImage').css({'top':'0', 'position':'relative'});
        $elt.find('.labelImageTitle').css('white-space','nowrap');
        $elt.find('.labelFolderTitle').css('white-space','nowrap');
        $elt.find('.labelDescription').css('white-space','nowrap');
        break;
      case 'overImageOnTop':
        $elt.find('.labelImage').css('top','0');
        break;
      case 'overImageOnMiddle':
        $elt.find('.labelImage').css({'top':'0', 'height':'100%'});
        $elt.find('.labelFolderTitle').css({'width':'100%','position':'absolute','bottom':'50%'});
        $elt.find('.labelImageTitle').css({'width':'100%','position':'absolute','bottom':'50%'});
        $elt.find('.labelDescription').css({'width':'100%','position':'absolute','top':'50%'});
        break;
      case 'overImageOnBottom':
      default :
        g_options.thumbnailLabel.position='overImageOnBottom';
        $elt.find('.labelImage').css('bottom','0');
        break;
    }
  }
  
  function ThumbnailClick( elt ) {
    var n=jQuery(elt).data('index');
    if( g_options.touchAnimation == false || g_ngItems[n].hovered === true ) {
      // open URL
      if( g_ngItems[n].destinationURL !== undefined && g_ngItems[n].destinationURL.length >0 ) {
        window.location = g_ngItems[n].destinationURL;
        return;
      }

      if( g_ngItems[n].kind == 'album' ) {
        switch( g_options.kind ) {
          case '':
            DisplayAlbum(n,true);
            break;
          case 'flickr':               // Open Flickr photoset
            FlickrProcessItems(n, false, -1, true);
            break;
          case 'picasa':               // Open Picasa/Google+ album
          default:
            PicasaProcessItems(n, false, -1, true);
            break;
        }
      }
      else {
        // Display image
        DisplayImage(n,false);
      }
    }
    else {
      // hover effect on click --> touchscreen
      ThumbnailHoverOutAll();
      ThumbnailHover(elt);
    }
  }
  
  
  function ThumbnailHoverOutAll() {
  var l=g_ngItems.length;
    for( var i=0; i < l ; i++ ) {
      if( g_ngItems[i].hovered === true ) {
        ThumbnailHoverOut(g_ngItems[i].$elt);
      }
    }
  }
  
  function ThumbnailInit( elt ) {
    var n=jQuery(elt).data("index");
    if( n == undefined ) { return; }    // required because can be fired on ghost elements
    var item=g_ngItems[n];

    for( j=0; j<g_thumbnailHoverEffect.length; j++) {
      var useTransitPlugin = false;
      switch(g_thumbnailHoverEffect[j].name ) {

        case 'imageSplit4':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            elt.find('.subcontainer').css({'overflow':'hidden','position':'relative','width':'100%','height':'100%'});
            elt.find('.subcontainer').prepend(elt.find('.imgContainer').clone());
            elt.find('.subcontainer').prepend(elt.find('.imgContainer').clone());
            var w=elt.find('.subcontainer').width();
            var h=elt.find('.subcontainer').height();
            var s='rect(0px, '+w/2+'px, '+h/2+'px, 0px)'
            elt.find('.imgContainer').eq(0).css({'right':'0%', 'top':'0%', 'clip':s, 'position':'absolute'});
            s='rect(0px, '+w+'px, '+h/2+'px, '+w/2+'px)'
            elt.find('.imgContainer').eq(1).css({'left':'0%', 'top':'0%', 'clip':s, 'position':'absolute'});
            s='rect('+h/2+'px, '+w+'px, '+h+'px, '+w/2+'px)'
            elt.find('.imgContainer').eq(2).css({'left':'0%', 'bottom':'0%', 'clip':s, 'position':'absolute'});
            s='rect('+h/2+'px, '+w/2+'px, '+h+'px, 0px)'
            elt.find('.imgContainer').eq(3).css({'right':'0%', 'bottom':'0%', 'clip':s, 'position':'absolute'});
            setElementOnTop('', jQuery(elt).find('.imgContainer'));
          }
          break;
        case 'imageSplitVert':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            elt.find('.subcontainer').css({'overflow':'hidden','position':'relative','width':'100%','height':'100%'});
            elt.find('.subcontainer').prepend(elt.find('.imgContainer').clone());
            var w=elt.find('.subcontainer').width();
            var h=elt.find('.subcontainer').height();
            var s='rect(0px, '+w/2+'px, '+h+'px, 0px)'
            elt.find('.imgContainer').eq(0).css({'right':'0%', 'clip':s, 'position':'absolute'});
            s='rect(0px, '+w+'px, '+h+'px, '+w/2+'px)'
            elt.find('.imgContainer').eq(1).css({'left':'0%', 'clip':s, 'position':'absolute'});
            setElementOnTop('', jQuery(elt).find('.imgContainer'));
          }
          break;
          
        case 'labelSplit4':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            elt.find('.subcontainer').css({'overflow':'hidden','position':'relative'});
            elt.find('.labelImage').clone().appendTo(elt.find('.subcontainer'));
            elt.find('.labelImage').clone().appendTo(elt.find('.subcontainer'));
            var w=elt.find('.subcontainer').width();
            var h=elt.find('.subcontainer').height();

            var s='rect(0px, '+w/2+'px, '+h/2+'px, 0px)'
            elt.find('.labelImage').eq(0).css({'right':'0%', 'top':'0%', 'clip':s });
            s='rect(0px, '+w+'px, '+h/2+'px, '+w/2+'px)'
            elt.find('.labelImage').eq(1).css({'left':'0%', 'top':'0%', 'clip':s });
            s='rect('+h/2+'px, '+w+'px, '+h+'px, '+w/2+'px)'
            elt.find('.labelImage').eq(2).css({'left':'0%', 'top':'0%', 'clip':s });
            s='rect('+h/2+'px, '+w/2+'px, '+h+'px, 0px)'
            elt.find('.labelImage').eq(3).css({'right':'0%', 'top':'0%', 'clip':s });
          }
          break;
        case 'labelSplitVert':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            elt.find('.subcontainer').css({'overflow':'hidden','position':'relative'});
            elt.find('.labelImage').clone().appendTo(elt.find('.subcontainer'));
            var w=elt.find('.subcontainer').width();
            var h=elt.find('.subcontainer').height();
            var s='rect(0px, '+w/2+'px, '+h+'px, 0px)'
            elt.find('.labelImage').eq(0).css({'right':'0%', 'clip':s});
            s='rect(0px, '+w+'px, '+h+'px, '+w/2+'px)'
            elt.find('.labelImage').eq(1).css({'left':'0%', 'clip':s});
          }
          break;

        case 'labelAppearSplit4':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            elt.find('.subcontainer').css({'overflow':'hidden','position':'relative'});
            elt.find('.labelImage').clone().appendTo(elt.find('.subcontainer'));
            elt.find('.labelImage').clone().appendTo(elt.find('.subcontainer'));
            var w=elt.find('.subcontainer').width();
            var h=elt.find('.subcontainer').height();
            var s='rect(0px, '+w/2+'px, '+h/2+'px, 0px)'
            elt.find('.labelImage').eq(0).css({'right':'50%', 'top':'-50%', 'clip':s });
            s='rect(0px, '+w+'px, '+h/2+'px, '+w/2+'px)'
            elt.find('.labelImage').eq(1).css({'left':'50%', 'top':'-50%', 'clip':s });
            s='rect('+h/2+'px, '+w+'px, '+h+'px, '+w/2+'px)'
            elt.find('.labelImage').eq(2).css({'left':'50%', 'top':'50%', 'clip':s });
            s='rect('+h/2+'px, '+w/2+'px, '+h+'px, 0px)'
            elt.find('.labelImage').eq(3).css({'right':'50%', 'top':'50%', 'clip':s });
          }
          break;
        case 'labelAppearSplitVert':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            elt.find('.subcontainer').css({'overflow':'hidden','position':'relative'});
            elt.find('.labelImage').clone().appendTo(elt.find('.subcontainer'));
            var w=elt.find('.subcontainer').width();
            var h=elt.find('.subcontainer').height();
            var s='rect(0px, '+w/2+'px, '+h+'px, 0px)'
            elt.find('.labelImage').eq(0).css({'right':'50%', 'clip':s});
            s='rect(0px, '+w+'px, '+h+'px, '+w/2+'px)'
            elt.find('.labelImage').eq(1).css({'left':'50%', 'clip':s});
          }
          break;
        
        case 'scaleLabelOverImage':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            //jQuery(elt).css('overflow','hidden');
            setElementOnTop('', jQuery(elt).find('.imgContainer'));
            jQuery(elt).find('.labelImage').css({'opacity':'0', scale:0.5});
            jQuery(elt).find('.imgContainer').css({ 'scale':'1'});
            useTransitPlugin=true;
          }
          break;
        case 'overScale':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            jQuery(elt).css('overflow','hidden');
            setElementOnTop('', jQuery(elt).find('.labelImage'));
            jQuery(elt).find('.labelImage').css({'opacity':'0', scale:1.5});
            jQuery(elt).find('.imgContainer').css({ 'opacity': '1', 'scale':'1'});
            useTransitPlugin=true;
          }
          break;
        case 'overScaleOutside':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            setElementOnTop('', jQuery(elt).find('.labelImage'));
            jQuery(elt).find('.labelImage').css({'opacity':'0', scale:1.5});
            jQuery(elt).find('.imgContainer').css({ 'opacity': '1', 'scale':'1'});
            useTransitPlugin=true;
          }
          break;
        case 'rotateCornerBL':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            if( g_supportTransit ) {
              jQuery(elt).css('overflow','hidden');
              jQuery(elt).find('.labelImage').css('opacity','1').css({ rotate: '-90deg', 'transform-origin': '100% 100%' });
              jQuery(elt).find('.imgContainer').css({ rotate: '0', 'transform-origin': '100% 100%' });
              useTransitPlugin=true;
            }
          }
          break;
        case 'rotateCornerBR':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            if( g_supportTransit ) {
              jQuery(elt).css('overflow','hidden');
              jQuery(elt).find('.labelImage').css('opacity','1').css({ rotate: '90deg', 'transform-origin': '0% 100%' });
              jQuery(elt).find('.imgContainer').css({ rotate: '0', 'transform-origin': '0 100%' });
              useTransitPlugin=true;
            }
          }
          break;
        case 'imageRotateCornerBL':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            if( g_supportTransit ) {
              setElementOnTop(elt, jQuery(elt).find('.imgContainer'));
              jQuery(elt).css('overflow','hidden');
              jQuery(elt).find('.labelImage').css('opacity','1');
              jQuery(elt).find('.imgContainer').css({ rotate: '0', 'transform-origin': '100% 100%' });
              useTransitPlugin=true;
            }
          }
          break;
        case 'imageRotateCornerBR':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            if( g_supportTransit ) {
              setElementOnTop(elt, jQuery(elt).find('.imgContainer'));
              jQuery(elt).css('overflow','hidden');
              jQuery(elt).find('.labelImage').css('opacity','1');
              jQuery(elt).find('.imgContainer').css({ rotate: '0', 'transform-origin': '0 100%' });
              useTransitPlugin=true;
            }
          }
          break;
        case 'slideUp':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            jQuery(elt).css('overflow','hidden');
            jQuery(elt).find('.labelImage').css('opacity','1').css('top',item.thumbRealHeight);
            jQuery(elt).find('.imgContainer').css({'left':'0', 'top':'0'});
            useTransitPlugin=true;
          }
          break;
        case 'slideDown':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            jQuery(elt).css('overflow','hidden');
            jQuery(elt).find('.labelImage').css('opacity','1').css('top',-item.thumbRealHeight);
            jQuery(elt).find('.imgContainer').css({'left':'0', 'top':'0'});
            useTransitPlugin=true;
          }
          break;
        case 'slideRight':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            jQuery(elt).css('overflow','hidden');
            jQuery(elt).find('.labelImage').css('opacity','1').css('left',-item.thumbRealWidth);
            jQuery(elt).find('.imgContainer').css({'left':'0', 'top':'0'});
            useTransitPlugin=true;
          }
          break;
        case 'slideLeft':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            jQuery(elt).css('overflow','hidden');
            jQuery(elt).find('.labelImage').css('opacity','1').css('left',item.thumbRealWidth);
            jQuery(elt).find('.imgContainer').css({'left':'0', 'top':'0'});
            useTransitPlugin=true;
          }
          break;
        case 'imageSlideUp':
        case 'imageSlideDown':
        case 'imageSlideRight':
        case 'imageSlideLeft':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            setElementOnTop(elt, jQuery(elt).find('.imgContainer'));
            jQuery(elt).css('overflow','hidden');
            jQuery(elt).find('.labelImage').css('opacity','1');
            jQuery(elt).find('.imgContainer').css({'left':'0', 'top':'0'});
            useTransitPlugin=true;
          }
          break;
        case 'labelAppear':
        case 'labelAppear75':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            //jQuery(elt).find('.labelImage').css('opacity','0');
            var c='rgb('+g_custGlobals.oldLabelRed+','+g_custGlobals.oldLabelGreen+','+g_custGlobals.oldLabelBlue+',0)';
            jQuery(elt).find('.labelImage').css('backgroundColor',c);
            jQuery(elt).find('.labelImageTitle').css('opacity','0');
            jQuery(elt).find('.labelFolderTitle').css('opacity','0');
            jQuery(elt).find('.labelDescription').css('opacity','0');
            useTransitPlugin=false;
          }
          break;
        case 'labelSlideUpTop':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            jQuery(elt).css('overflow','hidden');
            jQuery(elt).find('.labelImage').css({'top':item.thumbRealHeight, 'bottom':'none'});
            useTransitPlugin=true;
          }
          break;
        case 'labelSlideUp':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            jQuery(elt).css('overflow','hidden');
            jQuery(elt).find('.labelImage').css({'bottom':-item.thumbRealHeight,'top':'none'});
            useTransitPlugin=true;
          }
          break;
        case 'labelSlideDown':
          if( g_options.thumbnailLabel.position != 'onBottom' ) {
            jQuery(elt).css('overflow','hidden');
            jQuery(elt).find('.labelImage').css({'top':-item.thumbRealHeight, 'bottom':'none'});
            useTransitPlugin=true;
          }
          break;
        case 'descriptionSlideUp':
          if( g_options.thumbnailLabel.position == 'overImageOnBottom' ) {
            jQuery(elt).css('overflow','hidden');
            var p=item.thumbRealHeight - (jQuery(elt).find('.labelImage').outerHeight(true)-jQuery(elt).find('.labelImage').height())/2 - g_oneThumbnailLabelTitleHeight +'px';
            jQuery(elt).find('.labelImage').css({'top':p, 'bottom':null});
            useTransitPlugin=true;
          }
          break;

        case 'imageFlipHorizontal':
          if( g_supportTransit ) {
            var n= Math.round(item.thumbRealHeight*1.2) + 'px';
            //jQuery(elt).css({ perspective: n, 'backface-visibility': 'hidden' });
            //jQuery(elt).find('.subcontainer').css({perspective: n, 'rotateX': '0deg', 'transform-style':'preserve-3d', 'backface-visibility': 'hidden'});
            //jQuery(elt).find('.imgcontainer').css({perspective: n, rotateX: '0deg', 'backface-visibility': 'hidden'});
            jQuery(elt).find('.imgContainer').css({perspective: n, rotateX: '0deg', 'backface-visibility': 'hidden'});
            jQuery(elt).find('.labelImage').css({ perspective: n,rotateX: '180deg', 'backface-visibility': 'hidden','height':item.thumbRealHeight,'opacity':'1' });
            useTransitPlugin=true;
          }
          break;
        case 'imageFlipVertical':
          if( g_supportTransit ) {
            var n= Math.round(item.thumbRealWidth*1.2) + 'px';
            //setElementOnTop(elt, jQuery(elt).find('.imgContainer'));
            //jQuery(elt).find('.subcontainer').css({perspective: n, 'rotateY': '0deg', 'transform-style':'preserve-3d', 'backface-visibility': 'hidden'});
            jQuery(elt).find('.imgContainer').css({perspective: n, rotateY: '0deg', 'backface-visibility': 'hidden'});
            jQuery(elt).find('.labelImage').css({ perspective: n, rotateY: '180deg', 'backface-visibility': 'hidden','height':item.thumbRealHeight,'opacity':'1' });


            //jQuery(elt).find('.labelImage').css({ perspective: n, rotateY: '180deg', 'backface-visibility': 'hidden', 'opacity':'1', 'height':item.thumbRealHeight });
            //jQuery(elt).find('.imgContainer').css({ perspective: n, rotateY: '0deg', 'backface-visibility': 'hidden' });
            useTransitPlugin=true;
          }
          break;
        case 'flipHorizontal':
          var n= Math.round(item.thumbRealHeight*1.2) + 'px';
          jQuery(elt).find('.labelImage').css({ perspective: n, rotateX: '180deg', 'backface-visibility': 'hidden', 'opacity':'1', 'height':'100%' });
          useTransitPlugin=true;
          break;
        case 'flipVertical':
          var n= Math.round(item.thumbRealWidth*1.2) + 'px';
          jQuery(elt).find('.subcontainer').css({ perspective: n, rotateY: '0deg'});
          jQuery(elt).find('.labelImage').css({ perspective: n, rotateY: '180deg', 'backface-visibility': 'hidden', 'opacity':'1', 'height':'100%' });
          useTransitPlugin=true;
          break;
        case 'borderLighter':
        case 'borderDarker':
          useTransitPlugin=false;
          break;
        case 'imageScale150':
          jQuery(elt).css('overflow','hidden');
        case 'imageScale150Outside':
          useTransitPlugin=true;
          break;

      }
      if( !g_supportTransit ) { useTransitPlugin=false; }
      if( useTransitPlugin ) { //|| g_supportTransit ) {
        if( g_thumbnailHoverEffect[j].easing == 'swing' ) { g_thumbnailHoverEffect[j].easing = 'ease'; }
        if( g_thumbnailHoverEffect[j].easingBack == 'swing' ) { g_thumbnailHoverEffect[j].easingBack = 'ease'; }
        g_thumbnailHoverEffect[j].method='transit';
      }
      else if( g_thumbnailHoverEffect[j].easing != 'swing' || g_thumbnailHoverEffect[j].easingBack != 'swing') { 
          g_thumbnailHoverEffect[j].method='transit';
          if( g_thumbnailHoverEffect[j].easing == 'swing' ) { g_thumbnailHoverEffect[j].easing = 'ease'; }
          if( g_thumbnailHoverEffect[j].easingBack == 'swing' ) { g_thumbnailHoverEffect[j].easingBack = 'ease'; }
        }
        else {
          g_thumbnailHoverEffect[j].method='jquery';
        }
    }

  };

  function ThumbnailHover( elt ) {
    //if( jQueryMinVersion('1.9') ) { jQuery(elt).find('*').finish(); }
    jQuery(elt).find('*').stop(true,true);
    var n=jQuery(elt).data('index');
    if( n == undefined ) { return; }    // required because can be fired on ghost elements
    var item=g_ngItems[n];
    item.hovered=true;

    try {
      for( j=0; j<g_thumbnailHoverEffect.length; j++) {
        switch(g_thumbnailHoverEffect[j].name ) {
          case 'imageSplit4':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').eq(0).transition({'right':'50%', 'top':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').eq(1).transition({'left':'50%', 'top':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').eq(2).transition({'left':'50%', 'bottom':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').eq(3).transition({'right':'50%', 'bottom':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else{
                jQuery(elt).find('.imgContainer').eq(0).animate({'right':'50%', 'top':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').eq(1).animate({'left':'50%', 'top':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').eq(2).animate({'left':'50%', 'bottom':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').eq(3).animate({'right':'50%', 'bottom':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'imageSplitVert':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').eq(0).transition({'right':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').eq(1).transition({'left':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else{
                jQuery(elt).find('.imgContainer').eq(0).animate({'right':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').eq(1).animate({'left':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'labelSplit4':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').eq(0).transition({'right':'50%', 'top':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(1).transition({'left':'50%', 'top':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(2).transition({'left':'50%', 'top':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(3).transition({'right':'50%', 'top':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else{
                jQuery(elt).find('.labelImage').eq(0).animate({'right':'50%', 'top':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(1).animate({'left':'50%', 'top':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(2).animate({'left':'50%', 'top':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(3).animate({'right':'50%', 'top':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'labelSplitVert':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').eq(0).transition({'right':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(1).transition({'left':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.labelImage').eq(0).animate({'right':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(1).animate({'left':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'labelAppearSplit4':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').eq(0).transition({'right':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(1).transition({'left':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(2).transition({'left':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(3).transition({'right':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else{
                jQuery(elt).find('.labelImage').eq(0).animate({'right':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(1).animate({'left':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(2).animate({'left':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(3).animate({'right':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'labelAppearSplitVert':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').eq(0).transition({'right':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(1).transition({'left':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.labelImage').eq(0).animate({'right':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(1).animate({'left':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'scaleLabelOverImage':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').transition({ 'opacity': '1', 'scale':'1'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').transition({ 'scale':'0.5'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.labelImage').animate({ 'opacity': '1', 'scale':'1'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').animate({ 'scale':'0.5'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'overScale':
          case 'overScaleOutside':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').transition({ 'opacity': '1', 'scale':'1'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').transition({ 'opacity': '0', 'scale':'0.5'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.labelImage').animate({ 'opacity': '1', 'scale':'1'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').animate({ 'opacity': '0', 'scale':'0.5'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'imageInvisible':
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              jQuery(elt).find('.imgContainer').transition({ 'opacity': '0'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            else {
              jQuery(elt).find('.imgContainer').animate({ 'opacity': '0'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'rotateCornerBL':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              if( g_options.thumbnailLabel.position != 'onBottom' ) {
                jQuery(elt).find('.labelImage').transition({ rotate: '0deg'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').transition({ rotate: '90deg'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'rotateCornerBR':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              if( g_options.thumbnailLabel.position != 'onBottom' ) {
                jQuery(elt).find('.labelImage').transition({ rotate: '0deg'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').transition({ rotate: '-90deg'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'imageRotateCornerBL':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              if( g_options.thumbnailLabel.position != 'onBottom' ) {
                jQuery(elt).find('.imgContainer').transition({ rotate: '90deg'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'imageRotateCornerBR':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              if( g_options.thumbnailLabel.position != 'onBottom' ) {
                jQuery(elt).find('.imgContainer').transition({ rotate: '-90deg'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'slideUp':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              var n= '-' + item.thumbRealHeight + 'px';
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').transition({ 'top': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').transition({ 'top': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.imgContainer').animate({ 'top': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').animate({ 'top': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'slideDown':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              var n= item.thumbRealHeight + 'px';
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').transition({ 'top': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').transition({ 'top': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.imgContainer').animate({ 'top': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').animate({ 'top': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'slideRight':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              var n= item.thumbRealWidth + 'px';
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').transition({ 'left': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').transition({ 'left': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.imgContainer').animate({ 'left': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').animate({ 'left': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'slideLeft':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              var n= '-'+ item.thumbRealWidth + 'px';
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').transition({ 'left': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').transition({ 'left': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.imgContainer').animate({ 'left': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').animate({ 'left': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'imageSlideUp':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              var n= '-' + item.thumbRealHeight + 'px';
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').transition({ 'top': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.imgContainer').animate({ 'top': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'imageSlideDown':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              var n= item.thumbRealHeight + 'px';
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').transition({ 'top': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.imgContainer').animate({ 'top': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'imageSlideLeft':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              var n= '-' + item.thumbRealWidth + 'px';
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').transition({ 'left': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.imgContainer').animate({ 'left': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'imageSlideRight':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              var n= item.thumbRealWidth + 'px';
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').transition({ 'left': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.imgContainer').animate({ 'left': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'labelAppear':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              var c='rgba('+g_custGlobals.oldLabelRed+','+g_custGlobals.oldLabelGreen+','+g_custGlobals.oldLabelBlue+',1)';
              jQuery(elt).find('.labelImage').animate({ 'backgroundColor': c}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).find('.labelImageTitle').animate({ 'opacity': '1'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).find('.labelFolderTitle').animate({ 'opacity': '1'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).find('.labelDescription').animate({ 'opacity': '1'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              //jQuery(elt).find('.labelImage').animate({ 'opacity': '1'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'labelAppear75':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              //jQuery(elt).find('.labelImage').animate({ 'opacity': '0.75'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              var c='rgba('+g_custGlobals.oldLabelRed+','+g_custGlobals.oldLabelGreen+','+g_custGlobals.oldLabelBlue+',0.75)';
              jQuery(elt).find('.labelImage').animate({ 'backgroundColor': c}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).find('.labelImageTitle').animate({ 'opacity': '1'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).find('.labelFolderTitle').animate({ 'opacity': '1'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).find('.labelDescription').animate({ 'opacity': '1'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'labelSlideDown':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').transition({ 'top': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.labelImage').animate({ 'top': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'labelSlideUpTop':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').transition({ 'top': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.labelImage').animate({ 'top': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'labelSlideUp':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').transition({ 'bottom': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.labelImage').animate({ 'bottom': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'descriptionSlideUp':
            if( g_options.thumbnailLabel.position == 'overImageOnBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').transition({ 'top': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.labelImage').animate({ 'top': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'labelOpacity50':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              jQuery(elt).find('.labelImage').transition({ 'opacity': 0.5 },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            else {
              jQuery(elt).find('.labelImage').animate({ 'opacity': 0.5 },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'imageOpacity50':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              jQuery(elt).find('.imgContainer').transition({ 'opacity': 0.5 },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            else {
              jQuery(elt).find('.imgContainer').animate({ 'opacity': 0.5 },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'borderLighter':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              jQuery(elt).transition({ 'borderColor': lighterColor(g_custGlobals.oldBorderColor,0.5) },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            else {
              //jQuery(elt).animate({ 'borderColor': lighterColor(g_custGlobals.oldBorderColor,0.5) },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).animate({ 'borderTopColor': lighterColor(g_custGlobals.oldBorderColor,0.5), 'borderRightColor': lighterColor(g_custGlobals.oldBorderColor,0.5), 'borderBottomColor': lighterColor(g_custGlobals.oldBorderColor,0.5), 'borderLeftColor': lighterColor(g_custGlobals.oldBorderColor,0.5) },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'borderDarker':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              jQuery(elt).transition({ 'borderColor': darkerColor(g_custGlobals.oldBorderColor,0.5) },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            else {
              //jQuery(elt).animate({ 'borderColor': darkerColor(g_custGlobals.oldBorderColor,0.5) },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).animate({ 'borderTopColor': darkerColor(g_custGlobals.oldBorderColor,0.5), 'borderRightColor': darkerColor(g_custGlobals.oldBorderColor,0.5), 'borderBottomColor': darkerColor(g_custGlobals.oldBorderColor,0.5), 'borderLeftColor': darkerColor(g_custGlobals.oldBorderColor,0.5) },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'imageScale150':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              jQuery(elt).find('img').transition({ scale: 1.5 },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            else {
              jQuery(elt).find('img').animate({ scale: 1.5 },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'imageScale150Outside':
            setElementOnTop('', elt);
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              jQuery(elt).find('img').transition({ scale: 1.5 },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            else {
              jQuery(elt).find('img').animate({ scale: 1.5 },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'scale120':
            setElementOnTop('', elt);
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              jQuery(elt).transition({ scale: 1.2 },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            else {
              jQuery(elt).animate({ scale: 1.2 },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'imageFlipHorizontal':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              setElementOnTop('', elt);
              var n= Math.round(item.thumbRealHeight*1.2) + 'px';
              //jQuery(elt).find('.subcontainer').transition({ rotateX: '180deg'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).find('.imgContainer').transition({ perspective: n, rotateX: '180deg'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).find('.labelImage').transition({ perspective: n, rotateX: '360deg'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'imageFlipVertical':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              setElementOnTop('', elt);
              var n= Math.round(item.thumbRealWidth*1.2) + 'px';
              jQuery(elt).find('.imgContainer').transition({ perspective: n, rotateY: '180deg'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).find('.labelImage').transition({ perspective: n, rotateY: '360deg'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              //jQuery(elt).find('.subcontainer').transition({ rotateY: '180deg'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'flipHorizontal':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              setElementOnTop('', elt);
              var n= Math.round(item.thumbRealHeight*1.2) + 'px';
              jQuery(elt).transition({ perspective: n, rotateX: '180deg'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'flipVertical':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              setElementOnTop('', elt);
              var n= Math.round(item.thumbRealWidth*1.2) + 'px';
              jQuery(elt).transition({ perspective: n, rotateY: '180deg'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'TEST':
            //jQuery(elt).find('img').stop(true, true);
            jQuery(elt).find('.subcontainer').transition({ scale: 0.8 },150).transition({ perspective: '50px', rotateX: '180deg'}, 300, 'ease').transition({ scale: 1 },150);
            break;
        }
      }
    }
    catch (e) { 
      nanoAlert( 'error on hover ' +e.message );
    }
  };
  
  function ThumbnailHoverOut( elt ) {
    //if( jQueryMinVersion('1.9') ) { jQuery(elt).find('*').finish(); }
    if( g_containerViewerDisplayed ) { return; }
    jQuery(elt).find('*').stop(true,false);
    var n=jQuery(elt).data("index");
    if( n == undefined ) { return; }    // required because can be fired on ghost elements
    var item=g_ngItems[n];
    item.hovered=false;

    try {
      for( j=0; j<g_thumbnailHoverEffect.length; j++) {
        switch(g_thumbnailHoverEffect[j].name ) {
          case 'imageSplit4':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').eq(0).transition({'right':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.imgContainer').eq(1).transition({'left':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.imgContainer').eq(2).transition({'left':'0%', 'bottom':'0%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.imgContainer').eq(3).transition({'right':'0%', 'bottom':'0%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
              else{
                jQuery(elt).find('.imgContainer').eq(0).animate({'right':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.imgContainer').eq(1).animate({'left':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.imgContainer').eq(2).animate({'left':'0%', 'bottom':'0%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.imgContainer').eq(3).animate({'right':'0%', 'bottom':'0%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
            }
          case 'imageSplitVert':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').eq(0).transition({'right':'0%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.imgContainer').eq(1).transition({'left':'0%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
              else {
                jQuery(elt).find('.imgContainer').eq(0).animate({'right':'0%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.imgContainer').eq(1).animate({'left':'0%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
            }
            break;
          case 'labelSplit4':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').eq(0).transition({'right':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(1).transition({'left':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(2).transition({'left':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(3).transition({'right':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else{
                jQuery(elt).find('.labelImage').eq(0).animate({'right':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(1).animate({'left':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(2).animate({'left':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(3).animate({'right':'0%', 'top':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'labelSplitVert':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').eq(0).transition({'right':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(1).transition({'left':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.labelImage').eq(0).animate({'right':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(1).animate({'left':'0%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'labelAppearSplit4':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').eq(0).transition({'right':'50%', 'top':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(1).transition({'left':'50%', 'top':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(2).transition({'left':'50%', 'top':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(3).transition({'right':'50%', 'top':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else{
                jQuery(elt).find('.labelImage').eq(0).animate({'right':'50%', 'top':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(1).animate({'left':'50%', 'top':'-50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(2).animate({'left':'50%', 'top':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').eq(3).animate({'right':'50%', 'top':'50%'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
          case 'labelAppearSplitVert':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').eq(0).transition({'right':'50%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.labelImage').eq(1).transition({'left':'50%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
              else {
                jQuery(elt).find('.labelImage').eq(0).animate({'right':'50%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.labelImage').eq(1).animate({'left':'50%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
            }
            break;

          case 'scaleLabelOverImage':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').transition({ 'opacity': '0', 'scale':'0.5'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.imgContainer').transition({ 'scale':'1'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
              else {
                jQuery(elt).find('.labelImage').animate({ 'opacity': '0', 'scale':'0.5'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.imgContainer').animate({ 'scale':'1'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
            }
            break;
          case 'overScale':
          case 'overScaleOutside':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').transition({ 'opacity': '0', 'scale':'1.5'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.imgContainer').transition({ 'opacity': '1', 'scale':'1'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
              else {
                jQuery(elt).find('.labelImage').animate({ 'opacity': '0', 'scale':'1.5'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.imgContainer').animate({ 'opacity': '1', 'scale':'1'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
            }
            break;
          case 'imageInvisible':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              jQuery(elt).find('.imgContainer').transition({ 'opacity': '1'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
            }
            else {
              jQuery(elt).find('.imgContainer').animate({ 'opacity': '1'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
            }
            break;
          case 'rotateCornerBL':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              if( g_options.thumbnailLabel.position != 'onBottom' ) {
                jQuery(elt).find('.labelImage').transition({ rotate: '-90deg'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').transition({ rotate: '0deg'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'rotateCornerBR':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              if( g_options.thumbnailLabel.position != 'onBottom' ) {
                jQuery(elt).find('.labelImage').transition({ rotate: '90deg'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.imgContainer').transition({ rotate: '0deg'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'imageRotateCornerBL':
          case 'imageRotateCornerBR':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              if( g_options.thumbnailLabel.position != 'onBottom' ) {
                jQuery(elt).find('.imgContainer').transition({ 'rotate': '0'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack );
              }
            }
            break;
          case 'slideUp':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              var n= item.thumbRealHeight + 'px';
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').transition({ 'top': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').transition({ 'top': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.imgContainer').animate({ 'top': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').animate({ 'top': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'slideDown':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              var n= '-'+item.thumbRealHeight + 'px';
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').transition({ 'top': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').transition({ 'top': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.imgContainer').animate({ 'top': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').animate({ 'top': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'slideRight':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              var n= '-'+item.thumbRealWidth + 'px';
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').transition({ 'left': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').transition({ 'left': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.imgContainer').animate({ 'left': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').animate({ 'left': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'slideLeft':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              var n= item.thumbRealWidth + 'px';
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').transition({ 'left': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').transition({ 'left': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.imgContainer').animate({ 'left': 0},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
                jQuery(elt).find('.labelImage').animate({ 'left': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'imageSlideUp':
          case 'imageSlideDown':
          case 'imageSlideLeft':
          case 'imageSlideRight':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.imgContainer').transition({ 'top': '0'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.imgContainer').transition({ 'left': '0'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
              else {
                jQuery(elt).find('.imgContainer').animate({ 'top': '0'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
                jQuery(elt).find('.imgContainer').animate({ 'left': '0'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
            }
            break;
          case 'labelAppear':
          case 'labelAppear75':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              //jQuery(elt).find('.labelImage').animate({ 'opacity': '0'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              var c='rgb('+g_custGlobals.oldLabelRed+','+g_custGlobals.oldLabelGreen+','+g_custGlobals.oldLabelBlue+',0)';
              jQuery(elt).find('.labelImage').animate({ 'backgroundColor': c},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).find('.labelImageTitle').animate({ 'opacity': '0'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).find('.labelFolderTitle').animate({ 'opacity': '0'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).find('.labelDescription').animate({ 'opacity': '0'},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'labelSlideDown':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').transition({ 'top': '-99%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
              else {
                jQuery(elt).find('.labelImage').animate({ 'top': '-99%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
            }
            break;
          case 'labelSlideUpTop':
            var n= item.thumbRealHeight + 'px';
            if( g_options.thumbnailLabel.position == 'overImageOnBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').transition({ 'top': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.labelImage').animate({ 'top': n},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;

          case 'labelSlideUp':
            if( g_options.thumbnailLabel.position != 'onBottom' ) {
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').transition({ 'bottom': '-99%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
              else {
                jQuery(elt).find('.labelImage').animate({ 'bottom': '-99%'},g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              }
            }
            break;
          case 'descriptionSlideUp':
            if( g_options.thumbnailLabel.position == 'overImageOnBottom' ) {
              var p=item.thumbRealHeight - (jQuery(elt).find('.labelImage').outerHeight(true)-jQuery(elt).find('.labelImage').height())/2 - g_oneThumbnailLabelTitleHeight +'px';
              if( g_thumbnailHoverEffect[j].method == 'transit' ) {
                jQuery(elt).find('.labelImage').transition({ 'top': p},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
              else {
                jQuery(elt).find('.labelImage').animate({ 'top': p},g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              }
            }
            break;
          case 'labelOpacity50':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              jQuery(elt).find('.labelImage').transition({ 'opacity': g_custGlobals.oldLabelOpacity },g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
            }
            else {
              jQuery(elt).find('.labelImage').animate({ 'opacity': g_custGlobals.oldLabelOpacity },g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
            }
            break;
          case 'imageOpacity50':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              jQuery(elt).find('.imgContainer').transition({ 'opacity': 1 },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            else {
              jQuery(elt).find('.imgContainer').animate({ 'opacity': 1 },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'borderLighter':
          case 'borderDarker':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              jQuery(elt).transition({ 'borderColor': g_custGlobals.oldBorderColor },g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
            }
            else {
              //jQuery(elt).animate({ 'borderColor': g_custGlobals.oldBorderColor },g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              jQuery(elt).animate({ 'borderTopColor': g_custGlobals.oldBorderColor, 'borderRightColor': g_custGlobals.oldBorderColor, 'borderBottomColor': g_custGlobals.oldBorderColor, 'borderLeftColor': g_custGlobals.oldBorderColor },g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'imageScale150':
          case 'imageScale150Outside':
        	jQuery(elt).css('z-index','auto');
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              jQuery(elt).find('img').transition({ scale: 1 },g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
            }
            else {
              jQuery(elt).find('img').animate({ scale: 1 },g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
            }
            break;
          case 'scale120':
          	jQuery(elt).css('z-index','auto');
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              jQuery(elt).transition({ scale: 1 },g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
            }
            else {
              jQuery(elt).animate({ scale: 1 },g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
            }
            break;
          case 'imageFlipHorizontal':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              var n= Math.round(item.thumbRealHeight*1.2) + 'px';
              //jQuery(elt).find('.subcontainer').transition({rotateX: '0deg'}, g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              jQuery(elt).find('.imgContainer').transition({ perspective: n, rotateX: '0deg'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).find('.labelImage').transition({ perspective: n, rotateX: '180deg'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'imageFlipVertical':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              var n= Math.round(item.thumbRealWidth*1.2) + 'px';
              //jQuery(elt).find('.subcontainer').transition({ rotateY: '0deg'}, g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
              jQuery(elt).find('.imgContainer').transition({ perspective: n, rotateY: '0deg'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
              jQuery(elt).find('.labelImage').transition({ perspective: n, rotateY: '180deg'}, g_thumbnailHoverEffect[j].duration, g_thumbnailHoverEffect[j].easing);
            }
            break;
          case 'flipHorizontal':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              var n= Math.round(g_oneThumbnailHeight*1.2) + 'px';
              jQuery(elt).transition({ perspective:n, rotateX: '0deg'}, g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
            }
            break;
          case 'flipVertical':
            if( g_thumbnailHoverEffect[j].method == 'transit' ) {
              var n= Math.round(item.thumbRealWidth*1.2) + 'px';
              jQuery(elt).transition({ perspective:n, rotateY: '0deg'}, g_thumbnailHoverEffect[j].durationBack, g_thumbnailHoverEffect[j].easingBack);
            }
            break;
          case 'TEST':
            //if( jQueryMinVersion('1.9') ) { jQuery(elt).find('.subcontainer').finish(); }
            jQuery(elt).find('.subcontainer').transition({ scale: 0.85 },150).transition({ perspective: '50px', rotateX: '0deg'}, 300, 'ease').transition({ scale: 1 },150);
            break;
        }
      }
    }
    catch (e) { 
      nanoAlert( 'error on hoverOut ' +e.message );
    }
  };

    

  // #########################
  // ##### IMAGE DISPLAY #####
  // #########################

  function DisplayImage( imageIdx ) {

    if( g_options.viewer == 'fancybox' ) {
      OpenFancyBox(imageIdx);
    }
    else {
      if( !g_containerViewerDisplayed ) {
        OpenInternalViewer(imageIdx);
      }
      else {
        DisplayInternalViewer(imageIdx, '');
      }
    }
  };
  function OpenInfoBox(){
		var lat=g_ngItems[g_viewerCurrentItemIdx].infobox.lat;
		var long=g_ngItems[g_viewerCurrentItemIdx].infobox.long;
		
		var info_box_css='ozio-nano-white-info-box-with-gmap';
		if (lat=='' || long==''){
			info_box_css='ozio-nano-white-info-box';
		}
	  
			 var html='';
			 html+='<div  class="nanoGalleryInfoBox '+info_box_css+' mfp-hide">';
			 html+='<div class="ozio-nano-infobox-middle">';
			 html+='	<dl class="odl-horizontal">';
			 html+=' 		<dt></dt><dd><img class="oimg-polaroid pi-image" alt="preview"/></dd>';
			 if (g_options.showInfoBoxAlbum){
				 html+=' 		<dt>'+g_i18nTranslations.infoBoxAlbum+'</dt><dd class="pi-album"></dd>';
			 }
			 if (g_options.showInfoBoxPhoto){
			 html+='	<dt>'+g_i18nTranslations.infoBoxPhoto+'</dt><dd class="pi-photo"></dd>';
			 }
			 if (g_options.showInfoBoxDate){
			 html+='					<dt>'+g_i18nTranslations.infoBoxDate+'</dt><dd class="pi-date"></dd>';
			 }
			 if (g_options.showInfoBoxDimensions){
			 html+='					<dt>'+g_i18nTranslations.infoBoxDimensions+'</dt><dd class="pi-dimensions"></dd>';
			 }
			 if (g_options.showInfoBoxFilename){
			 html+='					<dt>'+g_i18nTranslations.infoBoxFilename+'</dt><dd class="pi-filename"></dd>';
			 }
			 if (g_options.showInfoBoxFilesize){
			 html+='					<dt>'+g_i18nTranslations.infoBoxFileSize+'</dt><dd class="pi-filesize"></dd>';
			 }
			 if (g_options.showInfoBoxCamera){
			 html+='					<dt>'+g_i18nTranslations.infoBoxCamera+'</dt><dd class="pi-camera"></dd>';
			 }
			 if (g_options.showInfoBoxFocallength){
			 html+='					<dt>'+g_i18nTranslations.infoBoxFocalLength+'</dt><dd class="pi-focallength"></dd>';
			 }
			 if (g_options.showInfoBoxExposure){
			 html+='					<dt>'+g_i18nTranslations.infoBoxExposure+'</dt><dd class="pi-exposure"></dd>';
			 }
			 if (g_options.showInfoBoxFNumber){
			 html+='					<dt>'+g_i18nTranslations.infoBoxFNumber+'</dt><dd class="pi-fnumber"></dd>';
			 }
			 if (g_options.showInfoBoxISO){
			 html+='					<dt>'+g_i18nTranslations.infoBoxISO+'</dt><dd class="pi-iso"></dd>';
			 }
			 if (g_options.showInfoBoxMake){
			 html+='					<dt>'+g_i18nTranslations.infoBoxMake+'</dt><dd class="pi-make"></dd>';
			 }
			 if (g_options.showInfoBoxFlash){
			 html+='					<dt>'+g_i18nTranslations.infoBoxFlash+'</dt><dd class="pi-flash"></dd>';
			 }
			 if (g_options.showInfoBoxViews){
			 html+='					<dt>'+g_i18nTranslations.infoBoxViews+'</dt><dd class="pi-views"></dd>';
			 }
			 if (g_options.showInfoBoxComments){
			 html+='					<dt>'+g_i18nTranslations.infoBoxComments+'</dt><dd class="pi-comments"></dd>';
			 }
			 html+='				</dl>';
			 html+='				<div class="pi-map-container"></div>';
			 html+='				</div>';
			 html+='					<div class="pi-photo-buttons">';
			 if (g_options.showInfoBoxLink){
			 html+='					<a href="#" class="btn pi-link" target="_blank">';
			 html+='						↗ Google+';
			 html+='					</a>';
			 }
			 if (g_options.showInfoBoxDownload){
			 html+='					<a href="#" class="btn pi-download">';
			 html+='						⬇ Download';
			 html+='					</a>';
			 }
			 html+='					</div>';
			  		
			  $g_containerInfoBox=jQuery(html).appendTo('body');
/*	  
	  var infobox={};
	  infobox.album='-na-';
	  infobox.photo='-na-';
	  infobox.date='-na-';
	  infobox.dimensions='-na-';
	  infobox.filename='-na-';
	  infobox.filesize='-na-';
	  infobox.camera='-na-';
	  infobox.focallength='-na-';
	  infobox.exposure='-na-';
	  infobox.fnumber='-na-';
	  infobox.iso='-na-';
	  infobox.make='-na-';
	  infobox.flash='-na-';
	  infobox.views='-na-';
	  infobox.comments='-na-';
	  infobox.image=g_ngItems[g_viewerCurrentItemIdx].thumbsrc;
	  infobox.lat='34.5';
	  infobox.long='55.6';
	  infobox.link='#';
	  infobox.download='#';
	  g_ngItems[g_viewerCurrentItemIdx].infobox=infobox;
	*/  
	  $g_containerInfoBox.find('.pi-album').text(g_ngItems[g_viewerCurrentItemIdx].infobox.album);
	  $g_containerInfoBox.find('.pi-photo').text(g_ngItems[g_viewerCurrentItemIdx].infobox.photo);
	  $g_containerInfoBox.find('.pi-date').text(g_ngItems[g_viewerCurrentItemIdx].infobox.date);
	  $g_containerInfoBox.find('.pi-dimensions').text(g_ngItems[g_viewerCurrentItemIdx].infobox.dimensions);
	  $g_containerInfoBox.find('.pi-filename').text(g_ngItems[g_viewerCurrentItemIdx].infobox.filename);
	  $g_containerInfoBox.find('.pi-filesize').text(g_ngItems[g_viewerCurrentItemIdx].infobox.filesize);
	  $g_containerInfoBox.find('.pi-camera').text(g_ngItems[g_viewerCurrentItemIdx].infobox.camera);
	  $g_containerInfoBox.find('.pi-focallength').text(g_ngItems[g_viewerCurrentItemIdx].infobox.focallength);
	  $g_containerInfoBox.find('.pi-exposure').text(g_ngItems[g_viewerCurrentItemIdx].infobox.exposure);
	  $g_containerInfoBox.find('.pi-fnumber').text(g_ngItems[g_viewerCurrentItemIdx].infobox.fnumber);
	  $g_containerInfoBox.find('.pi-iso').text(g_ngItems[g_viewerCurrentItemIdx].infobox.iso);
	  $g_containerInfoBox.find('.pi-make').text(g_ngItems[g_viewerCurrentItemIdx].infobox.make);
	  $g_containerInfoBox.find('.pi-flash').text(g_ngItems[g_viewerCurrentItemIdx].infobox.flash);
	  $g_containerInfoBox.find('.pi-views').text(g_ngItems[g_viewerCurrentItemIdx].infobox.views);
	  $g_containerInfoBox.find('.pi-comments').text(g_ngItems[g_viewerCurrentItemIdx].infobox.comments);
	  $g_containerInfoBox.find('.pi-image').attr('src',g_ngItems[g_viewerCurrentItemIdx].infobox.image);
	  $g_containerInfoBox.find('.pi-link').attr('href',g_ngItems[g_viewerCurrentItemIdx].infobox.link);
	  $g_containerInfoBox.find('.pi-download').attr('href',g_ngItems[g_viewerCurrentItemIdx].infobox.download);
	  
	  jQuery.magnificPopup.open({
	    items: {
	      src: $g_containerInfoBox, // can be a HTML string, jQuery object, or CSS selector
	      type: 'inline',
	      closeBtnInside: true,
	      showCloseBtn: true,
	      enableEscapeKey: true,
	      modal: true
	    },
	    callbacks: {
	    	open: function(){
				var lat=g_ngItems[g_viewerCurrentItemIdx].infobox.lat;
				var long=g_ngItems[g_viewerCurrentItemIdx].infobox.long;
				
				if (lat=='' || long==''){
					$g_containerInfoBox.find('.pi-map-container').html('');
				}else{
					$g_containerInfoBox.find('.pi-map-container').html('<span id="nano-gmap-viewer" style="width:100%; height:400px;"></span>');
					var latLng = new google.maps.LatLng(lat,long);

				     var map = new google.maps.Map(document.getElementById('nano-gmap-viewer'), {
				        zoom: 14,
				        center: latLng,
						mapTypeId: google.maps.MapTypeId.MAP,
						scrollwheel: false
				     });	
				     var marker = new google.maps.Marker({
				    	    position: latLng,
				    	});

				     marker.setMap(map);				     
				}	
				var json_details_url=g_ngItems[g_viewerCurrentItemIdx].infobox.json_details;
				if (json_details_url!=''){
					$g_containerInfoBox.find('.pi-views').text('...');
					$g_containerInfoBox.find('.pi-comments').text('...');
					jQuery.ajax({
						'url':json_details_url,
						'dataType': 'json',
						'success': function (result, textStatus, jqXHR){
							if ($g_containerInfoBox!=null){
								if (typeof result.entry !== "undefined" && typeof result.entry.gphoto$commentCount !== "undefined" && typeof result.entry.gphoto$commentCount.$t !== "undefined"){
									$g_containerInfoBox.find('.pi-comments').text(result.entry.gphoto$commentCount.$t);
								}else{
									$g_containerInfoBox.find('.pi-comments').text('-na-');
								}
	
								if (typeof result.entry !== "undefined" && typeof result.entry.gphoto$viewCount !== "undefined" && typeof result.entry.gphoto$viewCount.$t !== "undefined"){
									$g_containerInfoBox.find('.pi-views').text(result.entry.gphoto$viewCount.$t);
								}else{
									$g_containerInfoBox.find('.pi-views').text('-na-');
								}
							}
						},
						'error': function (jqXHR, textStatus, error){
							$g_containerInfoBox.find('.pi-views').text('-na-');
							$g_containerInfoBox.find('.pi-comments').text('-na-');
						}
					});
				}else{
					$g_containerInfoBox.find('.pi-views').text('-na-');
					$g_containerInfoBox.find('.pi-comments').text('-na-');
				}
	    	},
	    	afterClose: function() {
	        	$g_containerInfoBox.remove();
	        	$g_containerInfoBox=null;
	        }
	      }	    
	  });	  
	  
  }
  
  function OpenInternalViewer( imageIdx ) {

    if( !g_options.locationHash ) {
      top.location.hash = 'nanogallery/'+g_baseControlID+'/v';
    }
    
    jQuery('body').css('overflow','hidden');  //avoid scrollbars
    $g_containerViewerContainer=jQuery('<div  class="nanoGalleryViewerContainer" style="visibility:visible"></div>').appendTo('body');
    $g_containerViewerContainer.addClass('nanogallery_theme_'+g_options.theme);
    SetColorSchemeViewer($g_containerViewerContainer);

    $g_containerViewer=jQuery('<div  id="nanoGalleryViewer" class="nanoGalleryViewer" style="visibility:visible"></div>').appendTo($g_containerViewerContainer);
    
    var sImg='';
    var l=g_ngItems.length;

    sImg+='<img class="image" src="//:0" style="visibility:visible;opacity:0;position:absolute;top:0;bottom:0;left:0;right:0;margin:auto;zoom:1;">';
    sImg+='<img class="image" src="//:0" style="visibility:visible;opacity:0;position:absolute;top:0;bottom:0;left:0;right:0;margin:auto;zoom:1;">';
    sImg+='<img class="image" src="//:0" style="visibility:visible;opacity:0;position:absolute;top:0;bottom:0;left:0;right:0;margin:auto;zoom:1;">';

    $g_containerViewerContent=jQuery('<div class="content">'+sImg+'<div class="contentAreaPrevious"></div><div class="contentAreaNext"></div></div>').appendTo($g_containerViewer);
    $g_ViewerImagePrevious=$g_containerViewer.find('.image').eq(0);
    $g_ViewerImageCurrent=$g_containerViewer.find('.image').eq(1);
    $g_ViewerImageNext=$g_containerViewer.find('.image').eq(2);

    $g_containerViewerCloseFloating=jQuery('<div class="closeButtonFloating"></div>').appendTo($g_containerViewer);
    //g_containerViewerCloseFloating=jQuery('<div class="closeButtonFloating"></div>').appendTo(jQuery(g_containerViewerContent).find('img'));
    var fs='';
    if( g_supportFullscreenAPI ) {
      fs='<div class="setFullscreenButton fullscreenButton"></div>';
    }
    var ib='';
    if (g_options.kind=='picasa' && g_options.showInfoBoxButton){
    	ib='<div class="infoButton"></div>';
    }
    $g_containerViewerToolbar=jQuery('<div class="toolbarContainer" style="visibility:hidden;"><div class="toolbar"><div class="previousButton"></div><div class="pageCounter"></div><div class="nextButton"></div><div class="playButton playPauseButton"></div>'+fs+ib+'<div class="closeButton"></div><div class="label"><div class="title"></div><div class="description"></div></div></div>').appendTo($g_containerViewer);
    if( g_options.viewerDisplayLogo ) {
      $g_containerViewerLogo=jQuery('<div class="nanoLogo"></div>').appendTo($g_containerViewer);
    }
 
    setElementOnTop('',$g_containerViewer);
    ResizeInternalViewer($g_ViewerImageCurrent);

    
    // GESTURE : drag --> requires HAMMER.JS
    if( typeof(Hammer) !== 'undefined' ) {
      var hammertime = Hammer(document.getElementById('nanoGalleryViewer'), {
        drag:true,
        transform_always_block:true,
        drag_block_horizontal: true,
        //drag_min_distance: 25,
        prevent_default:false,
        drag_lock_min_distance: 20,
        hold: false,
        release: true,
        swipe: true,
        tap: false,
        touch: true,
        transform: false
      });

      var posX=0, posY=0;
      hammertime.on('drag release', function(ev) {
        if( g_viewerImageIsChanged ) { return; }
        switch(ev.type) {
          case 'drag':
            ev.stopPropagation();
            posX = ev.gesture.deltaX;
            posY = ev.gesture.deltaY;
            if( Math.abs(posX)  < 25 ) {
              $g_containerViewerContent.find('.imgCurrent').css({ 'left': 0 });  
            }
            else {
              $g_containerViewerContent.find('.imgCurrent').css({ 'left': posX });
            }
            break;
          case 'release':
            ev.gesture.stopPropagation();
            ev.stopPropagation();
            if( Math.abs(posX)  < 25 ||  (new Date().getTime()) - lastImageChange < 100 ) {
              $g_containerViewerContent.find('.imgCurrent').css({ 'left': 0 });  
            }
            if( Math.round(posX) < -25 ) {
              DisplayNextImagePart1();
            }
            if( Math.round(posX) > 25 ) {
              DisplayPreviousImage();
            }
            posX=0;
            break;
        }
      });
    }
    
    $g_containerViewerCloseFloating.on("click",function(e){
      e.stopPropagation();
      if( g_options.locationHash ) {
        CloseInternalViewer(true);
      }
      else {
        window.history.back();
      }
    });
    
    $g_containerViewerToolbar.find('.closeButton').on("click",function(e){
      e.stopPropagation();
      if( g_options.locationHash ) {
        CloseInternalViewer(true);
      }
      else {
        window.history.back();
      }
    });
    $g_containerViewerToolbar.find('.infoButton').on("click",function(e){
        e.stopPropagation();
        OpenInfoBox();//$g_containerInfoBox
      });

    $g_containerViewerContent.find('img').on("click",function(e){
      e.stopPropagation();
      if( e.pageX < (jQuery(window).width()/2) ) {
        DisplayPreviousImage();
      }
      else {
        DisplayNextImagePart1();
      }
    });
    

    $g_containerViewerToolbar.find('.playPauseButton').on("click",function(e){ 
      e.stopPropagation();
      SlideshowToggle();
    });
    
    $g_containerViewerToolbar.find('.fullscreenButton').on("click",function(e){ 
      e.stopPropagation();
      ViewerFullscreenToggle();
    });

    $g_containerViewerToolbar.find('.nextButton').on("click",function(e){ e.stopPropagation(); DisplayNextImagePart1(); });
    $g_containerViewerToolbar.find('.previousButton').on("click",function(e){ e.stopPropagation(); DisplayPreviousImage(); });
    $g_containerViewerContent.find('.contentAreaNext').on("click",function(e){ e.stopPropagation(); DisplayNextImagePart1(); });
    $g_containerViewerContent.find('.contentAreaPrevious').on("click",function(e){ e.stopPropagation(); DisplayPreviousImage(); });

    $g_containerViewerContent.on("click",function(e){ 
      if( (new Date().getTime()) - lastImageChange < 100 ) { return; }
      e.stopPropagation();
      if( g_options.locationHash ) {
        CloseInternalViewer(true);
      }
      else {
        window.history.back();
      }
    });
    
    DisplayInternalViewer(imageIdx, '');

  };
  
  // Toggle fullscreen mode on/off
  function ViewerFullscreenToggle(){
    if( g_viewerIsFullscreen ) {
      if (document.exitFullscreen) {
              document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
              document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
              document.msExitFullscreen();
            } else if (document.mozCancelFullScreen) {
              document.mozCancelFullScreen();
            }

      g_viewerIsFullscreen=false;
      $g_containerViewerToolbar.find('.fullscreenButton').removeClass('removeFullscreenButton').addClass('setFullscreenButton');
    }
    else {
      if ($g_containerViewerContainer[0].requestFullscreen) {
                  $g_containerViewerContainer[0].requestFullscreen();
              } else if ($g_containerViewerContainer[0].webkitRequestFullscreen) {
                $g_containerViewerContainer[0].webkitRequestFullscreen();
              } else if ($g_containerViewerContainer[0].msRequestFullscreen) {
                $g_containerViewerContainer[0].msRequestFullscreen();
              } else if ($g_containerViewerContainer[0].mozRequestFullScreen) {
                $g_containerViewerContainer[0].mozRequestFullScreen();
              }
      g_viewerIsFullscreen=true;
      $g_containerViewerToolbar.find('.fullscreenButton').removeClass('setFullscreenButton').addClass('removeFullscreenButton');
    }
  }
  
  // toggle slideshow mode on/off
  function SlideshowToggle(){
    if( g_playSlideshow ) {
      window.clearInterval(g_playSlideshowTimerID);
      g_playSlideshow=false;
      $g_containerViewerToolbar.find('.playPauseButton').removeClass('pauseButton').addClass('playButton');
    }
    else {
      g_playSlideshow=true;
      $g_containerViewerToolbar.find('.playPauseButton').removeClass('playButton').addClass('pauseButton');
      DisplayNextImage();
      g_playSlideshowTimerID=window.setInterval(function(){DisplayNextImage()},g_slideshowDelay);
    }
  }
  
  // Display next image
  function DisplayNextImagePart1() {
    if( g_playSlideshow ) {
      window.clearInterval(g_playSlideshowTimerID);
      g_playSlideshowTimerID=window.setInterval(function(){DisplayNextImage()},g_slideshowDelay);
    }
    DisplayNextImage();
  }
  function DisplayNextImage() {
    if( g_viewerImageIsChanged ) { return; }
    if( (new Date().getTime()) - lastImageChange < 100 ) { return; }
    var l=g_ngItems.length;

    var newImageIdx=GetNextImageIdx(g_viewerCurrentItemIdx);
    DisplayInternalViewer(newImageIdx, 'nextImage');
  };
  
  // Display previous image
  function DisplayPreviousImage() {
    if( g_viewerImageIsChanged ) { return; }
    if( (new Date().getTime()) - lastImageChange < 200 ) { return; }
    if( g_playSlideshow ) {
      SlideshowToggle();
    }
    
    var newImageIdx=GetPreviousImageIdx(g_viewerCurrentItemIdx);
    DisplayInternalViewer(newImageIdx, 'previousImage');
  };

  // Display image (and run animation)
  function DisplayInternalViewer( imageIdx, displayType ) {
    lastImageChange=new Date().getTime();
    g_viewerImageIsChanged=true;
    var displayNext=true;
    
    if( g_options.locationHash ) {
      var s ='nanogallery/'+g_baseControlID+'/'+g_ngItems[imageIdx].albumID+"/"+g_ngItems[imageIdx].GetID();
      if( ('#'+s) != location.hash ) {
        g_lastLocationHash='#'+s;   //nanogallery/'+g_baseControlID+'/'+g_ngItems[imageIdx].albumID+"/"+g_ngItems[imageIdx].GetID();
        top.location.hash = s;      //'nanogallery/'+g_baseControlID+'/'+g_ngItems[imageIdx].albumID+"/"+g_ngItems[imageIdx].GetID();
      }
      else {
        g_lastLocationHash=top.location.hash;
      }
    }
    
    DisplayInternalViewerToolbar(imageIdx);
    ResizeInternalViewer($g_ViewerImageCurrent);
    
    g_viewerCurrentItemIdx=imageIdx;

    var s='-'+(1*jQuery(window).width())+'px';
    var animImgCurrent='';
    switch( displayType ) {
      case 'nextImage':
        animImgCurrent = { left : s,'opacity': 0 };
        $g_containerViewerContent.find('*').stop(true,true);
        $g_ViewerImageNext.css({'opacity':0, 'right':'0', visibility: 'visible'});  //.attr('src',g_ngItems[imageIdx].responsiveURL());
        
//        ResizeInternalViewer($g_ViewerImageNext);
        
        jQuery.when(
          $g_ViewerImageCurrent.animate(animImgCurrent, 500), 
          $g_ViewerImageNext.animate({'opacity': 1 }, 300)
        ).done(function () {
          DisplayInternalViewerComplete(imageIdx, displayType);
        });
        break;

      case 'previousImage':
        animImgCurrent = { right : s,'opacity': 0 };
        $g_containerViewerContent.find('*').stop(true,true);
        $g_ViewerImagePrevious.css({'opacity':0, 'right':'0', visibility: 'visible'});  //.attr('src',g_ngItems[imageIdx].responsiveURL());
        
//        ResizeInternalViewer($g_ViewerImagePrevious);
        
        jQuery.when(
          $g_ViewerImageCurrent.animate(animImgCurrent, 500), 
          $g_ViewerImagePrevious.animate({'opacity': 1 }, 300)
        ).done(function () {
          DisplayInternalViewerComplete(imageIdx, displayType);
        });
        break;
        
      default:
        $g_containerViewerContent.find('*').stop(true,true);
        $g_ViewerImageCurrent.css({'opacity':0, 'right':'0', visibility: 'visible'}).attr('src',g_ngItems[imageIdx].responsiveURL());

        jQuery.when(
          $g_ViewerImageCurrent.animate({'opacity': 1 }, 300)
        ).done(function () {
          DisplayInternalViewerComplete(imageIdx, displayType);
        });
        break;
    }

    g_containerViewerDisplayed=true;

  };


  function DisplayInternalViewerComplete( imageIdx, displayType ) {
    //DisplayInternalViewerToolbar(imageIdx);

    $g_ViewerImageCurrent.off("click");
    $g_ViewerImageCurrent.removeClass('imgCurrent');
  
    var $tmp=$g_ViewerImageCurrent;
    switch( displayType ) {
      case 'nextImage':
        $g_ViewerImageCurrent=$g_ViewerImageNext;
        $g_ViewerImageNext=$tmp;
        break;
      case 'previousImage':
        $g_ViewerImageCurrent=$g_ViewerImagePrevious;
        $g_ViewerImagePrevious=$tmp;
        break;
    }
    $g_ViewerImageCurrent.addClass('imgCurrent');
    
    $g_ViewerImageNext.css({'opacity':0, 'right':'0', 'left':'0', visibility: 'hidden'}).attr('src',g_ngItems[GetNextImageIdx(imageIdx)].responsiveURL());
    $g_ViewerImagePrevious.css({'opacity':0, 'right':'0', 'left':'0', visibility: 'hidden'}).attr('src',g_ngItems[GetPreviousImageIdx(imageIdx)].responsiveURL());

    $g_ViewerImageCurrent.on("click",function(e){
    e.stopPropagation();
      if( e.pageX < (jQuery(window).width()/2) ) {
        DisplayPreviousImage();
      }
      else {
        DisplayNextImagePart1();
      }
    });

  
    ResizeInternalViewer($g_ViewerImageCurrent);

    // TODO: this code does not work
    //jQuery(g_containerViewerContent).find('img').on('resize', function(){ 
    //  ResizeInternalViewer('.imgCurrent');
    //  console.log('resized');
    //});


    g_viewerImageIsChanged=false;
  }

  function GetNextImageIdx( imageIdx ) {
    var l=g_ngItems.length;
    var newImageIdx=-1;

    for(var i=imageIdx+1; i<l; i++ ){
      if( g_ngItems[i].albumID == g_ngItems[imageIdx].albumID && g_ngItems[i].kind == 'image' ) {
        newImageIdx=i;
        break;
      }
    }
    if( newImageIdx == -1 ) {
      for(var i=0; i<=imageIdx; i++ ){
        if( g_ngItems[i].albumID == g_ngItems[imageIdx].albumID && g_ngItems[i].kind == 'image' ) {
          newImageIdx=i;
          break;
        }
      }
    }
    
    return newImageIdx;
  }

  function GetPreviousImageIdx( imageIdx ) {
    var newImageIdx=-1;
    for(var i=imageIdx-1; i>=0; i-- ){
      if( g_ngItems[i].albumID == g_ngItems[imageIdx].albumID && g_ngItems[i].kind == 'image' ) {
        newImageIdx=i;
        break;
      }
    }
    if( newImageIdx == -1 ) {
      for(var i=g_ngItems.length-1; i>=imageIdx; i-- ){
        if( g_ngItems[i].albumID == g_ngItems[imageIdx].albumID && g_ngItems[i].kind == 'image' ) {
          newImageIdx=i;
          break;
        }
      }
    }
    
    return newImageIdx;
  }

  function HideInternalViewerToolbar() {
    $g_containerViewerToolbar.css({'visibility':'hidden'});
  }

  
  function DisplayInternalViewerToolbar( imageIdx ) {
//    $g_containerViewerToolbar.css({'visibility':'visible'});
    // set title
    if( g_ngItems[imageIdx].title !== undefined ) {
      $g_containerViewerToolbar.find('.title').html(g_ngItems[imageIdx].title);
    }
    else {
      $g_containerViewerToolbar.find('.title').html('');
    }
    // set description
    if( g_ngItems[imageIdx].description !== undefined ) {
      $g_containerViewerToolbar.find('.description').html(g_ngItems[imageIdx].description);
    }
    else {
      $g_containerViewerToolbar.find('.description').html('');
    }

    // set page number
    var viewerMaxImages=0;
    var l=g_ngItems.length;
    for( var i=0; i <  l ; i++ ) {
      if( g_ngItems[i].albumID == g_ngItems[imageIdx].albumID && g_ngItems[i].kind == 'image' ) {
        viewerMaxImages++;
      }
    }
    if( viewerMaxImages > 0 ) {
      $g_containerViewerToolbar.find('.pageCounter').html((g_ngItems[imageIdx].imageNumber+1)+'/'+viewerMaxImages);
    }
    
    //ResizeInternalViewer();
  }
  
  
  function CloseInternalViewer( setLocationHash ) {

    if( g_viewerImageIsChanged ) {
      $g_containerViewerContent.find('*').stop(true,true);
    }

    if( g_containerViewerDisplayed ) {
      window.clearInterval(g_viewerResizeTimerID);
      if( g_playSlideshow ) {
        window.clearInterval(g_playSlideshowTimerID);
        g_playSlideshow=false;
      }
      if( g_viewerIsFullscreen ) {
        ViewerFullscreenToggle()
      }
      g_containerViewerDisplayed=false;
      $g_containerViewerContainer.off().remove();
      jQuery('body').css('overflow','inherit');
      
      if( g_albumIdxToOpenOnViewerClose != -1 ) {
        DisplayAlbum(g_albumIdxToOpenOnViewerClose,true);
      }
      else {
        if( g_options.locationHash && setLocationHash ) {
          var albumID=g_ngItems[g_viewerCurrentItemIdx].albumID;
          var s='nanogallery/'+g_baseControlID+'/'+albumID;
          g_lastLocationHash='#'+s;
          top.location.hash=s;
        }

        ThumbnailHoverOutAll();
      }
    }
  };
  
  function ResizeInternalViewer($imgElt) {

    window.clearInterval(g_viewerResizeTimerID);
    g_viewerResizeTimerID=window.setInterval(function(){ResizeInternalViewer($g_ViewerImageCurrent)},500);

  
    var windowsW=jQuery(window).width();
    var windowsH=jQuery(window).height();
    $g_containerViewer.css({
      "visibility":"visible",
      "position": "fixed"    //"absolute",
      //"top":0,
      //"left":0,
      //"width":jQuery(window).width(),
      //"height":jQuery(window).height()
    });

    var $elt=$imgElt
    
    if( $g_ViewerImageCurrent.height() <= 40 ) {
      $g_containerViewerToolbar.css({'visibility':'hidden'});
    }
    else {
      $g_containerViewerToolbar.css({'visibility':'visible'});
    }
    
    var contentOuterWidthV=Math.abs($g_containerViewerContent.outerHeight(true)-$g_containerViewerContent.height());  // vertical margin+border+padding
    var contentOuterWidthH=Math.abs($g_containerViewerContent.outerWidth(true)-$g_containerViewerContent.width());  // horizontal margin+border+padding
    
    var imgBorderV=$elt.outerHeight(false)-$elt.innerHeight();
    var imgBorderH=Math.abs($elt.outerWidth(false)-$elt.innerWidth());
    
    var imgPaddingV=Math.abs($elt.innerHeight()-$elt.height());
    var imgPaddingH=Math.abs($elt.innerHeight()-$elt.height());
    
    var tV=imgBorderV+imgPaddingV;  //+tmargin;
    var tH=imgBorderH+imgPaddingH;  //+tmargin;

    var toolbarH=0;
    if( g_options.viewerToolbar.style != 'innerImage' ) {
      toolbarH=$g_containerViewerToolbar.find('.toolbar').outerHeight(true);
    }
    var h=windowsH-toolbarH-contentOuterWidthV;
    var w=windowsW-contentOuterWidthH;
        
    switch( g_options.viewerToolbar.position ) {
      case 'top':
        $g_containerViewerContent.css({'height':h, 'width':w, 'top':toolbarH  });
        var posY=0;
        if( g_options.viewerToolbar.style == 'innerImage' ) {
          posY= Math.abs($g_ViewerImageCurrent.outerHeight(true)-$g_ViewerImageCurrent.height())/2 +5;
        }
        if( g_options.viewerToolbar.style == 'stuckImage' ) {
          posY= Math.abs($g_ViewerImageCurrent.outerHeight(true)-$g_ViewerImageCurrent.height())/2 -tV;
        }
        $g_containerViewerToolbar.css({'top': posY});
        break;

      case 'bottom':
      default:
        $g_containerViewerContent.css({'height':h, 'width':w });
        var posY=0;
        if( g_options.viewerToolbar.style == 'innerImage' ) {
          posY= Math.abs($g_ViewerImageCurrent.outerHeight(true)-$g_ViewerImageCurrent.height())/2 +5;//- $g_containerViewerToolbar.outerHeight(true) ;
        }
        if( g_options.viewerToolbar.style == 'stuckImage' ) {
          posY= Math.abs($g_ViewerImageCurrent.outerHeight(true)-$g_ViewerImageCurrent.height())/2 -tV;
        }
        $g_containerViewerToolbar.css({'bottom': posY});
        break;
    }
    
    if( g_options.viewerToolbar.style == 'innerImage' ) {
      $g_containerViewerToolbar.find('.toolbar').css({'max-width': $g_ViewerImageCurrent.width()});
    }
    
    if( g_options.viewerToolbar.style == 'fullWidth' ) {
      $g_containerViewerToolbar.find('.toolbar').css({'width': w});
    }

    $g_containerViewerToolbar.css({'height': $g_containerViewerToolbar.find('.toolbar').outerHeight(true)});
    $g_containerViewerContent.children('img').css({'max-width':(w-tV), 'max-height':(h-tH) });
  }
  
  
  function OpenFancyBox( imageIdx ) {
    var n=imageIdx;   //jQuery(element).data("index");
    var lstImages=[];
    var current=0;

    lstImages[current]=new Object;
    lstImages[current].href=g_ngItems[n].responsiveURL();
    lstImages[current].title=g_ngItems[n].title;
    
    var l=g_ngItems.length;
    for( var j=n+1; j<l ; j++) {
      if( g_ngItems[j].kind == 'image' && g_ngItems[j].albumID == g_ngItems[imageIdx].albumID && g_ngItems[j].destinationURL == '' ) {
        current++;
        lstImages[current]=new Object;
        lstImages[current].href=g_ngItems[j].responsiveURL();
        lstImages[current].title=g_ngItems[j].title;
      }
    }
    for( var j=0; j<n; j++) {
      if( g_ngItems[j].kind == 'image' && g_ngItems[j].albumID == g_ngItems[imageIdx].albumID && g_ngItems[j].destinationURL == '' ) {
        current++;
        lstImages[current]=new Object;
        lstImages[current].href=g_ngItems[j].responsiveURL();
        lstImages[current].title=g_ngItems[j].title;
      }
    }
    jQuery.fancybox(lstImages,{'autoPlay':false, 'nextEffect':'fade', 'prevEffect':'fade','scrolling':'no',
      helpers    : {  buttons  : { 'position' : 'bottom'} }
    });
  };
  
  // ##### BREADCRUMB/THUMBNAIL COLOR SCHEME #####
  function SetColorScheme( element ) {
    var cs=null;
    switch(toType(g_options.colorScheme)) {
      case 'object':    // user custom color scheme object 
        cs=g_colorScheme_default;
        jQuery.extend(true,cs,g_options.colorScheme);
        g_colorSchemeLabel='nanogallery_colorscheme_custom';
        break;
      case 'string':    // name of an internal defined color scheme
        switch( g_options.colorScheme ) {
          case 'none':
            return;
            break;
          case 'light':
            cs=g_colorScheme_light;
            g_colorSchemeLabel='nanogallery_colorscheme_light';
            break;
          case 'lightBackground':
            cs=g_colorScheme_lightBackground;
            g_colorSchemeLabel='nanogallery_colorscheme_lightBackground';
            break;
          case 'darkRed':
            cs=g_colorScheme_darkRed;
            g_colorSchemeLabel='nanogallery_colorscheme_darkred';
            break;
          case 'darkGreen':
            cs=g_colorScheme_darkGreen;
            g_colorSchemeLabel='nanogallery_colorscheme_darkgreen';
            break;
          case 'darkBlue':
            cs=g_colorScheme_darkBlue;
            g_colorSchemeLabel='nanogallery_colorscheme_darkblue';
            break;
          case 'darkOrange':
            cs=g_colorScheme_darkOrange;
            g_colorSchemeLabel='nanogallery_colorscheme_darkorange';
            break;
          case 'default':
          case 'dark':
          default:
            cs=g_colorScheme_default;
            g_colorSchemeLabel='nanogallery_colorscheme_default';
        }
        break;
      default:
        nanoAlert('Error in colorScheme parameter.');
        return;
    }

    
    //var s1='.nanogallery_theme_'+g_options.theme+' ';
    var s1='.' + g_colorSchemeLabel + ' ';
    var s=s1+'.nanoGalleryNavigationbar { background:'+cs.navigationbar.background+'; }'+'\n';
    if( cs.navigationbar.border !== undefined ) { s+=s1+'.nanoGalleryNavigationbar { border:'+cs.navigationbar.border+'; }'+'\n'; }
    if( cs.navigationbar.borderTop !== undefined ) { s+=s1+'.nanoGalleryNavigationbar { border-top:'+cs.navigationbar.borderTop+'; }'+'\n'; }
    if( cs.navigationbar.borderBottom !== undefined ) { s+=s1+'.nanoGalleryNavigationbar { border-bottom:'+cs.navigationbar.borderBottom+'; }'+'\n'; }
    if( cs.navigationbar.borderRight !== undefined ) { s+=s1+'.nanoGalleryNavigationbar { border-right:'+cs.navigationbar.borderRight+'; }'+'\n'; }
    if( cs.navigationbar.borderLeft !== undefined ) { s+=s1+'.nanoGalleryNavigationbar { border-left:'+cs.navigationbar.borderLeft+'; }'+'\n'; }
    //s+=s1+'.g_containerNavigationbar .folder { color:'+cs.navigationbar.color+'; }'+'\n';
    s+=s1+'.nanoGalleryNavigationbar .folder  { color:'+cs.navigationbar.color+'; }'+'\n';
    s+=s1+'.nanoGalleryNavigationbar .separator  { color:'+cs.navigationbar.color+'; }'+'\n';
    s+=s1+'.nanoGalleryNavigationbar .nanoGalleryTags { color:'+cs.navigationbar.color+'; }'+'\n';
    //s+=s1+'.nanoGalleryNavigationbar .separator:after { color:'+cs.navigationbar.color+'; }'+'\n';
    //s+=s1+'.g_containerNavigationbar .folder:hover { color:'+cs.navigationbar.colorHover+'; }'+'\n';
    s+=s1+'.nanoGalleryNavigationbar .folder:hover { color:'+cs.navigationbar.colorHover+'; }'+'\n';
    s+=s1+'.nanoGalleryNavigationbar .separator:hover { color:'+cs.navigationbar.colorHover+'; }'+'\n';
    s+=s1+'.nanoGalleryNavigationbar .nanoGalleryTags:hover { color:'+cs.navigationbar.colorHover+'; }'+'\n';

    s+=s1+'.nanoGalleryContainer .nanoGalleryThumbnailContainer { background:'+cs.thumbnail.background+'; border:'+cs.thumbnail.border+'; }'+'\n';
    s+=s1+'.nanoGalleryContainer .nanoGalleryThumbnailContainer .imgContainer { background:'+cs.thumbnail.background+'; }'+'\n';
    //s+=s1+'.nanoGalleryContainer .nanoGalleryThumbnailContainer .imgContainer { background:'+cs.thumbnail.background+'; opacity:'+cs.thumbnail.labelOpacity+'}'+'\n';
    s+=s1+'.nanoGalleryContainer .nanoGalleryThumbnailContainer .labelImage { background:'+cs.thumbnail.labelBackground+'; }'+'\n';
    s+=s1+'.nanoGalleryContainer .nanoGalleryThumbnailContainer .labelImageTitle  { color:'+cs.thumbnail.titleColor+'; Text-Shadow:'+cs.thumbnail.titleShadow+' }'+'\n';
    s+=s1+'.nanoGalleryContainer .nanoGalleryThumbnailContainer .labelImageTitle:before { color:'+cs.thumbnail.titleColor+'; Text-Shadow:'+cs.thumbnail.titleShadow+' }'+'\n';
    s+=s1+'.nanoGalleryContainer .nanoGalleryThumbnailContainer .labelFolderTitle { color:'+cs.thumbnail.titleColor+'; Text-Shadow:'+cs.thumbnail.titleShadow+' }'+'\n';
    s+=s1+'.nanoGalleryContainer .nanoGalleryThumbnailContainer .labelFolderTitle:before { color:'+cs.thumbnail.titleColor+'; Text-Shadow:'+cs.thumbnail.titleShadow+' }'+'\n';
    s+=s1+'.nanoGalleryContainer .nanoGalleryThumbnailContainer .labelDescription { color:'+cs.thumbnail.descriptionColor+'; Text-Shadow:'+cs.thumbnail.descriptionShadow+' }'+'\n';

    jQuery('head').append('<style>'+s+'</style>');
    jQuery(element).addClass(g_colorSchemeLabel);

  };
  
  // ##### VIEWER COLOR SCHEME #####
  function SetColorSchemeViewer( element ) {

    var cs=null;
    switch(toType(g_options.colorSchemeViewer)) {
      case 'object':    // user custom color scheme object 
        cs=g_colorSchemeViewer_default;
        jQuery.extend(true,cs,g_options.colorSchemeViewer);
        g_colorSchemeLabel='nanogallery_colorschemeviewer_custom';
        break;
      case 'string':    // name of an internal defined color scheme
        switch( g_options.colorSchemeViewer ) {
          case 'none':
            return;
            break;
          case 'light':
            cs=g_colorSchemeViewer_light;
            g_colorSchemeLabel='nanogallery_colorschemeviewer_light';
            break;
          case 'darkRed':
            cs=g_colorSchemeViewer_darkRed;
            g_colorSchemeLabel='nanogallery_colorschemeviewer_darkred';
            break;
          case 'darkGreen':
            cs=g_colorSchemeViewer_darkGreen;
            g_colorSchemeLabel='nanogallery_colorschemeviewer_darkgreen';
            break;
          case 'darkBlue':
            cs=g_colorSchemeViewer_darkBlue;
            g_colorSchemeLabel='nanogallery_colorschemeviewer_darkblue';
            break;
          case 'darkOrange':
            cs=g_colorSchemeViewer_darkOrange;
            g_colorSchemeLabel='nanogallery_colorschemeviewer_darkorange';
            break;
          case 'default':
          case 'dark':
          default:
            cs=g_colorSchemeViewer_default;
            g_colorSchemeLabel='nanogallery_colorschemeviewer_default';
        }
        break;
      default:
        nanoAlert('Error in colorSchemeViewer parameter.');
        return;
    }

    
    //var s1='.nanogallery_theme_'+g_options.theme+' ';
    var s1='.' + g_colorSchemeLabel + ' ';
    var s=s1+'.nanoGalleryViewer { background:'+cs.background+'; }'+'\n';
    //s+=s1+'.nanoGalleryViewer { background:'+cs.viewer.background+'; color:'+cs.viewer.color+'; }'+'\n';
    s+=s1+'.nanoGalleryViewer .content img { border:'+cs.imageBorder+'; box-shadow:'+cs.imageBoxShadow+'; }'+'\n';
    s+=s1+'.nanoGalleryViewer .toolbar { background:'+cs.barBackground+'; border:'+cs.barBorder+'; color:'+cs.barColor+'; }'+'\n';
    s+=s1+'.nanoGalleryViewer .toolbar .previousButton:after { color:'+cs.barColor+'; }'+'\n';
    s+=s1+'.nanoGalleryViewer .toolbar .nextButton:after { color:'+cs.barColor+'; }'+'\n';
    s+=s1+'.nanoGalleryViewer .toolbar .closeButton:after { color:'+cs.barColor+'; }'+'\n';
    //s+=s1+'.nanoGalleryViewer .toolbar .label { background:'+cs.barBackground+'; }'+'\n';
    s+=s1+'.nanoGalleryViewer .toolbar .label .title { color:'+cs.barColor+'; }'+'\n';
    s+=s1+'.nanoGalleryViewer .toolbar .label .description { color:'+cs.barDescriptionColor+'; }'+'\n';
    jQuery('head').append('<style>'+s+'</style>');
    jQuery(element).addClass(g_colorSchemeLabel);
  };


  
// ##################################
// ##### nanoGALLERY DEMO PANEL #####
// ##################################
  
  
// jQuery plugin - nanoGALLERY DEMO PANEL
(function( $ ) {
  jQuery.fn.nanoGalleryDemo = function(options) {
    var g_containerDemo=null,
    g_containerDemoPanel=null,
    g_containerNew=null,
    g_save=null;
    
    var settings = $.extend(true, {
      // default settings
      userID:'',
      kind:'',
      album:'',
      photoset:'',
      blackList:'scrapbook|profil',
      whiteList:'',
      albumList:'',
      galleryToolbarWidthAligned:true,
      displayBreadcrumb:false,
      theme:'default',
      colorScheme:'none',
      colorSchemeViewer:'none',
      items:null,
      itemsBaseURL:'',
      maxItemsPerLine:0,
      paginationMaxItemsPerPage:0,
      paginationMaxLinesPerPage:0,
      maxWidth:0,
      viewer:'internal',
      viewerDisplayLogo:true,
      viewerToolbar:{position:'bottom', style:'innerImage'},
      thumbnailWidth:230,
      thumbnailHeight:154,
      thumbnailHoverEffect:null,
      thumbnailLabel:{position:'overImageOnBottom',display:true,displayDescription:false},
      thumbnailDisplayInterval:30,
      thumbnailDisplayTransition:true,
      thumbnailLazyLoad:false,
      thumbnailLazyLoadTreshold:100,
      touchAnimation:true,
      useTags:false,
      preset:'none',
      locationHash:false,
      slideshowDelay:3000,
      thumbnailGlobalImageTitle:'',
      thumbnailGlobalAlbumTitle:''
    }, options );


    return this.each(function(index) {
      g_save=jQuery(this)[0].outerHTML;
      g_containerDemo =jQuery('<div class="nanoGalleryDemo" style="padding:5px"></div>').replaceAll(this);
    
      var sHoverEffects='<option style="color:#000">none</option>';
      sHoverEffects+='<option style="color:#000">slideUp</option><option style="color:#000">slideDown</option><option style="color:#000">slideLeft</option><option style="color:#000">slideRight</option>';
      sHoverEffects+='<option style="color:#000">imageSlideUp</option><option style="color:#000">imageSlideDown</option><option style="color:#000">imageSlideLeft</option><option style="color:#000">imageSlideRight</option>';
      sHoverEffects+='<option style="color:#000">imageSplitVert</option><option style="color:#000">imageSplit4</option>';
      sHoverEffects+='<option style="color:#000">labelAppear</option><option style="color:#000">labelAppear75</option><option style="color:#000">labelSlideDown</option><option style="color:#000">labelSlideUp</option>';
      sHoverEffects+='<option style="color:#000">labelSplitVert</option><option style="color:#000">labelSplit4</option><option style="color:#000">labelAppearSplitVert</option><option style="color:#000">labelAppearSplit4</option>';
      sHoverEffects+='<option style="color:#000">labelOpacity50</option><option style="color:#000">imageOpacity50</option><option style="color:#000">descriptionSlideUp</option>';
      sHoverEffects+='<option style="color:#000">borderLighter</option><option style="color:#000">borderDarker</option><option style="color:#000">imageInvisible</option>';

      
      sHoverEffects+='<option style="color:#000"></option><option style="color:#000">imageScale150*</option><option style="color:#000">imageScale150Outside*</option><option style="color:#000">scale120*</option>';
      sHoverEffects+='<option style="color:#000">overScale*</option>';
      sHoverEffects+='<option style="color:#000">overScaleOutside*</option><option style="color:#000">scaleLabelOverImage*</option>';

      sHoverEffects+='<option style="color:#000">imageFlipHorizontal*</option><option style="color:#000">imageFlipVertical*</option>';
      sHoverEffects+='<option style="color:#000">rotateCornerBR*</option><option style="color:#000">rotateCornerBL*</option><option style="color:#000">imageRotateCornerBR*</option><option style="color:#000">imageRotateCornerBL*</option>';

      var sPanel='<div style="line-height:normal;margin:10px auto 30px auto;text-align:center;border:1px solid #555;background:#000;padding:5px;font-size:1.2em;"><span style="color:#d3d3d3;">nano</span><span style="color:#6e6;">GALLERY</span> - demonstration panel</div>';
      sPanel+='<div style="display:inline-block;"><b>Thumbnail hover effects:</b>&nbsp;<select name="lHoverEffect1" style="color:#000">'+sHoverEffects+'</select>';
      sPanel+='&nbsp;<select name="lHoverEffect2" style="color:#000">'+sHoverEffects+'</select>';
      sPanel+='&nbsp;<select name="lHoverEffect3" style="color:#000">'+sHoverEffects+'</select></div>';
      sPanel+='<br><div style="display:inline-block;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*: requires Transit plugin</div>';
      sPanel+='<br><br><div style="display:inline-block;"><b>Thumbnail size:</b>&nbsp;Width (px):&nbsp;<input type="text" name="thumbWidth" value="120" style="width:50px;color:#000">&nbsp;&nbsp;Height (px):&nbsp;<input type="text" name="thumbHeight" value="120" style="width:50px;color:#000"></div>';
      //sPanel+='<br><br><div style="display:inline-block;">Maximum number of thumbnails per line:&nbsp;<input type="text" name="thumbMaxItemsPerLine" value="" style="width:50px;color:#000"></div>';
      //sPanel+='<div style="display:inline-block;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Maximum gallery width (px): <input type="text" name="thumbMaxWidth" value="" style="width:50px;color:#000"></div>';
      sPanel+='<br><br><div><b>Thumbnail label: </b></div>';
      sPanel+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Position: <form style="display:inline-block;"><input type="radio" name="thumbnailLabelPosition" value="overImageOnBottom" checked style="margin:0">overImageOnBottom&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="thumbnailLabelPosition" value="overImageOnTop" style="margin:0">overImageOnTop&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="thumbnailLabelPosition" value="onBottom" style="margin:0">onBottom&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="thumbnailLabelPosition" value="overImageOnMiddle" style="margin:0">overImageOnMiddle</form>';
      sPanel+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="thumbnailLabelDisplay" value="true" checked style="margin:0">Display label';
      sPanel+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="thumbnailLabelDisplayDescription" value="true" style="margin:0">Display description';
      sPanel+='<br><br><div><b>Gallery color scheme:</b> <br>';
      sPanel+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<form style="display:inline-block;"><input type="radio" checked name="colorScheme" value="none" style="margin:0">none &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="colorScheme" value="default" style="margin:0">default &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="colorScheme" value="dark" style="margin:0">dark &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="colorScheme" value="darkRed" style="margin:0">darkRed &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      sPanel+='<input type="radio" name="colorScheme" value="darkGreen" style="margin:0">darkGreen &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="colorScheme" value="darkBlue" style="margin:0">darkBlue &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      sPanel+='<input type="radio" name="colorScheme" value="darkOrange" style="margin:0">darkOrange &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="colorScheme" value="light" style="margin:0">light &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="colorScheme" value="lightBackground" style="margin:0">lightBackground';
      sPanel+='</form></div>';
      sPanel+='<br><div><b>Image display color scheme:</b> <br>';
      sPanel+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<form style="display:inline-block;"><input type="radio" checked name="colorSchemeViewer" value="none" style="margin:0">none &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="colorSchemeViewer" value="default" style="margin:0">default &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="colorSchemeViewer" value="darkRed" style="margin:0">darkRed &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="colorScheme" value="darkRed" style="margin:0">darkRed &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      sPanel+='<input type="radio" name="colorSchemeViewer" value="darkGreen" style="margin:0">darkGreen &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="colorSchemeViewer" value="darkBlue" style="margin:0">darkBlue &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      sPanel+='<input type="radio" name="colorSchemeViewer" value="darkOrange" style="margin:0">darkOrange &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="colorSchemeViewer" value="light" style="margin:0">light';
      sPanel+='</form></div>';
      sPanel+='<br><div><b>CSS file:</b> <br>';
      sPanel+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<form style="display:inline-block;"><input type="radio" name="cssFile" value="default" checked style="margin:0">default &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cssFile" value="clean" style="margin:0">clean&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="cssFile" value="light" style="margin:0">light</form></div>';
      sPanel+='<br><div><b>Background color</b> (not a nanoGALLERY parameter): <br>';
      sPanel+='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<form style="display:inline-block;"><input type="radio" name="backgroundColor" value="dark" checked style="margin:0">Dark&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="backgroundColor" value="light" style="margin:0">Light</form></div>';


      sPanel+='<div style="display:table;margin:auto;"><button name="bGo" "type="button" style="color:#000;padding:5px 15px;margin:5px;">--> Launch</button></div>';

      sPanel+='<hr style="margin:0px;"><div style="display:table;margin:15px auto;text-align:center;">';
      sPanel+='<button name="bPreset1" "type="button" style="color:#000;padding:4px;">Preset 1</button>';
      sPanel+='<button name="bPreset2" type="button" style="color:#000;padding:4px;">Preset 2</button>';
      sPanel+='<button name="bPreset3" type="button" style="color:#000;padding:4px;">Preset 3</button>';
      sPanel+='<button name="bPreset4" type="button" style="color:#000;padding:4px;">Preset 4</button>';
      sPanel+='<button name="bPreset5" type="button" style="color:#000;padding:4px;">Preset 5</button></div>';
      g_containerDemoPanel=jQuery(g_containerDemo).append('<div class="nanoGalleryDemoPanel" style="display:table;border:2px solid #666;background:#555;margin:10px auto;padding:5px;font-size:0.8em;">'+sPanel+'</div>');

      g_containerNew=jQuery(g_save).appendTo(g_containerDemo);
      jQuery(g_containerDemoPanel).find('[name=lHoverEffect1]').val('borderLighter');


      var nanoGALLERY_obj = new nanoGALLERY();
      nanoGALLERY_obj.Initiate(g_containerNew,settings);

      jQuery(g_containerDemoPanel).find('button[name=bGo]').on("click",function(){
        runDemo();
      });
      
      jQuery(g_containerDemoPanel).find('button[name=bPreset1]').on("click",function(){
        setPreset('borderLighter','imageSlideRight','none','overImageOnBottom',true,true,'dark');
      });
      jQuery(g_containerDemoPanel).find('button[name=bPreset2]').on("click",function(){
        setPreset('imageRotateCornerBL*','scale120*','borderLighter','overImageOnBottom',true,true,'dark');
      });
      jQuery(g_containerDemoPanel).find('button[name=bPreset3]').on("click",function(){
        setPreset('imageScale150*','labelSlideUp','none','overImageOnBottom',true,false,'dark');
      });
      jQuery(g_containerDemoPanel).find('button[name=bPreset4]').on("click",function(){
        setPreset('imageInvisible','imageScale150Outside*','none','overImageOnBottom',true,false,'light');
      });
      jQuery(g_containerDemoPanel).find('button[name=bPreset5]').on("click",function(){
        setPreset('descriptionSlideUp','borderLighter','none','overImageOnBottom',true,true,'dark');
      });
    });

    function setPreset(hoverEffect1,hoverEffect2,hoverEffect3,thumbnailLabelPosition,thumbnailLabelDisplay,thumbnailLabelDisplayDescription,backgroundColor) {
      jQuery(g_containerDemoPanel).find('[name=lHoverEffect1]').val(hoverEffect1);
      jQuery(g_containerDemoPanel).find('[name=lHoverEffect2]').val(hoverEffect2);
      jQuery(g_containerDemoPanel).find('[name=lHoverEffect3]').val(hoverEffect3);
      jQuery(g_containerDemoPanel).find('input:radio[name=thumbnailLabelPosition]').val([thumbnailLabelPosition]);
      jQuery(g_containerDemoPanel).find('[name=thumbnailLabelDisplay]').prop('checked',thumbnailLabelDisplay);
      jQuery(g_containerDemoPanel).find('[name=thumbnailLabelDisplayDescription]').prop('checked',thumbnailLabelDisplayDescription);
      jQuery(g_containerDemoPanel).find('input:radio[name=backgroundColor]').val([backgroundColor]);
      runDemo();
    }
    
    function runDemo() {
      settings.thumbnailHoverEffect=[];
      var sTHE=jQuery(g_containerDemoPanel).find('[name=lHoverEffect1] option:selected').text();
      if( sTHE != 'none' && sTHE != '' ) {
        sTHE=sTHE.replace('*', '');
        settings.thumbnailHoverEffect.push({'name':sTHE,'duration':200,'durationBack':200,'easing':'swing','easingBack':'swing'});
      }
      sTHE=jQuery(g_containerDemoPanel).find('[name=lHoverEffect2] option:selected').text();
      if( sTHE != 'none' && sTHE != '' ) {
        sTHE=sTHE.replace('*', '');
        settings.thumbnailHoverEffect.push({'name':sTHE,'duration':200,'durationBack':200,'easing':'swing','easingBack':'swing'});
      }
      sTHE=jQuery(g_containerDemoPanel).find('[name=lHoverEffect3] option:selected').text();
      if( sTHE != 'none' && sTHE != '' ) {
        sTHE=sTHE.replace('*', '');
        settings.thumbnailHoverEffect.push({'name':sTHE,'duration':200,'durationBack':200,'easing':'swing','easingBack':'swing'});
      }
      var tW=+parseInt(jQuery(g_containerDemoPanel).find('[name=thumbWidth]').val(),10);
      if( tW >= 10 && tW <= 500) {
        jQuery(g_containerDemoPanel).find('[name=thumbWidth]').val(tW);  
        settings.thumbnailWidth=tW;
      }
      var tH=parseInt(jQuery(g_containerDemoPanel).find('[name=thumbHeight]').val(),10);
      if( tH>= 10 && tH <=500) {
        jQuery(g_containerDemoPanel).find('[name=thumbHeight]').val(tH);
        settings.thumbnailHeight=tH;
      }
      //var tMIPL=parseInt(jQuery(g_containerDemoPanel).find('[name=thumbMaxItemsPerLine]').val(),10);
      //if( tMIPL>= 0 ) {
      //  jQuery(g_containerDemoPanel).find('[name=thumbMaxItemsPerLine]').val(tMIPL);
      //  settings.maxItemsPerLine=tMIPL;
      //}
      //var tMW=parseInt(jQuery(g_containerDemoPanel).find('[name=thumbMaxWidth]').val(),10);
      //if( tMW>= 50 ) {
      //  jQuery(g_containerDemoPanel).find('[name=thumbMaxWidth]').val(tMW);
      //  settings.maxWidth=tMW;
      //}
      settings.thumbnailLabel.position=jQuery(g_containerDemoPanel).find('input[name=thumbnailLabelPosition]:checked',g_containerDemoPanel).val();
      settings.thumbnailLabel.display=jQuery(g_containerDemoPanel).find('[name=thumbnailLabelDisplay]').prop('checked');
      settings.thumbnailLabel.displayDescription=jQuery(g_containerDemoPanel).find('[name=thumbnailLabelDisplayDescription]').prop('checked');
      settings.colorScheme=jQuery(g_containerDemoPanel).find('input[name=colorScheme]:checked',g_containerDemoPanel).val();
      settings.colorSchemeViewer=jQuery(g_containerDemoPanel).find('input[name=colorSchemeViewer]:checked',g_containerDemoPanel).val();
      settings.theme=jQuery(g_containerDemoPanel).find('input[name=cssFile]:checked',g_containerDemoPanel).val();
      
      if( jQuery(g_containerDemoPanel).find('input[name=backgroundColor]:checked',g_containerDemoPanel).val() == 'dark' ) {
        jQuery(g_containerDemoPanel).css('background','#222');
      }
      else {
        jQuery(g_containerDemoPanel).css('background','#eee');
      }
      
      jQuery(g_containerNew).animate({opacity: 0,height:0},200).promise().done(function(){
      jQuery(g_containerNew).remove();
        g_containerNew=jQuery(g_save).appendTo(g_containerDemo);
        var nanoGALLERY_obj = new nanoGALLERY();
        nanoGALLERY_obj.Initiate(g_containerNew,settings);
        //jQuery(elt).css('opacity','1');
      });
    }
  };

}( jQuery ));
  
  

  
// #################
// ##### TOOLS #####
// #################
  
  // Display a message
  function nanoAlert( msg ) {
    alert('nanoGALLERY: ' + msg);
    nanoConsoleLog(msg);
  };
  
  // write to console log
  function nanoConsoleLog( msg ) {
    if (window.console) { console.log('nanoGALLERY: ' + msg); }
  };
  
  // get viewport coordinates and size
  function getViewport() {
    var $win = jQuery(window);
    return {
      l: $win.scrollLeft(),
      t: $win.scrollTop(),
      w: $win.width(),
      h: $win.height()
    }
  }


  function inViewport( $elt, threshold ) {
    var wp=getViewport();
    var eltOS=jQuery($elt).offset();
    var th=jQuery($elt).outerHeight(true);
    var tw=jQuery($elt).outerWidth(true);
    if( eltOS.top >= (wp.t-threshold) 
      && (eltOS.top+th) <= (wp.t+wp.h+threshold) 
      && eltOS.left >= (wp.l-threshold) 
      && (eltOS.left+tw) <= (wp.l+wp.w+threshold) ) {
        return true;
    }
    else {
      return false;
    }
  }

  
  // set z-index to display element on top of all others
  function setElementOnTop( start, elt ) {
    var highest_index = 0;
    if( start=='' ) { start= '*'; }
    jQuery(start).each(function() {
      var cur = parseInt(jQuery(this).css('z-index'));
      highest_index = cur > highest_index ? cur : highest_index;
    });
    highest_index++;
    jQuery(elt).css('z-index',highest_index);
  };

  // set z-index to display 2 elements on top of all others
  function set2ElementsOnTop( start, elt1, elt2 ) {
    var highest_index = 0;
    if( start=='' ) { start= '*'; }
    jQuery(start).each(function() {
      var cur = parseInt(jQuery(this).css('z-index'));
      highest_index = cur > highest_index ? cur : highest_index;
    });
    highest_index++;
    jQuery(elt2).css('z-index',highest_index+1);
    jQuery(elt1).css('z-index',highest_index);
  };

  
  // return the real type of the object
  var toType = function( obj ) {
    // by Angus Croll - http://javascriptweblog.wordpress.com/2011/08/08/fixing-the-javascript-typeof-operator/
    return ({}).toString.call(obj).match(/\s([a-zA-Z]+)/)[1].toLowerCase()
  };

  
  // return true if current jQuery version match the minimum required
  function jQueryMinVersion( version ) {
    var $vrs = window.jQuery.fn.jquery.split('.'),
    min = version.split('.');
    for (var i=0, len=$vrs.length; i<len; i++) {
      if (min[i] && (+$vrs[i]) < (+min[i])) {
        return false;
      }
    }
    return true;
  };
  
  // color lighter or darker
  // found on http://stackoverflow.com/questions/1507931/generate-lighter-darker-color-in-css-using-javascript/5747818#5747818
  // Ratio is between 0 and 1
  var changeColor = function( color, ratio, darker ) {
    // Trim trailing/leading whitespace
    color = color.replace(/^\s*|\s*$/, '');

    // Expand three-digit hex
    color = color.replace(
      /^#?([a-f0-9])([a-f0-9])([a-f0-9])$/i,
      '#$1$1$2$2$3$3'
    );

    // Calculate ratio
    var difference = Math.round(ratio * 256) * (darker ? -1 : 1),
      // Determine if input is RGB(A)
      rgb = color.match(new RegExp('^rgba?\\(\\s*' +
        '(\\d|[1-9]\\d|1\\d{2}|2[0-4][0-9]|25[0-5])' +
        '\\s*,\\s*' +
        '(\\d|[1-9]\\d|1\\d{2}|2[0-4][0-9]|25[0-5])' +
        '\\s*,\\s*' +
        '(\\d|[1-9]\\d|1\\d{2}|2[0-4][0-9]|25[0-5])' +
        '(?:\\s*,\\s*' +
        '(0|1|0?\\.\\d+))?' +
        '\\s*\\)$'
      , 'i')),
      alpha = !!rgb && rgb[4] != null ? rgb[4] : null,

      // Convert hex to decimal
      decimal = !!rgb? [rgb[1], rgb[2], rgb[3]] : color.replace(
        /^#?([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])([a-f0-9][a-f0-9])/i,
        function() {
          return parseInt(arguments[1], 16) + ',' +
            parseInt(arguments[2], 16) + ',' +
            parseInt(arguments[3], 16);
        }
      ).split(/,/),
      returnValue;

    // Return RGB(A)
    return !!rgb ?
      'rgb' + (alpha !== null ? 'a' : '') + '(' +
        Math[darker ? 'max' : 'min'](
          parseInt(decimal[0], 10) + difference, darker ? 0 : 255
        ) + ', ' +
        Math[darker ? 'max' : 'min'](
          parseInt(decimal[1], 10) + difference, darker ? 0 : 255
        ) + ', ' +
        Math[darker ? 'max' : 'min'](
          parseInt(decimal[2], 10) + difference, darker ? 0 : 255
        ) +
        (alpha !== null ? ', ' + alpha : '') +
        ')' :
      // Return hex
      [
        '#',
        pad(Math[darker ? 'max' : 'min'](
          parseInt(decimal[0], 10) + difference, darker ? 0 : 255
        ).toString(16), 2),
        pad(Math[darker ? 'max' : 'min'](
          parseInt(decimal[1], 10) + difference, darker ? 0 : 255
        ).toString(16), 2),
        pad(Math[darker ? 'max' : 'min'](
          parseInt(decimal[2], 10) + difference, darker ? 0 : 255
        ).toString(16), 2)
      ].join('');
  };
  var lighterColor = function(color, ratio) {
    return changeColor(color, ratio, false);
  };
  var darkerColor = function(color, ratio) {
    return changeColor(color, ratio, true);
  };
  var pad = function(num, totalChars) {
    var pad = '0';
    num = num + '';
    while (num.length < totalChars) {
      num = pad + num;
    }
    return num;
  };  
}



/*!
 * jQuery Color Animations v2.1.2
 * https://github.com/jquery/jquery-color
 *
 * Copyright 2013 jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * Date: Wed Jan 16 08:47:09 2013 -0600
 */
(function( jQuery, undefined ) {

  var stepHooks = "backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor",

  // plusequals test for += 100 -= 100
  rplusequals = /^([\-+])=\s*(\d+\.?\d*)/,
  // a set of RE's that can match strings and generate color tuples.
  stringParsers = [{
      re: /rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
      parse: function( execResult ) {
        return [
          execResult[ 1 ],
          execResult[ 2 ],
          execResult[ 3 ],
          execResult[ 4 ]
        ];
      }
    }, {
      re: /rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
      parse: function( execResult ) {
        return [
          execResult[ 1 ] * 2.55,
          execResult[ 2 ] * 2.55,
          execResult[ 3 ] * 2.55,
          execResult[ 4 ]
        ];
      }
    }, {
      // this regex ignores A-F because it's compared against an already lowercased string
      re: /#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/,
      parse: function( execResult ) {
        return [
          parseInt( execResult[ 1 ], 16 ),
          parseInt( execResult[ 2 ], 16 ),
          parseInt( execResult[ 3 ], 16 )
        ];
      }
    }, {
      // this regex ignores A-F because it's compared against an already lowercased string
      re: /#([a-f0-9])([a-f0-9])([a-f0-9])/,
      parse: function( execResult ) {
        return [
          parseInt( execResult[ 1 ] + execResult[ 1 ], 16 ),
          parseInt( execResult[ 2 ] + execResult[ 2 ], 16 ),
          parseInt( execResult[ 3 ] + execResult[ 3 ], 16 )
        ];
      }
    }, {
      re: /hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d?(?:\.\d+)?)\s*)?\)/,
      space: "hsla",
      parse: function( execResult ) {
        return [
          execResult[ 1 ],
          execResult[ 2 ] / 100,
          execResult[ 3 ] / 100,
          execResult[ 4 ]
        ];
      }
    }],

  // jQuery.Color( )
  color = jQuery.Color = function( color, green, blue, alpha ) {
    return new jQuery.Color.fn.parse( color, green, blue, alpha );
  },
  spaces = {
    rgba: {
      props: {
        red: {
          idx: 0,
          type: "byte"
        },
        green: {
          idx: 1,
          type: "byte"
        },
        blue: {
          idx: 2,
          type: "byte"
        }
      }
    },

    hsla: {
      props: {
        hue: {
          idx: 0,
          type: "degrees"
        },
        saturation: {
          idx: 1,
          type: "percent"
        },
        lightness: {
          idx: 2,
          type: "percent"
        }
      }
    }
  },
  propTypes = {
    "byte": {
      floor: true,
      max: 255
    },
    "percent": {
      max: 1
    },
    "degrees": {
      mod: 360,
      floor: true
    }
  },
  support = color.support = {},

  // element for support tests
  supportElem = jQuery( "<p>" )[ 0 ],

  // colors = jQuery.Color.names
  colors,

  // local aliases of functions called often
  each = jQuery.each;

// determine rgba support immediately
supportElem.style.cssText = "background-color:rgba(1,1,1,.5)";
support.rgba = supportElem.style.backgroundColor.indexOf( "rgba" ) > -1;

// define cache name and alpha properties
// for rgba and hsla spaces
each( spaces, function( spaceName, space ) {
  space.cache = "_" + spaceName;
  space.props.alpha = {
    idx: 3,
    type: "percent",
    def: 1
  };
});

function clamp( value, prop, allowEmpty ) {
  var type = propTypes[ prop.type ] || {};

  if ( value == null ) {
    return (allowEmpty || !prop.def) ? null : prop.def;
  }

  // ~~ is an short way of doing floor for positive numbers
  value = type.floor ? ~~value : parseFloat( value );

  // IE will pass in empty strings as value for alpha,
  // which will hit this case
  if ( isNaN( value ) ) {
    return prop.def;
  }

  if ( type.mod ) {
    // we add mod before modding to make sure that negatives values
    // get converted properly: -10 -> 350
    return (value + type.mod) % type.mod;
  }

  // for now all property types without mod have min and max
  return 0 > value ? 0 : type.max < value ? type.max : value;
}

function stringParse( string ) {
  var inst = color(),
    rgba = inst._rgba = [];

  string = string.toLowerCase();

  each( stringParsers, function( i, parser ) {
    var parsed,
      match = parser.re.exec( string ),
      values = match && parser.parse( match ),
      spaceName = parser.space || "rgba";

    if ( values ) {
      parsed = inst[ spaceName ]( values );

      // if this was an rgba parse the assignment might happen twice
      // oh well....
      inst[ spaces[ spaceName ].cache ] = parsed[ spaces[ spaceName ].cache ];
      rgba = inst._rgba = parsed._rgba;

      // exit each( stringParsers ) here because we matched
      return false;
    }
  });

  // Found a stringParser that handled it
  if ( rgba.length ) {

    // if this came from a parsed string, force "transparent" when alpha is 0
    // chrome, (and maybe others) return "transparent" as rgba(0,0,0,0)
    if ( rgba.join() === "0,0,0,0" ) {
      jQuery.extend( rgba, colors.transparent );
    }
    return inst;
  }

  // named colors
  return colors[ string ];
}

color.fn = jQuery.extend( color.prototype, {
  parse: function( red, green, blue, alpha ) {
    if ( red === undefined ) {
      this._rgba = [ null, null, null, null ];
      return this;
    }
    if ( red.jquery || red.nodeType ) {
      red = jQuery( red ).css( green );
      green = undefined;
    }

    var inst = this,
      type = jQuery.type( red ),
      rgba = this._rgba = [];

    // more than 1 argument specified - assume ( red, green, blue, alpha )
    if ( green !== undefined ) {
      red = [ red, green, blue, alpha ];
      type = "array";
    }

    if ( type === "string" ) {
      return this.parse( stringParse( red ) || colors._default );
    }

    if ( type === "array" ) {
      each( spaces.rgba.props, function( key, prop ) {
        rgba[ prop.idx ] = clamp( red[ prop.idx ], prop );
      });
      return this;
    }

    if ( type === "object" ) {
      if ( red instanceof color ) {
        each( spaces, function( spaceName, space ) {
          if ( red[ space.cache ] ) {
            inst[ space.cache ] = red[ space.cache ].slice();
          }
        });
      } else {
        each( spaces, function( spaceName, space ) {
          var cache = space.cache;
          each( space.props, function( key, prop ) {

            // if the cache doesn't exist, and we know how to convert
            if ( !inst[ cache ] && space.to ) {

              // if the value was null, we don't need to copy it
              // if the key was alpha, we don't need to copy it either
              if ( key === "alpha" || red[ key ] == null ) {
                return;
              }
              inst[ cache ] = space.to( inst._rgba );
            }

            // this is the only case where we allow nulls for ALL properties.
            // call clamp with alwaysAllowEmpty
            inst[ cache ][ prop.idx ] = clamp( red[ key ], prop, true );
          });

          // everything defined but alpha?
          if ( inst[ cache ] && jQuery.inArray( null, inst[ cache ].slice( 0, 3 ) ) < 0 ) {
            // use the default of 1
            inst[ cache ][ 3 ] = 1;
            if ( space.from ) {
              inst._rgba = space.from( inst[ cache ] );
            }
          }
        });
      }
      return this;
    }
  },
  is: function( compare ) {
    var is = color( compare ),
      same = true,
      inst = this;

    each( spaces, function( _, space ) {
      var localCache,
        isCache = is[ space.cache ];
      if (isCache) {
        localCache = inst[ space.cache ] || space.to && space.to( inst._rgba ) || [];
        each( space.props, function( _, prop ) {
          if ( isCache[ prop.idx ] != null ) {
            same = ( isCache[ prop.idx ] === localCache[ prop.idx ] );
            return same;
          }
        });
      }
      return same;
    });
    return same;
  },
  _space: function() {
    var used = [],
      inst = this;
    each( spaces, function( spaceName, space ) {
      if ( inst[ space.cache ] ) {
        used.push( spaceName );
      }
    });
    return used.pop();
  },
  transition: function( other, distance ) {
    var end = color( other ),
      spaceName = end._space(),
      space = spaces[ spaceName ],
      startColor = this.alpha() === 0 ? color( "transparent" ) : this,
      start = startColor[ space.cache ] || space.to( startColor._rgba ),
      result = start.slice();

    end = end[ space.cache ];
    each( space.props, function( key, prop ) {
      var index = prop.idx,
        startValue = start[ index ],
        endValue = end[ index ],
        type = propTypes[ prop.type ] || {};

      // if null, don't override start value
      if ( endValue === null ) {
        return;
      }
      // if null - use end
      if ( startValue === null ) {
        result[ index ] = endValue;
      } else {
        if ( type.mod ) {
          if ( endValue - startValue > type.mod / 2 ) {
            startValue += type.mod;
          } else if ( startValue - endValue > type.mod / 2 ) {
            startValue -= type.mod;
          }
        }
        result[ index ] = clamp( ( endValue - startValue ) * distance + startValue, prop );
      }
    });
    return this[ spaceName ]( result );
  },
  blend: function( opaque ) {
    // if we are already opaque - return ourself
    if ( this._rgba[ 3 ] === 1 ) {
      return this;
    }

    var rgb = this._rgba.slice(),
      a = rgb.pop(),
      blend = color( opaque )._rgba;

    return color( jQuery.map( rgb, function( v, i ) {
      return ( 1 - a ) * blend[ i ] + a * v;
    }));
  },
  toRgbaString: function() {
    var prefix = "rgba(",
      rgba = jQuery.map( this._rgba, function( v, i ) {
        return v == null ? ( i > 2 ? 1 : 0 ) : v;
      });

    if ( rgba[ 3 ] === 1 ) {
      rgba.pop();
      prefix = "rgb(";
    }

    return prefix + rgba.join() + ")";
  },
  toHslaString: function() {
    var prefix = "hsla(",
      hsla = jQuery.map( this.hsla(), function( v, i ) {
        if ( v == null ) {
          v = i > 2 ? 1 : 0;
        }

        // catch 1 and 2
        if ( i && i < 3 ) {
          v = Math.round( v * 100 ) + "%";
        }
        return v;
      });

    if ( hsla[ 3 ] === 1 ) {
      hsla.pop();
      prefix = "hsl(";
    }
    return prefix + hsla.join() + ")";
  },
  toHexString: function( includeAlpha ) {
    var rgba = this._rgba.slice(),
      alpha = rgba.pop();

    if ( includeAlpha ) {
      rgba.push( ~~( alpha * 255 ) );
    }

    return "#" + jQuery.map( rgba, function( v ) {

      // default to 0 when nulls exist
      v = ( v || 0 ).toString( 16 );
      return v.length === 1 ? "0" + v : v;
    }).join("");
  },
  toString: function() {
    return this._rgba[ 3 ] === 0 ? "transparent" : this.toRgbaString();
  }
});
color.fn.parse.prototype = color.fn;

// hsla conversions adapted from:
// https://code.google.com/p/maashaack/source/browse/packages/graphics/trunk/src/graphics/colors/HUE2RGB.as?r=5021

function hue2rgb( p, q, h ) {
  h = ( h + 1 ) % 1;
  if ( h * 6 < 1 ) {
    return p + (q - p) * h * 6;
  }
  if ( h * 2 < 1) {
    return q;
  }
  if ( h * 3 < 2 ) {
    return p + (q - p) * ((2/3) - h) * 6;
  }
  return p;
}

spaces.hsla.to = function ( rgba ) {
  if ( rgba[ 0 ] == null || rgba[ 1 ] == null || rgba[ 2 ] == null ) {
    return [ null, null, null, rgba[ 3 ] ];
  }
  var r = rgba[ 0 ] / 255,
    g = rgba[ 1 ] / 255,
    b = rgba[ 2 ] / 255,
    a = rgba[ 3 ],
    max = Math.max( r, g, b ),
    min = Math.min( r, g, b ),
    diff = max - min,
    add = max + min,
    l = add * 0.5,
    h, s;

  if ( min === max ) {
    h = 0;
  } else if ( r === max ) {
    h = ( 60 * ( g - b ) / diff ) + 360;
  } else if ( g === max ) {
    h = ( 60 * ( b - r ) / diff ) + 120;
  } else {
    h = ( 60 * ( r - g ) / diff ) + 240;
  }

  // chroma (diff) == 0 means greyscale which, by definition, saturation = 0%
  // otherwise, saturation is based on the ratio of chroma (diff) to lightness (add)
  if ( diff === 0 ) {
    s = 0;
  } else if ( l <= 0.5 ) {
    s = diff / add;
  } else {
    s = diff / ( 2 - add );
  }
  return [ Math.round(h) % 360, s, l, a == null ? 1 : a ];
};

spaces.hsla.from = function ( hsla ) {
  if ( hsla[ 0 ] == null || hsla[ 1 ] == null || hsla[ 2 ] == null ) {
    return [ null, null, null, hsla[ 3 ] ];
  }
  var h = hsla[ 0 ] / 360,
    s = hsla[ 1 ],
    l = hsla[ 2 ],
    a = hsla[ 3 ],
    q = l <= 0.5 ? l * ( 1 + s ) : l + s - l * s,
    p = 2 * l - q;

  return [
    Math.round( hue2rgb( p, q, h + ( 1 / 3 ) ) * 255 ),
    Math.round( hue2rgb( p, q, h ) * 255 ),
    Math.round( hue2rgb( p, q, h - ( 1 / 3 ) ) * 255 ),
    a
  ];
};


each( spaces, function( spaceName, space ) {
  var props = space.props,
    cache = space.cache,
    to = space.to,
    from = space.from;

  // makes rgba() and hsla()
  color.fn[ spaceName ] = function( value ) {

    // generate a cache for this space if it doesn't exist
    if ( to && !this[ cache ] ) {
      this[ cache ] = to( this._rgba );
    }
    if ( value === undefined ) {
      return this[ cache ].slice();
    }

    var ret,
      type = jQuery.type( value ),
      arr = ( type === "array" || type === "object" ) ? value : arguments,
      local = this[ cache ].slice();

    each( props, function( key, prop ) {
      var val = arr[ type === "object" ? key : prop.idx ];
      if ( val == null ) {
        val = local[ prop.idx ];
      }
      local[ prop.idx ] = clamp( val, prop );
    });

    if ( from ) {
      ret = color( from( local ) );
      ret[ cache ] = local;
      return ret;
    } else {
      return color( local );
    }
  };

  // makes red() green() blue() alpha() hue() saturation() lightness()
  each( props, function( key, prop ) {
    // alpha is included in more than one space
    if ( color.fn[ key ] ) {
      return;
    }
    color.fn[ key ] = function( value ) {
      var vtype = jQuery.type( value ),
        fn = ( key === "alpha" ? ( this._hsla ? "hsla" : "rgba" ) : spaceName ),
        local = this[ fn ](),
        cur = local[ prop.idx ],
        match;

      if ( vtype === "undefined" ) {
        return cur;
      }

      if ( vtype === "function" ) {
        value = value.call( this, cur );
        vtype = jQuery.type( value );
      }
      if ( value == null && prop.empty ) {
        return this;
      }
      if ( vtype === "string" ) {
        match = rplusequals.exec( value );
        if ( match ) {
          value = cur + parseFloat( match[ 2 ] ) * ( match[ 1 ] === "+" ? 1 : -1 );
        }
      }
      local[ prop.idx ] = value;
      return this[ fn ]( local );
    };
  });
});

// add cssHook and .fx.step function for each named hook.
// accept a space separated string of properties
color.hook = function( hook ) {
  var hooks = hook.split( " " );
  each( hooks, function( i, hook ) {
    jQuery.cssHooks[ hook ] = {
      set: function( elem, value ) {
        var parsed, curElem,
          backgroundColor = "";

        if ( value !== "transparent" && ( jQuery.type( value ) !== "string" || ( parsed = stringParse( value ) ) ) ) {
          value = color( parsed || value );
          if ( !support.rgba && value._rgba[ 3 ] !== 1 ) {
            curElem = hook === "backgroundColor" ? elem.parentNode : elem;
            while (
              (backgroundColor === "" || backgroundColor === "transparent") &&
              curElem && curElem.style
            ) {
              try {
                backgroundColor = jQuery.css( curElem, "backgroundColor" );
                curElem = curElem.parentNode;
              } catch ( e ) {
              }
            }

            value = value.blend( backgroundColor && backgroundColor !== "transparent" ?
              backgroundColor :
              "_default" );
          }

          value = value.toRgbaString();
        }
        try {
          elem.style[ hook ] = value;
        } catch( e ) {
          // wrapped to prevent IE from throwing errors on "invalid" values like 'auto' or 'inherit'
        }
      }
    };
    jQuery.fx.step[ hook ] = function( fx ) {
      if ( !fx.colorInit ) {
        fx.start = color( fx.elem, hook );
        fx.end = color( fx.end );
        fx.colorInit = true;
      }
      jQuery.cssHooks[ hook ].set( fx.elem, fx.start.transition( fx.end, fx.pos ) );
    };
  });

};

color.hook( stepHooks );

jQuery.cssHooks.borderColor = {
  expand: function( value ) {
    var expanded = {};

    each( [ "Top", "Right", "Bottom", "Left" ], function( i, part ) {
      expanded[ "border" + part + "Color" ] = value;
    });
    return expanded;
  }
};

// Basic color names only.
// Usage of any of the other color names requires adding yourself or including
// jquery.color.svg-names.js.
colors = jQuery.Color.names = {
  // 4.1. Basic color keywords
  aqua: "#00ffff",
  black: "#000000",
  blue: "#0000ff",
  fuchsia: "#ff00ff",
  gray: "#808080",
  green: "#008000",
  lime: "#00ff00",
  maroon: "#800000",
  navy: "#000080",
  olive: "#808000",
  purple: "#800080",
  red: "#ff0000",
  silver: "#c0c0c0",
  teal: "#008080",
  white: "#ffffff",
  yellow: "#ffff00",

  // 4.2.3. "transparent" color keyword
  transparent: [ null, null, null, 0 ],

  _default: "#ffffff"
};

})( jQuery );



(function($, window, document, undefined) {

    'use strict';

    var defaults = {
		intense: true,
		photoData: {},
		data_loading_gif: '',
		intense_big: 0,
    };

    /**
     * Creates the autoplay plugin.
     * @param {object} element - lightGallery element
     */
    var OzioIntense = function(element) {

        this.core = $(element).data('lightGallery');

        this.$el = $(element);

        this.core.s = $.extend({}, defaults, this.core.s);

        this.init();

        return this;
    };

    OzioIntense.prototype.init = function() {
        var _this = this;

        // append autoplay controls
        if (_this.core.s.intense) {
            _this.intenseAdd();
        }


    };



    // Manage autoplay via play/stop buttons
    OzioIntense.prototype.intenseAdd = function() {
        var _this = this;
		
		var zoomIcons = '<span id="lg-zoom-in" class="lg-icon"></span>';

        this.core.$outer.find('.lg-toolbar').append(zoomIcons);		

        _this.core.$outer.find('#lg-zoom-in').on('click.lg', function() {
			if (!gi_ozio_intenseViewer){
				gi_ozio_intenseViewer=true
				//visualizzo il popup
				_this.showIntenseBox();
			}
        });
    };

	OzioIntense.prototype.showIntenseBox = function() {
        var _this = this;
		
		var current=_this.core.$slide.eq(_this.core.index).find('.lg-img-wrap > img').attr( 'src' );
		if (!_this.core.s.photoData.hasOwnProperty(current)){
			return;
		}
		
		var photoData=_this.core.s.photoData[current];
		
		var link = photoData.seed;
		var bigdata = _this.core.s.intense_big;
		_this.core.$outer.find('.ozio-intense-div').remove();
		var newdiv=$('<div class="ozio-intense-div"></div>');
		_this.core.$outer.append(newdiv);
		
		if (bigdata == 0 || bigdata == ''){
			newdiv.attr('data-image',link + 's0/');
		}else{
			newdiv.attr('data-image',link + 's'+bigdata+'/');
		}
		
		newdiv.attr('data-title',photoData.album);
		newdiv.attr('data-caption',photoData.photo);
		newdiv.attr('data-loading-gif',_this.core.s.data_loading_gif);
		Intense( newdiv );
		
		
		
	},	

    OzioIntense.prototype.destroy = function() {

    };

    $.fn.lightGallery.modules.oziointense = OzioIntense;

})(jQuery, window, document);

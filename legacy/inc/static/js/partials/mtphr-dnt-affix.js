/* ========================================================================
 * Bootstrap: mtphr_dnt_affix.js v3.3.5
 * http://getbootstrap.com/javascript/#mtphr_dnt_affix
 * ========================================================================
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/master/LICENSE)
 * ======================================================================== */


(function ($) {
  'use strict';

  // AFFIX CLASS DEFINITION
  // ======================

  var MTPHR_DNT_Affix = function (element, options) {
    this.options = $.extend({}, MTPHR_DNT_Affix.DEFAULTS, options);

    this.$target = $(this.options.target)
      .on('scroll.bs.mtphr_dnt_affix.data-api', $.proxy(this.checkPosition, this))
      .on('click.bs.mtphr_dnt_affix.data-api',  $.proxy(this.checkPositionWithEventLoop, this));

    this.$element     = $(element);
    this.mtphr_dnt_affixed      = null;
    this.unpin        = null;
    this.pinnedOffset = null;

    this.checkPosition();
  };

  MTPHR_DNT_Affix.VERSION  = '3.3.5';

  MTPHR_DNT_Affix.RESET    = 'mtphr_dnt_affix mtphr_dnt_affix-top mtphr_dnt_affix-bottom';

  MTPHR_DNT_Affix.DEFAULTS = {
    offset: 0,
    target: window
  };

  MTPHR_DNT_Affix.prototype.getState = function (scrollHeight, height, offsetTop, offsetBottom) {
    var scrollTop    = this.$target.scrollTop();
    var position     = this.$element.offset();
    var targetHeight = this.$target.height();

    if (offsetTop !== null && this.mtphr_dnt_affixed === 'top') {
    	return scrollTop < offsetTop ? 'top' : false;
    }

    if (this.mtphr_dnt_affixed === 'bottom') {
      if (offsetTop !== null) { 
      	return (scrollTop + this.unpin <= position.top) ? false : 'bottom';
      }
      return (scrollTop + targetHeight <= scrollHeight - offsetBottom) ? false : 'bottom';
    }

    var initializing   = this.mtphr_dnt_affixed === null;
    var colliderTop    = initializing ? scrollTop : position.top;
    var colliderHeight = initializing ? targetHeight : height;

    if (offsetTop !== null && scrollTop <= offsetTop) {
	    return 'top';
	  }
    if (offsetBottom !== null && (colliderTop + colliderHeight >= scrollHeight - offsetBottom)) {
	    return 'bottom';
	  }

    return false;
  };

  MTPHR_DNT_Affix.prototype.getPinnedOffset = function () {
    if (this.pinnedOffset) {
    	return this.pinnedOffset;
    }
    this.$element.removeClass(MTPHR_DNT_Affix.RESET).addClass('mtphr_dnt_affix');
    var scrollTop = this.$target.scrollTop();
    var position  = this.$element.offset();
    return (this.pinnedOffset = position.top - scrollTop);
  };

  MTPHR_DNT_Affix.prototype.checkPositionWithEventLoop = function () {
    setTimeout($.proxy(this.checkPosition, this), 1);
  };

  MTPHR_DNT_Affix.prototype.checkPosition = function () {
    if (!this.$element.is(':visible')) {
	    return;
	  }

    var height       = this.$element.height();
    var offset       = this.options.offset;
    var offsetTop    = offset.top;
    var offsetBottom = offset.bottom;
    var scrollHeight = Math.max($(document).height(), $(document.body).height());

    if (typeof offset !== 'object') {
	  	offsetBottom = offsetTop = offset;
	  }
    if (typeof offsetTop === 'function') {
	  	offsetTop    = offset.top(this.$element);
	  }
    if (typeof offsetBottom === 'function') {
	    offsetBottom = offset.bottom(this.$element);
	  }

    var mtphr_dnt_affix = this.getState(scrollHeight, height, offsetTop, offsetBottom);

    if (this.mtphr_dnt_affixed !== mtphr_dnt_affix) {
      if (this.unpin !== null) {
	      this.$element.css('top', '');
	    }

      var mtphr_dnt_affixType = 'mtphr_dnt_affix' + (mtphr_dnt_affix ? '-' + mtphr_dnt_affix : '');
      var e         = $.Event(mtphr_dnt_affixType + '.bs.mtphr_dnt_affix');

      this.$element.trigger(e);

      if (e.isDefaultPrevented()) {
	      return;
	    }

      this.mtphr_dnt_affixed = mtphr_dnt_affix;
      this.unpin = mtphr_dnt_affix === 'bottom' ? this.getPinnedOffset() : null;

      this.$element
        .removeClass(MTPHR_DNT_Affix.RESET)
        .addClass(mtphr_dnt_affixType)
        .trigger(mtphr_dnt_affixType.replace('mtphr_dnt_affix', 'mtphr_dnt_affixed') + '.bs.mtphr_dnt_affix');
    }

    if (mtphr_dnt_affix === 'bottom') {
      this.$element.offset({
        top: scrollHeight - height - offsetBottom
      });
    }
  };


  // AFFIX PLUGIN DEFINITION
  // =======================

  function Plugin(option) {
    return this.each(function () {
      var $this   = $(this);
      var data    = $this.data('bs.mtphr_dnt_affix');
      var options = typeof option === 'object' && option;

      if (!data) {
	      $this.data('bs.mtphr_dnt_affix', (data = new MTPHR_DNT_Affix(this, options)));
	    }
      if (typeof option === 'string') {
      	data[option]();
      }
    });
  }

  var old = $.fn.mtphr_dnt_affix;

  $.fn.mtphr_dnt_affix             = Plugin;
  $.fn.mtphr_dnt_affix.Constructor = MTPHR_DNT_Affix;


  // AFFIX NO CONFLICT
  // =================

  $.fn.mtphr_dnt_affix.noConflict = function () {
    $.fn.mtphr_dnt_affix = old;
    return this;
  };


  // AFFIX DATA-API
  // ==============

  $(window).on('load', function () {
    $('[data-spy="mtphr_dnt_affix"]').each(function () {
      var $spy = $(this);
      var data = $spy.data();

      data.offset = data.offset || {};

      if (data.offsetBottom !== null) {
	      data.offset.bottom = data.offsetBottom;
	    }
      if (data.offsetTop    !== null) {
      	data.offset.top    = data.offsetTop;
      }

      Plugin.call($spy, data);
    });
  });

}(jQuery));

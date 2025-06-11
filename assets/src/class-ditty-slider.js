/* global jQuery:true */
/* global Hammer:true */

/**
 * Ditty Slider class
 *
 * @since		3.0
 * @return	null
 */

(function ($) {
  "use strict";

  var defaults = {
    transition: "fade", // fade, slideLeft, slideRight, slideDown, slideUp
    transitionEase: "easeInOutQuint",
    transitionSpeed: 1.5, // 1 - 10
    autoplay: 0, // 0, 1
    autoplayPause: 0, // 0, 1
    autoplaySpeed: 7, // 1 - 60
    height: 0,
    heightEase: "easeInOutQuint",
    heightSpeed: 1.5, // 1 - 10
    initTransition: "fade", // fade, slideLeft, slideREight, slideDown, slideUp
    initTransitionEase: "easeInOutQuint",
    initTransitionSpeed: 1.5, // 1 - 10
    initHeightEase: "easeInOutQuint",
    initHeightSpeed: 0.5, // 1 - 10
    arrows: "none",
    arrowsIconColor: "",
    arrowsBgColor: "",
    arrowsPosition: "center",
    arrowsPadding: {},
    arrowsStatic: 0,
    navPrev: '<i class="fas fa-angle-left"></i>',
    navNext: '<i class="fas fa-angle-right"></i>',
    bullets: "none",
    bulletsColor: "",
    bulletsColorActive: "",
    bulletsPosition: "bottomCenter",
    bulletsSpacing: 2,
    bulletsPadding: {},
    bullet: "",
    maxWidth: "",
    bgColor: "",
    padding: {},
    margin: {},
    borderColor: {},
    borderStyle: {},
    borderWidth: {},
    borderRadius: {},
    contentsBgColor: "",
    contentsPadding: {},
    contentsBorderColor: {},
    contentsBorderStyle: {},
    contentsBorderWidth: {},
    contentsBorderRadius: {},
    slideBgColor: "",
    slidePadding: {},
    slideBorderColor: {},
    slideBorderStyle: {},
    slideBorderWidth: {},
    slideBorderRadius: {},
    slide: 0,
    slideId: false,
    touchSwipe: true,
    //init									: function () {},
    slidesEl: false,
    slides: [
      // {
      //  id:                null,
      //  html:              null,
      // ...
    ],
  };

  var Ditty_Slider = function (elmt, options) {
    this.elmt = elmt;
    this.settings = $.extend({}, defaults, $.ditty_slider.defaults, options);
    this.slide = this.settings.slide;
    this.slideObj = {};
    this.total = this.settings.slides.length;
    this.$elmt = $(elmt);
    this.$contents = null;
    this.$slides = null;
    this.$bullets = null;
    this.$arrows = null;
    this.$currentSlide = null;
    this.$lastSlide = null;
    this.transitioning = false;
    this.hovering = false;
    this.timer = null;
    this.currentHeight = this.settings.height;
    this.animateTransition = false;
    this.animateHeight = false;
    (this.slidesDisplayed = 0), (this.paused = false);
    this.transitions = [
      "fade",
      "slideLeft",
      "slideRight",
      "slideDown",
      "slideUp",
    ];
    this.eases = [
      "linear",
      "swing",
      "jswing",
      "easeInQuad",
      "easeInCubic",
      "easeInQuart",
      "easeInQuint",
      "easeInSine",
      "easeInExpo",
      "easeInCirc",
      "easeInElastic",
      "easeInBack",
      "easeInBounce",
      "easeOutQuad",
      "easeOutCubic",
      "easeOutQuart",
      "easeOutQuint",
      "easeOutSine",
      "easeOutExpo",
      "easeOutCirc",
      "easeOutElastic",
      "easeOutBack",
      "easeOutBounce",
      "easeInOutQuad",
      "easeInOutCubic",
      "easeInOutQuart",
      "easeInOutQuint",
      "easeInOutSine",
      "easeInOutExpo",
      "easeInOutCirc",
      "easeInOutElastic",
      "easeInOutBack",
      "easeInOutBounce",
    ];

    this._init();
  };

  Ditty_Slider.prototype = {
    _init: function () {
      var self = this,
        $contents,
        $slides;

      // Add classes and data attributes
      this.$elmt.addClass("ditty-slider");

      // Create the slider contents
      $contents = $('<div class="' + 'ditty-slider__contents"></div>');
      this.$contents = $contents;

      // Create the slider slides
      $slides = $('<div class="' + 'ditty-slider__slides"></div>');
      this.$slides = $slides;

      // Set the initial height
      this.$slides.height(this.currentHeight);

      // Add the new elements
      $contents.append($slides);
      this.$elmt.append($contents);

      // Setup styles
      this._styleDisplay();

      // Setup bullets
      this._setupBullets();

      // Setup arrows
      this._setupArrows();

      // Bind mouse over/out events
      $contents.on("mouseenter", { self: this }, this._mouseenter);
      $contents.on("mouseleave", { self: this }, this._mouseleave);

      // Enable touchswipe
      if (this.settings.touchSwipe) {
        delete Hammer.defaults.cssProps.userSelect;
        var hammertime = new Hammer($contents[0]);
        hammertime.on("swipe", function (e) {
          switch (e.direction) {
            case 2:
              self._swipeLeft();
              break;
            case 4:
              self._swipeRight();
              break;
            default:
              break;
          }
        });
      }

      // Convert slide elements into slide data
      this._convertSlideElements();

      if (this.settings.shuffle === true) {
        this.shuffle();
      }

      // Preload images
      // for (var i = 0; i < this.total; i++) {
      //   this._preloadSlide(this.settings.slides[i].html);
      // }

      // Trigger the init
      setTimeout(function () {
        // Show the first slide
        if (self.settings.slideId) {
          self.showSlideById(self.settings.slideId, "force");
        } else {
          self._showSlide();
        }
        self.trigger("init");
      }, 1);
    },

    /**
     * Convert slide elements into slide data
     *
     * @since    3.0
     * @return   null
     */
    _convertSlideElements: function () {
      if (this.settings.slidesEl) {
        var self = this,
          slidesElArray = this.$elmt.find(this.settings.slidesEl);

        self.settings.slides = [];
        slidesElArray.each(function (i) {
          var $slide = $(slidesElArray[i]),
            id = $slide.data("slide_id") ? $slide.data("slide_id") : false;

          self.settings.slides.push({
            id: id,
            html: $slide.prop("outerHTML"),
            $elmt: $slide,
            //cache	: cache,
          });

          self.$slides.append($slide);
          $slide.hide();
        });
        self.total = self.settings.slides.length;
      }
    },

    /**
     * Preload images of a slide
     *
     * @since    3.0
     * @return   null
     */
    _preloadSlide: function (slide) {
      var img;
      $(slide)
        .find("img")
        .each(function () {
          var src = $(this).attr("src");
          img = new Image();
          $(img)
            .on("load", function () {})
            .attr("src", src);
        });
    },

    _preloadItem: function ($item, setHeight = false) {
      var self = this,
        img,
        numImages = $item.find("img").length,
        imagesLoaded = 0;

      $item.find("img").each(function () {
        img = new Image();
        img.src = $(this).attr("src");
        var isLoaded = img.complete && img.naturalHeight !== 0;
        if (isLoaded) {
          imagesLoaded++;
          if (numImages === imagesLoaded) {
            if (setHeight) {
              self._animateHeight();
            }
          }
        } else {
          img.onload = function () {
            imagesLoaded++;
            if (numImages === imagesLoaded) {
              if (setHeight) {
                self._animateHeight();
              }
            }
          };
        }
      });
    },

    /**
     * Start the timer
     *
     * @since    3.0
     * @return   null
     */
    _timerStart: function () {
      var self = this;
      if (
        0 === parseInt(this.settings.autoplay) ||
        this.total < 2 ||
        this.timer ||
        this.hovering ||
        this.paused
      ) {
        return false;
      }

      cancelAnimationFrame(this.timer);

      var startTime = Date.now();
      function ditty_sliderLoop() {
        var currTime = Date.now(),
          passedTime = Math.floor((currTime - startTime) / 1000);

        if (passedTime >= self.settings.autoplaySpeed) {
          startTime = currTime;
          self._showSlide(self._getNextSlide());
        }

        self.timer = requestAnimationFrame(ditty_sliderLoop);
      }
      self.timer = requestAnimationFrame(ditty_sliderLoop);
    },

    /**
     * Stop the timer
     *
     * @since    3.0
     * @return   null
     */
    _timerStop: function () {
      if (this.timer) {
        cancelAnimationFrame(this.timer);
        this.timer = null;
      }
    },

    /**
     * Mouse enter event
     *
     * @since    3.0
     * @return   null
     */
    _mouseenter: function (e) {
      var self = e.data.self;
      if (self.settings.autoplay && self.settings.autoplayPause) {
        self.hovering = true;
        self._timerStop();
      }
    },

    /**
     * Mouse leave event
     *
     * @since    3.0
     * @return   null
     */
    _mouseleave: function (e) {
      var self = e.data.self;
      if (self.settings.autoplay && self.settings.autoplayPause) {
        self.hovering = false;
        self._timerStart();
      }
    },

    /**
     * Swipe left event
     *
     * @since    3.0
     * @return   null
     */
    _swipeLeft: function () {
      var self = this;
      switch (this.settings.transition) {
        case "slideRight":
          self._showPrevSlide();
          break;
        default:
          self._showNextSlide();
          break;
      }
    },

    /**
     * Swipe right event
     *
     * @since    3.0
     * @return   null
     */
    _swipeRight: function () {
      var self = this;
      switch (this.settings.transition) {
        case "slideRight":
          self._showNextSlide();
          break;
        default:
          self._showPrevSlide();
          break;
      }
    },

    /**
     * Show a slide
     *
     * @since    3.0
     * @return   null
     */
    _showSlide: function (index, direction) {
      var $slide = null,
        force = false;

      if (
        null !== this.$currentSlide &&
        this.$currentSlide.hasClass("ditty-slide-animating")
      ) {
        return false;
      }

      if (undefined === index) {
        index = this.settings.slide;
        if (index >= this.settings.slides.length) {
          index = this.settings.slides.length - 1;
        }
        force = true;
      }
      if (index >= this.total) {
        index = this.total - 1;
      } else if (index < 0) {
        index = 0;
      }
      if (!force && index === this.settings.slide) {
        return false;
      }
      if (undefined === this.settings.slides[index]) {
        return false;
      }

      var prevIndex = this.settings.slide,
        prevSlide = this.settings.slides[prevIndex];

      // Create and add a new slide
      if (this.settings.slides[index].$elmt) {
        $slide = this.settings.slides[index].$elmt; // Use an existing element
      } else {
        $slide = $(this.settings.slides[index].html); // Or, create and cache a new element
        this.settings.slides[index].$elmt = $slide;
      }

      // Add a the current class
      $slide.addClass("ditty-slider__slide");
      $slide.addClass("ditty-slider__slide--current");
      $slide.addClass("ditty-slider__slide--" + index);

      // Remove any old slides at the same index
      $(
        this.$slides.children("." + "ditty-slider__slide--" + index).not($slide)
      ).remove();

      // Add the custom styles
      this._styleSlide($slide);

      // Add the slide to the slider
      $slide.stop(true).css({
        position: "absolute",
        display: "block",
        opacity: 0,
      });
      this.$slides.append($slide);

      // Initialize the height change
      this.animateTransition = true;
      this.animateHeight = true;
      this._setStaticHeight();

      // After slide update trigger
      var actionParams = [
        index,
        this.settings.slides[index],
        prevIndex,
        prevSlide,
      ];
      this.trigger("before_slide_update", actionParams);

      // Hide the previous slide
      if (null !== this.$currentSlide) {
        this._animateOut(this.$currentSlide, $slide, direction);
      }

      // Set this as the current slide
      this.$currentSlide = $slide;
      this._preloadItem($slide, true);

      this.settings.slide = index;
      this.slideObj = this.settings.slides[index];
      this.visibleSlides = [this.settings.slides[index]];
      if (!$slide.hasClass("ditty-slide-animating")) {
        this._slideResetPosition($slide, direction);
      }
      this._animateIn($slide, actionParams);
      this._animateHeight();
      this._activateBullet();
      this.slidesDisplayed = this.slidesDisplayed + 1;
    },

    /**
     * Animate a slide into view
     *
     * @since    3.0.13
     * @return   null
     */
    _animateIn: function ($slide, actionParams) {
      var self = this,
        transitionSpeed =
          0 === parseInt(this.slidesDisplayed)
            ? this.settings.initTransitionSpeed
            : this.settings.transitionSpeed,
        transitionEase =
          0 === parseInt(this.slidesDisplayed)
            ? this.settings.initTransitionEase
            : this.settings.transitionEase;

      var animateCss = {
        left: 0,
        top: 0,
        opacity: 1,
      };

      if (0 === parseFloat(transitionSpeed)) {
        $slide.show();
        $slide.stop().css(animateCss);
        $slide.css("position", "relative");
        self.transitioning = false;
        self.animateTransition = false;
        self._removeStaticHeight();
        self._timerStart();
        self.trigger("after_slide_update", actionParams);
      } else {
        this.transitioning = true;
        this._timerStop();
        $slide.show();
        $slide.addClass("ditty-slide-animating");
        $slide
          .stop(true)
          .animate(
            animateCss,
            parseFloat(transitionSpeed) * 1000,
            transitionEase,
            function () {
              $slide.removeClass("ditty-slide-animating");
              $slide.css("position", "relative");
              self.transitioning = false;
              self.animateTransition = false;
              self._removeStaticHeight();
              self._timerStart();
              self.trigger("after_slide_update", actionParams);
            }
          );
      }
    },

    /**
     * Animate a slide out of view
     *
     * @since    3.0
     * @return   null
     */
    _animateOut: function ($slide, $nextSlide, direction) {
      this.$lastSlide = $slide;

      var posX = 0,
        posY = 0,
        opacity = 1,
        transition = this.settings.transition;

      if ("reverse" === direction) {
        transition = this._reverseTransition(transition);
      }

      switch (transition) {
        case "fade":
          opacity = 0;
          break;
        case "slideRight":
          posX = this.$slides.outerWidth();
          break;
        case "slideLeft":
          posX = "-" + $slide.outerWidth();
          break;
        case "slideDown":
          posY = $nextSlide.outerHeight();
          break;
        case "slideUp":
          posY = "-" + $slide.outerHeight();
          break;
      }

      $slide.removeClass("ditty-slider__slide--current");
      $slide.css("position", "absolute");
      $slide.css({
        zIndex: 1,
      });

      var animateCss = {
        left: posX + "px",
        top: posY + "px",
        opacity: opacity,
      };

      if (0 === parseFloat(this.settings.transitionSpeed)) {
        $slide.hide();
        $slide.stop().css(animateCss);
      } else {
        $slide.addClass("ditty-slide-animating");
        $slide
          .stop(true)
          .animate(
            animateCss,
            parseFloat(this.settings.transitionSpeed) * 1000,
            this.settings.transitionEase,
            function () {
              $slide.removeClass("ditty-slide-animating");
              $slide.hide();
            }
          );
      }
    },

    /**
     * Figure out and set a slide's reset position
     *
     * @since    3.0
     * @return   null
     */
    _slideResetPosition: function ($slide, direction) {
      var posX = 0,
        posY = 0,
        opacity = 1,
        transition =
          0 === parseInt(this.slidesDisplayed)
            ? this.settings.initTransition
            : this.settings.transition;

      if ("reverse" === direction) {
        transition = this._reverseTransition(transition);
      }

      switch (transition) {
        case "fade":
          opacity = 0;
          break;
        case "slideLeft":
          posX = this.$slides.outerWidth();
          break;
        case "slideRight":
          posX = "-" + $slide.outerWidth();
          break;
        case "slideUp":
          if (null !== this.$lastSlide) {
            posY = this.$lastSlide.outerHeight();
          } else {
            posY = this.$slides.outerHeight();
            if ($slide.outerHeight() > posY) {
              posY = $slide.outerHeight();
            }
          }
          break;
        case "slideDown":
          posY = "-" + $slide.outerHeight();
          break;
      }

      $slide.stop(true).css({
        display: "block",
        opacity: opacity,
        left: posX + "px",
        top: posY + "px",
        zIndex: 9,
      });
    },

    /**
     * Set a static height before starting an animation
     *
     * @since    3.0
     * @return   null
     */
    _setStaticHeight: function () {
      var height = this.$slides.outerHeight();
      this.$slides.stop(true).css("height", height + "px");
    },

    /**
     * Remove the static height after all animations are complete
     *
     * @since    3.0
     * @return   null
     */
    _removeStaticHeight: function () {
      if (!(this.animateTransition || this.animateHeight)) {
        this.$slides.stop(true).css("height", "auto");
      }
    },

    /**
     * Animate the height of the slider
     *
     * @since    3.0.13
     * @return   null
     */
    _animateHeight: function () {
      var self = this,
        height = this.$currentSlide.outerHeight(),
        heightSpeed =
          0 === parseInt(this.slidesDisplayed)
            ? this.settings.initHeightSpeed
            : this.settings.heightSpeed,
        heightEase =
          0 === parseInt(this.slidesDisplayed)
            ? this.settings.initHeightEase
            : this.settings.heightEase;

      this.currentHeight = height;

      var animateCss = {
        height: height + "px",
      };

      if (0 === parseFloat(heightSpeed)) {
        this.$slides.stop().css(animateCss);
        self.animateHeight = false;
        self._removeStaticHeight();
        self.trigger("height_updated");
      } else {
        this.$slides
          .stop(true)
          .animate(
            animateCss,
            parseFloat(heightSpeed) * 1000,
            heightEase,
            function () {
              self.animateHeight = false;
              self._removeStaticHeight();
              self.trigger("height_updated");
            }
          );
      }
    },

    /**
     * Return the opposite of the current transition
     *
     * @since    3.0
     * @return   string		reverseTransition
     */
    _reverseTransition: function (transition) {
      var reverseTransition = transition;

      switch (transition) {
        case "slideLeft":
          reverseTransition = "slideRight";
          break;
        case "slideRight":
          reverseTransition = "slideLeft";
          break;
        case "slideUp":
          reverseTransition = "slideDown";
          break;
        case "slideDown":
          reverseTransition = "slideUp";
          break;
      }
      return reverseTransition;
    },

    /**
     * Get the next slide index
     *
     * @since    3.0
     * @return   int		nextSlide
     */
    _getNextSlide: function () {
      var nextSlide = this.settings.slide + 1;
      if (nextSlide >= this.total) {
        nextSlide = 0;
      }
      return nextSlide;
    },

    /**
     * Show the next slide
     *
     * @since    3.0
     * @return   null
     */
    _showNextSlide: function () {
      this._showSlide(this._getNextSlide());
    },

    /**
     * Get the previous slide index
     *
     * @since    3.0
     * @return   int		prevSlide
     */
    _getPrevSlide: function () {
      var prevSlide = this.settings.slide - 1;
      if (prevSlide < 0) {
        prevSlide = this.total - 1;
      }
      return prevSlide;
    },

    /**
     * Show the previous slide
     *
     * @since    3.0
     * @return   null
     */
    _showPrevSlide: function () {
      this._showSlide(this._getPrevSlide(), "reverse");
    },

    /**
     * Bullet click action
     *
     * @since    3.0
     * @return   null
     */
    _clickBullet: function (e) {
      e.preventDefault();
      var self = e.data.self,
        index = $(e.target).data("index"),
        direction;

      if (index < self.settings.slide) {
        direction = "reverse";
      }

      self._showSlide(index, direction);
    },

    /**
     * Activate the correct bullet for the current slide
     *
     * @since    3.0
     * @return   null
     */
    _activateBullet: function () {
      if ("none" === this.settings.bullets || 2 > parseInt(this.total)) {
        return false;
      }

      var prev = this._getPrevSlide(),
        next = this._getNextSlide();

      this.$bullets
        .find("." + "ditty-slider__bullet")
        .removeClass(
          "ditty-slider__bullet--active " +
            "ditty-slider__bullet--next " +
            "ditty-slider__bullet--prev"
        );
      this.$bullets
        .find(
          "." + 'ditty-slider__bullet[data-index="' + this.settings.slide + '"]'
        )
        .addClass("ditty-slider__bullet--active");
      this.$bullets
        .find("." + 'ditty-slider__bullet[data-index="' + prev + '"]')
        .addClass("ditty-slider__bullet--prev");
      this.$bullets
        .find("." + 'ditty-slider__bullet[data-index="' + next + '"]')
        .addClass("ditty-slider__bullet--next");
      this._styleBullets();
    },

    /**
     * Setup the bullets
     *
     * @since    3.0
     * @return   null
     */
    _setupBullets: function () {
      var self = this;

      if (null === this.$bullets) {
        var $bullets = $('<div class="' + 'ditty-slider__bullets"></div>');
        this.$bullets = $bullets;
        this.$elmt.append($bullets);
      }
      if ("none" === this.settings.bullets || 2 > parseInt(this.total)) {
        this.$bullets.hide();
      } else {
        this.$bullets.empty();
        for (var i = 0; i < this.total; i++) {
          var $bullet = $(
            '<a href="#" class="' +
              'ditty-slider__bullet" data-index="' +
              i +
              '">' +
              this.settings.bullet +
              "</a>"
          );
          $bullet.on("click", { self: this }, self._clickBullet);
          this.$bullets.append($bullet);
        }

        this.$bullets.show();
        this._activateBullet();
      }
    },

    /**
     * Setup the bullets
     *
     * @since    3.0
     * @return   null
     */
    _styleBullets: function () {
      // Reset styles
      this.$bullets.removeAttr("style");

      this.$bullets.find("a").css({
        background: this.settings.bulletsColor,
      });
      this.$bullets
        .find("a.ditty-slider__bullet--active")
        .css({ background: this.settings.bulletsColorActive });

      // Add the custom padding & add to the list
      this.$bullets.css(this.settings.bulletsPadding);
      let spacing = this.settings.bulletsSpacing;
      const numberValue = String(this.settings.bulletsSpacing).match(/\d+/);
      if (String(this.settings.bulletsSpacing) === String(numberValue)) {
        spacing += "px";
      }
      this.$bullets[0].style.gap = spacing;

      switch (this.settings.bulletsPosition) {
        case "topLeft":
          this.$bullets.css({
            order: 0,
            justifyContent: "flex-start",
          });
          break;
        case "topCenter":
          this.$bullets.css({
            order: 0,
            justifyContent: "center",
          });
          break;
        case "topRight":
          this.$bullets.css({
            order: 0,
            justifyContent: "flex-end",
          });
          break;
        case "bottomLeft":
          this.$bullets.css({
            order: 2,
            justifyContent: "flex-start",
          });
          break;
        case "bottomCenter":
          this.$bullets.css({
            order: 2,
            justifyContent: "center",
          });
          break;
        case "bottomRight":
          this.$bullets.css({
            order: 2,
            justifyContent: "flex-end",
          });
          break;
      }
    },

    /**
     * Previous arrow click action
     *
     * @since    3.0
     * @return   null
     */
    _clickPrev: function (e) {
      e.preventDefault();
      var self = e.data.self;
      self._showPrevSlide();
    },

    /**
     * Next arrow click action
     *
     * @since    3.0
     * @return   null
     */
    _clickNext: function (e) {
      e.preventDefault();
      var self = e.data.self;
      self._showNextSlide();
    },

    /**
     * Setup the arrows for the slider
     *
     * @since    3.0
     * @return   null
     */
    _setupArrows: function () {
      if (null === this.$arrows) {
        var $arrows = $('<div class="' + 'ditty-slider__arrows"></div>'),
          $prev = $(
            '<a href="#" class="' +
              'ditty-slider__prev">' +
              this.settings.navPrev +
              "</a>"
          ),
          $next = $(
            '<a href="#" class="' +
              'ditty-slider__next">' +
              this.settings.navNext +
              "</a>"
          );

        $prev.on("click", { self: this }, this._clickPrev);
        $next.on("click", { self: this }, this._clickNext);
        $arrows.append($prev, $next);

        this.$arrows = $arrows;
        this.$elmt.append($arrows);
      }

      // Reset styles
      this.$arrows.removeAttr("style");

      if ("none" === this.settings.arrows || 2 > this.total) {
        this.$arrows.hide();
      } else {
        this.$arrows.css("display", "flex");
      }

      // Add the custom styles & add to the list
      this.$arrows.css(this.settings.arrowsPadding);

      switch (this.settings.arrowsPosition) {
        case "flexStart":
          this.$arrows.css({ alignItems: "flex-start" });
          break;
        case "flexEnd":
          this.$arrows.css({ alignItems: "flex-end" });
          break;
        default:
          this.$arrows.css({ alignItems: "center" });
          break;
      }

      this.$arrows.find("i").css({ color: this.settings.arrowsIconColor });
      this.$arrows.find("a").css({ background: this.settings.arrowsBgColor });

      if (1 === parseInt(this.settings.arrowsStatic)) {
        this.$arrows.addClass("ditty-slider__arrows--static");
      } else {
        this.$arrows.removeClass("ditty-slider__arrows--static");
      }
    },

    /**
     * Style slider and content elements
     *
     * @since    3.0
     * @return   null
     */
    _styleDisplay: function () {
      this.$elmt.css({
        background: this.settings.contentsBgColor,
        borderColor: this.settings.contentsBorderColor,
        borderStyle: this.settings.contentsBorderStyle,
      });
      this.$elmt.css(this.settings.contentsPadding);
      this.$elmt.css(this.settings.contentsBorderRadius);
      this.$elmt.css(this.settings.contentsBorderWidth);
    },

    /**
     * Style the slide
     *
     * @since    3.0
     * @return   null
     */
    _styleSlide: function ($slide) {
      $slide.css({
        background: this.settings.slideBgColor,
        borderColor: this.settings.slideBorderColor,
        borderStyle: this.settings.slideBorderStyle,
      });
      $slide.css(this.settings.slidePadding);
      $slide.css(this.settings.slideBorderRadius);
      $slide.css(this.settings.slideBorderWidth);
    },

    /**
     * Maybe adjust slider
     *
     * @since    3.0
     * @return   null
     */
    updateSlides: function (newSlides) {
      var currentCount = this.settings.slides.length,
        newCount = newSlides.length,
        currentIndex = this.settings.slide,
        newIndex = this.settings.slide;

      // for (var i = 0; i < newSlides.length; i++) {
      //   this._preloadSlide(newSlides[i]);
      // }

      this.settings.slides = newSlides;
      if (currentCount !== newCount) {
        this.total = newSlides.length;
        this._setupBullets();
        this._setupArrows();
      }

      if (currentIndex >= this.total) {
        newIndex = this.total - 1;
      }

      if (currentIndex !== newIndex) {
        this._showSlide(newIndex);
      }
    },

    /**
     * Return option data for the object
     *
     * @since    3.0
     * @return   value
     */
    _getOption: function (key) {
      switch (key) {
        case "elmnt":
          return this;
        case "height":
          return this.$slides.outerHeight();
        case "currentSlide":
          return this.$currentSlide;
        default:
          return this.settings[key];
      }
    },

    /**
     * Set options data for the object
     *
     * @since    3.0
     * @return   null
     */
    _setOption: function (key, value) {
      if (undefined === value) {
        return false;
      }
      if ("slides" !== key) {
        this.settings[key] = value;
      }
      switch (key) {
        case "slides":
          this.updateSlides(value);
          break;
        case "slide":
          this.slide = value;
          break;
        case "autoplay":
          if (1 === parseInt(value)) {
            if (!this.timer) {
              this._timerStart();
            }
          } else {
            this._timerStop();
          }
          break;
        case "bgColor":
        case "padding":
        case "borderColor":
        case "borderStyle":
        case "borderWidth":
        case "borderRadius":
        case "contentsBgColor":
        case "contentsPadding":
        case "contentsBorderColor":
        case "contentsBorderStyle":
        case "contentsBorderWidth":
        case "contentsBorderRadius":
          this.animateHeight = true;
          this._setStaticHeight();
          this._styleDisplay();
          this._animateHeight();
          break;
        case "slideBgColor":
        case "slidePadding":
        case "slideBorderColor":
        case "slideBorderStyle":
        case "slideBorderWidth":
        case "slideBorderRadius":
          this.animateHeight = true;
          this._setStaticHeight();
          this._styleSlide(this.$currentSlide);
          this._animateHeight();
          break;
        case "arrows":
        case "arrowsIconColor":
        case "arrowsBgColor":
        case "arrowsPadding":
        case "arrowsPosition":
        case "arrowsStatic":
          this._setupArrows();
          break;
        case "bullets":
          this._setupBullets();
          break;
        case "bulletsColor":
        case "bulletsColorActive":
        case "bulletsPosition":
        case "bulletsSpacing":
        case "bulletsPadding":
          this._styleBullets();
          break;
        default:
          this.settings[key] = value;
          break;
      }
      this.trigger("update");
    },

    /**
     * Shuffle the slides
     *
     * @since    3.0
     * @return   null
     */
    shuffle: function () {
      var temp, rand;

      for (var i = this.total - 1; i > 0; i--) {
        rand = Math.floor(Math.random() * (i + 1));
        temp = this.settings.slides[i];

        this.settings.slides[i] = this.settings.slides[rand];
        this.settings.slides[rand] = temp;
      }
    },

    /**
     * Hook to start the timer
     *
     * @since    3.0
     * @return   null
     */
    start: function () {
      this._timerStart();
    },

    /**
     * Hook to stop the timer
     *
     * @since    3.0
     * @return   null
     */
    stop: function () {
      this._timerStop();
    },

    /**
     * Hook to pause the slider
     *
     * @since    3.0
     * @return   null
     */
    pause: function () {
      this.paused = true;
    },

    /**
     * Hook to resume the slider
     *
     * @since    3.0
     * @return   null
     */
    resume: function () {
      this.paused = false;
      this._timerStart();
    },

    /**
     * Hook to return the current slide
     *
     * @since    3.0
     * @return   object		$currentSlide
     */
    current: function () {
      return this.$currentSlide;
    },

    /**
     * Hook to show a specific slide by index
     *
     * @since    3.0
     * @return   null
     */
    showSlide: function (index) {
      this._showSlide(index);
    },

    /**
     * Hook to show a specific slide by id
     *
     * @since    3.0
     * @return   null
     */
    showSlideById: function (id, force) {
      var self = this;
      $.each(this.settings.slides, function (index, slide) {
        if (slide.id === id) {
          if ("force" === force) {
            self.settings.slide = index;
            self._showSlide();
          } else {
            self._showSlide(index);
          }
          return false;
        }
      });
    },

    /**
     * Hook to add a slide
     *
     * @since    3.0
     * @return   null
     */
    addSlide: function (slide, index, type) {
      var indexExists = true;
      if (index >= this.total || index < 0) {
        indexExists = false;
      }

      // Replace a slide
      if ("replace" === type && indexExists) {
        // Let other scripts know this slide is being removed
        var toBeRemoved = this.settings.slides[index];
        this.trigger("slide_removed", [toBeRemoved]);

        this.settings.slides.splice(index, 1, slide);

        // Add a slide
      } else {
        if (null === index || "" === index || !indexExists) {
          this.settings.slides.splice(
            parseInt(this.settings.slide) + 1,
            0,
            slide
          );
        } else {
          this.settings.slides.splice(index, 0, slide);
        }
      }

      this.total = this.settings.slides.length;

      // Preload slide assets
      //this._preloadSlide(slide);

      if (1 === this.total) {
        this._showSlide();
      }

      //this._showSlide(0);
      this.trigger("update");
    },

    /**
     * Hook to add or replace a slide by id
     *
     * @since    3.0
     * @return   null
     */
    addSlideById: function (id, html) {
      var newSlide = {
          id: id,
          html: html,
        },
        slideIndex = false;

      $.each(this.settings.slides, function (index, slide) {
        if (String(slide.id) === String(id)) {
          slideIndex = index;
          return;
        }
      });

      if (slideIndex) {
        // Let other scripts know this slide is being removed
        var toBeRemoved = this.settings.slides[slideIndex];
        this.trigger("slide_removed", [toBeRemoved]);

        // Remove cached elements
        if (this.settings.slides[slideIndex].$elmt) {
          this.settings.slides[slideIndex].$elmt.remove();
        }
        this.settings.slides.splice(slideIndex, 1, newSlide);
      } else {
        this.settings.slides.push(newSlide);
      }

      this.total = this.settings.slides.length;
      //this._preloadSlide(newSlide);
      this.trigger("update");
    },

    /**
     * Hook to delete a slide by id
     *
     * @since    3.0
     * @return   null
     */
    deleteSlideById: function (id) {
      var updatedSlides = [];

      $.each(this.settings.slides, function (index, slide) {
        if (String(slide.id) !== String(id)) {
          updatedSlides.push(slide);
        }
      });

      this.settings.slides = updatedSlides;
      this.total = this.settings.slides.length;

      this.trigger("update");
    },

    /**
     * Hook to delete a slide by index
     *
     * @since    3.0
     * @return   null
     */
    deleteSlide: function (index) {
      if (index >= this.total || index < 0) {
        return false;
      }

      // Let other scripts know this slide is being removed
      var toBeRemoved = this.settings.slides[index];
      this.trigger("slide_removed", [toBeRemoved]);

      // Remove cached elements
      //if ( this.settings.slides[index].cache && this.settings.slides[index].$elmt ) {
      if (this.settings.slides[index].$elmt) {
        this.settings.slides[index].$elmt.remove();
      }
      this.settings.slides.splice(index, 1);
      this.total = this.settings.slides.length;

      this.trigger("update");
    },

    /**
     * Hook to set a static height
     *
     * @since    3.0
     * @return   null
     */
    setStaticHeight: function () {
      this.animateHeight = true;
      this._setStaticHeight();
    },

    /**
     * Hook to animate the slider height
     *
     * @since    3.0
     * @return   null
     */
    animateHeight: function () {
      this._animateHeight();
    },

    /**
     * Hook to trigger events
     *
     * @since    3.0
     * @return   null
     */
    trigger: function (fn, customParams) {
      var params = [];

      switch (fn) {
        case "height_updated":
          params = [this.currentHeight];
          break;
        default:
          params = [this.settings, this.$elmt];
          break;
      }

      if (customParams) {
        params = customParams;
      }

      this.$elmt.trigger("ditty_slider_" + fn, params);

      if (typeof this.settings[fn] === "function") {
        this.settings[fn].apply(this.$elmt, params);
      }
    },

    /**
     * Hook to get or set slider options
     *
     * @since    3.0
     * @return   null
     */
    options: function (key, value) {
      var self = this;
      if (typeof key === "object") {
        $.each(key, function (k, v) {
          self._setOption(k, v);
        });
      } else if (typeof key === "string") {
        if (value === undefined) {
          return self._getOption(key);
        }
        self._setOption(key, value);
      } else {
        return self.settings;
      }
    },

    /**
     * Hook to destroy this object
     *
     * @since    3.0
     * @return   null
     */
    destroy: function () {
      clearInterval(this.timer);
      this.$elmt.removeClass("ditty-slider");
      this.$elmt.removeAttr("style");
      this.$elmt.empty();
      this.trigger("destroy");
      this.elmt._ditty_slider = null;
    },
  };

  /**
   * Setup the class or listen for hooks
   *
   * @since    3.0
   * @return   null
   */
  $.fn.ditty_slider = function (options) {
    var args = arguments,
      error = false,
      returns;

    if (options === undefined || typeof options === "object") {
      return this.each(function () {
        if (!this._ditty_slider) {
          this._ditty_slider = new Ditty_Slider(this, options);
        }
      });
    } else if (typeof options === "string") {
      this.each(function () {
        var instance = this._ditty_slider;

        if (!instance) {
          throw new Error("No Ditty_Slider applied to this element.");
        }
        if (typeof instance[options] === "function" && options[0] !== "_") {
          returns = instance[options].apply(instance, [].slice.call(args, 1));
        } else {
          error = true;
        }
      });

      if (error) {
        throw new Error('No method "' + options + '" in Ditty_Slider.');
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_slider = {};
  $.ditty_slider.defaults = defaults;
})(jQuery);

/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/assets/css/admin.scss":
/*!***********************************!*\
  !*** ./src/assets/css/admin.scss ***!
  \***********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
/*!***********************************************!*\
  !*** ./src/admin/class-ditty-ui-data-list.js ***!
  \***********************************************/
/**
 * UI - Data List
 *
 * @since		3.0
 * @return	null
*/

(function ($) {
  'use strict';

  var defaults = {
    listType: 'toggle',
    showAll: true,
    activeFilter: '*',
    filter: '.ditty-data-list__filter',
    filterSelector: 'filter',
    item: '.ditty-data-list__item',
    itemSelector: 'filter'
  };
  var Ditty_UI_Data_List = function (elmt, options) {
    this.elmt = elmt;
    this.settings = $.extend({}, defaults, $.ditty_ui_data_list.defaults, options);
    this.$elmt = $(elmt);
    this.filters = [];
    this._init();
  };
  Ditty_UI_Data_List.prototype = {
    /**
    * Initialize the data list
    *
    * @since		3.0
    * @return	null
    */
    _init: function () {
      var self = this;
      this.$elmt.on('click', this.settings.filter, {
        self: this
      }, this._filterClick);
      this.$elmt.find(this.settings.filter + '.active').each(function () {
        self._filterList($(this).data(self.settings.filterSelector));
      });
      if ('*' !== this.settings.activeFilter) {
        this._filterList(this.settings.activeFilter);
      }

      // Trigger the init
      setTimeout(function () {
        self.trigger('init');
      }, 1);
    },
    /**
    * Select the filter and update the list
    *
    * @since    3.0
    * @return   null
    */
    _filterList: function (filter) {
      var self = this,
        filters = this.filters;
      if ('toggle' === this.settings.listType) {
        if (this.filters.includes(filter)) {
          if (this.settings.showAll) {
            this.filters = [];
            this.$elmt.find(this.settings.filter).removeClass('active');
            this.$elmt.find(this.settings.item).show();
          }
        } else {
          this.filters = [filter];
          this.$elmt.find(this.settings.filter).removeClass('active');
          this.$elmt.find(this.settings.filter + '[data-' + this.settings.filterSelector + '="' + filter + '"]').addClass('active');
          this.$elmt.find(this.settings.item).hide();
          this.$elmt.find(this.settings.item + '[data-' + this.settings.itemSelector + '="' + filter + '"]').show();
        }
      } else if ('filter' === this.settings.listType) {
        this.$elmt.find(this.settings.item).hide();
        if (this.filters.includes(filter)) {
          this.$elmt.find(this.settings.filter + '[data-' + this.settings.filterSelector + '="' + filter + '"]').removeClass('active');
          for (var i = 0; i < this.filters.length; i++) {
            if (self.filters[i] === filter) {
              self.filters.splice(i, 1);
            }
          }
        } else {
          this.$elmt.find(this.settings.filter + '[data-' + this.settings.filterSelector + '="' + filter + '"]').addClass('active');
          this.filters.push(filter);
        }

        // Show the filtered items
        $.each(this.filters, function (index, value) {
          self.$elmt.find(this.settings.item + '[data-' + this.settings.itemSelector + '="' + value + '"]').show();
        });

        // Show all if no filters
        if (0 === this.filters.length) {
          self.$elmt.find(this.settings.item).show();
        }
      }
      if (filters !== this.filters) {
        self.trigger('update');
      }
    },
    /**
    * Filter click listener
    *
    * @since    3.0
    * @return   null
    */
    _filterClick: function (e) {
      e.preventDefault();
      var self = e.data.self,
        $link = $(e.target);
      if (!$link.is('a')) {
        $link = $link.parents('a');
      }
      self._filterList($link.data(self.settings.filterSelector));
    },
    /**
    * Return a specific setting
    *
    * @since    3.0
    * @return   null
    */
    _options: function (key) {
      return this.settings[key];
    },
    /**
     * Setup triggers
     *
     * @since  	3.0
     * @return 	null
    */
    trigger: function (fn, customParams) {
      var params = [this.settings, this.filters];
      if (customParams) {
        params = customParams;
      }
      this.$elmt.trigger('ditty_ui_data_list_' + fn, params);
      if (typeof this.settings[fn] === 'function') {
        this.settings[fn].apply(this.$elmt, params);
      }
    },
    /**
     * Allow settings to be modified
     *
     * @since  	3.0
     * @return 	null
    */
    options: function (key, value) {
      if (typeof key === 'object') {
        this.settings = $.extend({}, defaults, $.ditty_ui_data_list.defaults, key);
      } else if (typeof key === 'string') {
        if (value === undefined) {
          return this.settings[key];
        }
        this.settings[key] = value;
      } else {
        return this.settings;
      }
      this.trigger('options_update');
    },
    /**
     * Destroy the editor
     *
     * @since  	3.0
     * @return 	null
    */
    destroy: function () {
      this.$elmt.off('click', this.settings.filter, {
        self: this
      }, this._filterClick);

      // Trigger a reset notice
      this.trigger('destroy');
      this.elmt._ditty_ui_data_list = null;
    }
  };

  /**
   * Create the data list
   *
   * @since  	3.0
   * @return 	null
  */
  $.fn.ditty_ui_data_list = function (options) {
    var args = arguments,
      error = false,
      returns;
    if (options === undefined || typeof options === 'object') {
      return this.each(function () {
        if (!this._ditty_ui_data_list) {
          this._ditty_ui_data_list = new Ditty_UI_Data_List(this, options);
        }
      });
    } else if (typeof options === 'string') {
      this.each(function () {
        var instance = this._ditty_ui_data_list;
        if (!instance) {
          throw new Error('No Ditty_UI_Data_List applied to this element.');
        }
        if (typeof instance[options] === 'function' && options[0] !== '_') {
          returns = instance[options].apply(instance, [].slice.call(args, 1));
        } else {
          error = true;
        }
      });
      if (error) {
        throw new Error('No method "' + options + '" in Ditty_UI_Data_List.');
      }
      return returns !== undefined ? returns : this;
    }
  };
  $.ditty_ui_data_list = {};
  $.ditty_ui_data_list.defaults = defaults;
})(jQuery);
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
/*!********************************************!*\
  !*** ./src/admin/class-ditty-extension.js ***!
  \********************************************/
/**
 * Ditty Extension class
 *
 * @since		3.0
 * @return	null
 */

(function ($) {
  "use strict";

  var defaults = {};
  var Ditty_Extension = function (elmt, options) {
    this.elmt = elmt;
    this.settings = $.extend({}, defaults, $.ditty_extension.defaults, options);
    this.$elmt = $(elmt);
    this.$panels = $(elmt).find(".ditty-extension__panels");
    this.initPanel = this.$panels.data("init_panel");
    this._init();
  };
  Ditty_Extension.prototype = {
    _init: function () {
      // Add listeners
      this.$elmt.on("click", ".ditty-extension__tab", {
        self: this
      }, this._tabClick);
      this.$elmt.on("click", 'button[name="submit"]', {
        self: this
      }, this._updatePanel);
      this.$elmt.on("click", ".ditty-extension__license__submit", {
        self: this,
        action: "ditty_extension_license_activate"
      }, this._licenseUpdate);
      this.$elmt.on("click", ".ditty-extension__license__refresh", {
        self: this,
        action: "ditty_extension_license_refresh"
      }, this._licenseUpdate);
      this.$elmt.on("click", ".ditty-extension__license__deactivate", {
        self: this,
        action: "ditty_extension_license_deactivate"
      }, this._licenseUpdate);
      this.$panels.on("ditty_slider_before_slide_update", {
        self: this
      }, this._beforePanelUpdate);
      this._initSlider();
    },
    /**
     * Initialize the slider
     *
     * @since    3.0
     * @return   null
     */
    _initSlider: function () {
      var args = {
        transition: "fade",
        transitionSpeed: 0.75,
        heightSpeed: 0.75,
        touchSwipe: false,
        slidesEl: ".ditty-extension__panel",
        slideId: "" !== this.initPanel ? this.initPanel : false
      };
      this.$panels.ditty_slider(args);
    },
    /**
     * Initialize the slider
     *
     * @since    3.0
     * @return   null
     */
    _beforePanelUpdate: function (e, index, slide) {
      var self = e.data.self;
      self.$elmt.find(".ditty-extension__tab").removeClass("active");
      self.$elmt.find('.ditty-extension__tab[data-slide_id="' + slide.id + '"]').addClass("active");
    },
    /**
     * Tab click
     *
     * @since    3.0
     * @return   null
     */
    _tabClick: function (e) {
      e.preventDefault();
      var self = e.data.self;
      var $tab = $(this),
        slideId = $tab.data("slide_id"),
        transition = "slideLeft",
        $currentTab = self.$elmt.find(".ditty-extension__tab.active");
      if ($currentTab === $tab) {
        return false;
      }
      if ($tab.index() < $currentTab.index()) {
        transition = "slideRight";
      }

      //self.$elmt.find( '.ditty-extension__tab' ).removeClass( 'active' );
      //$tab.addClass( 'active' );

      self.$panels.ditty_slider("options", "transition", transition);
      self.$panels.ditty_slider("showSlideById", slideId);
    },
    /**
     * Update inputs after save and sanitize
     *
     * @since    3.0.19
     * @return   null
     */
    _upateInputs: function (updates) {
      $.each(updates, function (inputName, updatedValue) {
        if ($('input[name="' + inputName + '"]').length) {
          $('input[name="' + inputName + '"]').val(updatedValue);
        }
      });
    },
    /**
     * Panel update listener
     *
     * @since    3.0.19
     * @return   null
     */
    _updatePanel: function (e) {
      e.preventDefault();
      var self = e.data.self;
      var $button = $(this),
        $panel = $button.parents(".ditty-extension__panel"),
        $form = $button.parents(".ditty-extension__form"),
        $icon = $button.find("i"),
        iconClass = $icon.attr("class"),
        extension = self.$elmt.data("extension"),
        panel = $panel.data("slide_id");
      if (self.$elmt.hasClass("updating")) {
        return false;
      }
      self.$elmt.addClass("updating");
      $icon.attr("class", dittyAdminVars.updateIcon);
      $form.ajaxSubmit({
        url: dittyAdminVars.ajaxurl,
        type: "post",
        dataType: "json",
        data: {
          action: "ditty_extension_panel_update",
          extension: extension,
          panel: panel,
          security: dittyAdminVars.security
        },
        success: function (data) {
          $icon.attr("class", iconClass);
          self.$elmt.removeClass("updating");
          if (data.input_updates) {
            self._upateInputs(data.input_updates);
          }
          $("#ditty-extensions").trigger("ditty_extension_panel_updated", [data, self.$elmt, $panel]);
        }
      });
    },
    /**
     * Update a license
     *
     * @since    3.0
     * @return   null
     */
    _licenseUpdate: function (e) {
      e.preventDefault();
      var self = e.data.self,
        action = e.data.action;
      var $button = $(this),
        $message = self.$elmt.find(".ditty-extension__license__message"),
        $input = $button.siblings(".ditty-extension__license__input"),
        license = $input.val(),
        extension = $button.data("extension"),
        extensionId = $button.data("extension_id"),
        extensionName = $button.data("extension_name"),
        $icon = $button.children("i");
      if (self.$elmt.hasClass("updating")) {
        return false;
      }
      self.$elmt.addClass("updating");
      $icon.attr("class", dittyAdminVars.updateIcon);

      // Load the new display data
      var data = {
        action: action,
        license: license,
        extension: extension,
        extension_id: extensionId,
        extension_name: extensionName,
        security: dittyAdminVars.security
      };
      $.post(dittyAdminVars.ajaxurl, data, function (response) {
        if (response) {
          self.$elmt.attr("data-license_status", response.status);
          $message.html(response.message);
        }
        if (response.license_key) {
          if ("DELETE" === response.license_key) {
            $input.val("");
          } else {
            $input.val(response.license_key);
          }
        }
        $icon.attr("class", $icon.data("class"));
        self.$elmt.removeClass("updating");
      }, "json").fail(function (jqXHR, textStatus, errorThrown) {
        console.error("AJAX Error:", textStatus, errorThrown, jqXHR.responseText); // ✅ Log errors
      });
    },
    /**
     * Return data for the object
     *
     * @since    3.0
     * @return   null
     */
    _getOption: function (key) {
      switch (key) {
        default:
          return this.settings[key];
      }
    },
    /**
     * Set data for the object
     *
     * @since    3.0
     * @return   null
     */
    _setOption: function (key, value) {
      if (undefined === value) {
        return false;
      }
      this.settings[key] = value;
    },
    /**
     * Get or set ditty options
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
     * Destroy this object
     *
     * @since    3.0
     * @return   null
     */
    destroy: function () {
      // Remove listeners
      this.$elmt.off("click", ".ditty-extension__tab", {
        self: this
      }, this._tabClick);
      this.$elmt.off("click", 'button[name="submit"]', {
        self: this
      }, this._updatePanel);
      this.$elmt.off("click", ".ditty-extension__license__submit, .ditty-extension__license__refresh", {
        self: this,
        action: "ditty_extension_license_activate"
      }, this._licenseUpdate);
      this.$elmt.off("click", ".ditty-extension__license__deactivate", {
        self: this,
        action: "ditty_extension_license_deactivate"
      }, this._licenseUpdate);
      this.$panels.off("ditty_slider_after_slide_update", {
        self: this
      }, this._afterPanelUpdate);
      this.$panels.ditty_slider("destroy");
      this.elmt._ditty_extension = null;
    }
  };
  $.fn.ditty_extension = function (options) {
    var args = arguments,
      error = false,
      returns;
    if (options === undefined || typeof options === "object") {
      return this.each(function () {
        if (!this._ditty_extension) {
          this._ditty_extension = new Ditty_Extension(this, options);
        }
      });
    } else if (typeof options === "string") {
      this.each(function () {
        var instance = this._ditty_extension;
        if (!instance) {
          throw new Error("No Ditty_Extension applied to this element.");
        }
        if (typeof instance[options] === "function" && options[0] !== "_") {
          returns = instance[options].apply(instance, [].slice.call(args, 1));
        } else {
          error = true;
        }
      });
      if (error) {
        throw new Error('No method "' + options + '" in Ditty_Extension.');
      }
      return returns !== undefined ? returns : this;
    }
  };
  $.ditty_extension = {};
  $.ditty_extension.defaults = defaults;
})(jQuery);
})();

// This entry need to be wrapped in an IIFE because it need to be isolated against other entry modules.
(() => {
/*!***************************************!*\
  !*** ./src/admin/ditty-extensions.js ***!
  \***************************************/
jQuery(function ($) {
  // Setup strict mode
  (function () {
    "use strict";

    // Setup protip
    $.protip({
      defaults: {
        position: 'top',
        size: 'small',
        scheme: 'black',
        classes: 'ditty-protip'
      }
    });

    /**
     * Listen for an accordion toggle click
     *
     * @since    3.0
     * @return   null
    */
    // $( '#ditty-extensions' ).on( 'click', '.ditty-accordion__toggle', function( e ) {
    // 	e.preventDefault();
    // 	var $accordion = $( this ).parent(),
    // 			$content = $( this ).next();
    // 			
    // 	if ( $accordion.hasClass( 'active' ) ) {
    // 		$accordion.removeClass( 'active' );
    // 		$content.stop().slideUp( { duration: 750, easing: "easeInOutQuint" } );
    // 	} else {
    // 		$accordion.addClass( 'active' );
    // 		$content.stop().slideDown( { duration: 750, easing: "easeInOutQuint" }, function() {
    // 			$content.css( 'height', 'auto' );
    // 		} );
    // 	}
    // } );
    // 
    /**
    * Initialize the extensions
    *
    * @since    3.0
    * @return   null
    */
    function ditty_extensions_init() {
      $('#ditty-extensions').find('.ditty-extension').each(function (index) {
        var $extension = $(this),
          $panels = $extension.find('.ditty-extension__panels');
        if ($panels.length) {
          $extension.ditty_extension();
        }
        $('#ditty-extensions').trigger('ditty_init_fields');
        setTimeout(function () {
          $extension.addClass('ditty-extension--init');
        }, index * 250);
      });
    }
    ditty_extensions_init();
  })();
});
})();

// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!***************************!*\
  !*** ./src/dittyAdmin.js ***!
  \***************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _assets_css_admin_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./assets/css/admin.scss */ "./src/assets/css/admin.scss");

jQuery(function ($) {
  // Setup strict mode
  (function () {
    "use strict";

    $("#poststuff").trigger("ditty_init_fields");
    $("body").on("click", '.ditty-export-posts input[type="checkbox"]', function (e) {
      var $checkbox = $(e.target),
        $group = $checkbox.parents(".ditty-input--checkboxes__group"),
        $button = $(".ditty-export-button"),
        checkboxes = $group.find('input[type="checkbox"]'),
        isChecked = $checkbox.is(":checked"),
        value = $checkbox.attr("value");
      if ("select_all" === value) {
        checkboxes.each(function () {
          if ($(this)[0] !== $checkbox[0]) {
            $(this).prop("checked", isChecked);
          }
        });
      }

      // Check if any checkboxes are selected
      var enableButton = false;
      checkboxes.each(function () {
        if ($(this).is(":checked")) {
          enableButton = true;
        }
      });
      if (enableButton) {
        $button.attr("disabled", false);
      } else {
        $button.attr("disabled", "disabled");
      }
    });
  })();
});
})();

/******/ })()
;
//# sourceMappingURL=dittyAdmin.js.map
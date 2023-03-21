/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/utils/helpers.js":
/*!******************************!*\
  !*** ./src/utils/helpers.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "convertBoxControlValues": () => (/* binding */ convertBoxControlValues),
/* harmony export */   "easeOptions": () => (/* binding */ easeOptions),
/* harmony export */   "sliderTransitions": () => (/* binding */ sliderTransitions),
/* harmony export */   "updateLayoutCss": () => (/* binding */ updateLayoutCss),
/* harmony export */   "updatedDisplayItems": () => (/* binding */ updatedDisplayItems)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);


/**
 * Return easing options
 * @returns object
 */
const getEaseOptions = () => {
  const eases = ["linear", "swing", "jswing", "easeInQuad", "easeInCubic", "easeInQuart", "easeInQuint", "easeInSine", "easeInExpo", "easeInCirc", "easeInElastic", "easeInBack", "easeInBounce", "easeOutQuad", "easeOutCubic", "easeOutQuart", "easeOutQuint", "easeOutSine", "easeOutExpo", "easeOutCirc", "easeOutElastic", "easeOutBack", "easeOutBounce", "easeInOutQuad", "easeInOutCubic", "easeInOutQuart", "easeInOutQuint", "easeInOutSine", "easeInOutExpo", "easeInOutCirc", "easeInOutElastic", "easeInOutBack", "easeInOutBounce"];
  const easeObject = {};
  for (let i = 0; i < eases.length; i++) {
    easeObject[eases[i]] = eases[i];
  }
  return easeObject;
};
const easeOptions = getEaseOptions();

/**
 * Return the slider transition options
 * @returns object
 */
function getSliderTransitions() {
  return {
    fade: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Fade", "ditty-news-ticker"),
    slideLeft: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Slide Left", "ditty-news-ticker"),
    slideRight: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Slide Right", "ditty-news-ticker"),
    slideDown: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Slide Down", "ditty-news-ticker"),
    slideUp: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Slide Up", "ditty-news-ticker")
  };
}
const sliderTransitions = getSliderTransitions();

/**
 * Convert default box controls to custom control keys
 * @returns object
 */
const convertBoxControlValues = (values, args) => {
  const updatedValues = {};
  for (const [objKey, objValue] of Object.entries(args)) {
    updatedValues[objValue] = values[objKey];
  }
  return updatedValues;
};

/**
 * Convert default box controls to custom control keys
 * @returns object
 */
const updatedDisplayItems = function (prevItems, newItems) {
  let type = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : "replace";
  const prevGroupedItems = prevItems.reduce((items, item) => {
    const index = items.findIndex(i => {
      return i.id === item.id;
    });
    if (index < 0) {
      items.push({
        id: item.id,
        items: [item]
      });
    } else {
      items[index].items.push(item);
    }
    return items;
  }, []);
  const newGroupedItems = newItems.reduce((items, item) => {
    const index = items.findIndex(i => {
      return i.id === item.id;
    });
    item.updated = "updated";
    if (index < 0) {
      items.push({
        id: item.id,
        items: [item]
      });
    } else {
      items[index].items.push(item);
    }
    return items;
  }, []);
  let flattenedItems;
  if ("update" === type) {
    const updatedGroupedItems = newGroupedItems.reduce((groups, newItems) => {
      const index = groups.findIndex(group => {
        return group.id === newItems.id;
      });
      if (index < 0) {
        groups.push(newItems);
      } else {
        groups[index] = newItems;
      }
      return groups;
    }, prevGroupedItems);
    flattenedItems = updatedGroupedItems.reduce((items, group) => {
      return [...items, ...group.items];
    }, []);
  } else {
    flattenedItems = newGroupedItems.reduce((items, group) => {
      return [...items, ...group.items];
    }, []);
  }
  const updatedIndexes = [];
  const updatedItems = flattenedItems.map((item, index) => {
    if (item.updated) {
      updatedIndexes.push(index);
      delete item.updated;
    } else if (typeof prevItems[index] === "undefined") {
      updatedIndexes.push(index);
    } else if (String(prevItems[index].uniq_id) !== String(item.uniq_id)) {
      updatedIndexes.push(index);
    }
    return item;
  });
  return {
    updatedItems: updatedItems,
    updatedIndexes: updatedIndexes
  };
};

/**
 * Update item layout css
 *
 * @since    3.1
 * @return   null
 */
const updateLayoutCss = (layoutCss, layoutId) => {
  let styles = document.getElementById(`ditty-layout--${layoutId}`);
  if (!styles) {
    styles = document.createElement("style");
    styles.setAttribute("id", `ditty-layout--${layoutId}`);
    document.getElementsByTagName("head")[0].appendChild(styles);
  }
  layoutCss = layoutCss.replace("&gt;", ">");
  styles.innerHTML = layoutCss;
};

/***/ }),

/***/ "./src/utils/layoutTags.js":
/*!*********************************!*\
  !*** ./src/utils/layoutTags.js ***!
  \*********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "layoutTagWrapperOptions": () => (/* binding */ layoutTagWrapperOptions),
/* harmony export */   "layoutTags": () => (/* binding */ layoutTags)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);

const dateFormat = "";
const layoutTagWrapperOptions = ["div", "h1", "h2", "h3", "h4", "h5", "h6", "p", "span", "none"];
const layoutTags = {};
layoutTags.author_avatar = {
  tag: "author_avatar",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item's author avatar", "ditty-news-ticker"),
  type: "image",
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    width: "",
    height: "",
    fit: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: ""
  }
};
layoutTags.author_banner = {
  tag: "author_banner",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item's author banner", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    width: "",
    height: "",
    fit: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: ""
  }
};
layoutTags.author_bio = {
  tag: "author_bio",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item's author biography", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: ""
  }
};
layoutTags.author_name = {
  tag: "author_name",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item's author name", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: ""
  }
};
layoutTags.author_screen_name = {
  tag: "author_screen_name",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item's author screen name", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: ""
  }
};
layoutTags.caption = {
  tag: "caption",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item caption.", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    wpautop: "",
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: ""
  }
};
layoutTags.categories = {
  tag: "categories",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item categories", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    link_target: "",
    separator: ", ",
    class: ""
  }
};
layoutTags.content = {
  tag: "content",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("renders the item content.", "ditty-news-ticker"),
  atts: {
    wrapper: {
      type: "select",
      id: "wrapper",
      //name: __("Wrapper", "ditty-news-ticker"),
      options: layoutTagWrapperOptions,
      help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Set the containing element of the rendered content", "ditty-news-ticker"),
      std: "div"
    },
    //wrapper: "div",
    before: "",
    after: "",
    class: ""
  }
};
layoutTags.custom_field = {
  tag: "custom_field",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render a custom field for the item", "ditty-news-ticker"),
  atts: {
    id: "",
    wrapper: "div",
    before: "",
    after: "",
    class: ""
  }
};
layoutTags.excerpt = {
  tag: "excerpt",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item excerpt.", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    wpautop: false,
    before: "",
    after: "",
    excerpt_length: "200",
    more: "...",
    more_link: "post",
    more_link_target: "",
    more_link_rel: "",
    more_before: "",
    more_after: "",
    class: ""
  }
};
layoutTags.icon = {
  tag: "icon",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item icon.", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: ""
  }
};
layoutTags.image = {
  tag: "image",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item image.", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    width: "",
    height: "",
    fit: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: ""
  }
};
layoutTags.image_url = {
  tag: "image_url",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item image url.", "ditty-news-ticker")
};
layoutTags.permalink = {
  tag: "permalink",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item permalink.", "ditty-news-ticker")
};
layoutTags.source = {
  tag: "source",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item source.", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: ""
  }
};
layoutTags.terms = {
  tag: "terms",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item terms", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    before: "",
    after: "",
    term: "",
    link_target: "",
    separator: ", ",
    class: ""
  }
};
layoutTags.time = {
  tag: "time",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item date/time.", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
    ago: "",
    format: dateFormat,
    ago_string: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("%s ago", "ditty-news-ticker"),
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: ""
  }
};
layoutTags.title = {
  tag: "title",
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item title.", "ditty-news-ticker"),
  atts: {
    wrapper: "h3",
    before: "",
    after: "",
    link: "",
    link_target: "",
    link_rel: "",
    link_before: "",
    link_after: "",
    class: ""
  }
};

/***/ }),

/***/ "@wordpress/hooks":
/*!*******************************!*\
  !*** external ["wp","hooks"] ***!
  \*******************************/
/***/ ((module) => {

module.exports = window["wp"]["hooks"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

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
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
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
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!********************************!*\
  !*** ./src/dittyEditorInit.js ***!
  \********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _utils_helpers__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./utils/helpers */ "./src/utils/helpers.js");
/* harmony import */ var _utils_layoutTags__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./utils/layoutTags */ "./src/utils/layoutTags.js");



const editorHooks = (0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__.createHooks)();
dittyEditor.addFilter = (action, callable, priority) => {
  editorHooks.addFilter(action, "dittyEditor", callable, priority);
};
dittyEditor.applyFilters = editorHooks.applyFilters;
dittyEditor.helpers = {
  easeOptions: _utils_helpers__WEBPACK_IMPORTED_MODULE_1__.easeOptions,
  sliderTransitions: _utils_helpers__WEBPACK_IMPORTED_MODULE_1__.sliderTransitions
};
dittyEditor.layoutTags = _utils_layoutTags__WEBPACK_IMPORTED_MODULE_2__.layoutTags;

/**
 * Store registered item types
 */
dittyEditor.itemTypes = [];
dittyEditor.registerItemType = itemType => {
  dittyEditor.itemTypes.push(itemType);
};

/**
 * Store registered display types
 */
dittyEditor.displayTypes = [];
dittyEditor.registerDisplayType = displayType => {
  dittyEditor.displayTypes.push(displayType);
};
})();

/******/ })()
;
//# sourceMappingURL=dittyEditorInit.js.map
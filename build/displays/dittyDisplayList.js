/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/displays/components/dittyDisplay.js":
/*!*************************************************!*\
  !*** ./src/displays/components/dittyDisplay.js ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ DittyDisplay)
/* harmony export */ });
/* harmony import */ var _dittyDisplayStyles_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./dittyDisplayStyles.js */ "./src/displays/components/dittyDisplayStyles.js");

class DittyDisplay {
  constructor(config) {
    const defaults = {
      element: null,
      id: 0,
      cloneItems: "no",
      display: 0,
      heightEase: "easeInOutQuint",
      heightSpeed: 1.5,
      // 1 - 10
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
      titleDisplay: "none",
      titleElement: "h3",
      titleElementPosition: "topLeft",
      titleFontSize: "",
      titleLineHeight: "",
      titleColor: "",
      titleBgColor: "",
      titleMargin: {},
      titlePadding: {},
      titleBorderColor: {},
      titleBorderStyle: {},
      titleBorderWidth: {},
      titleBorderRadius: {},
      itemTextColor: "",
      itemBgColor: "",
      itemBorderColor: {},
      itemBorderStyle: {},
      itemBorderWidth: {},
      itemBorderRadius: {},
      itemPadding: {},
      item: 0,
      items: [],
      shuffle: 0,
      showEditor: 0,
      type: "",
      wrapItems: "no"
    };
    this.config = {
      ...defaults,
      ...config
    };
    this.$ditty = this.config.element;
    this.$ditty.classList.remove("ditty--pre");
    this.$ditty.classList.add(`ditty-${this.config.type}`);
    this.$title = this.$ditty.getElementsByClassName("ditty__title")[0];
    this.$titleContents = this.$ditty.getElementsByClassName("ditty__title__contents")[0];
    this.$titleElement = this.$ditty.getElementsByClassName("ditty__title__element")[0];
    this.$contents = this.$ditty.getElementsByClassName("ditty__contents")[0];
    this.$items = this.$contents.getElementsByClassName("ditty__items")[0];
    this.currentHeight = 0;
    this.firstItemIndex = this.config.item;
    this.nextItem = null;
    this.item = this.config.item;
    this.items = this.$contents.getElementsByClassName("ditty-item");
    this.total = this.items.length;
    this.activeItems = [];
    this.visibleItems = [];
    this.init = false;
    this.itemsInit = false;
    this.paused = false;
  }
  dittyInit() {
    this.init = true;
    this.$ditty.classList.add("ditty--init");
  }
  dittyItemsInit() {
    this.itemsInit = true;
    this.$ditty.classList.add("ditty--init");
  }
  getNextItemIndex(index) {
    let nextItemIndex = parseInt(index) + 1;
    if (nextItemIndex >= parseInt(this.total)) {
      nextItemIndex = 0;
    }
    // Set the next item
    return nextItemIndex;
  }

  /**
   * Add to the visible item list
   * @param {int} index
   * @param {object} $item
   */
  addVisibleItem($item) {
    this.visibleItems.push($item);
    this.setActiveItems();
  }

  /**
   * Remove from the visible item list
   * @param {int} index
   * @param {object} $item
   */
  removeVisibleItem($item) {
    const visibleItems = this.visibleItems.filter($visibleItem => {
      return $visibleItem !== $item;
    });
    this.visibleItems = visibleItems;
    this.setActiveItems();
  }

  /**
   * Set the active items
   */
  setActiveItems() {
    this.activeItems = [];
    this.visibleItems.forEach($item => {
      const itemID = $item.dataset.item_id;
      this.activeItems[itemID] = itemID;
    });
    window.dittyHooks.doAction("dittyActiveItemsUpdate", this.$ditty, this.activeItems);
  }

  /**
   * Check if an item is enabled
   * @param {int} index
   * @returns bool
   */
  itemEnabled(index) {
    if (undefined === this.items[parseInt(index)]) {
      return false;
    }
    if (undefined === this.items[parseInt(index)].dataset.isDisabled) {
      return true;
    } else {
      if (this.items[parseInt(index)].dataset.isDisabled) {
        return false;
      } else {
        return true;
      }
    }
  }

  /**
   * Set an option
   * @param {string} key
   * @param {string} value
   * @returns null
   */
  setOption(key, value) {
    if (undefined === value) {
      return false;
    }
    switch (key) {
      // case "items":
      // 	//this.updateItems(value);
      // 	break;
      // case "direction":
      // 	// this.config[key] = value;
      // 	// this._styleDisplay();
      // 	// this._setDirection(value);
      // 	break;
      case "titleElement":
        this.config[key] = value;
        (0,_dittyDisplayStyles_js__WEBPACK_IMPORTED_MODULE_0__.updateTitleElement)(this.$ditty, this.config, this.config.type);
        break;
      case "titleDisplay":
      case "titleElementPosition":
      case "titleFontSize":
      case "titleLineHeight":
      case "titleColor":
      case "titleBgColor":
      case "titleMargin":
      case "titlePadding":
      case "titleBorderColor":
      case "titleBorderStyle":
      case "titleBorderWidth":
      case "titleBorderRadius":
      case "minHeight":
      case "maxHeight":
      case "bgColor":
      case "padding":
      case "borderColor":
      case "borderStyle":
      case "borderWidth":
      case "borderRadius":
      case "contentsBgColor":
      case "contentsPadding":
      case "contentsBorderRadius":
        this.config[key] = value;
        (0,_dittyDisplayStyles_js__WEBPACK_IMPORTED_MODULE_0__.updateDisplayStyles)(this.config, this.config.display, this.config.type);
        // 	this._setCurrentHeight();
        break;
      default:
        this.config[key] = value;
        break;
    }
  }
  getOption(key) {
    switch (key) {
      case "ditty":
        return this;
      case "type":
        return this.config.type;
      case "display":
        return this.config.display;
      case "items":
        return this.items;
      // case "height":
      // 	return this.currentHeight;
      default:
        return this.config[key];
    }
  }
  options(key, value) {
    if (typeof key === "object") {
      for (const property in key) {
        this.setOption(property, key[property]);
      }
    } else if (typeof key === "string") {
      if (value === undefined) {
        return this.getOption(key);
      }
      this.setOption(key, value);
    } else {
      return this.config;
    }
  }
  destroy() {}
}

/***/ }),

/***/ "./src/displays/components/dittyDisplayStyles.js":
/*!*******************************************************!*\
  !*** ./src/displays/components/dittyDisplayStyles.js ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "updateDisplayStyles": () => (/* binding */ updateDisplayStyles),
/* harmony export */   "updateTitleElement": () => (/* binding */ updateTitleElement)
/* harmony export */ });
/**
 * Update the title element
 * @param {element} $ditty
 * @param {object} settings
 */
function updateTitleElement($ditty, settings, type) {
  const $titleContents = $ditty.getElementsByClassName("ditty__title__contents")[0];
  const $titleElement = $ditty.getElementsByClassName("ditty__title__element")[0];
  const $newTitleElement = document.createElement(settings["titleElement"]);
  $newTitleElement.classList.add("ditty__title__element");
  $newTitleElement.innerHTML = $titleElement.innerHTML;
  $titleElement.remove();
  $titleContents.append($newTitleElement);
}

/**
 * Update the display title styles
 * @param {int} display
 * @param {object} settings
 * @param {string} type
 */
function displayTitleStyles(settings, display, type) {
  let styles = "";
  styles += `.ditty[data-display="${display}"] .ditty__title {`;
  styles += "" != settings["titleBgColor"] ? `background-color:${settings["titleBgColor"]};` : "";
  styles += "" != settings["titleMargin"]["marginTop"] ? `margin-top:${settings["titleMargin"]["marginTop"]};` : "";
  styles += "" != settings["titleMargin"]["marginRight"] ? `margin-right:${settings["titleMargin"]["marginRight"]};` : "";
  styles += "" != settings["titleMargin"]["marginBottom"] ? `margin-bottom:${settings["titleMargin"]["marginBottom"]};` : "";
  styles += "" != settings["titleMargin"]["marginLeft"] ? `margin-left:${settings["titleMargin"]["marginLeft"]};` : "";
  styles += "" != settings["titlePadding"]["paddingTop"] ? `padding-top:${settings["titlePadding"]["paddingTop"]};` : "";
  styles += "" != settings["titlePadding"]["paddingRight"] ? `padding-right:${settings["titlePadding"]["paddingRight"]};` : "";
  styles += "" != settings["titlePadding"]["paddingBottom"] ? `padding-bottom:${settings["titlePadding"]["paddingBottom"]};` : "";
  styles += "" != settings["titlePadding"]["paddingLeft"] ? `padding-left:${settings["titlePadding"]["paddingLeft"]};` : "";
  if ("none" != settings["titleBorderStyle"]) {
    styles += `border-style:${settings["titleBorderStyle"]};`;
    styles += "" != settings["titleBorderColor"] ? `border-color:${settings["titleBorderColor"]};` : "";
    styles += "" != settings["titleBorderWidth"]["borderTopWidth"] ? `border-top-width:${settings["titleBorderWidth"]["borderTopWidth"]};` : "";
    styles += "" != settings["titleBorderWidth"]["borderRightWidth"] ? `border-right-width:${settings["titleBorderWidth"]["borderRightWidth"]};` : "";
    styles += "" != settings["titleBorderWidth"]["borderBottomWidth"] ? `border-bottom-width:${settings["titleBorderWidth"]["borderBottomWidth"]};` : "";
    styles += "" != settings["titleBorderWidth"]["borderLeftWidth"] ? `border-left-width:${settings["titleBorderWidth"]["borderLeftWidth"]};` : "";
  }
  styles += "" != settings["titleBorderRadius"]["borderTopLeftRadius"] ? `border-top-left-radius:${settings["titleBorderRadius"]["borderTopLeftRadius"]};` : "";
  styles += "" != settings["titleBorderRadius"]["borderTopRightRadius"] ? `border-top-right-radius:${settings["titleBorderRadius"]["borderTopRightRadius"]};` : "";
  styles += "" != settings["titleBorderRadius"]["borderBottomLeftRadius"] ? `border-bottom-left-radius:${settings["titleBorderRadius"]["borderBottomLeftRadius"]};` : "";
  styles += "" != settings["titleBorderRadius"]["borderBottomRightRadius"] ? `border-bottom-right-radius:${settings["titleBorderRadius"]["borderBottomRightRadius"]};` : "";
  styles += "}";
  styles += `.ditty[data-display="${display}"] .ditty__title__element {`;
  styles += "" != settings["titleColor"] ? `color:${settings["titleColor"]};` : "";
  styles += "" != settings["titleFontSize"] ? `font-size:${settings["titleFontSize"]};` : "";
  styles += "" != settings["titleLineHeight"] ? `line-height:${settings["titleLineHeight"]};` : "";
  styles += "}";
  return window.dittyHooks.applyFilters("dittyDisplayTitleStyles", styles, settings, display, type);
}

/**
 * Update the display container styles
 * @param {int} display
 * @param {object} settings
 * @param {string} type
 */
function displayContainerStyles(settings, display, type) {
  let styles = "";
  styles += `.ditty[data-display="${display}"] {`;
  styles += "" != settings["maxWidth"] ? `max-width:${settings["maxWidth"]};` : "";
  styles += "" != settings["bgColor"] ? `background-color:${settings["bgColor"]};` : "";
  styles += "" != settings["padding"]["paddingTop"] ? `padding-top:${settings["padding"]["paddingTop"]};` : "";
  styles += "" != settings["padding"]["paddingRight"] ? `padding-right:${settings["padding"]["paddingRight"]};` : "";
  styles += "" != settings["padding"]["paddingBottom"] ? `padding-bottom:${settings["padding"]["paddingBottom"]};` : "";
  styles += "" != settings["padding"]["paddingLeft"] ? `padding-left:${settings["padding"]["paddingLeft"]};` : "";
  styles += "" != settings["margin"]["marginTop"] ? `margin-top:${settings["margin"]["marginTop"]};` : "";
  styles += "" != settings["margin"]["marginRight"] ? `margin-right:${settings["margin"]["marginRight"]};` : "";
  styles += "" != settings["margin"]["marginBottom"] ? `margin-bottom:${settings["margin"]["marginBottom"]};` : "";
  styles += "" != settings["margin"]["marginLeft"] ? `margin-left:${settings["margin"]["marginLeft"]};` : "";
  if ("none" != settings["borderStyle"]) {
    styles += `border-style:${settings["borderStyle"]};`;
    styles += "" != settings["borderColor"] ? `border-color:${settings["borderColor"]};` : "";
    styles += "" != settings["borderWidth"]["borderTopWidth"] ? `border-top-width:${settings["borderWidth"]["borderTopWidth"]};` : "";
    styles += "" != settings["borderWidth"]["borderRightWidth"] ? `border-right-width:${settings["borderWidth"]["borderRightWidth"]};` : "";
    styles += "" != settings["borderWidth"]["borderBottomWidth"] ? `border-bottom-width:${settings["borderWidth"]["borderBottomWidth"]};` : "";
    styles += "" != settings["borderWidth"]["borderLeftWidth"] ? `border-left-width:${settings["borderWidth"]["borderLeftWidth"]};` : "";
  }
  styles += "" != settings["borderRadius"]["borderTopLeftRadius"] ? `border-top-left-radius:${settings["borderRadius"]["borderTopLeftRadius"]};` : "";
  styles += "" != settings["borderRadius"]["borderTopRightRadius"] ? `border-top-right-radius:${settings["borderRadius"]["borderTopRightRadius"]};` : "";
  styles += "" != settings["borderRadius"]["borderBottomLeftRadius"] ? `border-bottom-left-radius:${settings["borderRadius"]["borderBottomLeftRadius"]};` : "";
  styles += "" != settings["borderRadius"]["borderBottomRightRadius"] ? `border-bottom-right-radius:${settings["borderRadius"]["borderBottomRightRadius"]};` : "";
  styles += "}";
  return window.dittyHooks.applyFilters("dittyDisplayContainerStyles", styles, settings, display, type);
}

/**
 * Update the display content styles
 * @param {int} display
 * @param {object} settings
 * @param {string} type
 */
function displayContentStyles(settings, display, type) {
  let styles = "";
  styles += `.ditty[data-display="${display}"] .ditty__contents {`;
  styles += "" != settings["contentsBgColor"] ? `background-color:${settings["contentsBgColor"]};` : "";
  styles += "" != settings["contentsPadding"]["paddingTop"] ? `padding-top:${settings["contentsPadding"]["paddingTop"]};` : "";
  styles += "" != settings["contentsPadding"]["paddingRight"] ? `padding-right:${settings["contentsPadding"]["paddingRight"]};` : "";
  styles += "" != settings["contentsPadding"]["paddingBottom"] ? `padding-bottom:${settings["contentsPadding"]["paddingBottom"]};` : "";
  styles += "" != settings["contentsPadding"]["paddingLeft"] ? `padding-left:${settings["contentsPadding"]["paddingLeft"]};` : "";
  if ("none" != settings["contentsBorderStyle"]) {
    styles += `border-style:${settings["contentsBorderStyle"]};`;
    styles += "" != settings["contentsBorderColor"] ? `border-color:${settings["contentsBorderColor"]};` : "";
    styles += "" != settings["contentsBorderWidth"]["borderTopWidth"] ? `border-top-width:${settings["contentsBorderWidth"]["borderTopWidth"]};` : "";
    styles += "" != settings["contentsBorderWidth"]["borderRightWidth"] ? `border-right-width:${settings["contentsBorderWidth"]["borderRightWidth"]};` : "";
    styles += "" != settings["contentsBorderWidth"]["borderBottomWidth"] ? `border-bottom-width:${settings["contentsBorderWidth"]["borderBottomWidth"]};` : "";
    styles += "" != settings["contentsBorderWidth"]["borderLeftWidth"] ? `border-left-width:${settings["contentsBorderWidth"]["borderLeftWidth"]};` : "";
  }
  styles += "" != settings["contentsBorderRadius"]["borderTopLeftRadius"] ? `border-top-left-radius:${settings["contentsBorderRadius"]["borderTopLeftRadius"]};` : "";
  styles += "" != settings["contentsBorderRadius"]["borderTopRightRadius"] ? `border-top-right-radius:${settings["contentsBorderRadius"]["borderTopRightRadius"]};` : "";
  styles += "" != settings["contentsBorderRadius"]["borderBottomLeftRadius"] ? `border-bottom-left-radius:${settings["contentsBorderRadius"]["borderBottomLeftRadius"]};` : "";
  styles += "" != settings["contentsBorderRadius"]["borderBottomRightRadius"] ? `border-bottom-right-radius:${settings["contentsBorderRadius"]["borderBottomRightRadius"]};` : "";
  styles += "}";
  return window.dittyHooks.applyFilters("dittyDisplayContentStyles", styles, settings, display, type);
}

/**
 * Update the display item styles
 * @param {int} display
 * @param {object} settings
 * @param {string} type
 */
function displayItemStyles(settings, display, type) {
  let styles = "";
  styles += `.ditty[data-display="${display}"] .ditty-item__elements {`;
  styles += "" != settings["itemTextColor"] ? `color:${settings["itemTextColor"]};` : "";
  styles += "" != settings["itemBgColor"] ? `background-color:${settings["itemBgColor"]};` : "";
  styles += "" != settings["itemPadding"]["paddingTop"] ? `padding-top:${settings["itemPadding"]["paddingTop"]};` : "";
  styles += "" != settings["itemPadding"]["paddingRight"] ? `padding-right:${settings["itemPadding"]["paddingRight"]};` : "";
  styles += "" != settings["itemPadding"]["paddingBottom"] ? `padding-bottom:${settings["itemPadding"]["paddingBottom"]};` : "";
  styles += "" != settings["itemPadding"]["paddingLeft"] ? `padding-left:${settings["itemPadding"]["paddingLeft"]};` : "";
  if ("none" != settings["itemBorderStyle"]) {
    styles += `border-style:${settings["itemBorderStyle"]};`;
    styles += "" != settings["itemBorderColor"] ? `border-color:${settings["itemBorderColor"]};` : "";
    styles += "" != settings["itemBorderWidth"]["borderTopWidth"] ? `border-top-width:${settings["itemBorderWidth"]["borderTopWidth"]};` : "";
    styles += "" != settings["itemBorderWidth"]["borderRightWidth"] ? `border-right-width:${settings["itemBorderWidth"]["borderRightWidth"]};` : "";
    styles += "" != settings["itemBorderWidth"]["borderBottomWidth"] ? `border-bottom-width:${settings["itemBorderWidth"]["borderBottomWidth"]};` : "";
    styles += "" != settings["itemBorderWidth"]["borderLeftWidth"] ? `border-left-width:${settings["itemBorderWidth"]["borderLeftWidth"]};` : "";
  }
  styles += "" != settings["itemBorderRadius"]["borderTopLeftRadius"] ? `border-top-left-radius:${settings["itemBorderRadius"]["borderTopLeftRadius"]};` : "";
  styles += "" != settings["itemBorderRadius"]["borderTopRightRadius"] ? `border-top-right-radius:${settings["itemBorderRadius"]["borderTopRightRadius"]};` : "";
  styles += "" != settings["itemBorderRadius"]["borderBottomLeftRadius"] ? `border-bottom-left-radius:${settings["itemBorderRadius"]["borderBottomLeftRadius"]};` : "";
  styles += "" != settings["itemBorderRadius"]["borderBottomRightRadius"] ? `border-bottom-right-radius:${settings["itemBorderRadius"]["borderBottomRightRadius"]};` : "";
  styles += "" != settings["itemMaxWidth"] ? `max-width:${settings["itemMaxWidth"]};` : "";
  styles += "nowrap" == settings["itemElementsWrap"] ? "white-space:nowrap;" : "white-space:normal;";
  styles += "}";
  return window.dittyHooks.applyFilters("dittyDisplayItemStyles", styles, settings, display, type);
}

/**
 * Update the display style element
 * @param {int} display
 * @param {object} settings
 * @param {string} type
 */
function updateDisplayStyles(settings, display, type) {
  const styleEl = document.getElementById(`ditty-display--${display}`);
  let styles = "";
  styles += displayTitleStyles(settings, display, type);
  styles += displayContainerStyles(settings, display, type);
  styles += displayContentStyles(settings, display, type);
  styles += displayItemStyles(settings, display, type);
  styleEl.innerHTML = window.dittyHooks.applyFilters("dittyDisplayStyles", styles, settings, display, type);
}

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
/*!******************************************!*\
  !*** ./src/displays/dittyDisplayList.js ***!
  \******************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ DittyDisplayList)
/* harmony export */ });
/* harmony import */ var _components_dittyDisplay__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/dittyDisplay */ "./src/displays/components/dittyDisplay.js");

class DittyDisplayList extends _components_dittyDisplay__WEBPACK_IMPORTED_MODULE_0__["default"] {
  constructor(config) {
    const defaults = {
      spacing: 30
    };
    super({
      ...defaults,
      ...config
    });
    this.initialize();

    //this.$ditty.addEventListener("mouseenter", () => this.mouseEnter(this));
    //this.$ditty.addEventListener("mouseleave", () => this.mouseLeave(this));

    window.dittyHooks.addFilter("dittyDisplayStyles", "ditty", this.updateDisplayStyles);
  }
  initialize() {}

  /**
   * Stop the ticker on mouse enter
   */
  // mouseEnter(ditty) {
  //   if (ditty.config.hoverPause) {
  //     this.paused = true;
  //     ditty.stopTicker();
  //   }
  // }

  /**
   * Start the ticker on mouse leave
   */
  // mouseLeave(ditty) {
  //   if (ditty.config.hoverPause) {
  //     this.paused = false;
  //     if (this.itemsInit) {
  //       ditty.startTicker();
  //     }
  //   }
  // }

  updateDisplayStyles(styles, settings, display, type) {
    if ("list" !== type) {
      return styles;
    }
    return styles;
  }
}
window.dittyDisplays.list = DittyDisplayList;
})();

/******/ })()
;
//# sourceMappingURL=dittyDisplayList.js.map
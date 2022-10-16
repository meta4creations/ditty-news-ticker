/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/displays/components/dittyDisplay.js":
/*!*************************************************!*\
  !*** ./src/displays/components/dittyDisplay.js ***!
  \*************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ DittyDisplay; }
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
}

/***/ }),

/***/ "./src/displays/components/dittyDisplayStyles.js":
/*!*******************************************************!*\
  !*** ./src/displays/components/dittyDisplayStyles.js ***!
  \*******************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "updateDisplayStyles": function() { return /* binding */ updateDisplayStyles; },
/* harmony export */   "updateTitleElement": function() { return /* binding */ updateTitleElement; }
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

/***/ }),

/***/ "./src/displays/css/dittyDisplayTicker.scss":
/*!**************************************************!*\
  !*** ./src/displays/css/dittyDisplayTicker.scss ***!
  \**************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

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
/******/ 	/* webpack/runtime/define property getters */
/******/ 	!function() {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = function(exports, definition) {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	!function() {
/******/ 		__webpack_require__.o = function(obj, prop) { return Object.prototype.hasOwnProperty.call(obj, prop); }
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!********************************************!*\
  !*** ./src/displays/dittyDisplayTicker.js ***!
  \********************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ DittyDisplayTicker; }
/* harmony export */ });
/* harmony import */ var _components_dittyDisplay__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./components/dittyDisplay */ "./src/displays/components/dittyDisplay.js");
/* harmony import */ var _css_dittyDisplayTicker_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./css/dittyDisplayTicker.scss */ "./src/displays/css/dittyDisplayTicker.scss");


class DittyDisplayTicker extends _components_dittyDisplay__WEBPACK_IMPORTED_MODULE_0__["default"] {
  constructor(config) {
    const defaults = {
      direction: "left",
      hoverPause: 0,
      // 0, 1
      itemElementsWrap: "wrap",
      itemMaxWidth: "",
      minHeight: null,
      maxHeight: null,
      scrollDelay: 2,
      scrollInit: "empty",
      spacing: 20,
      speed: 10 // 1 - 10
    };

    super({
      ...defaults,
      ...config
    });
    this.$ditty.classList.add(`ditty-ticker--${this.config.scrollInit}`);
    this.$firstItem = null;
    this.$lastItem = null;
    this.scrollPercent = 0.13;
    this.loop = null;
    this.xPosition = 0;
    this.yPosition = 0;
    this.heightInit = false;
    this.initialize();
    this.$ditty.addEventListener("mouseenter", () => this.mouseEnter(this));
    this.$ditty.addEventListener("mouseleave", () => this.mouseLeave(this));
    window.dittyHooks.addFilter("dittyDisplayStyles", "ditty", this.updateDisplayStyles);
  }

  // resetTicker() {
  // 	this.xPosition = this.$contents.offsetWidth;
  // 	this.yPosition = 0;

  // 	this.$items.style.transform = `translate(${this.xPosition}px, ${this.yPosition}px)`;
  // 	this.$items.style.opacity = 1;
  // }

  initialize() {
    if ("filled" === this.config.scrollInit) {
      this.fillTicker();
    } else {
      for (let i = 0; i < this.items.length; i++) {
        this.resetItem(this.items[i]);
      }
      if (!this.running) {
        this.dittyInit();
        this.dittyItemsInit();
        this.startTicker();
      }
    }
  }

  /**
   * Fill the ticker with items
   */
  fillTicker() {
    const tickerW = this.$items.offsetWidth;
    const tickerH = this.$items.offsetHeight;
    let index = this.firstItemIndex;
    let posX = 0;
    let posY = 0;
    let filled = false;
    if ("right" === this.config.direction) {
      posX = tickerW;
    } else if ("down" === this.config.direction) {
      posY = tickerH;
    }

    // Position the items
    while (false === filled) {
      const data = this.initializeFilledItem(index, posX, posY, tickerW, tickerH);
      index = data.index;
      posX = data.posX;
      posY = data.posY;
      filled = data.filled;
    }
    this.dittyInit();

    // Display the items
    let counter = 0;
    const fillTimer = setInterval(() => {
      this.visibleItems[counter].classList.add("ditty-item--active");
      counter++;
      if (counter >= this.visibleItems.length) {
        clearInterval(fillTimer);
      }
    }, 100);

    // Start the ticker
    setTimeout(() => {
      this.dittyItemsInit();
      if (!this.paused) {
        this.startTicker();
      }
    }, parseFloat(this.config.scrollDelay) * 1000);
  }

  /**
   * Start the ticker animation loop
   */
  startTicker() {
    cancelAnimationFrame(this.loop);
    this.loop = requestAnimationFrame(() => this.animateTicker());
  }

  /**
   * Stop the ticker animation loop
   */
  stopTicker() {
    cancelAnimationFrame(this.loop);
  }

  /**
   * Run the animation loop
   */
  animateTicker() {
    this.positionItems();
    this.loop = requestAnimationFrame(() => this.animateTicker());
  }

  /**
   * Position items as they scroll
   */
  positionItems() {
    // Initialize the first item
    if (0 === this.visibleItems.length) {
      this.initializeItem(this.firstItemIndex);
    }
    this.visibleItems.forEach(($item, index) => {
      this.positionItem($item);
      if (0 === index && this.itemShouldTerminate($item)) {
        this.terminateItem($item);
      }
    });

    // Check if a new item should start
    if (this.newItemShouldStart()) {
      const nextItemIndex = this.getNextItemIndex(this.item);
      this.initializeItem(nextItemIndex);
    }
  }

  /**
   * Set the position of a single item
   * @param {object} $item
   */
  positionItem($item) {
    let posX = 0;
    let posY = 0;
    const increment = parseFloat(this.config.speed) * this.scrollPercent;
    switch (this.config.direction) {
      case "left":
        posX = parseFloat($item.dataset.posX) - increment;
        break;
      case "right":
        posX = parseFloat($item.dataset.posX) + increment;
        break;
      case "up":
        posY = parseFloat($item.dataset.posY) - increment;
        break;
      case "down":
        posY = parseFloat($item.dataset.posY) + increment;
        break;
    }
    $item.style.transform = `translate(${Number(posX)}px, ${Number(posY)}px)`;
    $item.dataset.posX = posX;
    $item.dataset.posY = posY;
  }

  /**
   * Reset a single item
   * @param {object} $item
   */
  resetItem($item) {
    let posX = 0;
    let posY = 0;
    switch (this.config.direction) {
      case "left":
        posX = this.$items.offsetWidth;
        break;
      case "right":
        posX = `-${$item.offsetWidth}px`;
        break;
      case "up":
        posY = this.$items.offsetHeight;
        break;
      case "down":
        posY = `-${$item.offsetHeight}`;
        break;
    }
    $item.style.display = "block";
    $item.style.transform = `translate(${Number(posX)}px, ${Number(posY)}px)`;
    $item.dataset.posX = posX;
    $item.dataset.posY = posY;
    $item.classList.remove("ditty-item--active");
    $item.classList.remove("ditty-item--first");
    $item.classList.remove("ditty-item--last");
  }

  /**
   *
   * @param {int} index
   * @returns {object} $item
   */
  maybeCloneItem(index) {
    if (undefined === this.items[index]) {
      return false;
    }
    let $item = this.items[index];
    const visibleItem = this.visibleItems.filter($visibleItem => {
      return $visibleItem === $item;
    });
    if ("yes" !== this.config.cloneItems && visibleItem.length) {
      return false;
    }
    if ("yes" !== this.config.cloneItems && "yes" !== this.config.wrapItems && parseInt(this.firstItemIndex) === parseInt(index) && 0 !== this.visibleItems.length) {
      return false;
    }
    if (visibleItem.length) {
      $item = visibleItem[0].cloneNode(true);
      $item.classList.add("ditty-item--clone");
      this.$items.appendChild($item);
    }
    return $item;
  }

  /**
   * Set position classes for the items
   */
  setItemClasses() {
    const itemsWidth = this.$items.offsetWidth;
    const itemsHeight = this.$items.offsetHeight;
    let $firstItem = null;
    let $lastItem = null;
    let firstPosition = 0;
    let lastPosition = 0;
    switch (this.config.direction) {
      case "left":
        firstPosition = itemsWidth;
        lastPosition = 0;
        break;
      case "right":
        firstPosition = 0;
        lastPosition = itemsWidth;
        break;
      case "up":
        firstPosition = itemsHeight;
        lastPosition = 0;
        break;
      case "down":
        firstPosition = 0;
        lastPosition = itemsHeight;
        break;
      default:
        break;
    }
    for (const $item of this.visibleItems) {
      const position = "left" === this.config.direction || "right" === this.config.direction ? Number($item.dataset.posX) : Number($item.dataset.posY);
      switch (this.config.direction) {
        case "left":
        case "up":
          if (position <= firstPosition) {
            firstPosition = position;
            $firstItem = $item;
          }
          if (position >= lastPosition) {
            lastPosition = position;
            $lastItem = $item;
          }
          break;
        case "right":
        case "down":
          if (position >= firstPosition) {
            firstPosition = position;
            $firstItem = $item;
          }
          if (position <= lastPosition) {
            lastPosition = position;
            $lastItem = $item;
          }
          break;
        default:
          break;
      }
    }
    this.visibleItems.forEach($item => {
      if ($firstItem === $item) {
        $item.classList.add("ditty-item--first");
      } else {
        $item.classList.remove("ditty-item--first");
      }
      if ($lastItem === $item) {
        $item.classList.add("ditty-item--last");
      } else {
        $item.classList.remove("ditty-item--last");
      }
    });
    this.$firstItem = $firstItem;
    this.$lastItem = $lastItem;
  }

  /**
   * Initialize an individual item
   * @param {int} index
   * @returns {object} $item
   */
  initializeItem(index) {
    const $item = this.maybeCloneItem(index);
    if (!$item) {
      return false;
    }
    this.resetItem($item);
    $item.classList.add("ditty-item--active");
    this.item = index;
    this.addVisibleItem($item);
    this.setItemClasses();
    this.setCurrentHeight();
    return $item;
  }

  /**
   *
   * @param {int} index
   * @param {object} $item
   * @param {float} posX
   * @param {float} posY
   * @param {float} tickerW
   * @param {float} tickerH
   * @returns {array}
   */
  initializeFilledItem(index, posX, posY, tickerW, tickerH) {
    const $item = this.maybeCloneItem(index);
    if (!$item) {
      return {
        filled: true
      };
    }
    let nextIndex = this.getNextItemIndex(index);
    let nextPosX = false;
    let nextPosY = false;
    let filled = false;
    $item.style.display = "block";
    if ("up" === this.config.direction || "down" === this.config.direction) {
      $item.style.position = "absolute";
      $item.style.top = 0;
      $item.style.left = 0;
    }
    const itemW = $item.offsetWidth;
    const itemH = $item.offsetHeight;
    switch (this.config.direction) {
      case "left":
        nextPosX = posX + itemW + parseInt(this.config.spacing);
        if (nextPosX > tickerW) {
          filled = true;
        }
        break;
      case "right":
        posX = posX - itemW;
        nextPosX = posX - parseInt(this.config.spacing);
        if (nextPosX < 0) {
          filled = true;
        }
        break;
      case "up":
        nextPosY = posY + itemH + parseInt(this.config.spacing);
        if (nextPosY > tickerH) {
          filled = true;
        }
        break;
      case "down":
        posY = posY - itemH;
        nextPosY = posY - parseInt(this.config.spacing);
        if (nextPosY < 0) {
          filled = true;
        }
        break;
    }
    $item.style.transform = `translate(${Number(posX)}px, ${Number(posY)}px)`;
    $item.dataset.posX = posX;
    $item.dataset.posY = posY;
    this.item = index;
    this.addVisibleItem($item);
    this.setCurrentHeight();
    return {
      index: nextIndex,
      posX: nextPosX,
      posY: nextPosY,
      filled: filled
    };
  }

  /**
   * Check to see if a new item should start scrolling
   * @returns bool
   */
  newItemShouldStart() {
    if (0 === this.visibleItems.length) {
      return false;
    }
    const $item = this.visibleItems.at(-1);
    let shouldStart = false;
    switch (this.config.direction) {
      case "left":
        if (parseFloat($item.dataset.posX) <= this.$items.offsetWidth - $item.offsetWidth - this.config.spacing) {
          shouldStart = true;
        }
        break;
      case "right":
        if (parseFloat($item.dataset.posX) >= this.config.spacing) {
          shouldStart = true;
        }
        break;
      case "down":
        if (parseFloat($item.dataset.posY) >= this.config.spacing) {
          shouldStart = true;
        }
        break;
      case "up":
        if (parseFloat($item.dataset.posY) <= this.$items.offsetHeight - $item.offsetHeight - this.config.spacing) {
          shouldStart = true;
        }
        break;
    }
    return shouldStart;
  }

  /**
   * Check if an item should terminate
   * @param {object} $item
   * @returns bool
   */
  itemShouldTerminate($item) {
    let shouldTerminate = false;
    switch (this.config.direction) {
      case "left":
        if (parseFloat($item.dataset.posX) < -$item.offsetWidth) {
          shouldTerminate = true;
        }
        break;
      case "right":
        if (parseFloat($item.dataset.posX) > this.$items.offsetWidth) {
          shouldTerminate = true;
        }
        break;
      case "up":
        if (parseFloat($item.dataset.posY) < -$item.offsetHeight) {
          shouldTerminate = true;
        }
        break;
      case "down":
        if (parseFloat($item.dataset.posY) > this.$items.offsetHeight) {
          shouldTerminate = true;
        }
        break;
    }
    return shouldTerminate;
  }

  /**
   * Terminate the item
   * @param {object} $item
   */
  terminateItem($item) {
    this.removeVisibleItem($item);
    if ($item.classList.contains("ditty-item--clone")) {
      $item.remove();
    } else {
      this.resetItem($item);
    }
    this.setItemClasses();
    this.setCurrentHeight();
  }

  /**
   * Set the height of the ticker
   */
  setCurrentHeight() {
    let height = this.currentHeight;
    if (!this.heightInit && this.$firstItem) {
      height = this.currentHeight = this.$firstItem.offsetHeight;
      this.$items.style.height = height + "px";
      this.heightInit = true;
    }
    if ("up" === this.config.direction || "down" === this.config.direction) {
      height = this.$items.offsetHeight;
      this.$items.style.height = "100%";
    } else {
      height = 0;
      this.visibleItems.forEach($item => {
        let itemHeight = $item.offsetHeight;
        if (itemHeight > height) {
          height = itemHeight;
        }
      });
      if (height !== this.currentHeight) {
        this.currentHeight = height;
        jQuery(this.$items).stop().animate({
          height: height + "px"
        }, Number(this.config.heightSpeed) * 1000, this.config.heightEase);
      }
    }
  }

  /**
   * Stop the ticker on mouse enter
   */
  mouseEnter(ditty) {
    if (ditty.config.hoverPause) {
      this.paused = true;
      ditty.stopTicker();
    }
  }

  /**
   * Start the ticker on mouse leave
   */
  mouseLeave(ditty) {
    if (ditty.config.hoverPause) {
      this.paused = false;
      if (this.itemsInit) {
        ditty.startTicker();
      }
    }
  }
  updateDisplayStyles(styles, settings, display, type) {
    if ("ticker" !== type) {
      return styles;
    }
    if ("up" == settings["direction"] || "down" == settings["direction"]) {
      styles += `.ditty[data-display="${display}"] .ditty__items {`;
      styles += "" != settings["minHeight"] ? `min-height:${settings["minHeight"]};` : "";
      styles += "" != settings["maxHeight"] ? `max-height:${settings["maxHeight"]};` : "";
      styles += "}";
    }
    return styles;
  }
}
window.dittyDisplays.ticker = DittyDisplayTicker;
}();
/******/ })()
;
//# sourceMappingURL=dittyDisplayTicker.js.map
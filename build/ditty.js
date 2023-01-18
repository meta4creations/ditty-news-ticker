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
  destroy() {
    console.log("destroy");
  }
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
  console.log("display", display);
  console.log("type", type);
  let styles = "";
  styles += displayTitleStyles(settings, display, type);
  styles += displayContainerStyles(settings, display, type);
  styles += displayContentStyles(settings, display, type);
  styles += displayItemStyles(settings, display, type);
  styleEl.innerHTML = window.dittyHooks.applyFilters("dittyDisplayStyles", styles, settings, display, type);
}

/***/ }),

/***/ "./src/editor/utils/helpers.js":
/*!*************************************!*\
  !*** ./src/editor/utils/helpers.js ***!
  \*************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "convertBoxControlValues": () => (/* binding */ convertBoxControlValues),
/* harmony export */   "easeOptions": () => (/* binding */ easeOptions),
/* harmony export */   "sliderTransitions": () => (/* binding */ sliderTransitions)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);


/**
 * Return easing options
 * @returns array
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

/***/ }),

/***/ "./src/editor/utils/layoutTags.js":
/*!****************************************!*\
  !*** ./src/editor/utils/layoutTags.js ***!
  \****************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "layoutTags": () => (/* binding */ layoutTags)
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);

const dateFormat = "";
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
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__.__)("Render the item content.", "ditty-news-ticker"),
  atts: {
    wrapper: "div",
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

/***/ "./src/displays/css/dittyDisplay.scss":
/*!********************************************!*\
  !*** ./src/displays/css/dittyDisplay.scss ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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
/*!**********************!*\
  !*** ./src/ditty.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/hooks */ "@wordpress/hooks");
/* harmony import */ var _wordpress_hooks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _displays_components_dittyDisplay__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./displays/components/dittyDisplay */ "./src/displays/components/dittyDisplay.js");
/* harmony import */ var _displays_css_dittyDisplay_scss__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./displays/css/dittyDisplay.scss */ "./src/displays/css/dittyDisplay.scss");
/* harmony import */ var _editor_utils_helpers__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor/utils/helpers */ "./src/editor/utils/helpers.js");
/* harmony import */ var _editor_utils_layoutTags__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./editor/utils/layoutTags */ "./src/editor/utils/layoutTags.js");






/**
 * Add ditty global variables for reference
 */
window.dittyRenders = new WeakMap();
window.dittyHooks = (0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__.createHooks)();
window.dittyDisplays = {
  display: _displays_components_dittyDisplay__WEBPACK_IMPORTED_MODULE_1__["default"]
};
const editorHooks = (0,_wordpress_hooks__WEBPACK_IMPORTED_MODULE_0__.createHooks)();
dittyEditor.addFilter = (action, callable, priority) => {
  editorHooks.addFilter(action, "dittyEditor", callable, priority);
};
dittyEditor.applyFilters = editorHooks.applyFilters;
dittyEditor.helpers = {
  easeOptions: _editor_utils_helpers__WEBPACK_IMPORTED_MODULE_3__.easeOptions,
  sliderTransitions: _editor_utils_helpers__WEBPACK_IMPORTED_MODULE_3__.sliderTransitions
};
dittyEditor.layoutTags = _editor_utils_layoutTags__WEBPACK_IMPORTED_MODULE_4__.layoutTags;
dittyEditor.registerItemType = itemType => {
  dittyEditor.addFilter("dittyItemTypes", itemTypes => {
    itemTypes.push(itemType);
    return itemTypes;
  });
};
dittyEditor.registerDisplayType = displayType => {
  dittyEditor.addFilter("dittyDisplayTypes", displayTypes => {
    displayTypes.push(displayType);
    return displayTypes;
  });
};

/**
 * Load the Ditty on page load
 */
window.onload = function () {
  document.querySelectorAll(".ditty").forEach(dittyEl => {
    var type = dittyEl.dataset.type;
    if (!window.dittyDisplays[type]) {
      return;
    }
    const settings = dittyEl.dataset.settings ? JSON.parse(dittyEl.dataset.settings) : {};
    const args = {
      element: dittyEl,
      display: dittyEl.dataset.display,
      type: type,
      //items: JSON.parse(dittyEl.dataset.items),
      ...settings
    };
    const ditty = new window.dittyDisplays[type](args);
    window.dittyRenders.set(dittyEl, ditty);
  });
};

/**
 * Sample event to modify a Ditty
 */
document.addEventListener("click", clickHandle);
function clickHandle(e) {
  const el = e.target;
  if (el.closest(".ditty__title")) {
    e.preventDefault();
    const dittyEl = el.closest(".ditty");
    const ditty = window.dittyRenders.get(dittyEl);
    const randomColor = Math.floor(Math.random() * 16777215).toString(16);
    const options = {
      titleBgColor: `#${randomColor}`,
      titleElement: "h1"
    };
    ditty.options(options);
  }
}
})();

/******/ })()
;
//# sourceMappingURL=ditty.js.map
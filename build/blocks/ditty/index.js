/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/blocks/ditty/edit.js":
/*!**********************************!*\
  !*** ./src/blocks/ditty/edit.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ Edit; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./icon */ "./src/blocks/ditty/icon.js");
/* harmony import */ var _editor_scss__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./editor.scss */ "./src/blocks/ditty/editor.scss");








function Edit(_ref) {
  let {
    isSelected,
    setAttributes,
    attributes
  } = _ref;
  const {
    ditty,
    display,
    customID,
    customClasses
  } = attributes;
  const [dittyPosts, setDittyPosts] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);
  const [displayPosts, setDisplayPosts] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)([]);
  const dittyOptions = dittyPosts.map(ditty => {
    return {
      key: ditty.id,
      value: ditty.id,
      label: ditty.title.rendered
    };
  });
  dittyOptions.unshift({
    key: 0,
    value: 0,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("No Ditty Selected", "ditty-news-ticker")
  });
  const displayOptions = displayPosts.map(display => {
    return {
      key: display.id,
      value: display.id,
      label: display.title.rendered
    };
  });
  displayOptions.unshift({
    key: 0,
    value: 0,
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Use Default Display", "ditty-news-ticker")
  });
  const currentDitty = dittyOptions.filter(option => {
    return option.value === ditty;
  });
  const currentDittyLabel = currentDitty[0] ? currentDitty[0].label : "";
  const currentDisplay = displayOptions.filter(option => {
    return option.value === display;
  });
  const currentDisplayLabel = currentDisplay[0] ? currentDisplay[0].label : "";
  const blockClass = "wp-block-metaphorcreations-ditty";
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    async function getDittyPosts() {
      const posts = await _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default()({
        path: "/wp/v2/ditty"
      });
      setDittyPosts(posts);
    }

    async function getDisplayPosts() {
      const posts = await _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default()({
        path: "/wp/v2/ditty_display"
      });
      setDisplayPosts(posts);
    }

    getDittyPosts();
    getDisplayPosts();
  }, []);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", (0,_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.useBlockProps)(), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_2__.InspectorControls, {
    key: "dittySelectTicker"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.PanelBody, null, dittyOptions ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Ditty", "ditty-news-ticker"),
    value: ditty,
    options: dittyOptions,
    onChange: ditty => setAttributes({
      ditty: Number(ditty)
    })
  }) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Spinner, null), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Loading Tickers", "ditty-news-ticker")), displayOptions ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Display", "ditty-news-ticker"),
    value: display,
    options: displayOptions,
    onChange: display => setAttributes({
      display: Number(display)
    })
  }) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Spinner, null), (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Loading Displays", "ditty-news-ticker")), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Custom ID", "ditty-news-ticker"),
    value: customID,
    onChange: customID => setAttributes({
      customID
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Custom Classes", "ditty-news-ticker"),
    value: customClasses,
    onChange: customClasses => setAttributes({
      customClasses
    })
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: `${blockClass}__contents`
  }, _icon__WEBPACK_IMPORTED_MODULE_5__["default"].logoBlack, !isSelected && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: `${blockClass}__info`
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: `${blockClass}__vals`
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("ID:", "ditty-news-ticker"), " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("strong", null, currentDittyLabel)), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: `${blockClass}__vals`
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Display:", "ditty-news-ticker"), " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("strong", null, currentDisplayLabel))), isSelected && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: `${blockClass}__controls`
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("ID:", "ditty-news-ticker"),
    labelPosition: "side",
    value: ditty,
    options: dittyOptions,
    onChange: ditty => setAttributes({
      ditty: Number(ditty)
    })
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Display:", "ditty-news-ticker"),
    labelPosition: "side",
    value: display,
    options: displayOptions,
    onChange: display => setAttributes({
      display: Number(display)
    })
  }))));
}

/***/ }),

/***/ "./src/blocks/ditty/icon.js":
/*!**********************************!*\
  !*** ./src/blocks/ditty/icon.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

const icons = {};
icons.iconBlack = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  className: "ditty-logo ditty-icon--black",
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 69.8 71.1"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M0 46.4c0-17.2 8.6-29.1 24.6-29.1a19.93 19.93 0 0 1 6.6 1V0H45v59.2l1 10.3H34.2l-.9-5.2h-.5a15.21 15.21 0 0 1-13 6.8C3.8 71.1 0 58.4 0 46.4Zm31.2 7.4V28.6a13.7 13.7 0 0 0-6-1.3c-8.7 0-11.3 8.7-11.3 17.8 0 8.5 1.9 15.8 8.9 15.8 5.1 0 8.4-3.8 8.4-7.1ZM54.7 63.7a7 7 0 0 1 7.4-7.2c5 0 7.7 2.8 7.7 7.1s-2.6 7.5-7.4 7.5c-5.1 0-7.7-3.1-7.7-7.4Z"
}));
icons.iconWhite = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  className: "ditty-logo ditty-icon--white",
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 69.8 71.1"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M0 46.4c0-17.2 8.6-29.1 24.6-29.1a19.93 19.93 0 0 1 6.6 1V0H45v59.2l1 10.3H34.2l-.9-5.2h-.5a15.21 15.21 0 0 1-13 6.8C3.8 71.1 0 58.4 0 46.4Zm31.2 7.4V28.6a13.7 13.7 0 0 0-6-1.3c-8.7 0-11.3 8.7-11.3 17.8 0 8.5 1.9 15.8 8.9 15.8 5.1 0 8.4-3.8 8.4-7.1ZM54.7 63.7a7 7 0 0 1 7.4-7.2c5 0 7.7 2.8 7.7 7.1s-2.6 7.5-7.4 7.5c-5.1 0-7.7-3.1-7.7-7.4Z"
}));
icons.iconGreen = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  className: "ditty-logo ditty-icon--green",
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 69.8 71.1"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M0 46.4c0-17.2 8.6-29.1 24.6-29.1a19.93 19.93 0 0 1 6.6 1V0H45v59.2l1 10.3H34.2l-.9-5.2h-.5a15.21 15.21 0 0 1-13 6.8C3.8 71.1 0 58.4 0 46.4Zm31.2 7.4V28.6a13.7 13.7 0 0 0-6-1.3c-8.7 0-11.3 8.7-11.3 17.8 0 8.5 1.9 15.8 8.9 15.8 5.1 0 8.4-3.8 8.4-7.1ZM54.7 63.7a7 7 0 0 1 7.4-7.2c5 0 7.7 2.8 7.7 7.1s-2.6 7.5-7.4 7.5c-5.1 0-7.7-3.1-7.7-7.4Z"
}));
icons.logoBlack = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  className: "ditty-logo ditty-logo--black",
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 258.8 99.21"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M0 49.5c0-17.2 8.6-29.1 24.6-29.1a19.93 19.93 0 0 1 6.6 1V3.1H45v59.2l1 10.3H34.2l-.9-5.2h-.5a15.21 15.21 0 0 1-13 6.8C3.8 74.2 0 61.5 0 49.5Zm31.2 7.4V31.7a13.7 13.7 0 0 0-6-1.3c-8.7 0-11.3 8.7-11.3 17.8 0 8.5 1.9 15.8 8.9 15.8 5.1 0 8.4-3.8 8.4-7.1ZM55.7 7.4A7.33 7.33 0 0 1 63.4 0c4.6 0 7.8 3.3 7.8 7.4s-3.2 7.4-7.8 7.4-7.7-3.1-7.7-7.4Zm14.8 14.5v50.7H56.4V21.9ZM95.8 3.1v18.8H112V3.1h14.1v18.8h13v10.9h-13v23.1c0 5.9 2.6 7.6 6.4 7.6a11.9 11.9 0 0 0 6.1-1.9l3.2 9c-3 2-8.2 3.5-13.3 3.5-15.2 0-16.5-8.7-16.5-17.8V32.8H95.8v23.1c0 5.9 2 7.6 5.7 7.6a11.64 11.64 0 0 0 5.7-1.6l2.1 9.4c-2.6 1.7-7.4 2.8-11.1 2.8-15.1 0-16.4-8.7-16.4-17.8V3.1ZM149.6 85.81c0-7.21 4.4-12.81 10.3-17.11-8.4-1.3-13-5.9-13-16V21.9h14v29.7c0 5.4.5 9.1 7 9.1 4 0 7.7-3.2 7.7-8.3V21.9h14v42.3a108.13 108.13 0 0 1-.9 13.9c-1.5 13.5-8.9 21.11-22.4 21.11-11.1 0-16.7-5.21-16.7-13.4Zm26.3-9.11v-9.5c-7.4 3.5-14 8.5-14 16.11 0 3.9 2.2 5.79 6 5.79 5.9 0 8-4.7 8-12.4ZM198.7 66.8a7 7 0 0 1 7.4-7.2c5 0 7.7 2.8 7.7 7.1s-2.6 7.5-7.4 7.5c-5.1 0-7.7-3.1-7.7-7.4ZM221.2 66.8a7 7 0 0 1 7.4-7.2c5 0 7.7 2.8 7.7 7.1s-2.6 7.5-7.4 7.5c-5.1 0-7.7-3.1-7.7-7.4ZM243.7 66.8a7 7 0 0 1 7.4-7.2c5 0 7.7 2.8 7.7 7.1s-2.6 7.5-7.4 7.5c-5.1 0-7.7-3.1-7.7-7.4Z"
}));
icons.logoWhite = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  className: "ditty-logo ditty-logo--white",
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 258.8 99.21"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M0 49.5c0-17.2 8.6-29.1 24.6-29.1a19.93 19.93 0 0 1 6.6 1V3.1H45v59.2l1 10.3H34.2l-.9-5.2h-.5a15.21 15.21 0 0 1-13 6.8C3.8 74.2 0 61.5 0 49.5Zm31.2 7.4V31.7a13.7 13.7 0 0 0-6-1.3c-8.7 0-11.3 8.7-11.3 17.8 0 8.5 1.9 15.8 8.9 15.8 5.1 0 8.4-3.8 8.4-7.1ZM55.7 7.4A7.33 7.33 0 0 1 63.4 0c4.6 0 7.8 3.3 7.8 7.4s-3.2 7.4-7.8 7.4-7.7-3.1-7.7-7.4Zm14.8 14.5v50.7H56.4V21.9ZM95.8 3.1v18.8H112V3.1h14.1v18.8h13v10.9h-13v23.1c0 5.9 2.6 7.6 6.4 7.6a11.9 11.9 0 0 0 6.1-1.9l3.2 9c-3 2-8.2 3.5-13.3 3.5-15.2 0-16.5-8.7-16.5-17.8V32.8H95.8v23.1c0 5.9 2 7.6 5.7 7.6a11.64 11.64 0 0 0 5.7-1.6l2.1 9.4c-2.6 1.7-7.4 2.8-11.1 2.8-15.1 0-16.4-8.7-16.4-17.8V3.1ZM149.6 85.81c0-7.21 4.4-12.81 10.3-17.11-8.4-1.3-13-5.9-13-16V21.9h14v29.7c0 5.4.5 9.1 7 9.1 4 0 7.7-3.2 7.7-8.3V21.9h14v42.3a108.13 108.13 0 0 1-.9 13.9c-1.5 13.5-8.9 21.11-22.4 21.11-11.1 0-16.7-5.21-16.7-13.4Zm26.3-9.11v-9.5c-7.4 3.5-14 8.5-14 16.11 0 3.9 2.2 5.79 6 5.79 5.9 0 8-4.7 8-12.4ZM198.7 66.8a7 7 0 0 1 7.4-7.2c5 0 7.7 2.8 7.7 7.1s-2.6 7.5-7.4 7.5c-5.1 0-7.7-3.1-7.7-7.4ZM221.2 66.8a7 7 0 0 1 7.4-7.2c5 0 7.7 2.8 7.7 7.1s-2.6 7.5-7.4 7.5c-5.1 0-7.7-3.1-7.7-7.4ZM243.7 66.8a7 7 0 0 1 7.4-7.2c5 0 7.7 2.8 7.7 7.1s-2.6 7.5-7.4 7.5c-5.1 0-7.7-3.1-7.7-7.4Z"
}));
icons.logoGreen = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
  className: "ditty-logo ditty-logo--green",
  xmlns: "http://www.w3.org/2000/svg",
  viewBox: "0 0 258.8 99.21"
}, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
  d: "M0 49.5c0-17.2 8.6-29.1 24.6-29.1a19.93 19.93 0 0 1 6.6 1V3.1H45v59.2l1 10.3H34.2l-.9-5.2h-.5a15.21 15.21 0 0 1-13 6.8C3.8 74.2 0 61.5 0 49.5Zm31.2 7.4V31.7a13.7 13.7 0 0 0-6-1.3c-8.7 0-11.3 8.7-11.3 17.8 0 8.5 1.9 15.8 8.9 15.8 5.1 0 8.4-3.8 8.4-7.1ZM55.7 7.4A7.33 7.33 0 0 1 63.4 0c4.6 0 7.8 3.3 7.8 7.4s-3.2 7.4-7.8 7.4-7.7-3.1-7.7-7.4Zm14.8 14.5v50.7H56.4V21.9ZM95.8 3.1v18.8H112V3.1h14.1v18.8h13v10.9h-13v23.1c0 5.9 2.6 7.6 6.4 7.6a11.9 11.9 0 0 0 6.1-1.9l3.2 9c-3 2-8.2 3.5-13.3 3.5-15.2 0-16.5-8.7-16.5-17.8V32.8H95.8v23.1c0 5.9 2 7.6 5.7 7.6a11.64 11.64 0 0 0 5.7-1.6l2.1 9.4c-2.6 1.7-7.4 2.8-11.1 2.8-15.1 0-16.4-8.7-16.4-17.8V3.1ZM149.6 85.81c0-7.21 4.4-12.81 10.3-17.11-8.4-1.3-13-5.9-13-16V21.9h14v29.7c0 5.4.5 9.1 7 9.1 4 0 7.7-3.2 7.7-8.3V21.9h14v42.3a108.13 108.13 0 0 1-.9 13.9c-1.5 13.5-8.9 21.11-22.4 21.11-11.1 0-16.7-5.21-16.7-13.4Zm26.3-9.11v-9.5c-7.4 3.5-14 8.5-14 16.11 0 3.9 2.2 5.79 6 5.79 5.9 0 8-4.7 8-12.4ZM198.7 66.8a7 7 0 0 1 7.4-7.2c5 0 7.7 2.8 7.7 7.1s-2.6 7.5-7.4 7.5c-5.1 0-7.7-3.1-7.7-7.4ZM221.2 66.8a7 7 0 0 1 7.4-7.2c5 0 7.7 2.8 7.7 7.1s-2.6 7.5-7.4 7.5c-5.1 0-7.7-3.1-7.7-7.4ZM243.7 66.8a7 7 0 0 1 7.4-7.2c5 0 7.7 2.8 7.7 7.1s-2.6 7.5-7.4 7.5c-5.1 0-7.7-3.1-7.7-7.4Z"
}));
/* harmony default export */ __webpack_exports__["default"] = (icons);

/***/ }),

/***/ "./src/blocks/ditty/index.js":
/*!***********************************!*\
  !*** ./src/blocks/ditty/index.js ***!
  \***********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/blocks */ "@wordpress/blocks");
/* harmony import */ var _wordpress_blocks__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _edit__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./edit */ "./src/blocks/ditty/edit.js");
/* harmony import */ var _save__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./save */ "./src/blocks/ditty/save.js");
/* harmony import */ var _icon__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./icon */ "./src/blocks/ditty/icon.js");
/* harmony import */ var _style_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./style.scss */ "./src/blocks/ditty/style.scss");





(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.registerBlockType)("metaphorcreations/ditty", {
  version: Date.now(),
  icon: {
    src: _icon__WEBPACK_IMPORTED_MODULE_3__["default"].iconGreen
  },
  transforms: {
    from: [{
      type: "block",
      blocks: ["core/legacy-widget"],
      isMatch: _ref => {
        let {
          idBase,
          instance
        } = _ref;

        if (!(instance !== null && instance !== void 0 && instance.raw)) {
          // Can't transform if raw instance is not shown in REST API.
          return false;
        }

        return idBase === "ditty-widget";
      },
      transform: _ref2 => {
        let {
          instance
        } = _ref2;
        const blocks = [(0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.createBlock)("metaphorcreations/ditty", {
          ditty: instance.raw.ditty,
          display: instance.raw.display
        })];

        if (instance.raw.title) {
          blocks.unshift((0,_wordpress_blocks__WEBPACK_IMPORTED_MODULE_0__.createBlock)("core/heading", {
            content: instance.raw.title
          }));
        }

        return blocks;
      }
    }]
  },
  edit: _edit__WEBPACK_IMPORTED_MODULE_1__["default"],
  save: _save__WEBPACK_IMPORTED_MODULE_2__["default"]
});

/***/ }),

/***/ "./src/blocks/ditty/save.js":
/*!**********************************!*\
  !*** ./src/blocks/ditty/save.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": function() { return /* binding */ save; }
/* harmony export */ });
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_1__);


function save(_ref) {
  let {
    attributes
  } = _ref;
  return null; // const { ditty, display, customID, customClasses } = attributes;
  // let classNames = "ditty dity--pre";
  // if (customClasses) {
  // 	classNames += ` ${customClasses}`;
  // }
  // return (
  // 	<div {...useBlockProps.save()}>
  // 		<div className={classNames} data-id={ditty} data-display={display}>
  // 			This is my ditty...
  // 		</div>
  // 	</div>
  // );
}

/***/ }),

/***/ "./src/blocks/ditty/editor.scss":
/*!**************************************!*\
  !*** ./src/blocks/ditty/editor.scss ***!
  \**************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/blocks/ditty/style.scss":
/*!*************************************!*\
  !*** ./src/blocks/ditty/style.scss ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/***/ (function(module) {

module.exports = window["wp"]["apiFetch"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ (function(module) {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/blocks":
/*!********************************!*\
  !*** external ["wp","blocks"] ***!
  \********************************/
/***/ (function(module) {

module.exports = window["wp"]["blocks"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ (function(module) {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ (function(module) {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ (function(module) {

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
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	!function() {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = function(result, chunkIds, fn, priority) {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var chunkIds = deferred[i][0];
/******/ 				var fn = deferred[i][1];
/******/ 				var priority = deferred[i][2];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every(function(key) { return __webpack_require__.O[key](chunkIds[j]); })) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	}();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	!function() {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = function(module) {
/******/ 			var getter = module && module.__esModule ?
/******/ 				function() { return module['default']; } :
/******/ 				function() { return module; };
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	}();
/******/ 	
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
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	!function() {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"blocks/ditty/index": 0,
/******/ 			"blocks/ditty/style-index": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = function(chunkId) { return installedChunks[chunkId] === 0; };
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = function(parentChunkLoadingFunction, data) {
/******/ 			var chunkIds = data[0];
/******/ 			var moreModules = data[1];
/******/ 			var runtime = data[2];
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some(function(id) { return installedChunks[id] !== 0; })) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunkditty"] = self["webpackChunkditty"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	}();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["blocks/ditty/style-index"], function() { return __webpack_require__("./src/blocks/ditty/index.js"); })
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=index.js.map
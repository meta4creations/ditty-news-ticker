/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/editor/context/index.js":
/*!*************************************!*\
  !*** ./src/editor/context/index.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "EditorConsumer": function() { return /* binding */ EditorConsumer; },
/* harmony export */   "EditorContext": function() { return /* binding */ EditorContext; },
/* harmony export */   "EditorProvider": function() { return /* binding */ EditorProvider; }
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);


const EditorContext = React.createContext();
EditorContext.displayName = "EditorContext";
const EditorProvider = props => {
  const {
    data
  } = props;
  const initialTitle = data.title ? data.title : "";
  const initialItems = data.items ? JSON.parse(data.items) : [];
  const initialDisplay = data.display ? data.display : 0;
  const id = data.id;
  const [title, setTitle] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(initialTitle);
  const [items, setItems] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(initialItems);
  const [currentDisplay, setCurrentDisplay] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(initialDisplay);
  const [displays, setDisplays] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(dittyEditorVars.displays);
  const [layouts, setLayouts] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(dittyEditorVars.layouts);
  const [currentPanel, setCurrentPanel] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)("");

  function handleSetCurrentPanel(panel) {
    setCurrentPanel(panel);
  }

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(EditorContext.Provider, {
    value: {
      id,
      title,
      items,
      displays,
      layouts,
      currentPanel,
      currentDisplay,
      actions: {
        setCurrentPanel: handleSetCurrentPanel
      }
    }
  }, props.children);
};
const EditorConsumer = EditorContext.Consumer;

/***/ }),

/***/ "./src/editor/editor.js":
/*!******************************!*\
  !*** ./src/editor/editor.js ***!
  \******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _tabs__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./tabs */ "./src/editor/tabs.js");
/* harmony import */ var _panels__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./panels */ "./src/editor/panels.js");




/* harmony default export */ __webpack_exports__["default"] = (() => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ditty-editor__contents"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_tabs__WEBPACK_IMPORTED_MODULE_2__["default"], null), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_panels__WEBPACK_IMPORTED_MODULE_3__["default"], null));
});

/***/ }),

/***/ "./src/editor/item.js":
/*!****************************!*\
  !*** ./src/editor/item.js ***!
  \****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);



const Item = _ref => {
  let {
    data,
    renderIcon,
    renderLabel
  } = _ref;
  let elements = [{
    id: "icon",
    content: renderIcon(data)
  }, {
    id: "label",
    content: renderLabel(data)
  }, {
    id: "settings",
    content: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
      className: "fas fa-cog"
    })
  }];
  elements = window.dittyHooks.applyFilters("dittyEditorItemElements", elements);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ditty-editor-item"
  }, elements.map(element => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: `ditty-editor-item__${element.id}`,
      key: element.id
    }, element.content);
  }));
};

/* harmony default export */ __webpack_exports__["default"] = (Item);

/***/ }),

/***/ "./src/editor/panelDisplays.js":
/*!*************************************!*\
  !*** ./src/editor/panelDisplays.js ***!
  \*************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _context__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./context */ "./src/editor/context/index.js");
/* harmony import */ var _item__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./item */ "./src/editor/item.js");






const PanelDisplays = () => {
  const {
    displays
  } = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useContext)(_context__WEBPACK_IMPORTED_MODULE_2__.EditorContext);
  console.log("displays", displays);

  function handleRenderIcon(display) {
    return window.dittyHooks.applyFilters("dittyEditorDisplayIcon", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
      className: "fas fa-tablet-alt"
    }), display);
  }

  function handleRenderLabel(display) {
    return display.label;
  }

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ditty-editor__panel__header"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "ditty-button"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Add Display", "ditty-news-ticker"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ditty-editor__panel__content"
  }, displays.map(display => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_item__WEBPACK_IMPORTED_MODULE_3__["default"], {
      key: display.id,
      data: display,
      renderIcon: handleRenderIcon,
      renderLabel: handleRenderLabel
    });
  })));
};

/* harmony default export */ __webpack_exports__["default"] = (PanelDisplays);

/***/ }),

/***/ "./src/editor/panelItems.js":
/*!**********************************!*\
  !*** ./src/editor/panelItems.js ***!
  \**********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _context__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./context */ "./src/editor/context/index.js");
/* harmony import */ var _item__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./item */ "./src/editor/item.js");






const PanelItems = () => {
  const {
    items
  } = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useContext)(_context__WEBPACK_IMPORTED_MODULE_2__.EditorContext);

  function handleRenderIcon(item) {
    return window.dittyHooks.applyFilters("dittyEditorItemIcon", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
      className: "fas fa-pencil-alt"
    }), item);
  }

  function handleRenderLabel(item) {
    return window.dittyHooks.applyFilters("dittyEditorItemLabel", item.item_type, item);
  }

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ditty-editor__panel__header"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "ditty-button"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Add Item", "ditty-news-ticker"))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ditty-editor__panel__content"
  }, items.map(item => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_item__WEBPACK_IMPORTED_MODULE_3__["default"], {
      key: item.item_id,
      data: item,
      renderIcon: handleRenderIcon,
      renderLabel: handleRenderLabel
    });
  })));
};

/* harmony default export */ __webpack_exports__["default"] = (PanelItems);

/***/ }),

/***/ "./src/editor/panels.js":
/*!******************************!*\
  !*** ./src/editor/panels.js ***!
  \******************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _context__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./context */ "./src/editor/context/index.js");
/* harmony import */ var _panelItems__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./panelItems */ "./src/editor/panelItems.js");
/* harmony import */ var _panelDisplays__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./panelDisplays */ "./src/editor/panelDisplays.js");







const Panels = () => {
  const {
    currentPanel
  } = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useContext)(_context__WEBPACK_IMPORTED_MODULE_2__.EditorContext);
  const panels = [{
    id: "items",
    content: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_panelItems__WEBPACK_IMPORTED_MODULE_3__["default"], null)
  }, {
    id: "display",
    content: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_panelDisplays__WEBPACK_IMPORTED_MODULE_4__["default"], null)
  }, {
    id: "settings",
    content: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", null, "Settings")
  }];

  function renderCurrentPanel() {
    const selectedPanels = panels.filter(panel => panel.id === currentPanel);
    const selectedPanel = selectedPanels.length ? selectedPanels[0] : panels[0];
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: `ditty-editor__panel ditty-editor__panel--${selectedPanel.id}`,
      key: selectedPanel.id
    }, selectedPanel.content);
  }

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ditty-editor__panels"
  }, renderCurrentPanel());
};

/* harmony default export */ __webpack_exports__["default"] = (Panels);

/***/ }),

/***/ "./src/editor/tabs.js":
/*!****************************!*\
  !*** ./src/editor/tabs.js ***!
  \****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _context__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./context */ "./src/editor/context/index.js");





const Tabs = () => {
  const {
    currentPanel,
    actions
  } = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useContext)(_context__WEBPACK_IMPORTED_MODULE_2__.EditorContext);
  const tabs = [{
    id: "items",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Items", "ditty-news-ticker"),
    icon: "fas fa-stream"
  }, {
    id: "display",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Display", "ditty-news-ticker"),
    icon: "fas fa-tablet-alt"
  }, {
    id: "settings",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Settings", "ditty-news-ticker"),
    icon: "fas fa-cog"
  }];
  const selectedTabs = tabs.filter(tab => tab.id === currentPanel);
  const selectedTab = selectedTabs.length ? selectedTabs[0] : tabs[0];

  function renderButtonClass(tab) {
    let className = "ditty-editor__tab";

    if (tab === selectedTab) {
      className += " ditty-editor__tab--active";
    }

    return className;
  }

  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ditty-editor__tabs"
  }, tabs.map(tab => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      className: renderButtonClass(tab),
      key: tab.id,
      onClick: () => actions.setCurrentPanel(tab.id)
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
      className: tab.icon
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, tab.label));
  }));
};

/* harmony default export */ __webpack_exports__["default"] = (Tabs);

/***/ }),

/***/ "./src/editor/css/editor.scss":
/*!************************************!*\
  !*** ./src/editor/css/editor.scss ***!
  \************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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
/************************************************************************/
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
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!****************************!*\
  !*** ./src/dittyEditor.js ***!
  \****************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _editor_context__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./editor/context */ "./src/editor/context/index.js");
/* harmony import */ var _editor_editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./editor/editor */ "./src/editor/editor.js");
/* harmony import */ var _editor_css_editor_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./editor/css/editor.scss */ "./src/editor/css/editor.scss");

const {
  render
} = wp.element; //we are using wp.element here!





if (document.getElementById("ditty-editor")) {
  const $dittyEditor = document.getElementById("ditty-editor");
  render((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_editor_context__WEBPACK_IMPORTED_MODULE_1__.EditorProvider, {
    data: $dittyEditor.dataset
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_editor_editor__WEBPACK_IMPORTED_MODULE_2__["default"], null)), document.getElementById("ditty-editor"));
}
}();
/******/ })()
;
//# sourceMappingURL=dittyEditor.js.map
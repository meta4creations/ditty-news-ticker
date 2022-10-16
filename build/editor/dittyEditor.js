/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/editor/components/Editor.js":
/*!*****************************************!*\
  !*** ./src/editor/components/Editor.js ***!
  \*****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Tabs__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./Tabs */ "./src/editor/components/Tabs.js");
/* harmony import */ var _context__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../context */ "./src/editor/context/index.js");





/* harmony default export */ __webpack_exports__["default"] = (() => {
  const [currentTabId, setCurrentTabId] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)("items");
  const tabs = window.dittyHooks.applyFilters("dittyEditorTabs", [{
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
  }]);
  const handleTabClick = tab => {
    setCurrentTabId(tab.id);
  };
  const renderCurrentPanel = () => {
    return window.dittyHooks.applyFilters("dittyEditorPanel", "", currentTabId, _context__WEBPACK_IMPORTED_MODULE_3__.EditorContext);
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ditty-editor__contents"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Tabs__WEBPACK_IMPORTED_MODULE_2__["default"], {
    tabs: tabs,
    currentTabId: currentTabId,
    tabClick: handleTabClick,
    type: "primary"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ditty-editor__panels"
  }, renderCurrentPanel()), ";");
});

/***/ }),

/***/ "./src/editor/components/Tabs.js":
/*!***************************************!*\
  !*** ./src/editor/components/Tabs.js ***!
  \***************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);


const Tabs = _ref => {
  let {
    tabs,
    type,
    currentTabId,
    tabClick
  } = _ref;
  /**
   * Render the tabs container class name
   * @returns className
   */
  const renderTabsClass = () => {
    let className = "ditty-editor__tabs";
    if (type && "" !== type) {
      className += ` ditty-editor__tabs--${type}`;
    }
    return className;
  };

  /**
   * Render a tabs class name
   * @param {object} tab
   * @returns className
   */
  const renderButtonClass = tab => {
    let className = "ditty-editor__tab";
    if (tab.id === currentTabId) {
      className += " ditty-editor__tab--active";
    }
    return className;
  };

  /**
   * Render a tabs content
   * @param {object} tab
   * @returns className
   */
  const renderButtonContent = tab => {
    return tab.id === currentTabId ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, tab.label) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
      className: tab.icon
    });
  };

  /**
   * Return the tabs
   */
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: renderTabsClass()
  }, tabs.map(tab => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
      className: renderButtonClass(tab),
      key: tab.id,
      onClick: () => tabClick(tab)
    }, renderButtonContent(tab));
  }));
};
/* harmony default export */ __webpack_exports__["default"] = (Tabs);

/***/ }),

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
class EditorProvider extends _wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Component {
  data = this.props.data;
  initialTitle = this.data.title ? this.data.title : "";
  initialItems = this.data.items ? JSON.parse(this.data.items) : [];
  initialDisplay = this.data.display ? this.data.display : 0;
  id = this.data.id;
  state = {
    title: this.initialTitle,
    items: this.initialItems,
    displays: dittyEditorVars.displays,
    layouts: dittyEditorVars.layouts,
    currentDisplay: this.initialDisplay,
    currentPanel: "items"
  };
  handleUpdateItems = updatedItems => {
    const orderedItems = updatedItems.map((item, index) => {
      item.item_index = index.toString();
      return item;
    });
    this.setState({
      items: orderedItems
    });
  };
  handleUpdateItem = updatedItem => {
    const updatedItems = this.state.items.map(item => {
      return updatedItem.item_id === item.item_id ? updatedItem : item;
    });
    this.setState({
      items: updatedItems
    });
  };
  handleSetCurrentPanel = panel => {
    this.setState({
      currentPanel: panel
    });
  };
  render() {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(EditorContext.Provider, {
      value: {
        id: this.id,
        title: this.state.title,
        items: this.state.items,
        displays: this.state.displays,
        layouts: this.state.layouts,
        currentPanel: this.state.currentPanel,
        currentDisplay: this.state.currentDisplay,
        actions: {
          setCurrentPanel: this.handleSetCurrentPanel,
          updateItems: this.handleUpdateItems,
          updateItem: this.handleUpdateItem
        }
      }
    }, this.props.children);
  }
}
const EditorConsumer = EditorContext.Consumer;

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
/*!***********************************!*\
  !*** ./src/editor/dittyEditor.js ***!
  \***********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _context__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./context */ "./src/editor/context/index.js");
/* harmony import */ var _components_Editor__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/Editor */ "./src/editor/components/Editor.js");
/* harmony import */ var _css_editor_scss__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./css/editor.scss */ "./src/editor/css/editor.scss");

const {
  render
} = wp.element; //we are using wp.element here!



if (document.getElementById("ditty-editor")) {
  const $dittyEditor = document.getElementById("ditty-editor");
  render((0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_context__WEBPACK_IMPORTED_MODULE_1__.EditorProvider, {
    data: $dittyEditor.dataset
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_Editor__WEBPACK_IMPORTED_MODULE_2__["default"], null)), document.getElementById("ditty-editor"));
}
}();
/******/ })()
;
//# sourceMappingURL=dittyEditor.js.map
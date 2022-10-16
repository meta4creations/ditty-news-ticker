/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/editor/common/List.js":
/*!***********************************!*\
  !*** ./src/editor/common/List.js ***!
  \***********************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);


const List = _ref => {
  let {
    items
  } = _ref;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ditty-list"
  }, items);
};

/* harmony default export */ __webpack_exports__["default"] = (List);

/***/ }),

/***/ "./src/editor/components/Item.js":
/*!***************************************!*\
  !*** ./src/editor/components/Item.js ***!
  \***************************************/
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
    renderLabel,
    editable,
    onElementClick
  } = _ref;
  let elements = [{
    id: "icon",
    content: renderIcon(data)
  }, {
    id: "label",
    content: renderLabel(data)
  }];
  if (editable) {
    elements.push({
      id: "settings",
      content: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
        className: "fas fa-cog"
      })
    });
  }
  elements = window.dittyHooks.applyFilters("dittyEditorItemElements", elements);
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ditty-editor-item"
  }, elements.map(element => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
      className: `ditty-editor-item__${element.id}`,
      key: element.id,
      onClick: e => {
        onElementClick(e, element.id, data);
      }
    }, element.content);
  }));
};
/* harmony default export */ __webpack_exports__["default"] = (Item);

/***/ }),

/***/ "./src/editor/components/Panel.js":
/*!****************************************!*\
  !*** ./src/editor/components/Panel.js ***!
  \****************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);


const Panel = props => {
  const {
    id,
    header,
    content
  } = props;
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: `ditty-editor__panel ditty-editor__panel--${id}`,
    key: id
  }, header && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ditty-editor__panel__header"
  }, header), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "ditty-editor__panel__content"
  }, content));
};
/* harmony default export */ __webpack_exports__["default"] = (Panel);

/***/ }),

/***/ "./src/editor/components/PanelDisplays.js":
/*!************************************************!*\
  !*** ./src/editor/components/PanelDisplays.js ***!
  \************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _displays_DisplayList__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./displays/DisplayList */ "./src/editor/components/displays/DisplayList.js");
/* harmony import */ var _displays_DisplayEdit__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./displays/DisplayEdit */ "./src/editor/components/displays/DisplayEdit.js");





const PanelDisplays = _ref => {
  let {
    editor
  } = _ref;
  const {
    id,
    displays,
    actions
  } = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useContext)(editor);
  const [currentDisplay, setCurrentDisplay] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(null);
  const handleEditDisplay = display => {
    console.log(display);
    setCurrentDisplay(display);
  };
  const handleGoBack = () => {
    currentDisplay(null);
  };
  return currentDisplay ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_displays_DisplayEdit__WEBPACK_IMPORTED_MODULE_3__["default"], {
    display: currentDisplay,
    goBack: handleGoBack
  }) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_displays_DisplayList__WEBPACK_IMPORTED_MODULE_2__["default"], {
    id: id,
    displays: displays,
    actions: actions,
    editDisplay: handleEditDisplay
  });
};
/* harmony default export */ __webpack_exports__["default"] = (PanelDisplays);

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

/***/ "./src/editor/components/displays/DisplayEdit.js":
/*!*******************************************************!*\
  !*** ./src/editor/components/displays/DisplayEdit.js ***!
  \*******************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Tabs__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../Tabs */ "./src/editor/components/Tabs.js");
/* harmony import */ var _Panel__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../Panel */ "./src/editor/components/Panel.js");





const DisplayEdit = _ref => {
  let {
    display,
    goBack
  } = _ref;
  const [currentTab, setCurrentTab] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)("edit");
  const tabs = window.dittyHooks.applyFilters("dittyDisplaysTabs", [{
    id: "back",
    icon: "",
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)("Go Back", "ditty-news-ticker"),
    content: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", null, "Edit panel")
  }], currentTab);
  const panelHeader = () => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Tabs__WEBPACK_IMPORTED_MODULE_2__["default"], {
      tabs: tabs
    });
  };
  const panelContent = () => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("h1", null, "Display #", display.id);
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Panel__WEBPACK_IMPORTED_MODULE_3__["default"], {
    id: "displayEdit",
    header: panelHeader(),
    content: panelContent()
  });
};
/* harmony default export */ __webpack_exports__["default"] = (DisplayEdit);

/***/ }),

/***/ "./src/editor/components/displays/DisplayList.js":
/*!*******************************************************!*\
  !*** ./src/editor/components/displays/DisplayList.js ***!
  \*******************************************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _Panel__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../Panel */ "./src/editor/components/Panel.js");
/* harmony import */ var _common_List__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../common/List */ "./src/editor/common/List.js");
/* harmony import */ var _Item__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../Item */ "./src/editor/components/Item.js");





const DisplayList = _ref => {
  let {
    id,
    displays,
    actions,
    editItem
  } = _ref;
  /**
   * Render the icon
   */
  const handleRenderIcon = display => {
    return window.dittyHooks.applyFilters("dittyEditorDisplayIcon", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("i", {
      className: "fas fa-tablet-alt"
    }), display);
  };
  const handleRenderLabel = display => {
    return display.label;
  };
  const handleItemClick = (e, item) => {
    console.log("target", e.target);
  };
  const handleElementClick = (e, elementId, item) => {
    console.log("elementId", elementId);
  };
  const renderItems = () => {
    return displays.map((display, index) => {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Item__WEBPACK_IMPORTED_MODULE_4__["default"], {
        key: display.id,
        index: index,
        data: display,
        renderIcon: handleRenderIcon,
        renderLabel: handleRenderLabel,
        onClick: handleItemClick,
        onElementClick: handleElementClick
      });
    });
  };
  const panelContent = () => {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_common_List__WEBPACK_IMPORTED_MODULE_3__["default"], {
      items: renderItems()
    });
  };
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_Panel__WEBPACK_IMPORTED_MODULE_2__["default"], {
    id: "displays",
    content: panelContent()
  });
};
/* harmony default export */ __webpack_exports__["default"] = (DisplayList);

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
/*!*************************************!*\
  !*** ./src/editor/dittyDisplays.js ***!
  \*************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _components_PanelDisplays__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/PanelDisplays */ "./src/editor/components/PanelDisplays.js");




/**
 * Render the Items panel
 */
window.dittyHooks.addFilter("dittyEditorPanel", "dittyEditor", (panel, panelId, context) => {
  if ("display" === panelId) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_components_PanelDisplays__WEBPACK_IMPORTED_MODULE_2__["default"], {
      editor: context
    });
  }
  return panel;
});
}();
/******/ })()
;
//# sourceMappingURL=dittyDisplays.js.map
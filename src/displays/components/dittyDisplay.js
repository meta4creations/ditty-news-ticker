import {
  updateTitleElement,
  updateDisplayStyles,
} from "./dittyDisplayStyles.js";

export default class DittyDisplay {
  constructor(config) {
    const defaults = {
      element: null,
      id: 0,
      cloneItems: "no",
      display: 0,
      heightEase: "easeInOutQuint",
      heightSpeed: 1.5, // 1 - 10
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
      wrapItems: "no",
    };
    this.config = { ...defaults, ...config };

    this.$ditty = this.config.element;
    this.$ditty.classList.remove("ditty--pre");
    this.$ditty.classList.add(`ditty-${this.config.type}`);

    this.$title = this.$ditty.getElementsByClassName("ditty__title")[0];
    this.$titleContents = this.$ditty.getElementsByClassName(
      "ditty__title__contents"
    )[0];
    this.$titleElement = this.$ditty.getElementsByClassName(
      "ditty__title__element"
    )[0];

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
    const visibleItems = this.visibleItems.filter(($visibleItem) => {
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
    this.visibleItems.forEach(($item) => {
      const itemID = $item.dataset.item_id;
      this.activeItems[itemID] = itemID;
    });
    window.dittyHooks.doAction(
      "dittyActiveItemsUpdate",
      this.$ditty,
      this.activeItems
    );
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
        updateTitleElement(this.$ditty, this.config, this.config.type);
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
        updateDisplayStyles(this.config, this.config.display, this.config.type);
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

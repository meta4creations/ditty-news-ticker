class DittyList {
  static defaults = {
    id: 0,
    title: "",
    display: 0,
    status: "",
    order: "default",
    orderby: "desc",
    spacing: 30,
    paging: 0,
    perPage: 0,
    transition: "fade",
    transitionEase: "easeInOutQuint",
    transitionSpeed: 1.5,
    autoplay: 0,
    autoplayPause: 0,
    autoplaySpeed: 8,
    height: 0,
    heightEase: "easeInOutQuint",
    heightSpeed: 1.5,
    arrows: "none",
    arrowsIconColor: "",
    arrowsBgColor: "",
    arrowsPosition: "center",
    arrowsPadding: {},
    arrowsStatic: 0,
    navPrev: '<i class="fas fa-angle-left"></i>',
    navNext: '<i class="fas fa-angle-right"></i>',
    bullets: "none",
    bulletsColor: "",
    bulletsColorActive: "",
    bulletsPosition: "bottomCenter",
    bulletsSpacing: 2,
    bulletsPadding: {},
    bullet: "",
    maxWidth: "",
    bgColor: "",
    padding: {},
    margin: {},
    borderColor: "",
    borderStyle: {},
    borderWidth: {},
    borderRadius: {},
    contentsBgColor: "",
    contentsPadding: {},
    contentsBorderColor: "",
    contentsBorderStyle: {},
    contentsBorderWidth: {},
    contentsBorderRadius: {},
    pageBgColor: "",
    pagePadding: {},
    pageBorderColor: "",
    pageBorderStyle: {},
    pageBorderWidth: {},
    pageBorderRadius: {},
    itemTypography: {},
    itemTextColor: "",
    itemLinkColor: "",
    itemBgColor: "",
    itemBorderColor: "",
    itemBorderStyle: {},
    itemBorderWidth: {},
    itemBorderRadius: {},
    itemPadding: {},
    titleDisplay: "none",
    titleContentsSize: "stretch",
    titleContentsPosition: "start",
    titleElement: "h3",
    titleElementPosition: "start",
    titleElementVerticalPosition: "start",
    titleTypography: {},
    titleMinWidth: "",
    titleMaxWidth: "",
    titleMinHeight: "",
    titleMaxHeight: "",
    titleColor: "",
    titleLinkColor: "",
    titleBgColor: "",
    titleMargin: {},
    titlePadding: {},
    titleBorderColor: "",
    titleBorderStyle: {},
    titleBorderWidth: {},
    titleBorderRadius: {},
    page: 0,
    showEditor: 0,
    items: [],
  };

  constructor(element, options = {}) {
    this.displayType = "list";
    this.element = element;
    this.settings = { ...DittyList.defaults, ...options };
    this.total = this.settings.items.length;
    this.totalPages = 1;
    this.page = this.settings.page;
    this.pages = [];
    this.initItems = [...this.settings.items];
    this.enabledItems = [];
    this.visibleItems = [];
    this.editItem = null;

    this.init();
  }

  init() {
    if (this.total === 0) {
      this.element.style.display = "none";
    }

    this.element.classList.remove("ditty--pre");
    this.element.classList.add("ditty", "ditty-list");
    this.element.dataset.id = this.settings.id;
    this.element.dataset.type = this.displayType;
    this.element.dataset.display = this.settings.display;

    this.contentsElement = document.createElement("div");
    this.contentsElement.classList.add("ditty__contents");
    this.element.appendChild(this.contentsElement);

    this.titleElement = document.createElement("div");
    this.titleElement.classList.add("ditty__title");

    this.titleContentsElement = document.createElement("div");
    this.titleContentsElement.classList.add("ditty__title__contents");
    this.titleElement.appendChild(this.titleContentsElement);

    this.upgrades();
    this.styleDisplay();
    this.styleTitle();
    this.calculatePages();
    this.initSlider();

    if (this.settings.showEditor) {
      // Initialize the editor here
    } else {
      this.trigger("start_live_updates");
    }
  }

  upgrades() {
    if (this.settings.titleFontSize) {
      this.settings.titleTypography.fontSize = this.settings.titleFontSize;
      delete this.settings.titleFontSize;
    }
    if (this.settings.titleLineHeight) {
      this.settings.titleTypography.lineHeight = this.settings.titleLineHeight;
      delete this.settings.titleLineHeight;
    }
  }

  initSlider() {
    const sliderSettings = {};
    Object.keys(this.settings).forEach((key) => {
      const sliderKey = key.replace("page", "slide");
      sliderSettings[sliderKey] = this.settings[key];
    });
    sliderSettings.slides = this.pages;

    // Initialize your slider here using sliderSettings
  }

  destroySlider() {
    // Destroy slider logic
  }

  updateSlider(index) {
    // Update slider logic
  }

  styleDisplay() {
    this.element.style.maxWidth = this.settings.maxWidth;
    this.element.style.background = this.settings.bgColor;
    this.element.style.borderColor = this.settings.borderColor;
    this.element.style.borderStyle = this.settings.borderStyle;
    Object.assign(this.element.style, this.settings.borderRadius);
    Object.assign(this.element.style, this.settings.borderWidth);
    Object.assign(this.element.style, this.settings.margin);
    Object.assign(this.element.style, this.settings.padding);

    // Additional display styling logic
  }

  styleTitle() {
    this.element.dataset.title = this.settings.titleDisplay;

    const titleContentsPosition =
      this.settings.titleContentsPosition || this.settings.titleElementPosition;
    const titleVerticalPosition =
      this.settings.titleElementVerticalPosition ||
      this.settings.titleElementPosition;

    this.element.dataset.title_position = titleContentsPosition;
    this.element.dataset.title_horizontal_position =
      this.settings.titleElementPosition;
    this.element.dataset.title_vertical_position = titleVerticalPosition;

    if (this.settings.titleDisplay === "none") {
      if (this.titleElement.parentNode) {
        this.titleElement.parentNode.removeChild(this.titleElement);
      }
    } else {
      const element = document.createElement(this.settings.titleElement);
      element.classList.add("ditty__title__element");
      element.innerHTML = this.settings.title;

      Object.assign(this.titleContentsElement.style, {
        background: this.settings.titleBgColor,
        borderColor: this.settings.titleBorderColor,
        borderStyle: this.settings.titleBorderStyle,
        width: this.settings.titleContentsSize === "auto" ? "auto" : "100%",
        height: this.settings.titleContentsSize === "auto" ? "auto" : "100%",
        minWidth: this.settings.titleMinWidth,
        maxWidth: this.settings.titleMaxWidth,
        minHeight: this.settings.titleMinHeight,
        maxHeight: this.settings.titleMaxHeight,
      });

      Object.assign(
        this.titleContentsElement.style,
        this.settings.titleBorderRadius,
        this.settings.titleBorderWidth,
        this.settings.titlePadding
      );

      Object.assign(this.titleElement.style, this.settings.titleMargin);

      this.titleContentsElement.appendChild(element);
      this.element.prepend(this.titleElement);
    }
  }

  styleItem(itemElement) {
    const itemElements = itemElement.querySelector(".ditty-item__elements");
    Object.assign(itemElements.style, {
      background: this.settings.itemBgColor,
      borderColor: this.settings.itemBorderColor,
      borderStyle: this.settings.itemBorderStyle,
    });

    Object.assign(itemElements.style, this.settings.itemPadding);
    Object.assign(itemElements.style, this.settings.itemBorderRadius);
    Object.assign(itemElements.style, this.settings.itemBorderWidth);

    itemElement.style.paddingBottom = `${this.settings.spacing}px`;
  }

  createPage(index) {
    const pageElement = document.createElement("div");
    pageElement.classList.add("ditty-list__page", `ditty-list__page--${index}`);

    const items = this.getItemsByPageIndex(index);
    items.forEach((item) => {
      const itemElement = document.createElement("div");
      itemElement.innerHTML = item.html;
      this.styleItem(itemElement);

      // Add the layout CSS to the DOM
      if (item.css) {
        // Apply item CSS
      }
      pageElement.appendChild(itemElement);
    });

    pageElement.lastElementChild.style.paddingBottom = 0;

    return {
      id: `page${index + 1}`,
      html: pageElement,
      items,
    };
  }

  calculatePages() {
    this.enabledItems = this.settings.items.filter((_, index) =>
      this.isItemEnabled(index)
    );
    this.total = this.enabledItems.length;

    if (this.settings.paging && this.settings.perPage > 0) {
      this.totalPages = Math.ceil(this.total / this.settings.perPage);
    } else {
      this.totalPages = 1;
    }

    this.pages = [];
    for (let i = 0; i < this.totalPages; i++) {
      this.pages.push(this.createPage(i));
    }
  }

  getPageByItemIndex(index) {
    return Math.ceil((index + 1) / this.settings.perPage) - 1;
  }

  getItemsByPageIndex(index) {
    if (this.totalPages > 1) {
      const start = this.settings.perPage * index;
      const end = start + this.settings.perPage;
      return this.enabledItems.slice(start, end);
    }
    return this.enabledItems;
  }

  isItemEnabled(index) {
    const item = this.settings.items[index];
    if (!item || !item.is_disabled) {
      return true;
    }
    return item.is_disabled.length === 0;
  }

  showItem(id) {
    const itemIndexes = [];
    this.settings.items.forEach((item, index) => {
      if (String(item.id) === String(id)) {
        itemIndexes.push(index);
      }
    });

    if (itemIndexes.length !== 0) {
      const page = this.getPageByItemIndex(itemIndexes[0]);
      // Show the page in your slider
    }
  }

  trigger(eventName) {
    const event = new CustomEvent(`ditty_${eventName}`, {
      detail: { settings: this.settings, element: this.element },
    });
    this.element.dispatchEvent(event);

    if (typeof this.settings[eventName] === "function") {
      this.settings[eventName](this.settings, this.element);
    }

    document.body.dispatchEvent(event);
  }

  destroy() {
    this.destroySlider();
    this.element.classList.remove("ditty", "ditty-list");
    this.element.removeAttribute("data-id");
    this.element.removeAttribute("data-type");
    this.element.removeAttribute("data-display");
    this.element.removeAttribute("style");
    this.element.innerHTML = "";
  }
}

// Extendable by creating a new class that extends DittyList
class ExtendedDittyList extends DittyList {
  constructor(element, options = {}) {
    super(element, options);
  }

  // Add or override methods here
}

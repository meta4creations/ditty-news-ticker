import DittyDisplay from "./components/dittyDisplay";
import "./css/dittyDisplayTicker.scss";

export default class DittyDisplayTicker extends DittyDisplay {
  constructor(config) {
    const defaults = {
      direction: "left",
      hoverPause: 0, // 0, 1
      itemElementsWrap: "wrap",
      itemMaxWidth: "",
      minHeight: null,
      maxHeight: null,
      scrollDelay: 2,
      scrollInit: "empty",
      spacing: 20,
      speed: 10, // 1 - 10
    };
    super({ ...defaults, ...config });

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

    window.dittyHooks.addFilter(
      "dittyDisplayStyles",
      "ditty",
      this.updateDisplayStyles
    );
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
      const data = this.initializeFilledItem(
        index,
        posX,
        posY,
        tickerW,
        tickerH
      );
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
    const visibleItem = this.visibleItems.filter(($visibleItem) => {
      return $visibleItem === $item;
    });

    if ("yes" !== this.config.cloneItems && visibleItem.length) {
      return false;
    }
    if (
      "yes" !== this.config.cloneItems &&
      "yes" !== this.config.wrapItems &&
      parseInt(this.firstItemIndex) === parseInt(index) &&
      0 !== this.visibleItems.length
    ) {
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
      const position =
        "left" === this.config.direction || "right" === this.config.direction
          ? Number($item.dataset.posX)
          : Number($item.dataset.posY);

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

    this.visibleItems.forEach(($item) => {
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
      return { filled: true };
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
      filled: filled,
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
        if (
          parseFloat($item.dataset.posX) <=
          this.$items.offsetWidth - $item.offsetWidth - this.config.spacing
        ) {
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
        if (
          parseFloat($item.dataset.posY) <=
          this.$items.offsetHeight - $item.offsetHeight - this.config.spacing
        ) {
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
      this.visibleItems.forEach(($item) => {
        let itemHeight = $item.offsetHeight;
        if (itemHeight > height) {
          height = itemHeight;
        }
      });

      if (height !== this.currentHeight) {
        this.currentHeight = height;
        jQuery(this.$items)
          .stop()
          .animate(
            {
              height: height + "px",
            },
            Number(this.config.heightSpeed) * 1000,
            this.config.heightEase
          );
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
      styles +=
        "" != settings["minHeight"]
          ? `min-height:${settings["minHeight"]};`
          : "";
      styles +=
        "" != settings["maxHeight"]
          ? `max-height:${settings["maxHeight"]};`
          : "";
      styles += "}";
    }
    return styles;
  }

  destroy() {
    this.stopTicker();
  }
}

window.dittyDisplays.ticker = DittyDisplayTicker;

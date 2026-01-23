/**
 * Ditty Ticker - Vanilla JS Implementation
 *
 * A continuous scrolling ticker that works with pre-rendered PHP HTML.
 * Based on the original jQuery implementation but without jQuery dependency.
 *
 * @package Ditty
 * @since   4.0
 */

/**
 * Default configuration options
 */
const DEFAULTS = {
  direction: "left",
  speed: 10,
  spacing: 25,
  hoverPause: false,
  cloneItems: true,
  scrollPercent: 0.13,
};

/**
 * DittyTicker Class
 *
 * Handles continuous scrolling ticker animation for pre-rendered items.
 */
export default class DittyTicker {
  /**
   * Create a new DittyTicker instance
   *
   * @param {HTMLElement} element - The ticker container element
   * @param {Object} config - Configuration options
   */
  constructor(element, config = {}) {
    this.element = element;
    this.config = { ...DEFAULTS, ...config };

    // DOM references
    this.itemsContainer = element.querySelector(".ditty__items");
    this.originalItems = [];
    this.activeItems = [];

    // State
    this.running = false;
    this.paused = false;
    this.isVisible = true;
    this.animationId = null;
    this.scrollIncrement = 0;
    this.nextItemIndex = 0;
    this.currentItem = null;
    this.currentHeight = 0;

    // Observers
    this.visibilityObserver = null;
    this.mutationObserver = null;

    // Bind methods
    this._tick = this._tick.bind(this);
    this._onMouseEnter = this._onMouseEnter.bind(this);
    this._onMouseLeave = this._onMouseLeave.bind(this);

    // Initialize
    this._init();
  }

  /**
   * Initialize the ticker
   */
  _init() {
    if (!this.itemsContainer) {
      console.warn("DittyTicker: No items container found");
      return;
    }

    // Store original items
    const items = this.itemsContainer.querySelectorAll(".ditty__item");
    this.originalItems = Array.from(items).map((item) => {
      // Store original HTML for cloning
      return {
        element: item,
        html: item.outerHTML,
      };
    });

    if (this.originalItems.length === 0) {
      console.warn("DittyTicker: No items found");
      return;
    }

    // Apply spacing to items
    this._applySpacing();

    // Calculate and set container height
    this._updateContainerHeight();

    // Set up event listeners
    this._setupEventListeners();

    // Set up visibility observer
    this._initVisibilityObserver();

    // Mark as initialized
    this.element.classList.add("ditty--initialized");

    // Start the ticker
    this._startInitialItems();
  }

  /**
   * Calculate and set the container height based on tallest item
   */
  _updateContainerHeight() {
    let maxHeight = 0;

    // Measure each original item
    this.originalItems.forEach(({ element }) => {
      // Temporarily make visible to measure
      const originalVisibility = element.style.visibility;
      const originalPosition = element.style.position;
      element.style.visibility = "hidden";
      element.style.position = "absolute";

      const height = element.offsetHeight;
      if (height > maxHeight) {
        maxHeight = height;
      }

      // Restore original styles
      element.style.visibility = originalVisibility;
      element.style.position = originalPosition;
    });

    // Set container height
    if (maxHeight > 0) {
      this.itemsContainer.style.height = `${maxHeight}px`;
      this.currentHeight = maxHeight;
    }
  }

  /**
   * Apply spacing to items based on direction
   */
  _applySpacing() {
    const spacing = parseInt(this.config.spacing, 10) || 0;
    const halfSpacing = spacing / 2;

    this.originalItems.forEach(({ element }) => {
      if (this.config.direction === "left" || this.config.direction === "right") {
        element.style.paddingLeft = `${halfSpacing}px`;
        element.style.paddingRight = `${halfSpacing}px`;
      }
    });
  }

  /**
   * Set up mouse event listeners for hover pause
   */
  _setupEventListeners() {
    if (this.config.hoverPause) {
      this.element.addEventListener("mouseenter", this._onMouseEnter);
      this.element.addEventListener("mouseleave", this._onMouseLeave);
    }
  }

  /**
   * Handle mouse enter - pause ticker
   */
  _onMouseEnter() {
    if (this.config.hoverPause && this.running) {
      this.stop();
      this.paused = true;
    }
  }

  /**
   * Handle mouse leave - resume ticker
   */
  _onMouseLeave() {
    if (this.config.hoverPause && this.paused) {
      this.paused = false;
      this.start();
    }
  }

  /**
   * Initialize visibility observer to pause when off-screen
   */
  _initVisibilityObserver() {
    if (typeof IntersectionObserver === "undefined") {
      return;
    }

    this.visibilityObserver = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          const wasVisible = this.isVisible;
          this.isVisible = entry.isIntersecting;

          if (wasVisible !== this.isVisible) {
            if (this.isVisible && !this.paused) {
              this.start();
            } else if (!this.isVisible && this.running) {
              this.stop();
            }
          }
        });
      },
      { threshold: 0.01 }
    );

    this.visibilityObserver.observe(this.element);
  }

  /**
   * Initialize items and start scrolling
   */
  _startInitialItems() {
    // Clear any existing active items
    this.activeItems = [];

    // Initialize first item
    this._initializeItem(0);

    // Start animation
    if (this.isVisible) {
      this.start();
    }
  }

  /**
   * Initialize an item at the given index
   *
   * @param {number} index - Index in originalItems array
   * @returns {Object|null} The active item object or null
   */
  _initializeItem(index) {
    const sourceIndex = index % this.originalItems.length;
    const originalItem = this.originalItems[sourceIndex];

    if (!originalItem) {
      return null;
    }

    // Clone the item
    const clone = originalItem.element.cloneNode(true);
    clone.classList.add("ditty__item--active");

    if (index >= this.originalItems.length) {
      clone.classList.add("ditty__item--clone");
    }

    // Apply spacing
    const spacing = parseInt(this.config.spacing, 10) || 0;
    const halfSpacing = spacing / 2;
    clone.style.paddingLeft = `${halfSpacing}px`;
    clone.style.paddingRight = `${halfSpacing}px`;

    // Position the item at the start position
    clone.style.position = "absolute";
    clone.style.top = "0";
    clone.style.left = "0";

    // Add to container
    this.itemsContainer.appendChild(clone);

    // Check if we need to update container height
    const itemHeight = clone.offsetHeight;
    if (itemHeight > this.currentHeight) {
      this.currentHeight = itemHeight;
      this.itemsContainer.style.height = `${itemHeight}px`;
    }

    // Get initial position
    const position = this._getResetPosition(clone);

    // Create active item object
    const activeItem = {
      element: clone,
      posX: position.posX,
      posY: position.posY,
      index: index,
    };

    // Apply transform
    this._setTransform(clone, position);

    // Add to active items
    this.activeItems.push(activeItem);

    // Update current item reference
    this.currentItem = activeItem;

    // Set next item index
    this.nextItemIndex = index + 1;

    return activeItem;
  }

  /**
   * Get the reset/start position for an item
   *
   * @param {HTMLElement} element - The item element
   * @returns {Object} Position object with posX and posY
   */
  _getResetPosition(element) {
    const containerWidth = this.itemsContainer.offsetWidth;
    const itemWidth = element.offsetWidth;

    let posX = 0;
    let posY = 0;

    switch (this.config.direction) {
      case "left":
        posX = containerWidth;
        break;
      case "right":
        posX = -itemWidth;
        break;
    }

    return { posX, posY };
  }

  /**
   * Apply CSS transform to an element
   *
   * @param {HTMLElement} element - The element to transform
   * @param {Object} position - Position object with posX and posY
   */
  _setTransform(element, position) {
    const x = position.posX !== 0 ? `${position.posX}px` : "0";
    const y = position.posY !== 0 ? `${position.posY}px` : "0";
    element.style.transform = `translate(${x}, ${y})`;
  }

  /**
   * Calculate new position for an item
   *
   * @param {Object} item - The active item object
   * @returns {Object} New position object
   */
  _calculatePosition(item) {
    let posX = item.posX;
    let posY = item.posY;

    switch (this.config.direction) {
      case "left":
        posX = parseFloat(item.posX) - this.scrollIncrement;
        break;
      case "right":
        posX = parseFloat(item.posX) + this.scrollIncrement;
        break;
    }

    return { posX, posY };
  }

  /**
   * Check if a new item should be added
   *
   * @param {Object} item - The active item to check
   * @returns {boolean} True if a new item should start
   */
  _shouldAddNewItem(item) {
    if (item !== this.currentItem) {
      return false;
    }

    const containerWidth = this.itemsContainer.offsetWidth;
    const itemWidth = item.element.offsetWidth;

    switch (this.config.direction) {
      case "left":
        return item.posX <= containerWidth - itemWidth;
      case "right":
        return item.posX >= 0;
    }

    return false;
  }

  /**
   * Check if an item should be removed
   *
   * @param {Object} item - The active item to check
   * @returns {boolean} True if item should be terminated
   */
  _shouldTerminate(item) {
    const containerWidth = this.itemsContainer.offsetWidth;
    const itemWidth = item.element.offsetWidth;

    switch (this.config.direction) {
      case "left":
        return item.posX < -itemWidth;
      case "right":
        return item.posX > containerWidth;
    }

    return false;
  }

  /**
   * Remove an item from the ticker
   *
   * @param {number} index - Index in activeItems array
   */
  _terminateItem(index) {
    const item = this.activeItems[index];
    if (item && item.element) {
      item.element.remove();
    }
    this.activeItems.splice(index, 1);
  }

  /**
   * Position all active items (called each animation frame)
   */
  _positionItems() {
    // Initialize first item if needed
    if (this.activeItems.length === 0) {
      this._initializeItem(0);
    }

    // Iterate through items in reverse to safely remove
    for (let i = this.activeItems.length - 1; i >= 0; i--) {
      const item = this.activeItems[i];
      if (!item) continue;

      // Calculate new position
      const newPos = this._calculatePosition(item);
      item.posX = newPos.posX;
      item.posY = newPos.posY;

      // Apply transform
      this._setTransform(item.element, newPos);

      // Check if we need to add a new item
      if (this._shouldAddNewItem(item)) {
        if (this.config.cloneItems || this.nextItemIndex < this.originalItems.length) {
          this._initializeItem(this.nextItemIndex);
        }
      }

      // Check if item should be removed
      if (this._shouldTerminate(item)) {
        this._terminateItem(i);
      }
    }
  }

  /**
   * Animation tick function
   */
  _tick() {
    if (!this.running || this.paused || !this.isVisible) {
      return;
    }

    // Calculate scroll increment based on speed
    this.scrollIncrement = parseFloat(this.config.speed) * this.config.scrollPercent;

    // Position items
    this._positionItems();

    // Continue animation
    this.animationId = requestAnimationFrame(this._tick);
  }

  /**
   * Start the ticker animation
   */
  start() {
    if (this.running || this.paused) {
      return;
    }

    if (!this.isVisible) {
      return;
    }

    this.running = true;

    // Cancel any existing animation
    if (this.animationId) {
      cancelAnimationFrame(this.animationId);
    }

    // Start animation loop
    this.animationId = requestAnimationFrame(this._tick);

    // Dispatch event
    this.element.dispatchEvent(new CustomEvent("dittyTickerStart"));
  }

  /**
   * Stop the ticker animation
   */
  stop() {
    if (this.animationId) {
      cancelAnimationFrame(this.animationId);
      this.animationId = null;
    }

    this.running = false;

    // Dispatch event
    this.element.dispatchEvent(new CustomEvent("dittyTickerStop"));
  }

  /**
   * Pause the ticker (can be resumed)
   */
  pause() {
    this.paused = true;
    this.stop();
  }

  /**
   * Resume the ticker after pause
   */
  resume() {
    this.paused = false;
    this.start();
  }

  /**
   * Toggle play/pause
   */
  toggle() {
    if (this.running) {
      this.pause();
    } else {
      this.resume();
    }
  }

  /**
   * Destroy the ticker instance
   */
  destroy() {
    // Stop animation
    this.stop();

    // Remove event listeners
    this.element.removeEventListener("mouseenter", this._onMouseEnter);
    this.element.removeEventListener("mouseleave", this._onMouseLeave);

    // Disconnect observers
    if (this.visibilityObserver) {
      this.visibilityObserver.disconnect();
    }

    if (this.mutationObserver) {
      this.mutationObserver.disconnect();
    }

    // Remove cloned items
    this.activeItems.forEach((item) => {
      if (item.element && item.element.classList.contains("ditty__item--clone")) {
        item.element.remove();
      }
    });

    // Reset state
    this.activeItems = [];
    this.element.classList.remove("ditty--initialized");
  }
}

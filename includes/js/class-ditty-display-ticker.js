/* global jQuery:true */
/* global dittyEditorInit:true */
/* global dittyLayoutCss:true */

/**
 * Ditty class
 *
 * @since		3.0.16
 * @return	null
 */

(function ($) {
  "use strict";

  var defaults = {
    id: 0,
    title: "",
    display: 0,
    status: "",
    direction: "left",
    spacing: 20,
    speed: 10, // 1 - 10
    cloneItems: "yes",
    wrapItems: "yes",
    hoverPause: 0, // 0, 1
    height: null,
    minHeight: null,
    maxHeight: null,
    heightEase: "easeInOutQuint",
    heightSpeed: 1.5, // 1 - 10
    scrollInit: "empty",
    scrollDelay: 2,
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
    itemMaxWidth: "",
    itemElementsWrap: "wrap",
    item: 0,
    shuffle: 0,
    showEditor: 0,
    items: [
      // {
      //  id:						null,
      //  uniq_id:			null,
      //  parent_dd:		null,
      //  html:					null,
      //	is_disabled:	null,
      // ...
    ],
  };

  var Ditty_Ticker = function (elmt, options) {
    this.displayType = "ticker";
    this.elmt = elmt;
    this.settings = $.extend({}, defaults, $.ditty_ticker.defaults, options);
    this.nextItem = null;
    this.total = this.settings.items.length;
    this.$elmt = $(elmt);
    this.$title = null;
    this.$titleContents = null;
    this.$contents = null;
    this.$items = null;
    this.$currentItem = null;
    this.$lastItem = null;
    this.scrollPercent = 0.13;
    this.running = false;
    this.interval = false;
    this.firstItem = this.settings.item;
    this.currentHeight = this.settings.height;
    this.visibleItems = [];

    this.scrollIncrement = 0;
    // this.framesPerSecond = 60;
    // this.previousTime = performance.now();
    // this.frameInterval = 1000 / this.framesPerSecond;
    // this.deltaTimeMultiplier = 1;
    // this.deltaTime = 0;
    // this.averageDeltaTime = [];

    if (1 === parseInt(this.settings.shuffle)) {
      this.shuffle();
    }

    this._init();
  };

  Ditty_Ticker.prototype = {
    _init: function () {
      var self = this,
        $contents,
        $items;

      if (0 === this.total) {
        this.hide();
      }

      // Remove the pre class
      this.$elmt.removeClass("ditty--pre");

      // Add classes and data attributes
      this.$elmt.addClass("ditty ditty-ticker");
      this.$elmt.attr("data-id", this.settings.id);
      this.$elmt.attr("data-type", this.displayType);
      this.$elmt.attr("data-display", this.settings.display);

      // Create the ticker contents
      $contents = $('<div class="ditty-ticker__contents"></div>');
      this.$contents = $contents;

      // Create the ticker title
      this.$title = $('<div class="ditty-ticker__title"></div>');
      this.$titleContents = $(
        '<div class="ditty-ticker__title__contents"></div>'
      );
      this.$title.append(this.$titleContents);

      // Create the ticker items container
      $items = $('<div class="ditty-ticker__items"></div>');
      this.$items = $items;

      // Set the initial height
      this.$items.height(this.currentHeight);

      // Add the new elements
      $contents.append($items);
      this.$elmt.append($contents);

      // Setup styles
      this._styleDisplay();
      this._styleTitle();

      // Add listeners
      this.$elmt.on("mouseenter", { self: this }, this._mouseenter);
      this.$elmt.on("mouseleave", { self: this }, this._mouseleave);

      // Show the editor or start live updates
      if (this.settings.showEditor) {
        dittyEditorInit(this);
      } else {
        this.trigger("start_live_updates");
      }

      // Trigger the init
      setTimeout(function () {
        // Preload images
        for (var i = 0; i < self.total; i++) {
          self._preloadItem(self.settings.items[i]);
        }

        // Initialize the items
        self._initializeItems();

        self.trigger("init");
      }, 1);
    },

    _initializeItems: function () {
      if ("filled" === this.settings.scrollInit) {
        // Fill the ticker
        this._fillTicker();
      } else {
        // Start
        if (!this.running) {
          this._timerStart();
        }
      }
    },

    _preloadItem: function (item) {
      var img;
      $(item.html)
        .find("img")
        .each(function () {
          img = new Image();
          img.src = $(this).attr("src");
        });
    },

    _positionItems: function (distance) {
      var self = this;

      // Initialize the first item
      if (0 === self.visibleItems.length) {
        var firstItem = self.firstItem;
        if (!this._isItemEnabled(firstItem)) {
          firstItem = this._getNextItem(firstItem);
        }
        self._initializeItem(firstItem);
      }

      $.each(self.visibleItems, function (index, value) {
        if (!value) {
          return;
        }

        // Set the new position
        var $item = self.visibleItems[index].$item,
          newPos = self._calculateItemPosition(index, distance);

        self.visibleItems[index].posX = newPos.posX;
        self.visibleItems[index].posY = newPos.posY;

        // Move the item to the new position
        self._itemSetTransform($item, newPos);

        // Check if a new item should start
        if (self._newItemShouldStart($item, newPos)) {
          self._initializeItem(parseInt(self.nextItem));
        }

        // Check if the item should be removed from the visible array
        if (self._itemShouldTerminate($item, newPos)) {
          self._terminateItem(index);
        }
      });
    },

    _timerStart: function () {
      var self = this;

      this.running = true;
      this.trigger("start");

      cancelAnimationFrame(this.interval);

      function ditty_tickerLoop() {
        // var lengthLimit = 20;
        // var timeDiff = currentTime - self.previousTime;
        // self.averageDeltaTime.unshift( timeDiff );
        // if ( self.averageDeltaTime.length > lengthLimit ) {
        // 	self.averageDeltaTime.length = lengthLimit;
        // }
        // var sum = self.averageDeltaTime.reduce((partialSum, a) => partialSum + a, 0);
        // var average = Math.ceil(sum / lengthLimit);

        //self.deltaTime = currentTime - self.previousTime;
        //console.log('timeDiff', timeDiff);
        //self.deltaTimeMultiplier = self.deltaTime / self.frameInterval;
        self.scrollIncrement =
          parseFloat(self.settings.speed) * self.scrollPercent;
        self._positionItems();
        //self.previousTime = currentTime;
        self.interval = requestAnimationFrame(ditty_tickerLoop);
      }

      self.interval = requestAnimationFrame(ditty_tickerLoop);
    },

    _timerStop: function () {
      cancelAnimationFrame(this.interval);
      this.running = false;
      this.trigger("stop");
    },

    _mouseenter: function (e) {
      var self = e.data.self;
      if (self.settings.hoverPause) {
        self._timerStop();
      }
    },

    _mouseleave: function (e) {
      var self = e.data.self;
      if (self.settings.hoverPause) {
        self._timerStart();
      }
    },

    _newItemShouldStart: function ($item, position) {
      if (this.$currentItem[0] !== $item[0]) {
        return false;
      }

      var shouldStart = false;
      switch (this.settings.direction) {
        case "left":
          if (position.posX <= this.$items.outerWidth() - $item.outerWidth()) {
            shouldStart = true;
          }
          break;
        case "right":
          if (position.posX >= 0) {
            shouldStart = true;
          }
          break;
        case "down":
          if (position.posY >= 0) {
            shouldStart = true;
          }
          break;
        case "up":
          if (
            position.posY <=
            this.$items.outerHeight() - $item.outerHeight()
          ) {
            shouldStart = true;
          }
          break;
      }

      return shouldStart;
    },

    _initializeItem: function (index, positionType) {
      if (undefined === this.settings.items[index]) {
        return false;
      }

      var existingItems = this.$items.children(
        ".ditty-item--" + this.settings.items[index].uniq_id
      );
      if ("yes" !== this.settings.cloneItems && existingItems.length) {
        return false;
      }
      if (
        "yes" !== this.settings.cloneItems &&
        "yes" !== this.settings.wrapItems &&
        this.firstItem === parseInt(index) &&
        0 !== parseInt(this.visibleItems.length)
      ) {
        return false;
      }

      // Create and add a new item
      var $item = $(this.settings.items[index].html);
      $item.css({
        top: 0,
        left: 0,
      });
      if (
        this.$items
          .children(".ditty-item--" + this.settings.items[index].id)
          .not(".ditty-item--clone").length > 0
      ) {
        $item.addClass("ditty-item--clone");
      }

      // Style the item
      this._styleItem($item);

      // Add the layout css to the DOM
      if (this.settings.items[index].css) {
        dittyLayoutCss(
          this.settings.items[index].css,
          this.settings.items[index].layout_id
        );
      }

      this._itemSpacing($item);
      this._itemSetTransform($item, this._itemResetPosition($item));
      this.$items.append($item);

      $item.css({
        display: "block",
        top: 0,
        left: 0,
        opacity: 1,
      });

      // Add a visible class
      $item.addClass("ditty-item--current");

      // Remove the current class from the old current item
      if (null !== this.$currentItem) {
        this.$currentItem.removeClass("ditty-item--current");
      }

      // Set this as the current item
      this.$currentItem = $item;

      if (null === this.$lastItem) {
        $item.addClass("ditty-item--last");
        this.$lastItem = $item;
      }

      // Set the current item
      this.settings.item = index;

      // Set the next item
      this.nextItem = this._getNextItem(index);

      if ("custom" !== positionType) {
        var position = this._itemResetPosition($item);
        this._itemSetTransform($item, position);
        this.visibleItems.push({
          $item: $item,
          itemId: this.settings.items[index].id,
          posX: position.posX,
          posY: position.posY,
        });

        // Set the ticker height
        this._setCurrentHeight();
      }

      this.trigger("active_items_update");

      // Return the item
      return $item;
    },

    _getNextItem: function (index) {
      var self = this,
        nextItem = false,
        $i;

      // If the next item was changed externally, use it
      if (null !== this.nextItem && index !== this.nextItem) {
        var customIndex = parseInt(this.nextItem);
        if (
          customIndex < this.total &&
          customIndex >= 0 &&
          self._isItemEnabled(customIndex)
        ) {
          nextItem = parseInt(this.nextItem);
        }
      }

      // Find the next enabled item
      if (!nextItem) {
        for ($i = index; $i < this.total; $i++) {
          if ($i !== index && self._isItemEnabled($i)) {
            nextItem = parseInt($i);
            break;
          }
        }
      }
      if (!nextItem) {
        for ($i = 0; $i < this.total; $i++) {
          if (self._isItemEnabled($i)) {
            nextItem = parseInt($i);
            break;
          }
        }
      }

      // Set the next item
      return nextItem;
    },

    /**
     * Check if a item should terminate
     *
     * @since    3.0
     * @return   null
     */
    _itemShouldTerminate: function ($item, position) {
      var shouldTerminate = false;
      switch (this.settings.direction) {
        case "left":
          if (position.posX < -$item.outerWidth()) {
            shouldTerminate = true;
          }
          break;
        case "right":
          if (position.posX > this.$items.outerWidth()) {
            shouldTerminate = true;
          }
          break;
        case "up":
          if (position.posY < -$item.outerHeight()) {
            shouldTerminate = true;
          }
          break;
        case "down":
          if (position.posY > this.$items.outerHeight()) {
            shouldTerminate = true;
          }
          break;
      }

      return shouldTerminate;
    },

    /**
     * Terminate a items
     *
     * @since    3.0
     * @return   null
     */
    _terminateItem: function (index) {
      var $item = this.visibleItems[index].$item,
        $nextItem = $item.next();

      // Remove the item
      $item.remove();
      this.visibleItems.splice(index, 1);

      if ($nextItem.length) {
        $nextItem.addClass("ditty-item--last");
        this.$lastItem = $nextItem;
      }

      // Set the ticker height
      this._setCurrentHeight();

      this.trigger("active_items_update");
    },

    /**
     * Set the height of the ticker
     *
     * @since    3.0
     * @return   null
     */
    _setCurrentHeight: function () {
      var height = this.currentHeight;

      if (
        "up" === this.settings.direction ||
        "down" === this.settings.direction
      ) {
        height = this.$items.outerHeight();
        this.$items.css({
          height: "100%",
        });
      } else {
        height = 0;
        $.each(this.visibleItems, function (index, value) {
          var itemHeight = value.$item.outerHeight();
          if (itemHeight > height) {
            height = itemHeight;
          }
        });

        if (height !== this.currentHeight) {
          this.$items.stop().animate(
            {
              height: height + "px",
            },
            parseFloat(this.settings.heightSpeed) * 1000,
            this.settings.heightEase,
            function () {
              // Animation complete.
            }
          );
        }
      }

      if (height !== this.currentHeight) {
        this.currentHeight = height;
        this.trigger("height_updated");
      }
    },

    /**
     * Set the spacing between items
     *
     * @since    3.0
     * @return   null
     */
    _itemSpacing: function ($item) {
      switch (this.settings.direction) {
        case "left":
        case "right":
          $item.css({
            paddingLeft: this.settings.spacing / 2 + "px",
            paddingRight: this.settings.spacing / 2 + "px",
            paddingTop: 0,
            paddingBottom: 0,
          });
          break;
        case "up":
        case "down":
          $item.css({
            paddingLeft: 0,
            paddingRight: 0,
            paddingTop: this.settings.spacing / 2 + "px",
            paddingBottom: this.settings.spacing / 2 + "px",
          });
          break;
      }
    },

    /**
     * Transform the item position
     *
     * @since    3.0
     * @return   null
     */
    _itemSetTransform: function ($item, position) {
      var posX = position.posX,
        posY = position.posY;

      if (posX !== 0) {
        posX = posX + "px";
      }

      if (posY !== 0) {
        posY = posY + "px";
      }

      $item.css({
        transform: "translate( " + posX + ", " + posY + " )",
      });
    },

    /**
     * Return the new position of a item
     *
     * @since    3.0
     * @return   null
     */
    _calculateItemPosition: function (index, distance) {
      var posX = 0,
        posY = 0,
        increment = this.scrollIncrement;

      //var useDeltaMultiplier = 1;
      if (distance) {
        increment = distance;
      }
      // else if (useDeltaMultiplier) {
      //   increment = increment * this.deltaTimeMultiplier;
      // }

      // if ( useDeltaMultiplier ) {
      // 	console.log('DELTA increment', increment);
      // } else {
      // 	console.log('increment', increment);
      // }

      switch (this.settings.direction) {
        case "left":
          posX = parseFloat(this.visibleItems[index].posX) - increment;
          break;
        case "right":
          posX = parseFloat(this.visibleItems[index].posX) + increment;
          break;
        case "up":
          posY = parseFloat(this.visibleItems[index].posY) - increment;
          break;
        case "down":
          posY = parseFloat(this.visibleItems[index].posY) + increment;
          break;
      }

      return { posX: posX, posY: posY };
    },

    /**
     * Return the reset position of a item
     *
     * @since    3.0
     * @return   null
     */
    _itemResetPosition: function ($item) {
      var posX = 0,
        posY = 0;

      switch (this.settings.direction) {
        case "left":
          posX = this.$items.outerWidth();
          break;
        case "right":
          posX = "-" + $item.outerWidth();
          break;
        case "up":
          posY = this.$items.outerHeight();
          break;
        case "down":
          posY = "-" + $item.outerHeight();
          break;
      }

      return { posX: posX, posY: posY };
    },

    /**
     * Reverse the order of items
     *
     * @since    3.0
     * @return   null
     */
    _reverseItems: function () {
      if (this.$currentItem === this.$lastItem) {
        return false;
      }

      var $currentItem = this.$currentItem,
        $lastItem = this.$lastItem;

      this.$currentItem = $lastItem;
      this.$lastItem = $currentItem;

      this.$currentItem
        .removeClass("ditty-item--last")
        .addClass("ditty-item--current");
      this.$lastItem
        .removeClass("ditty-item--current")
        .addClass("ditty-item--last");

      var itemItems = this.$items.children(".ditty-item");
      this.$items.append(itemItems.get().reverse());
    },

    /**
     * Reset the visible items
     *
     * @since    3.0
     * @return   null
     */
    _resetItems: function () {
      this.$items.empty();
      this.visibleItems = [];
      this.trigger("active_items_update");
    },

    /**
     * Fill a ticker with items before scrolling
     *
     * @since    3.0
     * @return   null
     */
    _fillTicker: function () {
      var self = this,
        tickerW = parseFloat(this.$items.outerWidth()),
        tickerH = parseFloat(this.$items.outerHeight()),
        posX = 0,
        posY = 0,
        filled = false,
        current = this.settings.item,
        fillTimer = null;

      if (!this._isItemEnabled(current)) {
        current = this._getNextItem(current);
      }

      if ("right" === this.settings.direction) {
        posX = tickerW;
      } else if ("down" === this.settings.direction) {
        posY = tickerH;
      }

      fillTimer = setInterval(function () {
        var $item = self._initializeItem(current);
        if ($item) {
          var data = self._filledItemInit(
            current,
            $item,
            posX,
            posY,
            tickerW,
            tickerH
          );
          posX = data.posX;
          posY = data.posY;
          filled = data.filled;
          current = self._getNextItem(current);
        } else {
          filled = true;
        }

        if (filled) {
          clearInterval(fillTimer);

          self.trigger("active_items_update");

          // Delay the start
          setTimeout(function () {
            if (!self.running) {
              self._timerStart();
            }
          }, parseFloat(self.settings.scrollDelay) * 1000);
        }
      }, 100);
    },

    /**
     * Initialize a item for a filled init ticker
     *
     * @since    3.0
     * @return   null
     */
    _filledItemInit: function (index, $item, posX, posY, tickerW, tickerH) {
      var itemId = $item.data("id"),
        itemW = parseFloat($item.outerWidth()),
        itemH = parseFloat($item.outerHeight()),
        translateX = 0,
        translateY = 0,
        filled = false;

      $item.css({
        display: "block",
        opacity: 0,
      });

      switch (this.settings.direction) {
        case "left":
          translateX = posX;
          this._itemSetTransform($item, { posX: translateX, posY: posY });
          posX = posX + itemW;
          if (posX > tickerW) {
            filled = true;
          }
          break;
        case "right":
          posX = posX - itemW;
          translateX = posX;
          this._itemSetTransform($item, { posX: translateX, posY: posY });
          if (posX < 0) {
            filled = true;
          }
          break;
        case "up":
          translateY = posY;
          this._itemSetTransform($item, { posX: posX, posY: translateY });
          posY = posY + itemH;
          if (posY > tickerH) {
            filled = true;
          }
          break;
        case "down":
          posY = posY - itemH;
          translateY = posY;
          this._itemSetTransform($item, { posX: posX, posY: translateY });
          if (posY < 0) {
            filled = true;
          }
          break;
      }

      $item.stop().animate(
        {
          opacity: 1,
        },
        1000,
        "linear",
        function () {
          // Animation complete.
        }
      );

      this.visibleItems.push({
        $item: $item,
        itemId: itemId,
        posX: translateX,
        posY: translateY,
      });

      // Set the ticker height
      this._setCurrentHeight();

      return { posX: posX, posY: posY, filled: filled };
    },

    /**
     * Set the direction of the ticker
     *
     * @since    3.0
     * @return   null
     */
    _setDirection: function (direction) {
      if (!$.inArray(direction, this.settings.directions)) {
        return false;
      }
      if (direction === this.settings.directions) {
        return false;
      }
      this.settings.direction = direction;
      this._timerStop();
      this._resetItems();
      this.settings.item = 0;
      this._initializeItems();
      this.trigger("direction");
    },

    /**
     * Style the display element
     *
     * @since    3.0
     * @return   null
     */
    _styleDisplay: function () {
      this.$elmt.css({
        maxWidth: this.settings.maxWidth,
        backgroundColor: this.settings.bgColor,
        borderColor: this.settings.borderColor,
        borderStyle: this.settings.borderStyle,
      });
      this.$elmt.css(this.settings.borderRadius);
      this.$elmt.css(this.settings.borderWidth);
      this.$elmt.css(this.settings.margin);
      this.$elmt.css(this.settings.padding);

      this.$contents.css({
        backgroundColor: this.settings.contentsBgColor,
        borderColor: this.settings.contentsBorderColor,
        borderStyle: this.settings.contentsBorderStyle,
      });
      this.$contents.css(this.settings.contentsPadding);
      this.$contents.css(this.settings.contentsBorderRadius);
      this.$contents.css(this.settings.contentsBorderWidth);

      if (
        "up" === this.settings.direction ||
        "down" === this.settings.direction
      ) {
        var minHeight = this.settings.minHeight ? this.settings.minHeight : 0,
          maxHeight = this.settings.maxHeight
            ? this.settings.maxHeight
            : "none";
        this.$items.css({
          minHeight: minHeight,
          maxHeight: maxHeight,
        });
      } else {
        this.$items.css({
          minHeight: "",
          maxHeight: "",
        });
      }
    },

    /**
     * Style the title element
     *
     * @since    3.0
     * @return   null
     */
    _styleTitle: function () {
      this.$elmt.attr("data-title", this.settings.titleDisplay);
      this.$elmt.attr(
        "data-title_position",
        this.settings.titleElementPosition
      );
      if ("none" === this.settings.titleDisplay) {
        this.$title.remove();
      } else {
        var $element = $(
          "<" +
            this.settings.titleElement +
            ' class="ditty-ticker__title__element">' +
            this.settings.title +
            "</" +
            this.settings.titleElement +
            ">"
        );

        $element.css({
          fontSize: this.settings.titleFontSize,
          lineHeight: this.settings.titleLineHeight,
          color: this.settings.titleColor,
          margin: 0,
          padding: 0,
        });

        this.$title.css({
          backgroundColor: this.settings.titleBgColor,
          borderColor: this.settings.titleBorderColor,
          borderStyle: this.settings.titleBorderStyle,
        });
        this.$title.css(this.settings.titleMargin);
        this.$title.css(this.settings.titlePadding);
        this.$title.css(this.settings.titleBorderRadius);
        this.$title.css(this.settings.titleBorderWidth);

        this.$titleContents.html($element);
        this.$elmt.prepend(this.$title);
      }
    },

    /**
     * Style item elemtents
     *
     * @since    3.0
     * @return   null
     */
    _styleItem: function ($item) {
      $item.children(".ditty-item__elements").css({
        color: this.settings.itemTextColor,
        backgroundColor: this.settings.itemBgColor,
        borderColor: this.settings.itemBorderColor,
        borderStyle: this.settings.itemBorderStyle,
      });
      $item.children(".ditty-item__elements").css(this.settings.itemPadding);
      $item
        .children(".ditty-item__elements")
        .css(this.settings.itemBorderRadius);
      $item
        .children(".ditty-item__elements")
        .css(this.settings.itemBorderWidth);
      if ("" !== this.settings.itemElementsWrap) {
        $item
          .children(".ditty-item__elements")
          .css({ whiteSpace: this.settings.itemElementsWrap });
      }
      if ("" !== this.settings.itemMaxWidth) {
        $item
          .children(".ditty-item__elements")
          .css({ maxWidth: this.settings.itemMaxWidth });
      }
    },

    /**
     * Return data for the object
     *
     * @since    3.0
     * @return   null
     */
    _getOption: function (key) {
      switch (key) {
        case "ditty":
          return this;
        case "type":
          return this.displayType;
        case "display":
          return this.settings.display;
        case "items":
          return this.settings.items;
        case "height":
          return this.currentHeight;
        default:
          return this.settings[key];
      }
    },

    /**
     * Set data for the object
     *
     * @since    3.0
     * @return   null
     */
    _setOption: function (key, value) {
      if (undefined === value) {
        return false;
      }
      switch (key) {
        case "items":
          this.updateItems(value);
          break;
        case "direction":
          this.settings[key] = value;
          this._styleDisplay();
          this._setDirection(value);
          break;
        case "titleDisplay":
        case "titleElement":
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
          this.settings[key] = value;
          this._styleTitle();
          break;
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
          this.settings[key] = value;
          this._styleDisplay();
          this._setCurrentHeight();
          break;
        default:
          this.settings[key] = value;
          break;
      }

      this.trigger("update");
    },

    shuffle: function () {
      var temp, rand;

      for (var i = this.total - 1; i > 0; i--) {
        rand = Math.floor(Math.random() * (i + 1));
        temp = this.settings.items[i];

        this.settings.items[i] = this.settings.items[rand];
        this.settings.items[rand] = temp;
      }
    },

    play: function () {
      if (!this.running) {
        this._timerStart();
      }
    },

    pause: function () {
      if (this.running) {
        this._timerStop();
      }
    },

    direction: function (value) {
      this._setDirection(value);
    },

    toggle: function () {
      if (this.running) {
        this.pause();
      } else {
        this.play();
      }
    },

    running: function () {
      return this.running;
    },

    current: function () {
      return this.$currentItem;
    },

    /**
     * Check if a item is enabled
     *
     * @since    3.0
     * @return   null
     */
    _isItemEnabled: function (index) {
      if (undefined === this.settings.items[parseInt(index)]) {
        return false;
      }
      if (undefined === this.settings.items[parseInt(index)].is_disabled) {
        return true;
      } else {
        if (this.settings.items[parseInt(index)].is_disabled.length > 0) {
          return false;
        } else {
          return true;
        }
      }
    },

    /**
     * Get the disabled status of all items
     *
     * @since    3.0
     * @return   null
     */
    _disabledItemsStatus: function () {
      var self = this,
        statusus = {};
      $.each(this.settings.items, function (i, item) {
        if (self._isItemEnabled(i)) {
          statusus[item.id] = "enabled";
        } else {
          statusus[item.id] = "disabled";
        }
      });
      return statusus;
    },

    /**
     * Add a disabled type to a item
     *
     * @since    3.0
     * @return   null
     */
    addItemDisabled: function (id, slug) {
      var self = this;
      $.each(this.settings.items, function (i, item) {
        if (String(item.id) === String(id)) {
          if (!Array.isArray(self.settings.items[i].is_disabled)) {
            self.settings.items[i].is_disabled = [];
          }
          self.settings.items[i].is_disabled.push(slug);
        }
      });
      this.trigger("disabled_items_update");
    },

    /**
     * Remove a disabled type from a item
     *
     * @since    3.0
     * @return   null
     */
    removeItemDisabled: function (id, slug) {
      var self = this;
      $.each(this.settings.items, function (i, item) {
        if (String(item.id) === String(id)) {
          if (
            Array.isArray(self.settings.items[i].is_disabled) &&
            self.settings.items[i].is_disabled.length
          ) {
            self.settings.items[i].is_disabled = $.grep(
              self.settings.items[i].is_disabled,
              function (value) {
                return value !== slug;
              }
            );
          }
        }
      });
      this.trigger("disabled_items_update");
    },

    /**
     * Show a specific item by index or id
     *
     * @since    3.0
     * @return   null
     */
    showItem: function (id) {
      var itemIndexes = [];
      $.each(this.settings.items, function (i, item) {
        if (String(item.id) === String(id)) {
          itemIndexes.push(i);
        }
      });
      if (0 !== itemIndexes.length) {
        this.nextItem = itemIndexes[0];
        return this.nextItem;
      }
    },

    /**
     * Add a new item
     *
     * @since    3.0
     * @return   null
     */
    addItem: function (item, index, type) {
      var newItems = this.settings.items.slice(),
        indexExists = true;

      if (index >= this.total || index < 0) {
        indexExists = false;
      }

      // Replace a item
      if ("replace" === type && indexExists) {
        newItems.splice(index, 1, item);

        // Add a item
      } else {
        if (null === index || "" === index) {
          newItems.splice(this.nextItem, 0, item);
        } else {
          if (index >= this.total) {
            newItems.push(item);
          } else if (index < 0) {
            newItems.splice(0, 0, item);
          } else {
            newItems.splice(index, 0, item);
          }
        }
      }
      this.updateItems(newItems);
    },

    /**
     * Delete a item by index
     *
     * @since    3.0
     * @return   null
     */
    deleteItem: function (id) {
      var updatedItems = [];
      $.each(this.settings.items, function (index, item) {
        if (String(item.id) !== String(id)) {
          updatedItems.push(item);
        }
      });
      this.updateItems(updatedItems);
    },

    /**
     * Update the current items
     *
     * @since    3.0
     * @return   null
     */
    updateItemsNew: function (newItems, itemId, type, forceSwapAll) {
      if (undefined === newItems) {
        return false;
      }

      // var forceSwaps = [];

      // // Update a single item id
      // if (itemId) {
      //   var tempCurrentItems = this.settings.items.slice(),
      //     tempNewItems = [],
      //     tempSwapped = false;

      //   $.each(tempCurrentItems, function (index, item) {
      //     if (String(item.id) === String(itemId)) {
      //       // Add after the id
      //       if ("after" === type) {
      //         tempNewItems.push(item);
      //         $.each(newItems, function (index, newItem) {
      //           tempNewItems.push(newItem);
      //         });
      //         tempSwapped = true;

      //         // Add before the id
      //       } else if ("before" === type) {
      //         $.each(newItems, function (index, newItem) {
      //           tempNewItems.push(newItem);
      //         });
      //         tempNewItems.push(item);
      //         tempSwapped = true;

      //         // Else swap the ID
      //       } else {
      //         if (!tempSwapped) {
      //           $.each(newItems, function (index, newItem) {
      //             tempNewItems.push(newItem);
      //             forceSwaps.push(String(newItem.uniq_id));
      //           });
      //           tempSwapped = true;
      //         }
      //       }
      //     } else {
      //       tempNewItems.push(item);
      //     }
      //   });
      //   if (!tempSwapped) {
      //     $.each(this.settings.items, function (index, item) {
      //       tempNewItems.push(item);
      //     });
      //     tempSwapped = true;
      //   }

      //   if (0 !== this.total) {
      //     newItems = tempNewItems;
      //   }
      // }

      const { updatedItems } = dittyGetUpdatedItemData(
        this.settings.items,
        newItems,
        type
      );

      this.settings.items = updatedItems;
      this.total = updatedItems.length;
      if (0 === this.total) {
        this.hide();
      } else {
        this.show();
      }

      if (this.nextItem >= this.total) {
        this.nextItem = 0;
      }
      this.trigger("update");
    },
    
    /**
     * Update the current items
     *
     * @since    3.0
     * @return   null
    */
    updateItems: function ( newItems, itemId, type, forceSwapAll ) {
      if ( undefined === newItems ) {
        return false;
      }
    
      var forceSwaps = [];
          
      // Update a single item id		
      if ( itemId ) {
        var tempCurrentItems = this.settings.items.slice(),
            tempNewItems = [],
            tempSwapped = false;
    
        $.each( tempCurrentItems, function( index, item ) {
          if ( String( item.id ) === String( itemId ) ) {
            
            // Add after the id
            if ( 'after' === type ) {
              tempNewItems.push( item );
              $.each( newItems, function( index, newItem ) {
                tempNewItems.push( newItem );
              } );
              tempSwapped = true;
              
            // Add before the id
            } else if ( 'before' === type ) {
              $.each( newItems, function( index, newItem ) {
                tempNewItems.push( newItem );
              } );
              tempNewItems.push( item );
              tempSwapped = true;
            
            // Else swap the ID
            } else {
              if ( ! tempSwapped ) {
                $.each( newItems, function( index, newItem ) {
                  tempNewItems.push( newItem );
                  forceSwaps.push( String( newItem.uniq_id ) );
                } );
                tempSwapped = true;
              }
            }
          } else {
            tempNewItems.push( item );
          } 
        } );
        if ( ! tempSwapped ) {
          $.each( this.settings.items, function( index, item ) {
            tempNewItems.push( item );
          } );
          tempSwapped = true;
        }
        
        if ( 0 !== this.total ) {
          newItems = tempNewItems;
        }	
      }
      
      this.settings.items = newItems;
      this.total = newItems.length;
      if ( 0 === this.total ) {
        this.hide();
      } else {
        this.show();
      }
      
      if ( this.nextItem >= this.total ) {
        this.nextItem = 0;
      } 
      this.trigger( 'update' );
    },

    /**
     * Return the currently visible items
     *
     * @since    3.0
     * @return   null
     */
    getActiveItems: function () {
      var activeItems = [];
      $.each(this.visibleItems, function (index, value) {
        activeItems.push({ id: value.itemId });
      });
      return activeItems;
    },

    /**
     * Hide the ticker
     *
     * @since    3.0
     * @return   null
     */
    hide: function () {
      this.$elmt.hide();
      this.pause();
    },

    /**
     * Show the ticker
     *
     * @since    3.0
     * @return   null
     */
    show: function () {
      this.$elmt.show();
      this.play();
    },

    /**
     * Trigger events
     *
     * @since    3.0
     * @return   null
     */
    trigger: function (fn) {
      var params = [];
      switch (fn) {
        case "active_items_update":
          params = [this, this.getActiveItems()];
          break;
        case "disabled_items_update":
          params = [this._disabledItemsStatus()];
          break;
        case "height_updated":
          params = [this.currentHeight, this.$elmt];
          break;
        case "start_live_updates":
          params = [this.settings.id];
          break;
        default:
          params = [this.settings, this.$elmt];
          break;
      }

      //params = [this.settings];
      this.$elmt.trigger("ditty_" + fn, params);
      if (typeof this.settings[fn] === "function") {
        this.settings[fn].apply(this.$elmt, params);
      }
      $("body").trigger("ditty_" + fn, params);
    },

    /**
     * Get or set ditty options
     *
     * @since    3.0
     * @return   null
     */
    options: function (key, value) {
      var self = this;
      if (typeof key === "object") {
        $.each(key, function (k, v) {
          self._setOption(k, v);
        });
      } else if (typeof key === "string") {
        if (value === undefined) {
          return self._getOption(key);
        }
        self._setOption(key, value);
      } else {
        return self.settings;
      }
    },

    /**
     * Destroy this object
     *
     * @since    3.0
     * @return   null
     */
    destroy: function () {
      // Remove listeners
      this.$elmt.off("mouseenter", { self: this }, this._mouseenter);
      this.$elmt.off("mouseleave", { self: this }, this._mouseleave);

      cancelAnimationFrame(this.interval);

      this.$elmt.removeClass("ditty ditty-ticker");
      this.$elmt.removeAttr("data-id");
      this.$elmt.removeAttr("data-type");
      this.$elmt.removeAttr("data-display");
      this.$elmt.removeAttr("style");
      this.$elmt.empty();
      this.elmt._ditty_ticker = null;
    },
  };

  $.fn.ditty_ticker = function (options) {
    var args = arguments,
      error = false,
      returns;

    if (options === undefined || typeof options === "object") {
      return this.each(function () {
        if (!this._ditty_ticker) {
          this._ditty_ticker = new Ditty_Ticker(this, options);
        }
      });
    } else if (typeof options === "string") {
      this.each(function () {
        var instance = this._ditty_ticker;

        if (!instance) {
          throw new Error("No Ditty_Ticker applied to this element.");
        }
        if (typeof instance[options] === "function" && options[0] !== "_") {
          returns = instance[options].apply(instance, [].slice.call(args, 1));
        } else {
          error = true;
        }
      });

      if (error) {
        throw new Error('No method "' + options + '" in Ditty_Ticker.');
      }

      return returns !== undefined ? returns : this;
    }
  };

  $.ditty_ticker = {};
  $.ditty_ticker.defaults = defaults;
})(jQuery);

/**
 * Update item layout css
 *
 * @since    3.0
 * @return   null
 */
function dittyLayoutCss(layoutCss, layoutId, updateCSS) {
  var $styles = jQuery("style#ditty-layout--" + layoutId);
  if (undefined === $styles[0]) {
    $styles = jQuery('<style id="ditty-layout--' + layoutId + '"></style>');
    jQuery("head").append($styles);
    updateCSS = "update";
  }
  if ("update" === updateCSS) {
    layoutCss = layoutCss.replace("&gt;", ">");
    $styles.html(layoutCss);
  }
}

/**
 * Update item display css
 *
 * @since    3.0
 * @return   null
 */
function dittyDisplayCss(displayCss, displayId) {
  var $styles = jQuery("style#ditty-display--" + displayId);
  if (undefined === $styles[0]) {
    $styles = jQuery('<style id="ditty-display--' + displayId + '"></style>');
    jQuery("head").append($styles);
  }
  displayCss = displayCss.replace("&gt;", ">");
  $styles.html(displayCss);
}

/**
 * Update items
 *
 * @since    3.0.33
 * @return   null
 */
function dittyGetUpdatedItemData(prevItems, newItems, type = "replace") {
  const prevGroupedItems = prevItems.reduce((items, item) => {
    const index = items.findIndex((i) => {
      return i.id === item.id;
    });
    if (index < 0) {
      items.push({
        id: item.id,
        items: [item],
      });
    } else {
      items[index].items.push(item);
    }
    return items;
  }, []);

  const newGroupedItems = newItems.reduce((items, item) => {
    const index = items.findIndex((i) => {
      return i.id === item.id;
    });
    item.updated = "updated";
    if (index < 0) {
      items.push({
        id: item.id,
        items: [item],
      });
    } else {
      items[index].items.push(item);
    }
    return items;
  }, []);

  let flattenedItems;
  if ("update" === type) {
    const updatedGroupedItems = newGroupedItems.reduce((groups, newItems) => {
      const index = groups.findIndex((group) => {
        return group.id === newItems.id;
      });
      if (index < 0) {
        groups.push(newItems);
      } else {
        groups[index] = newItems;
      }
      return groups;
    }, prevGroupedItems);
    flattenedItems = updatedGroupedItems.reduce((items, group) => {
      return [...items, ...group.items];
    }, []);
  } else {
    flattenedItems = newGroupedItems.reduce((items, group) => {
      return [...items, ...group.items];
    }, []);
  }
  const updatedIndexes = [];
  const updatedItems = flattenedItems.map((item, index) => {
    if (item.updated) {
      updatedIndexes.push(index);
      delete item.updated;
    } else if (typeof prevItems[index] === "undefined") {
      updatedIndexes.push(index);
    } else if (String(prevItems[index].uniq_id) !== String(item.uniq_id)) {
      updatedIndexes.push(index);
    }
    return item;
  });

  return {
    updatedItems: updatedItems,
    updatedIndexes: updatedIndexes,
  };
}

/**
 * Update items
 *
 * @since    3.0.10
 * @return   null
 */
function dittyUpdateItems(itemSwaps) {
  var animationSpeed = 500;

  jQuery.each(itemSwaps, function (index, data) {
    var $current = data.currentItem,
      $new = data.newItem;

    $current.wrap(
      '<div class="ditty-update-wrapper" style="position: relative;overflow: hidden;"></div>'
    );
    var $updateWrapper = $current.parent(),
      newStyle = $new.attr("style");

    $updateWrapper.stop().css({
      height: $current.outerHeight(),
    });
    $current.stop().css({
      position: "absolute",
      top: 0,
      left: 0,
      width: "100%",
    });
    $new.stop().css({
      position: "absolute",
      top: 0,
      left: 0,
      width: "100%",
      opacity: 0,
    });
    $current.after($new);

    $current.stop().animate(
      {
        opacity: 0,
      },
      animationSpeed * 0.75,
      "linear"
    );

    $new.stop().animate(
      {
        opacity: 1,
      },
      animationSpeed * 0.75,
      "linear"
    );

    $updateWrapper.stop().animate(
      {
        height: $new.outerHeight(),
      },
      animationSpeed,
      "easeOutQuint",
      function () {
        $updateWrapper.removeAttr("style");
        $current.unwrap();
        $current.remove();
        if (newStyle) {
          $new.attr("style", newStyle);
        } else {
          $new.removeAttr("style");
        }
        if ($new.hasClass("ditty-temp-item")) {
          $new.remove();
        }
      }
    );
  });
}

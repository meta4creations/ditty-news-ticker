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
 * Configure the order of items
 *
 * @since    3.1.18
 * @return   null
 */
function dittyOrderItemGroup(items, settings) {
  const orderby = settings.orderby ? settings.orderby : "default";
  const order = settings.order ? settings.order : "desc";

  let sortedItems = items;
  switch (orderby) {
    case "timestamp":
      sortedItems = items.sort(
        (a, b) => new Date(b.timestamp_iso) - new Date(a.timestamp_iso)
      );
      break;
    case "random":
      for (let i = sortedItems.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        const temp = sortedItems[i];
        sortedItems[i] = sortedItems[j];
        sortedItems[j] = temp;
      }
      break;
    default:
      break;
  }

  if ("asc" === order) {
    sortedItems.reverse();
  }

  return sortedItems;
}
function dittyOrderItems(items, settings) {
  const parentItems = [];
  const childGroups = [];
  items.map((item) => {
    if (!item.parent_id || "0" === String(item.parent_id)) {
      parentItems.push(item);
    } else {
      if (!childGroups[item.parent_id]) {
        childGroups[item.parent_id] = [];
      }
      childGroups[item.parent_id].push(item);
    }
  });

  const sortedParentItems = dittyOrderItemGroup(parentItems, settings);
  const sortedChildGroups = childGroups.map((group) => {
    return dittyOrderItemGroup(group, settings);
  });

  const sortedItems = sortedParentItems.reduce((itemsList, item) => {
    itemsList.push(item);
    if (sortedChildGroups[item.id]) {
      itemsList = [...itemsList, ...sortedChildGroups[item.id]];
    }
    return itemsList;
  }, []);

  return sortedItems;
}

/**
 * Update items
 *
 * @since    3.1.19
 * @return   null
 */
function dittyGetUpdatedItemData(prevItems, newItems) {
  const updatedIndexes = [];
  const updatedItems = newItems.map((item, index) => {
    if (typeof prevItems[index] === "undefined") {
      updatedIndexes.push(index);
    } else if (String(prevItems[index].uniq_id) !== String(item.uniq_id)) {
      updatedIndexes.push(index);
    } else if (String(prevItems[index].html) !== String(item.html)) {
      updatedIndexes.push(index);
    } else if (String(prevItems[index].css) !== String(item.css)) {
      updatedIndexes.push(index);
    }
    return item;
  });

  return {
    updatedItems: updatedItems,
    updatedIndexes: updatedIndexes,
  };
}
// function dittyGetUpdatedItemData(prevItems, newItems) {
//   const newGroupedItems = newItems.reduce((items, item) => {
//     const index = items.findIndex((i) => {
//       return i.id === item.id;
//     });
//     item.updated = "updated";
//     if (index < 0) {
//       items.push({
//         id: item.id,
//         items: [item],
//       });
//     } else {
//       items[index].items.push(item);
//     }
//     return items;
//   }, []);

//   const flattenedItems = newGroupedItems.reduce((items, group) => {
//     return [...items, ...group.items];
//   }, []);
//   const updatedIndexes = [];
//   const updatedItems = flattenedItems.map((item, index) => {
//     if (item.updated) {
//       updatedIndexes.push(index);
//       delete item.updated;
//     } else if (typeof prevItems[index] === "undefined") {
//       updatedIndexes.push(index);
//     } else if (String(prevItems[index].uniq_id) !== String(item.uniq_id)) {
//       updatedIndexes.push(index);
//     }
//     return item;
//   });

//   return {
//     updatedItems: updatedItems,
//     updatedIndexes: updatedIndexes,
//   };
// }

/**
 * Update items
 *
 * @since    3.0.10
 * @return   null
 */
function dittyUpdateItems(itemSwaps, swapType = "animate") {
  var animationSpeed = 500;

  jQuery.each(itemSwaps, function (index, data) {
    var $current = data.currentItem,
      $new = data.newItem;

    var $updateWrapper = $current.parent();

    if ("static" === swapType) {
      $current.after($new);
      $current.remove();
    } else {
      var newStyle = $new.attr("style");
      $current.wrap(
        '<div class="ditty-update-wrapper" style="position: relative;overflow: hidden;"></div>'
      );
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
    }
  });
}

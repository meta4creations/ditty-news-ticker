/**
 * Limit a value to characters that are safe in an element id and css selector
 *
 * @since    3.1.72
 * @return   string
 */
function dittySanitizeCssId(id) {
  return String(id === undefined || id === null ? "" : id).replace(
    /[^a-zA-Z0-9_-]/g,
    ""
  );
}

/**
 * Remove characters that could break out of a css declaration
 *
 * @since    3.1.72
 * @return   string
 */
function dittySanitizeCssValue(value) {
  return String(value === undefined || value === null ? "" : value)
    .replace(/["'`;{}<>\\]/g, "")
    .trim();
}

/**
 * Limit a font family to the characters used by font names
 *
 * @since    3.1.72
 * @return   string
 */
function dittySanitizeFontFamily(font) {
  return String(font === undefined || font === null ? "" : font)
    .replace(/[^a-zA-Z0-9 _-]/g, "")
    .trim();
}

function dittyLoadGoogleFont(font, weight = 400) {
  const family = dittySanitizeFontFamily(font);
  if ("" === family) {
    return;
  }

  const fontId = family.replace(/\s+/g, "-").toLowerCase();
  const fontWeight = String(weight).replace(/[^0-9]/g, "") || "400";

  let link = document.getElementById(`ditty-google-font--${fontId}`);
  let weights = [];
  let updateFont = false;

  if (link && link.dataset.weights) {
    weights = link.dataset.weights
      .split(/[;,]/)
      .map((loadedWeight) => loadedWeight.replace(/[^0-9]/g, ""))
      .filter((loadedWeight) => "" !== loadedWeight);
  }

  if (!weights.includes(fontWeight)) {
    weights.push(fontWeight);
    updateFont = true;
  }

  if (updateFont) {
    if (link) {
      link.remove();
    }

    link = document.createElement("link");
    link.id = `ditty-google-font--${fontId}`;
    link.rel = "stylesheet";
    link.dataset.weights = weights.join(";");
    link.href = `https://fonts.googleapis.com/css?family=${encodeURIComponent(
      family
    )}:wght@0,${weights.join(";0,")}`;

    document.head.appendChild(link);
  }
}

/**
 * Update item layout css
 *
 * @since    3.0
 * @return   null
 */
function dittyLayoutCss(layoutCss, layoutId, updateCSS) {
  const styleId = `ditty-layout--${dittySanitizeCssId(layoutId)}`;
  let styles = document.getElementById(styleId);
  if (!styles) {
    styles = document.createElement("style");
    styles.id = styleId;
    document.head.appendChild(styles);
    updateCSS = "update";
  }
  if ("update" === updateCSS) {
    // Set as text so the contents are never parsed as html
    styles.textContent = String(layoutCss || "").replace("&gt;", ">");
  }
}

/**
 * Update item display css
 *
 * @since    3.0
 * @return   null
 */
function dittyDisplayCss(displayCss, displayId) {
  const styleId = `ditty-display--${dittySanitizeCssId(displayId)}`;
  let styles = document.getElementById(styleId);
  if (!styles) {
    styles = document.createElement("style");
    styles.id = styleId;
    document.head.appendChild(styles);
  }
  // Set as text so the contents are never parsed as html
  styles.textContent = String(displayCss || "").replace("&gt;", ">");
}

/**
 * Update item display css
 *
 * @since    3.1.24
 * @return   null
 */
function dittyTypographyCss(settings) {
  const fontFamily = dittySanitizeFontFamily(settings.fontFamily);
  const fontTypeface = dittySanitizeFontFamily(settings.fontTypeface);
  const fontWeight = dittySanitizeCssValue(settings.fontWeight);
  const fontSize = dittySanitizeCssValue(settings.fontSize);
  const lineHeight = dittySanitizeCssValue(settings.lineHeight);
  const letterSpacing = dittySanitizeCssValue(settings.letterSpacing);

  let css = "";
  if ("" !== fontFamily) {
    css += `font-family:${fontFamily}`;
    if ("" !== fontTypeface) {
      css += `, ${fontTypeface}`;
    }
    css += `;`;
  }
  if ("" !== fontWeight) {
    css += `font-weight:${fontWeight};`;
  }
  if ("" !== fontSize) {
    css += `font-size:${fontSize};`;
  }
  if ("" !== lineHeight) {
    css += `line-height:${lineHeight};`;
  }
  if ("" !== letterSpacing) {
    css += `letter-spacing:${letterSpacing};`;
  }
  return css;
}

/**
 * Update item display css
 *
 * @since    3.1.24
 * @return   null
 */
function dittyRenderDisplayCss(settings, displayId) {
  const cssPrefix = `.ditty[data-display="${dittySanitizeCssId(
    settings.display
  )}"]`;

  const titleColor = dittySanitizeCssValue(settings.titleColor);
  const titleLinkColor = dittySanitizeCssValue(settings.titleLinkColor);
  const itemTextColor = dittySanitizeCssValue(settings.itemTextColor);
  const itemLinkColor = dittySanitizeCssValue(settings.itemLinkColor);

  let css = "";

  // Title CSS
  const titleFont = settings.titleTypography ? settings.titleTypography : {};
  let titleFontCss = dittyTypographyCss(titleFont);
  titleFontCss += `margin:0;padding:0;`;
  if ("" !== titleColor) {
    titleFontCss += `color:${titleColor};`;
  }
  if ("" !== titleFontCss) {
    css += `${cssPrefix} .ditty__title__element{${titleFontCss};}`;
  }
  if ("" !== titleColor) {
    css += `${cssPrefix} .ditty__title__element *{color:${titleColor};}`;
  }
  if ("" !== titleLinkColor) {
    css += `${cssPrefix} .ditty__title__element a{color:${titleLinkColor};}`;
  }
  if (
    titleFont.fontType &&
    "google" === titleFont.fontType &&
    dittyVars.googleFonts
  ) {
    dittyLoadGoogleFont(titleFont.fontFamily, titleFont.fontWeight);
  }

  // Item CSS
  const itemFont = settings.itemTypography ? settings.itemTypography : {};
  let itemFontCss = dittyTypographyCss(itemFont);
  if ("" !== itemTextColor) {
    itemFontCss += `color:${itemTextColor};`;
  }
  if ("" !== itemFontCss) {
    css += `${cssPrefix} .ditty-item__elements{${itemFontCss};}`;
  }
  if ("" !== itemLinkColor) {
    css += `${cssPrefix} .ditty-item__elements a{color:${itemLinkColor};}`;
  }
  if (
    itemFont.fontType &&
    "google" === itemFont.fontType &&
    dittyVars.googleFonts
  ) {
    dittyLoadGoogleFont(itemFont.fontFamily, itemFont.fontWeight);
  }
  dittyDisplayCss(css, displayId);
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
    case "alphabetical":
      sortedItems = [...items].sort((a, b) => {
        const getSortText = (item) => {
          const raw =
            item.sort_text !== undefined
              ? String(item.sort_text || "")
              : (item.html || "").replace(/<[^>]*>/g, " ");
          return raw.replace(/\s+/g, " ").trim().toLowerCase();
        };
        return getSortText(a).localeCompare(getSortText(b));
      });
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

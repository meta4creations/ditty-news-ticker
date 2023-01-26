/**
 * Update the title element
 * @param {element} $ditty
 * @param {object} settings
 */
export function updateTitleElement($ditty, settings, type) {
  const $titleContents = $ditty.getElementsByClassName(
    "ditty__title__contents"
  )[0];
  const $titleElement = $ditty.getElementsByClassName(
    "ditty__title__element"
  )[0];

  const $newTitleElement = document.createElement(settings["titleElement"]);
  $newTitleElement.classList.add("ditty__title__element");
  $newTitleElement.innerHTML = $titleElement.innerHTML;

  $titleElement.remove();
  $titleContents.append($newTitleElement);
}

/**
 * Update the display title styles
 * @param {int} display
 * @param {object} settings
 * @param {string} type
 */
function displayTitleStyles(settings, display, type) {
  let styles = "";
  styles += `.ditty[data-display="${display}"] .ditty__title {`;
  styles +=
    "" != settings["titleBgColor"]
      ? `background-color:${settings["titleBgColor"]};`
      : "";
  styles +=
    "" != settings["titleMargin"]["marginTop"]
      ? `margin-top:${settings["titleMargin"]["marginTop"]};`
      : "";
  styles +=
    "" != settings["titleMargin"]["marginRight"]
      ? `margin-right:${settings["titleMargin"]["marginRight"]};`
      : "";
  styles +=
    "" != settings["titleMargin"]["marginBottom"]
      ? `margin-bottom:${settings["titleMargin"]["marginBottom"]};`
      : "";
  styles +=
    "" != settings["titleMargin"]["marginLeft"]
      ? `margin-left:${settings["titleMargin"]["marginLeft"]};`
      : "";
  styles +=
    "" != settings["titlePadding"]["paddingTop"]
      ? `padding-top:${settings["titlePadding"]["paddingTop"]};`
      : "";
  styles +=
    "" != settings["titlePadding"]["paddingRight"]
      ? `padding-right:${settings["titlePadding"]["paddingRight"]};`
      : "";
  styles +=
    "" != settings["titlePadding"]["paddingBottom"]
      ? `padding-bottom:${settings["titlePadding"]["paddingBottom"]};`
      : "";
  styles +=
    "" != settings["titlePadding"]["paddingLeft"]
      ? `padding-left:${settings["titlePadding"]["paddingLeft"]};`
      : "";
  if ("none" != settings["titleBorderStyle"]) {
    styles += `border-style:${settings["titleBorderStyle"]};`;
    styles +=
      "" != settings["titleBorderColor"]
        ? `border-color:${settings["titleBorderColor"]};`
        : "";
    styles +=
      "" != settings["titleBorderWidth"]["borderTopWidth"]
        ? `border-top-width:${settings["titleBorderWidth"]["borderTopWidth"]};`
        : "";
    styles +=
      "" != settings["titleBorderWidth"]["borderRightWidth"]
        ? `border-right-width:${settings["titleBorderWidth"]["borderRightWidth"]};`
        : "";
    styles +=
      "" != settings["titleBorderWidth"]["borderBottomWidth"]
        ? `border-bottom-width:${settings["titleBorderWidth"]["borderBottomWidth"]};`
        : "";
    styles +=
      "" != settings["titleBorderWidth"]["borderLeftWidth"]
        ? `border-left-width:${settings["titleBorderWidth"]["borderLeftWidth"]};`
        : "";
  }
  styles +=
    "" != settings["titleBorderRadius"]["borderTopLeftRadius"]
      ? `border-top-left-radius:${settings["titleBorderRadius"]["borderTopLeftRadius"]};`
      : "";
  styles +=
    "" != settings["titleBorderRadius"]["borderTopRightRadius"]
      ? `border-top-right-radius:${settings["titleBorderRadius"]["borderTopRightRadius"]};`
      : "";
  styles +=
    "" != settings["titleBorderRadius"]["borderBottomLeftRadius"]
      ? `border-bottom-left-radius:${settings["titleBorderRadius"]["borderBottomLeftRadius"]};`
      : "";
  styles +=
    "" != settings["titleBorderRadius"]["borderBottomRightRadius"]
      ? `border-bottom-right-radius:${settings["titleBorderRadius"]["borderBottomRightRadius"]};`
      : "";
  styles += "}";
  styles += `.ditty[data-display="${display}"] .ditty__title__element {`;
  styles +=
    "" != settings["titleColor"] ? `color:${settings["titleColor"]};` : "";
  styles +=
    "" != settings["titleFontSize"]
      ? `font-size:${settings["titleFontSize"]};`
      : "";
  styles +=
    "" != settings["titleLineHeight"]
      ? `line-height:${settings["titleLineHeight"]};`
      : "";
  styles += "}";

  return window.dittyHooks.applyFilters(
    "dittyDisplayTitleStyles",
    styles,
    settings,
    display,
    type
  );
}

/**
 * Update the display container styles
 * @param {int} display
 * @param {object} settings
 * @param {string} type
 */
function displayContainerStyles(settings, display, type) {
  let styles = "";
  styles += `.ditty[data-display="${display}"] {`;
  styles +=
    "" != settings["maxWidth"] ? `max-width:${settings["maxWidth"]};` : "";
  styles +=
    "" != settings["bgColor"] ? `background-color:${settings["bgColor"]};` : "";
  styles +=
    "" != settings["padding"]["paddingTop"]
      ? `padding-top:${settings["padding"]["paddingTop"]};`
      : "";
  styles +=
    "" != settings["padding"]["paddingRight"]
      ? `padding-right:${settings["padding"]["paddingRight"]};`
      : "";
  styles +=
    "" != settings["padding"]["paddingBottom"]
      ? `padding-bottom:${settings["padding"]["paddingBottom"]};`
      : "";
  styles +=
    "" != settings["padding"]["paddingLeft"]
      ? `padding-left:${settings["padding"]["paddingLeft"]};`
      : "";
  styles +=
    "" != settings["margin"]["marginTop"]
      ? `margin-top:${settings["margin"]["marginTop"]};`
      : "";
  styles +=
    "" != settings["margin"]["marginRight"]
      ? `margin-right:${settings["margin"]["marginRight"]};`
      : "";
  styles +=
    "" != settings["margin"]["marginBottom"]
      ? `margin-bottom:${settings["margin"]["marginBottom"]};`
      : "";
  styles +=
    "" != settings["margin"]["marginLeft"]
      ? `margin-left:${settings["margin"]["marginLeft"]};`
      : "";
  if ("none" != settings["borderStyle"]) {
    styles += `border-style:${settings["borderStyle"]};`;
    styles +=
      "" != settings["borderColor"]
        ? `border-color:${settings["borderColor"]};`
        : "";
    styles +=
      "" != settings["borderWidth"]["borderTopWidth"]
        ? `border-top-width:${settings["borderWidth"]["borderTopWidth"]};`
        : "";
    styles +=
      "" != settings["borderWidth"]["borderRightWidth"]
        ? `border-right-width:${settings["borderWidth"]["borderRightWidth"]};`
        : "";
    styles +=
      "" != settings["borderWidth"]["borderBottomWidth"]
        ? `border-bottom-width:${settings["borderWidth"]["borderBottomWidth"]};`
        : "";
    styles +=
      "" != settings["borderWidth"]["borderLeftWidth"]
        ? `border-left-width:${settings["borderWidth"]["borderLeftWidth"]};`
        : "";
  }
  styles +=
    "" != settings["borderRadius"]["borderTopLeftRadius"]
      ? `border-top-left-radius:${settings["borderRadius"]["borderTopLeftRadius"]};`
      : "";
  styles +=
    "" != settings["borderRadius"]["borderTopRightRadius"]
      ? `border-top-right-radius:${settings["borderRadius"]["borderTopRightRadius"]};`
      : "";
  styles +=
    "" != settings["borderRadius"]["borderBottomLeftRadius"]
      ? `border-bottom-left-radius:${settings["borderRadius"]["borderBottomLeftRadius"]};`
      : "";
  styles +=
    "" != settings["borderRadius"]["borderBottomRightRadius"]
      ? `border-bottom-right-radius:${settings["borderRadius"]["borderBottomRightRadius"]};`
      : "";
  styles += "}";

  return window.dittyHooks.applyFilters(
    "dittyDisplayContainerStyles",
    styles,
    settings,
    display,
    type
  );
}

/**
 * Update the display content styles
 * @param {int} display
 * @param {object} settings
 * @param {string} type
 */
function displayContentStyles(settings, display, type) {
  let styles = "";
  styles += `.ditty[data-display="${display}"] .ditty__contents {`;
  styles +=
    "" != settings["contentsBgColor"]
      ? `background-color:${settings["contentsBgColor"]};`
      : "";
  styles +=
    "" != settings["contentsPadding"]["paddingTop"]
      ? `padding-top:${settings["contentsPadding"]["paddingTop"]};`
      : "";
  styles +=
    "" != settings["contentsPadding"]["paddingRight"]
      ? `padding-right:${settings["contentsPadding"]["paddingRight"]};`
      : "";
  styles +=
    "" != settings["contentsPadding"]["paddingBottom"]
      ? `padding-bottom:${settings["contentsPadding"]["paddingBottom"]};`
      : "";
  styles +=
    "" != settings["contentsPadding"]["paddingLeft"]
      ? `padding-left:${settings["contentsPadding"]["paddingLeft"]};`
      : "";
  if ("none" != settings["contentsBorderStyle"]) {
    styles += `border-style:${settings["contentsBorderStyle"]};`;
    styles +=
      "" != settings["contentsBorderColor"]
        ? `border-color:${settings["contentsBorderColor"]};`
        : "";
    styles +=
      "" != settings["contentsBorderWidth"]["borderTopWidth"]
        ? `border-top-width:${settings["contentsBorderWidth"]["borderTopWidth"]};`
        : "";
    styles +=
      "" != settings["contentsBorderWidth"]["borderRightWidth"]
        ? `border-right-width:${settings["contentsBorderWidth"]["borderRightWidth"]};`
        : "";
    styles +=
      "" != settings["contentsBorderWidth"]["borderBottomWidth"]
        ? `border-bottom-width:${settings["contentsBorderWidth"]["borderBottomWidth"]};`
        : "";
    styles +=
      "" != settings["contentsBorderWidth"]["borderLeftWidth"]
        ? `border-left-width:${settings["contentsBorderWidth"]["borderLeftWidth"]};`
        : "";
  }
  styles +=
    "" != settings["contentsBorderRadius"]["borderTopLeftRadius"]
      ? `border-top-left-radius:${settings["contentsBorderRadius"]["borderTopLeftRadius"]};`
      : "";
  styles +=
    "" != settings["contentsBorderRadius"]["borderTopRightRadius"]
      ? `border-top-right-radius:${settings["contentsBorderRadius"]["borderTopRightRadius"]};`
      : "";
  styles +=
    "" != settings["contentsBorderRadius"]["borderBottomLeftRadius"]
      ? `border-bottom-left-radius:${settings["contentsBorderRadius"]["borderBottomLeftRadius"]};`
      : "";
  styles +=
    "" != settings["contentsBorderRadius"]["borderBottomRightRadius"]
      ? `border-bottom-right-radius:${settings["contentsBorderRadius"]["borderBottomRightRadius"]};`
      : "";
  styles += "}";

  return window.dittyHooks.applyFilters(
    "dittyDisplayContentStyles",
    styles,
    settings,
    display,
    type
  );
}

/**
 * Update the display item styles
 * @param {int} display
 * @param {object} settings
 * @param {string} type
 */
function displayItemStyles(settings, display, type) {
  let styles = "";
  styles += `.ditty[data-display="${display}"] .ditty-item__elements {`;
  styles +=
    "" != settings["itemTextColor"]
      ? `color:${settings["itemTextColor"]};`
      : "";
  styles +=
    "" != settings["itemBgColor"]
      ? `background-color:${settings["itemBgColor"]};`
      : "";
  styles +=
    "" != settings["itemPadding"]["paddingTop"]
      ? `padding-top:${settings["itemPadding"]["paddingTop"]};`
      : "";
  styles +=
    "" != settings["itemPadding"]["paddingRight"]
      ? `padding-right:${settings["itemPadding"]["paddingRight"]};`
      : "";
  styles +=
    "" != settings["itemPadding"]["paddingBottom"]
      ? `padding-bottom:${settings["itemPadding"]["paddingBottom"]};`
      : "";
  styles +=
    "" != settings["itemPadding"]["paddingLeft"]
      ? `padding-left:${settings["itemPadding"]["paddingLeft"]};`
      : "";
  if ("none" != settings["itemBorderStyle"]) {
    styles += `border-style:${settings["itemBorderStyle"]};`;
    styles +=
      "" != settings["itemBorderColor"]
        ? `border-color:${settings["itemBorderColor"]};`
        : "";
    styles +=
      "" != settings["itemBorderWidth"]["borderTopWidth"]
        ? `border-top-width:${settings["itemBorderWidth"]["borderTopWidth"]};`
        : "";
    styles +=
      "" != settings["itemBorderWidth"]["borderRightWidth"]
        ? `border-right-width:${settings["itemBorderWidth"]["borderRightWidth"]};`
        : "";
    styles +=
      "" != settings["itemBorderWidth"]["borderBottomWidth"]
        ? `border-bottom-width:${settings["itemBorderWidth"]["borderBottomWidth"]};`
        : "";
    styles +=
      "" != settings["itemBorderWidth"]["borderLeftWidth"]
        ? `border-left-width:${settings["itemBorderWidth"]["borderLeftWidth"]};`
        : "";
  }
  styles +=
    "" != settings["itemBorderRadius"]["borderTopLeftRadius"]
      ? `border-top-left-radius:${settings["itemBorderRadius"]["borderTopLeftRadius"]};`
      : "";
  styles +=
    "" != settings["itemBorderRadius"]["borderTopRightRadius"]
      ? `border-top-right-radius:${settings["itemBorderRadius"]["borderTopRightRadius"]};`
      : "";
  styles +=
    "" != settings["itemBorderRadius"]["borderBottomLeftRadius"]
      ? `border-bottom-left-radius:${settings["itemBorderRadius"]["borderBottomLeftRadius"]};`
      : "";
  styles +=
    "" != settings["itemBorderRadius"]["borderBottomRightRadius"]
      ? `border-bottom-right-radius:${settings["itemBorderRadius"]["borderBottomRightRadius"]};`
      : "";
  styles +=
    "" != settings["itemMaxWidth"]
      ? `max-width:${settings["itemMaxWidth"]};`
      : "";
  styles +=
    "nowrap" == settings["itemElementsWrap"]
      ? "white-space:nowrap;"
      : "white-space:normal;";
  styles += "}";

  return window.dittyHooks.applyFilters(
    "dittyDisplayItemStyles",
    styles,
    settings,
    display,
    type
  );
}

/**
 * Update the display style element
 * @param {int} display
 * @param {object} settings
 * @param {string} type
 */
export function updateDisplayStyles(settings, display, type) {
  const styleEl = document.getElementById(`ditty-display--${display}`);

  console.log("display", display);
  console.log("type", type);

  let styles = "";
  styles += displayTitleStyles(settings, display, type);
  styles += displayContainerStyles(settings, display, type);
  styles += displayContentStyles(settings, display, type);
  styles += displayItemStyles(settings, display, type);

  styleEl.innerHTML = window.dittyHooks.applyFilters(
    "dittyDisplayStyles",
    styles,
    settings,
    display,
    type
  );
}

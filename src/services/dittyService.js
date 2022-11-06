export const initializeDitty = (dittyEl, displayType, args) => {
  jQuery(dittyEl)["ditty_" + displayType](args);
};

export const setDittyDisplayTemplate = (dittyEl, display, prevDisplay) => {
  if (prevDisplay.type === display.type) {
    dittyEl["_ditty_" + display.type].options(display.settings);
  } else {
    const oldDitty = dittyEl["_ditty_" + prevDisplay.type];
    const args = display.settings;
    args["id"] = oldDitty.options("id");
    args["display"] = display.type;
    args["title"] = oldDitty.options("title");
    args["status"] = oldDitty.options("status");
    args["items"] = oldDitty.options("items");

    oldDitty.destroy();

    jQuery(dittyEl)["ditty_" + display.type](args);
  }
};

export const updateDisplayOptions = (dittyEl, displayType, option, value) => {
  dittyEl["_ditty_" + displayType].options(option, value);
};

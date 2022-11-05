export const initializeDitty = (dittyEl, displayType, args) => {
  jQuery(dittyEl)["ditty_" + displayType](args);
};

export const setDittyDisplayTemplate = (dittyEl, oldDisplay, newDisplay) => {
  if (oldDisplay.type === newDisplay.type) {
    dittyEl["_ditty_" + newDisplay.type].options(newDisplay.settings);
  } else {
    const oldDitty = dittyEl["_ditty_" + oldDisplay.type];
    const args = newDisplay.settings;
    args["id"] = oldDitty.options("id");
    args["display"] = newDisplay.type;
    args["title"] = oldDitty.options("title");
    args["status"] = oldDitty.options("status");
    args["items"] = oldDitty.options("items");

    oldDitty.destroy();

    jQuery(dittyEl)["ditty_" + newDisplay.type](args);
  }
};

export const updateDisplayOptions = (dittyEl, displayType, option, value) => {
  dittyEl["_ditty_" + displayType].options(option, value);
};

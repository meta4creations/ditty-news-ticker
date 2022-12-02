export const initializeDitty = (dittyEl, displayType, args) => {
  jQuery(dittyEl)["ditty_" + displayType](args);
};

export const updateDittyDisplayTemplate = (dittyEl, display) => {
  const prevType = dittyEl.dataset.type;
  if (prevType === display.type) {
    dittyEl["_ditty_" + display.type].options(display.settings);
  } else {
    const oldDitty = dittyEl["_ditty_" + prevType];
    const args = display.settings;
    args["id"] = oldDitty.options("id");
    args["display"] = display.type;
    args["title"] = oldDitty.options("title");
    args["status"] = oldDitty.options("status");
    args["items"] = oldDitty.options("items");

    oldDitty.destroy();

    jQuery(dittyEl)["ditty_" + display.type](args);
    dittyEl.dataset.type = display.type;
  }
};

export const updateDittyDisplayType = (dittyEl, type) => {
  const prevType = dittyEl.dataset.type;
  if (prevType !== type) {
    const oldDitty = dittyEl["_ditty_" + prevType];
    const args = oldDitty.options();
    oldDitty.destroy();

    jQuery(dittyEl)["ditty_" + type](args);
    dittyEl.dataset.type = type;
  }
};

export const updateDisplayOptions = (dittyEl, option, value) => {
  const type = dittyEl.dataset.type;
  dittyEl["_ditty_" + type].options(option, value);
};

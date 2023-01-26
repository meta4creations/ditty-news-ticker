import { createHooks } from "@wordpress/hooks";
import DittyDisplay from "./displays/components/dittyDisplay";
import "./displays/css/dittyDisplay.scss";
import { easeOptions, sliderTransitions } from "./utils/helpers";
import { layoutTags } from "./utils/layoutTags";

/**
 * Add ditty global variables for reference
 */
window.dittyRenders = new WeakMap();
window.dittyHooks = createHooks();
window.dittyDisplays = {
  display: DittyDisplay,
};

const editorHooks = createHooks();
dittyEditor.addFilter = (action, callable, priority) => {
  editorHooks.addFilter(action, "dittyEditor", callable, priority);
};
dittyEditor.applyFilters = editorHooks.applyFilters;
dittyEditor.helpers = {
  easeOptions,
  sliderTransitions,
};
dittyEditor.layoutTags = layoutTags;
dittyEditor.registerItemType = (itemType) => {
  dittyEditor.addFilter("dittyItemTypes", (itemTypes) => {
    itemTypes.push(itemType);
    return itemTypes;
  });
};
dittyEditor.registerDisplayType = (displayType) => {
  dittyEditor.addFilter("dittyDisplayTypes", (displayTypes) => {
    displayTypes.push(displayType);
    return displayTypes;
  });
};

/**
 * Load the Ditty on page load
 */
window.onload = function () {
  document.querySelectorAll(".ditty").forEach((dittyEl) => {
    var type = dittyEl.dataset.type;
    if (!window.dittyDisplays[type]) {
      return;
    }

    const settings = dittyEl.dataset.settings
      ? JSON.parse(dittyEl.dataset.settings)
      : {};

    const args = {
      element: dittyEl,
      display: dittyEl.dataset.display,
      type: type,
      //items: JSON.parse(dittyEl.dataset.items),
      ...settings,
    };
    const ditty = new window.dittyDisplays[type](args);

    window.dittyRenders.set(dittyEl, ditty);
  });
};

/**
 * Sample event to modify a Ditty
 */
document.addEventListener("click", clickHandle);
function clickHandle(e) {
  const el = e.target;
  if (el.closest(".ditty__title")) {
    e.preventDefault();
    const dittyEl = el.closest(".ditty");
    const ditty = window.dittyRenders.get(dittyEl);
    const randomColor = Math.floor(Math.random() * 16777215).toString(16);

    const options = {
      titleBgColor: `#${randomColor}`,
      titleElement: "h1",
    };
    ditty.options(options);
  }
}

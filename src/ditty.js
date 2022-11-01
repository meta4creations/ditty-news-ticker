import { createHooks } from "@wordpress/hooks";
import DittyDisplay from "./displays/components/dittyDisplay";
import "./displays/css/dittyDisplay.scss";

/**
 * Add ditty global variables for reference
 */
window.ditty = new WeakMap();
window.dittyHooks = createHooks();
window.dittyDisplays = {
  display: DittyDisplay,
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

    window.ditty.set(dittyEl, ditty);
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
    const ditty = window.ditty.get(dittyEl);
    const randomColor = Math.floor(Math.random() * 16777215).toString(16);

    const options = {
      titleBgColor: `#${randomColor}`,
      titleElement: "h1",
    };
    ditty.options(options);
  }
}

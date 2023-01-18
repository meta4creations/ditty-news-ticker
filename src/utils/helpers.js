import { __ } from "@wordpress/i18n";

/**
 * Return easing options
 * @returns array
 */
const getEaseOptions = () => {
  const eases = [
    "linear",
    "swing",
    "jswing",
    "easeInQuad",
    "easeInCubic",
    "easeInQuart",
    "easeInQuint",
    "easeInSine",
    "easeInExpo",
    "easeInCirc",
    "easeInElastic",
    "easeInBack",
    "easeInBounce",
    "easeOutQuad",
    "easeOutCubic",
    "easeOutQuart",
    "easeOutQuint",
    "easeOutSine",
    "easeOutExpo",
    "easeOutCirc",
    "easeOutElastic",
    "easeOutBack",
    "easeOutBounce",
    "easeInOutQuad",
    "easeInOutCubic",
    "easeInOutQuart",
    "easeInOutQuint",
    "easeInOutSine",
    "easeInOutExpo",
    "easeInOutCirc",
    "easeInOutElastic",
    "easeInOutBack",
    "easeInOutBounce",
  ];
  const easeObject = {};
  for (let i = 0; i < eases.length; i++) {
    easeObject[eases[i]] = eases[i];
  }
  return easeObject;
};
export const easeOptions = getEaseOptions();

function getSliderTransitions() {
  return {
    fade: __("Fade", "ditty-news-ticker"),
    slideLeft: __("Slide Left", "ditty-news-ticker"),
    slideRight: __("Slide Right", "ditty-news-ticker"),
    slideDown: __("Slide Down", "ditty-news-ticker"),
    slideUp: __("Slide Up", "ditty-news-ticker"),
  };
}
export const sliderTransitions = getSliderTransitions();

/**
 * Convert default box controls to custom control keys
 * @returns object
 */
export const convertBoxControlValues = (values, args) => {
  const updatedValues = {};
  for (const [objKey, objValue] of Object.entries(args)) {
    updatedValues[objValue] = values[objKey];
  }
  return updatedValues;
};

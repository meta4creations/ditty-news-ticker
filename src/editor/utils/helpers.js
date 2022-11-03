/**
 * Return easing options
 * @returns array
 */
export const easeOptions = () => {
  const eases = array(
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
    "easeInOutBounce"
  );
  const easeObject = {};
  for (let i = 0; i < eases.length; i++) {
    easeObject[eases[i]] = eases[i];
  }
  return easeObject;
};

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

// utilities/easing.js

const easing = {
  linear: (t) => t,
  swing: (t) => 0.5 - Math.cos(t * Math.PI) / 2,
  jswing: (t) => 0.5 - Math.cos(t * Math.PI) / 2, // Same as swing

  // Ease In Functions
  easeInQuad: (t) => t * t,
  easeInCubic: (t) => t * t * t,
  easeInQuart: (t) => t * t * t * t,
  easeInQuint: (t) => t * t * t * t * t,
  easeInSine: (t) => 1 - Math.cos((t * Math.PI) / 2),
  easeInExpo: (t) => (t === 0 ? 0 : Math.pow(2, 10 * (t - 1))),
  easeInCirc: (t) => 1 - Math.sqrt(1 - Math.pow(t, 2)),
  easeInElastic: (t) =>
    t === 0
      ? 0
      : t === 1
      ? 1
      : -Math.pow(2, 10 * t - 10) *
        Math.sin(((t * 10 - 10.75) * (2 * Math.PI)) / 3),
  easeInBack: (t) => 1.70158 * t * t * t - 1.70158 * t * t,
  easeInBounce: (t) => 1 - easing.easeOutBounce(1 - t),

  // Ease Out Functions
  easeOutQuad: (t) => 1 - (1 - t) * (1 - t),
  easeOutCubic: (t) => 1 - Math.pow(1 - t, 3),
  easeOutQuart: (t) => 1 - Math.pow(1 - t, 4),
  easeOutQuint: (t) => 1 - Math.pow(1 - t, 5),
  easeOutSine: (t) => Math.sin((t * Math.PI) / 2),
  easeOutExpo: (t) => (t === 1 ? 1 : 1 - Math.pow(2, -10 * t)),
  easeOutCirc: (t) => Math.sqrt(1 - Math.pow(t - 1, 2)),
  easeOutElastic: (t) =>
    t === 0
      ? 0
      : t === 1
      ? 1
      : Math.pow(2, -10 * t) * Math.sin(((t * 10 - 0.75) * (2 * Math.PI)) / 3) +
        1,
  easeOutBack: (t) =>
    1 + 1.70158 * Math.pow(t - 1, 3) + 1.70158 * (t - 1) * (t - 1),
  easeOutBounce: (t) => {
    if (t < 1 / 2.75) {
      return 7.5625 * t * t;
    } else if (t < 2 / 2.75) {
      return 7.5625 * (t -= 1.5 / 2.75) * t + 0.75;
    } else if (t < 2.5 / 2.75) {
      return 7.5625 * (t -= 2.25 / 2.75) * t + 0.9375;
    } else {
      return 7.5625 * (t -= 2.625 / 2.75) * t + 0.984375;
    }
  },

  // Ease In Out Functions
  easeInOutQuad: (t) => (t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2),
  easeInOutCubic: (t) =>
    t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2,
  easeInOutQuart: (t) =>
    t < 0.5 ? 8 * t * t * t * t : 1 - Math.pow(-2 * t + 2, 4) / 2,
  easeInOutQuint: (t) =>
    t < 0.5 ? 16 * t * t * t * t * t : 1 - Math.pow(-2 * t + 2, 5) / 2,
  easeInOutSine: (t) => -(Math.cos(Math.PI * t) - 1) / 2,
  easeInOutExpo: (t) =>
    t === 0
      ? 0
      : t === 1
      ? 1
      : t < 0.5
      ? Math.pow(2, 20 * t - 10) / 2
      : (2 - Math.pow(2, -20 * t + 10)) / 2,
  easeInOutCirc: (t) =>
    t < 0.5
      ? (1 - Math.sqrt(1 - Math.pow(2 * t, 2))) / 2
      : (Math.sqrt(1 - Math.pow(-2 * t + 2, 2)) + 1) / 2,
  easeInOutElastic: (t) =>
    t === 0
      ? 0
      : t === 1
      ? 1
      : t < 0.5
      ? -(
          Math.pow(2, 20 * t - 10) *
          Math.sin(((20 * t - 11.125) * (2 * Math.PI)) / 4.5)
        ) / 2
      : (Math.pow(2, -20 * t + 10) *
          Math.sin(((20 * t - 11.125) * (2 * Math.PI)) / 4.5)) /
          2 +
        1,
  easeInOutBack: (t) =>
    t < 0.5
      ? (Math.pow(2 * t, 2) * (1.70158 * 1.525 * 2 * t - 1.70158 * 1.525)) / 2
      : (Math.pow(2 * t - 2, 2) *
          (1.70158 * 1.525 * (t * 2 - 2) + 1.70158 * 1.525) +
          2) /
        2,
  easeInOutBounce: (t) =>
    t < 0.5
      ? (1 - easing.easeOutBounce(1 - 2 * t)) / 2
      : (1 + easing.easeOutBounce(2 * t - 1)) / 2,
};

export default easing;

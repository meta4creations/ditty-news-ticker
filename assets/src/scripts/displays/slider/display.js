window.addEventListener("DOMContentLoaded", () => {
  const selector = ".wp-block-mtphr-ditty-display.dittySlider";
  document.querySelectorAll(selector).forEach((block) => {
    const sliderEl = block.querySelector(".dittySlider__slider");

    // pull everything out of block.dataset (all strings!)
    const ds = block.dataset;

    // parse + coerce types:
    const settings = {
      initial: parseInt(ds.initial, 10),
      autoheight: ds.autoheight === "true",
      loop: ds.loop === "true",
      mode: ds.mode,
      rubberband: ds.rubberband === "true",
      vertical: ds.vertical === "true",
      transitionSpeed: parseInt(ds.animationDuration, 10),
      transitionEase: ds.animationEasing,
      slides: {
        origin: ds.center === "true" ? "center" : "auto",
        perView: parseInt(ds.perView, 10),
        spacing: parseInt(ds.spacing, 10),
      },
      // breakpoints was dumped as JSON
      breakpoints: JSON.parse(ds.breakpoints || "[]"),
    };

    // build your Keen `breakpoints` option
    const keenBreakpoints = settings.breakpoints.reduce((acc, bp) => {
      const mq = `(max-width: ${bp.maxWidth}px)`;
      acc[mq] = {
        slides: {
          origin: bp.center ? "center" : "auto",
          perView: bp.perView,
          spacing: bp.spacing,
        },
      };
      return acc;
    }, {});

    // final options passed to DittySlider
    const sliderOptions = {
      initial: settings.initial,
      autoheight: settings.autoheight,
      loop: settings.loop,
      mode: settings.mode,
      //rubberband: settings.rubberband,
      vertical: settings.vertical,
      transitionSpeed: settings.transitionSpeed,
      transitionEase: settings.transitionEase,
      slides: settings.slides,
      breakpoints: keenBreakpoints,
      selector: ".ditty-item",
    };

    new DittySlider(sliderEl, sliderOptions);
  });
});

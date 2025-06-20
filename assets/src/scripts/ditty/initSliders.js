const initSliders = () => {
  initDisplaySliders();
  initGallerySliders();
};

/**
 * Init display sliders
 */
const initDisplaySliders = () => {
  const selector = ".ditty__contents .dittySlider";
  document.querySelectorAll(selector).forEach((slider) => {
    const sliderEl = slider.querySelector(".dittySlider__slider");

    // pull everything out of slider.dataset (all strings!)
    const ds = slider.dataset;

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
    const breakpoints = settings.breakpoints.reduce((acc, bp) => {
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
      selector: ds.selector,
      initial: settings.initial,
      autoheight: settings.autoheight,
      loop: settings.loop,
      mode: settings.mode,
      //rubberband: settings.rubberband,
      vertical: settings.vertical,
      transitionSpeed: settings.transitionSpeed,
      transitionEase: settings.transitionEase,
      slides: settings.slides,
      breakpoints: breakpoints,
    };

    console.log("sliderOptions", sliderOptions);

    new DittySlider(sliderEl, sliderOptions);
  });
};

/**
 * Init the gallery sliders on items
 */
const initGallerySliders = () => {
  const selector = ".ditty-gallery-slider";
  document.querySelectorAll(selector).forEach((slider) => {
    const sliderEl = slider.querySelector(".dittySlider__slider");

    // final options passed to DittySlider
    const sliderOptions = {
      loop: true,
      autoplay: true,
      slides: {
        perView: 1,
        spacing: 0,
      },
      selector: ".ditty-gallery-item",
    };

    new DittySlider(sliderEl, sliderOptions);
  });

  /**
   * Listen for bullet clicks
   */
  document.addEventListener("click", function (e) {
    // Check if the click happened on or inside a .ditty-item__media__link
    const mediaLink = e.target.closest(".ditty-item__media__link");
    if (!mediaLink) return;

    // Check if the actual clicked target (or any of its parents) is a bullet
    const clickedBullet = e.target.closest(".dittySlider__bullet");
    if (clickedBullet && mediaLink.contains(clickedBullet)) {
      e.preventDefault(); // Prevent following the link
      e.stopPropagation(); // Optional: prevent other click handlers
    }
  });
};

export default initSliders;

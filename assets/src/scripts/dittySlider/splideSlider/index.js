import Splide from "@splidejs/splide";
import "@splidejs/splide/css";

export default class SplideDittySlider {
  constructor(sliderElement, options = {}) {
    this.sliderElement = sliderElement;
    this.options = options;

    const selector = this.options.selector || ".ditty-item";

    // Add 'splide__slide' class to each matched element
    const slides = this.sliderElement.querySelectorAll(selector);
    slides.forEach((slide) => {
      slide.classList.add("splide__slide");
    });

    // Setup default Splide settings
    const defaultSettings = {
      type: "loop",
      autoplay: this.options.autoplay || false,
      interval: this.options.autoplayTimeout || 5000,
      speed: this.options.transitionSpeed || 1000,
      easing: this.options.transitionEase || "cubic-bezier(0.25, 0.46, 0.45, 0.94)",
      perPage: this.options.perPage || 1,
      gap: this.options.spacing || 15,
      heightRatio: this.options.heightRatio || null,
      autoHeight: this.options.autoheight || false,
      arrows: this.options.arrows !== false,
      pagination: this.options.pagination || false,
    };

    this.settings = { ...defaultSettings };
    this.sliderInstance = new Splide(this.sliderElement, this.settings);
    this.sliderInstance.mount();

    console.log("Splide Slider created");
  }

  destroy() {
    if (this.sliderInstance) {
      this.sliderInstance.destroy();
    }
  }
}

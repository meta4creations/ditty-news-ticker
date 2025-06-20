import Swiper from "swiper/bundle";
import "swiper/css/bundle";

export default class SwiperDittySlider {
  constructor(sliderElement, options = {}) {
    this.sliderElement = sliderElement;
    this.options = options;

    const selector = this.options.selector || ".ditty-item";

    // Add 'swiper-slide' class to each matched element
    const slides = this.sliderElement.querySelectorAll(selector);
    slides.forEach((slide) => {
      slide.classList.add("swiper-slide");
    });

    // Setup default Swiper settings
    const defaultSettings = {
      loop: true,
      autoplay: {
        delay: options.autoplayTimeout || 5000,
      },
      slidesPerView: 1,
      spaceBetween: 15,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      on: {
        init: () => console.log("Swiper created"),
      },
    };

    this.settings = { ...defaultSettings };
    this.sliderInstance = new Swiper(this.sliderElement, this.settings);
  }

  destroy() {
    if (this.sliderInstance) {
      this.sliderInstance.destroy(true, true);
    }
  }
}

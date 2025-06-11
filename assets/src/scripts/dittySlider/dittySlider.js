import KeenSlider from "keen-slider";
import adaptiveHeight from "./plugins/adaptiveHeight";
import autoSwitch from "./plugins/autoSwitch";
import imagesLoaded from "./plugins/imagesLoaded";
import navigation from "./plugins/navigation";
import { easing } from "./utilities";
import "keen-slider/keen-slider.min.css";
import "./dittySlider.scss";

class DittySlider {
  constructor(sliderElement, options = {}) {
    // Save the slider element and options
    this.sliderElement = sliderElement;

    const transitionSpeed = options.transitionSpeed || 1000;
    const transitionEase = options.transitionEase || "easeInOutQuint";
    delete options.transitionSpeed;
    delete options.transitionEase;

    const arrowSettings = {
      arrows: false,
      arrowsIconColor: "",
      arrowsBgColor: "",
      arrowsPosition: "center",
      arrowsPadding: {},
      arrowsStatic: 0,
      navPrev: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="24" height="24" fill="currentColor"><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>`,
      navNext: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="24" height="24" fill="currentColor"><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c12.5-12.5 12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>`,
    };

    const bulletsSettings = {
      bullets: false,
      bulletsClickable: true,
      bulletsColor: "",
      bulletsColorActive: "",
      bulletsOverlay: false,
      bulletsPadding: {},
      bulletsPosition: "",
      bulletsSize: "",
      bulletsSpacing: "",
    };

    // Default settings for the slider
    this.defaultSettings = {
      loop: true,
      selector: ".ditty-item",
      defaultAnimation: {
        duration: transitionSpeed,
        easing: easing[transitionEase],
      },
      slides: {
        perView: 2,
        spacing: 15,
      },
      ...arrowSettings,
      ...bulletsSettings,
      created: () => console.log("Slider created"),
    };

    // Merge user-defined options
    this.settings = { ...this.defaultSettings, ...options };

    // Hide all but the first slide before initializing if autoheight is true
    if (this.settings.autoheight) {
      const slides = this.sliderElement.querySelectorAll(".ditty-item");
      slides.forEach((slide, idx) => {
        if (idx !== 0) {
          slide.style.display = "none";
        }
      });
    }

    // Initialize
    this.initSlider();
  }

  initSlider() {
    const plugins = [imagesLoaded, navigation];
    if (this.settings.autoheight) {
      plugins.push(adaptiveHeight);
    }
    if (this.settings.autoplay) {
      plugins.push(autoSwitch);
    }

    this.sliderInstance = new KeenSlider(
      this.sliderElement,
      this.settings,
      plugins
    );
  }

  destroySlider() {
    if (this.sliderInstance) {
      this.sliderInstance.destroy();
    }
  }
}

window.DittySlider = DittySlider;

import KeenSlider from "keen-slider";
import adaptiveHeight from "./plugins/adaptiveHeight";
import autoSwitch from "./plugins/autoSwitch";
import imagesLoaded from "./plugins/imagesLoaded";
import navigation from "./plugins/navigation";
import { easing } from "./utilities";
import "keen-slider/keen-slider.min.css";

export default class KeenDittySlider {
  constructor(sliderElement, options = {}) {
    const sliderEl = sliderElement.querySelector(".dittySlider__slider");

    const transitionSpeed = options.transitionSpeed || 1000;
    const transitionEase = options.transitionEase || "easeInOutQuint";

    delete options.transitionSpeed;
    delete options.transitionEase;

    const defaultSettings = {
      loop: true,
      selector: ".ditty-item",
      autoplayTimeout: 5000,
      defaultAnimation: {
        duration: transitionSpeed,
        easing: easing[transitionEase],
      },
      slides: {
        perView: 2,
        spacing: 15,
      },
      created: () => console.log("Keen Slider created"),
    };

    this.settings = { ...defaultSettings, ...options };

    const plugins = [imagesLoaded, navigation];
    if (this.settings.autoheight) plugins.push(adaptiveHeight);
    if (this.settings.autoplay) plugins.push(autoSwitch);

    this.sliderInstance = new KeenSlider(sliderEl, this.settings, plugins);
  }

  destroy() {
    if (this.sliderInstance) {
      this.sliderInstance.destroy();
    }
  }
}

import SplideDittySlider from "./splideSlider";
import "./dittySlider.scss";

class DittySlider {
  constructor(sliderElement, options = {}) {
    this.sliderElement = sliderElement;
    this.options = options;
    this.instance = null;

    this.initSlider();
  }

  initSlider() {
    this.instance = new SplideDittySlider(this.sliderElement, this.options);
  }

  destroySlider() {
    if (this.instance && typeof this.instance.destroy === "function") {
      this.instance.destroy();
    }
  }
}

window.DittySlider = DittySlider;

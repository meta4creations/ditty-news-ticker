import KeenDittySlider from "./keenSlider";
import SwiperDittySlider from "./swiperSlider";
import "./dittySlider.scss";

class DittySlider {
  constructor(sliderElement, options = {}) {
    this.sliderElement = sliderElement;
    this.options = options;
    this.instance = null;

    this.initSlider();
  }

  initSlider() {
    const type = this.options.type || "keen";
    if (type === "swiper") {
      console.log(this.options.selector);
      this.instance = new SwiperDittySlider(this.sliderElement, this.options);
    } else {
      this.instance = new KeenDittySlider(this.sliderElement, this.options);
    }
  }

  destroySlider() {
    if (this.instance && typeof this.instance.destroy === "function") {
      this.instance.destroy();
    }
  }
}

window.DittySlider = DittySlider;

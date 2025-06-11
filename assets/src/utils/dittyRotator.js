import KeenSlider from "keen-slider";
import "keen-slider/keen-slider.min.css";
import "../assets/css/rotator.scss";

export default class DittyRotator {
  constructor(element, options = {}) {
    this.element = element;
    if (!this.element.classList.contains("keen-slider")) {
      this.element.classList.add("keen-slider");
    }
    this.slides = Array.from(
      this.element.querySelectorAll(".keen-slider__slide")
    );
    this.options = {
      transitionType: "fade",
      transitionEasing: "ease",
      transitionTiming: 300,
      staticHeight: false,
      dynamicHeight: false,
      heightAnimationSpeed: 300,
      heightEasing: "ease",
      arrowNavigation: false,
      bulletNavigation: false,
      ...options,
    };
    this.slider = null;

    this.init();
  }

  init() {
    if (this.options.slides) {
      this.options.slides.forEach((html) => {
        const newSlide = document.createElement("div");
        newSlide.classList.add("keen-slider__slide");
        newSlide.innerHTML = html;
        this.element.appendChild(newSlide);
      });
    }

    this.slider = new KeenSlider(
      this.element,
      {
        loop: true,
        slides: { perView: 1, origin: 0 },
        defaultAnimation: {
          duration: 1000,
        },
        // detailsChanged: (s) => {
        //   s.slides.forEach((element, idx) => {
        //     element.style.opacity = s.track.details.slides[idx].portion;
        //   });
        // },
        // renderMode: "custom",
      },
      [
        // add plugins here
      ]
    );
    this.slider.update();
  }

  update() {
    this.slider.update();
  }

  size(size) {
    this.slider.size = size;
  }

  replaceAllSlides(newSlides) {
    if (newSlides) {
      const slides = newSlides.map((html) => {
        const newSlide = document.createElement("div");
        newSlide.classList.add("keen-slider__slide");
        newSlide.innerHTML = html;
        return newSlide;
      });
      this.slider.update({ slides: slides }, 0);
    }
  }

  slideTo(index) {
    this.slider.update();
    this.slider.moveToIdx(index);
  }
}

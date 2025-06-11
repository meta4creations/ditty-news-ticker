// plugins/imagesLoaded.js
export default function imagesLoaded(slider) {
  const imageLoadHandlers = [];

  const updateHeight = () => {
    const details = slider.track?.details;
    if (!details) return;
    const current = slider.slides[details.rel];
    if (current) {
      slider.container.style.height = `${current.offsetHeight}px`;
    }
  };

  const checkImages = (slide) => {
    const images = slide.querySelectorAll("img");
    images.forEach((img) => {
      if (!img.complete) {
        const handler = () => {
          updateHeight();
        };
        img.addEventListener("load", handler);
        imageLoadHandlers.push({ img, handler });
      }
    });
  };

  const onCreated = () => {
    const currentSlide = slider.slides[slider.track.details.rel];
    checkImages(currentSlide);
  };

  const onSlideChanged = (sliderInstance) => {
    const currentSlide =
      sliderInstance.slides[sliderInstance.track.details.rel];
    checkImages(currentSlide);
  };

  slider.on("created", onCreated);
  slider.on("slideChanged", onSlideChanged);

  slider.on("destroyed", () => {
    // Clean up handlers
    imageLoadHandlers.forEach(({ img, handler }) => {
      img.removeEventListener("load", handler);
    });
    imageLoadHandlers.length = 0;
  });
}

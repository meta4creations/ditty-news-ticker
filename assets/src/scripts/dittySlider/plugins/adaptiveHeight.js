export default function adaptiveHeight(slider) {
  const container = slider.container;
  const slides = Array.from(container.querySelectorAll(".ditty-item"));

  function updateHeight() {
    const details = slider.track?.details;
    if (!details) return;
    const idx = details.rel;
    const slide = slides[idx];
    if (slide) {
      container.style.height = `${slide.offsetHeight}px`;
    }
  }

  // On create, measure & un-hide
  slider.on("created", () => {
    updateHeight();
    slides.slice(1).forEach((slide) => (slide.style.display = ""));
  });

  // On every slide change
  slider.on("slideChanged", updateHeight);

  // Global resize
  window.addEventListener("resize", updateHeight);

  // Clean up on destroy
  slider.on("destroyed", () => {
    window.removeEventListener("resize", updateHeight);
  });
}

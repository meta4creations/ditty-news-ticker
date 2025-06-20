export default function navigationPHP(slider) {
  const root = slider.container.closest(".dittySlider");
  const prevBtn = root.querySelector(".dittySlider__arrow--left");
  const nextBtn = root.querySelector(".dittySlider__arrow--right");
  const bullets = Array.from(root.querySelectorAll(".dittySlider__bullet"));

  // Handlers
  const onPrevClick = () => slider.prev();
  const onNextClick = () => slider.next();
  const onDotClick = (idx) => () => slider.moveToIdx(idx);

  if (prevBtn) {
    prevBtn.addEventListener("click", onPrevClick);
  }
  if (nextBtn) {
    nextBtn.addEventListener("click", onNextClick);
  }
  if (bullets) {
    bullets.forEach((bullet, idx) =>
      bullet.addEventListener("click", onDotClick(idx))
    );
  }

  function update() {
    const idx = slider.track.details.rel;
    bullets.forEach((bullet, i) =>
      bullet.classList.toggle("dittySlider__bullet--active", i === idx)
    );
    if (!slider.options.loop) {
      if (prevBtn) {
        prevBtn.disabled = idx === 0;
      }
      if (nextBtn) {
        nextBtn.disabled = idx === slider.track.details.slides.length - 1;
      }
    }
  }

  slider.on("created", update);
  slider.on("slideChanged", update);

  // Clean up on destroy
  slider.on("destroyed", () => {
    if (prevBtn) {
      prevBtn.removeEventListener("click", onPrevClick);
    }
    if (nextBtn) {
      nextBtn.removeEventListener("click", onNextClick);
    }
    if (prevBtn) {
      bullets.forEach((bullet, idx) =>
        bullet.removeEventListener("click", onDotClick(idx))
      );
    }
  });
}

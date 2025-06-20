// plugins/autoSwitch.js
export default function autoSwitch(slider) {
  let timeout;
  let mouseOver = false;

  // Access custom timeout from slider options
  const delay = slider.options.autoplayTimeout || 2000;

  const clearNextTimeout = () => {
    clearTimeout(timeout);
  };

  const nextTimeout = () => {
    clearTimeout(timeout);
    if (mouseOver) return;
    timeout = setTimeout(() => {
      slider.next();
    }, delay);
  };

  const onMouseOver = () => {
    mouseOver = true;
    clearNextTimeout();
  };

  const onMouseOut = () => {
    mouseOver = false;
    nextTimeout();
  };

  const onCreated = () => {
    slider.container.addEventListener("mouseover", onMouseOver);
    slider.container.addEventListener("mouseout", onMouseOut);
    nextTimeout();
  };

  slider.on("created", onCreated);
  slider.on("dragStarted", clearNextTimeout);
  slider.on("animationEnded", nextTimeout);
  slider.on("updated", nextTimeout);

  slider.on("destroyed", () => {
    clearNextTimeout();
    slider.container.removeEventListener("mouseover", onMouseOver);
    slider.container.removeEventListener("mouseout", onMouseOut);
  });
}

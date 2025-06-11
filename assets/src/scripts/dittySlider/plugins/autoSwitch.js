// plugins/autoSwitch.js
export default function autoSwitch(slider) {
  let timeout;
  let mouseOver = false;

  const clearNextTimeout = () => {
    clearTimeout(timeout);
  };

  const nextTimeout = () => {
    clearTimeout(timeout);
    if (mouseOver) return;
    timeout = setTimeout(() => {
      slider.next();
    }, 2000);
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

  const onDragStarted = clearNextTimeout;
  const onAnimationEnded = nextTimeout;
  const onUpdated = nextTimeout;

  slider.on("created", onCreated);
  slider.on("dragStarted", onDragStarted);
  slider.on("animationEnded", onAnimationEnded);
  slider.on("updated", onUpdated);

  slider.on("destroyed", () => {
    // Clean up manual listeners & timer
    clearNextTimeout();
    slider.container.removeEventListener("mouseover", onMouseOver);
    slider.container.removeEventListener("mouseout", onMouseOut);
  });
}

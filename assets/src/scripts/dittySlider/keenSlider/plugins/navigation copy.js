const navigation = (slider) => {
  let wrapper, dots, arrows, arrowLeft, arrowRight;
  const options = slider.options;

  function markup(remove) {
    wrapperMarkup(remove);
    dotMarkup(remove);
    arrowMarkup(remove);
  }

  function removeElement(elment) {
    elment.parentNode.removeChild(elment);
  }
  function createDiv(className) {
    var div = document.createElement("div");
    var classNames = className.split(" ");
    classNames.forEach((name) => div.classList.add(name));
    return div;
  }

  /**
   * Create the nav wrapper markup
   */
  function wrapperMarkup(remove) {
    if (remove) {
      var parent = wrapper.parentNode;
      while (wrapper.firstChild)
        parent.insertBefore(wrapper.firstChild, wrapper);
      removeElement(wrapper);
      return;
    }
    wrapper = createDiv("dittySlider__wrapper");

    // Add the additional class
    if (options.arrowsPosition) {
      wrapper.classList.add(
        `dittySlider__wrapper--arrows-${options.arrowsPosition}`
      );
    }
    if (options.bulletsOverlay) {
      wrapper.classList.add("dittySlider__wrapper--bullets-overlay");
    }
    if (options.bulletsPosition) {
      wrapper.classList.add(
        `dittySlider__wrapper--bullets-${options.bulletsPosition}`
      );
    }

    const parentX = slider.container.parentNode;
    // swap out slider.container for wrapper
    parentX.replaceChild(wrapper, slider.container);
    // then stick the slider back *inside* the wrapper
    wrapper.appendChild(slider.container);
  }

  function arrowMarkup(remove) {
    if (!options.arrows || "none" === options.arrows) {
      return false;
    }
    if (remove) {
      removeElement(arrowLeft);
      removeElement(arrowRight);
      removeElement(arrows);
      return;
    }

    // Create a container for the arrows
    arrows = createDiv("dittySlider__arrows");

    // Check for each padding property and apply it if it exists
    if (options.arrowsPadding) {
      if (options.arrowsPadding.paddingTop) {
        arrows.style.paddingTop = options.arrowsPadding.paddingTop;
      }
      if (options.arrowsPadding.paddingRight) {
        arrows.style.paddingRight = options.arrowsPadding.paddingRight;
      }
      if (options.arrowsPadding.paddingBottom) {
        arrows.style.paddingBottom = options.arrowsPadding.paddingBottom;
      }
      if (options.arrowsPadding.paddingLeft) {
        arrows.style.paddingLeft = options.arrowsPadding.paddingLeft;
      }
    }

    arrowLeft = createDiv("dittySlider__arrow dittySlider__arrow--left");
    arrowLeft.addEventListener("click", () => slider.prev());
    arrowRight = createDiv("dittySlider__arrow dittySlider__arrow--right");
    arrowRight.addEventListener("click", () => slider.next());

    // Add SVGs to the arrows
    const leftArrowSVG = options.navPrev
      ? options.navPrev
      : `
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="24" height="24" fill="currentColor">
        <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/>
      </svg>
    `;

    const rightArrowSVG = options.navNext
      ? options.navNext
      : `
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" width="24" height="24" fill="currentColor">
        <path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/>
      </svg>
    `;

    // Insert the SVGs into the arrow divs
    arrowLeft.innerHTML = leftArrowSVG;
    arrowRight.innerHTML = rightArrowSVG;

    // Set color and background color for the arrows
    const arrowStyles = {
      color: options.arrowsIconColor ? options.arrowsIconColor : false,
      backgroundColor: options.arrowsBgColor ? options.arrowsBgColor : false,
    };

    Object.assign(arrowLeft.style, arrowStyles);
    Object.assign(arrowRight.style, arrowStyles);

    // Append arrows to the arrows container
    arrows.appendChild(arrowLeft);
    arrows.appendChild(arrowRight);

    // Append the arrows container to the wrapper
    wrapper.appendChild(arrows);
  }

  /**
   * Create the dot markup
   */
  function dotMarkup(remove) {
    if (remove) {
      removeElement(dots);
      return;
    }
    if (!options.bullets || "none" === options.bullets) {
      return false;
    }
    dots = createDiv("dittySlider__dots");
    dots.style.gap = options.bulletsSpacing;

    // Check for each padding property and apply it if it exists
    if (options.bulletsPadding) {
      if (options.bulletsPadding.paddingTop) {
        dots.style.paddingTop = options.bulletsPadding.paddingTop;
      }
      if (options.bulletsPadding.paddingRight) {
        dots.style.paddingRight = options.bulletsPadding.paddingRight;
      }
      if (options.bulletsPadding.paddingBottom) {
        dots.style.paddingBottom = options.bulletsPadding.paddingBottom;
      }
      if (options.bulletsPadding.paddingLeft) {
        dots.style.paddingLeft = options.bulletsPadding.paddingLeft;
      }
    }

    slider.track.details.slides.forEach((_e, idx) => {
      var dot = createDiv("dittySlider__dot");
      // Set the default background color for the bullet
      dot.style.width = options.bulletsSize;
      dot.style.height = options.bulletsSize;
      dot.style.backgroundColor = options.bulletsColor;
      if (options.bulletsClickable) {
        dot.style.cursor = "pointer";
        dot.addEventListener("click", () => slider.moveToIdx(idx));
      }
      dots.appendChild(dot);
    });
    wrapper.appendChild(dots);
  }

  /**
   * Update classes and set some styles
   */
  function updateClasses() {
    const slide = slider.track.details.rel;
    if (false === slider.options.loop) {
      slide === 0
        ? arrowLeft.classList.add("dittySlider__arrow--disabled")
        : arrowLeft.classList.remove("dittySlider__arrow--disabled");
      slide === slider.track.details.slides.length - 1
        ? arrowRight.classList.add("dittySlider__arrow--disabled")
        : arrowRight.classList.remove("dittySlider__arrow--disabled");
    }
    if (dots) {
      Array.from(dots.children).forEach(function (dot, idx) {
        if (idx === slide) {
          // Set active bullet color
          dot.classList.add("dittySlider__dot--active");
          dot.style.backgroundColor = options.bulletsColorActive;
        } else {
          // Set inactive bullet color
          dot.classList.remove("dittySlider__dot--active");
          dot.style.backgroundColor = options.bulletsColor;
        }
      });
    }
  }

  slider.on("created", () => {
    markup();
    updateClasses();
  });
  slider.on("optionsChanged", () => {
    markup(true);
    markup();
    updateClasses();
  });
  slider.on("slideChanged", () => {
    updateClasses();
  });
  slider.on("destroyed", () => {
    markup(true);
  });
};

export default navigation;

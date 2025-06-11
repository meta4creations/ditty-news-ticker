// Preview.js
import { useState, useEffect, useRef } from "@wordpress/element";
import { useSelect } from "@wordpress/data";
import { InnerBlocks } from "@wordpress/block-editor";

export default function Preview({ attributes, clientId }) {
  const {
    animationDuration,
    animationEasing,
    loop,
    mode,
    rubberband,
    vertical,
    slidesCenter,
    slidesPerView,
    slidesSpacing,
    initialSlide,
    sliderBreakpoints,
    autoheight,
    arrows,
    arrowPrevIcon,
    arrowNextIcon,
    arrowsPadding,
    arrowBorderRadius,
    arrowIconWidth,
    arrowIconColor,
    arrowIconHoverColor,
    arrowBgColor,
    arrowBgHoverColor,
    bullets,
  } = attributes;

  const wrapperRef = useRef();
  const sliderInstance = useRef(null);
  const prevSettings = useRef();

  // 1) Pull the media records so we know mime type + URL
  const prevMedia = useSelect(
    (select) => (arrowPrevIcon ? select("core").getMedia(arrowPrevIcon) : null),
    [arrowPrevIcon]
  );
  const nextMedia = useSelect(
    (select) => (arrowNextIcon ? select("core").getMedia(arrowNextIcon) : null),
    [arrowNextIcon]
  );
  const slideCount = useSelect(
    (select) => select("core/block-editor").getBlocks(clientId).length,
    [clientId]
  );

  // 2) State to hold raw SVG markup
  const [prevSvg, setPrevSvg] = useState(null);
  const [nextSvg, setNextSvg] = useState(null);

  // 3) Whenever the media changes, if it's an SVG fetch its text
  useEffect(() => {
    if (prevMedia?.mime_type === "image/svg+xml") {
      fetch(prevMedia.source_url)
        .then((r) => r.text())
        .then(setPrevSvg)
        .catch(() => setPrevSvg(null));
    } else {
      setPrevSvg(null);
    }
  }, [prevMedia]);

  useEffect(() => {
    if (nextMedia?.mime_type === "image/svg+xml") {
      fetch(nextMedia.source_url)
        .then((r) => r.text())
        .then(setNextSvg)
        .catch(() => setNextSvg(null));
    } else {
      setNextSvg(null);
    }
  }, [nextMedia]);

  // … your slider initialization effect stays the same …
  useEffect(() => {
    const wrapper = wrapperRef.current;
    if (!wrapper) return;
    const sliderEl = wrapper.querySelector(".block-editor-block-list__layout");
    if (!sliderEl) return;

    const baseSettings = {
      initial: initialSlide,
      autoheight,
      loop,
      mode,
      rubberband,
      vertical,
      transitionSpeed: animationDuration,
      transitionEase: animationEasing,
      slides: {
        origin: slidesCenter ? "center" : "auto",
        perView: slidesPerView,
        spacing: slidesSpacing,
      },
      selector: ".ditty-item",
    };

    const keenBreakpoints = (sliderBreakpoints || []).reduce((acc, bp) => {
      acc[`(max-width: ${bp.maxWidth}px)`] = {
        slides: {
          origin: bp.center ? "center" : "auto",
          perView: bp.perView,
          spacing: bp.spacing,
        },
      };
      return acc;
    }, {});

    const sliderOptions = { ...baseSettings, breakpoints: keenBreakpoints };

    const prev = prevSettings.current;
    const needReinit = true; // or your hybrid logic

    if (sliderInstance.current && !needReinit) {
      const ks = sliderInstance.current.sliderInstance;
      const idx = ks.track.details.rel;
      ks.update(sliderOptions, idx);
    } else {
      sliderInstance.current?.destroySlider();
      sliderInstance.current = new DittySlider(sliderEl, sliderOptions);
    }
    prevSettings.current = sliderOptions;

    return () => {
      sliderInstance.current?.destroySlider();
      sliderInstance.current = null;
    };
  }, [
    animationDuration,
    animationEasing,
    loop,
    mode,
    rubberband,
    vertical,
    slidesCenter,
    slidesPerView,
    slidesSpacing,
    initialSlide,
    autoheight,
    JSON.stringify(sliderBreakpoints),
  ]);

  // Hover state hooks
  const [hoverLeft, setHoverLeft] = useState(false);
  const [hoverRight, setHoverRight] = useState(false);

  // Inline styles
  const arrowsStyles = {
    paddingTop: arrowsPadding.top || 0,
    paddingRight: arrowsPadding.right || 0,
    paddingBottom: arrowsPadding.bottom || 0,
    paddingLeft: arrowsPadding.left || 0,
  };
  const arrowStyles = (hover) => ({
    borderRadius: arrowBorderRadius,
    color: hover ? arrowIconHoverColor : arrowIconColor,
    backgroundColor: hover ? arrowBgHoverColor : arrowBgColor,
  });
  const iconSizeStyle = {
    width: arrowIconWidth ? `${arrowIconWidth}px` : undefined,
  };

  return (
    <div className="wp-block-mtphr-ditty-display__preview" ref={wrapperRef}>
      <InnerBlocks />

      {arrows && (
        <div className="dittySlider__arrows" style={arrowsStyles}>
          {/* Prev Button */}
          <button
            className="dittySlider__arrow dittySlider__arrow--left"
            style={arrowStyles(hoverLeft)}
            onMouseEnter={() => setHoverLeft(true)}
            onMouseLeave={() => setHoverLeft(false)}
          >
            {prevSvg ? (
              <span
                style={iconSizeStyle}
                dangerouslySetInnerHTML={{ __html: prevSvg }}
              />
            ) : prevMedia?.source_url ? (
              <img src={prevMedia.source_url} alt="" style={iconSizeStyle} />
            ) : (
              /* fallback inline SVG */
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 320 512"
                width="24"
                height="24"
                fill="currentColor"
              >
                <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z" />
              </svg>
            )}
          </button>

          {/* Next Button */}
          <button
            className="dittySlider__arrow dittySlider__arrow--right"
            style={arrowStyles(hoverRight)}
            onMouseEnter={() => setHoverRight(true)}
            onMouseLeave={() => setHoverRight(false)}
          >
            {nextSvg ? (
              <span
                style={iconSizeStyle}
                dangerouslySetInnerHTML={{ __html: nextSvg }}
              />
            ) : nextMedia?.source_url ? (
              <img src={nextMedia.source_url} alt="" style={iconSizeStyle} />
            ) : (
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 320 512"
                width="24"
                height="24"
                fill="currentColor"
              >
                <path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z" />
              </svg>
            )}
          </button>
        </div>
      )}

      {bullets && (
        <div className="dittySlider__bullets">
          {Array.from({ length: slideCount }).map((_, i) => (
            <button key={i} className="dittySlider__bullet" data-idx={i} />
          ))}
        </div>
      )}
    </div>
  );
}

// edit.js
import { __ } from "@wordpress/i18n";
import { InspectorControls } from "@wordpress/block-editor";
import { Fragment } from "@wordpress/element";
import GeneralSettings from "./settings/generalSettings";
import SlideSettings from "./settings/slideSettings";
import AnimationSettings from "./settings/animationSettings";
import BreakpointSettings from "./settings/breakpointSettings";
import ArrowStyles from "./styles/arrowStyles";

export default function Inspector(props) {
  const { attributes, setAttributes } = props;
  const { sliderSettings } = attributes;

  const updateAnimation = (key, val) =>
    setAttributes({
      sliderSettings: {
        ...sliderSettings,
        defaultAnimation: {
          ...sliderSettings.defaultAnimation,
          [key]: val,
        },
      },
    });

  // helper to update slides sub-object
  const updateSlides = (key, value) => {
    setAttributes({
      sliderSettings: {
        ...sliderSettings,
        slides: {
          ...sliderSettings.slides,
          [key]: value,
        },
      },
    });
  };

  return (
    <Fragment>
      <InspectorControls group="settings">
        <GeneralSettings {...props} updateSlides={updateSlides} />
        <SlideSettings {...props} updateSlides={updateSlides} />
        <AnimationSettings {...props} updateAnimation={updateAnimation} />
        <BreakpointSettings {...props} />
      </InspectorControls>

      <InspectorControls group="styles">
        <ArrowStyles {...props} />
      </InspectorControls>
    </Fragment>
  );
}

import { __ } from "@wordpress/i18n";
import { PanelBody, RangeControl } from "@wordpress/components";

export default function SlideSettings(props) {
  const { attributes, setAttributes } = props;

  return (
    <PanelBody title={__("Slide Settings", "ditty-pro")} initialOpen>
      <RangeControl
        label={__("Slides per View", "ditty-pro")}
        value={attributes.slidesPerView}
        onChange={(val) => setAttributes({ slidesPerView: val })}
        min={1}
        max={5}
      />
      <RangeControl
        label={__("Spacing (px)", "ditty-pro")}
        value={attributes.slidesSpacing}
        onChange={(val) => setAttributes({ slidesSpacing: val })}
        min={0}
        max={50}
      />
      <RangeControl
        label={__("Initial Slide", "ditty-pro")}
        value={attributes.initialSlide}
        onChange={(val) => setAttributes({ initialSlide: val })}
        min={0}
        max={10}
      />
    </PanelBody>
  );
}

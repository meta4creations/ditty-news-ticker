import { __ } from "@wordpress/i18n";
import { PanelBody, ToggleControl } from "@wordpress/components";

export default function GeneralSettings(props) {
  const { attributes, setAttributes } = props;

  return (
    <PanelBody title={__("General Settings", "ditty-pro")} initialOpen>
      <ToggleControl
        label={__("Auto Height", "ditty-pro")}
        checked={attributes.autoheight}
        onChange={(val) => setAttributes({ autoheight: val })}
      />
      <ToggleControl
        label={__("Loop Slides", "ditty-pro")}
        checked={attributes.loop}
        onChange={(val) => setAttributes({ loop: val })}
      />
      {/* <ToggleControl
            label={__("Rubberband", "ditty-pro")}
            checked={attributes.rubberband}
            onChange={(val) => setAttributes({ rubberband: val })}
          /> */}
      {/* <ToggleControl
            label={__("Vertical", "ditty-pro")}
            checked={attributes.vertical}
            onChange={(val) => setAttributes({ vertical: val })}
          /> */}
      <ToggleControl
        label={__("Center Slides", "ditty-pro")}
        checked={attributes.slidesCenter}
        onChange={(val) => setAttributes({ slidesCenter: val })}
      />
      <ToggleControl
        label={__("Navigation Arrows", "ditty-pro")}
        checked={attributes.arrows ? true : false}
        onChange={(val) => setAttributes({ arrows: val })}
      />
      <ToggleControl
        label={__("Navigation Bullets", "ditty-pro")}
        checked={attributes.bullets ? true : false}
        onChange={(val) => setAttributes({ bullets: val })}
      />
      {/* <SelectControl
        label={__("Mode", "ditty-pro")}
        value={attributes.mode}
        options={[
          { label: "snap", value: "snap" },
          { label: "free", value: "free" },
          { label: "free-snap", value: "free-snap" },
        ]}
        onChange={(val) => setAttributes({ mode: val })}
      /> */}
    </PanelBody>
  );
}

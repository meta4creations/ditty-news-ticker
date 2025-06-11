import { __ } from "@wordpress/i18n";
import {
  PanelBody,
  ToggleControl,
  RangeControl,
  Button,
} from "@wordpress/components";

export default function BreakpointSettings(props) {
  const { attributes, setAttributes } = props;

  const updateBreakpoint = (index, field, value) => {
    const updatedBreakpoints = attributes.sliderBreakpoints.map((bp, i) =>
      i === index ? { ...bp, [field]: value } : bp
    );
    setAttributes({ sliderBreakpoints: updatedBreakpoints });
  };

  const removeBreakpoint = (index) => {
    const newBps = attributes.sliderBreakpoints.filter((_, i) => i !== index);
    setAttributes({ sliderBreakpoints: newBps });
  };

  const addBreakpoint = () => {
    const updatedBreakpoints = [
      ...attributes.sliderBreakpoints,
      {
        maxWidth: 600,
        perView: attributes.slidesPerView,
        spacing: attributes.slidesSpacing,
        center: attributes.slidesCenter,
      },
    ];
    setAttributes({ sliderBreakpoints: updatedBreakpoints });
  };

  return (
    <PanelBody title={__("Breakpoints", "ditty-pro")} initialOpen={false}>
      {attributes.sliderBreakpoints.map((bp, i) => (
        <PanelBody
          key={i}
          title={__("Max-width:", "ditty-pro") + " " + bp.maxWidth + "px"}
          initialOpen={false}
        >
          <RangeControl
            label={__("Max Width (px)", "ditty-pro")}
            value={bp.maxWidth}
            onChange={(val) => updateBreakpoint(i, "maxWidth", val)}
            min={100}
            max={2000}
          />
          <ToggleControl
            label={__("Center Slides", "ditty-pro")}
            checked={!!bp.center}
            onChange={(val) => updateBreakpoint(i, "center", val)}
          />
          <RangeControl
            label={__("Per View", "ditty-pro")}
            value={bp.perView}
            onChange={(val) => updateBreakpoint(i, "perView", val)}
            min={1}
            max={5}
          />
          <RangeControl
            label={__("Spacing (px)", "ditty-pro")}
            value={bp.spacing}
            onChange={(val) => updateBreakpoint(i, "spacing", val)}
            min={0}
            max={50}
          />
          <Button
            variant="link"
            isDestructive
            onClick={() => removeBreakpoint(i)}
          >
            {__("Remove breakpoint", "ditty-pro")}
          </Button>
        </PanelBody>
      ))}
      <Button variant="primary" onClick={addBreakpoint}>
        {__("Add breakpoint", "ditty-pro")}
      </Button>
    </PanelBody>
  );
}

import { __ } from "@wordpress/i18n";
import { PanelBody, RangeControl, SelectControl } from "@wordpress/components";

export default function AnimationSettings(props) {
  const { attributes, setAttributes } = props;

  const EASING_OPTIONS = [
    { label: "linear", value: "linear" },
    { label: "swing", value: "swing" },
    { label: "jswing", value: "jswing" },
    { label: "easeInQuad", value: "easeInQuad" },
    { label: "easeInCubic", value: "easeInCubic" },
    { label: "easeInQuart", value: "easeInQuart" },
    { label: "easeInQuint", value: "easeInQuint" },
    { label: "easeInSine", value: "easeInSine" },
    { label: "easeInExpo", value: "easeInExpo" },
    { label: "easeInCirc", value: "easeInCirc" },
    { label: "easeInElastic", value: "easeInElastic" },
    { label: "easeInBack", value: "easeInBack" },
    { label: "easeInBounce", value: "easeInBounce" },
    { label: "easeOutQuad", value: "easeOutQuad" },
    { label: "easeOutCubic", value: "easeOutCubic" },
    { label: "easeOutQuart", value: "easeOutQuart" },
    { label: "easeOutQuint", value: "easeOutQuint" },
    { label: "easeOutSine", value: "easeOutSine" },
    { label: "easeOutExpo", value: "easeOutExpo" },
    { label: "easeOutCirc", value: "easeOutCirc" },
    { label: "easeOutElastic", value: "easeOutElastic" },
    { label: "easeOutBack", value: "easeOutBack" },
    { label: "easeOutBounce", value: "easeOutBounce" },
    { label: "easeInOutQuad", value: "easeInOutQuad" },
    { label: "easeInOutCubic", value: "easeInOutCubic" },
    { label: "easeInOutQuart", value: "easeInOutQuart" },
    { label: "easeInOutQuint", value: "easeInOutQuint" },
    { label: "easeInOutSine", value: "easeInOutSine" },
    { label: "easeInOutExpo", value: "easeInOutExpo" },
    { label: "easeInOutCirc", value: "easeInOutCirc" },
    { label: "easeInOutElastic", value: "easeInOutElastic" },
    { label: "easeInOutBack", value: "easeInOutBack" },
    { label: "easeInOutBounce", value: "easeInOutBounce" },
  ];

  return (
    <PanelBody title={__("Animation", "ditty")} initialOpen>
      <RangeControl
        label={__("Duration (ms)", "ditty")}
        value={attributes.animationDuration}
        onChange={(val) => setAttributes({ animationDuration: val })}
        min={100}
        max={5000}
      />
      <SelectControl
        label={__("Easing", "ditty")}
        value={attributes.animationEasing}
        options={EASING_OPTIONS}
        onChange={(val) => setAttributes({ animationEasing: val })}
      />
    </PanelBody>
  );
}

import { __ } from "@wordpress/i18n";
import { RangeControl } from "@wordpress/components";
import BaseField from "./BaseField";

const SliderField = (props) => {
  const { value, min, max, step, onChange, js_options } = props;
  return (
    <BaseField {...props}>
      <RangeControl
        value={Number(value)}
        onChange={(updatedValue) => onChange(String(updatedValue))}
        min={min ? min : js_options ? js_options.min : null}
        max={max ? max : js_options ? js_options.max : null}
        step={step ? step : js_options ? js_options.step : null}
      />
    </BaseField>
  );
};

export default SliderField;
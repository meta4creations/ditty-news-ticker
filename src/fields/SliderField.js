import { __ } from "@wordpress/i18n";
import { RangeControl } from "@wordpress/components";
import BaseField from "./BaseField";

const SliderField = (props) => {
  const { value, min, max, step, onChange } = props;

  return (
    <BaseField {...props}>
      <RangeControl
        value={Number(value)}
        onChange={(updatedValue) => onChange(String(updatedValue))}
        min={min}
        max={max}
        step={step}
      />
    </BaseField>
  );
};

export default SliderField;

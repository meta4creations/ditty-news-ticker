const { __ } = wp.i18n;
import BaseField from "./BaseField";

const NumberField = (props) => {
  const { value, onChange, min, max } = props;

  return (
    <BaseField {...props} type="number">
      <input
        type="number"
        value={Number(value)}
        min={min}
        max={max}
        onChange={(e) => {
          onChange(String(e.target.value));
        }}
      />
    </BaseField>
  );
};

export default NumberField;

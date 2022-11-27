import { __ } from "@wordpress/i18n";
import BaseField from "./BaseField";

const NumberField = (props) => {
  const { value, onChange } = props;

  return (
    <BaseField {...props}>
      <input
        type="number"
        value={value}
        onChange={(e) => {
          onChange(e.target.value);
        }}
      />
    </BaseField>
  );
};

export default NumberField;

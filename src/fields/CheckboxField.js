const { __ } = wp.i18n;
import BaseField from "./BaseField";

const CheckboxField = (props) => {
  const { label, value, onChange } = props;

  return (
    <BaseField {...props} type="checkbox">
      <label>
        <input
          type="checkbox"
          value="1"
          checked={"1" === value}
          onChange={(e) => {
            const updatedValue = "1" === value ? false : "1";
            onChange(updatedValue);
          }}
        />
        <span>{label}</span>
      </label>
    </BaseField>
  );
};

export default CheckboxField;

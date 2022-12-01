import { __ } from "@wordpress/i18n";
import BaseField from "../BaseField";

const TextField = (props) => {
  const { value, type, onChange } = props;
  const inputType = type ? type : "text";

  return (
    <BaseField {...props}>
      <input
        type={inputType}
        value={value}
        onChange={(e) => {
          onChange(e.target.value);
        }}
      />
    </BaseField>
  );
};

export default TextField;

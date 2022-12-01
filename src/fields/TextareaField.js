import { __ } from "@wordpress/i18n";
import BaseField from "./BaseField";

const TextareaField = (props) => {
  const { value, cols, rows, onChange } = props;

  return (
    <BaseField {...props}>
      <textarea
        cols={cols}
        rows={rows}
        defaultValue={value}
        onChange={(e) => {
          onChange(e.target.value);
        }}
      />
    </BaseField>
  );
};

export default TextareaField;

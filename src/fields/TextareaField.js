import { __ } from "@wordpress/i18n";
import BaseField from "./BaseField";

const TextareaField = (props) => {
  const { value, cols, rows, onChange } = props;

  return (
    <BaseField {...props}>
      <textarea
        cols={cols}
        rows={rows}
        onChange={(e) => {
          onChange(e.target.value);
        }}
      >
        {value}
      </textarea>
    </BaseField>
  );
};

export default TextareaField;

import { __ } from "@wordpress/i18n";
import { useState, useRef, useCallback } from "@wordpress/element";
import BaseField from "./BaseField";

const TextareaField = (props) => {
  const { value, cols, rows, onChange } = props;
  const [inputValue, setInputValue] = useState(value);

  const timerRef = useRef(null);

  const handleInputChange = useCallback(
    (event) => {
      const updatedValue = event.target.value;
      setInputValue(updatedValue);

      // Clear the existing timer
      clearTimeout(timerRef.current);

      // Start a new timer to update the parent element
      timerRef.current = setTimeout(() => onChange(updatedValue), 500);
    },
    [onChange, inputValue]
  );

  return (
    <BaseField {...props} type="textarea">
      <textarea
        cols={cols}
        rows={rows}
        defaultValue={inputValue}
        onChange={handleInputChange}
      />
    </BaseField>
  );
};

export default TextareaField;

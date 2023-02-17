import { __ } from "@wordpress/i18n";
import { useState, useRef, useCallback } from "@wordpress/element";
import BaseField from "./BaseField";

const TextField = (props) => {
  const { value, type, onChange } = props;
  const inputType = type ? type : "text";
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
    <BaseField {...props} type={inputType}>
      <input type={inputType} value={inputValue} onChange={handleInputChange} />
    </BaseField>
  );
};

export default TextField;

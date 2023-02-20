import { __ } from "@wordpress/i18n";
import { useState, useRef, useCallback } from "@wordpress/element";
import BaseField from "./BaseField";

const TextField = (props) => {
  const { value, type, onChange, delayChange = false } = props;
  const inputType = type ? type : "text";
  const [delayValue, setDelayValue] = useState(value);

  const timerRef = useRef(null);

  const handleInputChangeDelay = useCallback(
    (e) => {
      const updatedValue = e.target.value;
      setDelayValue(updatedValue);

      // Clear the existing timer
      clearTimeout(timerRef.current);

      // Start a new timer to update the parent element
      timerRef.current = setTimeout(() => onChange(updatedValue), 500);
    },
    [onChange, delayValue]
  );

  return (
    <BaseField {...props} type={inputType}>
      <input
        type={inputType}
        value={delayChange ? delayValue : value}
        onChange={(e) => {
          delayChange ? handleInputChangeDelay(e) : onChange(e.target.value);
        }}
      />
    </BaseField>
  );
};

export default TextField;

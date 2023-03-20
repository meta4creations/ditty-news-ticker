import { __ } from "@wordpress/i18n";
import { useState, useRef, useEffect, useCallback } from "@wordpress/element";
import BaseField from "./BaseField";

const TextField = (props) => {
  const {
    value,
    type,
    onChange,
    onBlur,
    onFocus,
    setFocus,
    delayChange = false,
  } = props;
  const inputType = type ? type : "text";
  const [delayValue, setDelayValue] = useState(value);

  const inputRef = useRef(null);
  const timerRef = useRef(null);

  useEffect(() => {
    if (setFocus) {
      inputRef.current.focus();
    }
    return () => {
      if (delayChange) {
        clearTimeout(timerRef.current);
      }
    };
  }, [setFocus]);

  const handleInputChangeDelay = useCallback(
    (updatedValue) => {
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
          delayChange
            ? handleInputChangeDelay(e.target.value)
            : onChange(e.target.value);
        }}
        onBlur={onBlur}
        onFocus={onFocus}
        ref={inputRef}
      />
    </BaseField>
  );
};

export default TextField;

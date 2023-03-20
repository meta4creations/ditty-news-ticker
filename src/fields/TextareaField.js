import { __ } from "@wordpress/i18n";
import { useState, useRef, useEffect, useCallback } from "@wordpress/element";
import BaseField from "./BaseField";

const TextareaField = (props) => {
  const {
    value,
    cols,
    rows,
    onChange,
    onBlur,
    onFocus,
    setFocus,
    delayChange = false,
  } = props;
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
    <BaseField {...props} type="textarea">
      <textarea
        cols={cols}
        rows={rows}
        defaultValue={delayChange ? delayValue : value}
        onChange={(e) => {
          delayChange ? handleInputChangeDelay(e) : onChange(e.target.value);
        }}
        onBlur={onBlur}
        onFocus={onFocus}
        ref={inputRef}
      />
    </BaseField>
  );
};

export default TextareaField;

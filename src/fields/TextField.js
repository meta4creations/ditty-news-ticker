const { __ } = wp.i18n;
const { useState, useRef, useEffect, useCallback } = wp.element;
import BaseField from "./BaseField";

const TextField = (props) => {
  const { stripTags } = wp.sanitize;
  const {
    value,
    type,
    placeholder,
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
        value={delayChange ? stripTags(delayValue) : stripTags(value)}
        placeholder={placeholder}
        onChange={(e) => {
          delayChange
            ? handleInputChangeDelay(stripTags(e.target.value))
            : onChange(stripTags(e.target.value));
        }}
        onBlur={onBlur}
        onFocus={onFocus}
        ref={inputRef}
      />
    </BaseField>
  );
};

export default TextField;

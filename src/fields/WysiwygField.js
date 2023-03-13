import { __ } from "@wordpress/i18n";
import { FreeForm } from "@wordpress/block-editor";
import { useState, useRef, useEffect, useCallback } from "@wordpress/element";
import BaseField from "./BaseField";

const WysiwygField = (props) => {
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
    <BaseField {...props} type="textarea">
      <FreeForm
        value={delayChange ? delayValue : value}
        onChange={(updatedValue) => {
          delayChange
            ? handleInputChangeDelay(updatedValue)
            : onChange(updatedValue);
        }}
      />
    </BaseField>
  );
};

export default WysiwygField;

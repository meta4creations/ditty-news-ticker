const { __ } = wp.i18n;
const { useState, useRef, useEffect } = wp.element;
import ColorPicker from "react-best-gradient-color-picker";
import BaseField from "./BaseField";

const ColorField = (props) => {
  const { value, onChange, gradient = false } = props;
  const [displayPicker, setDisplayPicker] = useState(false);
  const wrapperRef = useRef(null);

  useEffect(() => {
    /**
     * Alert if clicked on outside of element
     */
    function handleClickOutside(event) {
      if (wrapperRef.current && !wrapperRef.current.contains(event.target)) {
        setDisplayPicker(false);
      }
    }
    // Bind the event listener
    document.addEventListener("mousedown", handleClickOutside);
    return () => {
      // Unbind the event listener on clean up
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, [wrapperRef, setDisplayPicker]);

  return (
    <BaseField {...props} type="color">
      <>
        <div
          className="ditty-field__input--color__swatch"
          style={{ background: value }}
          onClick={() => setDisplayPicker(true)}
        ></div>
        <input
          type="text"
          value={value}
          onChange={(e) => onChange(e.target.value)}
          onClick={() => setDisplayPicker(true)}
        />
        {displayPicker && (
          <div className="ditty-field__input--color__popover" ref={wrapperRef}>
            <div
              style={{
                background: "#fff",
                borderRadius: 8,
                boxShadow: "0 0 6px rgb(0 0 0 / 25%)",
                padding: 8,
                position: "relative",
                width: 310,
              }}
            >
              <ColorPicker
                value={value}
                onChange={onChange}
                hideControls={!gradient}
              />
            </div>
          </div>
        )}
      </>
    </BaseField>
  );
};

export default ColorField;

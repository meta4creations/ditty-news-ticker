import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { ChromePicker } from "react-color";
import BaseField from "./BaseField";

const ColorField = (props) => {
  const { value, onChange } = props;
  const [displayPicker, setDisplayPicker] = useState(false);

  return (
    <BaseField {...props}>
      <>
        <div
          className="ditty-field__input--color__swatch"
          style={{ backgroundColor: value }}
          onClick={() => setDisplayPicker(true)}
        ></div>
        <input
          type="text"
          value={value}
          onChange={(e) => onChange(e.target.value)}
          onClick={() => setDisplayPicker(true)}
        />
        {displayPicker && (
          <div className="ditty-field__input--color__popover">
            <div
              style={{
                position: "fixed",
                top: "0px",
                right: "0px",
                bottom: "0px",
                left: "0px",
              }}
              onClick={() => setDisplayPicker(false)}
            />
            <ChromePicker
              color={value}
              onChangeComplete={(color) => {
                const val = `rgba(${color.rgb.r}, ${color.rgb.g}, ${color.rgb.b}, ${color.rgb.a})`;
                onChange(val);
              }}
            />
          </div>
        )}
      </>
    </BaseField>
  );
};

export default ColorField;

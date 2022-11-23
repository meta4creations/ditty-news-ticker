import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import BaseField from "./BaseField";
import UnitField from "./UnitField";

const SpacingField = (props) => {
  const { options, value, onChange } = props;
  const [current, setCurrent] = useState(false);
  console.log("current", current);

  const defaults = {
    paddingTop: __("Top", "ditty-news-ticker"),
    paddingBottom: __("Bottom", "ditty-news-ticker"),
    paddingLeft: __("Left", "ditty-news-ticker"),
    paddingRight: __("Right", "ditty-news-ticker"),
  };

  const args = options ? options : defaults;

  const updateValue = (key, updatedValue) => {
    console.log(key, updatedValue);
    value[key] = updatedValue;
    onChange(value);
  };

  const renderBox = () => {
    const sides = [];
    for (const [objKey, objValue] of Object.entries(args)) {
      sides.push(
        <span
          key={objKey}
          className={objKey === current ? "active" : ""}
        ></span>
      );
    }
    return sides;
  };

  const renderField = () => {
    const fields = [];
    for (const [objKey, objValue] of Object.entries(args)) {
      fields.push(
        <div
          key={objKey}
          className="ditty-field__input--spacing__input"
          onFocus={(e) => {
            setCurrent(objKey);
          }}
          onBlur={(e) => {
            setCurrent(false);
          }}
        >
          <UnitField
            value={value[objKey]}
            placeholder={objValue}
            onChange={(updatedValue) => updateValue(objKey, updatedValue)}
          />
        </div>
      );
    }
    return fields;
  };

  return (
    <BaseField {...props}>
      {/* {current && (
        <div
          style={{
            position: "fixed",
            top: "0px",
            right: "0px",
            bottom: "0px",
            left: "0px",
          }}
          onClick={() => setCurrent(false)}
        />
      )} */}
      <div className="ditty-field__input--spacing__box">{renderBox()}</div>
      <div className="ditty-field__input--spacing__inputs">{renderField()}</div>
    </BaseField>
  );
};

export default SpacingField;

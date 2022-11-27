import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import BaseField from "./BaseField";
import UnitField from "./UnitField";

const SpacingField = (props) => {
  const { type, options, value = {}, onChange } = props;
  const [current, setCurrent] = useState(false);

  const defaults = {
    paddingTop: __("Top", "ditty-news-ticker"),
    paddingBottom: __("Bottom", "ditty-news-ticker"),
    paddingLeft: __("Left", "ditty-news-ticker"),
    paddingRight: __("Right", "ditty-news-ticker"),
  };

  const args = options ? options : defaults;

  const updateValue = (key, updatedValue) => {
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
      const unitValue = value[objKey] ? value[objKey] : "";
      fields.push(
        <div
          key={objKey}
          className={`ditty-field__input--${type}__input`}
          onFocus={(e) => {
            setCurrent(objKey);
          }}
          onBlur={(e) => {
            setCurrent(false);
          }}
        >
          <UnitField
            value={unitValue}
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
      <div className={`ditty-field__input--${type}__box`}>{renderBox()}</div>
      <div className={`ditty-field__input--${type}__inputs`}>
        {renderField()}
      </div>
    </BaseField>
  );
};

export default SpacingField;

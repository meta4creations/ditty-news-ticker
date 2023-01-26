import { __ } from "@wordpress/i18n";
import BaseField from "./BaseField";

const UnitField = (props) => {
  const { placeholder, value, onChange } = props;
  const unitOptions = [
    {
      value: "px",
      label: "px",
    },
    {
      value: "%",
      label: "%",
    },
    {
      value: "em",
      label: "em",
    },
    {
      value: "rem",
      label: "rem",
    },
    {
      value: "vw",
      label: "vw",
    },
    {
      value: "vh",
      label: "vh",
    },
  ];

  const renderOptions = () => {
    return unitOptions.map((option) => {
      return (
        <option key={option.value} value={option.value}>
          {option.label}
        </option>
      );
    });
  };

  const numberValue = () => {
    if (!value) {
      return "";
    }
    const numbers = String(value).match(/\d+/);
    if (numbers) {
      return numbers[0];
    }
    return "";
  };

  const unitValue = () => {
    if (!value) {
      return unitOptions[0].value;
    }
    const numbers = String(value).match(/\d+/);
    if (numbers) {
      return String(value).substr(numbers[0].length, value.length);
    } else {
      return value;
    }
  };

  const updateInputValue = (val) => {
    if (!value) {
      onChange(`${val}${unitOptions[0].value}`);
    } else {
      let unit;
      const numbers = String(value).match(/\d+/);
      if (numbers) {
        unit = String(value).substr(numbers[0].length, value.length);
      } else {
        unit = value;
      }
      onChange(`${val}${unit}`);
    }
  };

  const updateUnitValue = (val) => {
    const numbers = value ? String(value).match(/\d+/) : false;
    if (numbers) {
      onChange(`${numbers[0]}${val}`);
    } else {
      onChange(val);
    }
  };

  return (
    <BaseField type="unit" {...props}>
      <input
        autoComplete="off"
        inputMode="numeric"
        max="Infinity"
        min="-Infinity"
        step="1"
        type="number"
        value={numberValue()}
        placeholder={placeholder}
        onChange={(e) => updateInputValue(e.target.value)}
      />
      <select
        defaultValue={unitValue()}
        onChange={(e) => updateUnitValue(e.target.value)}
      >
        {renderOptions()}
      </select>
    </BaseField>
  );
};

export default UnitField;

import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import BaseField from "./BaseField";

const UnitField = (props) => {
  const { placeholder, value, min, max, step, onChange } = props;
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
    const numbers = String(value).match(/\d+(\.\d+)?/);
    if (numbers) {
      return numbers[0];
    }
    return "";
  };

  const unitValue = () => {
    if (!value) {
      return unitOptions[0].value;
    }
    const numbers = String(value).match(/\d+(\.\d+)?/);
    if (numbers) {
      return String(value).substr(numbers[0].length, value.length);
    } else {
      return value;
    }
  };

  const [number, setNumber] = useState(numberValue());
  const [unit, setUnit] = useState(unitValue());

  return (
    <BaseField {...props} type="unit">
      <input
        autoComplete="off"
        inputMode="numeric"
        max={undefined !== max ? String(max) : "Infinity"}
        min={undefined !== min ? String(min) : "-Infinity"}
        step={undefined !== step ? String(step) : "1"}
        type="number"
        value={number}
        placeholder={placeholder}
        onChange={(e) => {
          setNumber(e.target.value);
          if ("" === e.target.value) {
            onChange("");
          } else {
            onChange(`${e.target.value}${unit}`);
          }
        }}
      />
      <select
        defaultValue={unit}
        onChange={(e) => {
          setUnit(e.target.value);
          onChange(`${number}${e.target.value}`);
        }}
      >
        {renderOptions()}
      </select>
    </BaseField>
  );
};

export default UnitField;

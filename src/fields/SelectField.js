import { __ } from "@wordpress/i18n";
import BaseField from "./BaseField";

const SelectField = (props) => {
  const { placeholder, options, value, onChange } = props;
  const convertFieldOptions = (options) => {
    if (Array.isArray(options)) {
      return options.map((option) => {
        if (typeof option === "string") {
          return {
            label: option,
            value: option,
          };
        } else {
          return option;
        }
      });
    } else if (typeof options === "object") {
      let optionsArray = [];
      for (const key in options) {
        optionsArray.push({
          label: options[key],
          value: key,
        });
      }
      return optionsArray;
    }
  };

  const renderOptions = () => {
    const convertedOptions = convertFieldOptions(options);
    return convertedOptions.map((option) => {
      return (
        <option key={option.value} value={option.value}>
          {option.label}
        </option>
      );
    });
  };

  return (
    <BaseField {...props}>
      <select
        placeholder={placeholder}
        defaultValue={value}
        onChange={(e) => onChange(e.target.value)}
      >
        {placeholder && <option>{placeholder}</option>}
        {renderOptions()}
      </select>
    </BaseField>
  );
};

export default SelectField;

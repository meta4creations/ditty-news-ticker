import { __ } from "@wordpress/i18n";
import BaseField from "./BaseField";

const SelectField = (props) => {
  const { id, placeholder, options, value, onChange } = props;
  let helpOptions = {};

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

  const renderOption = (option) => {
    if (option.help) {
      helpOptions[option.value] = option.help;
    }
    return (
      <option key={option.value} value={option.value}>
        {option.label}
      </option>
    );
  };

  const renderGroup = (group) => {
    return (
      <optgroup key={group.label} label={group.label}>
        {group.options && renderOptions(group.options)}
      </optgroup>
    );
  };

  const renderOptions = (selectOptions) => {
    const convertedOptions = convertFieldOptions(selectOptions);
    return convertedOptions.map((option) => {
      if (option.group) {
        return renderGroup(option);
      } else {
        return renderOption(option);
      }
    });
  };
  return (
    <BaseField {...props}>
      <select
        placeholder={placeholder}
        defaultValue={value}
        onChange={(e) => {
          window.dispatchEvent(
            new CustomEvent("dittySelectFieldChange", {
              detail: {
                target: e.target,
                id: id,
                value: e.target.value,
                help: helpOptions[e.target.value]
                  ? helpOptions[e.target.value]
                  : "",
              },
            })
          );
          onChange(e.target.value);
        }}
      >
        {placeholder && <option>{placeholder}</option>}
        {renderOptions(options)}
      </select>
    </BaseField>
  );
};

export default SelectField;

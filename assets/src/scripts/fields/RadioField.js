const { __ } = wp.i18n;
import BaseField from "./BaseField";

const RadioField = (props) => {
  const { options, value, onChange } = props;
  const convertFieldOptions = (options) => {
    if (typeof options !== "object") {
      return options;
    }
    const optionsArray = [];
    for (const key in options) {
      optionsArray.push({
        label: options[key],
        value: key,
      });
    }
    return optionsArray;
  };

  const renderOptions = () => {
    const convertedOptions = convertFieldOptions(options);
    return convertedOptions.map((option) => {
      return (
        <label key={option.value}>
          <input
            type="radio"
            value={option.value}
            checked={option.value === value ? "checked" : false}
            onChange={(e) => onChange(e.target.value)}
          />
          <span>{option.label}</span>
        </label>
      );
    });
  };

  return (
    <BaseField {...props} type="radio">
      <div role="radiogroup">{renderOptions()}</div>
    </BaseField>
  );
};

export default RadioField;

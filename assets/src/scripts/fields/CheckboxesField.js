const { __ } = wp.i18n;
import BaseField from "./BaseField";

const CheckboxesField = (props) => {
  const { options, value, onChange } = props;

  const convertFieldValues = () => {
    let valueArray = [];
    if (typeof value === "object" && !Array.isArray(value)) {
      for (const key in options) {
        valueArray.push(key);
      }
    } else if (Array.isArray(value)) {
      valueArray = value;
    }
    return valueArray;
  };

  const convertFieldOptions = () => {
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
    const convertedValues = convertFieldValues();
    const convertedOptions = convertFieldOptions();
    return convertedOptions.map((option) => {
      return (
        <label key={option.value}>
          <input
            type="checkbox"
            value="1"
            checked={convertedValues.includes(option.value) ? "checked" : false}
            onChange={() => {
              const updatedValue = convertedOptions.reduce((val, o) => {
                if (o.value === option.value) {
                  if (!convertedValues.includes(o.value)) {
                    val.push(o.value);
                  }
                } else {
                  if (convertedValues.includes(o.value)) {
                    val.push(o.value);
                  }
                }
                return val;
              }, []);
              onChange(updatedValue);
            }}
          />
          <span>{option.label}</span>
        </label>
      );
    });
  };

  return (
    <BaseField {...props} type="checkboxes">
      <div role="group">{renderOptions()}</div>
    </BaseField>
  );
};

export default CheckboxesField;

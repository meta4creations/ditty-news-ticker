import { __ } from "@wordpress/i18n";

const RadioField = ({ selected, options, inline, onChange }) => {
  const optionsInline = inline ? inline : false;

  const renderOptions = () => {
    return options.map((option) => {
      return (
        <span className="ditty-field__option" key={option.value}>
          <input
            type="radio"
            value={option.value}
            checked={option.value === selected ? "checked" : false}
            onChange={(e) => onChange(e.target.value)}
          />
          <label className="ditty-field__option__label">{option.label}</label>
        </span>
      );
    });
  };

  return <div role="radiogroup">{renderOptions()}</div>;
};

export default RadioField;

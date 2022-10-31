import { __ } from "@wordpress/i18n";
import { TextControl } from "@wordpress/components";

const Spacing = ({ label, options, value, onChange }) => {
  const defaults = {
    paddingTop: __("Top", "ditty-news-ticker"),
    paddingBottom: __("Bottom", "ditty-news-ticker"),
    paddingLeft: __("Left", "ditty-news-ticker"),
    paddingRight: __("Right", "ditty-news-ticker"),
  };

  const args = { ...defaults, ...options };

  const updateValue = (key, updatedValue) => {
    value[key] = updatedValue;
    onChange(value);
  };

  const renderField = () => {
    const fields = [];
    for (const [objKey, objValue] of Object.entries(args)) {
      fields.push(
        <TextControl
          key={objKey}
          value={value[objKey]}
          onChange={(updatedValue) => updateValue(objKey, updatedValue)}
        />
      );
    }
    return fields;
  };

  return (
    <div className={`dittyField dittyField--spacing`}>{renderField()}</div>
  );
};

export default Spacing;

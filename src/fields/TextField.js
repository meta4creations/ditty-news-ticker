import { __ } from "@wordpress/i18n";

const TextField = ({ value, type, onChange }) => {
  const inputType = type ? type : "text";

  return (
    <input
      type={inputType}
      value={value}
      onChange={(e) => {
        onChange(e.target.value);
      }}
    />
  );
};

export default TextField;

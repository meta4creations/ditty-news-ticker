import { RichTextEditor } from "../common";
import BaseField from "./BaseField";

const WysiwygField = (props) => {
  const { value, onChange, delayChange = false } = props;

  return (
    <BaseField {...props} type="wysiwyg">
      <RichTextEditor
        value={value}
        delayChange={delayChange}
        onChange={onChange}
      />
    </BaseField>
  );
};

export default WysiwygField;

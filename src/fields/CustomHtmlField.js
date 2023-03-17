import { html } from "@codemirror/lang-html";
import { CodeEditor } from "../common";
import BaseField from "./BaseField";

const CustomHtmlField = (props) => {
  const { value, onChange, delayChange = false } = props;

  return (
    <BaseField {...props}>
      <CodeEditor
        value={value}
        delayChange={delayChange}
        extensions={[html()]}
        onChange={onChange}
      />
    </BaseField>
  );
};

export default CustomHtmlField;

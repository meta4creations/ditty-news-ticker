import { useState, useRef, useCallback } from "@wordpress/element";
import { Editor } from "@tinymce/tinymce-react";

const RichTextEditor = ({ value, onChange, delayChange = false }) => {
  const [delayValue, setDelayValue] = useState(value);

  const editorRef = useRef(null);
  const timerRef = useRef(null);

  const handleInputChangeDelay = useCallback(
    (updatedValue) => {
      setDelayValue(updatedValue);

      // Clear the existing timer
      clearTimeout(timerRef.current);

      // Start a new timer to update the parent element
      timerRef.current = setTimeout(() => onChange(updatedValue), 500);
    },
    [onChange, delayValue]
  );

  return (
    <>
      <Editor
        apiKey="46s6s4dl57vn07zahm2mu55d6erb32kjar037mvxi12jiop1"
        onInit={(evt, editor) => (editorRef.current = editor)}
        value={delayChange ? delayValue : value}
        init={{
          resize: false,
          height: "100%",
          max_height: "100%",
          menubar: false,
          plugins: "lists",
          toolbar:
            "undo redo | formatselect | bold italic | \
              alignleft aligncenter alignright | \
              bullist numlist outdent indent",
          content_style:
            "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }",
        }}
        onEditorChange={(updatedValue) => {
          delayChange
            ? handleInputChangeDelay(updatedValue)
            : onChange(updatedValue);
        }}
      />
    </>
  );
};
export default RichTextEditor;

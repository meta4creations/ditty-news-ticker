const { useState, useEffect, useRef, useCallback } = wp.element;
import { Editor } from "@tinymce/tinymce-react";

const RichTextEditor = ({ value, onChange, delayChange = false }) => {
  const [delayValue, setDelayValue] = useState(value);

  const editorRef = useRef(null);
  const timerRef = useRef(null);

  useEffect(() => {
    return () => {
      if (delayChange) {
        clearTimeout(timerRef.current);
      }
    };
  }, []);

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
          fontsize_formats: "8px 10px 12px 14px 18px 24px 36px",
          plugins: ["lists", "textcolor", "link"],
          toolbar:
            "undo redo | bold italic | forecolor backcolor | \
              alignleft aligncenter alignright | link | \
              bullist numlist outdent indent | formatselect fontsizeselect",
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

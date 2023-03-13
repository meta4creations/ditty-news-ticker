import { Editor } from "@tinymce/tinymce-react";

const RichText = ({ value, onChange }) => {
  return (
    <Editor
      initialValue={value}
      init={{
        height: 500,
        menubar: false,
        plugins: [
          "advlist autolink lists link image",
          "charmap print preview anchor help",
          "searchreplace visualblocks code",
          "insertdatetime media table paste wordcount",
        ],
        toolbar:
          "undo redo | formatselect | bold italic | \
            alignleft aligncenter alignright | \
            bullist numlist outdent indent | help",
      }}
      onChange={onChange}
    />
  );
};
export default RichText;

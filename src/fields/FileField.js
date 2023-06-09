import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { Button } from "../components";
import BaseField from "./BaseField";
import TextField from "./TextField";

const FileField = (props) => {
  const { value, onChange, mediaTitle, mediaButton, fileTypes, multiple } =
    props;

  const getTimestamp = () => {
    return Math.floor(Date.now() / 1000);
  };
  const [textFieldKey, setTextFieldKey] = useState(getTimestamp());

  let uploader;
  const runUploader = (e) => {
    e.preventDefault();

    // If the media uploader already exists, reopen it.
    if (uploader) {
      uploader.open();
      return;
    }

    // Create a new media uploader
    uploader = wp.media({
      title: mediaTitle,
      button: {
        text: mediaButton,
      },
      multiple: multiple,
      library: {
        type: fileTypes,
      },
    });

    uploader.on("select", function () {
      var attachments = uploader.state().get("selection").toJSON();
      if (attachments.length) {
        onChange(attachments[0].url);
        setTextFieldKey(getTimestamp());
      }
    });

    // Finally, open the modal on click
    uploader.open();
  };

  return (
    <BaseField {...props}>
      <TextField
        key={textFieldKey}
        value={value}
        type="url"
        onChange={onChange}
        delayChange={true}
        raw={true}
      />
      <Button size="small" onClick={runUploader}>
        {__("Upload File", "ditty-news-ticker")}
      </Button>
    </BaseField>
  );
};

export default FileField;

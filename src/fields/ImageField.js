import { __ } from "@wordpress/i18n";
import { useState, useRef, useEffect, useCallback } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPlus } from "@fortawesome/pro-light-svg-icons";
import { Button } from "../components";
import BaseField from "./BaseField";

const ImageField = (props) => {
  const { value, onChange, mediaTitle, mediaButton, fileTypes, multiple } =
    props;

  console.log("value", value);

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
        // const imageData = attachments.map((attachment) => {
        //   return {
        //     id: attachment.id,
        //     title: attachment.title,
        //     caption: attachment.caption,
        //     description: attachment.description,
        //     link: attachment.link,
        //     url: attachment.url,
        //     sizes: attachment.sizes,
        //   };
        // });
        const imageData = {
          id: attachments[0].id,
          title: attachments[0].title,
          caption: attachments[0].caption,
          description: attachments[0].description,
          link: attachments[0].link,
          url: attachments[0].url,
          sizes: attachments[0].sizes,
        };
        console.log("imageData", imageData);
        onChange(imageData);
      }
    });

    // Finally, open the modal on click
    uploader.open();
  };

  return (
    <BaseField {...props}>
      <button onClick={runUploader}>
        {value && <img src={value.sizes.medium.url} />}
        <FontAwesomeIcon icon={faPlus} />
      </button>
    </BaseField>
  );
};

export default ImageField;

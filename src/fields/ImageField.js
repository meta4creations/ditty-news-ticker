import axios from "axios";
import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
//import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
//import { faPlus } from "@fortawesome/pro-light-svg-icons";
import BaseField from "./BaseField";

const ImageField = (props) => {
  const { value, onChange, mediaTitle, mediaButton, fileTypes, multiple } =
    props;

  let initialImagePreview = value && value.sizes ? value.sizes.full.url : false;
  if (value && value.sizes && value.sizes.large) {
    initialImagePreview = value.sizes.large.url;
  }
  const [imagePreview, setImagePreview] = useState(initialImagePreview);

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
      description: "this is a description",
      button: {
        text: mediaButton,
      },
      multiple: multiple,
      library: {
        type: fileTypes,
      },
    });

    uploader.on("open", function () {
      var selection = uploader.state().get("selection");

      const ids = Array.isArray(value)
        ? value.map((attachment) => {
            return typeof attachment === "object" ? attachment.id : attachment;
          })
        : typeof value === "object"
        ? [value.id]
        : [value];

      ids.forEach(function (id) {
        let attachment = wp.media.attachment(id);
        selection.add(attachment ? [attachment] : []);
      });
    });

    uploader.on("select", function () {
      var attachments = uploader.state().get("selection").toJSON();
      if (attachments.length) {
        const imageData = {
          id: attachments[0].id,
          title: attachments[0].title,
          caption: attachments[0].caption,
          description: attachments[0].description,
          link: attachments[0].link,
          url: attachments[0].url,
          sizes: attachments[0].sizes,
        };

        let newImagePreview = imageData.sizes.large
          ? imageData.sizes.large.url
          : imageData.sizes.full.url;

        setImagePreview(newImagePreview);
        onChange(imageData);
      }
    });

    // Finally, open the modal on click
    uploader.open();
  };

  const getImage = async (imageId) => {
    const apiURL = `${dittyEditorVars.siteUrl}/wp-json/wp/v2/media/${imageId}`;

    try {
      await axios.get(apiURL, {}).then((res) => {
        if (
          res.data &&
          res.data.media_details &&
          res.data.media_details.sizes
        ) {
          let newImagePreview = res.data.media_details.sizes.large
            ? res.data.media_details.sizes.large.url
            : res.data.media_details.sizes.full.url;
          setImagePreview(newImagePreview);
        }
      });
    } catch (ex) {
      //console.log("ex", ex);
    }
  };
  if (!imagePreview && value) {
    getImage(value);
  }

  return (
    <BaseField {...props}>
      <button onClick={runUploader}>
        {imagePreview && <img src={imagePreview} />}
        <i className="fa-solid fa-plus"></i>
      </button>
    </BaseField>
  );
};

export default ImageField;

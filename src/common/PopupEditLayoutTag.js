import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import _ from "lodash";
import { toast } from "react-toastify";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPaintbrushPencil, faCopy } from "@fortawesome/pro-light-svg-icons";
import { getAttributeFields } from "../utils/layouts";
import { FieldList } from "../fields";
import { Button, ButtonGroup, Link, IconBlock, Popup } from "../components";

const PopupEditLayoutTag = ({
  layoutTag,
  submitLabel = __("Update Layout", "ditty-news-ticker"),
  onClose,
  level,
}) => {
  const [attributeValues, setAttributeValues] = useState({});

  console.log("layoutTag.atts", layoutTag.atts);

  /**
   * Return the layout tag render
   * @returns string
   */
  const renderTag = () => {
    const atts = layoutTag.atts;
    let renderedTag = layoutTag.tag;
    for (const key in atts) {
      if (!attributeValues[key]) {
        continue;
      }
      if (typeof atts[key] === "object") {
        if (!atts[key].std || attributeValues[key] !== atts[key].std) {
          renderedTag += ` ${key}="${attributeValues[key]}"`;
        }
      } else {
        if (attributeValues[key] !== atts[key]) {
          renderedTag += ` ${key}="${attributeValues[key]}"`;
        }
      }
    }
    return `{${renderedTag}}`;
  };

  const renderPopupHeader = () => {
    return (
      <>
        <IconBlock
          icon={<FontAwesomeIcon icon={faPaintbrushPencil} />}
          className="ditty-icon-block--heading"
        >
          <div className="ditty-icon-block--heading__title">
            <h2>{`{${layoutTag.tag}}`}</h2>
          </div>
          <p>{layoutTag.description}</p>
        </IconBlock>
        <div style={{ padding: "0 10px 10px" }}>
          <div style={{ position: "relative" }}>
            <pre
              style={{
                padding: "10px",
                margin: "0",
                background: "#eee",
                borderRadius: "3px",
                whiteSpace: "pre-line",
              }}
            >
              {renderTag()}
            </pre>
            <FontAwesomeIcon
              icon={faCopy}
              style={{
                position: "absolute",
                top: "0",
                right: "0",
                padding: "5px",
                width: "20px",
                height: "20px",
                background: "rgba(255,255,255,.5)",
                cursor: "pointer",
              }}
              onClick={() => {
                navigator.clipboard.writeText(renderTag());
                toast("Tag shortcode copied to clipboard!", {
                  position: "top-right",
                  autoClose: 5000,
                  hideProgressBar: false,
                  closeOnClick: true,
                  pauseOnHover: true,
                  draggable: true,
                  progress: undefined,
                  theme: "colored",
                });
              }}
            />
          </div>
        </div>
      </>
    );
  };

  const renderPopupFooter = () => {
    return (
      <ButtonGroup justify="flex-end" gap="20px">
        <Link onClick={onClose}>{__("Cancel", "ditty-news-ticker")}</Link>
        <Button
          type="primary"
          id="editLayout__insertTag"
          onClick={() => {
            window.dispatchEvent(
              new CustomEvent("dittyEditorInsertLayoutTag", {
                detail: { renderedTag: renderTag() },
              })
            );
            onClose();
          }}
        >
          {__("Insert Tag", "ditty-news-ticker")}
        </Button>
      </ButtonGroup>
    );
  };

  const renderPopupContents = () => {
    const fields = getAttributeFields(layoutTag.atts);
    return (
      <FieldList
        name={__("Tag attribute options", "ditty-news-ticker")}
        description={__(
          "Set the default attribute options for the tag",
          "ditty-news-ticker"
        )}
        fields={fields}
        values={attributeValues}
        onUpdate={(id, value) => {
          const updatedAttributeValues = { ...attributeValues };
          updatedAttributeValues[id] = value;
          console.log("updatedAttributeValues", updatedAttributeValues);
          setAttributeValues(updatedAttributeValues);
        }}
      />
    );
  };

  return (
    <>
      <Popup
        id="editLayoutTag"
        submitLabel={submitLabel}
        header={renderPopupHeader()}
        footer={renderPopupFooter()}
        level={level}
      >
        {renderPopupContents()}
      </Popup>
    </>
  );
};
export default PopupEditLayoutTag;

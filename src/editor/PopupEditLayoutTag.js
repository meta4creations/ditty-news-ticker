import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPaintbrushPencil, faCopy } from "@fortawesome/pro-light-svg-icons";
import { FieldList } from "../fields";
import { Button, ButtonGroup, Link, IconBlock, Popup } from "../components";

const PopupEditLayoutTag = ({
  layoutTag,
  submitLabel = __("Update Layout", "ditty-news-ticker"),
  onClose,
  level,
}) => {
  const [attributeValues, setAttributeValues] = useState({});

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

  const getTagFields = () => {
    const atts = layoutTag.atts;
    const fields = [];
    for (const key in atts) {
      if (typeof atts[key] === "object") {
        fields.push(atts[key]);
      } else {
        fields.push({
          type: "text",
          id: key,
          name: key,
          std: atts[key],
        });
      }
    }
    return fields;
  };

  const renderPopupHeader = () => {
    return (
      <>
        <IconBlock
          icon={<FontAwesomeIcon icon={faPaintbrushPencil} />}
          className="editLayout__header"
        >
          <div className="itemEdit__header__type">
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
                width: "25px",
                height: "25px",
                background: "rgba(255,255,255,.5)",
                cursor: "pointer",
              }}
              onClick={() => navigator.clipboard.writeText(renderTag())}
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
    const fields = getTagFields();
    return (
      <FieldList
        name={__("Tag attribute options", "ditty-news-ticker")}
        desc={__(
          "Set the default attribute options for the tag",
          "ditty-news-ticker"
        )}
        fields={fields}
        values={attributeValues}
        onUpdate={(id, value) => {
          const updatedAttributeValues = { ...attributeValues };
          updatedAttributeValues[id] = value;
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
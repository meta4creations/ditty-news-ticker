import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPaintbrushPencil } from "@fortawesome/pro-light-svg-icons";
import {
  getItemTypeObject,
  getItemLabel,
  getItemTypes,
  getItemTypeSettings,
} from "../utils/itemTypes";
import { getTagFields } from "../utils/layouts";
import {
  Button,
  ButtonGroup,
  IconBlock,
  Link,
  Popup,
  Tabs,
} from "../components";
import { FieldList } from "../fields";
import PopupTypeSelector from "./PopupTypeSelector";

const PopupEditItem = ({
  item,
  submitLabel = __("Update Item", "ditty-news-ticker"),
  onChange,
  onClose,
  onUpdate,
  onDelete,
}) => {
  const [editItem, setEditItem] = useState(item);
  const [updateKeys, setUpdateKeys] = useState([]);
  const [childPopupStatus, setChildPopupStatus] = useState(false);

  const itemTypeObject = getItemTypeObject(editItem);
  const itemTypes = getItemTypes();

  const fieldGroups = getItemTypeSettings(editItem);
  fieldGroups.push({
    desc: __(
      "Customize the layout tag attributes for this item.",
      "ditty-news-ticker"
    ),
    icon: <FontAwesomeIcon icon={faPaintbrushPencil} />,
    id: "layoutAttributes",
    label: __("Tags", "ditty-news-ticker"),
    name: __("Layout Tag Attribute Customizations", "ditty-news-ticker"),
    fields: getTagFields(itemTypeObject.layoutTags),
  });

  const initialTab = fieldGroups.length ? fieldGroups[0].id : "";
  const [currentTabId, setCurrentTabId] = useState(initialTab);

  const getCurrentFieldGroup = () => {
    const index = fieldGroups.findIndex((fieldGroup) => {
      return fieldGroup.id === currentTabId;
    });
    if (-1 === index) {
      return false;
    }
    return fieldGroups[index];
  };
  const currentFieldGroup = getCurrentFieldGroup();

  const addItemUpdate = (updatedItem, key) => {
    setEditItem(updatedItem);
    if (!updateKeys.includes(key)) {
      updateKeys.push(key);
      setUpdateKeys(updateKeys);
    }
    onChange(updatedItem);
  };

  /**
   * Render a popup component
   * @returns Popup component
   */
  const renderChildPopup = () => {
    switch (childPopupStatus) {
      case "itemTypeSelect":
        return (
          <PopupTypeSelector
            level="2"
            currentType={editItem.item_type}
            types={itemTypes}
            getTypeObject={getItemTypeObject}
            onClose={() => {
              setChildPopupStatus(false);
            }}
            onUpdate={(updatedType) => {
              setChildPopupStatus(false);

              const updatedItem = { ...editItem };
              updatedItem.item_type = updatedType;
              addItemUpdate(updatedItem, "item_type");
            }}
          />
        );
      default:
        return;
    }
  };

  const renderPopupHeader = () => {
    return (
      <>
        <IconBlock
          icon={itemTypeObject && itemTypeObject.icon}
          className="ditty-icon-block--heading"
        >
          <div className="ditty-icon-block--heading__title">
            <h2>{itemTypeObject && itemTypeObject.label}</h2>
            <Link onClick={() => setChildPopupStatus("itemTypeSelect")}>
              {__("Change Type", "ditty-news-ticker")}
            </Link>
          </div>
          <p>{getItemLabel(editItem)}</p>
        </IconBlock>
        {fieldGroups.length > 1 && (
          <Tabs
            type="cloud"
            tabs={fieldGroups}
            currentTabId={currentTabId}
            tabClick={(tab) => setCurrentTabId(tab.id)}
            className="itemEdit__header__tabs"
          />
        )}
      </>
    );
  };

  const renderPopupFooter = () => {
    return (
      <ButtonGroup justify="flex-end" gap="20px">
        <Link
          style={{ marginRight: "auto", color: "#cc1818" }}
          onClick={onDelete}
        >
          {__("Delete", "ditty-news-ticker")}
        </Link>
        <Link onClick={onClose}>{__("Cancel", "ditty-news-ticker")}</Link>
        <Button
          type="primary"
          onClick={() => {
            onUpdate(editItem, updateKeys);
          }}
        >
          <span>
            {submitLabel ? submitLabel : __("Submit", "ditty-news-ticker")}
          </span>
        </Button>
      </ButtonGroup>
    );
  };

  const renderPopupContents = () => {
    if ("layoutAttributes" === currentFieldGroup.id) {
      return (
        <FieldList
          name={currentFieldGroup.name}
          desc={currentFieldGroup.desc}
          fields={currentFieldGroup.fields}
          values={{}}
          onUpdate={(id, value) => {
            console.log(id, value);
            // const updatedItem = { ...editItem };
            // if (
            //   !updatedItem.item_value ||
            //   typeof updatedItem.item_value !== "object" ||
            //   Array.isArray(updatedItem.item_value)
            // ) {
            //   updatedItem.item_value = {};
            // }
            // updatedItem.item_value[id] = value;
            // addItemUpdate(updatedItem, "item_value");
          }}
        />
      );
    } else {
      return (
        <FieldList
          name={currentFieldGroup.name}
          desc={currentFieldGroup.desc}
          fields={currentFieldGroup.fields}
          values={editItem.item_value}
          onUpdate={(id, value) => {
            const updatedItem = { ...editItem };
            if (
              !updatedItem.item_value ||
              typeof updatedItem.item_value !== "object" ||
              Array.isArray(updatedItem.item_value)
            ) {
              updatedItem.item_value = {};
            }
            updatedItem.item_value[id] = value;
            addItemUpdate(updatedItem, "item_value");
          }}
        />
      );
    }
  };

  return (
    <>
      <Popup
        id="itemEdit"
        submitLabel={submitLabel}
        header={renderPopupHeader()}
        footer={renderPopupFooter()}
        onClose={() => {
          onClose("onClose");
        }}
        onSubmit={() => {
          onUpdate(editItem, updateKeys);
        }}
      >
        {renderPopupContents()}
      </Popup>
      {renderChildPopup()}
    </>
  );
};
export default PopupEditItem;

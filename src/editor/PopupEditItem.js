import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { applyFilters } from "@wordpress/hooks";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPaintbrushPencil } from "@fortawesome/pro-light-svg-icons";
import { PopupTypeSelector } from "../common";
import {
  Button,
  ButtonGroup,
  IconBlock,
  Link,
  Popup,
  Tabs,
} from "../components";
import { FieldList } from "../fields";
import {
  getItemTypeObject,
  getItemLabel,
  getItemTypes,
  getItemTypeSettings,
  getItemTypePreviewIcon,
} from "../utils/itemTypes";
import { getTagFields } from "../utils/layouts";

const PopupEditItem = ({
  editor,
  item,
  editType = "editItem",
  onChange,
  onClose,
  onUpdate,
  onDelete,
}) => {
  const [editItem, setEditItem] = useState(_.cloneDeep(item));
  const [updateKeys, setUpdateKeys] = useState([]);
  const [childPopupStatus, setChildPopupStatus] = useState(false);

  const itemTypeObject = getItemTypeObject(editItem);
  const itemTypes = getItemTypes();

  let fieldGroups = getItemTypeSettings(editItem);
  fieldGroups.push({
    id: "layoutCustomizations",
    label: __("Customize", "ditty-news-ticker"),
    name: __("Layout Tag Customizations", "ditty-news-ticker"),
    description: __(
      "Customize the layout tags that are using in Layouts for this item. Keep in mind that some layouts may not use all of these tags.",
      "ditty-news-ticker"
    ),
    icon: <FontAwesomeIcon icon={faPaintbrushPencil} />,
    fields: getTagFields(
      editItem.layoutTags ? editItem.layoutTags : itemTypeObject.layoutTags
    ),
  });
  fieldGroups = applyFilters(
    "dittyEditor.itemFieldGroups",
    fieldGroups,
    editItem,
    editor
  );

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
    onChange && onChange(updatedItem);
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
              if (updatedType === editItem.item_type) {
                return false;
              }

              const updatedItem = { ...editItem };
              delete updatedItem.layoutTags;
              const updatedItemTypeObject = getItemTypeObject(updatedType);
              updatedItem.item_type = updatedType;
              updatedItem.item_value = {
                ...updatedItemTypeObject.defaultValues,
                ...updatedItem.item_value,
              };

              // Set the current tab
              const fieldGroups = getItemTypeSettings(updatedType);
              if (fieldGroups.length) {
                setCurrentTabId(fieldGroups[0].id);
              } else {
                setCurrentTabId("layoutCustomizations");
              }

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
          icon={getItemTypePreviewIcon(item)}
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

  const renderPopupContents = () => {
    const itemKey =
      "layoutCustomizations" === currentTabId
        ? "attribute_value"
        : "item_value";

    return (
      <FieldList
        name={currentFieldGroup.name}
        description={currentFieldGroup.description}
        fields={currentFieldGroup.fields}
        values={editItem[itemKey] ? editItem[itemKey] : {}}
        delayChange={true}
        onUpdate={(id, value) => {
          const updatedItem = { ...editItem };
          if (
            !updatedItem[itemKey] ||
            typeof updatedItem[itemKey] !== "object" ||
            Array.isArray(updatedItem[itemKey])
          ) {
            updatedItem[itemKey] = {};
          }
          updatedItem[itemKey][id] = value;
          addItemUpdate(updatedItem, itemKey);
        }}
      />
    );
  };

  const renderPopupFooter = () => {
    return (
      <ButtonGroup justify="flex-end" gap="20px">
        {"editItem" === editType && (
          <>
            {/* <Link
              style={{ marginRight: "auto", color: "#cc1818" }}
              onClick={onDelete}
            >
              {__("Delete", "ditty-news-ticker")}
            </Link> */}
            <Link onClick={onClose}>{__("Cancel", "ditty-news-ticker")}</Link>
          </>
        )}
        <Button
          type="primary"
          onClick={() => {
            onUpdate(editItem, updateKeys);
          }}
        >
          <span>{__("Update Item Settings", "ditty-news-ticker")}</span>
        </Button>
      </ButtonGroup>
    );
  };

  return (
    <>
      <Popup
        id="itemEdit"
        className={`ditty-edit-item-type--${editItem.item_type} ditty-edit-item-type--${editItem.item_type}--${currentTabId}`}
        submitLabel={
          "editItem" === editType
            ? __("Update Item", "ditty-news-ticker")
            : __("Add Item", "ditty-news-ticker")
        }
        header={renderPopupHeader()}
        footer={renderPopupFooter()}
      >
        {renderPopupContents()}
      </Popup>
      {renderChildPopup()}
    </>
  );
};
export default PopupEditItem;

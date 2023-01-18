import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import _ from "lodash";
import {
  getItemTypeObject,
  getItemLabel,
  getItemTypes,
  getItemTypeSettings,
} from "../utils/itemTypes";
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

const PopupItemEdit = ({
  item,
  submitLabel = __("Update Item", "ditty-news-ticker"),
  onChange,
  onClose,
  onUpdate,
  onDelete,
}) => {
  const [editItem, setEditItem] = useState(item);
  const [updateKeys, setUpdateKeys] = useState([]);
  const [popupStatus, setPopupStatus] = useState(false);

  const itemTypeObject = getItemTypeObject(editItem);
  const itemTypes = getItemTypes();

  const fieldGroups = getItemTypeSettings(editItem);
  const initialTab = fieldGroups.length ? fieldGroups[0].id : "";
  const [currentTabId, setCurrentTabId] = useState(initialTab);

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
  const renderPopup = () => {
    switch (popupStatus) {
      case "itemTypeSelect":
        return (
          <PopupTypeSelector
            level="2"
            currentType={editItem.item_type}
            types={itemTypes}
            getTypeObject={getItemTypeObject}
            onClose={() => {
              setPopupStatus(false);
            }}
            onUpdate={(updatedType) => {
              setPopupStatus(false);

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

  return (
    <>
      <Popup
        id="itemEdit"
        submitLabel={submitLabel}
        header={
          <>
            <IconBlock
              icon={itemTypeObject && itemTypeObject.icon}
              className="itemEdit__header"
            >
              <div className="itemEdit__header__type">
                <h2>{itemTypeObject && itemTypeObject.label}</h2>
                <Link onClick={() => setPopupStatus("itemTypeSelect")}>
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
        }
        footer={
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
        }
        onClose={() => {
          onClose("onClose");
        }}
        onSubmit={() => {
          onUpdate(editItem, updateKeys);
        }}
      >
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
      </Popup>
      {renderPopup()}
    </>
  );
};
export default PopupItemEdit;

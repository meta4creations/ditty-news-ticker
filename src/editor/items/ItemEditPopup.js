import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import {
  getItemTypeObject,
  getItemLabel,
  getItemTypes,
} from "../utils/itemTypes";
import { IconBlock, Link, Popup, Tabs } from "../../components";
import TypeSelectorPopup from "../TypeSelectorPopup";

const ItemEditPopup = ({
  item,
  submitLabel = __("Update Item", "ditty-news-ticker"),
  onChange,
  onClose,
  onUpdate,
}) => {
  const [editItem, setEditItem] = useState(item);
  const [popupStatus, setPopupStatus] = useState(false);

  const itemTypeObject = getItemTypeObject(editItem);
  const itemTypes = getItemTypes();

  /**
   * Render a popup component
   * @returns Popup component
   */
  const renderPopup = () => {
    switch (popupStatus) {
      case "itemTypeSelect":
        return (
          <TypeSelectorPopup
            level="2"
            activeType={editItem.item_type}
            types={itemTypes}
            getTypeObject={getItemTypeObject}
            onClose={() => {
              setPopupStatus(false);
            }}
            onUpdate={(updatedType) => {
              setPopupStatus(false);

              const updatedItem = { ...editItem };
              updatedItem.item_type = updatedType;
              setEditItem(updatedItem);
            }}
          />
        );
      default:
        return;
    }
  };

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
          </>
        }
        onClose={() => {
          onClose("onClose");
        }}
        onSubmit={() => {
          onUpdate("onUpdate");
        }}
      >
        {/* <Tabs
        tabs={types}
        currentTabId={selectedType}
        type="cloud"
        className="typeSelector"
        tabClick={(data) => {
          if (data.id === selectedType) {
            return false;
          }
          onChange && onChange(data.id);
          setSelectedType(data.id);
        }}
      /> */}
      </Popup>
      {renderPopup()}
    </>
  );
};
export default ItemEditPopup;

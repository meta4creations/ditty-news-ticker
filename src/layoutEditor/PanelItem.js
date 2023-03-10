import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import _ from "lodash";
import { PopupTypeSelector } from "../common";
import { IconBlock, Panel, Link, Tabs } from "../components";
import { FieldList } from "../fields";
import {
  getItemTypeObject,
  getItemTypes,
  getItemTypeSettings,
} from "../utils/itemTypes";

const PanelItem = ({ editorItem, onUpdateEditorItem }) => {
  const [popupStatus, setPopupStatus] = useState(false);
  const itemTypeObject = getItemTypeObject(editorItem.item_type);
  const itemTypes = getItemTypes();

  const fieldGroups = getItemTypeSettings(editorItem);

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

  /**
   * Render a popup component
   * @returns Popup component
   */
  const renderPopup = () => {
    const dittyEl = document.getElementById("ditty-editor__ditty");
    switch (popupStatus) {
      case "itemTypeSelect":
        return (
          <PopupTypeSelector
            level="1"
            currentType={editorItem.item_type}
            types={itemTypes}
            getTypeObject={getItemTypeObject}
            onClose={() => {
              setPopupStatus(false);
            }}
            onUpdate={(updatedType) => {
              setPopupStatus(false);

              const updatedItem = { ...editorItem };
              updatedItem.item_type = updatedType;
              onUpdateEditorItem(updatedItem);
            }}
          />
        );
      default:
        return;
    }
  };

  const panelHeader = () => {
    return (
      <>
        <IconBlock
          icon={itemTypeObject.icon}
          className="ditty-icon-block--heading"
        >
          <div className="ditty-icon-block--heading__title">
            <h2>{itemTypeObject.label}</h2>
            <Link onClick={() => setPopupStatus("itemTypeSelect")}>
              {__("Change Type", "ditty-news-ticker")}
            </Link>
          </div>
          <p>{itemTypeObject.description}</p>
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

  return (
    <>
      <Panel id="editLayoutItem" header={panelHeader()}>
        <FieldList
          name={currentFieldGroup.name}
          description={currentFieldGroup.description}
          fields={currentFieldGroup.fields}
          values={editorItem.item_value}
          delayChange={true}
          onUpdate={(id, value) => {
            const updatedItem = { ...editorItem };
            if (
              !updatedItem.item_value ||
              typeof updatedItem.item_value !== "object" ||
              Array.isArray(updatedItem.item_value)
            ) {
              updatedItem.item_value = {};
            }
            updatedItem.item_value[id] = value;
            onUpdateEditorItem(updatedItem);
          }}
        />
      </Panel>
      {renderPopup()}
    </>
  );
};
export default PanelItem;

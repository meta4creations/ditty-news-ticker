import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faPenToSquare,
  faPenRuler,
  faAngleLeft,
} from "@fortawesome/pro-light-svg-icons";
import { ButtonGroup, Button, IconBlock, Link, Panel } from "../../components";
import { getItemTypes, getItemTypeObject } from "../utils/itemTypes";
import { LayoutList } from "../layouts";
import { EditorContext } from "../context";
import TypeSelectorPopup from "../TypeSelectorPopup";
import ItemSettings from "./ItemSettings";

const ItemEdit = ({ item, items, goBack, deleteItem }) => {
  const { actions } = useContext(EditorContext);
  const [currentTabId, setCurrentTabId] = useState("settings");
  const [popupStatus, setPopupStatus] = useState(false);
  const itemTypeObject = getItemTypeObject(item);
  const itemTypes = getItemTypes();

  const handleOnUpdateSettings = (item, id, value) => {
    const updatedItem = { ...item };
    const updatedItemValue = _.cloneDeep(item.item_value);
    updatedItemValue[id] = value;
    updatedItem.item_value = updatedItemValue;
    actions.updateItem(updatedItem, "item_value");
  };

  const handleTabClick = (tab) => {
    setCurrentTabId(tab.id);
  };

  /**
   * Render a popup component
   * @returns Popup component
   */
  const renderPopup = () => {
    const dittyEl = document.getElementById("ditty-editor__ditty");
    switch (popupStatus) {
      case "itemTypeSelect":
        return (
          <TypeSelectorPopup
            activeType={item.item_type}
            types={itemTypes}
            getTypeObject={getItemTypeObject}
            onClose={() => {
              setPopupStatus(false);
            }}
            onUpdate={(updatedType) => {
              setPopupStatus(false);

              const updatedItem = { ...item };
              updatedItem.item_type = updatedType;
              actions.updateItem(updatedItem, "item_type");

              console.log("updatedType", updatedType);
              console.log("item", item);
              // if (currentDisplay.type === updatedType) {
              //   return false;
              // }
              // const updatedDisplay = { ...currentDisplay };
              // updatedDisplay.type = updatedType;
              // actions.setCurrentDisplay(updatedDisplay);
            }}
          />
        );
      default:
        return;
    }
  };

  const panelHeader = () => {
    const count = items.length;
    return (
      <>
        <IconBlock icon={itemTypeObject.icon} className="displayEditType">
          <h3>{itemTypeObject.label}</h3>
          <Link onClick={() => setPopupStatus("itemTypeSelect")}>
            {__("Change Type", "ditty-news-ticker")}
          </Link>
        </IconBlock>
        <ButtonGroup>
          <Button onClick={goBack}>
            <FontAwesomeIcon icon={faAngleLeft} />
            {__(`Back - ${count} items`, "ditty-news-ticker")}
          </Button>
          <Button
            onClick={() => {
              deleteItem(item);
            }}
          >
            {__("Delete", "ditty-news-ticker")}
          </Button>
        </ButtonGroup>
      </>
    );
  };

  const tabs = dittyEditor.applyFilters("dittyItemEditTabs", [
    {
      id: "settings",
      icon: <FontAwesomeIcon icon={faPenToSquare} />,
      label: __("Settings", "ditty-news-ticker"),
      content: (
        <ItemSettings item={item} onUpdateSettings={handleOnUpdateSettings} />
      ),
    },
    {
      id: "layout",
      icon: <FontAwesomeIcon icon={faPenRuler} />,
      label: __("Layout", "ditty-news-ticker"),
      content: <LayoutList item={item} editor={EditorContext} />,
    },
  ]);

  const panelContent = () => {
    const index = tabs.findIndex((tab) => {
      return tab.id === currentTabId;
    });
    if (-1 === index) {
      return false;
    }
    return tabs[index].content;
  };

  return (
    <>
      <Panel
        id="itemEdit"
        header={panelHeader()}
        tabs={tabs}
        tabClick={handleTabClick}
        currentTabId={currentTabId}
        tabsType="cloud"
      >
        {panelContent()}
      </Panel>
      {renderPopup()}
    </>
  );
};
export default ItemEdit;

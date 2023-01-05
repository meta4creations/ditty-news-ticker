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
import { getItemTypeObject } from "../utils/itemTypes";
import { LayoutList } from "../layouts";
import { EditorContext } from "../context";
import ItemSettings from "./ItemSettings";

const ItemEdit = ({ item, items, goBack, deleteItem }) => {
  const { actions } = useContext(EditorContext);
  const [currentTabId, setCurrentTabId] = useState("settings");
  const itemTypeObject = getItemTypeObject(item);

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

  const panelHeader = () => {
    const count = items.length;
    return (
      <>
        <IconBlock icon={itemTypeObject.icon}>
          <h3>{itemTypeObject.label}</h3>
          {/* <Link onClick={() => setPopupStatus("displayTypeSelect")}>
            {__("Change Type", "ditty-news-ticker")}
          </Link> */}
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

  const tabs = window.dittyHooks.applyFilters("dittyItemEditTabs", [
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
  );
};
export default ItemEdit;

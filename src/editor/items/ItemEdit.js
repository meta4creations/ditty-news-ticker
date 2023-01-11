import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faAngleLeft,
  faPaintbrushPencil,
} from "@fortawesome/pro-light-svg-icons";
import { ButtonGroup, Button, IconBlock, Link, Panel } from "../../components";
import { FieldList } from "../../fields";
import {
  getItemTypes,
  getItemTypeSettings,
  getItemTypeObject,
} from "../utils/itemTypes";
import { LayoutList } from "../layouts";
import { EditorContext } from "../context";
import PopupTypeSelector from "../PopupTypeSelector";

const ItemEdit = ({ item, items, goBack, deleteItem }) => {
  const { actions } = useContext(EditorContext);
  const itemTypeObject = getItemTypeObject(item);
  const fieldGroups = getItemTypeSettings(item);
  const initialTab = fieldGroups.length ? fieldGroups[0].id : "";

  const [currentTabId, setCurrentTabId] = useState(initialTab);
  const [popupStatus, setPopupStatus] = useState(false);

  const itemTypes = getItemTypes();

  const handleOnUpdateSettings = (id, value) => {
    const updatedItem = { ...item };
    const updatedItemValue = _.cloneDeep(item.item_value);
    updatedItemValue[id] = value;
    updatedItem.item_value = updatedItemValue;
    actions.updateItem(updatedItem, "item_value");
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
          <PopupTypeSelector
            currentType={item.item_type}
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
        <ButtonGroup>
          <Button onClick={goBack}>
            <FontAwesomeIcon icon={faAngleLeft} />
            {__(`Back - ${count} items`, "ditty-news-ticker")}
          </Button>
          <Link
            onClick={() => {
              deleteItem(item);
            }}
          >
            {__("Delete", "ditty-news-ticker")}
          </Link>
        </ButtonGroup>
        <IconBlock
          icon={<FontAwesomeIcon icon={faPaintbrushPencil} />}
          className="editType"
        >
          <h3>Layout name</h3>
          <Link onClick={() => setPopupStatus("layoutSelect")}>
            {__("Change Layout", "ditty-news-ticker")}
          </Link>
        </IconBlock>
        <IconBlock icon={itemTypeObject.icon} className="editType">
          <h3>{itemTypeObject.label}</h3>
          <Link onClick={() => setPopupStatus("itemTypeSelect")}>
            {__("Change Type", "ditty-news-ticker")}
          </Link>
        </IconBlock>
      </>
    );
  };

  const panelContent = () => {
    const index = fieldGroups.findIndex((fieldGroup) => {
      return fieldGroup.id === currentTabId;
    });
    if (-1 === index) {
      return false;
    }

    const fieldGroup = fieldGroups[index];
    return (
      <FieldList
        name={fieldGroup.name}
        desc={fieldGroup.desc}
        fields={fieldGroup.fields}
        values={item.item_value}
        onUpdate={(id, value) => {
          handleOnUpdateSettings(id, value);
        }}
      />
    );
  };

  return (
    <>
      <Panel
        id="itemEdit"
        header={panelHeader()}
        tabs={fieldGroups.length > 1 ? fieldGroups : null}
        tabClick={(tab) => setCurrentTabId(tab.id)}
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

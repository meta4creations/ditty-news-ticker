import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { List, ListItem } from "../../../components";
import {
  getDisplayTypes,
  getDisplayObject,
  getDisplayTypeIcon,
} from "../../utils/displayTypes";
import Panel from "../components/Panel";

const DisplayTypes = ({ display, editor }) => {
  const displayTypes = getDisplayTypes();

  /**
   * Set up the elements
   */
  const elements = window.dittyHooks.applyFilters(
    "dittyEditorDisplayTypesListElements",
    [
      {
        id: "icon",
        content: (displayType) => displayType.icon,
      },
      {
        id: "content",
        content: (displayType) => {
          return (
            <>
              <h3>{displayType.label}</h3>
              <p>{displayType.description}</p>
            </>
          );
        },
      },
    ],
    editor
  );

  const panelHeader = () => {
    return (
      <>
        <h2>{__("Display Types", "ditty-news-ticker")}</h2>
      </>
    );
  };

  const handleItemClick = (e, itemType) => {
    console.log(itemType);
    //addItem(itemType.id);
  };

  const handleElementClick = (e, elementId, itemType) => {
    //console.log("elementId", elementId);
  };

  const renderItems = () => {
    return displayTypes.map((displayType) => {
      return (
        <ListItem
          key={displayType.id}
          data={displayType}
          elements={elements}
          onItemClick={handleItemClick}
        />
      );
    });
  };

  const panelContent = () => {
    return <List>{renderItems()}</List>;
  };

  return (
    <Panel id="displayTypes" header={panelHeader()} content={panelContent()} />
  );
};
export default DisplayTypes;

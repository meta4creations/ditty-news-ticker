import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import Panel from "../Panel";
import List from "../../common/List";
import Item from "../../common/Item";

const DisplayTypes = ({ display, editor }) => {
  const { displayTypes } = useContext(editor);

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
        <Item
          key={displayType.id}
          data={displayType}
          elements={elements}
          onItemClick={handleItemClick}
        />
      );
    });
  };

  const panelContent = () => {
    return <List items={renderItems()} />;
  };

  return (
    <Panel id="displayTypes" header={panelHeader()} content={panelContent()} />
  );
};
export default DisplayTypes;

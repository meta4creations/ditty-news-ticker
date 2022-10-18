import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGear } from "@fortawesome/pro-regular-svg-icons";
import Panel from "../Panel";
import List from "../../common/List";
import Item from "../Item";

const DisplayList = ({ editItem, editor }) => {
  const { displays, helpers, actions } = useContext(editor);

  /**
   * Set up the elements
   */
  const elements = window.dittyHooks.applyFilters(
    "dittyEditorDisplayListElements",
    [
      {
        id: "icon",
        content: (display) => {
          return helpers.displayTypeIcon(display);
        },
      },
      {
        id: "label",
        content: "test",
        content: (display) => display.label,
      },
      {
        id: "settings",
        content: <FontAwesomeIcon icon={faGear} />,
      },
    ],
    editor
  );

  const handleElementClick = (e, elementId, item) => {
    console.log("elementId", elementId);
  };

  const renderItems = () => {
    return displays.map((display) => {
      return (
        <Item
          key={display.id}
          data={display}
          elements={elements}
          onElementClick={handleElementClick}
        />
      );
    });
  };

  const panelContent = () => {
    return <List items={renderItems()} />;
  };

  return <Panel id="displays" content={panelContent()} />;
};
export default DisplayList;

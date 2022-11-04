import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPenRuler } from "@fortawesome/pro-light-svg-icons";
import { faGear } from "@fortawesome/pro-regular-svg-icons";
import Panel from "../Panel";
import List from "../../common/List";
import Item from "../../common/Item";

const LayoutList = ({ item, editor }) => {
  const { layouts } = useContext(editor);

  /**
   * Set up the elements
   */
  const elements = window.dittyHooks.applyFilters(
    "dittyEditorLayoutListElements",
    [
      {
        id: "icon",
        content: <FontAwesomeIcon icon={faPenRuler} />,
      },
      {
        id: "label",
        content: "test",
        content: (layout) => layout.title,
      },
      {
        id: "settings",
        content: <FontAwesomeIcon icon={faGear} />,
      },
    ],
    editor
  );

  const handleElementClick = (e, elementId, item) => {};

  const renderItems = () => {
    return layouts.map((layout) => {
      return (
        <Item
          key={layout.id}
          data={layout}
          elements={elements}
          onElementClick={handleElementClick}
        />
      );
    });
  };

  const panelContent = () => {
    return <List items={renderItems()} />;
  };

  return <Panel id="layouts" content={panelContent()} />;
};
export default LayoutList;

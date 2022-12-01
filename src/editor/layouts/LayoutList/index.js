import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPenRuler, faGear } from "@fortawesome/pro-light-svg-icons";
import { List, ListItem, Panel } from "../../../components";

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
        <ListItem
          key={layout.id}
          data={layout}
          elements={elements}
          onElementClick={handleElementClick}
        />
      );
    });
  };

  const panelContent = () => {
    return <List>{renderItems()}</List>;
  };

  return <Panel id="layouts" content={panelContent()} />;
};
export default LayoutList;

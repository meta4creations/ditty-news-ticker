import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import List from "./common/List";
import Item from "./item";
import { EditorContext } from "./context";

const PanelDisplays = () => {
  const { displays } = useContext(EditorContext);
  console.log("displays", displays);

  const handleRenderIcon = (display) => {
    return window.dittyHooks.applyFilters(
      "dittyEditorDisplayIcon",
      <i className="fas fa-tablet-alt"></i>,
      display
    );
  };

  const handleRenderLabel = (display) => {
    return display.label;
  };

  const handleItemClick = (e, item) => {
    console.log("target", e.target);
  };

  const handleElementClick = (e, elementId, item) => {
    console.log("elementId", elementId);
  };

  const renderItems = () => {
    return displays.map((display, index) => {
      return (
        <Item
          key={display.id}
          index={index}
          data={display}
          renderIcon={handleRenderIcon}
          renderLabel={handleRenderLabel}
          onClick={handleItemClick}
          onElementClick={handleElementClick}
        />
      );
    });
  };

  return <List items={renderItems()} />;
};
export default PanelDisplays;

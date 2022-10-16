import { __ } from "@wordpress/i18n";
import Panel from "../Panel";
import List from "../../common/List";
import Item from "../Item";

const DisplayList = ({ id, displays, actions, editItem }) => {
  /**
   * Render the icon
   */
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

  const panelContent = () => {
    return <List items={renderItems()} />;
  };

  return <Panel id="displays" content={panelContent()} />;
};
export default DisplayList;

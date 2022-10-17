import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPenRuler } from "@fortawesome/pro-light-svg-icons";
import Panel from "../Panel";
import List from "../../common/List";
import Item from "../Item";

const LayoutList = ({ item, editor }) => {
  const { layouts } = useContext(editor);

  /**
   * Render the icon
   */
  const handleRenderIcon = (layout) => {
    return window.dittyHooks.applyFilters(
      "dittyEditorLayoutIcon",
      <FontAwesomeIcon icon={faPenRuler} />,
      layout
    );
  };

  const handleRenderLabel = (layout) => {
    return layout.label;
  };

  const handleItemClick = (e, item) => {
    console.log("target", e.target);
  };

  const handleElementClick = (e, elementId, item) => {
    console.log("elementId", elementId);
  };

  const renderItems = () => {
    return layouts.map((layout, index) => {
      return (
        <Item
          key={layout.id}
          index={index}
          data={layout}
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

  return <Panel id="layouts" content={panelContent()} />;
};
export default LayoutList;

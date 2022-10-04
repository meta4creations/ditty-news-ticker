import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { EditorContext } from "./context";
import Item from "./item";

const PanelItems = () => {
  const { items } = useContext(EditorContext);

  function handleRenderIcon(item) {
    return window.dittyHooks.applyFilters(
      "dittyEditorItemIcon",
      <i className="fas fa-pencil-alt"></i>,
      item
    );
  }

  function handleRenderLabel(item) {
    return window.dittyHooks.applyFilters(
      "dittyEditorItemLabel",
      item.item_type,
      item
    );
  }

  return (
    <>
      <div className="ditty-editor__panel__header">
        <button className="ditty-button">
          {__("Add Item", "ditty-news-ticker")}
        </button>
      </div>
      <div className="ditty-editor__panel__content">
        {items.map((item) => {
          return (
            <Item
              key={item.item_id}
              data={item}
              renderIcon={handleRenderIcon}
              renderLabel={handleRenderLabel}
            />
          );
        })}
      </div>
    </>
  );
};
export default PanelItems;

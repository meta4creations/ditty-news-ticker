import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { EditorContext } from "./context";
import Item from "./item";

const PanelDisplays = () => {
  const { displays } = useContext(EditorContext);
  console.log("displays", displays);

  function handleRenderIcon(display) {
    return window.dittyHooks.applyFilters(
      "dittyEditorDisplayIcon",
      <i className="fas fa-tablet-alt"></i>,
      display
    );
  }

  function handleRenderLabel(display) {
    return display.label;
  }

  return (
    <>
      <div className="ditty-editor__panel__header">
        <button className="ditty-button">
          {__("Add Display", "ditty-news-ticker")}
        </button>
      </div>
      <div className="ditty-editor__panel__content">
        {displays.map((display) => {
          return (
            <Item
              key={display.id}
              data={display}
              renderIcon={handleRenderIcon}
              renderLabel={handleRenderLabel}
            />
          );
        })}
      </div>
    </>
  );
};
export default PanelDisplays;

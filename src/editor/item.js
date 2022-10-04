import { __ } from "@wordpress/i18n";

const Item = ({ data, renderIcon, renderLabel }) => {
  let elements = [
    {
      id: "icon",
      content: renderIcon(data),
    },
    {
      id: "label",
      content: renderLabel(data),
    },
    {
      id: "settings",
      content: <i className="fas fa-cog"></i>,
    },
  ];

  elements = window.dittyHooks.applyFilters(
    "dittyEditorItemElements",
    elements
  );

  return (
    <div className="ditty-editor-item">
      {elements.map((element) => {
        return (
          <span className={`ditty-editor-item__${element.id}`} key={element.id}>
            {element.content}
          </span>
        );
      })}
    </div>
  );
};
export default Item;

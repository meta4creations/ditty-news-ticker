import { __ } from "@wordpress/i18n";

const Item = ({ data }) => {
  let elements = [
    {
      id: "icon",
      content: <i className="fas fa-pencil-alt"></i>,
    },
    {
      id: "label",
      content: data.item_type,
    },
    {
      id: "rearrange",
      content: <i className="fas fa-bars"></i>,
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

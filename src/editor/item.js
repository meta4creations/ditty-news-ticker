import { __ } from "@wordpress/i18n";

const Item = ({ data }) => {
  let elements = [
    {
      id: "icon",
      content: window.dittyHooks.applyFilters(
        "dittyEditorItemIcon",
        <i className="fas fa-pencil-alt"></i>,
        data
      ),
    },
    {
      id: "label",
      content: window.dittyHooks.applyFilters(
        "dittyEditorItemLabel",
        data.item_type,
        data
      ),
    },
    {
      id: "edit",
      content: <i className="fas fa-edit"></i>,
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

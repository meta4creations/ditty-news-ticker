import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGear } from "@fortawesome/pro-regular-svg-icons";

const Item = ({ data, renderIcon, renderLabel, editable, onElementClick }) => {
  let elements = [
    {
      id: "icon",
      content: renderIcon(data),
    },
    {
      id: "label",
      content: renderLabel(data),
    },
  ];
  if (editable) {
    elements.push({
      id: "settings",
      content: <FontAwesomeIcon icon={faGear} />,
    });
  }

  elements = window.dittyHooks.applyFilters(
    "dittyEditorItemElements",
    elements
  );

  return (
    <div className="ditty-editor-item">
      {elements.map((element) => {
        return (
          <span
            className={`ditty-editor-item__${element.id}`}
            key={element.id}
            onClick={(e) => {
              onElementClick(e, element.id, data);
            }}
          >
            {element.content}
          </span>
        );
      })}
    </div>
  );
};
export default Item;

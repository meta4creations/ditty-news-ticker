import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGear } from "@fortawesome/pro-regular-svg-icons";

const Item = ({ data, elements, onElementClick }) => {
  const renderElement = (element) => {
    if (element.content) {
      return (
        <span
          className={`ditty-editor-item__${element.id}`}
          key={element.id}
          onClick={(e) => {
            onElementClick(e, element.id, data);
          }}
        >
          {"function" === typeof element.content
            ? element.content(data)
            : element.content}
        </span>
      );
    }
  };

  return (
    <div className="ditty-editor-item">
      {elements.map((element) => {
        return renderElement(element);
      })}
    </div>
  );
};
export default Item;

import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGear } from "@fortawesome/pro-regular-svg-icons";

const Item = ({
  data,
  elements,
  isActive,
  classes,
  onItemClick,
  onElementClick,
}) => {
  const renderElement = (element) => {
    if (element.content) {
      return (
        <span
          className={`ditty-editor-item__${element.id}`}
          key={element.id}
          onClick={(e) => {
            onElementClick && onElementClick(e, element.id, data);
          }}
        >
          {"function" === typeof element.content
            ? element.content(data)
            : element.content}
        </span>
      );
    }
  };

  const getItemClassName = () => {
    let className = "ditty-editor-item";
    if (isActive) {
      className += " active";
    }
    if (classes) {
      className += ` ${classes}`;
    }
    return className;
  };

  return (
    <div
      className={getItemClassName()}
      onClick={(e) => {
        onItemClick && onItemClick(e, data);
      }}
    >
      {elements.map((element) => {
        return renderElement(element);
      })}
    </div>
  );
};
export default Item;

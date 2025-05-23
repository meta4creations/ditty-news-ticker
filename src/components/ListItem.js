const { __ } = wp.i18n;
import classnames from "classnames";

const Item = ({ data, elements, isActive, classes, onItemClick }) => {
  const renderElement = (element) => {
    if (element.content) {
      const classes = classnames(
        `ditty-editor-item__${element.id}`,
        element.className
      );
      return (
        <span
          className={classes}
          key={element.id}
          onClick={() => {
            element.onClick && element.onClick(data);
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
      <div className="ditty-editor-item__actions">
        {elements.map((element) => {
          return renderElement(element);
        })}
      </div>
    </div>
  );
};
export default Item;

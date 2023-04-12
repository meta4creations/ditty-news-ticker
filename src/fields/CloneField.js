import { __ } from "@wordpress/i18n";
// import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
// import {
//   faMinus,
//   faPlus,
//   faClone,
//   faAngleUp,
//   faAngleDown,
// } from "@fortawesome/pro-regular-svg-icons";
import classnames from "classnames";
import { Button, ButtonGroup } from "../components";

const CloneField = ({
  className,
  data,
  onMoveUp,
  onMoveDown,
  onClone,
  onDelete,
  children,
}) => {
  const fieldClasses = classnames("ditty-clone__field", className);

  return (
    <div className={fieldClasses}>
      {(onMoveUp || onMoveDown) && (
        <ButtonGroup className="ditty-clone__field__buttons ditty-clone__field__buttons--start">
          {onMoveUp && (
            <Button onClick={() => onMoveUp(data)}>
              <i className="fa-solid fa-angle-up"></i>
            </Button>
          )}
          {onMoveDown && (
            <Button onClick={() => onMoveDown(data)}>
              <i className="fa-solid fa-angle-down"></i>
            </Button>
          )}
        </ButtonGroup>
      )}
      {children}
      <ButtonGroup className="ditty-clone__field__buttons ditty-clone__field__buttons--end">
        <Button onClick={onDelete}>
          <i className="fa-solid fa-minus"></i>
        </Button>
        <Button onClick={() => onClone()}>
          <i className="fa-solid fa-plus"></i>
        </Button>
        <Button
          onClick={() => {
            onClone(data._value);
          }}
        >
          <i className="fa-solid fa-clone"></i>
        </Button>
      </ButtonGroup>
    </div>
  );
};

export default CloneField;

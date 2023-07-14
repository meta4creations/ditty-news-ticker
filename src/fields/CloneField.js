import { __ } from "@wordpress/i18n";
import classnames from "classnames";
import { Button, ButtonGroup, Icon } from "../components";

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
              <Icon id="faAngleUp" />
            </Button>
          )}
          {onMoveDown && (
            <Button onClick={() => onMoveDown(data)}>
              <Icon id="faAngleDown" />
            </Button>
          )}
        </ButtonGroup>
      )}
      {children}
      <ButtonGroup className="ditty-clone__field__buttons ditty-clone__field__buttons--end">
        <Button onClick={onDelete}>
          <Icon id="faMinus" />
        </Button>
        <Button onClick={() => onClone()}>
          <Icon id="faPlus" />
        </Button>
        <Button
          onClick={() => {
            onClone(data._value);
          }}
        >
          <Icon icon="faClone" />
        </Button>
      </ButtonGroup>
    </div>
  );
};

export default CloneField;

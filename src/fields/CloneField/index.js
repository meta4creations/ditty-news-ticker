import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faMinus, faPlus, faClone } from "@fortawesome/pro-light-svg-icons";
import classnames from "classnames";
import { Button, ButtonGroup } from "../../components";

const CloneField = ({ className, value, onClone, onDelete, children }) => {
  const fieldClasses = classnames("ditty-clone__field", className);

  return (
    <div className={fieldClasses}>
      <div className="ditty-clone__field__actions">
        <ButtonGroup gap="5px" justify="flex-end">
          <Button onClick={onDelete}>
            <FontAwesomeIcon icon={faMinus} />
          </Button>
          <Button onClick={() => onClone()}>
            <FontAwesomeIcon icon={faPlus} />
          </Button>
          <Button
            onClick={() => {
              onClone(value);
            }}
          >
            <FontAwesomeIcon icon={faClone} />
          </Button>
        </ButtonGroup>
      </div>
      {children}
    </div>
  );
};

export default CloneField;

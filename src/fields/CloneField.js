import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faMinus,
  faPlus,
  faClone,
  faSort,
} from "@fortawesome/pro-light-svg-icons";
import classnames from "classnames";
import { Button, ButtonGroup } from "../components";

const CloneField = ({ className, value, onClone, onDelete, children }) => {
  const fieldClasses = classnames("ditty-clone__field", className);

  return (
    <div className={fieldClasses}>
      <ButtonGroup className="ditty-clone__field__buttons ditty-clone__field__buttons--start">
        <Button>
          <FontAwesomeIcon icon={faSort} />
        </Button>
      </ButtonGroup>
      {children}
      <ButtonGroup className="ditty-clone__field__buttons ditty-clone__field__buttons--end">
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
  );
};

export default CloneField;

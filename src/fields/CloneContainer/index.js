import { __ } from "@wordpress/i18n";
import classnames from "classnames";
import { Button, ButtonGroup } from "../../components";

const CloneContainer = ({ cloneButton, className, onClone, children }) => {
  const fieldClasses = classnames("ditty-clone-container", className);

  return (
    <div className={fieldClasses}>
      {children}
      <ButtonGroup>
        <Button onClick={onClone} className="ditty-field--clone__button">
          {cloneButton ? cloneButton : __("Add More", "ditty-news-ticker")}
        </Button>
      </ButtonGroup>
    </div>
  );
};

export default CloneContainer;

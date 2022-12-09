import { __ } from "@wordpress/i18n";
import classnames from "classnames";
import { Button, ButtonGroup } from "../../components";

const Clone = ({ cloneButton, className, onClone, children }) => {
  const fieldClasses = classnames("ditty-clone", className);

  return (
    <div className={fieldClasses}>
      <div className="ditty-clone__fields">{children}</div>
      <ButtonGroup className="ditty-clone__footer">
        <Button onClick={onClone} className="ditty-clone__button">
          {cloneButton ? cloneButton : __("Add More", "ditty-news-ticker")}
        </Button>
      </ButtonGroup>
    </div>
  );
};

export default Clone;

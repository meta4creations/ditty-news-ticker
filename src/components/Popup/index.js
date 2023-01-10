import { __ } from "@wordpress/i18n";
import classnames from "classnames";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLoader } from "@fortawesome/pro-light-svg-icons";
import Button from "../Button";
import ButtonGroup from "../ButtonGroup";
import Link from "../Link";

const Popup = ({
  id,
  header,
  submitLabel,
  submitDisabled,
  children,
  onClose,
  onSubmit,
  showSpinner,
  className,
  level,
}) => {
  const classes = classnames("ditty-popup", className, `ditty-popup--${id}`, {
    "ditty-popup--level-2": level === "2",
    "ditty-popup--level-3": level === "3",
  });

  return (
    <>
      <div className={classes}>
        <div className="ditty-popup__overlay" onClick={onClose}></div>
        <div className="ditty-popup__contents">
          {header && <div className="ditty-popup__header">{header}</div>}
          <div className="ditty-popup__body">
            <div className="ditty-popup__scroll">{children}</div>
          </div>
          <div className="ditty-popup__footer">
            <ButtonGroup justify="flex-end" gap="20px">
              <Link onClick={onClose}>{__("Cancel", "ditty-news-ticker")}</Link>
              <Button
                type="primary"
                onClick={onSubmit}
                disabled={submitDisabled}
              >
                {showSpinner && (
                  <FontAwesomeIcon icon={faLoader} className="fa-spin" />
                )}
                <span>
                  {submitLabel
                    ? submitLabel
                    : __("Submit", "ditty-news-ticker")}
                </span>
              </Button>
            </ButtonGroup>
          </div>
        </div>
      </div>
    </>
  );
};
export default Popup;

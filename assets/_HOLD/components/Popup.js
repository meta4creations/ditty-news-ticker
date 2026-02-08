const { __ } = wp.i18n;
import classnames from "classnames";
import Button from "./Button";
import ButtonGroup from "./ButtonGroup";
import Icon from "./Icon";
import Link from "./Link";

const Popup = ({
  id,
  header,
  footer,
  footerBefore,
  submitLabel,
  submitDisabled,
  children,
  onClose,
  onSubmit,
  className,
  hideCancel,
  level,
  showSpinner,
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
          <div className="ditty-popup__body">{children}</div>
          <div className="ditty-popup__footer">
            {footerBefore && footerBefore}
            {footer ? (
              footer
            ) : (
              <ButtonGroup justify="flex-end" gap="20px">
                {!hideCancel && (
                  <Link onClick={onClose}>
                    {__("Cancel", "ditty-news-ticker")}
                  </Link>
                )}
                <Button
                  type="primary"
                  onClick={() => {
                    onSubmit();
                  }}
                  disabled={submitDisabled || (showSpinner && "disabled")}
                >
                  {showSpinner && <Icon id="faLoader" spin />}
                  <span>
                    {submitLabel
                      ? submitLabel
                      : __("Submit", "ditty-news-ticker")}
                  </span>
                </Button>
              </ButtonGroup>
            )}
          </div>
        </div>
      </div>
    </>
  );
};
export default Popup;

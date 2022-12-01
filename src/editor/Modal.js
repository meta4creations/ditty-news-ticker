import { __ } from "@wordpress/i18n";
import { __experimentalText as Text } from "@wordpress/components";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faXmark } from "@fortawesome/pro-regular-svg-icons";

const Modal = ({ closeModal, className, label, header, footer, children }) => {
  const renderModalClass = () => {
    let modalClass = className
      ? `ditty-editor__modal ${className}`
      : "ditty-editor__modal";
    return modalClass;
  };

  const maybeCloseModal = (e) => {
    if (e.target.classList.contains("ditty-editor__modal")) {
      closeModal();
    }
  };

  const renderHeader = () => {
    return <div className="ditty-editor__modal__header">{header}</div>;
  };

  const renderFooter = () => {
    return <div className="ditty-editor__modal__footer">{footer}</div>;
  };

  return (
    <div className={renderModalClass()} onClick={maybeCloseModal}>
      <div className="ditty-editor__modal__content">
        <div className="ditty-editor__modal__toolbar">
          {label && (
            <Text truncate numberOfLines="1">
              {label}
            </Text>
          )}
          <FontAwesomeIcon
            className="ditty-editor__modal__close"
            icon={faXmark}
            onClick={closeModal}
          />
        </div>
        {header && renderHeader()}
        <div className="ditty-editor__modal__body">
          <div className="ditty-editor__modal__body__contents">{children}</div>
        </div>
        {footer && renderFooter()}
      </div>
    </div>
  );
};
export default Modal;

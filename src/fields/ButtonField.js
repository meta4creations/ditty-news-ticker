import { __ } from "@wordpress/i18n";
import BaseField from "./BaseField";
import { Button, Icon } from "../components";

const ButtonField = (props) => {
  const { label, kind, showSpinner, onClick } = props;

  return (
    <BaseField {...props} type="button">
      <Button {...props} type={kind} onClick={onClick}>
        {showSpinner && <Icon id="loader" spin />}
        <span>{label}</span>
      </Button>
    </BaseField>
  );
};

export default ButtonField;

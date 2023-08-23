import { __ } from "@wordpress/i18n";
import BaseField from "./BaseField";
import { Button } from "../components";

const ButtonField = (props) => {
  const { label, kind, onClick } = props;

  return (
    <BaseField {...props} type="button">
      <Button {...props} type={kind} onClick={onClick}>
        {label}
      </Button>
    </BaseField>
  );
};

export default ButtonField;

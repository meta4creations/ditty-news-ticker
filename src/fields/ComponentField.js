import { __ } from "@wordpress/i18n";
import BaseField from "./BaseField";

const ComponentField = (props) => {
  const { std } = props;
  return <BaseField {...props}>{std}</BaseField>;
};

export default ComponentField;

import { __ } from "@wordpress/i18n";
import BaseField from "./BaseField";

const HtmlField = (props) => {
  const { value } = props;
  return <BaseField {...props}>{value}</BaseField>;
};

export default HtmlField;

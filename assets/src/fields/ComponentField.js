const { __ } = wp.i18n;
import BaseField from "./BaseField";

const ComponentField = (props) => {
  const { std } = props;
  return (
    <BaseField {...props} type="component">
      {std}
    </BaseField>
  );
};

export default ComponentField;

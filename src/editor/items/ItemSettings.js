import { __ } from "@wordpress/i18n";
import { FieldList } from "../../fields";
import { getItemTypeFields } from "../utils/itemTypes";

const ItemSettings = ({ item, onUpdateSettings }) => {
  const renderFields = () => {
    const fields = getItemTypeFields(item.item_type);
    return (
      <FieldList
        fields={fields}
        values={item.item_value}
        onUpdate={(id, value) => {
          onUpdateSettings(item, id, value);
        }}
      />
    );
  };

  return <div className="dittyEditorFields">{renderFields()}</div>;
};
export default ItemSettings;

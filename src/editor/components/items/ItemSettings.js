import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import Field from "../../common/Field";

const ItemSettings = ({ item, editor }) => {
  const { helpers, actions } = useContext(editor);

  const handleFieldUpdate = (field, value) => {
    const updatedItem = item;
    updatedItem.item_value[field.id] = value;
    actions.updateItem(updatedItem);
  };

  const renderFields = () => {
    const fields = helpers.itemTypeFields(item.item_type);
    return (
      fields &&
      fields.map((field) => {
        const value = item.item_value[field.id]
          ? item.item_value[field.id]
          : field.std;

        return (
          <Field
            key={field.id}
            field={field}
            value={value}
            onFieldUpdate={handleFieldUpdate}
          />
        );
      })
    );
  };

  return <div className="dittyEditorFields">{renderFields()}</div>;
};
export default ItemSettings;

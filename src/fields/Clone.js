import { __ } from "@wordpress/i18n";
import classnames from "classnames";
import { Button, ButtonGroup, SortableList } from "../components";
import FieldHeader from "./FieldHeader";

const Clone = (props) => {
  const { fields, cloneButton, className, onClone, onSort, children } = props;
  const fieldClasses = classnames("ditty-clone", className);

  const getCloneValues = (field, value = fieldVal) => {
    let cloneValues = Array.isArray(value) ? value : [value];
    if (cloneValues.length < 1) {
      cloneValues.push("");
    }

    const cloneValueObjects = cloneValues.map((cloneValue, cloneIndex) => {
      const cloneValueObject =
        typeof cloneValue === "object" && cloneValue._id
          ? cloneValue
          : { _id: Date.now() + cloneIndex, _value: cloneValue };
      return cloneValueObject;
    });
    return cloneValueObjects;
  };

  const getCloneFields = () => {
    const cloneValues = getCloneValues(inputField, inputValue);
    return cloneValues.map((cloneValue, cloneIndex) => {
      const cloneField = { ...inputField };
      delete cloneField.clone;
      delete cloneField.clone_button;
      cloneField.hideHeader = true;
      cloneField.cloneIndex = `${cloneIndex}`;
      cloneField.cloneId = cloneValue._id;

      return {
        id: cloneValue._id,
        data: cloneValue,
        content: (
          <CloneField
            key={`${inputField.id}${cloneIndex}`}
            value={cloneValue._value}
            onDelete={() => {
              cloneValues.splice(cloneIndex, 1);
              handleUpdateCloneValue(inputField, cloneValues);
            }}
            onClone={(value = "") => {
              addCloneValue(inputField, cloneValues, value, cloneIndex + 1);
            }}
          >
            {renderInput(cloneField, cloneValue._value)}
          </CloneField>
        ),
      };
    });
  };

  /**
   * Pull data from sorted list items to update items
   * @param {array} sortedListItems
   */
  const handleSortEnd = (sortedListItems) => {
    const updatedItems = sortedListItems.map((item) => {
      return item.data;
    });
    onSort(updatedItems);
  };

  return (
    <div className={fieldClasses}>
      <FieldHeader {...props} />
      {/* {fields.map((value, index) => {
        return value.content;
      })} */}

      <SortableList
        className="ditty-clone__fields"
        items={fields}
        onSortEnd={handleSortEnd}
      />
      <ButtonGroup className="ditty-clone__footer">
        <Button onClick={onClone} className="ditty-clone__button">
          {cloneButton ? cloneButton : __("Add More", "ditty-news-ticker")}
        </Button>
      </ButtonGroup>
    </div>
  );
};

export default Clone;

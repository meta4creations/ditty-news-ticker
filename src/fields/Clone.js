import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import classnames from "classnames";
import { Button, ButtonGroup, SortableList } from "../components";
import FieldHeader from "./FieldHeader";
import CloneField from "./CloneField";

const Clone = (props) => {
  const { field, value, renderInput, onChange, cloneButton, className } = props;
  const fieldClasses = classnames("ditty-clone", className);

  const getCloneValues = () => {
    let values = Array.isArray(value) ? value : [value];
    if (values.length < 1) {
      values.push("");
    }
    const valueObjects = values.map((cloneValue, cloneIndex) => {
      const valueObject =
        typeof cloneValue === "object" && cloneValue._id
          ? cloneValue
          : { _id: Date.now() + cloneIndex, _value: cloneValue };
      return valueObject;
    });
    return valueObjects;
  };
  const [cloneValues, setCloneValues] = useState(getCloneValues());

  const handleUpdateCloneValue = (updatedValues) => {
    const cleanedValues = updatedValues.map((cloneValue) => {
      return cloneValue._id ? cloneValue._value : cloneValue;
    });
    setCloneValues(updatedValues);
    onChange(cleanedValues);
  };

  const handleUpdateValue = (inputField, updatedValue) => {
    const updatedValues = [...cloneValues];
    updatedValues[Number(inputField.cloneIndex)]._value = updatedValue;
    handleUpdateCloneValue(updatedValues);
  };

  const handleMoveUp = (data) => {
    const updatedValues = [...cloneValues];
    const fromIndex = updatedValues.findIndex((object) => {
      return object._id === data._id;
    });
    if (fromIndex <= 0) {
      return false;
    }
    let toIndex = fromIndex - 1;
    updatedValues.splice(fromIndex, 1);
    updatedValues.splice(toIndex, 0, data);
    handleUpdateCloneValue(updatedValues);
  };

  const handleMoveDown = (data) => {
    const updatedValues = [...cloneValues];
    const fromIndex = updatedValues.findIndex((object) => {
      return object._id === data._id;
    });
    if (fromIndex >= updatedValues.length - 1) {
      return false;
    }
    const toIndex = fromIndex + 1;
    updatedValues.splice(fromIndex, 1);
    updatedValues.splice(toIndex, 0, data);
    handleUpdateCloneValue(updatedValues);
  };

  const handleAddClone = (value, index) => {
    const cloneValue = typeof value === "object" ? { ...value } : value;
    const updatedValues = [...cloneValues];
    if (index && index <= updatedValues.length) {
      updatedValues.splice(index, 0, {
        _id: Date.now() + index,
        _value: cloneValue,
      });
    } else {
      updatedValues.push({
        _id: Date.now() + updatedValues.length,
        _value: cloneValue,
      });
    }
    handleUpdateCloneValue(updatedValues);
  };

  const getCloneFields = () => {
    return cloneValues.map((cloneValue, cloneIndex) => {
      const cloneField = { ...field };
      delete cloneField.clone;
      delete cloneField.cloneButton;
      cloneField.hideHeader = true;
      cloneField.cloneIndex = `${cloneIndex}`;
      cloneField.cloneId = cloneValue._id;

      return {
        id: cloneValue._id,
        data: cloneValue,
        content: (
          <CloneField
            key={cloneValue._id}
            data={cloneValue}
            value={cloneValue._value}
            onDelete={() => {
              const updatedValues = [...cloneValues];
              updatedValues.splice(cloneIndex, 1);

              // If all fields are removed, add a blank one back in
              if (!updatedValues.length) {
                const newCloneValue =
                  typeof value === "object" ? { ...value } : value;
                updatedValues.push({
                  _id: Date.now() + updatedValues.length,
                  _value: newCloneValue,
                });
              }
              handleUpdateCloneValue(updatedValues);
            }}
            onClone={(value = "") => {
              handleAddClone(value, cloneIndex + 1);
            }}
            onMoveUp={cloneIndex > 0 ? handleMoveUp : null}
            onMoveDown={
              cloneIndex < cloneValues.length - 1 ? handleMoveDown : null
            }
          >
            {renderInput(cloneField, cloneValue._value, handleUpdateValue)}
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
    handleUpdateCloneValue(updatedItems);
  };

  return (
    <div className={fieldClasses}>
      <FieldHeader {...field} />
      <SortableList
        className="ditty-clone__fields"
        items={getCloneFields()}
        onSortEnd={handleSortEnd}
      />
      <ButtonGroup className="ditty-clone__footer">
        <Button
          onClick={() => handleAddClone("")}
          className="ditty-clone__button"
        >
          {cloneButton ? cloneButton : __("Add More", "ditty-news-ticker")}
        </Button>
      </ButtonGroup>
    </div>
  );
};

export default Clone;

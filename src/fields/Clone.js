import { __ } from "@wordpress/i18n";
import classnames from "classnames";
import { Button, ButtonGroup, SortableList } from "../components";
import FieldHeader from "./FieldHeader";

const Clone = (props) => {
  const { fields, cloneButton, className, onClone, onSort, children } = props;
  const fieldClasses = classnames("ditty-clone", className);

  console.log("fields", fields);

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

import { __ } from "@wordpress/i18n";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPencil, faSliders } from "@fortawesome/pro-light-svg-icons";
import _ from "lodash";

/**
 * Return all itemm types
 * @returns array
 */
export const getItemTypes = () => {
  const itemTypes = dittyEditor.applyFilters("dittyItemTypes", []);
  const migratedItemTypes = migrateItemTypes(itemTypes);
  const sortedItemTypes = _.orderBy(migratedItemTypes, ["label"], ["asc"]);
  return sortedItemTypes;
};

/**
 * Integrate migrated php item types
 * @param {object} item
 * @returns element
 */
const migrateItemTypes = (itemTypes) => {
  const phpItemTypes =
    dittyEditorVars.itemTypes &&
    dittyEditorVars.itemTypes.reduce((filtered, phpType) => {
      const existingType = itemTypes.filter((type) => type.id === phpType.type);
      if (!existingType.length) {
        filtered.push({
          id: phpType.type,
          icon: <i className={phpType.icon}></i>,
          label: phpType.label,
          description: phpType.description,
          variationTypes: phpType.variationTypes,
          phpSettings: phpType.settings,
        });
      }
      return filtered;
    }, []);
  if (phpItemTypes && phpItemTypes.length) {
    const updatedItemTypes = itemTypes.concat(phpItemTypes);
    return updatedItemTypes;
  } else {
    return itemTypes;
  }
};

/**
 * Return an Item Type object
 * @param {object} item
 * @returns element
 */
export const getItemTypeObject = (item) => {
  const itemTypes = getItemTypes();
  const itemTypeObject = itemTypes.filter((itemType) => {
    if (typeof item === "object") {
      return itemType.id === item.item_type;
    } else {
      return itemType.id === item;
    }
  });
  return itemTypeObject.length ? itemTypeObject[0] : false;
};

/**
 * Return an item types icon from item
 * @param {object} item
 * @returns element
 */
export const getItemTypeIcon = (item) => {
  const itemTypes = getItemTypes();
  const itemType = itemTypes.filter(
    (itemType) => itemType.id === item.item_type
  );
  return itemType.length ? (
    itemType[0].icon
  ) : (
    <FontAwesomeIcon icon={faPencil} />
  );
};

/**
 * Return the fields for an item type
 * @param {string} item
 * @returns object
 */
export const getItemTypeSettings = (item) => {
  const itemTypeObject = getItemTypeObject(item);
  const fieldGroups = [];
  if (itemTypeObject.phpSettings) {
    fieldGroups.push({
      id: "settings",
      label: __("Settings", "ditty-news-ticker"),
      name: __("Settings", "ditty-news-ticker"),
      desc: __(
        `Configure the settings of the ${itemTypeObject.label}.`,
        "ditty-news-ticker"
      ),
      icon: <FontAwesomeIcon icon={faSliders} />,
      fields: itemTypeObject.phpSettings,
    });
  } else {
    for (const key in itemTypeObject.settings) {
      if (
        typeof itemTypeObject.settings[key] === "object" &&
        !Array.isArray(itemTypeObject.settings[key])
      ) {
        fieldGroups.push(itemTypeObject.settings[key]);
      }
    }
  }
  return fieldGroups;
};

/**
 * Return an item types icon from item
 * @param {object} item
 * @returns element
 */
export const getItemLabel = (item) => {
  const itemTypeObject = getItemTypeObject(item);
  return itemTypeObject.itemLabel
    ? itemTypeObject.itemLabel(item)
    : item.item_type;
};

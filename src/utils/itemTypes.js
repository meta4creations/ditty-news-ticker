import { __ } from "@wordpress/i18n";
// import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
// import { faPencil, faSliders } from "@fortawesome/pro-light-svg-icons";
import _ from "lodash";

/**
 * Return all itemm types
 * @returns array
 */
export const getItemTypes = () => {
  const itemTypes = dittyEditor ? dittyEditor.itemTypes : [];
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
  const updatedItemTypes = [...itemTypes];
  if (dittyEditorVars.itemTypes) {
    dittyEditorVars.itemTypes.map((phpType) => {
      const existingIndex = updatedItemTypes.findIndex((itemType) => {
        return itemType.id === phpType.id;
      });
      if (-1 === existingIndex) {
        updatedItemTypes.push(phpType);
      } else {
        updatedItemTypes[existingIndex] = {
          ...phpType,
          ...itemTypes[existingIndex],
        };
      }
    });
  }
  return updatedItemTypes;
};

/**
 * Return an Item Type object
 * @param {object} item
 * @returns element
 */
export const getItemTypeObject = (item) => {
  if (typeof item === "object" && item.id) {
    return item;
  }
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
    <i className="fa-solid fa-pencil"></i>
  );
};

/**
 * Return an item types icon from item
 * @param {object} item
 * @returns element
 */
export const getItemTypePreviewIcon = (item) => {
  const itemTypeObject = getItemTypeObject(item);
  let previewIcon;
  if (itemTypeObject.previewIcon) {
    previewIcon = itemTypeObject.previewIcon(item);
  } else {
    const icon = getItemTypeIcon(item);
    previewIcon = "string" === typeof icon ? <i className={icon}></i> : icon;
  }
  const style = {
    color: itemTypeObject.iconColor ? itemTypeObject.iconColor : false,
    background: itemTypeObject.iconBGColor ? itemTypeObject.iconBGColor : false,
  };
  return (
    <div className="ditty-preview-icon" style={style}>
      {previewIcon}
    </div>
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
      icon: <i className="fa-solid fa-sliders"></i>,
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
  return itemTypeObject.previewText
    ? itemTypeObject.previewText(item)
    : item.editor_preview
    ? item.editor_preview
    : item.item_type;
};

export const getLayoutVariationObject = (itemType, variation) => {
  const itemTypeObject =
    typeof itemType === "object" ? itemType : getItemTypeObject(itemType);
  const layoutVariations = itemTypeObject.layoutVariations
    ? itemTypeObject.layoutVariations
    : {};

  return layoutVariations[variation] ? layoutVariations[variation] : variation;
};

/**
 * Return an Item Type object
 * @param {object} item
 * @returns element
 */
export const getDefaultLayout = (item, variation = "default") => {
  const itemTypeObject = getItemTypeObject(item);
  return itemTypeObject.defaultLayout;

  // const variationDefaults = dittyEditorVars.variationDefaults
  //   ? dittyEditorVars.variationDefaults
  //   : {};
  // if (
  //   variationDefaults[itemTypeObject.id] &&
  //   variationDefaults[itemTypeObject.id][variation]
  // ) {
  //   console.log("template", variationDefaults[itemTypeObject.id][variation]);
  // }
};

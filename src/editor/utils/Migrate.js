/**
 * Migrate php item types
 * @param {array} itemTypes
 * @returns array
 */
export function migrateItemTypes(itemTypes) {
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
}

/**
 * Migrate php display types
 * @param {array} displayTypes
 * @returns array
 */
export function migrateDisplayTypes(displayTypes) {
  const phpDisplayTypes =
    dittyEditorVars.displayTypes &&
    dittyEditorVars.displayTypes.reduce((filtered, phpType) => {
      const existingType = displayTypes.filter(
        (type) => type.id === phpType.type
      );
      if (!existingType.length) {
        filtered.push({
          id: phpType.type,
          icon: <i className={phpType.icon}></i>,
          label: phpType.label,
          description: phpType.description,
        });
      }
      return filtered;
    }, []);
  if (phpDisplayTypes && phpDisplayTypes.length) {
    const updatedDisplayTypes = displayTypes.concat(phpDisplayTypes);
    return updatedDisplayTypes;
  } else {
    return displayTypes;
  }
}

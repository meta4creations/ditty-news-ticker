/**
 * Migrate php item types
 * @param {array} itemTypes
 * @returns array
 */
export function migrateItemTypes(itemTypes) {
  const phpItemTypes = dittyEditorVars.itemTypes.reduce((filtered, phpType) => {
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
  if (phpItemTypes.length) {
    const updatedItemTypes = itemTypes.concat(phpItemTypes);
    return updatedItemTypes;
  } else {
    return itemTypes;
  }
}

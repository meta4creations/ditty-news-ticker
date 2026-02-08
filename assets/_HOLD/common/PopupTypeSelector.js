const { __ } = wp.i18n;
const { useState } = wp.element;
import { Button, Icon, IconBlock, Popup, Tabs } from "../components";

const PopupTypeSelector = ({
  currentType,
  types,
  apiTypes,
  getTypeObject,
  submitLabel = __("Update Type", "ditty-news-ticker"),
  onChange,
  onClose,
  onUpdate,
  forceUpdate,
  className,
  level,
  defaultTitle = __("Type", "ditty-news-ticker"),
  defaultDescription = __(
    "Choose the type you want to use.",
    "ditty-news-ticker"
  ),
  defaultIcon,
}) => {
  const [selectedType, setSelectedType] = useState(currentType);
  const [showSpinner, setShowSpinner] = useState(false);
  const allTypes = apiTypes ? types.concat(apiTypes) : types;

  const getItemTypeObject = () => {
    let typeObject = selectedType ? getTypeObject(selectedType) : false;
    if (!typeObject) {
      const apiObjects = apiTypes.filter((type) => type.id === selectedType);
      if (apiObjects.length) {
        typeObject = apiObjects[0];
      }
    }
    return typeObject;
  };
  const itemTypeObject = getItemTypeObject();

  const iconStyle = itemTypeObject
    ? {
        color: itemTypeObject.iconColor ? itemTypeObject.iconColor : false,
        background: itemTypeObject.iconBGColor
          ? itemTypeObject.iconBGColor
          : false,
      }
    : false;

  return (
    <Popup
      id="typeSelect"
      submitLabel={
        itemTypeObject.isPreview
          ? __(
              `Buy ${itemTypeObject.extension.label} - ${itemTypeObject.extension.price}`,
              "ditty-news-ticker"
            )
          : typeof submitLabel === "function"
          ? submitLabel(itemTypeObject)
          : submitLabel
      }
      submitDisabled={!selectedType}
      hideCancel={forceUpdate ? true : false}
      header={
        <>
          <IconBlock
            icon={itemTypeObject ? itemTypeObject.icon : defaultIcon}
            iconStyle={iconStyle}
            className="ditty-icon-block--heading"
          >
            <div className="ditty-icon-block--heading__title">
              <h2>{itemTypeObject ? itemTypeObject.label : defaultTitle}</h2>
            </div>
            <p>
              {itemTypeObject ? itemTypeObject.description : defaultDescription}
            </p>
          </IconBlock>
          {itemTypeObject.isPreview && (
            <p className="ditty-preview-extension">
              <span>
                {__(
                  `Requires ${itemTypeObject.extension.label}`,
                  "ditty-news-ticker"
                )}
              </span>
              <Button
                type="primary"
                size="small"
                onClick={() =>
                  window.open(itemTypeObject.extension.url, "_blank")
                }
              >
                {__(
                  `Buy - ${itemTypeObject.extension.price}`,
                  "ditty-news-ticker"
                )}
              </Button>
            </p>
          )}
        </>
      }
      onClose={() => {
        if (forceUpdate) {
          return false;
        }
        onClose(selectedType);
      }}
      onSubmit={() => {
        if (itemTypeObject.isPreview) {
          window.open(itemTypeObject.extension.url, "_blank");
        } else {
          setShowSpinner(true);
          onUpdate(selectedType);
        }
      }}
      level={level}
      className={className}
      showSpinner={showSpinner}
    >
      <Tabs
        tabs={allTypes}
        currentTabId={selectedType}
        type="cloud"
        className="typeSelector"
        tabClick={(data) => {
          if (data.id === selectedType) {
            return false;
          }
          onChange && onChange(data.id);
          setSelectedType(data.id);
        }}
      />
    </Popup>
  );
};
export default PopupTypeSelector;

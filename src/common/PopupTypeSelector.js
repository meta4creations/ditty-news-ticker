import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { IconBlock, Popup, Tabs } from "../components";

const PopupTypeSelector = ({
  currentType,
  types,
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
  const itemTypeObject = selectedType ? getTypeObject(selectedType) : false;

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
        typeof submitLabel === "function"
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
        </>
      }
      onClose={() => {
        if (forceUpdate) {
          return false;
        }
        onClose(selectedType);
      }}
      onSubmit={() => {
        setShowSpinner(true);
        onUpdate(selectedType);
      }}
      level={level}
      className={className}
      showSpinner={showSpinner}
    >
      <Tabs
        tabs={types}
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

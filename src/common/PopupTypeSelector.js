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
  className,
  level,
}) => {
  const [selectedType, setSelectedType] = useState(currentType);
  const itemTypeObject = getTypeObject(selectedType);

  return (
    <Popup
      id="typeSelect"
      submitLabel={
        typeof submitLabel === "function"
          ? submitLabel(itemTypeObject)
          : submitLabel
      }
      header={
        <>
          <IconBlock
            icon={itemTypeObject && itemTypeObject.icon}
            className="ditty-icon-block--heading"
          >
            <div className="ditty-icon-block--heading__title">
              <h2>{itemTypeObject && itemTypeObject.label}</h2>
            </div>
            <p>{itemTypeObject && itemTypeObject.description}</p>
          </IconBlock>
        </>
      }
      onClose={() => {
        onClose(selectedType);
      }}
      onSubmit={() => {
        onUpdate(selectedType);
      }}
      level={level}
      className={className}
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

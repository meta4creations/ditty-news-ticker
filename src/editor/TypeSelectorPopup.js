import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { IconBlock, Popup, Tabs } from "../components";

const TypeSelectorPopup = ({
  activeType,
  types,
  getTypeObject,
  submitLabel = __("Update Type", "ditty-news-ticker"),
  onChange,
  onClose,
  onUpdate,
  className,
  level,
}) => {
  const [selectedType, setSelectedType] = useState(activeType);
  const itemTypeObject = getTypeObject(selectedType);

  return (
    <Popup
      id="typeSelect"
      submitLabel={submitLabel}
      header={
        <>
          <IconBlock icon={itemTypeObject && itemTypeObject.icon}>
            <h2>{itemTypeObject && itemTypeObject.label}</h2>
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
export default TypeSelectorPopup;

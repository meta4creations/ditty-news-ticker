import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { updateDittyDisplayType } from "../../services/dittyService";
import { IconBlock, Popup, Tabs } from "../../components";
import { displayTypes, getDisplayTypeObject } from "../utils/displayTypes";

const DisplayTypeSelectorPopup = ({
  activeType,
  onClose,
  onUpdate,
  dittyEl,
}) => {
  const [currentType, setCurrentType] = useState(activeType);
  const displayTypeObject = getDisplayTypeObject(currentType);

  return (
    <Popup
      id="displayTypeSelect"
      submitLabel={__("Update Type", "ditty-news-ticker")}
      header={
        <IconBlock icon={displayTypeObject.icon}>
          <h2>{displayTypeObject.label}</h2>
          <p>{displayTypeObject.description}</p>
        </IconBlock>
      }
      onClose={() => {
        if (activeType !== currentType && dittyEl) {
          updateDittyDisplayType(dittyEl, activeType);
        }
        onClose();
      }}
      onSubmit={() => {
        onUpdate(currentType);
      }}
    >
      <Tabs
        tabs={displayTypes}
        currentTabId={currentType}
        type="cloud"
        className="displayTypeSelector"
        tabClick={(data) => {
          if (data.id === currentType) {
            return false;
          }

          // Update the Ditty options
          if (dittyEl) {
            updateDittyDisplayType(dittyEl, data.id);
          }

          setCurrentType(data.id);
        }}
      />
    </Popup>
  );
};
export default DisplayTypeSelectorPopup;

import { __ } from "@wordpress/i18n";
import { Tabs } from "../../../components";
import { getDisplayTypes } from "../../utils/displayTypes";

const DisplayTypeSelector = ({ selected, onSelected }) => {
  const displayTypes = getDisplayTypes();

  return (
    <Tabs
      tabs={displayTypes}
      currentTabId={selected}
      tabClick={onSelected}
      type="cloud"
      className="displayTypeSelector"
    />
  );
};
export default DisplayTypeSelector;

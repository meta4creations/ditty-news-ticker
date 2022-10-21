import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faAngleLeft } from "@fortawesome/pro-regular-svg-icons";
import Panel from "../Panel";

const DisplayEdit = ({ display, goBack }) => {
  const [currentTab, setCurrentTab] = useState("edit");

  const panelHeader = () => {
    return (
      <>
        <button onClick={goBack}>
          <FontAwesomeIcon icon={faAngleLeft} />
          {__(`Back`, "ditty-news-ticker")}
        </button>
      </>
    );
  };

  const panelContent = () => {
    return <h1>Display #{display.id}</h1>;
  };

  return (
    <Panel id="displayEdit" header={panelHeader()} content={panelContent()} />
  );
};
export default DisplayEdit;

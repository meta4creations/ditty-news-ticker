import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import { TextControl } from "@wordpress/components";
import Panel from "../Panel";
import List from "../../common/List";
import Item from "../../common/Item";
import { getDisplayObject } from "../../utils/DisplayTypes";
import { setDittyDisplayTemplate } from "../../../services/dittyService";

const DisplayList = ({ editDisplay, goBack, editor }) => {
  const { currentDisplay, displays, displayTypes, helpers, actions } =
    useContext(editor);

  const dittyEl = document.getElementById("ditty-editor__ditty");
  const currentDisplayObject = getDisplayObject(currentDisplay, displays);
  const [previewDisplay, setPreviewDisplay] = useState(currentDisplay);
  const [searchQuery, setSearchQuery] = useState("");
  const [selectedType, setSelectedType] = useState(null);

  /**
   * Set up the elements
   */
  const elements = window.dittyHooks.applyFilters(
    "dittyEditorDisplayListElements",
    [
      {
        id: "icon",
        content: (display) => {
          return helpers.displayTypeIcon(display);
        },
      },
      {
        id: "label",
        content: "test",
        content: (display) => {
          return (
            <>
              <span>{display.title}</span>
              <span>{`ID: ${display.id}`}</span>
            </>
          );
        },
      },
    ],
    editor
  );

  const handleApprove = () => {
    goBack(previewDisplay);
  };

  const handleCancel = () => {
    if (Number(previewDisplay) !== Number(currentDisplay)) {
      const previewDisplayObject = getDisplayObject(previewDisplay, displays);
      setDittyDisplayTemplate(
        dittyEl,
        currentDisplayObject,
        previewDisplayObject
      );
    }

    actions.setCurrentDisplay(previewDisplay);
    goBack();
  };

  const setFilteredType = (type) => {
    if (selectedType === type) {
      setSelectedType(null);
    } else {
      setSelectedType(type);
    }
  };

  /**
   * Perform actions on item click
   * @param {object} e
   * @param {object} display
   */
  const handleItemClick = (e, display) => {
    if (Number(display.id) === Number(currentDisplay)) {
      return false;
    }
    setDittyDisplayTemplate(dittyEl, currentDisplayObject, display);
    actions.setCurrentDisplay(display.id);
  };

  /**
   * Render the panel header
   * @returns html
   */
  const panelHeader = () => {
    return (
      <div className="ditty-editor__panel__header__buttons">
        <button className="ditty-button" onClick={handleApprove}>
          {__("Use Template", "ditty-news-ticker")}
        </button>
        <button onClick={handleCancel}>
          {__("Cancel", "ditty-news-ticker")}
        </button>
      </div>
    );
  };

  /**
   * Render the display items
   * @returns array
   */
  const renderDisplays = () => {
    let filteredDisplays = displays;
    if (selectedType) {
      filteredDisplays = displays.filter(
        (display) => display.type === selectedType
      );
    }
    if (searchQuery) {
      filteredDisplays = filteredDisplays.filter((display) =>
        display.label.toLowerCase().includes(searchQuery.toLowerCase())
      );
    }

    return filteredDisplays.map((display) => {
      const isActive = display.id === Number(currentDisplay) ? true : false;

      return (
        <Item
          key={display.id}
          data={display}
          elements={elements}
          isActive={isActive}
          onItemClick={handleItemClick}
        />
      );
    });
  };

  const panelContent = () => {
    return (
      <>
        <div className="ditty-list__filters">
          <div className="ditty-list__filters__search">
            <TextControl
              label="Search Displays"
              value={searchQuery}
              onChange={(value) => setSearchQuery(value)}
            />
          </div>
          <div className="ditty-list__filters__filters">
            <div className="ditty-button-group">
              {displayTypes.map((displayType) => {
                const className =
                  selectedType === displayType.id ? "active" : "";
                return (
                  <button
                    key={displayType.id}
                    className={className}
                    onClick={() => setFilteredType(displayType.id)}
                  >
                    {displayType.icon}
                  </button>
                );
              })}
            </div>
          </div>
        </div>
        <List items={renderDisplays()} />
      </>
    );
  };

  /**
   * Return the display list panel
   */
  return (
    <Panel id="displays" header={panelHeader()} content={panelContent()} />
  );
};
export default DisplayList;

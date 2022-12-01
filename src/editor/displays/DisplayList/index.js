import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import { TextControl, Button } from "@wordpress/components";
import { updateDittyDisplayTemplate } from "../../../services/dittyService";
import { List, ListItem, Panel } from "../../../components";
import {
  getDisplayTypes,
  getDisplayObject,
  getDisplayTypeIcon,
} from "../../utils/displayTypes";

const DisplayList = ({ goBack, editor }) => {
  const { currentDisplay, displays, actions } = useContext(editor);

  const displayTypes = getDisplayTypes();
  const dittyEl = document.getElementById("ditty-editor__ditty");
  const currentDisplayObject = getDisplayObject(currentDisplay, displays);
  const [previewDisplayObject, setPreviewDisplay] =
    useState(currentDisplayObject);
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
          return getDisplayTypeIcon(display);
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
    actions.setCurrentDisplay(previewDisplayObject.id);
    goBack();
  };

  const handleCancel = () => {
    if (Number(previewDisplayObject.id) !== Number(currentDisplayObject.id)) {
      updateDittyDisplayTemplate(
        dittyEl,
        currentDisplayObject,
        previewDisplayObject
      );
    }
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
    if (Number(display.id) === Number(previewDisplayObject.id)) {
      return false;
    }
    updateDittyDisplayTemplate(dittyEl, display, previewDisplayObject);
    setPreviewDisplay(display);
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
        display.title.toLowerCase().includes(searchQuery.toLowerCase())
      );
    }

    return filteredDisplays.map((display) => {
      const isActive =
        display.id === Number(previewDisplayObject.id) ? true : false;

      return (
        <ListItem
          key={display.id}
          data={display}
          elements={elements}
          isActive={isActive}
          onItemClick={handleItemClick}
        />
      );
    });
  };

  /**
   * Render the panel header
   * @returns html
   */
  const panelHeader = () => {
    return (
      <div className="ditty-editor__panel__header__buttons">
        <Button
          onClick={handleApprove}
          variant="primary"
          disabled={
            Number(currentDisplayObject.id) === Number(previewDisplayObject.id)
          }
        >
          {__("Change Template", "ditty-news-ticker")}
        </Button>
        <Button onClick={handleCancel} variant="link">
          {__("Cancel", "ditty-news-ticker")}
        </Button>
      </div>
    );
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
        <List>{renderDisplays()}</List>
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

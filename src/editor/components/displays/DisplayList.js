import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import {
  CheckboxControl,
  SelectControl,
  TextControl,
  TextareaControl,
} from "@wordpress/components";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGear } from "@fortawesome/pro-regular-svg-icons";
import Panel from "../Panel";
import List from "../../common/List";
import Item from "../../common/Item";

const DisplayList = ({ editDisplay, editor }) => {
  const { currentDisplay, displays, displayTypes, helpers, actions } =
    useContext(editor);
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
              <span>{display.label}</span>
              <span>{`ID: ${display.id}`}</span>
            </>
          );
        },
      },
      {
        id: "settings",
        content: <FontAwesomeIcon icon={faGear} />,
      },
    ],
    editor
  );

  const setFilteredType = (type) => {
    if (selectedType === type) {
      setSelectedType(null);
    } else {
      setSelectedType(type);
    }
  };

  const panelHeader = () => {
    return (
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
              const className = selectedType === displayType.id ? "active" : "";
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
      const isActive =
        typeof currentDisplay !== "object" &&
        display.id === Number(currentDisplay)
          ? true
          : false;

      return (
        <Item
          key={display.id}
          data={display}
          elements={elements}
          isActive={isActive}
          onItemClick={handleItemClick}
          onElementClick={handleElementClick}
        />
      );
    });
  };

  const panelContent = () => {
    return <List items={renderDisplays()} />;
  };

  /**
   * Perform actions on item click
   * @param {object} e
   * @param {object} display
   */
  const handleItemClick = (e, display) => {
    if (!e.target.closest(".ditty-editor-item__settings")) {
      actions.setCurrentDisplay(display.id);
    }
  };

  /**
   * Perform actions on element click
   * @param {object} e
   * @param {int} elementId
   * @param {object} display
   */
  const handleElementClick = (e, elementId, display) => {
    if ("settings" === elementId) {
      editDisplay(display);
    }
  };

  /**
   * Return the display list panel
   */
  return (
    <Panel id="displays" header={panelHeader()} content={panelContent()} />
  );
};
export default DisplayList;

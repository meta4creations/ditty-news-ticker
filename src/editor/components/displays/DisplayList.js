import { __ } from "@wordpress/i18n";
import { useState, useContext } from "@wordpress/element";
import {
  CheckboxControl,
  SelectControl,
  TextControl,
  TextareaControl,
} from "@wordpress/components";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faGear, faCheck } from "@fortawesome/pro-regular-svg-icons";
import Panel from "../Panel";
import List from "../../common/List";
import Item from "../../common/Item";

const DisplayList = ({ editDisplay, goBack, editor }) => {
  const { currentDisplay, displays, displayTypes, helpers, actions } =
    useContext(editor);
  const [previewDisplay, setPreviewDisplay] = useState(currentDisplay);
  const [searchQuery, setSearchQuery] = useState("");
  const [selectedType, setSelectedType] = useState(null);

  console.log("currentDisplay", currentDisplay);
  console.log("previewDisplay", previewDisplay);

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
      // {
      //   id: "select",
      //   content: <FontAwesomeIcon icon={faCheck} />,
      // },
    ],
    editor
  );

  const handleApprove = () => {
    goBack(previewDisplay);
  };

  const handleCancel = () => {
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
    if (Number(display.id) === Number(currentDisplay.id)) {
      return false;
    }

    const dittyEl = document.getElementById("ditty-editor__ditty");
    const ditty = window.ditty.get(dittyEl);
    ditty.destroy();

    dittyEl.dataset.type = display.type;
    dittyEl.dataset.display = display.id;
    dittyEl.dataset.settings = JSON.stringify(display.settings);

    const args = {
      element: dittyEl,
      display: display.id,
      type: display.type,
      ...display.setings,
    };
    console.log(window.dittyDisplays);
    if (window.dittyDisplays[display.type]) {
      const newDitty = new window.dittyDisplays[display.type](args);
      window.ditty.set(dittyEl, newDitty);
      actions.setCurrentDisplay(display.id);
    }
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

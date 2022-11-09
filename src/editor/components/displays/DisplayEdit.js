import { __ } from "@wordpress/i18n";
import { useContext, useState } from "@wordpress/element";
import { Button } from "@wordpress/components";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faPenToSquare,
  faArrowsLeftRight,
  faEllipsis,
  faContainerStorage,
  faLayerGroup,
  faPage,
  faObjectsColumn,
} from "@fortawesome/pro-regular-svg-icons";
import Panel from "../Panel";
import Field from "../../common/Field";
import { updateDisplayOptions } from "../../../services/dittyService";
import { getDisplayTypeLabel } from "../../utils/displayTypes";

const DisplayEdit = ({ displayObject, goBack, editor }) => {
  const { helpers, actions } = useContext(editor);

  /**
   * Set the initial fields
   */
  const fieldGroups = window.dittyHooks.applyFilters(
    "dittyDisplayEditFieldGroups",
    [
      {
        id: "general",
        label: __("General Settings", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faPenToSquare} />,
        fields: window.dittyHooks.applyFilters(
          "dittyDisplayEditFieldsGeneral",
          [],
          displayObject.type
        ),
      },
      {
        id: "arrows",
        label: __("Arrow Navigation", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faArrowsLeftRight} />,
        fields: window.dittyHooks.applyFilters(
          "dittyDisplayEditFieldsArrow",
          [
            {
              type: "radio",
              id: "direction",
              name: __("Direction", "ditty-news-ticker"),
              help: __("Set the direction of the ticker.", "ditty-news-ticker"),
              options: {
                left: __("Left", "ditty-news-ticker"),
                right: __("Right", "ditty-news-ticker"),
                down: __("Down", "ditty-news-ticker"),
                up: __("Up", "ditty-news-ticker"),
              },
              inline: true,
            },
          ],
          displayObject.type
        ),
      },
      {
        id: "bullets",
        label: __("Bullet Naviation", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faEllipsis} />,
        fields: window.dittyHooks.applyFilters(
          "dittyDisplayEditFieldsBullets",
          [
            {
              type: "radio",
              id: "direction",
              name: __("Direction", "ditty-news-ticker"),
              help: __("Set the direction of the ticker.", "ditty-news-ticker"),
              options: {
                left: __("Left", "ditty-news-ticker"),
                right: __("Right", "ditty-news-ticker"),
                down: __("Down", "ditty-news-ticker"),
                up: __("Up", "ditty-news-ticker"),
              },
              inline: true,
            },
          ],
          displayObject.type
        ),
      },
      {
        id: "container",
        label: __("Container Settings", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faContainerStorage} />,
        fields: window.dittyHooks.applyFilters(
          "dittyDisplayEditFieldsContainer",
          [
            {
              type: "radio",
              id: "direction",
              name: __("Direction", "ditty-news-ticker"),
              help: __("Set the direction of the ticker.", "ditty-news-ticker"),
              options: {
                left: __("Left", "ditty-news-ticker"),
                right: __("Right", "ditty-news-ticker"),
                down: __("Down", "ditty-news-ticker"),
                up: __("Up", "ditty-news-ticker"),
              },
              inline: true,
            },
          ],
          displayObject.type
        ),
      },
      {
        id: "content",
        label: __("Content Settings", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faLayerGroup} />,
        fields: window.dittyHooks.applyFilters(
          "dittyDisplayEditFieldsContent",
          [
            {
              type: "radio",
              id: "direction",
              name: __("Direction", "ditty-news-ticker"),
              help: __("Set the direction of the ticker.", "ditty-news-ticker"),
              options: {
                left: __("Left", "ditty-news-ticker"),
                right: __("Right", "ditty-news-ticker"),
                down: __("Down", "ditty-news-ticker"),
                up: __("Up", "ditty-news-ticker"),
              },
              inline: true,
            },
          ],
          displayObject.type
        ),
      },
      {
        id: "page",
        label: __("Page Settings", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faPage} />,
        fields: window.dittyHooks.applyFilters(
          "dittyDisplayEditFieldsPage",
          [
            {
              type: "radio",
              id: "direction",
              name: __("Direction", "ditty-news-ticker"),
              help: __("Set the direction of the ticker.", "ditty-news-ticker"),
              options: {
                left: __("Left", "ditty-news-ticker"),
                right: __("Right", "ditty-news-ticker"),
                down: __("Down", "ditty-news-ticker"),
                up: __("Up", "ditty-news-ticker"),
              },
              inline: true,
            },
          ],
          displayObject.type
        ),
      },
      {
        id: "item",
        label: __("Item Settings", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faObjectsColumn} />,
        fields: window.dittyHooks.applyFilters(
          "dittyDisplayEditFieldsItem",
          [
            {
              type: "radio",
              id: "direction",
              name: __("Direction", "ditty-news-ticker"),
              help: __("Set the direction of the ticker.", "ditty-news-ticker"),
              options: {
                left: __("Left", "ditty-news-ticker"),
                right: __("Right", "ditty-news-ticker"),
                down: __("Down", "ditty-news-ticker"),
                up: __("Up", "ditty-news-ticker"),
              },
              inline: true,
            },
          ],
          displayObject.type
        ),
      },
    ],
    displayObject.type
  );

  const initialTab = fieldGroups.length ? fieldGroups[0].id : "";
  const [currentTabId, setCurrentTabId] = useState(initialTab);

  /**
   * Set the current tab
   * @param {string} tab
   */
  const handleTabClick = (tab) => {
    setCurrentTabId(tab.id);
  };

  /**
   * Update the Display on field update
   * @param {object} field
   * @param {string} value
   */
  const handleFieldUpdate = (field, value) => {
    // Update the Ditty options
    const dittyEl = document.getElementById("ditty-editor__ditty");
    updateDisplayOptions(dittyEl, displayObject.type, field.id, value);

    // Update the editor display
    const updatedDisplay = { ...displayObject };
    updatedDisplay.settings[field.id] = value;
    actions.setCurrentDisplay(updatedDisplay);
  };

  /**
   * Render the panel header
   * @returns components
   */
  const panelHeader = () => {
    return (
      <>
        <h3>
          {__(
            `Custom ${getDisplayTypeLabel(displayObject)} display`,
            "ditty-news-ticker"
          )}
        </h3>
        <div className="ditty-editor__panel__header__buttons">
          <Button variant="secondary">
            {__("Save as Template", "ditty-news-ticker")}
          </Button>
          <Button onClick={goBack} variant="link">
            {__("Cancel", "ditty-news-ticker")}
          </Button>
        </div>
      </>
    );
  };

  /**
   * Render the panel content
   * @returns components
   */
  const panelContent = () => {
    const index = fieldGroups.findIndex((fieldGroup) => {
      return fieldGroup.id === currentTabId;
    });
    if (-1 === index) {
      return false;
    }

    const fields = fieldGroups[index].fields;
    return (
      fields &&
      fields.map((field) => {
        const value = displayObject.settings[field.id]
          ? displayObject.settings[field.id]
          : field.std;
        return (
          <Field
            key={field.id}
            field={field}
            value={value}
            onFieldUpdate={handleFieldUpdate}
          />
        );
      })
    );
  };

  return (
    <Panel
      id="displayEdit"
      header={panelHeader()}
      tabs={fieldGroups}
      tabClick={handleTabClick}
      currentTabId={currentTabId}
      content={panelContent()}
    />
  );
};
export default DisplayEdit;

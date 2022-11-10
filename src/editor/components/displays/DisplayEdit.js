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
        label: __("General", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faPenToSquare} />,
        fields: window.dittyHooks.applyFilters(
          "dittyDisplayEditFieldsGeneral",
          [],
          displayObject.type
        ),
      },
      {
        id: "arrows",
        label: __("Arrows", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faArrowsLeftRight} />,
        fields: window.dittyHooks.applyFilters(
          "dittyDisplayEditFieldsArrow",
          [
            {
              type: "select",
              id: "arrows",
              name: __("Arrows", "ditty-news-ticker"),
              help: __("Set the arrow navigation style", "ditty-news-ticker"),
              options: {
                none: __("Hide", "ditty-news-ticker"),
                style1: __("Show", "ditty-news-ticker"),
              },
            },
            {
              type: "color",
              id: "arrowsIconColor",
              name: __("Arrows Icon Color", "ditty-news-ticker"),
              help: __(
                "Add a custom icon color to the arrows",
                "ditty-news-ticker"
              ),
            },
            {
              type: "color",
              id: "arrowsBgColor",
              name: __("Arrows Background Color", "ditty-news-ticker"),
              help: __(
                "Add a custom background color to the arrows",
                "ditty-news-ticker"
              ),
            },
            {
              type: "select",
              id: "arrowsPosition",
              name: __("Arrows Position", "ditty-news-ticker"),
              help: __("Set the position of the arrows", "ditty-news-ticker"),
              options: {
                flexStart: __("Top", "ditty-news-ticker"),
                center: __("Center", "ditty-news-ticker"),
                flexEnd: __("Bottom", "ditty-news-ticker"),
              },
            },
            {
              type: "spacing",
              id: "arrowsPadding",
              name: __("Arrows Padding", "ditty-news-ticker"),
              help: __(
                "Add padding to the arrows container",
                "ditty-news-ticker"
              ),
            },
            {
              type: "checkbox",
              id: "arrowsStatic",
              name: __("Arrows Visibility", "ditty-news-ticker"),
              label: __(
                "Keep arrows visible at all times",
                "ditty-news-ticker"
              ),
              help: __("Keep arrows visible at all times", "ditty-news-ticker"),
            },
          ],
          displayObject.type
        ),
      },
      {
        id: "bullets",
        label: __("Bullets", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faEllipsis} />,
        fields: window.dittyHooks.applyFilters(
          "dittyDisplayEditFieldsBullets",
          [
            {
              type: "select",
              id: "bullets",
              name: __("Bullets", "ditty-news-ticker"),
              help: __("Set the bullet navigation style", "ditty-news-ticker"),
              options: {
                none: __("Hide", "ditty-news-ticker"),
                style1: __("Show", "ditty-news-ticker"),
              },
            },
            {
              type: "color",
              id: "bulletsColor",
              name: __("Bullets Color", "ditty-news-ticker"),
              help: __(
                "Add a custom color to the bullets",
                "ditty-news-ticker"
              ),
            },
            {
              type: "color",
              id: "bulletsColorActive",
              name: __("Bullets Active Color", "ditty-news-ticker"),
              help: __(
                "Add a custom color to the active bullet",
                "ditty-news-ticker"
              ),
            },
            {
              type: "select",
              id: "bulletsPosition",
              name: __("Bullets Position", "ditty-news-ticker"),
              help: __("Set the position of the bullets", "ditty-news-ticker"),
              options: {
                topLeft: __("Top Left", "ditty-news-ticker"),
                topCenter: __("Top Center", "ditty-news-ticker"),
                topRight: __("Top Right", "ditty-news-ticker"),
                bottomLeft: __("Bottom Left", "ditty-news-ticker"),
                bottomCenter: __("Bottom Center", "ditty-news-ticker"),
                bottomRight: __("Bottom Right", "ditty-news-ticker"),
              },
            },
            {
              type: "slider",
              id: "bulletsSpacing",
              name: __("Bullets Spacing", "ditty-news-ticker"),
              help: __(
                "Set the amount of space between bullets (in pixels).",
                "ditty-news-ticker"
              ),
              suffix: "px",
              js_options: {
                min: 0,
                max: 50,
                step: 1,
              },
            },
            {
              type: "spacing",
              id: "bulletsPadding",
              name: __("Bullets Padding", "ditty-news-ticker"),
              help: __(
                "Add padding to the bullets container",
                "ditty-news-ticker"
              ),
            },
          ],
          displayObject.type
        ),
      },
      {
        id: "container",
        label: __("Container", "ditty-news-ticker"),
        icon: <FontAwesomeIcon icon={faContainerStorage} />,
        fields: window.dittyHooks.applyFilters(
          "dittyDisplayEditFieldsContainer",
          [
            {
              type: "text",
              id: "maxWidth",
              name: __("Max. Width", "ditty-news-ticker"),
              help: __(
                "Set a maximum width for the container",
                "ditty-news-ticker"
              ),
            },
            {
              type: "color",
              id: "bgColor",
              name: __("Background Color", "ditty-news-ticker"),
            },
            {
              type: "spacing",
              id: "padding",
              name: __("Padding", "ditty-news-ticker"),
            },
            {
              type: "spacing",
              id: "margin",
              name: __("Margin", "ditty-news-ticker"),
              options: {
                marginTop: __("Top", "ditty-news-ticker"),
                marginBottom: __("Bottom", "ditty-news-ticker"),
                marginLeft: __("Left", "ditty-news-ticker"),
                marginRight: __("Right", "ditty-news-ticker"),
              },
            },
            {
              type: "border",
              id: "border",
              name: __("Border", "ditty-news-ticker"),
            },
          ],
          displayObject.type
        ),
      },
      {
        id: "content",
        label: __("Content", "ditty-news-ticker"),
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
        label: __("Page", "ditty-news-ticker"),
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
        label: __("Item", "ditty-news-ticker"),
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
      tabsType="cloud"
      content={panelContent()}
    />
  );
};
export default DisplayEdit;

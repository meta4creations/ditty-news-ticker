import { __ } from "@wordpress/i18n";
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

export const generalSettings = (displayType) => {
  return {
    id: "general",
    label: __("General", "ditty-news-ticker"),
    icon: <FontAwesomeIcon icon={faPenToSquare} />,
    fields: window.dittyHooks.applyFilters(
      "dittyDisplayEditFieldsGeneral",
      [],
      displayType
    ),
  };
};

export const arrowSettings = (displayType) => {
  return {
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
          help: __("Add padding to the arrows container", "ditty-news-ticker"),
        },
        {
          type: "checkbox",
          id: "arrowsStatic",
          name: __("Arrows Visibility", "ditty-news-ticker"),
          label: __("Keep arrows visible at all times", "ditty-news-ticker"),
          help: __("Keep arrows visible at all times", "ditty-news-ticker"),
        },
      ],
      displayType
    ),
  };
};

export const bulletSettings = (displayType) => {
  return {
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
          help: __("Add a custom color to the bullets", "ditty-news-ticker"),
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
          help: __("Add padding to the bullets container", "ditty-news-ticker"),
        },
      ],
      displayType
    ),
  };
};

export const containerStyles = (displayType) => {
  return {
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
      displayType
    ),
  };
};

export const contentStyles = (displayType) => {
  return {
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
      displayType
    ),
  };
};

export const pageStyles = (displayType) => {
  return {
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
      displayType
    ),
  };
};

export const itemStyles = (displayType) => {
  return {
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
      displayType
    ),
  };
};

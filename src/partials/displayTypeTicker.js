const { addFilter } = wp.hooks;
const { __ } = wp.i18n;

if (dittyEditor) {
  const { easeOptions } = dittyEditor.helpers;
  const { Icon } = dittyEditor.components;

  dittyEditor.registerDisplayType({
    id: "ticker",
    icon: <Icon id="faEllipsis" />,
    label: __("Ticker", "ditty-news-ticker"),
    description: __(
      "Display items in a basic news ticker.",
      "ditty-news-ticker"
    ),
    settings: {
      general: true,
      styles: ["item", "container", "content"],
      title: true,
    },
    defaultValues: {
      direction: "left",
      minHeight: "300px",
      spacing: "25",
      speed: "10",
      heightEase: "easeInOutQuint",
      heightSpeed: "1.5",
      scrollInit: "empty",
      scrollDelay: "3",
      cloneItems: "yes",
      wrapItems: "yes",
      hoverPause: "",
      titleDisplay: "none",
      titleContentsSize: "stretch",
      titleContentsPosition: "start",
      titleElement: "h3",
      titleElementPosition: "start",
      titleElementVerticalPosition: "start",
      itemElementsWrap: "nowrap",
    },
  });

  /**
   * Add the ticker fields
   */
  addFilter(
    "dittyEditor.displaySettingsGeneralFields",
    "ditty-news-ticker/displaySettingsGeneralFields",
    (fields, displayType) => {
      if ("ticker" !== displayType) {
        return fields;
      }
      fields = [
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
        {
          type: "unit",
          id: "minHeight",
          name: __("Min. Height", "ditty-news-ticker"),
          help: __(
            "Set the minimum height of the Ditty for vertical scrolling tickers.",
            "ditty-news-ticker"
          ),
          show: {
            relation: "OR",
            fields: [
              { key: "direction", value: "down", compare: "=" },
              { key: "direction", value: "up", compare: "=" },
            ],
          },
        },
        {
          type: "unit",
          id: "maxHeight",
          name: __("Max. Height", "ditty-news-ticker"),
          help: __(
            "Set the maximum height of the Ditty for vertical scrolling tickers.",
            "ditty-news-ticker"
          ),
          show: {
            relation: "OR",
            fields: [
              { key: "direction", value: "down", compare: "=" },
              { key: "direction", value: "up", compare: "=" },
            ],
          },
        },
        {
          type: "slider",
          id: "spacing",
          name: __("Spacing", "ditty-news-ticker"),
          help: __(
            "Set the amount of space between items (in pixels).",
            "ditty-news-ticker"
          ),
          min: 0,
          max: 100,
          step: 1,
          suffix: "px",
        },
        {
          type: "slider",
          id: "speed",
          name: __("Speed", "ditty-news-ticker"),
          help: __("Set the speed of the ticker.", "ditty-news-ticker"),
          min: 0,
          max: 50,
          step: 1,
        },
        {
          type: "select",
          id: "heightEase",
          name: __("Height Ease", "ditty-news-ticker"),
          help: __("Set the easing of the ticker height.", "ditty-news-ticker"),
          options: easeOptions,
        },
        {
          type: "slider",
          id: "heightSpeed",
          name: __("Height Speed", "ditty-news-ticker"),
          help: __("Set the speed of the ticker height.", "ditty-news-ticker"),
          min: 0,
          max: 10,
          step: 0.25,
          suffix: __("second(s)", "ditty-news-ticker"),
        },
        {
          type: "radio",
          id: "scrollInit",
          name: __("Initial Display", "ditty-news-ticker"),
          help: __(
            "Choose how the ticker should initialize.",
            "ditty-news-ticker"
          ),
          options: {
            empty: __("Empty", "ditty-news-ticker"),
            filled: __("Filled", "ditty-news-ticker"),
          },
          inline: true,
        },
        {
          type: "slider",
          id: "scrollDelay",
          name: __("Scroll Delay", "ditty-news-ticker"),
          help: __(
            "Delay the start of scrolling for filled tickers.",
            "ditty-news-ticker"
          ),
          min: 0,
          max: 10,
          step: 0.25,
          suffix: __("second(s)", "ditty-news-ticker"),
          show: {
            fields: [{ key: "scrollInit", value: "filled", compare: "=" }],
          },
        },
        {
          type: "unit",
          id: "itemMaxWidth",
          name: __("Item Max Width", "ditty-news-ticker"),
          help: __("Set a maximum width for items", "ditty-news-ticker"),
        },
        {
          type: "radio",
          id: "itemElementsWrap",
          name: __("Wrap Item Elements", "ditty-news-ticker"),
          help: __(
            "Allow item elements to wrap, or force them to not wrap.",
            "ditty-news-ticker"
          ),
          inline: true,
          options: {
            wrap: __("Wrap", "ditty-news-ticker"),
            nowrap: __("No Wrap", "ditty-news-ticker"),
          },
        },
        {
          type: "radio",
          id: "cloneItems",
          name: __("Clone Items?", "ditty-news-ticker"),
          help: __(
            "Should items continually clone to fill the ticker?",
            "ditty-news-ticker"
          ),
          options: {
            yes: __("Yes", "ditty-news-ticker"),
            no: __("No", "ditty-news-ticker"),
          },
          inline: true,
        },
        {
          type: "radio",
          id: "wrapItems",
          name: __("Wrap Items?", "ditty-news-ticker"),
          help: __(
            "Should items restart before all items have finished scrolling?",
            "ditty-news-ticker"
          ),
          options: {
            yes: __("Yes", "ditty-news-ticker"),
            no: __("No", "ditty-news-ticker"),
          },
          inline: true,
        },
        {
          type: "checkbox",
          id: "hoverPause",
          name: __("Hover Pause", "ditty-news-ticker"),
          label: __("Pause the ticker on mouse over", "ditty-news-ticker"),
          help: __("Pause the ticker on mouse over.", "ditty-news-ticker"),
        },
        {
          type: "checkbox",
          id: "shuffle",
          name: __("Shuffle Items", "ditty-news-ticker"),
          label: __(
            "Randomly shuffle items on each page load",
            "ditty-news-ticker"
          ),
          help: __(
            "Randomly shuffle items on each page load.",
            "ditty-news-ticker"
          ),
        },
        {
          type: "checkbox",
          id: "playPauseButton",
          name: __("Play/Pause Button", "ditty-news-ticker"),
          label: __("Add a play/pause button to the container", "ditty-news-ticker"),
          help: __("Add a play/pause button to the container.", "ditty-news-ticker"),
        },
      ];

      return fields;
    }
  );
}

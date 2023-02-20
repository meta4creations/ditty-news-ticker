import { __ } from "@wordpress/i18n";
import { easeOptions, sliderTransitions } from "../utils/helpers";

import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faList } from "@fortawesome/pro-light-svg-icons";

if (dittyEditor) {
  dittyEditor.registerDisplayType({
    id: "list",
    icon: <FontAwesomeIcon icon={faList} />,
    label: __("List", "ditty-news-ticker"),
    description: __("Display items in a static list.", "ditty-news-ticker"),
    settings: {
      general: true,
      navigation: ["arrows", "bullets"],
      styles: ["container", "content", "page", "item"],
    },
    defaultValues: {
      spacing: "14",
      paging: "1",
      perPage: "10",
      transition: "fade",
      transitionEase: "easeInOutQuint",
      transitionSpeed: "1",
      heightEase: "easeInOutQuint",
      heightSpeed: "1",
      arrows: "none",
      arrowsPosition: "center",
      arrowsPadding: {
        paddingTop: "",
        paddingBottom: "20px",
        paddingLeft: "",
        paddingRight: "",
      },
      bullets: "style1",
      bulletsPosition: "bottomCenter",
      bulletsSpacing: "2",
      bulletsPadding: {
        paddingTop: "20px",
        paddingBottom: "",
        paddingLeft: "",
        paddingRight: "",
      },
      borderStyle: "none",
      contentsBorderStyle: "none",
      pageBorderStyle: "none",
      itemBorderStyle: "none",
    },
  });

  /**
   * Add the list fields
   */
  dittyEditor.addFilter(
    "displaySettingsGeneralFields",
    (fields, displayType) => {
      if ("list" !== displayType) {
        return fields;
      }
      fields = [
        {
          type: "slider",
          id: "spacing",
          name: __("Spacing", "ditty-news-ticker"),
          help: __(
            "Set the amount of space between items (in pixels).",
            "ditty-news-ticker"
          ),
          suffix: "px",
          min: 0,
          max: 100,
          step: 1,
          std: 15,
        },
        {
          type: "radio",
          id: "paging",
          name: __("Paging", "ditty-news-ticker"),
          help: __("Split the list into pages", "ditty-news-ticker"),
          inline: true,
          options: {
            0: __("No", "ditty-news-ticker"),
            1: __("Yes", "ditty-news-ticker"),
          },
          std: 1,
        },
        {
          type: "number",
          id: "perPage",
          name: __("Items Per Page", "ditty-news-ticker"),
          help: __(
            "Set the number of items to show per page",
            "ditty-news-ticker"
          ),
          std: 10,
          show: {
            fields: [{ key: "paging", value: "1", compare: "=" }],
          },
        },
        {
          type: "select",
          id: "transition",
          name: __("Page Transition", "ditty-news-ticker"),
          help: __(
            "Set the type of transition to use between pages",
            "ditty-news-ticker"
          ),
          options: sliderTransitions,
          std: "fade",
          show: {
            fields: [{ key: "paging", value: "1", compare: "=" }],
          },
        },
        {
          type: "select",
          id: "transitionEase",
          name: __("Page Transition Ease", "ditty-news-ticker"),
          help: __(
            "Set the easing of the transition between pages.",
            "ditty-news-ticker"
          ),
          options: easeOptions,
          std: "easeInOutQuint",
          show: {
            fields: [{ key: "paging", value: "1", compare: "=" }],
          },
        },
        {
          type: "slider",
          id: "transitionSpeed",
          name: __("Page Transition Speed", "ditty-news-ticker"),
          help: __(
            "Set the speed of the transition between pages.",
            "ditty-news-ticker"
          ),
          suffix: __("second(s)", "ditty-news-ticker"),
          min: 0,
          max: 10,
          step: 0.25,
          std: 1,
          show: {
            fields: [{ key: "paging", value: "1", compare: "=" }],
          },
        },
        {
          type: "select",
          id: "heightEase",
          name: __("Height Ease", "ditty-news-ticker"),
          help: __("Set the easing of the list height.", "ditty-news-ticker"),
          options: easeOptions,
          std: "easeInOutQuint",
        },
        {
          type: "slider",
          id: "heightSpeed",
          name: __("Height Speed", "ditty-news-ticker"),
          help: __("Set the speed of the list height.", "ditty-news-ticker"),
          suffix: __("second(s)", "ditty-news-ticker"),
          min: 0,
          max: 10,
          step: 0.25,
          std: 1,
        },
        {
          type: "radio",
          id: "autoplay",
          name: __("Auto Play", "ditty-news-ticker"),
          help: __("Auto play the slider", "ditty-news-ticker"),
          inline: true,
          options: {
            0: __("No", "ditty-news-ticker"),
            1: __("Yes", "ditty-news-ticker"),
          },
          std: 0,
          show: {
            fields: [{ key: "paging", value: "1", compare: "=" }],
          },
        },
        {
          type: "checkbox",
          id: "autoplayPause",
          name: __("Pause Autoplay on Hover", "ditty-news-ticker"),
          label: __("Pause the autoplay on mouse over", "ditty-news-ticker"),
          help: __("Pause the autoplay on mouse over", "ditty-news-ticker"),
          show: {
            fields: [{ key: "paging", value: "1", compare: "=" }],
          },
        },
        {
          type: "slider",
          id: "autoplaySpeed",
          name: __("Auto Play Speed", "ditty-news-ticker"),
          help: __(
            "Set the amount of delay between slides",
            "ditty-news-ticker"
          ),
          suffix: __("seconds", "ditty-news-ticker"),
          min: 0,
          max: 60,
          step: 0.25,
          std: 7,
          show: {
            fields: [{ key: "paging", value: "1", compare: "=" }],
          },
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
            "Randomly shuffle items on each page load",
            "ditty-news-ticker"
          ),
        },
      ];

      return fields;
    }
  );
}

const { __ } = wp.i18n;
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faArrowsLeftRight, faSliders } from "@fortawesome/pro-light-svg-icons";
import config from "./display.json";

if (dittyEditor) {
  const { easeOptions, sliderTransitions } = dittyEditor.helpers;

  const displayType = __("Slider", "ditty-news-ticker");
  dittyEditor.registerDisplayType({
    id: "slider",
    icon: <FontAwesomeIcon icon={faArrowsLeftRight} />,
    label: __("Slider", "ditty-news-ticker"),
    description: __("Display items in a slider.", "ditty-news-ticker"),
    settings: {
      general: {
        id: "general",
        label: __("General", "ditty-news-ticker"),
        name: __("General Settings", "ditty-news-ticker"),
        desc: __(
          `Set the general settings of the ${displayType}.`,
          "ditty-news-ticker"
        ),
        icon: <FontAwesomeIcon icon={faSliders} />,
        fields: [
          {
            type: "number",
            id: "slidesPerView",
            name: __("Items Per View", "ditty-news-ticker"),
            help: __(
              "Set the number of items to show per view.",
              "ditty-news-ticker"
            ),
            std: 1,
          },
          {
            type: "slider",
            id: "slidesSpacing",
            name: __("Spacing", "ditty-news-ticker"),
            help: __(
              "Set the amount of space between items (in pixels).",
              "ditty-news-ticker"
            ),
            suffix: "px",
            min: 0,
            max: 100,
            step: 1,
            std: 0,
          },
          {
            type: "radio",
            id: "autoplay",
            name: __("Auto Play", "ditty-news-ticker"),
            help: __(
              "Automatically transition pages on a timer.",
              "ditty-news-ticker"
            ),
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
            help: __("Pause the autoplay on mouse over.", "ditty-news-ticker"),
            show: {
              fields: [{ key: "paging", value: "1", compare: "=" }],
            },
          },
          {
            type: "slider",
            id: "autoplaySpeed",
            name: __("Auto Play Speed", "ditty-news-ticker"),
            help: __(
              "Set the amount of delay between slides.",
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
            type: "group",
            name: __("Transition Animations", "ditty-news-ticker"),
            description: __(
              "Configure page and height transitions.",
              "ditty-news-ticker"
            ),
            multipleFields: true,
            defaultState: "collapsed",
            collapsible: true,
            fields: [
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
              },
              {
                type: "select",
                id: "heightEase",
                name: __("Height Ease", "ditty-news-ticker"),
                help: __(
                  "Set the easing of the list height.",
                  "ditty-news-ticker"
                ),
                options: easeOptions,
                std: "easeInOutQuint",
              },
              {
                type: "slider",
                id: "heightSpeed",
                name: __("Height Speed", "ditty-news-ticker"),
                help: __(
                  "Set the speed of the list height.",
                  "ditty-news-ticker"
                ),
                suffix: __("second(s)", "ditty-news-ticker"),
                min: 0,
                max: 10,
                step: 0.25,
                std: 1,
              },
            ],
          },
        ],
      },
      navigation: ["arrows", "bullets"],
      styles: ["item", "container", "content", "page"],
      title: true,
    },
    defaultValues: config.defaults,
  });
}

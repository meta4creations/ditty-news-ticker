const { __ } = wp.i18n;

if (dittyEditor) {
  const { easeOptions, sliderTransitions } = dittyEditor.helpers;
  const { Icon } = dittyEditor.components;

  const displayType = __("List", "ditty-news-ticker");
  dittyEditor.registerDisplayType({
    id: "list",
    icon: <Icon id="faList" />,
    label: __("List", "ditty-news-ticker"),
    description: __("Display items in a static list.", "ditty-news-ticker"),
    settings: {
      general: {
        id: "general",
        label: __("General", "ditty-news-ticker"),
        name: __("General Settings", "ditty-news-ticker"),
        desc: __(
          `Set the general settings of the ${displayType}.`,
          "ditty-news-ticker"
        ),
        icon: <Icon id="faSliders" />,
        fields: [
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
              "Set the number of items to show per page.",
              "ditty-news-ticker"
            ),
            std: 10,
            show: {
              fields: [{ key: "paging", value: "1", compare: "=" }],
            },
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
          {
            type: "group",
            name: __("Initial Animation", "ditty-news-ticker"),
            description: __(
              "Configure the initial transition on load.",
              "ditty-news-ticker"
            ),
            multipleFields: true,
            defaultState: "collapsed",
            collapsible: true,
            fields: [
              {
                type: "select",
                id: "initTransition",
                name: __("Initial Page Transition", "ditty-news-ticker"),
                help: __(
                  "Set the transition for initial display.",
                  "ditty-news-ticker"
                ),
                options: sliderTransitions,
              },
              {
                type: "select",
                id: "initTransitionEase",
                name: __("Initial Page Transition Ease", "ditty-news-ticker"),
                help: __(
                  "Set the easing for initial display.",
                  "ditty-news-ticker"
                ),
                options: easeOptions,
              },
              {
                type: "slider",
                id: "initTransitionSpeed",
                name: __("Initial Page Transition Speed", "ditty-news-ticker"),
                help: __(
                  "Set the transition speed for initial display.",
                  "ditty-news-ticker"
                ),
                suffix: __("second(s)", "ditty-news-ticker"),
                min: 0,
                max: 10,
                step: 0.25,
              },
              {
                type: "select",
                id: "initHeightEase",
                name: __("Initial Height Ease", "ditty-news-ticker"),
                help: __(
                  "Set the height easing for initial display.",
                  "ditty-news-ticker"
                ),
                options: easeOptions,
              },
              {
                type: "slider",
                id: "initHeightSpeed",
                name: __("Initial Height Speed", "ditty-news-ticker"),
                help: __(
                  "Set the height speed for initial display.",
                  "ditty-news-ticker"
                ),
                suffix: __("second(s)", "ditty-news-ticker"),
                min: 0,
                max: 10,
                step: 0.25,
              },
            ],
          },
        ],
      },
      navigation: ["arrows", "bullets"],
      styles: ["item", "container", "content", "page"],
      title: true,
    },
    defaultValues: {
      spacing: "15",
      paging: "1",
      perPage: "10",
      autoplay: "0",
      transition: "fade",
      transitionEase: "easeInOutQuint",
      transitionSpeed: "1",
      heightEase: "easeInOutQuint",
      heightSpeed: "1",
      initTransition: "fade",
      initTransitionEase: "easeInOutQuint",
      initTransitionSpeed: "1",
      initHeightEase: "easeInOutQuint",
      initHeightSpeed: "1",
      arrows: "none",
      arrowsPosition: "center",
      arrowsIconColor: "#777",
      arrowsStatic: "1",
      arrowsPadding: {
        paddingTop: "",
        paddingBottom: "",
        paddingLeft: "",
        paddingRight: "",
      },
      bullets: "style1",
      bulletsPosition: "bottomCenter",
      bulletsColor: "#777",
      bulletsColorActive: "#000",
      bulletsSpacing: "5px",
      bulletsPadding: {
        paddingTop: "",
        paddingBottom: "",
        paddingLeft: "",
        paddingRight: "",
      },
      borderStyle: "none",
      contentsBorderStyle: "none",
      pageBorderStyle: "none",
      itemBorderStyle: "none",
      titleDisplay: "none",
      titleContentsSize: "stretch",
      titleContentsPosition: "start",
      titleElement: "h3",
      titleElementPosition: "start",
      titleElementVerticalPosition: "start",
    },
  });
}

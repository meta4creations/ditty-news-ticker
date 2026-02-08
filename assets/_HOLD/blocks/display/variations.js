import { __ } from "@wordpress/i18n";
import { lineDashed, listView, loop, grid } from "@wordpress/icons";

const variations = [
  {
    name: "ticker",
    title: __("Ditty Ticker", "ditty-pro"),
    description: __("Display items in a news ticker."),
    attributes: { type: "ticker" },
    scope: ["block", "inserter", "transform"],
    isActive: (blockAttributes) => blockAttributes.type === "ticker",
    icon: lineDashed,
  },
  {
    name: "list",
    title: __("Ditty List", "ditty-pro"),
    description: __("Display items in a list."),
    attributes: { type: "list" }, // Use "type" directly
    isDefault: true, // Set this as default if needed
    scope: ["block", "inserter", "transform"],
    isActive: (blockAttributes) => blockAttributes.type === "list",
    icon: listView,
  },
  {
    name: "slider",
    title: __("Ditty Slider", "ditty-pro"),
    description: __("Display items in a slider."),
    attributes: { type: "slider" }, // Corrected attribute for slider
    scope: ["block", "inserter", "transform"],
    isActive: (blockAttributes) => blockAttributes.type === "slider",
    icon: loop,
  },
  {
    name: "grid",
    title: __("Ditty Grid", "ditty-pro"),
    description: __("Display items in a grid."),
    attributes: { type: "grid" }, // Correct attribute for grid
    scope: ["block", "inserter", "transform"],
    isActive: (blockAttributes) => blockAttributes.type === "grid",
    icon: grid,
  },
];

export default variations;

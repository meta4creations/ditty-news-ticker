import DittyDisplay from "./components/dittyDisplay";

export default class DittyDisplayList extends DittyDisplay {
  constructor(config) {
    const defaults = {
      spacing: 30,
    };
    super({ ...defaults, ...config });

    this.initialize();

    //this.$ditty.addEventListener("mouseenter", () => this.mouseEnter(this));
    //this.$ditty.addEventListener("mouseleave", () => this.mouseLeave(this));

    window.dittyHooks.addFilter(
      "dittyDisplayStyles",
      "ditty",
      this.updateDisplayStyles
    );
  }

  initialize() {
    console.log("Initialize List");
  }

  /**
   * Stop the ticker on mouse enter
   */
  // mouseEnter(ditty) {
  //   if (ditty.config.hoverPause) {
  //     this.paused = true;
  //     ditty.stopTicker();
  //   }
  // }

  /**
   * Start the ticker on mouse leave
   */
  // mouseLeave(ditty) {
  //   if (ditty.config.hoverPause) {
  //     this.paused = false;
  //     if (this.itemsInit) {
  //       ditty.startTicker();
  //     }
  //   }
  // }

  updateDisplayStyles(styles, settings, display, type) {
    if ("list" !== type) {
      return styles;
    }
    return styles;
  }
}

window.dittyDisplays.list = DittyDisplayList;

/**
 * Ditty V4 - Display Integration
 *
 * Handles initialization of Ditty displays using either:
 * - Custom DittyTicker for ticker type (continuous scrolling)
 * - Splide for list/carousel type (slide-based)
 *
 * @package Ditty
 * @since   4.0
 */

import Splide from "@splidejs/splide";
import "@splidejs/splide/css";
import DittyTicker from "./DittyTicker";
import "./dittyV4.scss";

/**
 * Parse the Ditty configuration from data attribute
 *
 * @param {HTMLElement} element - The Ditty element
 * @returns {Object} Configuration object
 */
function parseConfig(element) {
  const configAttr = element.dataset.dittyConfig;
  if (!configAttr) {
    return { type: "list" };
  }

  try {
    return JSON.parse(configAttr);
  } catch (e) {
    console.warn("Ditty: Failed to parse config", e);
    return { type: "list" };
  }
}

/**
 * Build ticker configuration from Ditty config
 *
 * @param {Object} config - Ditty configuration
 * @returns {Object} Ticker configuration
 */
function buildTickerConfig(config) {
  return {
    direction: config.direction || "left",
    speed: parseInt(config.speed, 10) || 10,
    spacing: parseInt(config.spacing, 10) || 25,
    hoverPause: Boolean(config.hoverPause),
    cloneItems: config.cloneItems !== "no",
  };
}

/**
 * Build Splide options for list/carousel type
 *
 * @param {Object} config - Ditty configuration
 * @returns {Object} Splide options
 */
function buildListOptions(config) {
  const spacing = parseInt(config.spacing, 10) || 25;
  const hoverPause = Boolean(config.hoverPause);

  return {
    type: "loop",
    perPage: 1,
    gap: spacing,
    autoplay: true,
    pauseOnHover: hoverPause,
    pauseOnFocus: true,
    interval: 3000,
    speed: 400,
    arrows: true,
    pagination: true,
  };
}

/**
 * Initialize a single Ditty element
 *
 * @param {HTMLElement} element - The Ditty element
 */
function initDittyElement(element) {
  // Skip if already initialized
  if (element.classList.contains("ditty--initialized")) {
    return;
  }

  const config = parseConfig(element);
  const dittyType = config.type || "list";

  if (dittyType === "ticker") {
    // Use custom DittyTicker for ticker type
    const tickerConfig = buildTickerConfig(config);
    const ticker = new DittyTicker(element, tickerConfig);

    // Store reference on element
    element._dittyTicker = ticker;
  } else {
    // Use Splide for list/carousel type
    const splideOptions = buildListOptions(config);
    const splide = new Splide(element, splideOptions);
    splide.mount();

    // Store reference and mark as initialized
    element._dittySplide = splide;
    element.classList.add("ditty--initialized");
  }
}

/**
 * Initialize all Ditty elements on the page
 */
function initDittySplide() {
  const dittyElements = document.querySelectorAll(".ditty");
  dittyElements.forEach(initDittyElement);
}

// Initialize on DOM ready
if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initDittySplide);
} else {
  initDittySplide();
}

// Export for potential external use
window.initDittySplide = initDittySplide;
window.DittyTicker = DittyTicker;

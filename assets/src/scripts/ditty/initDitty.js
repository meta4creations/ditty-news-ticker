const liveIds = {};
let liveInterval = null;

function liveUpdate(dittyId, items) {
  document.querySelectorAll(`.ditty[data-id="${dittyId}"]`).forEach((el) => {
    const displayType = el.getAttribute("data-type");
    if (dittyVars.mode === "development" && window.console) {
      console.log(`LIVE UPDATE: ${dittyId}`);
    }
    if (typeof el[`ditty_${displayType}`] === "function") {
      el[`ditty_${displayType}`]("loadItems", items, "static");
    }
  });
}

function checkLiveUpdates() {
  fetch(`${dittyVars.restUrl}ditty/v1/live-updates`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-WP-Nonce": dittyVars.restNonce,
    },
    body: JSON.stringify({
      live_ids: liveIds,
    }),
  })
    .then((res) => res.json())
    .then((response) => {
      if (response.updated_items) {
        Object.entries(response.updated_items).forEach(([dittyId, items]) => {
          liveUpdate(dittyId, items);
          liveIds[dittyId].timestamp = Math.floor(Date.now() / 1000);
        });
      }
    });
}

function startLiveUpdates() {
  if (liveInterval !== null || Object.keys(liveIds).length < 1) return false;
  cancelAnimationFrame(liveInterval);

  const updateInterval = dittyVars.updateInterval
    ? parseInt(dittyVars.updateInterval)
    : 60;
  let startTime = Date.now();

  function dittyLiveUpdatesLoop() {
    const currTime = Date.now();
    const passedTime = Math.floor((currTime - startTime) / 1000);

    if (passedTime >= updateInterval) {
      startTime = currTime;
      checkLiveUpdates();
    }
    liveInterval = requestAnimationFrame(dittyLiveUpdatesLoop);
  }

  liveInterval = requestAnimationFrame(dittyLiveUpdatesLoop);
}

function setupGlobalDitty() {
  dittyVars.globals.forEach((data) => {
    if (!data.ditty || !data.selector) return;
    const selector = document.querySelector(data.selector);
    if (!selector) return;

    const $ditty = document.createElement("div");
    $ditty.className = "ditty";
    $ditty.setAttribute("data-id", data.ditty);
    $ditty.setAttribute("data-ajax_load", "1");

    if (data.display) $ditty.setAttribute("data-display", data.display);
    if (data.live_updates === "1")
      $ditty.setAttribute("data-live_updates", "1");
    if (data.custom_id) $ditty.id = data.custom_id;
    if (data.custom_classes)
      $ditty.classList.add(...data.custom_classes.split(" "));
    if (data.edit_links) $ditty.innerHTML = data.edit_links;

    switch (data.position) {
      case "prepend":
        selector.prepend($ditty);
        break;
      case "before":
        selector.parentNode.insertBefore($ditty, selector);
        break;
      case "after":
        selector.parentNode.insertBefore($ditty, selector.nextSibling);
        break;
      default:
        selector.appendChild($ditty);
        break;
    }
  });
}

const initDitty = () => {
  setupGlobalDitty();

  document.querySelectorAll(".ditty").forEach(($ditty) => {
    const id = $ditty.dataset.id;
    const ajaxLoad = $ditty.dataset.ajax_load === "1";
    const liveUpdates = $ditty.dataset.live_updates === "1";
    const displaySettings = $ditty.dataset.display_settings || false;
    const layoutSettings = $ditty.dataset.layout_settings || false;
    const editor = $ditty.dataset.show_editor === "1";

    if (ajaxLoad) {
      const data = {
        id,
        uniqid: $ditty.dataset.uniqid || false,
        display: $ditty.dataset.display || "",
        display_settings: displaySettings,
        layout_settings: layoutSettings,
        editor,
      };

      fetch(`${dittyVars.restUrl}ditty/v1/init`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-WP-Nonce": dittyVars.restNonce,
        },
        body: JSON.stringify(data),
      })
        .then((res) => res.json())
        .then((response) => {
          if (
            !response.display_type ||
            typeof $ditty[`ditty_${response.display_type}`] !== "function"
          ) {
            console.log(
              "Ditty Display type not loaded:",
              response.display_type
            );
            return;
          }

          $ditty[`ditty_${response.display_type}`](response.args);

          if (!editor && liveUpdates) {
            liveIds[id] = {
              timestamp: Math.floor(Date.now() / 1000),
              layout_settings: layoutSettings,
            };
            startLiveUpdates();
          }
        });
    } else {
      if (!editor && liveUpdates) {
        liveIds[id] = {
          timestamp: Math.floor(Date.now() / 1000),
          layout_settings: layoutSettings,
        };
        startLiveUpdates();
      }
    }
  });
};

export default initDitty;

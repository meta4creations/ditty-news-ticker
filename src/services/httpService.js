import axios from "axios";

export function saveDitty(data, onComplete) {
  // Pass url params
  const urlParams = new URLSearchParams(location.search);
  const paramsObj = {};
  for (const [key, value] of urlParams) {
    paramsObj[key] = value;
  }
  const apiURL = `${dittyEditorVars.restUrl}dittyeditor/v1/save`;
  const apiData = {
    userId: dittyEditorVars.userId,
    urlParams: paramsObj,
    ...data,
  };
  const config = {
    headers: {
      "X-WP-Nonce": dittyEditorVars.nonce,
    },
  };
  return axios.post(apiURL, { apiData }, config).then((res) => {
    onComplete(res.data);
  });
}

export function saveDisplay(data, onComplete) {
  const apiURL = `${dittyEditorVars.restUrl}dittyeditor/v1/saveDisplay`;
  const apiData = {
    userId: dittyEditorVars.userId,
    ...data,
  };
  const config = {
    headers: {
      "X-WP-Nonce": dittyEditorVars.nonce,
    },
  };
  return axios.post(apiURL, { apiData }, config).then((res) => {
    onComplete && onComplete(res.data);
  });
}

export function saveLayout(data, onComplete) {
  const apiURL = `${dittyEditorVars.restUrl}dittyeditor/v1/saveLayout`;
  const apiData = {
    userId: dittyEditorVars.userId,
    ...data,
  };
  const config = {
    headers: {
      "X-WP-Nonce": dittyEditorVars.nonce,
    },
  };
  return axios.post(apiURL, { apiData }, config).then((res) => {
    onComplete(res.data);
  });
}

export function saveSettings(updatedSettings, onComplete) {
  const apiURL = `${dittySettingsVars.restUrl}dittyeditor/v1/saveSettings`;
  const apiData = {
    userId: dittySettingsVars.userId,
    settings: updatedSettings,
  };
  const config = {
    headers: {
      "X-WP-Nonce": dittySettingsVars.nonce,
    },
  };
  return axios.post(apiURL, { apiData }, config).then((res) => {
    onComplete(res.data);
  });
}

export function getRenderedItems(items, layouts, onComplete) {
  const apiURL = `${dittyEditorVars.restUrl}dittyeditor/v1/displayItems`;
  const apiData = {
    userId: dittyEditorVars.userId,
    items: items,
    layouts: layouts,
  };
  const config = {
    headers: {
      "X-WP-Nonce": dittyEditorVars.nonce,
    },
  };
  return axios.post(apiURL, { apiData }, config).then((res) => {
    onComplete && onComplete(res.data);
  });
}

export function phpItemMods(item, hook = false, onComplete) {
  const apiURL = `${dittyEditorVars.restUrl}dittyeditor/v1/phpItemMods`;
  const apiData = {
    userId: dittyEditorVars.userId,
    item: item,
    hook: hook,
  };
  const config = {
    headers: {
      "X-WP-Nonce": dittyEditorVars.nonce,
    },
  };
  return axios.post(apiURL, { apiData }, config).then((res) => {
    onComplete && onComplete(res.data);
  });
}

export function refreshTranslations(dittyId, onComplete) {
  const apiURL = `${dittyEditorVars.restUrl}dittyeditor/v1/refreshTranslations`;
  const apiData = {
    userId: dittyEditorVars.userId,
    id: dittyId,
  };
  const config = {
    headers: {
      "X-WP-Nonce": dittyEditorVars.nonce,
    },
  };
  return axios.post(apiURL, { apiData }, config).then((res) => {
    onComplete && onComplete(res.data);
  });
}

export function dynamicLayoutTags(itemType, itemValue, onComplete) {
  const apiURL = `${dittyEditorVars.restUrl}dittyeditor/v1/dynamicLayoutTags`;
  const apiData = {
    itemType: itemType,
    itemValue: itemValue,
  };
  const config = {
    headers: {
      "X-WP-Nonce": dittyEditorVars.nonce,
    },
  };
  return axios.post(apiURL, { apiData }, config).then((res) => {
    onComplete && onComplete(res.data);
  });
}

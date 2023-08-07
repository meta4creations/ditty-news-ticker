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
    security: dittyEditorVars.security,
    userId: dittyEditorVars.userId,
    urlParams: paramsObj,
    ...data,
  };
  return axios.post(apiURL, { apiData }).then((res) => {
    console.log("data", res.data);
    onComplete(res.data);
  });
}

export function saveDisplay(data, onComplete) {
  const apiURL = `${dittyEditorVars.restUrl}dittyeditor/v1/saveDisplay`;
  const apiData = {
    security: dittyEditorVars.security,
    userId: dittyEditorVars.userId,
    ...data,
  };
  return axios.post(apiURL, { apiData }).then((res) => {
    onComplete(res.data);
  });
}

export function saveLayout(data, onComplete) {
  const apiURL = `${dittyEditorVars.restUrl}dittyeditor/v1/saveLayout`;
  const apiData = {
    security: dittyEditorVars.security,
    userId: dittyEditorVars.userId,
    ...data,
  };
  return axios.post(apiURL, { apiData }).then((res) => {
    onComplete(res.data);
  });
}

export function saveSettings(updatedSettings, onComplete) {
  const apiURL = `${dittySettingsVars.restUrl}dittyeditor/v1/saveSettings`;
  const apiData = {
    security: dittySettingsVars.security,
    userId: dittySettingsVars.userId,
    settings: updatedSettings,
  };
  return axios.post(apiURL, { apiData }).then((res) => {
    onComplete(res.data);
  });
}

export function getRenderedItems(items, layouts, onComplete) {
  const apiURL = `${dittyEditorVars.restUrl}dittyeditor/v1/displayItems`;
  const apiData = {
    security: dittyEditorVars.security,
    userId: dittyEditorVars.userId,
    items: items,
    layouts: layouts,
  };
  return axios.post(apiURL, { apiData }).then((res) => {
    onComplete && onComplete(res.data);
  });
}

export function phpItemMods(item, hook = false, onComplete) {
  const apiURL = `${dittyEditorVars.restUrl}dittyeditor/v1/phpItemMods`;
  const apiData = {
    security: dittyEditorVars.security,
    userId: dittyEditorVars.userId,
    item: item,
    hook: hook,
  };
  return axios.post(apiURL, { apiData }).then((res) => {
    onComplete && onComplete(res.data);
  });
}

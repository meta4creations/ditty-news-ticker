import axios from "axios";

export function saveDitty(data, onComplete) {
  console.log("data", data);
  const apiURL = `${dittyEditorVars.restUrl}dittyeditor/v1/save`;
  const apiData = {
    security: dittyEditorVars.security,
    userId: dittyEditorVars.userId,
    ...data,
  };
  return axios.post(apiURL, { apiData }).then((res) => {
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

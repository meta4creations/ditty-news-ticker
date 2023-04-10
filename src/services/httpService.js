import axios from "axios";

// export const getDittyData = (dittyId) => {
//   const apiEndpoint = `${dittyEditorVars.siteUrl}/wp-json/dittyeditor/v1`;

//   const apiURL = `${apiEndpoint}/${dittyId}`;
//   const apiData = {
//     security: dittyEditorVars.security,
//   };
//   axios.post(apiURL, { apiData }).then((res) => {
//   });
// };

export function saveDitty(data, onComplete) {
  const apiEndpoint = `${dittyEditorVars.siteUrl}/wp-json/dittyeditor/v1`;

  const apiURL = `${apiEndpoint}/save`;
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
  const apiEndpoint = `${dittyEditorVars.siteUrl}/wp-json/dittyeditor/v1`;

  const apiURL = `${apiEndpoint}/saveDisplay`;
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
  const apiEndpoint = `${dittyEditorVars.siteUrl}/wp-json/dittyeditor/v1`;

  const apiURL = `${apiEndpoint}/saveLayout`;
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
  const apiEndpoint = `${dittySettingsVars.siteUrl}/wp-json/dittyeditor/v1`;

  const apiURL = `${apiEndpoint}/saveSettings`;
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
  const apiEndpoint = `${dittyEditorVars.siteUrl}/wp-json/dittyeditor/v1`;

  const apiURL = `${apiEndpoint}/displayItems`;
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
  const apiEndpoint = `${dittyEditorVars.siteUrl}/wp-json/dittyeditor/v1`;

  const apiURL = `${apiEndpoint}/phpItemMods`;
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

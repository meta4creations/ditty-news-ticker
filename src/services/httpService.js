import axios from "axios";
import { toast } from "react-toastify";

const apiEndpoint = `${dittyEditorVars.siteUrl}/wp-json/dittyeditor/v1`;

export const getDittyData = (dittyId) => {
  const apiURL = `${apiEndpoint}/${dittyId}`;
  const apiData = {
    security: dittyEditorVars.security,
  };
  axios.post(apiURL, { apiData }).then((res) => {
    //console.log("res", res);
    //console.log("data", res.data);
  });
};

export function saveDitty(data, onComplete) {
  console.log("data", data);
  const apiURL = `${apiEndpoint}/save`;
  const apiData = {
    security: dittyEditorVars.security,
    userId: dittyEditorVars.userId,
    ...data,
  };
  return axios.post(apiURL, { apiData }).then((res) => {
    console.log("res.data", res.data);
    onComplete(res.data);
  });
}

export function saveDisplay(data, onComplete) {
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

export function getRenderedItems(items, layouts, onComplete) {
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

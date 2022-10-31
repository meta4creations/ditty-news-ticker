import axios from "axios";
import { toast } from "react-toastify";

const apiEndpoint = `${dittyEditorVars.siteUrl}/wp-json/dittyeditor/v1`;

export const getDittyData = (dittyId) => {
  const apiURL = `${apiEndpoint}/${dittyId}`;
  const apiData = {
    security: dittyEditorVars.security,
  };
  axios.post(apiURL, { apiData }).then((res) => {
    console.log("res", res);
    console.log("data", res.data);
  });
};

export function saveDitty(
  id,
  items,
  deletedItems,
  display = false,
  settings = false
) {
  const apiURL = `${apiEndpoint}/save`;

  console.log("display", display);
  console.log("settings", settings);
  const apiData = {
    security: dittyEditorVars.security,
    id: id,
    items: items,
    deletedItems: deletedItems,
    display: display,
    settings: settings,
  };
  axios.post(apiURL, { apiData }).then((res) => {
    console.log("res", res);
    console.log("data", res.data);
  });

  // const apiURL = `${apiEndpoint}/save`;
  // console.log("apiURL", apiURL);
  // const apiData = {
  //   security: dittyEditorVars.security,
  //   id: id,
  //   items: items,
  //   deletedItems: deletedItems,
  //   display: display,
  // };
  // axios.post(apiURL, { apiData }).then((res) => {
  //   console.log("res", res);
  //   console.log("data", res.data);
  // });
}

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

export function saveDitty(data) {
  const apiURL = `${apiEndpoint}/save`;

  const apiData = {
    security: dittyEditorVars.security,
    userId: dittyEditorVars.userId,
    ...data,
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

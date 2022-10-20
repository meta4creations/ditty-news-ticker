import axios from "axios";
import { toast } from "react-toastify";

export const getDittyData = (dittyId) => {
  const apiURL = `${dittyEditorVars.siteUrl}/wp-json/ditty/v1/ditty/${dittyId}`;
  const apiData = {
    security: dittyEditorVars.security,
  };
  axios.post(apiURL, { apiData }).then((res) => {
    console.log("res", res);
    console.log("data", res.data);
  });
};

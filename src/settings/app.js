import { __ } from "@wordpress/i18n";
import AdminBar from "../components/AdminBar";
//import FooterBar from "./FooterBar";

import { ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export default () => {
  return (
    <>
      <AdminBar title={__("Ditty Settings", "ditty-news-ticker")} />
      <div id="ditty-settings">Ditty Settings!</div>
      <ToastContainer />
    </>
  );
};

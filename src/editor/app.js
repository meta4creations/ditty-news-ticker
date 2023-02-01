import AdminBar from "./AdminBar";
import FooterBar from "./FooterBar";
import Preview from "./Preview";
import Editor from "./Editor";

import { ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export default () => {
  return (
    <>
      <AdminBar />
      <div id="ditty-editor">
        <Preview />
        <Editor />
      </div>
      <FooterBar />
      <ToastContainer />
    </>
  );
};

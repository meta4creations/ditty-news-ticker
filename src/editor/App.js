import { __ } from "@wordpress/i18n";
import AdminBar from "./AdminBar";
import FooterBar from "./FooterBar";
import Preview from "./Preview";
import Editor from "./Editor";

export default () => {
  return (
    <>
      <AdminBar />
      <div id="ditty-editor">
        <Preview />
        <Editor />
      </div>
      <FooterBar />
    </>
  );
};

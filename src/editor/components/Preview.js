import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { EditorContext } from "../context";
import { getDittyData } from "../../services/httpService";

const Preview = () => {
  const { id, dittyRender } = useContext(EditorContext);
  return (
    <div
      id="ditty-editor__preview"
      dangerouslySetInnerHTML={{ __html: dittyRender }}
    ></div>
  );

  // var url = new URL(dittyEditorVars.siteUrl);
  // url.searchParams.set("ditty_edit", true);
  // url.searchParams.set("ditty_edit_id", id);
  // url.searchParams.set("dittyDev", true);

  // return (
  //   <iframe
  //     id="ditty-editor__preview"
  //     title={__("Ditty Preview", "ditty-news-ticker")}
  //     src={url.toString()}
  //   ></iframe>
  // );
};
export default Preview;

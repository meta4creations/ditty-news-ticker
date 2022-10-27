import { __ } from "@wordpress/i18n";
import { useContext, useEffect } from "@wordpress/element";
import { EditorContext } from "../context";
import Ditty from "./Ditty";

const Preview = () => {
  const { id, dittyRender } = useContext(EditorContext);

  // useEffect(() => {
  //   console.log("useEffect", id);
  //   console.log(window.ditty);
  // }, []);

  return (
    <div id="ditty-editor__preview">
      <Ditty id={id} />
    </div>
  );
};
export default Preview;

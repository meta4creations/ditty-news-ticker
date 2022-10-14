import { __ } from "@wordpress/i18n";
import Tabs from "./Tabs";
import Panels from "./panels";

export default () => {
  return (
    <div className="ditty-editor__contents">
      <Tabs />
      <Panels />
    </div>
  );
};

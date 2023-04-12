import { createRoot } from "@wordpress/element";
import App from "./layoutEditor/app";
import "./assets/css/editor.scss";

if (document.getElementById("ditty-layout-editor__wrapper")) {
  const root = createRoot(
    document.getElementById("ditty-layout-editor__wrapper")
  );
  root.render(<App />);
}

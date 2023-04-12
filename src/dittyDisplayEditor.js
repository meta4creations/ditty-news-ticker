import { createRoot } from "@wordpress/element";
import App from "./displayEditor/app";
import "./assets/css/editor.scss";

if (document.getElementById("ditty-display-editor__wrapper")) {
  const root = createRoot(
    document.getElementById("ditty-display-editor__wrapper")
  );
  root.render(<App />);
}

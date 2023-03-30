const { createRoot } = wp.element; //we are using wp.element here!
import App from "./layoutEditor/app";
import "./assets/css/editor.scss";

if (document.getElementById("ditty-layout-editor__wrapper")) {
  const root = createRoot(
    document.getElementById("ditty-layout-editor__wrapper")
  );
  root.render(<App />);
}

const { render } = wp.element; //we are using wp.element here!
import App from "./displayEditor/app";
import "./assets/css/editor.scss";

if (document.getElementById("ditty-display-editor__wrapper")) {
  if (document.getElementById("ditty-display-editor__wrapper")) {
    render(<App />, document.getElementById("ditty-display-editor__wrapper"));
  }
}

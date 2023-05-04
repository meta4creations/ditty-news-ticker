const { render } = wp.element; //we are using wp.element here!
import App from "./layoutEditor/app";
import "./assets/css/editor.scss";

if (document.getElementById("ditty-layout-editor__wrapper")) {
  render(<App />, document.getElementById("ditty-layout-editor__wrapper"));
}

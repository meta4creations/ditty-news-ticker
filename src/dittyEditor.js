const { render } = wp.element; //we are using wp.element here!
import { EditorProvider } from "./editor/context";
import App from "./editor/app";
import "./assets/css/editor.scss";

if (document.getElementById("ditty-editor__wrapper")) {
  render(
    <EditorProvider>
      <App />
    </EditorProvider>,
    document.getElementById("ditty-editor__wrapper")
  );
}

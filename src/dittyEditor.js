const { render } = wp.element; //we are using wp.element here!
import { EditorProvider } from "./editor/context";
import App from "./editor/app";
import "./editor/css/editor.scss";

if (document.getElementById("ditty-editor__wrapper")) {
  const $dittyEditorWrapper = document.getElementById("ditty-editor__wrapper");
  render(
    <EditorProvider data={$dittyEditorWrapper.dataset}>
      <App />
    </EditorProvider>,
    document.getElementById("ditty-editor__wrapper")
  );
}

const { render } = wp.element; //we are using wp.element here!
import { EditorProvider } from "./context";
import App from "./components/App";
import "./css/editor.scss";

if (document.getElementById("ditty-editor__wrapper")) {
  const $dittyEditorWrapper = document.getElementById("ditty-editor__wrapper");
  render(
    <EditorProvider data={$dittyEditorWrapper.dataset}>
      <App />
    </EditorProvider>,
    document.getElementById("ditty-editor__wrapper")
  );
}

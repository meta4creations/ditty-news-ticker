const { render } = wp.element; //we are using wp.element here!
import { EditorProvider } from "./context";
import Editor from "./components/Editor";
import "./css/editor.scss";

if (document.getElementById("ditty-editor")) {
  const $dittyEditor = document.getElementById("ditty-editor");
  render(
    <EditorProvider data={$dittyEditor.dataset}>
      <Editor />
    </EditorProvider>,
    document.getElementById("ditty-editor")
  );
}

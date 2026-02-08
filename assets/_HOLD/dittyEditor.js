const { createRoot, render } = wp.element; //we are using wp.element here!
import { EditorProvider } from "./editor/context";
import App from "./editor/app";
import "./assets/css/editor.scss";

const rootElement = document.getElementById("ditty-editor__wrapper");

if (rootElement) {
  if (createRoot) {
    createRoot(rootElement).render(
      <EditorProvider>
        <App />
      </EditorProvider>
    );
  } else {
    render(
      <EditorProvider>
        <App />
      </EditorProvider>,
      rootElement
    );
  }
}

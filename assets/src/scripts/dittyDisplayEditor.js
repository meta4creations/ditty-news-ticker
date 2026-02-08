const { createRoot, render } = wp.element; //we are using wp.element here!
import App from "./displayEditor/app";
import "../styles/editor.scss";

const rootElement = document.getElementById("ditty-display-editor__wrapper");

if (rootElement) {
  if (createRoot) {
    createRoot(rootElement).render(<App />);
  } else {
    render(<App />, rootElement);
  }
}

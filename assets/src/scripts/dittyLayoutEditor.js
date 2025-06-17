const { createRoot, render } = wp.element; //we are using wp.element here!
import App from "./layoutEditor/app";
import "../styles/editor.scss";

const rootElement = document.getElementById("ditty-layout-editor__wrapper");

if (rootElement) {
  if (createRoot) {
    createRoot(rootElement).render(<App />);
  } else {
    render(<App />, rootElement);
  }
}

const { createRoot, render } = wp.element; //we are using wp.element here!
import App from "./settings/app";
import "./assets/css/settings.scss";

const rootElement = document.getElementById("ditty-settings__wrapper");

if (rootElement) {
  if (createRoot) {
    createRoot(rootElement).render(<App />);
  } else {
    render(<App />, rootElement);
  }
}

const { createRoot } = wp.element; //we are using wp.element here!
import App from "./settings/app";
import "./assets/css/settings.scss";

if (document.getElementById("ditty-settings__wrapper")) {
  const root = createRoot(document.getElementById("ditty-settings__wrapper"));
  root.render(<App />);
}

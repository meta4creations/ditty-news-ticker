const { render } = wp.element; //we are using wp.element here!
import App from "./settings/app";
import "./settings/css/settings.scss";

if (document.getElementById("ditty-settings__wrapper")) {
  render(<App />, document.getElementById("ditty-settings__wrapper"));
}

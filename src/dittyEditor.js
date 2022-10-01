const { render } = wp.element; //we are using wp.element here!
import Editor from "./editor/editor";
import "./editor/css/editor.scss";

if (document.getElementById("ditty-editor")) {
  const $dittyEditor = document.getElementById("ditty-editor");
  const id = $dittyEditor.dataset.id;
  const title = $dittyEditor.dataset.title;
  const items = $dittyEditor.dataset.items
    ? JSON.parse($dittyEditor.dataset.items)
    : [];
  render(
    <Editor id={id} title={title} items={items} />,
    document.getElementById("ditty-editor")
  );
}

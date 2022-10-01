window.dittyHooks.addFilter(
  "dittyEditorItemElements",
  "dittyEditor",
  dittyEditorItemElements
);

function dittyEditorItemElements(elements) {
  elements.push({
    id: "clone",
    content: <i className="fas fa-clone"></i>,
  });
  return elements;
}

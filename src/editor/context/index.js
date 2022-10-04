import { useState } from "@wordpress/element";

export const EditorContext = React.createContext();
EditorContext.displayName = "EditorContext";

export const EditorProvider = (props) => {
  const { data } = props;
  const initialTitle = data.title ? data.title : "";
  const initialItems = data.items ? JSON.parse(data.items) : [];
  const initialDisplay = data.display ? data.display : 0;

  const id = data.id;
  const [title, setTitle] = useState(initialTitle);
  const [items, setItems] = useState(initialItems);
  const [currentDisplay, setCurrentDisplay] = useState(initialDisplay);
  const [displays, setDisplays] = useState(dittyEditorVars.displays);
  const [layouts, setLayouts] = useState(dittyEditorVars.layouts);

  const [currentPanel, setCurrentPanel] = useState("");

  function handleSetCurrentPanel(panel) {
    setCurrentPanel(panel);
  }

  return (
    <EditorContext.Provider
      value={{
        id,
        title,
        items,
        displays,
        layouts,
        currentPanel,
        currentDisplay,
        actions: { setCurrentPanel: handleSetCurrentPanel },
      }}
    >
      {props.children}
    </EditorContext.Provider>
  );
};

export const EditorConsumer = EditorContext.Consumer;

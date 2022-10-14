import { Component } from "@wordpress/element";

export const EditorContext = React.createContext();
EditorContext.displayName = "EditorContext";

export class EditorProvider extends Component {
  data = this.props.data;
  initialTitle = this.data.title ? this.data.title : "";
  initialItems = this.data.items ? JSON.parse(this.data.items) : [];
  initialDisplay = this.data.display ? this.data.display : 0;
  id = this.data.id;

  state = {
    title: this.initialTitle,
    items: this.initialItems,
    displays: dittyEditorVars.displays,
    layouts: dittyEditorVars.layouts,
    currentDisplay: this.initialDisplay,
    currentPanel: "items",
  };

  handleUpdateItems = (updatedItems) => {
    const orderedItems = updatedItems.map((item, index) => {
      item.item_index = index.toString();
      return item;
    });

    this.setState({ items: orderedItems });
  };

  handleSetCurrentPanel = (panel) => {
    this.setState({ currentPanel: panel });
  };

  render() {
    return (
      <EditorContext.Provider
        value={{
          id: this.id,
          title: this.state.title,
          items: this.state.items,
          displays: this.state.displays,
          layouts: this.state.layouts,
          currentPanel: this.state.currentPanel,
          currentDisplay: this.state.currentDisplay,
          actions: {
            setCurrentPanel: this.handleSetCurrentPanel,
            updateItems: this.handleUpdateItems,
          },
        }}
      >
        {this.props.children}
      </EditorContext.Provider>
    );
  }
}

export const EditorConsumer = EditorContext.Consumer;

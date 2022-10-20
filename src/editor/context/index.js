import { __ } from "@wordpress/i18n";
import { Component } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faTabletScreen } from "@fortawesome/pro-light-svg-icons";
import _ from "lodash";
import {
  getItemTypes,
  getItemTypeIcon,
  getItemTypeFields,
} from "../utils/ItemTypes";

export const EditorContext = React.createContext();
EditorContext.displayName = "EditorContext";

export class EditorProvider extends Component {
  data = this.props.data;
  dittyRender = this.data.render ? this.data.render : "";
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

  getDisplayTypeIcon = (display) => {
    return window.dittyHooks.applyFilters(
      "dittyEditorDisplayIcon",
      <FontAwesomeIcon icon={faTabletScreen} />,
      display
    );
  };

  handleUpdateItems = (updatedItems) => {
    const orderedItems = updatedItems.map((item, index) => {
      item.item_index = index.toString();
      return item;
    });

    this.setState({ items: orderedItems });
  };

  handleUpdateItem = (updatedItem) => {
    const updatedItems = this.state.items.map((item) => {
      return updatedItem.item_id === item.item_id ? updatedItem : item;
    });
    this.setState({ items: updatedItems });
  };

  handleSetCurrentPanel = (panel) => {
    this.setState({ currentPanel: panel });
  };

  render() {
    return (
      <EditorContext.Provider
        value={{
          dittyRender: this.dittyRender,
          id: this.id,
          title: this.state.title,
          itemTypes: getItemTypes(),
          items: this.state.items,
          displays: this.state.displays,
          layouts: this.state.layouts,
          currentPanel: this.state.currentPanel,
          currentDisplay: this.state.currentDisplay,
          helpers: {
            itemTypeIcon: getItemTypeIcon,
            itemTypeFields: getItemTypeFields,
            displayTypeIcon: this.getDisplayTypeIcon,
          },
          actions: {
            setCurrentPanel: this.handleSetCurrentPanel,
            updateItems: this.handleUpdateItems,
            updateItem: this.handleUpdateItem,
          },
        }}
      >
        {this.props.children}
      </EditorContext.Provider>
    );
  }
}

export const EditorConsumer = EditorContext.Consumer;

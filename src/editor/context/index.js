import { __ } from "@wordpress/i18n";
import { Component } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faPenToSquare,
  faPencil,
  faTabletScreen,
} from "@fortawesome/pro-light-svg-icons";
import { faWordpress } from "@fortawesome/free-brands-svg-icons";

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

  itemTypes = window.dittyHooks.applyFilters("dittyItemTypes", [
    {
      id: "default",
      icon: <FontAwesomeIcon icon={faPencil} />,
      label: __("Default", "ditty-news-ticker"),
      description: __("Manually add HTML to the item.", "ditty-news-ticker"),
    },
    {
      id: "wp_editor",
      icon: <FontAwesomeIcon icon={faPenToSquare} />,
      label: __("WP Editor", "ditty-news-ticker"),
      description: __(
        "Manually add wp editor content to the item.",
        "ditty-news-ticker"
      ),
    },
    {
      id: "posts_feed",
      icon: <FontAwesomeIcon icon={faWordpress} />,
      label: __("WP Posts Feed (Lite)", "ditty-news-ticker"),
      description: __("Add a WP Posts feed.", "ditty-news-ticker"),
    },
  ]);

  getItemTypeIcon = (item) => {
    const itemType = this.itemTypes.filter(
      (itemType) => itemType.id === item.item_type
    );
    return itemType.length ? (
      itemType[0].icon
    ) : (
      <FontAwesomeIcon icon={faPencil} />
    );
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
          id: this.id,
          title: this.state.title,
          itemTypes: this.itemTypes,
          items: this.state.items,
          displays: this.state.displays,
          layouts: this.state.layouts,
          currentPanel: this.state.currentPanel,
          currentDisplay: this.state.currentDisplay,
          helpers: {
            itemTypeIcon: this.getItemTypeIcon,
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

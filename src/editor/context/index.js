import { __ } from "@wordpress/i18n";
import { Component } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faTabletScreen } from "@fortawesome/pro-light-svg-icons";
import _ from "lodash";
import {
  getItemTypes,
  getItemTypeIcon,
  getItemTypeFields,
} from "../utils/itemTypes";
import {
  getDisplayTypes,
  getDisplayTypeIcon,
  getDisplayTypeFields,
} from "../utils/displayTypes";
import { saveDitty } from "../../services/httpService";

export const EditorContext = React.createContext();
EditorContext.displayName = "EditorContext";

export class EditorProvider extends Component {
  data = this.props.data;
  //dittyRender = this.data.render ? this.data.render : "";
  initialTitle = this.data.title ? this.data.title : "";
  initialItems = this.data.items ? JSON.parse(this.data.items) : [];
  initialDisplays = dittyEditorVars.displays ? dittyEditorVars.displays : [];
  initialLayouts = dittyEditorVars.layouts ? dittyEditorVars.layouts : [];
  initialDisplay = this.data.display ? this.data.display : 0;
  id = this.data.id;

  state = {
    title: this.initialTitle,
    items: [...this.initialItems],
    displays: [...this.initialDisplays],
    layouts: [...this.initialLayouts],
    currentDisplay: this.initialDisplay,
    currentPanel: "items",
  };

  /**
   * Update all items
   * @param {object} updatedItems
   */
  handleUpdateItems = (updatedItems) => {
    const orderedItems = updatedItems.map((item, index) => {
      item.item_index = index.toString();
      return item;
    });

    this.setState({ items: orderedItems });
  };

  /**
   * Update a single item
   * @param {object} updatedItem
   */
  handleUpdateItem = (updatedItem) => {
    const updatedItems = this.state.items.map((item) => {
      return updatedItem.item_id === item.item_id ? updatedItem : item;
    });
    this.setState({ items: updatedItems });
  };

  /**
   * Update a single display
   * @param {object} updatedDisplay
   */
  handleUpdateDisplay = (updatedDisplay) => {
    const updatedDisplays = this.state.displays.map((display) => {
      return updatedDisplay.id === display.id ? updatedDisplay : display;
    });
    this.setState({ displays: updatedDisplays });
  };

  /**
   * Set the current display
   * @param {string} panel
   */
  handleSetCurrentPanel = (panel) => {
    this.setState({ currentPanel: panel });
  };

  /**
   * Set the current display
   * @param {object} display
   */
  handleSetCurrentDisplay = (display) => {
    this.setState({ currentDisplay: display });
  };

  /**
   * Save the ditty
   */
  handleSaveDitty = async () => {
    const deletedItems = this.initialItems.filter((initialItem) => {
      const existingItems = this.state.items.some((item) => {
        return item.item_id === initialItem.item_id;
      });
      if (!existingItems) {
        return true;
      }
    });
    // console.log("initialItems", this.initialItems);

    // console.log("deletedItems", deletedItems);
    // console.log("display", this.state.currentDisplay);

    // Save the initialItems
    const initialItems = [...this.initialItems];
    this.initialItems = [...this.state.items];

    try {
      await saveDitty(
        this.id,
        this.state.items,
        deletedItems,
        this.state.currentDisplay
      );
    } catch (ex) {
      console.log(ex);
      if (ex.response && ex.response.status === 404) {
      }
      this.initialItems = initialItems;
    }
  };

  render() {
    return (
      <EditorContext.Provider
        value={{
          //dittyRender: this.dittyRender,
          id: this.id,
          title: this.state.title,
          itemTypes: getItemTypes(),
          items: this.state.items,
          displayTypes: getDisplayTypes(),
          displays: this.state.displays,
          layouts: this.state.layouts,
          currentPanel: this.state.currentPanel,
          currentDisplay: this.state.currentDisplay,
          helpers: {
            itemTypeIcon: getItemTypeIcon,
            itemTypeFields: getItemTypeFields,
            displayTypeIcon: getDisplayTypeIcon,
            displayTypeFields: getDisplayTypeFields,
          },
          actions: {
            setCurrentPanel: this.handleSetCurrentPanel,
            setCurrentDisplay: this.handleSetCurrentDisplay,
            updateItems: this.handleUpdateItems,
            updateItem: this.handleUpdateItem,
            updateDisplay: this.handleUpdateDisplay,
            saveDitty: this.handleSaveDitty,
          },
        }}
      >
        {this.props.children}
      </EditorContext.Provider>
    );
  }
}

export const EditorConsumer = EditorContext.Consumer;

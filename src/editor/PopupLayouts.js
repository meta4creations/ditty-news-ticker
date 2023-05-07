import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPaintbrushPencil } from "@fortawesome/pro-light-svg-icons";
import { getDisplayItems, replaceDisplayItems } from "../services/dittyService";
import {
  getItemTypeObject,
  getItemLabel,
  getItemTypePreviewIcon,
  getLayoutVariationObject,
} from "../utils/itemTypes";
import {
  getLayoutObject,
  getDefaultLayout,
  compileLayoutStyle,
} from "../utils/layouts";
import { Button, ButtonGroup, IconBlock, Popup } from "../components";
import PopupTemplateSave from "./PopupTemplateSave";
import PopupTemplateSelector from "./PopupTemplateSelector";
import PopupEditLayout from "./PopupEditLayout";

const PopupLayouts = ({
  item,
  editor,
  layouts,
  submitLabel = __("Update Item", "ditty-news-ticker"),
  onChange,
  onClose,
  onUpdate,
  onTemplateSave,
  level,
}) => {
  const [editItem, setEditItem] = useState(_.cloneDeep(item));
  const [updateKeys, setUpdateKeys] = useState([]);
  const [selectedVariation, setSelectedVariation] = useState();
  const [variationTemplates, setVariationTemplates] = useState({});
  const [popupStatus, setPopupStatus] = useState(false);
  const [hasLiveEditPreview, setHasLiveEditPreview] = useState(false);

  const itemTypeObject = getItemTypeObject(editItem);

  const addItemUpdate = (updatedItem, key) => {
    setEditItem(updatedItem);

    const updatedUpdateKeys = [...updateKeys];
    if (!updatedUpdateKeys.includes(key)) {
      updatedUpdateKeys.push(key);
      setUpdateKeys(updatedUpdateKeys);
    }
    onChange && onChange(updatedItem);
  };

  const updateVariationTemplates = (variation, template) => {
    const updatedTemplates = { ...variationTemplates };
    updatedTemplates[variation] = template;
    setVariationTemplates(updatedTemplates);
  };

  const getLayoutVariations = () => {
    const layoutVariations = editItem.layout_value;
    if (
      !layoutVariations ||
      typeof layoutVariations !== "object" ||
      !Object.keys(layoutVariations).length
    ) {
      return { default: getDefaultLayout() };
    }
    return layoutVariations;
  };

  const getVariationLayoutObject = (variation) => {
    const layoutVariations = getLayoutVariations();
    for (const variationId in layoutVariations) {
      if (variationId === variation) {
        return getLayoutObject(layoutVariations[variationId], layouts);
      }
    }
  };

  const previewLayout = (variation, layout) => {
    const updatedLayoutVariations = { ...editItem.layout_value };
    updatedLayoutVariations[variation] = layout.id ? String(layout.id) : layout;

    const updatedEditItem = { ...editItem };
    updatedEditItem.layout_value = updatedLayoutVariations;
    addItemUpdate(updatedEditItem, "layout_value");
    //onChange(updatedEditItem);
  };

  const setVariationLayout = (variation, layout, preview = false) => {
    const updatedLayoutVariations = { ...editItem.layout_value };
    updatedLayoutVariations[variation] = layout.id ? String(layout.id) : layout;

    const updatedEditItem = { ...editItem };
    updatedEditItem.layout_value = updatedLayoutVariations;
    setEditItem(updatedEditItem);
    if (preview) {
      //onChange(updatedEditItem);
      addItemUpdate(updatedEditItem, "layout_value");
    }
  };

  /**
   * Render a popup component
   * @returns Popup component
   */
  const renderPopup = () => {
    switch (popupStatus) {
      case "layoutTemplateSave":
        const currentLayout = getVariationLayoutObject(selectedVariation);
        const variationTemplate = variationTemplates[selectedVariation]
          ? variationTemplates[selectedVariation]
          : {};
        const templateToSave = { ...variationTemplate, ...currentLayout };
        return (
          <PopupTemplateSave
            level="2"
            templateType="layout"
            currentTemplate={templateToSave}
            templates={layouts}
            headerIcon={<FontAwesomeIcon icon={faPaintbrushPencil} />}
            templateIcon={() => <FontAwesomeIcon icon={faPaintbrushPencil} />}
            saveData={(type, selectedTemplate, name, description) => {
              return "existing" === type
                ? {
                    layout: {
                      ...selectedTemplate,
                      html: currentLayout.html,
                      css: currentLayout.css,
                      updated: Date.now(),
                    },
                  }
                : {
                    title: name,
                    description: description,
                    status: "publish",
                    layout: { ...selectedTemplate },
                  };
            }}
            onClose={() => {
              setPopupStatus(false);
            }}
            onUpdate={(updatedTemplate) => {
              if (updatedTemplate.new) {
                updatedTemplate.id = updatedTemplate.new;
                delete updatedTemplate.new;
              }
              setPopupStatus(false);
              setVariationLayout(selectedVariation, updatedTemplate);
              onTemplateSave(updatedTemplate);
            }}
          />
        );
      case "layoutTemplateSelect":
        return (
          <PopupTemplateSelector
            level="2"
            currentTemplate={getVariationLayoutObject(selectedVariation)}
            templates={layouts}
            headerIcon={<FontAwesomeIcon icon={faPaintbrushPencil} />}
            templateIcon={() => <FontAwesomeIcon icon={faPaintbrushPencil} />}
            submitLabel={__("Use Layout", "ditty-news-ticker")}
            onChange={(selectedTemplate) => {
              previewLayout(selectedVariation, selectedTemplate);
            }}
            onClose={() => {
              setPopupStatus(false);
              onChange(editItem);
            }}
            onUpdate={(updatedTemplate) => {
              setPopupStatus(false);
              setVariationLayout(selectedVariation, updatedTemplate);
              updateVariationTemplates(selectedVariation, updatedTemplate);
            }}
          />
        );
      case "editLayout":
        const layoutObject = variationTemplates[selectedVariation];
        const customLayout = { html: layoutObject.html, css: layoutObject.css };
        return (
          <PopupEditLayout
            level="2"
            item={editItem}
            layout={customLayout}
            itemTypeObject={itemTypeObject}
            onClose={() => {
              setPopupStatus(false);

              // If display has been updated with changes, revert them
              if (hasLiveEditPreview) {
                const dittyEl = document.getElementById("ditty-editor__ditty");
                getDisplayItems(editItem, false, (data) => {
                  const updatedDisplayItems =
                    editor.helpers.replaceDisplayItems(data.display_items);
                  replaceDisplayItems(dittyEl, updatedDisplayItems);
                });
                setHasLiveEditPreview(false);
              }
            }}
            onChange={(updatedLayout, type) => {
              const updatedItem = { ...editItem };
              const updatedLayoutValue = { ...updatedItem.layout_value };
              updatedLayoutValue[selectedVariation] = updatedLayout;
              updatedItem.layout_value = updatedLayoutValue;

              // Update just the css
              // if (hasLiveEditPreview && "css" === type) {
              //   const selector = `${item.item_id}_${selectedVariation}`;
              //   console.log("selector", selector);
              //   compileLayoutStyle(updatedLayout.css, selector, (css) => {
              //     console.log("css", css);
              //     updateLayoutCss(css, selector);
              //   });

              //   // Get new display items
              // } else {
              const dittyEl = document.getElementById("ditty-editor__ditty");
              getDisplayItems(updatedItem, false, (data) => {
                const updatedDisplayItems = editor.helpers.replaceDisplayItems(
                  data.display_items
                );
                replaceDisplayItems(dittyEl, updatedDisplayItems);
              });
              //}

              setHasLiveEditPreview(true);
            }}
            onUpdate={(updatedLayout) => {
              setPopupStatus(false);
              setVariationLayout(selectedVariation, updatedLayout, true);
              setHasLiveEditPreview(false);
            }}
          />
        );
      default:
        return;
    }
  };

  const templateButtons = (variation) => {
    return (
      <ButtonGroup gap="3px">
        <Button
          size="small"
          onClick={() => {
            setSelectedVariation(variation);
            setPopupStatus("layoutTemplateSelect");
          }}
        >
          {__("Change Template", "ditty-news-ticker")}
        </Button>
        <Button
          size="small"
          onClick={() => {
            setSelectedVariation(variation);
            updateVariationTemplates(
              variation,
              getVariationLayoutObject(variation)
            );

            setPopupStatus("editLayout");
          }}
        >
          {__("Customize", "ditty-news-ticker")}
        </Button>
      </ButtonGroup>
    );
  };

  const customButtons = (variation) => {
    return (
      <>
        <Button
          className=""
          style={{ marginBottom: "10px", width: "100%" }}
          onClick={() => {
            setSelectedVariation(variation);
            updateVariationTemplates(
              variation,
              getVariationLayoutObject(variation)
            );
            setPopupStatus("editLayout");
          }}
        >
          {__("Customize", "ditty-news-ticker")}
        </Button>
        <ButtonGroup gap="3px">
          <Button
            size="small"
            onClick={() => {
              setSelectedVariation(variation);
              setPopupStatus("layoutTemplateSelect");
            }}
          >
            {__("Use Template", "ditty-news-ticker")}
          </Button>
          <Button
            size="small"
            onClick={() => {
              setSelectedVariation(variation);
              setPopupStatus("layoutTemplateSave");
            }}
          >
            {__("Save as Template", "ditty-news-ticker")}
          </Button>
        </ButtonGroup>
      </>
    );
  };

  const renderVariationsList = () => {
    const layoutBlocks = [];
    const layoutVariations = getLayoutVariations();
    for (const variation in layoutVariations) {
      const layout = layoutVariations[variation];
      const layoutObject = getLayoutObject(layout, layouts);
      const variationObject = getLayoutVariationObject(
        itemTypeObject,
        variation
      );
      layoutBlocks.push(
        <div key={variation} className="editLayout__variation">
          <IconBlock style={{ marginBottom: "10px" }}>
            <IconBlock
              icon={variationObject.icon}
              className="ditty-layout-variation--heading"
            >
              <div className="ditty-icon-block--heading__title">
                <h3>{variationObject.label && variationObject.label}</h3>
              </div>
              <p>
                {variationObject.description && variationObject.description}
              </p>
            </IconBlock>
            {layoutObject.id && (
              <>
                <h2>{layoutObject.title}</h2>
                <p>{layoutObject.description}</p>
              </>
            )}
          </IconBlock>
          {layoutObject.id
            ? templateButtons(variation)
            : customButtons(variation)}
        </div>
      );
    }
    return layoutBlocks;
  };

  const renderPopupHeader = () => {
    return (
      <IconBlock
        icon={getItemTypePreviewIcon(editItem)}
        className="ditty-icon-block--heading"
      >
        <div className="ditty-icon-block--heading__title">
          <h2>{__("Layout Settings", "ditty-news-ticker")}</h2>
        </div>
        <p>{getItemLabel(editItem)}</p>
      </IconBlock>
    );
  };

  return (
    <>
      <Popup
        id="layouts"
        submitLabel={submitLabel}
        header={renderPopupHeader()}
        onClose={() => {
          onClose(editItem);
        }}
        onSubmit={() => {
          onUpdate(editItem, updateKeys);
        }}
        level={level}
      >
        <div className="ditty-field-list__heading">
          <h3 className="ditty-field-list__heading__title">
            {__("Layout Variations", "ditty-news-ticker")}
          </h3>
          <p className="ditty-field-list__heading__description">
            {__(
              "Configure the layouts to use for the item.",
              "ditty-news-ticker"
            )}
          </p>
        </div>
        <div className="editLayout__variations">{renderVariationsList()}</div>
      </Popup>
      {renderPopup()}
    </>
  );
};
export default PopupLayouts;

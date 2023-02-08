import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPaintbrushPencil } from "@fortawesome/pro-light-svg-icons";
import { getItemTypeObject, getItemLabel } from "../utils/itemTypes";
import { getLayoutObject, getDefaultLayout } from "../utils/layouts";
import { Button, ButtonGroup, IconBlock, Popup } from "../components";
import PopupTemplateSave from "./PopupTemplateSave";
import PopupTemplateSelector from "./PopupTemplateSelector";
import PopupEditLayout from "./PopupEditLayout";

const PopupEditLayoutVariations = ({
  item,
  layouts,
  submitLabel = __("Update Item", "ditty-news-ticker"),
  onChange,
  onClose,
  onUpdate,
  onTemplateSave,
  level,
}) => {
  const [editItem, setEditItem] = useState(item);
  const [selectedVariation, setSelectedVariation] = useState();
  const [variationTemplates, setVariationTemplates] = useState({});
  const [popupStatus, setPopupStatus] = useState(false);
  console.log("editItem", editItem);

  const itemTypeObject = getItemTypeObject(editItem);

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
    onChange(updatedEditItem);
  };

  const setVariationLayout = (variation, layout, preview = false) => {
    const updatedLayoutVariations = { ...editItem.layout_value };
    updatedLayoutVariations[variation] = layout.id ? String(layout.id) : layout;

    const updatedEditItem = { ...editItem };
    updatedEditItem.layout_value = updatedLayoutVariations;
    setEditItem(updatedEditItem);
    if (preview) {
      onChange(updatedEditItem);
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
        const templateToSave = { ...variationTemplate, currentLayout };
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
                    layout: selectedTemplate,
                  };
            }}
            onClose={() => {
              setPopupStatus(false);
            }}
            onUpdate={(updatedTemplate) => {
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
        console.log("variationTemplates", variationTemplates);
        console.log("selectedVariation", selectedVariation);
        const layoutObject = variationTemplates[selectedVariation];
        console.log("layoutObject", layoutObject);
        const customLayout = { html: layoutObject.html, css: layoutObject.css };
        return (
          <PopupEditLayout
            level="2"
            layout={customLayout}
            itemTypeObject={itemTypeObject}
            onClose={() => {
              setPopupStatus(false);
            }}
            onUpdate={(updatedLayout) => {
              setPopupStatus(false);
              setVariationLayout(selectedVariation, updatedLayout, true);
            }}
          />
        );
      default:
        return;
    }
  };

  const templateButtons = (variation, layoutObject) => {
    return (
      <>
        <Button
          onClick={() => {
            setSelectedVariation(variation);
            setPopupStatus("layoutTemplateSelect");
          }}
        >
          {__("Change Template", "ditty-news-ticker")}
        </Button>
        <Button
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
      </>
    );
  };

  const customButtons = (variation) => {
    return (
      <>
        <Button
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
        <Button
          onClick={() => {
            setSelectedVariation(variation);
            setPopupStatus("layoutTemplateSelect");
          }}
        >
          {__("Use Template", "ditty-news-ticker")}
        </Button>
        <Button
          onClick={() => {
            setSelectedVariation(variation);
            setPopupStatus("layoutTemplateSave");
          }}
        >
          {__("Save as Template", "ditty-news-ticker")}
        </Button>
      </>
    );
  };

  const renderVariationsList = () => {
    const layoutBlocks = [];
    const layoutVariations = getLayoutVariations();
    for (const variation in layoutVariations) {
      const layout = layoutVariations[variation];
      const layoutObject = getLayoutObject(layout, layouts);
      layoutBlocks.push(
        <div key={variation} className="editLayout__variation">
          <IconBlock style={{ marginBottom: "10px" }}>
            {layoutObject.id ? (
              <>
                <h3>{`${variation}: ${layoutObject.title}`} </h3>
                <p>
                  {__("Post ID", "ditty-news-ticker")} :{" "}
                  <a href={layoutObject.edit_url}>{layoutObject.id}</a>
                </p>
                <p>{layoutObject.description}</p>
              </>
            ) : (
              <>
                <h3>
                  {`${variation}: ${__("Custom Layout", "ditty-news-ticker")}`}{" "}
                </h3>
              </>
            )}
          </IconBlock>
          <ButtonGroup className="ditty-displayEdit__links">
            {layoutObject.id
              ? templateButtons(variation, layoutObject)
              : customButtons(variation, layoutObject)}
          </ButtonGroup>
        </div>
      );
    }
    return layoutBlocks;
  };

  return (
    <>
      <Popup
        id="editLayoutVariations"
        submitLabel={submitLabel}
        header={
          <>
            <IconBlock
              icon={itemTypeObject && itemTypeObject.icon}
              className="ditty-icon-block--heading"
            >
              <div className="ditty-icon-block--heading__title">
                <h2>{itemTypeObject && itemTypeObject.label}</h2>
              </div>
              <p>{getItemLabel(editItem)}</p>
            </IconBlock>
          </>
        }
        onClose={() => {
          onClose(editItem);
        }}
        onSubmit={() => {
          onUpdate(editItem);
        }}
        level={level}
      >
        <div className="editLayout__variations">{renderVariationsList()}</div>
      </Popup>
      {renderPopup()}
    </>
  );
};
export default PopupEditLayoutVariations;

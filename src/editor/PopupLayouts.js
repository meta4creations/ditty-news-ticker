import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import _ from "lodash";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faPaintbrushPencil,
  faTableLayout,
  faGear,
} from "@fortawesome/pro-light-svg-icons";
import {
  getItemTypeObject,
  getItemLabel,
  getLayoutVariationObject,
} from "../utils/itemTypes";
import {
  getLayoutObject,
  getDefaultLayout,
  getTagFields,
} from "../utils/layouts";
import { Button, ButtonGroup, IconBlock, Popup, Tabs } from "../components";
import { FieldList } from "../fields";
import PopupTemplateSave from "./PopupTemplateSave";
import PopupTemplateSelector from "./PopupTemplateSelector";
import PopupEditLayout from "./PopupEditLayout";

const PopupLayouts = ({
  item,
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

  const itemTypeObject = getItemTypeObject(editItem);
  const [currentTabId, setCurrentTabId] = useState("layouts");

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
        const layoutObject = variationTemplates[selectedVariation];
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
      </>
    );
  };

  const customButtons = (variation) => {
    return (
      <>
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
            {layoutObject.id ? (
              <>
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
                <h2>{layoutObject.title}</h2>
                <p>{layoutObject.description}</p>
                {/* <p>
                  {__("Post ID", "ditty-news-ticker")} :{" "}
                  <a href={layoutObject.edit_url}>{layoutObject.id}</a>
                </p> */}
              </>
            ) : (
              <>
                <h3>
                  {`${variation}: ${__("Custom Layout", "ditty-news-ticker")}`}{" "}
                </h3>
              </>
            )}
          </IconBlock>
          <ButtonGroup className="ditty-displayEdit__links" gap="3px">
            {layoutObject.id
              ? templateButtons(variation, layoutObject)
              : customButtons(variation, layoutObject)}
          </ButtonGroup>
        </div>
      );
    }
    return layoutBlocks;
  };

  const renderPopupHeader = () => {
    return (
      <>
        <IconBlock
          icon={<FontAwesomeIcon icon={faPaintbrushPencil} />}
          className="ditty-icon-block--heading"
        >
          <div className="ditty-icon-block--heading__title">
            <h2>{__("Layout Settings", "ditty-news-ticker")}</h2>
          </div>
          <p>{getItemLabel(editItem)}</p>
        </IconBlock>
        <Tabs
          type="cloud"
          tabs={[
            {
              id: "layouts",
              icon: <FontAwesomeIcon icon={faTableLayout} />,
              label: __("Layouts", "ditty-news-ticker"),
            },
            {
              id: "customizations",
              icon: <FontAwesomeIcon icon={faGear} />,
              label: __("Customizations", "ditty-news-ticker"),
            },
          ]}
          currentTabId={currentTabId}
          tabClick={(tab) => setCurrentTabId(tab.id)}
          className="itemEdit__header__tabs"
        />
      </>
    );
  };

  const renderPopupContents = () => {
    if ("layouts" === currentTabId) {
      return (
        <>
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
        </>
      );
    } else {
      return (
        <FieldList
          name={__("Layout Tag Customizations", "ditty-news-ticker")}
          description={__(
            "Customize the layout tags that are using in Layouts for this item. Keep in mind that some layouts may not use all of these tags.",
            "ditty-news-ticker"
          )}
          fields={getTagFields(itemTypeObject.layoutTags)}
          values={editItem.attribute_value ? editItem.attribute_value : {}}
          onUpdate={(id, value) => {
            const updatedItem = { ...editItem };
            if (
              !updatedItem.attribute_value ||
              typeof updatedItem.attribute_value !== "object" ||
              Array.isArray(updatedItem.attribute_value)
            ) {
              updatedItem.attribute_value = {};
            }
            updatedItem.attribute_value[id] = value;
            addItemUpdate(updatedItem, "attribute_value");
          }}
        />
      );
    }
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
        {renderPopupContents()}
      </Popup>
      {renderPopup()}
    </>
  );
};
export default PopupLayouts;

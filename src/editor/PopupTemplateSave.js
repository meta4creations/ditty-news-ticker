import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { saveDisplay, saveLayout } from "../services/httpService";
import { IconBlock, Filter, List, ListItem, Popup, Tabs } from "../components";
import { FieldList, TextField, TextareaField } from "../fields";

const PopupTemplateSave = ({
  templateType,
  currentTemplate,
  templates,
  filters,
  filterKey,
  headerIcon,
  headerTitle = __("Save as Template", "ditty-news-ticker"),
  headerDescription = __(
    "Save your data as a template you can use in other Ditty.",
    "ditty-news-ticker"
  ),
  templateIcon,
  saveData,
  onClose,
  onUpdate,
}) => {
  const { dittyNotification } = dittyEditor.notifications;
  const [templateName, setTemplateName] = useState(
    currentTemplate.title ? currentTemplate.title : ""
  );
  const [templateDescription, setTemplateDescription] = useState(
    currentTemplate.description ? currentTemplate.description : ""
  );
  const [selectedTemplate, setSelectedTemplate] = useState(currentTemplate);
  const [filteredTemplates, setFilteredTemplates] = useState(templates);
  const [currentTabId, setCurrentTabId] = useState(
    currentTemplate.id ? "existing" : "new"
  );
  const [showSpinner, setShowSpinner] = useState(false);
  const elements = [
    {
      id: "icon",
      content: (template) => {
        return (
          templateIcon && (
            <div className="ditty-preview-icon">{templateIcon(template)}</div>
          )
        );
      },
    },
    {
      id: "content",
      content: (template) => {
        return (
          <>
            <h3>{template.title}</h3>
            <span>{template.description}</span>
          </>
        );
      },
    },
  ];

  const popupHeader = () => {
    return (
      <>
        <IconBlock icon={headerIcon} className="ditty-icon-block--heading">
          <div className="ditty-icon-block--heading__title">
            <h2>{headerTitle}</h2>
          </div>
          <p>{headerDescription}</p>
        </IconBlock>
        <Tabs
          tabs={[
            {
              id: "new",
              label: __("New Template", "ditty-news-ticker"),
            },
            {
              id: "existing",
              label: __("Existing Template", "ditty-news-ticker"),
            },
          ]}
          currentTabId={currentTabId}
          tabClick={(tab) => setCurrentTabId(tab.id)}
          type="secondary"
        />
        {"existing" === currentTabId && (
          <Filter
            data={templates}
            filters={filters}
            filterKey={filterKey}
            searchKey="title"
            searchLabel={__("Search Templates", "ditty-news-ticker")}
            onUpdate={(data) => {
              setFilteredTemplates(data);
            }}
          />
        )}
      </>
    );
  };

  /**
   * Render the template items
   * @returns array
   */
  const renderTemplates = () => {
    return filteredTemplates.length ? (
      filteredTemplates.map((template) => {
        return (
          <ListItem
            key={template.id}
            data={template}
            elements={elements}
            isActive={selectedTemplate.id === template.id}
            onItemClick={(e, data) => {
              if (selectedTemplate.id && selectedTemplate.id === data.id) {
                setSelectedTemplate(currentTemplate);
              } else {
                setSelectedTemplate(data);
              }
            }}
          />
        );
      })
    ) : (
      <ListItem
        elements={[
          {
            id: "label",
            content: __("Sorry, no resultes", "ditty-news-ticker"),
          },
        ]}
      />
    );
  };

  const handleApiData = (apiData) => {
    if (apiData.errors.length) {
    } else {
      onUpdate({ ...selectedTemplate, ...apiData.updates });
    }
  };

  const handleSaveTemplate = async () => {
    setShowSpinner(true);
    const saveTemplate = { ...selectedTemplate };
    if ("new" === currentTabId) {
      delete saveTemplate.id;
    }
    const data = saveData(
      currentTabId,
      saveTemplate,
      templateName,
      templateDescription
    );
    try {
      if ("display" === templateType) {
        await saveDisplay(data, handleApiData);
      } else if ("layout" === templateType) {
        await saveLayout(data, handleApiData);
      }
      dittyNotification(`"${templateName}" template has been saved!`);
      setShowSpinner(false);
    } catch (ex) {
      dittyNotification(ex, "error");
      setShowSpinner(false);
    }
  };

  return (
    <Popup
      id="templateSave"
      submitLabel={
        "existing" === currentTabId
          ? __("Overwrite Template", "ditty-news-ticker")
          : __("Create Template", "ditty-news-ticker")
      }
      submitDisabled={
        ("existing" === currentTabId && !selectedTemplate.id) ||
        ("new" === currentTabId && "" === templateName)
      }
      header={popupHeader()}
      onClose={() => {
        onClose();
      }}
      onSubmit={handleSaveTemplate}
      showSpinner={showSpinner}
    >
      {"new" === currentTabId ? (
        <FieldList>
          <TextField
            id="dittyTemplateName"
            name={__("Template Name", "ditty-news-ticker")}
            value={templateName}
            onChange={(value) => setTemplateName(value)}
          />
          <TextareaField
            id="dittyTemplateDescription"
            name={__("Template Description", "ditty-news-ticker")}
            value={templateDescription}
            onChange={(value) => setTemplateDescription(value)}
          />
        </FieldList>
      ) : (
        <List>{renderTemplates()}</List>
      )}
    </Popup>
  );
};
export default PopupTemplateSave;

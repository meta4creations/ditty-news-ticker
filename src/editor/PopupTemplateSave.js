import { __ } from "@wordpress/i18n";
import { toast } from "react-toastify";
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
        return templateIcon && templateIcon(template);
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
    const data = saveData(
      currentTabId,
      selectedTemplate,
      templateName,
      templateDescription
    );
    setShowSpinner(true);
    try {
      if ("display" === templateType) {
        await saveDisplay(data, handleApiData);
      } else if ("layout" === templateType) {
        await saveLayout(data, handleApiData);
      }
      toast(`"${templateName}" template has been saved!`, {
        autoClose: 3000,
        icon: (
          <svg
            className="ditty-logo"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 69.8 71.1"
          >
            <path d="M0 46.4c0-17.2 8.6-29.1 24.6-29.1a19.93 19.93 0 0 1 6.6 1V0H45v59.2l1 10.3H34.2l-.9-5.2h-.5a15.21 15.21 0 0 1-13 6.8C3.8 71.1 0 58.4 0 46.4Zm31.2 7.4V28.6a13.7 13.7 0 0 0-6-1.3c-8.7 0-11.3 8.7-11.3 17.8 0 8.5 1.9 15.8 8.9 15.8 5.1 0 8.4-3.8 8.4-7.1ZM54.7 63.7a7 7 0 0 1 7.4-7.2c5 0 7.7 2.8 7.7 7.1s-2.6 7.5-7.4 7.5c-5.1 0-7.7-3.1-7.7-7.4Z" />
          </svg>
        ),
      });
    } catch (ex) {
      if (ex.response && ex.response.status === 404) {
      }
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
      showSpinner={showSpinner}
      onClose={() => {
        onClose();
      }}
      onSubmit={handleSaveTemplate}
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

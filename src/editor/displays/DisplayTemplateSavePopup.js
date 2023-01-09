import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faTabletScreen } from "@fortawesome/pro-light-svg-icons";
import { saveDisplay } from "../../services/httpService";
import {
  IconBlock,
  Filter,
  List,
  ListItem,
  Popup,
  Tabs,
} from "../../components";
import {
  getDisplayTypes,
  getDisplayTypeIcon,
  getDisplayTypeDescription,
} from "../utils/displayTypes";
import { FieldList, TextField, TextareaField } from "../../fields";

const DisplayTemplateSavePopup = ({
  activeTemplate,
  templates,
  onClose,
  onUpdate,
}) => {
  const [templateName, setTemplateName] = useState(
    activeTemplate.title ? activeTemplate.title : ""
  );
  const [templateDescription, setTemplateDescription] = useState(
    activeTemplate.description ? activeTemplate.description : ""
  );
  const [currentTemplate, setCurrentTemplate] = useState(activeTemplate);
  const [filteredTemplates, setFilteredTemplates] = useState(templates);
  const [currentTabId, setCurrentTabId] = useState("new");
  const [showSpinner, setShowSpinner] = useState(false);
  const displayTypes = getDisplayTypes();

  const elements = [
    {
      id: "icon",
      content: (template) => {
        return getDisplayTypeIcon(template);
      },
    },
    {
      id: "content",
      content: (template) => {
        return (
          <>
            <h3>{template.title}</h3>
            <span>{getDisplayTypeDescription(template)}</span>
          </>
        );
      },
    },
  ];

  const popupHeader = () => {
    return (
      <>
        <IconBlock icon={<FontAwesomeIcon icon={faTabletScreen} />}>
          <h2>{__("Save as Template", "ditty-news-ticker")}</h2>
          <p>
            {__(
              "Save your Display as template you can use in other Ditty.",
              "ditty-news-ticker"
            )}
          </p>
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
            filters={displayTypes}
            filterKey="type"
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
   * Render the display items
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
            isActive={currentTemplate === template}
            onItemClick={(e, data) => {
              if (currentTemplate.id && currentTemplate.id === data.id) {
                setCurrentTemplate(activeTemplate);
              } else {
                setCurrentTemplate(data);
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
      onUpdate({ ...currentTemplate, ...apiData.updates });
    }
  };

  const handleSaveDisplay = async () => {
    const data =
      "existing" === currentTabId
        ? {
            display: {
              ...currentTemplate,
              type: activeTemplate.type,
              settings: activeTemplate.settings,
              updated: Date.now(),
            },
          }
        : {
            title: templateName,
            description: templateDescription,
            display: currentTemplate,
          };
    setShowSpinner(true);
    try {
      await saveDisplay(data, handleApiData);
    } catch (ex) {
      if (ex.response && ex.response.status === 404) {
      }
      setShowSpinner(false);
    }
  };

  return (
    <Popup
      id="displayTemplateSelector"
      submitLabel={
        "existing" === currentTabId
          ? __("Overwrite Template", "ditty-news-ticker")
          : __("Create Template", "ditty-news-ticker")
      }
      submitDisabled={
        ("existing" === currentTabId && !currentTemplate.id) ||
        ("new" === currentTabId && "" === templateName)
      }
      header={popupHeader()}
      showSpinner={showSpinner}
      onClose={() => {
        onClose();
      }}
      onSubmit={handleSaveDisplay}
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
export default DisplayTemplateSavePopup;

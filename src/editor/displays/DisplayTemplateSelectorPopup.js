import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faTabletScreen } from "@fortawesome/pro-light-svg-icons";

import { updateDittyDisplayTemplate } from "../../services/dittyService";
import { IconBlock, Filter, List, ListItem, Popup } from "../../components";
import {
  displayTypes,
  getDisplayTypeIcon,
  getDisplayTypeLabel,
} from "../utils/displayTypes";

const DisplayTemplateSelectorPopup = ({
  activeTemplate,
  templates,
  onClose,
  onUpdate,
  dittyEl,
}) => {
  const [currentTemplate, setCurrentTemplate] = useState(activeTemplate);
  const [filteredTemplates, setFilteredTemplates] = useState(templates);

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
            <span>{getDisplayTypeLabel(template)}</span>
          </>
        );
      },
    },
  ];

  const popupHeader = () => {
    return (
      <>
        <IconBlock icon={<FontAwesomeIcon icon={faTabletScreen} />}>
          <h2>{__("Choose a saved Display template", "ditty-news-ticker")}</h2>
          <p>
            {__(
              "Select one of your previously saved Display templates.",
              "ditty-news-ticker"
            )}
          </p>
        </IconBlock>
        <Filter
          data={templates}
          filters={displayTypes}
          filterKey="type"
          searchKey="title"
          searchLabel={__("Search Templates", "ditty-news-ticker")}
          onUpdate={(data) => {
            console.log(data);
            setFilteredTemplates(data);
          }}
        />
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
              if (data.id === currentTemplate.id) {
                return false;
              }
              if (dittyEl) {
                updateDittyDisplayTemplate(dittyEl, data);
              }
              setCurrentTemplate(data);
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

  return (
    <Popup
      id="displayTemplateSelector"
      submitLabel={__("Use Template", "ditty-news-ticker")}
      header={popupHeader()}
      onClose={() => {
        if (activeTemplate.id !== currentTemplate.id && dittyEl) {
          updateDittyDisplayTemplate(dittyEl, activeTemplate);
        }
        onClose();
      }}
      onSubmit={() => {
        onUpdate(currentTemplate);
      }}
    >
      <List>{renderTemplates()}</List>
    </Popup>
  );
};
export default DisplayTemplateSelectorPopup;

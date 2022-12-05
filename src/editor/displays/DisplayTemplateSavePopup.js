import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faTabletScreen } from "@fortawesome/pro-light-svg-icons";

import { IconBlock, Filter, List, ListItem, Popup } from "../../components";
import {
  displayTypes,
  getDisplayTypeIcon,
  getDisplayTypeLabel,
} from "../utils/displayTypes";
import { TextField } from "../../fields";

const DisplayTemplateSavePopup = ({
  activeTemplate,
  templates,
  onClose,
  onUpdate,
}) => {
  const [templateName, setTemplateName] = useState("");
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
          <h2>{__("Save as Template", "ditty-news-ticker")}</h2>
          <p>
            {__(
              "Save your Display as template you can use in other Ditty.",
              "ditty-news-ticker"
            )}
          </p>
        </IconBlock>
        <TextField
          id="dittyTemplateName"
          name={__("Template Name", "ditty-news-ticker")}
          value={templateName}
          onChange={(value) => setTemplateName(value)}
        />
        {/* <Filter
          data={templates}
          filters={displayTypes}
          filterKey="type"
          searchKey="title"
          searchLabel={__("Search Templates", "ditty-news-ticker")}
          onUpdate={(data) => {
            console.log(data);
            setFilteredTemplates(data);
          }}
        /> */}
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
      submitLabel={__("Create & Save Template", "ditty-news-ticker")}
      submitDisabled={!templateName || templateName === ""}
      header={popupHeader()}
      onClose={() => {
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
export default DisplayTemplateSavePopup;

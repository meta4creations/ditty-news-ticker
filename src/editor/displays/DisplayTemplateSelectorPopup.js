import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCubes } from "@fortawesome/pro-light-svg-icons";

import { updateDittyDisplayTemplate } from "../../services/dittyService";
import { IconBlock, List, ListItem, Popup } from "../../components";
import { getDisplayTypeIcon } from "../utils/displayTypes";

const DisplayTemplateSelectorPopup = ({
  activeTemplate,
  templates,
  onClose,
  onUpdate,
  dittyEl,
}) => {
  const [currentTemplate, setCurrentTemplate] = useState(activeTemplate);

  const elements = [
    {
      id: "icon",
      content: (template) => {
        return getDisplayTypeIcon(template);
      },
    },
    {
      id: "label",
      content: "test",
      content: (template) => {
        return (
          <>
            <span>{template.title}</span>
            <span>{`ID: ${template.id}`}</span>
          </>
        );
      },
    },
  ];

  return (
    <Popup
      id="displayTemplateSelector"
      submitLabel={__("Use Template", "ditty-news-ticker")}
      header={
        <IconBlock icon={<FontAwesomeIcon icon={faCubes} />}>
          <h2>{__("Choose a saved Display template", "ditty-news-ticker")}</h2>
          <p>
            {__(
              "Select one of your previously saved Display templates.",
              "ditty-news-ticker"
            )}
          </p>
        </IconBlock>
      }
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
      <List>
        {templates.map((template) => (
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
        ))}
      </List>
    </Popup>
  );
};
export default DisplayTemplateSelectorPopup;

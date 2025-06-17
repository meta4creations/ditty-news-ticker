const { __ } = wp.i18n;
const { useState } = wp.element;
import { IconBlock, Filter, List, ListItem, Popup } from "../components";

const PopupTemplateSelector = ({
  currentTemplate,
  templates,
  filters,
  filterKey,
  headerIcon,
  templateIcon,
  submitLabel = __("Use Template", "ditty-news-ticker"),
  onChange,
  onClose,
  onUpdate,
  level,
}) => {
  const [selectedTemplate, setSelectedTemplate] = useState(currentTemplate);
  const [filteredTemplates, setFilteredTemplates] = useState(templates);

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
            <h2>{__("Choose a template", "ditty-news-ticker")}</h2>
          </div>
          <p>
            {__(
              "Select one of your previously saved templates.",
              "ditty-news-ticker"
            )}
          </p>
        </IconBlock>
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
      </>
    );
  };

  /**
   * Render the display items
   * @returns array
   */
  const renderTemplates = () => {
    return filteredTemplates.length ? (
      filteredTemplates.map((template, index) => {
        return (
          <ListItem
            key={template.id}
            data={template}
            elements={elements}
            isActive={
              selectedTemplate &&
              selectedTemplate.id &&
              selectedTemplate.id === template.id
            }
            onItemClick={(e, data) => {
              if (
                selectedTemplate &&
                selectedTemplate.id &&
                selectedTemplate.id === data.id
              ) {
                return false;
              }
              onChange && onChange(data);
              setSelectedTemplate(data);
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
      id="templateSelector"
      submitLabel={submitLabel}
      header={popupHeader()}
      submitDisabled={!selectedTemplate || selectedTemplate === currentTemplate}
      onClose={() => {
        onClose(selectedTemplate);
      }}
      onSubmit={() => {
        onUpdate(selectedTemplate);
      }}
      level={level}
    >
      <List>{renderTemplates()}</List>
    </Popup>
  );
};
export default PopupTemplateSelector;

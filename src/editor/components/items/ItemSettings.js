import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import Field from "../../common/Field";

const ItemSettings = ({ item, editor }) => {
  const { actions } = useContext(editor);

  const fields = [
    {
      type: "textarea",
      id: "content",
      name: __("Content", "ditty-news-ticker"),
      help: __(
        "Add the content of your item. HTML and inline styles are supported.",
        "ditty-news-ticker"
      ),
      std: __("This is a sample item. Please edit me!", "ditty-news-ticker"),
    },
    {
      type: "text",
      id: "link_url",
      name: __("Link", "ditty-news-ticker"),
      help: __(
        "Add a custom link to your content. You can also add a link directly into your content.",
        "ditty-news-ticker"
      ),
      atts: {
        type: "url",
      },
      std: "",
    },
    {
      type: "text",
      id: "link_title",
      name: __("Title", "ditty-news-ticker"),
      help: __("Add a title to the custom lnk.", "ditty-news-ticker"),
      std: "",
    },
    {
      type: "select",
      id: "link_target",
      name: __("Target", "ditty-news-ticker"),
      help: __("Set a target for your link.", "ditty-news-ticker"),
      options: {
        _self: "_self",
        _blank: "_blank",
      },
      std: "_self",
    },
    {
      type: "checkbox",
      id: "link_nofollow",
      name: __("No Follow", "ditty-news-ticker"),
      label: __('Add "nofollow" to link', "ditty-news-ticker"),
      help: __(
        "Enabling this setting will add an attribute called 'nofollow' to your link. This tells search engines to not follow this link.",
        "ditty-news-ticker"
      ),
      std: "",
    },
  ];

  const handleFieldUpdate = (field, value) => {
    const updatedItem = item;
    updatedItem.item_value[field.id] = value;
    actions.updateItem(updatedItem);
  };

  const renderFields = () => {
    console.log("fields", fields);

    return fields.map((field) => {
      const value = item.item_value[field.id]
        ? item.item_value[field.id]
        : field.std;

      return (
        <Field
          key={field.id}
          field={field}
          value={value}
          onFieldUpdate={handleFieldUpdate}
        />
      );
    });
  };

  return (
    <>
      <h2>{item.item_id}</h2>
      <div className="dittyEditorFields">{renderFields()}</div>
    </>
  );
};
export default ItemSettings;

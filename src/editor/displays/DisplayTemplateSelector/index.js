import { __ } from "@wordpress/i18n";
import { useContext } from "@wordpress/element";
import { List, ListItem } from "../../../components";
import { getDisplayTypeIcon } from "../../utils/displayTypes";

const DisplayTemplateSelector = ({ selected, onSelected, editor }) => {
  const { displays } = useContext(editor);
  const elements = window.dittyHooks.applyFilters(
    "dittyDisplayTemplatesListElements",
    [
      {
        id: "icon",
        content: (display) => {
          return getDisplayTypeIcon(display);
        },
      },
      {
        id: "label",
        content: "test",
        content: (display) => {
          return (
            <>
              <span>{display.title}</span>
              <span>{`ID: ${display.id}`}</span>
            </>
          );
        },
      },
    ],
    editor
  );

  return (
    <List>
      {displays.map((display) => (
        <ListItem
          key={display.id}
          data={display}
          elements={elements}
          isActive={selected === display}
          onItemClick={(e, data) => {
            onSelected(data);
          }}
        />
      ))}
    </List>
  );
};
export default DisplayTemplateSelector;

import { __ } from "@wordpress/i18n";
import Item from "./item";

const Items = ({ items }) => {
  return (
    <>
      {items.map((item) => {
        return <Item data={item} key={item.item_id} />;
      })}
    </>
  );
};
export default Items;

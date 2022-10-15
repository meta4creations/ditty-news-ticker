import { __ } from "@wordpress/i18n";

const Panel = (props) => {
  const { id, header, content } = props;
  return (
    <div className={`ditty-editor__panel ditty-editor__panel--${id}`} key={id}>
      {header && <div className="ditty-editor__panel__header">{header}</div>}
      <div className="ditty-editor__panel__content">{content}</div>
    </div>
  );
};
export default Panel;

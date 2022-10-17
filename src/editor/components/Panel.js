import { __ } from "@wordpress/i18n";
import Tabs from "./Tabs";

const Panel = (props) => {
  const { id, header, tabs, currentTabId, tabClick, content } = props;

  const renderPanelClass = () => {
    let className = `ditty-editor__panel ditty-editor__panel--${id}`;
    return className;
  };

  return (
    <div className={renderPanelClass()} key={id}>
      {header && <div className="ditty-editor__panel__header">{header}</div>}
      {tabs && (
        <Tabs
          tabs={tabs}
          currentTabId={currentTabId}
          tabClick={tabClick}
          type="secondary"
        />
      )}
      <div className="ditty-editor__panel__content">{content}</div>
    </div>
  );
};
export default Panel;

import { __ } from "@wordpress/i18n";
import Tabs from "./Tabs";

const Panel = (props) => {
  const {
    id,
    header,
    footer,
    tabs,
    currentTabId,
    tabsType,
    tabClick,
    children,
  } = props;

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
          type={tabsType ? tabsType : "secondary"}
        />
      )}
      <div className="ditty-editor__panel__content">{children}</div>
      {footer && <div className="ditty-editor__panel__footer">{footer}</div>}
    </div>
  );
};
export default Panel;

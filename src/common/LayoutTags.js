const { useState } = wp.element;
const { __ } = wp.i18n;
import PopupEditLayoutTag from "./PopupEditLayoutTag";

const LayoutTags = ({ type, layoutTags, className, styles }) => {
  const [currentTag, setCurrentTag] = useState(false);

  const modifiedLayoutTags =
    "css" === type ? [{ tag: "elements" }, ...layoutTags] : layoutTags;

  /**
   * Render a popup component
   * @returns Popup component
   */
  const renderPopup = () => {
    if (currentTag) {
      return (
        <PopupEditLayoutTag
          layoutTag={currentTag}
          level="1"
          onClose={() => {
            setCurrentTag(false);
          }}
        >
          {currentTag.tag}
        </PopupEditLayoutTag>
      );
    }
  };

  return "css" === type ? (
    <div className="editLayout__tagCloud">
      <h3>{__("CSS Selectors", "ditty-news-ticker")}</h3>
      <p>
        {__(
          "These are the css selectors associated with the available dynamic HTML tags. Click on a button to generate and insert a selector.",
          "ditty-news-ticker"
        )}
      </p>
      <div className="editLayout__tagCloud__tags">
        {modifiedLayoutTags &&
          modifiedLayoutTags.map((layoutTag) => {
            return (
              <span
                key={layoutTag.tag}
                data-tag={layoutTag.tag}
                className="editLayout__tagCloud__tag"
                onClick={() => {
                  window.dispatchEvent(
                    new CustomEvent("dittyEditorInsertLayoutTag", {
                      detail: {
                        renderedTag: `.ditty-item__${layoutTag.tag} {  }`,
                        cursorOffset: -2,
                      },
                    })
                  );
                }}
              >
                {`.ditty-item__${layoutTag.tag}`}
              </span>
            );
          })}
      </div>
    </div>
  ) : (
    <>
      <div className="editLayout__tagCloud">
        <h3>{__("Dynamic Tags", "ditty-news-ticker")}</h3>
        <p>
          {__(
            "These tags are available for the current item type. Click on a button to generate and insert a tag.",
            "ditty-news-ticker"
          )}
        </p>
        <div className="editLayout__tagCloud__tags">
          {modifiedLayoutTags &&
            modifiedLayoutTags.map((layoutTag) => {
              return (
                <span
                  key={layoutTag.tag}
                  data-tag={layoutTag.tag}
                  className="editLayout__tagCloud__tag"
                  onClick={() => setCurrentTag(layoutTag)}
                >{`{${layoutTag.tag}}`}</span>
              );
            })}
        </div>
      </div>
      {renderPopup()}
    </>
  );
};
export default LayoutTags;

// https://www.adamcollier.co.uk/blog/adding-codemirror-6-to-a-react-project

import { useRef, useEffect } from "@wordpress/element";
import { basicSetup } from "@uiw/codemirror-extensions-basic-setup";
import { EditorState } from "@codemirror/state";
import { EditorView, keymap } from "@codemirror/view";
import { defaultKeymap, indentWithTab } from "@codemirror/commands";
import { oneDark } from "@codemirror/theme-one-dark";

export const LayoutEditor = ({
  value,
  extensions,
  onChange,
  tags,
  insertTagContent,
}) => {
  const editor = useRef();

  const onUpdate = EditorView.updateListener.of((v) => {
    onChange(v.state.doc.toString());
  });

  useEffect(() => {
    console.log("tags", tags);

    const startState = EditorState.create({
      doc: value,
      extensions: [
        basicSetup(),
        keymap.of([defaultKeymap, indentWithTab]),
        EditorView.lineWrapping,
        oneDark,
        onUpdate,
        [...extensions],
      ],
    });

    const view = new EditorView({ state: startState, parent: editor.current });

    const elements = document.getElementsByClassName(
      "layoutEdit__tagCloud__tag"
    );
    Array.from(elements).forEach(function (element) {
      element.addEventListener("click", (e) => {
        const tagId = e.target.dataset.tag;
        const selectionRange = view.state.selection.ranges[0];
        const insert = insertTagContent && insertTagContent(tagId);
        if (insert) {
          const cursorPosition =
            selectionRange.to + insert.content.length + insert.cursorOffset;
          view.focus();
          view.dispatch({
            changes: {
              from: selectionRange.from,
              to: selectionRange.to,
              insert: insert.content,
            },
            selection: {
              anchor: cursorPosition,
              head: cursorPosition,
            },
          });
        }
      });
    });

    return () => {
      view.destroy();
    };
  }, []);

  return <div ref={editor}></div>;
};

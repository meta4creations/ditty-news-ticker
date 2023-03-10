// https://www.adamcollier.co.uk/blog/adding-codemirror-6-to-a-react-project

import { useRef, useEffect } from "@wordpress/element";
import _ from "lodash";
import { basicSetup } from "@uiw/codemirror-extensions-basic-setup";
import { EditorState } from "@codemirror/state";
import { EditorView, keymap } from "@codemirror/view";
import { defaultKeymap, indentWithTab } from "@codemirror/commands";
import { oneDark } from "@codemirror/theme-one-dark";

const LayoutEditor = ({ value, extensions, onChange }) => {
  const editor = useRef();

  const onUpdate = EditorView.updateListener.of((v) => {
    const updatedValue = v.state.doc.toString();
    if (_.isEqual(value, updatedValue)) return false;
    if (!value && "" === updatedValue) return false;
    onChange(updatedValue);
  });

  useEffect(() => {
    const startState = EditorState.create({
      doc: value,
      extensions: [
        basicSetup(),
        keymap.of([defaultKeymap, indentWithTab]),
        EditorView.lineWrapping,
        onUpdate,
        [...extensions],
      ],
    });

    const view = new EditorView({ state: startState, parent: editor.current });

    window.addEventListener("dittyEditorInsertLayoutTag", function (e) {
      if (!e.detail || !e.detail.renderedTag) {
        return false;
      }
      const selectionRange = view.state.selection.ranges[0];
      const offset = e.detail.cursorOffset ? e.detail.cursorOffset : 0;
      const cursorPosition =
        selectionRange.from + e.detail.renderedTag.length + offset;

      view.focus();
      view.dispatch({
        changes: {
          from: selectionRange.from,
          to: selectionRange.to,
          insert: e.detail.renderedTag,
        },
        selection: {
          anchor: cursorPosition,
        },
      });
    });

    return () => {
      view.destroy();
    };
  }, []);

  return <div ref={editor}></div>;
};
export default LayoutEditor;

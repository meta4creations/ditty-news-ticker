// https://www.adamcollier.co.uk/blog/adding-codemirror-6-to-a-react-project

import { useState, useRef, useEffect, useCallback } from "@wordpress/element";
import _ from "lodash";
import { basicSetup } from "codemirror";
import {
  EditorView,
  keymap,
  defaultKeymap,
  highlightActiveLine,
} from "@codemirror/view";
import { indentWithTab } from "@codemirror/commands";

const CodeEditor = ({ value, extensions, onChange, delayChange = false }) => {
  const [delayValue, setDelayValue] = useState(value);

  const editor = useRef();
  const timerRef = useRef(null);

  const handleInputChangeDelay = useCallback(
    (updatedValue) => {
      setDelayValue(updatedValue);

      // Clear the existing timer
      clearTimeout(timerRef.current);

      // Start a new timer to update the parent element
      timerRef.current = setTimeout(() => onChange(updatedValue), 500);
    },
    [onChange, delayValue]
  );

  const onUpdate = EditorView.updateListener.of((v) => {
    const updatedValue = v.state.doc.toString();
    //if (updatedValue.length === value.length) return;
    if (_.isEqual(value, updatedValue)) return;
    if (!value && "" === updatedValue) return;

    delayChange ? handleInputChangeDelay(updatedValue) : onChange(updatedValue);
  });

  useEffect(() => {
    const view = new EditorView({
      doc: value,
      extensions: [
        basicSetup,
        onUpdate,
        [...extensions],
        EditorView.lineWrapping,
        keymap.of([indentWithTab]),
        highlightActiveLine(),
        //keymap.of([defaultKeymap, indentWithTab]),
        // lineWrapping,
        // onUpdate,
        // [...extensions],
      ],
      parent: editor.current,
    });

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

  return <div className="ditty-code-editor" ref={editor}></div>;
};
export default CodeEditor;

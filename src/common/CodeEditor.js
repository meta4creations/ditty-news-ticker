// https://www.adamcollier.co.uk/blog/adding-codemirror-6-to-a-react-project

import { useState, useRef, useEffect, useCallback } from "@wordpress/element";
import _ from "lodash";
import { basicSetup, EditorView } from "codemirror";
import {
  keymap,
  defaultKeymap,
  indentWithTab,
  lineWrapping,
} from "@codemirror/view";
//import { basicSetup } from "@codemirror/basic-setup";
//import { EditorState } from "@codemirror/state";
//import { EditorView, keymap } from "@codemirror/view";
//import { defaultKeymap, indentWithTab } from "@codemirror/commands";
//import { oneDark } from "@codemirror/theme-one-dark";
//import { EditorState } from "@codemirror/state";

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
    // const startState = EditorState.create({
    //   doc: value,
    //   extensions: [
    //     basicSetup(),
    //     keymap.of([defaultKeymap, indentWithTab]),
    //     EditorView.lineWrapping,
    //     onUpdate,
    //     [...extensions],
    //   ],
    // });

    //const view = new EditorView({ state: startState, parent: editor.current });
    const view = new EditorView({
      doc: value,
      lineWrapping: true,
      extensions: [
        basicSetup,
        onUpdate,
        [...extensions],
        //lineWrapping,
        // keymap.of([defaultKeymap, indentWithTab]),
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

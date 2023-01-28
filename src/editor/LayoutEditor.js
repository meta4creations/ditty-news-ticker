// https://www.adamcollier.co.uk/blog/adding-codemirror-6-to-a-react-project

import { useRef, useEffect } from "@wordpress/element";
import { basicSetup } from "@uiw/codemirror-extensions-basic-setup";
import { EditorState } from "@codemirror/state";
import { EditorView, keymap } from "@codemirror/view";
import { defaultKeymap, indentWithTab } from "@codemirror/commands";
import { oneDark } from "@codemirror/theme-one-dark";

export const LayoutEditor = ({ value, extensions, onChange, tags }) => {
  const editor = useRef();

  const onUpdate = EditorView.updateListener.of((v) => {
    console.log(v.state.selection.ranges[0]);
    onChange(v.state.doc.toString());
  });

  useEffect(() => {
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

    view.dispatch({
      changes: {
        from: 1,
        to: 3,
        insert: "blammmmm",
      },
    });

    return () => {
      view.destroy();
    };
  }, []);

  return <div ref={editor}></div>;
};

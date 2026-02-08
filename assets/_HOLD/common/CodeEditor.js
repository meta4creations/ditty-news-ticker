// https://www.adamcollier.co.uk/blog/adding-codemirror-6-to-a-react-project

const { useState, useRef, useEffect, useCallback } = wp.element;
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
  const viewRef = useRef(null);
  const timerRef = useRef(null);
  const isUpdatingRef = useRef(false);
  const extensionsRef = useRef(extensions);
  const valueRef = useRef(value);
  const onChangeRef = useRef(onChange);
  const delayChangeRef = useRef(delayChange);
  const isInitializedRef = useRef(false);
  const lastUserInputRef = useRef(0);

  // Keep refs up to date
  useEffect(() => {
    valueRef.current = value;
    onChangeRef.current = onChange;
    delayChangeRef.current = delayChange;
  }, [value, onChange, delayChange]);

  // Create editor only once on mount
  useEffect(() => {
    if (!editor.current || isInitializedRef.current) return;
    isInitializedRef.current = true;

    const handleInputChangeDelay = (updatedValue) => {
      setDelayValue(updatedValue);
      clearTimeout(timerRef.current);
      timerRef.current = setTimeout(() => onChangeRef.current(updatedValue), 500);
    };

    const onUpdate = EditorView.updateListener.of((v) => {
      if (isUpdatingRef.current) return;
      
      // Track user input time
      lastUserInputRef.current = Date.now();
      
      const updatedValue = v.state.doc.toString();
      const currentValue = valueRef.current || "";
      if (_.isEqual(currentValue, updatedValue)) return;
      if (!currentValue && "" === updatedValue) return;

      delayChangeRef.current ? handleInputChangeDelay(updatedValue) : onChangeRef.current(updatedValue);
    });

    const view = new EditorView({
      doc: valueRef.current || "",
      extensions: [
        basicSetup,
        onUpdate,
        ...(extensions || []),
        EditorView.lineWrapping,
        keymap.of([indentWithTab]),
        highlightActiveLine(),
      ],
      parent: editor.current,
    });

    viewRef.current = view;
    extensionsRef.current = extensions;

    // Set up event listener for layout tag insertion
    const handleLayoutTagInsert = function (e) {
      if (!e.detail || !e.detail.renderedTag || !viewRef.current) {
        return false;
      }
      const selectionRange = viewRef.current.state.selection.ranges[0];
      const offset = e.detail.cursorOffset ? e.detail.cursorOffset : 0;
      const cursorPosition =
        selectionRange.from + e.detail.renderedTag.length + offset;

      viewRef.current.focus();
      viewRef.current.dispatch({
        changes: {
          from: selectionRange.from,
          to: selectionRange.to,
          insert: e.detail.renderedTag,
        },
        selection: {
          anchor: cursorPosition,
        },
      });
    };

    window.addEventListener("dittyEditorInsertLayoutTag", handleLayoutTagInsert);

    return () => {
      window.removeEventListener("dittyEditorInsertLayoutTag", handleLayoutTagInsert);
      if (delayChangeRef.current) {
        clearTimeout(timerRef.current);
      }
      if (viewRef.current) {
        viewRef.current.destroy();
        viewRef.current = null;
      }
    };
  }, []); // Only run once on mount

  // Update editor content when value changes externally (not from user input)
  useEffect(() => {
    if (!viewRef.current || !isInitializedRef.current) return;
    
    const currentValue = viewRef.current.state.doc.toString();
    const newValue = value || "";
    
    // Only update if the value actually changed and we're not currently updating
    if (currentValue !== newValue && !isUpdatingRef.current) {
      // Don't update if user just typed (within last 200ms)
      const timeSinceLastInput = Date.now() - lastUserInputRef.current;
      if (timeSinceLastInput < 200) {
        // User is actively typing, don't update the editor
        return;
      }
      
      isUpdatingRef.current = true;
      const selection = viewRef.current.state.selection;
      viewRef.current.dispatch({
        changes: {
          from: 0,
          to: viewRef.current.state.doc.length,
          insert: newValue,
        },
        selection: selection, // Preserve selection
      });
      // Reset flag after a brief delay to allow the update to complete
      setTimeout(() => {
        isUpdatingRef.current = false;
      }, 0);
    }
  }, [value]);


  return <div className="ditty-code-editor" ref={editor}></div>;
};
export default CodeEditor;

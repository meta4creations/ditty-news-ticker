const { __ } = wp.i18n;
const { useContext, useState, useEffect, useRef } = wp.element;
import { EditorContext } from "./context";

/**
 * PreviewDebugPanel Component
 *
 * Displays debug information for the preview system.
 * Only visible when dittyEditorVars.mode === 'development'
 *
 * @since 4.0
 */
const PreviewDebugPanel = () => {
  const editor = useContext(EditorContext);
  const { settings, displayItems, currentDisplay, previewVersion, actions } = editor;
  const [lastRefresh, setLastRefresh] = useState(null);
  const [showBorder, setShowBorder] = useState(false);
  const [position, setPosition] = useState({ left: 20, bottom: 20 });
  const panelRef = useRef(null);
  const dragStateRef = useRef(null);

  // Check if we're in development mode
  const isDevelopment = dittyEditorVars && dittyEditorVars.mode === "development";
  
  if (!isDevelopment) {
    return null;
  }

  const dittyVersion = settings?.dittyVersion || settings?.previewMode || "v4";
  const previewRenderMode = settings?.previewRenderMode || "endpoint";

  // Load saved position on mount
  useEffect(() => {
    try {
      const saved = localStorage.getItem("dittyPreviewDebugPanelPosition");
      if (saved) {
        const parsed = JSON.parse(saved);
        if (
          typeof parsed.left === "number" &&
          typeof parsed.bottom === "number"
        ) {
          setPosition(parsed);
        }
      }
    } catch (err) {
      // Ignore localStorage errors
    }
  }, []);

  // Save position when it changes
  useEffect(() => {
    try {
      localStorage.setItem(
        "dittyPreviewDebugPanelPosition",
        JSON.stringify(position)
      );
    } catch (err) {
      // Ignore localStorage errors
    }
  }, [position]);

  // Track preview refreshes
  useEffect(() => {
    if (previewVersion > 0) {
      setLastRefresh(new Date().toLocaleTimeString());
    }
  }, [previewVersion]);

  // Toggle iframe border for visibility
  useEffect(() => {
    const iframe = document.querySelector(".ditty-preview-iframe");
    if (iframe && showBorder) {
      iframe.style.border = "2px solid #ff0000";
    } else if (iframe) {
      iframe.style.border = "";
    }
  }, [showBorder]);

  const handleForceRefresh = () => {
    actions.refreshPreview();
  };

  const handleToggleBorder = () => {
    setShowBorder(!showBorder);
  };

  const handleClearConsole = () => {
    console.clear();
    console.log("[Ditty Debug] Console cleared");
  };

  const startDrag = (event) => {
    if (!panelRef.current) return;

    const panelRect = panelRef.current.getBoundingClientRect();
    dragStateRef.current = {
      startX: event.clientX,
      startY: event.clientY,
      startLeft: position.left,
      startBottom: position.bottom,
      panelWidth: panelRect.width,
      panelHeight: panelRect.height,
    };

    window.addEventListener("mousemove", handleDrag);
    window.addEventListener("mouseup", stopDrag);
  };

  const handleDrag = (event) => {
    if (!dragStateRef.current) return;

    const {
      startX,
      startY,
      startLeft,
      startBottom,
      panelWidth,
      panelHeight,
    } = dragStateRef.current;

    const deltaX = event.clientX - startX;
    const deltaY = startY - event.clientY;

    let newLeft = startLeft + deltaX;
    let newBottom = startBottom + deltaY;

    // Clamp to viewport
    const maxLeft = Math.max(0, window.innerWidth - panelWidth - 10);
    const maxBottom = Math.max(0, window.innerHeight - panelHeight - 10);

    newLeft = Math.min(Math.max(0, newLeft), maxLeft);
    newBottom = Math.min(Math.max(0, newBottom), maxBottom);

    setPosition({ left: newLeft, bottom: newBottom });
  };

  const stopDrag = () => {
    dragStateRef.current = null;
    window.removeEventListener("mousemove", handleDrag);
    window.removeEventListener("mouseup", stopDrag);
  };

  return (
    <div
      id="ditty-preview-debug-panel"
      ref={panelRef}
      style={{
        position: "fixed",
        bottom: `${position.bottom}px`,
        left: `${position.left}px`,
        background: "#f8f9fa",
        border: "1px solid #dee2e6",
        borderRadius: "4px",
        padding: "12px",
        boxShadow: "0 2px 8px rgba(0,0,0,0.1)",
        zIndex: 100000,
        fontSize: "12px",
        maxWidth: "300px",
        fontFamily: "monospace",
      }}
    >
      <div
        onMouseDown={startDrag}
        style={{
          fontWeight: "bold",
          marginBottom: "8px",
          fontSize: "13px",
          borderBottom: "1px solid #dee2e6",
          paddingBottom: "6px",
          cursor: "move",
          userSelect: "none",
        }}
      >
        Ditty Preview Debug
      </div>

      <div style={{ marginBottom: "8px" }}>
        <strong>Version:</strong> {dittyVersion}
        {dittyVersion === "v4" && (
          <>
            <br />
            <strong>Render:</strong> {previewRenderMode}
          </>
        )}
      </div>

      <div style={{ marginBottom: "8px" }}>
        <strong>Preview Version:</strong> {previewVersion}
        <br />
        <strong>Last Refresh:</strong> {lastRefresh || "N/A"}
      </div>

      <div style={{ marginBottom: "8px" }}>
        <strong>Items:</strong> {displayItems?.length || 0}
        <br />
        <strong>Display Type:</strong> {currentDisplay?.type || "N/A"}
      </div>

      <div style={{ display: "flex", flexDirection: "column", gap: "4px" }}>
        <button
          onClick={handleForceRefresh}
          style={{
            padding: "4px 8px",
            fontSize: "11px",
            cursor: "pointer",
            background: "#007bff",
            color: "#fff",
            border: "none",
            borderRadius: "3px",
          }}
        >
          Force Refresh
        </button>

        <button
          onClick={handleToggleBorder}
          style={{
            padding: "4px 8px",
            fontSize: "11px",
            cursor: "pointer",
            background: showBorder ? "#dc3545" : "#6c757d",
            color: "#fff",
            border: "none",
            borderRadius: "3px",
          }}
        >
          {showBorder ? "Hide Border" : "Show Border"}
        </button>

        <button
          onClick={handleClearConsole}
          style={{
            padding: "4px 8px",
            fontSize: "11px",
            cursor: "pointer",
            background: "#6c757d",
            color: "#fff",
            border: "none",
            borderRadius: "3px",
          }}
        >
          Clear Console
        </button>
      </div>

      <div
        style={{
          marginTop: "8px",
          fontSize: "10px",
          color: "#6c757d",
          borderTop: "1px solid #dee2e6",
          paddingTop: "6px",
        }}
      >
        Development Mode Only
      </div>
    </div>
  );
};

export default PreviewDebugPanel;

import classnames from "classnames";
const { useState, useEffect, useRef } = wp.element;
const { __ } = wp.i18n;
import { generatePreviewHTML, generatePreviewData } from "../editor/previewGenerator";
import { useDebounce } from "../hooks/useDebounce";
import axios from "axios";

/**
 * PreviewIframe Component
 *
 * Renders a Ditty preview in an iframe using either:
 * - endpoint mode: Server-side rendered page with theme styles
 * - srcdoc mode: Client-side generated HTML with v4 assets
 *
 * @param {Object} props - Component props
 * @param {string} props.mode - Preview mode: 'endpoint' | 'srcdoc' | 'v3'
 * @param {string} props.dittyId - Ditty ID
 * @param {string} props.title - Ditty title
 * @param {Object} props.display - Display settings
 * @param {Array} props.displayItems - Array of display items
 * @param {Object} props.styles - Custom preview styles
 * @param {number} props.previewVersion - Version number to force refresh
 * @param {number} props.debounceDelay - Debounce delay in ms (default: 300)
 * @param {string} props.className - Additional CSS classes
 */
const PreviewIframe = ({
  mode = "endpoint",
  dittyId,
  title,
  display,
  displayItems = [],
  styles = {},
  previewVersion = 0,
  debounceDelay = 300,
  className,
}) => {
  const iframeRef = useRef(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);
  const [previewKey, setPreviewKey] = useState(null);
  const [previewUrl, setPreviewUrl] = useState("");
  const [srcdocContent, setSrcdocContent] = useState("");

  // Debounce the preview data to avoid excessive refreshes
  const debouncedDisplay = useDebounce(display, debounceDelay);
  const debouncedItems = useDebounce(displayItems, debounceDelay);
  const debouncedTitle = useDebounce(title, debounceDelay);

  /**
   * Handle iframe load event
   */
  const handleIframeLoad = () => {
    setIsLoading(false);
    setError(null);

    // Log for debugging
    if (dittyEditorVars && dittyEditorVars.mode === "development") {
      console.log("[Ditty Preview] Iframe loaded", {
        mode,
        timestamp: new Date().toISOString(),
      });
    }
  };

  /**
   * Handle iframe error
   */
  const handleIframeError = () => {
    setIsLoading(false);
    setError(__("Failed to load preview", "ditty-news-ticker"));
  };

  /**
   * Listen for postMessage from iframe
   */
  useEffect(() => {
    const handleMessage = (event) => {
      if (event.data && event.data.type === "ditty_preview_loaded") {
        handleIframeLoad();
      }
    };

    window.addEventListener("message", handleMessage);
    return () => window.removeEventListener("message", handleMessage);
  }, []);

  /**
   * Update preview when data changes (endpoint mode)
   */
  useEffect(() => {
    if (mode !== "endpoint") return;

    const updateEndpointPreview = async () => {
      setIsLoading(true);
      setError(null);

      try {
        // Generate preview data
        const previewData = generatePreviewData({
          dittyId,
          title: debouncedTitle,
          display: debouncedDisplay,
          items: debouncedItems,
          styles,
        });

        // Generate unique preview key
        const key = `${dittyId}_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;

        // Store preview data via AJAX
        const apiURL = `${dittyEditorVars.ajaxUrl}`;
        const formData = new FormData();
        formData.append("action", "ditty_store_preview_data");
        formData.append("preview_nonce", dittyEditorVars.previewNonce || dittyEditorVars.nonce);
        formData.append("preview_key", key);
        formData.append("preview_data", JSON.stringify(previewData));

        const response = await axios.post(apiURL, formData, {
          withCredentials: true,
        });

        if (response.data && response.data.success) {
          setPreviewKey(key);
          // Build preview URL
          const url = `${dittyEditorVars.adminUrl}admin.php?action=ditty_preview&preview_key=${key}`;
          setPreviewUrl(url);
        } else {
          const responseMessage =
            typeof response.data === "string"
              ? response.data.slice(0, 200)
              : response.data?.data?.message;
          throw new Error(
            responseMessage || "Failed to store preview data"
          );
        }
      } catch (err) {
        console.error("[Ditty Preview] Error:", err);
        setError(
          err.message ||
            __("Failed to load preview. Please try again.", "ditty-news-ticker")
        );
        setIsLoading(false);
      }
    };

    updateEndpointPreview();
  }, [mode, dittyId, debouncedTitle, debouncedDisplay, debouncedItems, styles, previewVersion]);

  /**
   * Update preview when data changes (srcdoc mode)
   */
  useEffect(() => {
    if (mode !== "srcdoc") return;

    setIsLoading(true);

    try {
      // Generate HTML for srcdoc
      const html = generatePreviewHTML({
        dittyId,
        title: debouncedTitle,
        display: debouncedDisplay,
        items: debouncedItems,
        styles,
        v4CssUrl: dittyEditorVars.v4CssUrl || "",
        v4JsUrl: dittyEditorVars.v4JsUrl || "",
      });

      setSrcdocContent(html);
      
      // Small delay to ensure iframe updates
      setTimeout(() => {
        setIsLoading(false);
      }, 100);
    } catch (err) {
      console.error("[Ditty Preview] Error generating HTML:", err);
      setError(__("Failed to generate preview", "ditty-news-ticker"));
      setIsLoading(false);
    }
  }, [mode, dittyId, debouncedTitle, debouncedDisplay, debouncedItems, styles, previewVersion]);

  /**
   * Render loading spinner
   */
  const renderLoading = () => {
    return (
      <div className="ditty-preview-loading">
        <div className="ditty-spinner"></div>
        <p>{__("Loading preview...", "ditty-news-ticker")}</p>
      </div>
    );
  };

  /**
   * Render error message
   */
  const renderError = () => {
    return (
      <div className="ditty-preview-error">
        <p className="ditty-preview-error__message">{error}</p>
        <button
          className="ditty-button"
          onClick={() => {
            setError(null);
            setIsLoading(true);
            // Force refresh by updating preview version
            if (iframeRef.current) {
              if (mode === "endpoint") {
                iframeRef.current.src = iframeRef.current.src;
              } else {
                iframeRef.current.srcdoc = srcdocContent;
              }
            }
          }}
        >
          {__("Retry", "ditty-news-ticker")}
        </button>
      </div>
    );
  };

  // Build iframe classes
  const iframeClasses = classnames("ditty-preview-iframe", className, {
    "ditty-preview-iframe--loading": isLoading,
    "ditty-preview-iframe--error": error,
  });

  // Render iframe based on mode
  if (mode === "endpoint") {
    return (
      <div className="ditty-preview-iframe-wrapper">
        {isLoading && renderLoading()}
        {error && renderError()}
        {previewUrl && (
          <iframe
            ref={iframeRef}
            className={iframeClasses}
            src={previewUrl}
            title={__("Ditty Preview", "ditty-news-ticker")}
            onLoad={handleIframeLoad}
            onError={handleIframeError}
            style={{ display: isLoading || error ? "none" : "block" }}
          />
        )}
      </div>
    );
  } else if (mode === "srcdoc") {
    return (
      <div className="ditty-preview-iframe-wrapper">
        {isLoading && renderLoading()}
        {error && renderError()}
        <iframe
          ref={iframeRef}
          className={iframeClasses}
          srcDoc={srcdocContent}
          title={__("Ditty Preview", "ditty-news-ticker")}
          onLoad={handleIframeLoad}
          onError={handleIframeError}
          style={{ display: isLoading || error ? "none" : "block" }}
        />
      </div>
    );
  }

  // Fallback: should not reach here
  return (
    <div className="ditty-preview-error">
      <p>{__("Invalid preview mode", "ditty-news-ticker")}</p>
    </div>
  );
};

export default PreviewIframe;

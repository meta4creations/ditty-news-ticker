/**
 * Ditty Display Block - Save Component
 *
 * Uses InnerBlocks.Content to save the inner block content.
 * The actual rendering is done server-side via render.php.
 */

import { InnerBlocks } from "@wordpress/block-editor";

export default function save() {
  // Server-side rendering via render.php handles the wrapper
  // We only save the inner blocks content
  return <InnerBlocks.Content />;
}

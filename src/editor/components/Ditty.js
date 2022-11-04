import { __ } from "@wordpress/i18n";
import _ from "lodash";
import { useContext, useEffect, useState } from "@wordpress/element";
import { EditorContext } from "../context";
import DittyItem from "./DittyItem";
import { getDisplayObject } from "../utils/displayTypes";

const Ditty = () => {
  const { id, items, displays, currentDisplay } = useContext(EditorContext);
  const displayObject = getDisplayObject(currentDisplay, displays);

  // $ditty_atts = array(
  //   'id'										=> ( '' != $args['el_id'] ) ? sanitize_title( $args['el_id'] ) : false,
  //   'class' 								=> $class,
  //   'data-id' 							=> $args['id'],
  //   'data-uniqid' 					=> $args['uniqid'],
  //   'data-display' 					=> ( '' != $args['display'] ) ? $args['display'] : false,
  //   'data-display_settings' => ( '' != $args['display_settings'] ) ? $args['display_settings'] : false,
  //   'data-layout_settings' 	=> ( '' != $args['layout_settings'] ) ? $args['layout_settings'] : false,
  //   'data-show_editor' 			=> ( 0 != intval( $args['show_editor'] ) ) ? '1' : false,
  //   'data-ajax_load' 				=> $ajax_load,
  //   'data-live_updates' 		=> $live_updates,
  // );

  useEffect(() => {
    function setDittyAttributes() {
      const dittyEl = document.getElementById("ditty-editor__ditty");
      dittyEl.dataset.type = displayObject.type;
      dittyEl.dataset.display = displayObject.id;
      dittyEl.dataset.settings = JSON.stringify(displayObject.settings);
    }
    setDittyAttributes();
  }, []);

  const renderjQuery = () => {
    jQuery(function ($) {
      $("div#ditty-editor__ditty").ditty_ticker({});
    });
  };

  return (
    <>
      <div id="ditty-editor__ditty" className="ditty" data-id={id}></div>
      <script>{renderjQuery()}</script>
    </>
  );
};
export default Ditty;

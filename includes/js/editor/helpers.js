/**
 * Reorder a set of items by id
 *
 * @since    3.0
 * @return   null
*/
function dittyGetItemsById( items, id ) {
	var idItems = [];
	jQuery.each( items, function( i, item ) {
		if ( String( item.id ) === String( id ) ) {
			idItems.push( item );
		}
	} );
	return idItems;
}

/**
 * Reorder a set of items
 *
 * @since    3.0
 * @return   null
*/
function dittyItemsReorder( items, ids ) {
	var orderedItems = [];
	jQuery.each( ids, function( index, id ) {
		orderedItems = jQuery.merge( orderedItems, dittyGetItemsById( items, id ) );
	} );
	return orderedItems;
}

/**
 * Ditty editor ajax request
 * @since    3.0
 * @return   null
*/
// function ditty_editor_ajax( data, elmt ) {
// 	var defaults = {
// 		action				: 'ditty_editor_ajax',
// 		draft_values 	: elmt.settings.editor.getDraftValues(),
// 		security			: dittyVars.security
// 	};
// 	var ajax_data = jQuery.extend( {}, defaults, data );
// 	jQuery.post( dittyVars.ajaxurl, ajax_data, function( response ) {
// 		if ( response.draft_values ) {
// 			elmt.settings.editor.updateDraftValues( false, response.draft_values );
// 		}
// 		if ( response.hook ) {
// 			elmt.$elmt.trigger( response.hook, [response] );
// 		}
// 	}, 'json' );
// }

/**
 * Update draft data
 * @since    3.0
 * @return   null
*/
function dittyDraftUpdate( elmt, type, key, value ) {
	var draftValues = elmt.settings.editor.getDraftValues( type );
	if ( ! draftValues ) {
		draftValues = {};
	}
	if ( key ) {
		draftValues[key] = value;
	} else {
		draftValues = value;
	}
	return elmt.settings.editor.updateDraftValues( type, draftValues );
}

/**
 * Get draft data
 * @since    3.0
 * @return   null
*/
function dittyDraftGet( elmt, type, key ) {
	var draftValues = elmt.settings.editor.getDraftValues( type );
	if ( ! draftValues ) {
		return false;
	}
	if ( key && draftValues[key] ) {
		return draftValues[key];
	} else {
		return draftValues;
	}
}

/**
 * Set a ditty element to delete
 *
 * @since    3.0
 * @return   null
*/
function dittyDraftDelete( type, elmt, el_id ) {
	var draftValues = elmt.settings.editor.getDraftValues( type ),
			updatedDraftValues = {};

	jQuery.each( draftValues, function( id, values ) { 
		if ( String( el_id ) !== String( id ) ) {
			updatedDraftValues[id] = values;
		}
	} );	
	if ( 'new-' !== String( el_id ).substring( 0, 4 ) ) {
		updatedDraftValues[el_id] = 'DELETE';
	}
	return elmt.settings.editor.updateDraftValues( type, updatedDraftValues );
}
function dittyDraftItemDelete( elmt, itemId ) {
	return dittyDraftDelete( 'items', elmt, itemId );
}
function dittyDraftLayoutDelete( elmt, layoutId ) {
	return dittyDraftDelete( 'layouts', elmt, layoutId );
}
function dittyDraftDisplayDelete( elmt, displayId ) {
	return dittyDraftDelete( 'displays', elmt, displayId );
}

/**
 * Update item draft data
 *
 * @since    3.0
 * @return   null
*/
function dittyDraftItemUpdateData( elmt, itemId, key, value ) {
	var itemDraftValues = elmt.settings.editor.getDraftValues( 'items' );
	if ( ! itemDraftValues ) {
		itemDraftValues = {};
	}
	if ( ! itemDraftValues[itemId] ) {
		itemDraftValues[itemId] = {};
	}
	if ( ! itemDraftValues[itemId].data ) {
		itemDraftValues[itemId].data = {};
	}
	if ( key ) {
		itemDraftValues[itemId].data[key] = value;
	} else {
		itemDraftValues[itemId].data = value;
	}
	return elmt.settings.editor.updateDraftValues( 'items', itemDraftValues );
}

/**
 * Get item draft data
 *
 * @since    3.0
 * @return   null
*/
function dittyDraftItemGetData( elmt, itemId, key ) {
	var itemDraftValues = elmt.settings.editor.getDraftValues( 'items' );
	if ( ! itemDraftValues[itemId] ) {
		return false;
	}
	if ( ! itemDraftValues[itemId].data ) {
		return false;
	}
	if ( key ) {
		if ( itemDraftValues[itemId].data[key] ) {
			return itemDraftValues[itemId].data[key];
		}
	} else {
		return itemDraftValues[itemId].data;
	}
}

/**
 * Update item draft meta
 *
 * @since    3.0
 * @return   null
*/
function dittyDraftItemUpdateMeta( elmt, itemId, key, value ) {
	var itemDraftValues = elmt.settings.editor.getDraftValues( 'items' );
	if ( ! itemDraftValues ) {
		itemDraftValues = {};
	}
	if ( ! itemDraftValues[itemId] ) {
		itemDraftValues[itemId] = {};
	}
	if ( ! itemDraftValues[itemId].meta ) {
		itemDraftValues[itemId].meta = {};
	}
	if ( key ) {
		itemDraftValues[itemId].meta[key] = value;
	} else {
		itemDraftValues[itemId].meta = value;
	}
	return elmt.settings.editor.updateDraftValues( 'items', itemDraftValues );
}

/**
 * Get item draft meta
 *
 * @since    3.0
 * @return   null
*/
function dittyDraftItemGetMeta( elmt, itemId, key ) {
	var itemDraftValues = elmt.settings.editor.getDraftValues( 'items' );
	if ( ! itemDraftValues[itemId] ) {
		return false;
	}
	if ( ! itemDraftValues[itemId].meta ) {
		return false;
	}
	if ( key ) {
		if ( itemDraftValues[itemId].meta[key] ) {
			return itemDraftValues[itemId].meta[key];
		}
	} else {
		return itemDraftValues[itemId].meta;
	}
}

/**
 * Update layout draft meta
 *
 * @since    3.0
 * @return   null
*/
function dittyDraftLayoutUpdate( elmt, layoutId, key, value ) {
	var layoutDraftValues = elmt.settings.editor.getDraftValues( 'layouts' );
	if ( ! layoutDraftValues ) {
		layoutDraftValues = {};
	}
	if ( ! layoutDraftValues[layoutId] ) {
		layoutDraftValues[layoutId] = {};
	}
	if ( key ) {
		layoutDraftValues[layoutId][key] = value;
	} else {
		layoutDraftValues[layoutId] = value;
	}
	return elmt.settings.editor.updateDraftValues( 'layouts', layoutDraftValues );
}

/**
 * Get layout draft meta
 *
 * @since    3.0
 * @return   null
*/
function dittyDraftLayoutGet( elmt, layoutId, key ) {
	var layoutDraftValues = elmt.settings.editor.getDraftValues( 'layouts' );
	if ( ! layoutDraftValues[layoutId] ) {
		return false;
	}
	if ( key ) {
		if ( layoutDraftValues[layoutId][key] ) {
			return layoutDraftValues[layoutId][key];
		}
	} else {
		return layoutDraftValues[layoutId];
	}
}

/**
 * Update display draft meta
 *
 * @since    3.0
 * @return   null
*/
function dittyDraftDisplayUpdate( elmt, displayId, key, value ) {
	var displayDraftValues = elmt.settings.editor.getDraftValues( 'displays' );
	if ( ! displayDraftValues ) {
		displayDraftValues = {};
	}
	if ( ! displayDraftValues[displayId] ) {
		displayDraftValues[displayId] = {};
	}
	if ( key ) {
		displayDraftValues[displayId][key] = value;
	} else {
		displayDraftValues[displayId] = value;
	}
	return elmt.settings.editor.updateDraftValues( 'displays', displayDraftValues );
}

/**
 * Get layout draft meta
 *
 * @since    3.0
 * @return   null
*/
function dittyDraftDisplayGet( elmt, displayId, key ) {
	var displayDraftValues = elmt.settings.editor.getDraftValues( 'layouts' );
	if ( ! displayDraftValues[displayId] ) {
		return false;
	}
	if ( key ) {
		if ( displayDraftValues[displayId][key] ) {
			return displayDraftValues[displayId][key];
		}
	} else {
		return displayDraftValues[displayId];
	}
}
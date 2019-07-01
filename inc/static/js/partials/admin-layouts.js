jQuery( document ).ready( function($) {

	// Setup strict mode
	(function() {

    "use strict";
    
    /**
     * Destroy CodeMirror
     * @since 	3.0
     */
/*
    function destroy_structure_codemirror() {
	    
	    $('#dnt-layout-structure').find('.dnt-layout-structure-textarea').each( function() {
		    
		    var $textarea = $(this);
		    
		    if( $textarea[0].CodeMirror ) {
			    console.log('yes');
		    } else {
			    console.log('no');
		    }
		    
		    //console.log( $textarea[0].CodeMirror() );
		    
		   // CodeMirror.toTextArea( $textarea[0] );
	    });  
    }
*/
  
    /**
     * Initialize CodeMirror
     * @since 	3.0
     */
/*
    function initialize_structure_codemirror( $textarea ) {
	    
    	var editor = CodeMirror.fromTextArea($textarea[0], {
		    lineNumbers: true,
		    lineWrapping: true,
		    tabSize: 2,
		    indentWithTabs: true,
		    mode: 'htmlmixed',
		    theme: 'blackboard'
		  });
		  editor.setSize( false, '100%' );
    }
*/
    
    $('.dnt-layout-structure-textarea').each( function() {
	    
	    var $textarea = $(this);

    	var editor = CodeMirror.fromTextArea( $textarea[0], {
		    lineNumbers: true,
		    lineWrapping: true,
		    tabSize: 2,
		    indentWithTabs: true,
		    mode: 'htmlmixed',
		    theme: 'blackboard'
		  });
		  editor.setSize( false, false );
    });
    
    $('.dnt-layout-style-textarea').each( function() {
	    
	    var $textarea = $(this);
	    
    	var editor = CodeMirror.fromTextArea( $textarea[0], {
		    lineNumbers: true,
		    lineWrapping: true,
		    tabSize: 2,
		    indentWithTabs: true,
		    mode: 'css',
		    theme: 'blackboard'
		  });
		  editor.setSize( false, false );
    });

	}());

});
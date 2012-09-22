jQuery(document).ready(function($){
	
	// Delete template function
	$( '.delete_template' ).click( function(){
	    var delete_id = $( this ).attr('rel');
	    $( '#delete_template_id' ).val( delete_id );
	    
	    check = confirm( dfb_txt_delete_template_question );
		if ( true == check ){
			console.log( 'DID: ' + delete_id );
			$( '#delete_template_id' ).parent().parent().parent().submit();
		}
	});
});
	
	
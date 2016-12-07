<?php 


// This function builds JS validation for checkbox

function kino_validate_chekbox( $field_id ) {
		
		?>
		
		$("#profile-edit-form div.field_<?php echo $field_id; ?> ").addClass('has-error');
		 					
		 					$("#profile-edit-form div#field_<?php echo $field_id; ?> label:first-of-type input").attr('data-validation', 'checkbox_group');
		 					
		 					$("#profile-edit-form div#field_<?php echo $field_id; ?> label:first-of-type input").attr('data-validation-qty', 'min1');
		 					
		 				<?php	// When checking any box, remove the obligation ?>
		 					
		 					$("#profile-edit-form div#field_<?php echo $field_id; ?> input").click(function() {
		 					    if($(this).is(":checked"))
		 					    {
		 					        
		 					        <?php	// remove color: ?>
		 					        	$("#profile-edit-form div.field_<?php echo $field_id; ?> ").removeClass('has-error');
		 					        
		 					     <?php	// remove requirement! ?>
		 					        
		 					        $("#profile-edit-form div#field_<?php echo $field_id; ?> label:first-of-type input").removeAttr('data-validation');
		 					        $("#profile-edit-form div#field_<?php echo $field_id; ?> label:first-of-type input").removeAttr('data-validation-qty');
		
		 					    } 
		 					});
		
		<?php

}




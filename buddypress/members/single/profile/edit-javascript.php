<?php 
	
	// some jQuery corrections for the BuddyPress Profile Edit Page
	
	
	// Load Field Numbers:
	
	$kino_fields = kino_test_fields();
	
	// $userid = bp_loggedin_user_id();
	$userid = bp_displayed_user_id();
	
	// Load User Role testing
	
	$kino_user_role = kino_user_participation( $userid, $kino_fields );
	

 ?>
 
 <script>
 jQuery(document).ready(function($){	
 		
 		
 		// le champ Presentez-vous = limiter à 500 signes = 100 mots
 		
 			$("#profile-edit-form #field_31").attr("maxlength", "500"); 
 			
 			// le champ Mes motivations à rejoindre KinoGeneva = limiter à  500 mots = 2500 signes
 			$("#profile-edit-form #field_545").attr("maxlength", "2500"); 
 			
 			// add target_blank to .kino-edit-profile links
 			$("#profile-edit-form p.description a[href^=http]").attr('target', '_blank');
 			
 			<?php
 			
 			// Une fois les conditions générales acceptées, on ne peut plus les modifier!
 			// make #check_acc_field_1070 read-only if checked
 			//$('#profile-edit-form #check_acc_field_1070').prop('checked').prop('disabled', true);
 			
 			?>
 				
 				var kino_accept_cond = '#profile-edit-form #check_acc_field_1070';
 				if ($(kino_accept_cond).is(":checked")) {
 				        $(kino_accept_cond).prop('disabled', true);
 				}
 			
 			<?php
 			
 			/*************************************
 			 		 * Make Required Fields Required 
 			 		 * using jQuery-Form-Validator library
 			 		 * info: https://github.com/victorjonsson/jQuery-Form-Validator/wiki
 			 		 ******************************
 			 		*/
 			
 					?>
 					
 						$("div.required-field.field_type_textarea textarea").attr('data-validation', 'required');
 						
 						$("div.required-field.field_type_textbox input").attr('data-validation', 'required');
 					
 						// datebox
 						$("div.required-field.field_type_datebox select").attr('data-validation', 'required');
 						// selectbox
 						$("div.required-field.field_type_selectbox select").attr('data-validation', 'required');
 						// color
 						$("div.required-field.field_type_color input").attr('data-validation', 'required');
 						// number
 						$("div.required-field.field_type_number input").attr('data-validation', 'required');
 			 			
 			 			<?php
 			 			
 			 			// checkbox group
 			 			// **********************************
 			 			
 			 			// Disabled, not working well with BuddyPress checkbox groups
 			 			// https://bitbucket.org/ms-studio/kinogeneva/issues/71/bug-aide-b-n-vole
 			 			

 			 		
 			 			// image = must test by JS if empty
 			 			// **********************************
 			 				
 			 				?>
 			 				
 			 				$('div.required-field.field_type_image').each(function() {
 			 				    if ($(this).children('img').length) {
 			 				    	// has image = do nothing
 			 				    	// alert('has image');
 			 				    } else {
 			 				    	$(this).children('input[type=file]').attr('data-validation', 'required');
 			 				    }
 			 				});
 			 				
 			 				// file = must test by JS if empty
 			 				// **********************************
 			 					
 			 				$('div.required-field.field_type_file').each(function() {
 			 					    if ($(this).children('a').length) {
 			 					    // has link = do nothing
 			 					    // alert('has link');
 			 					    } else {
// 			 					    	$(this).children('input[type=file]').attr({
// 			 					    	    'data-validation':'mime',  // required - cf http://formvalidator.net/#file-validators
// 			 					    	    'data-validation-allowing':'jpg' // rather PDF etc!!
// 			 					    	});
 			 					    }
 			 					});
 			 				
 			   	<?php 
 			
 		    	/*************************************
 			 		 * CONDITIONAL CODE 
 			 		 * for Profile Group 1 (= Profil Kinoïte)
 			 		 ******************************
 			 		*/
 			
 			
 			if ( bp_get_current_profile_group_id() == 1 ) {
 				if ( current_user_can('subscriber') ) {
				 			
				 				// Une fois coché, on désactive l'option Réalisateur: 
				 					// voir https://bitbucket.org/ms-studio/kinogeneva/issues/18/inscriptions-section-r-alisateur
				 			
				 			$kino_disable_real_checkbox = false;
				 			
				 				/* Test for Groups: 
				 				 * group-real-platform-pending? 
				 				 * group-real-platform?
				 				 * group-real-platform-rejected
				 				*/
				 				
				 				// build ID arrays:
				 				
				 					$ids_group_real_platform = get_objects_in_term( 
				 						$kino_fields['group-real-platform'], 
				 						'user-group' 
				 					);
				 					$ids_group_real_platform_pending = get_objects_in_term( 
				 						$kino_fields['group-real-platform-pending'], 
				 						'user-group' 
				 					);
				 					$ids_group_real_platform_rejected = get_objects_in_term( 
				 						$kino_fields['group-real-platform-rejected'], 
				 						'user-group' 
				 					);
				 				
				 				if ( in_array( $userid, $ids_group_real_platform ) ) {
				 				
				 							$kino_disable_real_checkbox = true;
				 							
				 							$kino_real_notification = 'Votre statut de réalisateur-trice <b>sur la plateforme</b> est validé.';
				 				
				 				} else if ( in_array( $userid, $ids_group_real_platform_pending ) ) {
				 				
				 							// $kino_disable_real_checkbox = true;
				 							
				 							$kino_real_notification = 'Votre statut de réalisateur-trice <b>sur la plateforme</b> est <b>en attente de validation</b>.';
				 				
				 				} else if ( in_array( $userid, $ids_group_real_platform_rejected ) ) {
				 				
				 							$kino_disable_real_checkbox = true;
				 							
				 							$kino_real_notification = '&nbsp;';
				 				
				 				}
				 			
				 			
				 			if ( $kino_disable_real_checkbox == true ) {
				 				
				 				?>
				 					// Dans "Profil Kinoïte", option Réalisateur-trice:
				 					//$('#profile-edit-form div.field_<?php echo $kino_fields['profile-role']; ?> input[value="Réalisateur-trice"]').prop('disabled', true);
				 					$('#profile-edit-form div.field_<?php echo $kino_fields['profile-role']; ?> label[for="field_<?php echo $kino_fields['profile-role-real']; ?>"]').hide();
				 					
				 					<?php
				 			}
				 			
				 			if ( !empty($kino_real_notification) ) {
				 					
				 					?>
				 						$('#profile-edit-form div.field_<?php echo $kino_fields['profile-role']; ?> p.description').html('<?php echo $kino_real_notification; ?>');
				 						<?php
				 			} 
				 			
		 			} // if subscriber
		 	} // if edit group id = 1
		 	
		 	
		 	/*************************************
		 			 * CONDITIONAL CODE 
		 			 * for Profile Group 16 (= Aide Bénévole)
		 		******************************
		 			*/
		 			
		 if ( bp_get_current_profile_group_id() == 16 ) {
		 		
		 		// rendre obligatoire les cases "Aide Bénévole: activités Kinogeneva"
		 		// $kino_fields['benevole-kabaret']
		 		
		 		// http://formvalidator.net/#default-validators_checkboxgroup
		 				 		

//		 			$("[name='field_  ... []']:eq(0)") // echo $kino_fields['benevole-kabaret'];
//		 			  .valAttr('','validate_checkbox_group')
//		 			  .valAttr('qty','min 1')
//		 			  .valAttr('error-msg','Veuillez choisir au moins un élément');
		 		
		 		// NOTE: validation does not work, see
		 		// https://bitbucket.org/ms-studio/kinogeneva/issues/71/bug-aide-b-n-vole
		 		
		 }
 			
 			
 			/*************************************
 				 * CONDITIONAL CODE 
 				 * for Profile Group 15 (= Kino Kabaret 2016)
 			******************************
 				*/
 			
 			
 			if ( bp_get_current_profile_group_id() == 15 ) {
 				
 				if ( current_user_can('subscriber') ) {
 							
 							// Désactiver l'option "Réalisateur", une fois la demande soumise
 							
 							$kabaret_disable_real_checkbox = false;
 							
				 					/* test for Groups: 
				 					 * group-real-kabaret-pending? 
				 					 * group-real-kabaret?
				 					 * group-real-kabaret-rejected
				 					*/
				 					
				 					// build ID arrays:
				 					
				 					$ids_group_real_kabaret = get_objects_in_term( 
				 						$kino_fields['group-real-kabaret'], 
				 						'user-group' 
				 					);
				 					$ids_group_real_kabaret_pending = get_objects_in_term( 
				 						$kino_fields['group-real-kabaret-pending'], 
				 						'user-group' 
				 					);
				 					$ids_group_real_kabaret_rejected = get_objects_in_term( 
				 						$kino_fields['group-real-kabaret-rejected'], 
				 						'user-group' 
				 					);
				 					
				 					if ( in_array( $userid, $ids_group_real_kabaret ) ) {
				 					
				 								$kabaret_disable_real_checkbox = true;
				 								
				 								$kino_real_kab_notification = 'Vous êtes réalisateur-trice <b>pour le Kino Kabaret</b>.<br/><br/>';
				 					
				 					} else if ( in_array( $userid, $ids_group_real_kabaret_pending ) ) {
				 					
				 								// $kabaret_disable_real_checkbox = true;
				 								
				 								$kino_real_kab_notification = 'Votre statut de réalisateur-trice <b>pour le Kino Kabaret</b> est <b>en attente de validation</b>.<br/><br/>';
				 					
				 					} else if ( in_array( $userid, $ids_group_real_kabaret_rejected ) ) {
				 					
				 								$kabaret_disable_real_checkbox = true;
				 								
				 								$kino_real_kab_notification = '&nbsp;';
				 					
				 					}
				 				
				 				
				 			if ( $kabaret_disable_real_checkbox == true ) {
				 			
				 				?>
				 						
				 					// $('#profile-edit-form #field_<?php echo $kino_fields['role-kabaret-real']; ?>').prop('disabled', true);
				 					
				 					// $('#profile-edit-form div.field_<?php echo $kino_fields['role-kabaret']; ?> input[value="Réalisateur-trice"]').prop('disabled', true);
				 					
				 					$('#profile-edit-form div.field_<?php echo $kino_fields['role-kabaret']; ?> label[for="field_<?php echo $kino_fields['role-kabaret-real']; ?>"]').hide();
				 					
				 				<?php
				 			}
				 			
				 			if ( !empty($kino_real_kab_notification) ) {
				 						
				 						?>
				 							 $('#profile-edit-form div.field_<?php echo $kino_fields['role-kabaret']; ?> p.description').prepend('<?php echo $kino_real_kab_notification; ?>');
				 							<?php
				 			} 	
				 				
				} // if user = subscriber
				
				
				// Interaction: 
				// montrer les Checkbox si Realisateur est "Checked":
					
					?>
					
					$("input#field_<?php echo $kino_fields['role-kabaret-real']; ?>").click(function() {
					    if($(this).is(":checked")) // "this" refers to the element that fired the event
					    {
					        // alert('home is checked');
					        $('#profile-edit-form div.field_<?php echo $kino_fields['session-un']; ?>').show();
					        $('#profile-edit-form div.field_<?php echo $kino_fields['session-deux']; ?>').show();
					        $('#profile-edit-form div.field_<?php echo $kino_fields['session-trois']; ?>').show();
					        // require validation
					        $("#profile-edit-form div.field_<?php echo $kino_fields['session-un']; ?> select").attr('data-validation', 'required');
					        $("#profile-edit-form div.field_<?php echo $kino_fields['session-deux']; ?> select").attr('data-validation', 'required');
					        $("#profile-edit-form div.field_<?php echo $kino_fields['session-trois']; ?> select").attr('data-validation', 'required');
					        
					    } else {
					    		$('#profile-edit-form div.field_<?php echo $kino_fields['session-un']; ?>').hide();
					    		$('#profile-edit-form div.field_<?php echo $kino_fields['session-deux']; ?>').hide();
					    		$('#profile-edit-form div.field_<?php echo $kino_fields['session-trois']; ?>').hide();
					    		// remove validation
					    		$("#profile-edit-form div.field_<?php echo $kino_fields['session-un']; ?> select").removeAttr('data-validation');
					    		$("#profile-edit-form div.field_<?php echo $kino_fields['session-deux']; ?> select").removeAttr('data-validation');
					    		$("#profile-edit-form div.field_<?php echo $kino_fields['session-trois']; ?> select").removeAttr('data-validation');
					    }
					});
					
					<?php
				
				
 		} // END if edit group ID = 15
 		
 		
   		// conditional part for CV field
   		
   		if (in_array( "realisateur", $kino_user_role )) {
   		
   			// test if file exists
   			?>
   			
   			$('div.field_858.field_type_file').each(function() {
   					    if ($(this).children('a').length) {
   					    // has link = file exists = do nothing
   					    } else {
   					    	$("div.field_858 input[type=file]").attr('data-validation', 'required');
   					    	$("div.field_858 label[for=field_858]").text("C.V. (obligatoire)");
   					    }
   					});
   			
   			<?php
   		}
   		
   		
   		// champ "Prénom Nom"
   		// 
   		// https://bitbucket.org/ms-studio/kinogeneva/issues/45/ 
   		// On teste si le champ "Prénom Nom" contient le nom d'utilsateur  		
   		
   		$kino_fullname = bp_get_profile_field_data( array(
   				'field'   => '1',
   				'user_id' => $userid
   		) );
   		
   		$kino_user_info = get_userdata( $userid );
   		$kino_wp_login = $kino_user_info->user_login;
   		
   		// echo "// $kino_fullname = ". $kino_fullname . " -  $kino_wp_login = " . $kino_wp_login ;
   		
   		if ( $kino_fullname == $kino_wp_login ) {
   		
   				// clear the field with Jquery!
   				?>
   				$("input#field_1").val('');
   				<?php
   				
   		}
   		
   		/*
   		 * Mixpanel Link Tracking Code:
   		 ********************************
   		
   		https://mixpanel.com/help/reference/javascript
   		https://mixpanel.com/help/reference/javascript-full-api-reference#mixpanel.track_forms
   		
   		mixpanel.track_links("#button-nav", "Clicked Edit Profile");
   		 // NOTE: doit cibler le <a>, sinon, BUG= ajoute "undefined" après l'URL!
   		 
   		mixpanel.track_forms("#profile-edit-form", "Submitted Profile Form");
   		 // NOTE: cela fait bugger la validation!
   		  **********/
   		
   		 $host = $_SERVER['HTTP_HOST'];
   		 if ( $host == 'kinogeneva.ch' ) {
   		 	// track via mixpanel
   		 
   		 	?>
   		  mixpanel.track_links('#button-nav li a', 'Clicked Edit Profile');
   		  <?php 
   		 
   		 }
   		 
   		  ?>

 });
 </script>
 <?php 
 
 		// Conditional code for Kino Kabaret 2016

 		// NOTE: We use inline CSS to prevent delay
 		
 		if ( bp_get_current_profile_group_id() == 15 ) {
 			
 			/*
 			 * WHY use javascript ??? 
 			 * Because we cannot hide a SINGLE checkbox in a list with PHP
 			 *
 			********/
 			
 			if ( !in_array( "realisateur", $kino_user_role ) ) {
 				?>
 				<style type="text/css">
 				#buddypress div.field_<?php echo $kino_fields['role-kabaret']; ?> label[for=field_<?php echo $kino_fields['role-kabaret-real']; ?>],
 				#buddypress div.field_<?php echo $kino_fields['role-kabaret']; ?> p.description
 				{
 					display:none;
 				}
 				</style>
 				<?php
 			}
 			if (!in_array( "comedien", $kino_user_role )) { 				
 				?>
 				<style type="text/css">
 				 #buddypress div.field_<?php echo $kino_fields['role-kabaret']; ?> label[for=field_<?php echo $kino_fields['role-kabaret-comed']; ?>] {
 				 	display:none;
 				 }
 				</style>
 				<?php
 			}
 			if (!in_array( "technicien", $kino_user_role )) {
 				?>
 				<style type="text/css">
 				#buddypress div.field_<?php echo $kino_fields['role-kabaret']; ?> label[for=field_<?php echo $kino_fields['role-kabaret-tech']; ?>] {
 					display: none;
 				}
 				</style>
 				<?php
 			} // technicien
 			
 			
 			/*
 			 * REMOVED: test if NO ROLE for kabaret:
 			 * This is now handled by PHP in: bp-group-tabs.php - kino_hide_some_profile_fields()
 			************/ 
 			
 			
 			if (!in_array( "realisateur-2016", $kino_user_role )) {
 					
 					/*
 					 * https://bitbucket.org/ms-studio/kinogeneva/issues/51/kino-kabaret-2016-sessions
 					 *
 					 * WHY use javascript ???
 					 * because we must be able to show the fields if user clicks "Role: Réalisateur-trice"
 					 *
 					*************/
 					
 					?>
 					<style type="text/css">
 					 #buddypress #profile-edit-form div.field_<?php echo $kino_fields['session-un']; ?>,
 					 #buddypress #profile-edit-form div.field_<?php echo $kino_fields['session-deux']; ?>,
 					 #buddypress #profile-edit-form div.field_<?php echo $kino_fields['session-trois']; ?> {
 					 	display:none;
 					 }
 					</style>
 					<?php
 				}
 			
 		} // end profile group #15
 
 echo '</style>';
 
  ?>
<?php 
	/*
	* Some jQuery corrections for the BuddyPress Profile Edit Page
	* 
	* Documentation:
	* https://bitbucket.org/ms-studio/kinogeneva/wiki/Fonctionnement-Champs-Profil.wiki#!modifications-des-champs-par-javascript
	*/
	
	// Load Field Numbers:
	$kino_fields = kino_test_fields();
	
	// $userid = bp_loggedin_user_id();
	$userid = bp_displayed_user_id();
	
	// Load User Role testing
	$kino_user_role = kino_user_participation( $userid, $kino_fields );
	
	// Note: returns Kino Kabaret role only
	
	
	/* Méthode pour désactiver les liens des onglets
	**************************************************
	* Cf ticket https://bitbucket.org/ms-studio/kinogeneva/issues/123/		
	* Proposition 1: masquer les onglets, sauf l'actuel.
	*/
	/*

<style>
	#button-nav li {
		display:  none; 		
 	}
 	
 	#button-nav li.current {
 			display:  block; 		
 	}
</style>
 */
?>
<script>
jQuery(document).ready(function($){
	
	//onglet identité: test sur le champ photo #114 => on efface le nom du fichier du champ s'il ne contient pas l'extension voulue
	$("#profile-edit-form #field_859").change(function() {	 
		if(($(this).val().indexOf('jpg') >=0) || ($(this).val().indexOf('JPG') >=0) || ($(this).val().indexOf('jpeg') >=0) || ($(this).val().indexOf('JPEG') >=0)){
			//ok
		}
		else {
			$(this).val('');
		}
	});


	
	<?php
	
	/* Méthode pour désactiver les liens des onglets
	**************************************************
	* Cf ticket https://bitbucket.org/ms-studio/kinogeneva/issues/123/		
	* Proposition 2: desactiver le HREF.
	*/
	
	if ( current_user_can('subscriber') ) {
	?>
 		$("#button-nav li a").removeAttr("href").css({'cursor': 'default', 'pointer-events' : 'none'});
	<?php
	} // test 'subscriber'
	?>

 
	// le champ Presentez-vous = limiter à 500 signes = 100 mots
	$("#profile-edit-form #field_31").attr("maxlength", "500"); 
 			
	// le champ Mes motivations à rejoindre KinoGeneva = limiter à  500 mots = 2500 signes
	$("#profile-edit-form #field_545").attr("maxlength", "2500"); 
 			
	// add target_blank to .kino-edit-profile links
	$("#profile-edit-form p.description a[href^=http]").attr('target', '_blank');
 			
 	<?php
 	// Une fois les conditions générales acceptées, on ne peut plus les modifier!		
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
		}
		else {
			/*
			$(this).children('input[type=file]').attr('data-validation', 'required');
			$(this).children('input[type=file]').attr({
				'data-validation':'mime',  // required - cf http://formvalidator.net/#file-validators
				'data-validation-allowing':'jpg' // rather PDF etc!!
			});*/
		}
	});
					
		<?php 
	
		/*************************************
		 * CONDITIONAL CODE 
		 * for Profil Kinoïte
		 ******************************
		*/
		
		//04.12.2016 issue #125 => les champs ont été déplacés dans le tab 19
		if ( bp_get_current_profile_group_id() == 19 ) {
			
			//demande 2017-2018 d'afficher le texte participe au kabaret en rouge ?>
			$('#profile-edit-form div.field_<?php echo $kino_fields['kabaret']; ?>').css({color: "#c11119"});
			<?php
			
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
				
				//statut du réal
 				if ( in_array( $userid, $ids_group_real_platform ) ) {
					$kino_disable_real_checkbox = true;
					$kino_real_notification = 'Votre statut de réalisateur-trice <b>sur la plateforme</b> est validé.';
 				
 				} else if ( in_array( $userid, $ids_group_real_platform_pending ) ) {
					$kino_disable_real_checkbox = true;
					$kino_real_notification = 'Votre statut de réalisateur-trice <b>sur la plateforme</b> est <b>en attente de validation</b>.';
 				
 				} else if ( in_array( $userid, $ids_group_real_platform_rejected ) ) {
					$kino_disable_real_checkbox = true;
					$kino_real_notification = '&nbsp;';
 				}
 				
				//tél. du 15/11/2017: sandrane me demande de ré-ouvrir cette option
				//cacher la case à cocher
				/*
	 			if ( $kino_disable_real_checkbox == true ) {
				
					// Dans "Profil Kinoïte", option Réalisateur-trice:
		?>
		
					// ATTENTION BUG NAVIGATEUR: si on fait DISABLED sur une liste de checkbox, 
					// elles vont être vidées lors de la soumission!
					
					// Etrangement, ce n'est pas le cas pour des cases à cocher uniques.
					
					// Ce qui fonctionne:  faire display: none
	 						
					$('#profile-edit-form div.field_<?php echo $kino_fields['profile-role']; ?> label[for="field_<?php echo $kino_fields['profile-role-real']; ?>"]').css({display: "none"});
	 					
	 	<?php
	 			}*/
	 			
				//afficher le message
	 			if ( !empty($kino_real_notification) ) {
	 	?>
					$('#profile-edit-form div.field_<?php echo $kino_fields['profile-role']; ?> p.description').html('<?php echo $kino_real_notification; ?>');
		<?php
				} 
				 			
	 			// Disable Kino Kabaret Checkbox if Checked...
	 			
	 			// test if KinoKabaret checked
	 			$ids_of_kino_pending = get_objects_in_term(
 					$kino_fields['group-kino-pending'] , 
 					'user-group' 
	 			);
				 				
		 		if ( in_array( $userid, $ids_of_kino_pending ) ) {
 		?>
 					$('#profile-edit-form input#check_acc_field_<?php echo $kino_fields['kabaret']; ?>').prop('disabled', true);
 		<?php
		 		}				
				 			
		 	} // if subscriber

		} // if edit group id = 19 (Profil Kinoïte)
		 	
		 	
	 	/*************************************
		 * CONDITIONAL CODE 
		 * for Profile Group 10 (= Identité)
		 ******************************
		 */
		
		 if ( bp_get_current_profile_group_id() == 10 ) {
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
			
	 		// conditional part for CV field
 			if (in_array( "realisateur", $kino_user_role ) || in_array( "benevole", $kino_user_role )) {
 			
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
		 
		 } //fin de bp_get_current_profile_group_id() == 10
		 	
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
		 		
		 		// Disable Bénévole Checkbox if Checked... + message de contacter l'équipe!
	 			$ids_of_benevoles = get_objects_in_term( 
	 				$kino_fields['group-benevoles-kabaret'] , 
	 				'user-group' 
	 			);
				 			
	 			if ( in_array( $userid, $ids_of_benevoles ) ) {		
		?>
					//$('#profile-edit-form input#check_acc_field_<?php echo $kino_fields['benevole']; ?>').prop('disabled', true);						 						
					$('#profile-edit-form div.field_<?php echo $kino_fields['benevole-kabaret']; ?> label[for="field_<?php echo $kino_fields['benevole-kabaret-yes']; ?>"]').css({display: "none"});
					$('#profile-edit-form div.field_<?php echo $kino_fields['benevole-kabaret']; ?>').append('<b>Vous vous êtes inscrit comme bénévole pour cette édition du Kino Kabaret</b>. Pour des questions d\'organisation, Si vous ne souhaitez plus être bénévole pour le Kino Kabaret, merci de contact l\'équipe!');
		<?php
	 			}
		 		
		 		
		 }
 			
 			
		/*************************************
		 * CONDITIONAL CODE 
		 * for Profile Group 17 (= Kino Kabaret 2017)
		 ******************************
		 */
 			
 			
		if ( bp_get_current_profile_group_id() == 17 ) {
			
			/**
			 * D'abord, on teste si le profil est complet.
			 * Si c'est le cas, on désactive tous les champs!
			 *
			 *
			 * Sinon, diverses opérations:
			 * Kab.Dispo.Oblig
			 * Kab.Role.Oblig
			*/
 				
			$ids_group_kino_complete = get_objects_in_term( 
				$kino_fields['group-kino-complete'], 
				'user-group' 
			);
 			
 			//profil complet?
			if ( in_array( $userid, $ids_group_kino_complete ) ) {
				
				if ( current_user_can('subscriber') ) {
				
		?>
 					
 					// désactiver l'édition de tous les champs!
 					
 					$('#profile-edit-form input[type="checkbox"]').prop('disabled', true);
					
					$('#profile-edit-form .field_type_selectbox select').prop('disabled', true);
					
					$('#profile-edit-form .field_type_textbox input[type="text"]').prop('disabled', true);
					
					$('#profile-edit-form .submit input[type="submit"]').prop('disabled', true).prop('value', 'Inscription Terminée');
 					
		<?php		
 					
 				} // end if current_user_can('subscriber')
 				
 			}
 			//profil incomplet
 			else {
 				
				/**
					 * Profil non complet: diverses opérations:
					 * Kab.Dispo.Oblig
					 * Kab.Role.Oblig
					*/
 						
				/*
					 * Cf https://bitbucket.org/ms-studio/kinogeneva/issues/116/
					
					 * Kab.Dispo.Oblig:  Mettre "Mes disponibilités (obligatoires)" en rouge si aucune date n'est cochée (le caractère obligatoire du champ ne fonctionne pas)
					
					 * Kab.Role.Oblig:  Rendre obligatoire "je m'inscris au kino kabaret en tant que"
					
					 * Kab.Dispo.Oblig 
					   Méthode : 
					   - On teste le groupe de champ. = defined in $kino_fields['dispo'] = 1917
					   - Si le groupe est entièrement vide, on affiche l'avertissement par Javascript.
					   Ceci étant, on ne force pas la validation, pour éviter des bugs.
					*/
 							
				$kino_field_dispo = bp_get_profile_field_data( array(
						'field'   => $kino_fields['dispo'],
						'user_id' => $userid
				 ) );
 							
				if ( empty( $kino_field_dispo ) ) {
					
					// rien sélectionné : 
					// afficher avertissement!
					// + rendre le groupe obligatoire
					
					kino_validate_chekbox( $kino_fields['dispo'] );
				
				} // if empty($kino_field_dispo)

				/*
					 * Kab.Role.Oblig
					 * = 1872 defined in $kino_fields['role-kabaret']
					*/
 								
				$kino_role_kab = bp_get_profile_field_data( array(
					'field'   => $kino_fields['role-kabaret'],
					'user_id' => $userid
				) );
 								 				
				if ( empty( $kino_role_kab ) ) {
					
					// rien sélectionné : 
					
					kino_validate_chekbox( $kino_fields['role-kabaret'] );
				
				} // if empty($kino_field_dispo)
 								
 								
 				
 				} // fin test si profil complet
 				
 				// Désactiver l'option "Réalisateur", une fois la demande soumise
 				if ( current_user_can('subscriber') ) {
 							
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
 					
						$kabaret_disable_real_checkbox = true;
						
						$kino_real_kab_notification = 'Votre statut de réalisateur-trice <b>pour le Kino Kabaret</b> est <b>en attente de validation</b>.<br/><br/>';
 					
 					} else if ( in_array( $userid, $ids_group_real_kabaret_rejected ) ) {
 					
						$kabaret_disable_real_checkbox = true;
						
						$kino_real_kab_notification = '&nbsp;';
 					
 					}
				 				
		 				
		 			if ( $kabaret_disable_real_checkbox == true ) {
		 			
		 				?>
		 						
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
				        // show fields
				        
				        $('#profile-edit-form div.field_<?php echo $kino_fields['session-un']; ?>').show();
				        $('#profile-edit-form div.field_<?php echo $kino_fields['session-deux']; ?>').show();
				        $('#profile-edit-form div.field_<?php echo $kino_fields['session-trois']; ?>').show();
				        
				        // require validation
				        $("#profile-edit-form div.field_<?php echo $kino_fields['session-un']; ?> select").attr('data-validation', 'required');
				        $("#profile-edit-form div.field_<?php echo $kino_fields['session-deux']; ?> select").attr('data-validation', 'required');
				        $("#profile-edit-form div.field_<?php echo $kino_fields['session-trois']; ?> select").attr('data-validation', 'required');
				        
				    } else {
					    		
			    		// hide fields
			    		
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


 		} // END if edit group ID 17 - Kino Kabaret 2017
 		
 
   		
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

 		// Conditional CSS styles for Kino Kabaret 2017
 		// NOTE: We use inline CSS to prevent delay
 		
 		if ( bp_get_current_profile_group_id() == 17 ) {
 			
 			/*
 			 * WHY use CSS ??? 
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
 			
 			
 			if (!in_array( "realisateur-kab", $kino_user_role )) {
 					
 					/* 
 					 * Hide the session fields
 					 * https://bitbucket.org/ms-studio/kinogeneva/issues/51/kino-kabaret-2016-sessions
 					 *
 					 * WHY use CSS ???
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
 			
 		} // end profile group #17
 		
 		
//cacher le dernier onglet
/*
<style type="text/css">
#buddypress ul.button-nav li:last-child,
#buddypress #profile-edit-form .editfield.field_2097 {
	display:none;
}
</style>
*/
?>

<?php 

/*
 * Message Dashboard Edit Profile
 
 * Message à afficher pour aider à compléter le profil
 * 
**************************************/

 function kino_edit_profile_notifications( $userid ) {
 	
 	if ( empty( $userid ) ) {
 		$userid = bp_loggedin_user_id();
 	}
 	
 	$userdata = get_userdata($userid);
 	
 	$kino_notification = '';
 	
 	$kino_notification_email = '';
 	
 	$kino_fields = kino_test_fields();
 	
 	// run our test battery...
 	
 	$kino_user_role = kino_user_participation( $userid, $kino_fields );
 	
 	// load user info
 	$user = get_user_by( 'id', $userid );

 	/*
 		 * Before performing tests, (re)organise groups for Réalisateurs
 		 *
 		 * - real-platform-pending
 		 * - real-kabaret-pending
 		***/
 			
 			if ( in_array( "realisateur", $kino_user_role ) ) {
 			
 			/* signifie: 
 			 * la personne a coché le "profile-role" Réal.
 			 * cf bp-user-fields.php - l.43
 			*/
 						
 						// build arrays:
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
 						
 				 		// test:
 				 		if ( in_array( $userid, $ids_group_real_platform ) ) {
 				 		  // accepted: do nothing
 				 		} else if ( in_array( $userid, $ids_group_real_platform_pending ) ) {
 				 		  // do nothing
 				 		  
 				 		  // TROUBLESHOOTING: 
 				 		  // test if user is also in "realisateur-kab"
 				 		  if ( in_array( "realisateur-kab", $kino_user_role ) ) {
 				 		  	// platform + kino = all OK
 				 		  } else {
 				 		  	// platform ONLY: probably mistake !
 				 		  	
 				 		  } // end troubleshooting
 				 		  
 				 		} else if ( in_array( $userid, $ids_group_real_platform_rejected ) ) {
 				 			  // rejected: do nothing
 				 		} else {
 				 				// New candidate!
 				 				// move to group: real-platform-pending
 				 				kino_add_to_usergroup( $userid, $kino_fields['group-real-platform-pending'] );
 				 		}
 				
 		} // end testing "realisateur"
 		
 		
 		if ( in_array( "realisateur-kab", $kino_user_role ) ) {
 			
 			/* signifie: 
 				 * la personne a coché le 'role-kabaret' Réal.
 				 * cf bp-user-fields.php - l.175
 				*/
 			
 							
 							// build arrays:
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
 					
 					 		// test:
 					 		if ( in_array( $userid, $ids_group_real_kabaret ) ) {
 					 			  // accepted: do nothing
				 			} else if ( in_array( $userid, $ids_group_real_kabaret_pending ) ) {
				 			  // do nothing
				 			} else if ( in_array( $userid, $ids_group_real_kabaret_rejected ) ) {
				 				  // do nothing
				 			} else {
				 				// New candidate!
				 				// move to group: real-kabaret-pending
				 				kino_add_to_usergroup( $userid, 
				 						$kino_fields['group-real-kabaret-pending'] );
//				 				kino_add_to_mailpoet_list( $userid, 
//				 				  	$kino_fields['mailpoet-real-kabaret-pending'] );
 					 		}
 					
 			} // end testing "realisateur-kab"
 			
 			
 			// test if Bénévole?
 			
 			if ( in_array( "benevole-kabaret", $kino_user_role ) ) {
 				// already in group?
 						$ids_group_benevoles = get_objects_in_term( 
 							$kino_fields['group-benevoles-kabaret'], 
 							'user-group' 
 						);
 						if ( !in_array( $userid, $ids_group_benevoles ) ) { 
 							// add to group!
 							kino_add_to_usergroup( $userid, $kino_fields['group-benevoles-kabaret'] );
 							// add to mailing list!
// 							kino_add_to_mailpoet_list( $userid, $kino_fields['mailpoet-benevoles'] );
 						}
 			}
 			
 		
 			// Test if user is looking for / is proposing an appartment.
 			
 			$kinoite_cherche_logement = bp_get_profile_field_data( array(
 					'field'   => $kino_fields["cherche-logement"],
 					'user_id' => $userid
 			) );
 			// if OUI = add to group! $kino_fields['group-cherche-logement']
 			if ( ( $kinoite_cherche_logement == "OUI" ) ) {
 						kino_add_to_usergroup( $userid, $kino_fields['group-cherche-logement'] );
 			}
 			
 			$kinoite_offre_logement = bp_get_profile_field_data( array(
 						'field'   => $kino_fields["offre-logement"],
 						'user_id' => $userid
 				) );
 				// if OUI = add to group! $kino_fields['group-cherche-logement']
 				if ( ( $kinoite_offre_logement == "OUI" ) ) {
 							kino_add_to_usergroup( $userid, $kino_fields['group-offre-logement'] );
 				}
 	
 	// Massive Conditional Testing
 		
 		// prepares arrays:
 		// IDs of complete profiles
 			$ids_group_kino_complete = get_objects_in_term( 
 				$kino_fields['group-kino-complete'], 
 				'user-group' 
 			);
 	
 	// DO WHILE structure
 	// src: http://stackoverflow.com/questions/7468836/any-way-to-break-if-statement-in-php
 	
 	do {
 		 
 		// Q -1: is user in group "Participants Kino (profil complet)"?
 		
 		if ( in_array( $userid, $ids_group_kino_complete ) ) { 
			// already complete, do nothing
 			break;
 		}
 		
 		/*Q0 : taking part in Kino Kabaret? */
 		
 	  if( !in_array( "kabaret-2017", $kino_user_role ) ) { 
 	    
 	    // un peu de pub...
 	    $kino_notification = 'Le prochain Kino Kabaret se déroule du 8 au 19 janvier 2017! N’oubliez pas <a href="'.bp_core_get_user_domain( $userid ).'profile/edit/group/1/">de vous inscrire par ici</a>, et d’enregistrer tous les onglets jusqu’à celui du Kino Kabaret.';
 	    
 	    break; }
 		
 		// if we continue = the user joins the Kabaret
 		
 		kino_add_to_usergroup( $userid, $kino_fields['group-kino-pending'] );
 		
 		$kino_notification_email .= "Votre inscription au Kino Kabaret a bien été prise en compte.";
 			
 	  /* Q1 : is the ID part complete? */
 	
 	  if( !in_array( "id-complete", $kino_user_role ) ) { 
 	  		
 	  		// user subscribed but:
 	  		// id section = incomplete
 	  		
 	  		$kino_notification = 'Complétez votre profil (identité).';
 	  		break; }
 		
 		/* Q1b : is the Photo uploaded? */
 		
 		$kinoite_id_photo = bp_get_profile_field_data( array(
 					'field'   => $kino_fields["id-photo"],
 					'user_id' => $userid ) );
 		if ( empty($kinoite_id_photo) ) {
 			// photo is missing!
 			$kino_notification = 'Complétez votre profil (identité) en ajoutant votre photo.';
 			break; }
 			
 		/* Q1b : is the CV uploaded? */
 		
 		if ( in_array( "realisateur", $kino_user_role ) ) {
 		
 			$kinoite_id_cv = bp_get_profile_field_data( array(
 						'field'   => $kino_fields["id-cv"],
 						'user_id' => $userid ) );
 			if ( empty($kinoite_id_cv) ) {
 				// CV is missing!
 				$kino_notification = 'Complétez votre profil (identité) en ajoutant votre CV.';
 				break; }
 		}
 		
 	  /* Q2 : is "Compétence Comédien" complete? */
 	  
 	  if( in_array( "comedien", $kino_user_role ) && !in_array( "comedien-complete", $kino_user_role ) ) { 
 	   		$kino_notification = 'Complétez votre profil (Compétence Comédien).';
 	   		break; }
 	   		
 		
 		// Q3 : is "Compétence Tech" complete?
 		
 		if( in_array( "technicien", $kino_user_role ) && !in_array( "technicien-complete", $kino_user_role ) ) { 
 		  		$kino_notification = 'Complétez votre profil (Compétence Technicien).';
 		  		break; }
 	  		
		// Q4 : is "Compétence Réal" complete?
		
		if ( in_array( "realisateur", $kino_user_role ) ) {
		
			if ( in_array( "realisateur-complete", $kino_user_role ) ) {
				
				// Cette personne vient de compléter la section "Compétence Réalisateur"!
				
				$kino_notification_email .= "La participation en tant que réalisateur-trice est limitée à 12 réalisateur-trices par session (au total 36 réalisateur-trices). Pour les réalisateur-trices étranger-ères inscrits avant le 18/12/2016 minuit, nous vous ferons part du choix de la direction artistique le 21 décembre. Pour tous les autres réalisateurs-trices (date limite d’inscription le 29/12/2016 minuit) la sélection finale sera communiquée le 31 décembre 2016.";
			
			} else {
				
				$kino_notification = 'Complétez votre profil (Compétence Réalisateur).';
					break;
			}
		}  
		
		
		// Q5 : is "Aide Bénévole" complete?
		
		if( in_array( "benevole", $kino_user_role ) && !in_array( "benevole-complete", $kino_user_role ) ) {
		 
		 		$kino_notification = 'Merci de vous proposer comme bénévole. Complétez votre profil d’aide bénévole.';
		 		break; }
		
		
		// Q6 : is "Kino Kabaret 2016" complete?
		if( in_array( "kabaret-2017", $kino_user_role ) && !in_array( "kabaret-complete", $kino_user_role ) ) { 
		 		$kino_notification = 'Complétez les informations relatives à votre participation au Kino Kabaret.';
		 		break; }
		
			
		// If we continue... User just completed profile - give notifications !
			
			// Q7 : is "Photo du Profil" already complete?
			
			if( in_array( "avatar-complete", $kino_user_role ) ) {
			 
			 		$kino_notification = 'Votre profil est complet. Vous pouvez régulièrement mettre à jour les informations de votre profil en vous connectant avec votre mot de passe.
			 			<script>
			 				mixpanel.track(
			 				    "Completed Profile"
			 				);
			 			</script>
			 		';
			 		
			 } else {
			 		
			 		$kino_notification = 'Votre profil est complet. Vous pouvez régulièrement mettre à jour les informations de votre profil en vous connectant avec votre mot de passe.
			 		
PS: pensez à <a href="'.bp_core_get_user_domain( $userid ).'profile/change-avatar/">choisir une photo d’avatar</a>!
			 		<script>
			 				mixpanel.track(
			 				    "Completed Profile"
			 				);
			 			</script>
			 		';
					 
			 }
				
				// Final Actions for complete user:
				
				// Action 1 = add user to group Kino Complete
				// ****************************************
				
				kino_add_to_usergroup( $userid, $kino_fields['group-kino-complete'] );
				
				// Action 2 = send email notification!
				// ****************************************
				
				$headers[] = 'From: KinoGeneva <onvafairedesfilms@kinogeneva.ch>';
				
				$kino_notification_email .= '
				
Nous nous réjouissons de vous accueillir dans notre nouveau KinoLab à la Fonderie Kugler ( 19 av. de la Jonction, 1205 Genève - entrée par l’arrière du bâtiment) pour la soirée de lancement du Kabaret le dimanche 8 janvier à 17h. Finalisation des inscriptions et paiement des frais de participation (en liquide) dès 14h.


Pour toutes les informations pratiques et le programme du Kino Kabaret 2017, voir: <a href="https://kinogeneva.ch/informations-pratiques/" style="color:#c11119;">https://kinogeneva.ch/informations-pratiques/</a>

Pour toute question relative à votre inscription, n’hésitez pas à contacter Alex à l’adresse ci-dessous.';
				
				$host = $_SERVER['HTTP_HOST'];
				
				if ( $host == 'kinogeneva.ch' ) {
				
					$to = $user->user_email;
					$headers[] = 'Bcc: Manu <ms@ms-studio.net>';
					$headers[] = 'Bcc: KinoGeneva <onvafairedesfilms@kinogeneva.ch>';
				
				} else {
				
					$to = 'webmaster@kinogeneva.com';
					$headers[] = 'Bcc: Manu <ms@ms-studio.net>';
					$kino_notification_email .= '
					
					(Debug: message envoyé depuis le serveur test, page '.$_SERVER[REQUEST_URI].', à '. date( 'H:i:s', time() ) .')';
				
				}
				
				 wp_mail( 
				 	$to,  // $to
				 	'[KinoGeneva] Confirmation pour '.$user->display_name, // $subject
				 	$kino_notification_email, 
				 	$headers 
				 );
				 
				 // Add user to Mailpoet list: 
//				 kino_add_to_mailpoet_list( $userid, 
//				   $kino_fields['mailpoet-participant-kabaret']);
				 // Remove from incomplete list:
//				 kino_remove_from_mailpoet_list( $userid, 
//				   $kino_fields['mailpoet-participant-kabaret-incomplet'] );
				 
				break;
		
		
 	} while (0);
 	
 	
 	return $kino_notification;
 	
 }

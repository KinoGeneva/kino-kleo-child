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
 						$ids_group_real_platform_canceled = get_objects_in_term( 
 							$kino_fields['group-real-platform-canceled'], 
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
 				 		} else if ( in_array( $userid, $ids_group_real_platform_canceled ) ) {
 				 			  // canceled: do nothing
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
							$ids_group_real_kabaret_canceled = get_objects_in_term( 
								$kino_fields['group-real-kabaret-canceled'], 
								'user-group' 
							);
 					
 					 		// test:
 					 		if ( in_array( $userid, $ids_group_real_kabaret ) ) {
 					 			  // accepted: do nothing
				 			} else if ( in_array( $userid, $ids_group_real_kabaret_pending ) ) {
				 			  // do nothing
				 			} else if ( in_array( $userid, $ids_group_real_kabaret_rejected ) ) {
				 				  // do nothing
				 			} else if ( in_array( $userid, $ids_group_real_kabaret_canceled ) ) {
				 				  // do nothing
				 			} else {
				 				// New candidate!
				 				// move to group: real-kabaret-pending
				 				kino_add_to_usergroup( $userid, 
				 						$kino_fields['group-real-kabaret-pending'] );
				 				//mailpoet
				 				if( $mailpoet_id = getMailpoetId( $userid ) ) {
									kino_add_to_mailpoet_list(
										$mailpoet_id, 
										$kino_fields['mailpoet-real-kabaret-pending'] 
									);
								}
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
 							//mailpoet
			 				if( $mailpoet_id = getMailpoetId( $userid ) ) {
								kino_add_to_mailpoet_list(
									$mailpoet_id, 
									$kino_fields['mailpoet-benevoles'] 
								);
							}

							//mail à sandrane #227
							$kino_email_new_benevole = 'L\'utilisateur <a href="'. bp_core_get_user_domain( $userid ) .'">'. $user->display_name .'</a> s\'est inscrit comme bénévole pour le kabaret 2018';
								$header = 'From: KinoGeneva <onvafairedesfilms@kinogeneva.ch>';
								$to = 'sandrane@kinogeneva.ch';
								  wp_mail(
									$to,  // $to
									'[KinoGeneva] Nouveau bénévole: '.$user->display_name, // $subject
									$kino_email_new_benevole, 
									$header
								 );
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
 			//sinon supprimer du groupe
			else {
				kino_remove_from_usergroup( $userid, $kino_fields['group-cherche-logement'] );
			}
 			
 			$kinoite_offre_logement = bp_get_profile_field_data( array(
 						'field'   => $kino_fields["offre-logement"],
 						'user_id' => $userid
 				) );
 				// if OUI = add to group! $kino_fields['group-cherche-logement']
 				if ( ( $kinoite_offre_logement == "OUI" ) ) {
 					kino_add_to_usergroup( $userid, $kino_fields['group-offre-logement'] );
 				}
 				//sinon supprimer du groupe
 				else {
					kino_remove_from_usergroup( $userid, $kino_fields['group-offre-logement'] );
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
 		
			$kino_notification = '';
			// already complete, do nothing
 			break;
 		
 		}
 		
 		/*Q0 : taking part in Kino Kabaret? */
 		
 	  if( !in_array( "kabaret-2018", $kino_user_role ) ) { 
 	    
 	    // un peu de pub...
 	    $kino_notification = '<p>Le prochain Kino Kabaret se déroule 13 au 26 janvier 2018! N’oubliez pas <a href="'.bp_core_get_user_domain( $userid ).'profile/edit/group/19/">de vous inscrire par ici</a>, et d’enregistrer tous les onglets jusqu’à celui du Kino Kabaret.</p>';
 	    
 	    break; }
 		
 		// if we continue = the user joins the Kabaret
 		
 		kino_add_to_usergroup( $userid, $kino_fields['group-kino-pending'] );
 		
 		$kino_notification_email .= "Votre inscription au Kino Kabaret a bien été prise en compte.";
 			
 	  /* Q1 : is the ID part complete? */
 	
 	  if( !in_array( "id-complete", $kino_user_role ) ) { 
 	  		
 	  		// user subscribed but:
 	  		// id section = incomplete
 	  		
 	  		$kino_notification = '<p>Complétez votre profil (identité).</p>';
 	  		break; }
 		
 		/* Q1b : is the Photo uploaded? */
 		
 		$kinoite_id_photo = bp_get_profile_field_data( array(
 					'field'   => $kino_fields["id-photo"],
 					'user_id' => $userid ) );
 		if ( empty($kinoite_id_photo) ) {
 			// photo is missing!
 			$kino_notification = '<p>Complétez votre profil (identité) en ajoutant votre photo.</p>';
 			break; }
 			
 		/* Q1b : is the CV uploaded? */
 		
 		if ( in_array( "realisateur", $kino_user_role ) || in_array( "benevole", $kino_user_role ) ) {
 		
 			$kinoite_id_cv = bp_get_profile_field_data( array(
 						'field'   => $kino_fields["id-cv"],
 						'user_id' => $userid ) );
 			if ( empty($kinoite_id_cv) ) {
 				// CV is missing!
 				$kino_notification = '<p>Complétez votre profil (identité) en ajoutant votre CV.</p>';
 				break; }
 		}
 		
 	  /* Q2 : is "Compétence Comédien" complete? */
 	  
 	  if( in_array( "comedien", $kino_user_role ) && !in_array( "comedien-complete", $kino_user_role ) ) { 
 	   		$kino_notification = '<p>Complétez votre profil (Compétence Comédien).</p>';
 	   		break; }
 	   		
 		
 		// Q3 : is "Compétence Tech" complete?
 		
 		if( in_array( "technicien", $kino_user_role ) && !in_array( "technicien-complete", $kino_user_role ) ) { 
 		  		$kino_notification = '<p>Complétez votre profil (Compétence Technicien).</p>';
 		  		break; }
 	  		
		// Q4 : is "Compétence Réal" complete?
		
		if ( in_array( "realisateur", $kino_user_role ) ) {
		
			if ( in_array( "realisateur-complete", $kino_user_role ) ) {
				
				// Cette personne vient de compléter la section "Compétence Réalisateur"!
				
				$kino_notification_email .= "La participation en tant que réalisateur-trice est limitée à 12 réalisateur-trices par session (au total 36 réalisateur-trices). La date limite d’inscription pour les réalisateur-trices est le 16.12.2017 à minuit. Nous vous ferons part du choix de la direction artistique le 19.12.2017. Les réalisateurs-trices devront confirmer leur participation d’ici au 6.1.2018.";
			
			} else {
				$kino_notification = '<p>Complétez votre profil (Compétence Réalisateur).</p>';
					break;
			}
		}  
		
		
		// Q5 : is "Aide Bénévole" complete?
		
		if( in_array( "benevole", $kino_user_role ) && !in_array( "benevole-complete", $kino_user_role ) ) {
		 
		 		$kino_notification = '<p>Merci de vous proposer comme bénévole. Complétez votre profil d’aide bénévole.</p>';
		 		break; }
		
		
		// Q6 : is "Kino Kabaret 2018" complete?
		if( in_array( "kabaret-2018", $kino_user_role ) && !in_array( "kabaret-complete", $kino_user_role ) ) { 
		 		$kino_notification = '<p>Complétez les informations relatives à votre participation au Kino Kabaret.</p>';
		 		break; }
		
			
		// If we continue... User just completed profile - give notifications !
		
			$kino_notification = '
							<p style="font-style: normal; font-weight: normal; font-size: 100%">Bravo, vous êtes inscrit au Kino Kabaret de Genève 2018. Vos frais d\'inscription contribuent à permettre à ce que l\'évènement ait lieu ainsi qu\'aux coûts de location du KinoLab, des salles de projection, de préparation des repas, des assurances, etc. En contrepartie vous bénéficiez de repas à un prix en dessous du prix coûtant, d\'un magnifique KinoLab avec accès internet, d\'une imprimante à disposition, d\'un espace de montage, d\'une plateforme internet, d\'impressions de fiches de tous les participants et l\'entrée aux trois projections.</p>
				
							<p style="font-style: normal; font-weight: normal; font-size: 100%">Merci de payer vos frais d\'inscription sur notre plateforme de financement participatif, en choisissant Inscription Kinoïte ou Inscription Kinoïte de soutien.
							<a href="https://www.lokalhelden.ch/5me-kino-kabaret-de-geneve" target="_blank">www.lokalhelden.ch/5me-kino-kabaret-de-geneve</a>.</p>
				
							<p style="font-style: normal; font-weight: 100; font-size: 100%">Afin qu\'il reste accessible pour tous et parce qu\'à ce jour nos recherches de soutiens ne suffisent pas à couvrir tous les frais liés à l\'organisation du Kino Kabaret tel que vous, et nous, l’aimons, nous convions la communauté à le soutenir, et merci d\'encourager votre entourage à aussi soutenir le Kino Kabaret de Genève.</p>
							';
			
			// Q7 : is "Photo du Profil" already complete?
			
			if( in_array( "avatar-complete", $kino_user_role ) ) {
			 
			 		// $kino_notification = '<p>Votre profil est complet. Vous pouvez régulièrement mettre à jour les informations de votre profil en vous connectant avec votre mot de passe.</p>';

			 		$kino_notification .= '
			 		  <script>
			 				mixpanel.track(
			 				    "Completed Profile"
			 				);
			 			</script>
			 		';
			 		
			 } else {

			 		$kino_notification .= '<p>PS: pensez à <a href="'.bp_core_get_user_domain( $userid ).'profile/change-avatar/">choisir une photo d’avatar</a>!</p>';
			 		
			 		$kino_notification .= '
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
				kino_remove_from_usergroup( $userid, $kino_fields['group-kino-incomplete'] );
				
				//mailpoet ajout "Kino Kabaret (Profil Complet)" et suppression de liste mailpoet kabaret incomplet
 				if( $mailpoet_id = getMailpoetId( $userid ) ) {
					kino_add_to_mailpoet_list(
						$mailpoet_id, 
						$kino_fields['mailpoet-participant-kabaret'] 
					);
					kino_remove_from_mailpoet_list(
						$mailpoet_id, 
						$kino_fields['mailpoet-participant-kabaret-incomplet'] 
					);
				}
				
				// Action 2 = send email notification!
				// ****************************************
				
				$headers[] = 'From: KinoGeneva <onvafairedesfilms@kinogeneva.ch>';
				
				$kino_notification_email .= '
Nous nous réjouissons de vous accueillir dans notre KinoLab à la Fonderie Kugler ( 19 av. de la Jonction, 1205 Genève - entrée par l’arrière du bâtiment) pour la soirée de lancement du Kabaret le samedi 13 janvier à 17h. Finalisation des inscriptions et paiement des frais de participation (en liquide) dès 14h. 
Vos frais d’inscription contribuent à permettre à ce que l’évènement ait lieu ainsi qu’aux coûts de location du KinoLab, des salles de projection, de préparation des repas, des assurances, etc. En contrepartie vous bénéficiez de repas à un prix en dessous du prix coûtant, d’un magnifique KinoLab avec accès internet, d’une imprimante à disposition, d’un espace de montage, d’une plateforme internet, d’impressions de fiches de tous les participants et l’entrée aux trois projections.

Merci de payer vos frais d’inscription sur notre plateforme de financement participatif, en choisissant Inscription Kinoïte ou Inscription Kinoïte de soutien.
<a href="https://www.lokalhelden.ch/5me-kino-kabaret-de-geneve">www.lokalhelden.ch/5me-kino-kabaret-de-geneve</a>

Afin qu’il reste accessible pour tous et parce qu’à ce jour nos recherches de soutiens ne suffisent pas à couvrir tous les frais liés à l’organisation du Kino Kabaret tel que vous, et nous, l’aimons, nous convions la communauté à le soutenir, et merci d’encourager votre entourage à aussi soutenir le Kino Kabaret de Genève.

Pour toutes les informations pratiques et le programme du Kino Kabaret 2018, voir: <a href="https://kinogeneva.ch/informations-pratiques/" style="color:#c11119;">https://kinogeneva.ch/informations-pratiques/</a>

Pour toute question relative à votre inscription, n’hésitez pas à nous contacter à onvafairedesfilms@kinogeneva.ch.';
				
				$host = $_SERVER['HTTP_HOST'];
				
				if ( $host == 'kinogeneva.ch' ) {
				
					$to = $user->user_email;
					$headers[] = 'Bcc: Manu <ms@ms-studio.net>';
					$headers[] = 'Bcc: KinoGeneva <onvafairedesfilms@kinogeneva.ch>';
				
				} else {
					
					$to = 'webmaster@kinogeneva.com';
					$headers[] = 'Bcc: Manu <ms@ms-studio.net>';
					$kino_notification_email .= '
					
					(Debug: message envoyé depuis le serveur test, page '.$_SERVER['REQUEST_URI'].', à '. date( 'H:i:s', time() ) .')';
				
				}
				
				 wp_mail( 
				 	$to,  // $to
				 	'[KinoGeneva] Confirmation pour '.$user->display_name, // $subject
				 	$kino_notification_email, 
				 	$headers 
				 );
				 
				break;
		
		
 	} while (0);
 	
 	//temporaire jusqu'à l'ouverture des inscription
 	//$kino_notification.= '<br/>Les inscriptions seront ouvertes très prochainement. Merci de votre patience!';
 	return $kino_notification;
 	
 }

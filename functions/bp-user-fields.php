<?php 


function kino_add_to_usergroup( $id, $group ) {
	
	wp_set_object_terms( 
			$id, // $object_id, 
			$group, // $terms, 
			'user-group', // $taxonomy, 
			true // $append 
	);

}

function kino_remove_from_usergroup( $id, $group ) {
	
	wp_remove_object_terms( 
			$id, // $object_id, 
			$group, // $terms, 
			'user-group' // $taxonomy, 
	);

}


function kino_user_participation( $userid, $kino_fields ) {
	
			// 
			
			if ( empty( $userid ) ) {
				$userid = bp_loggedin_user_id();
			}
			
			if ( empty( $kino_fields ) ) {
				$kino_fields = kino_test_fields();
			}
			
			
			// $kino_user_participation = $kup
			$kup = array();
			
			
			// is Comédien? // is Realisateur? // is Technicien?
			$kino_particiation_boxes = bp_get_profile_field_data( array(
					'field'   => $kino_fields['profile-role'],
					'user_id' => $userid
			) );
			if ($kino_particiation_boxes) {
				foreach ($kino_particiation_boxes as $key => $value) {
					
					$value = mb_substr($value, 0, 4);
				  
				  if ( $value == "Réal" ) {
				  	$kup[] = "realisateur";
				  	
				  	
				  	// Profil Realisateur complet? 
				  	$kino_profil_real = bp_get_profile_field_data( array(
				  			'field'   => $kino_fields['profil-real-complete'],
				  			'user_id' => $userid
				  	) );
				  	if ( $kino_profil_real != "" ) {
				  			$kup[] = "realisateur-complete";
				  	}
				  	
				  }
				  if ( $value == "Comé" ) {
				  	$kup[] = "comedien";
				  	
				  	// Profil Comédien complet? 
				  	$kino_profil_comedien = bp_get_profile_field_data( array(
				  			'field'   => $kino_fields['profil-comed-complete'],
				  			'user_id' => $userid
				  	) );
				  	if ( $kino_profil_comedien ) {
				  			$kup[] = "comedien-complete";
				  	}
				  	
				  }
				  if ( $value == "Arti" ) {
				  	$kup[] = "technicien";
				  	
				  	// Profil Technicien complet? 
				  	$kino_profil_tech = bp_get_profile_field_data( array(
				  			'field'   => $kino_fields['profil-tech-complete'], // trouver ID du champ!
				  			'user_id' => $userid
				  	) );
				  	if ( $kino_profil_tech ) {
				  			$kup[] = "technicien-complete";
				  	}
				  	
				  }
				  
				} // end foreach
			} // end testing field kino_participation
			
			// ***************
			
			// Test statut réalisateur plateforme
			// NOTE: if rejected = the real_participation checkbox will be unchecked.
			
			$ids_group_real_plaform_valid = get_objects_in_term( 
					$kino_fields['group-real-platform'], 
					'user-group' 
				);
				$ids_group_real_plaform_pending = get_objects_in_term( 
					$kino_fields['group-real-platform-pending'], 
					'user-group' 
				);
				$ids_group_real_plaform_rejected = get_objects_in_term( 
					$kino_fields['group-real-platform-rejected'], 
					'user-group' 
				);
				
				if ( in_array( $userid, $ids_group_real_plaform_valid ) ) {
				  $kup[] = "real-platform-valid";
				} else if ( in_array( $userid, $ids_group_real_plaform_rejected ) ) {
				  $kup[] = "real-platform-rejected";
				} else if ( in_array( $userid, $ids_group_real_plaform_pending ) ) {
					$kup[] = "real-platform-pending";
				}
			
			// ***************
			
			// Participe au cabaret 2016?
			$kino_test = bp_get_profile_field_data( array(
					'field'   => $kino_fields['kabaret'], 
					'user_id' => $userid
			) );
			if ( ( $kino_test == "oui" ) || ( $kino_test == "yes" ) ) {
						
						$kup[] = "kabaret-2016";
						
						// Profil Cabaret 2016 complet? 
						
						$kino_dispo_kab = bp_get_profile_field_data( array(
								'field'   => $kino_fields['profil-kabaret-complete'], // trouver ID du champ!
								'user_id' => $userid
						) );
						if ( $kino_dispo_kab ) {
								$kup[] = "kabaret-complete";
						}
						
			}
			
			// ***************
			
			// Aide Bénévole?
			$kino_aide_benevole = bp_get_profile_field_data( array(
					'field'   => $kino_fields['benevole'], 
					'user_id' => $userid
			) );
			if ( ( $kino_aide_benevole == "oui" ) || ( $kino_aide_benevole == "yes" ) ) {
						$kup[] = "benevole";
			}
			
			// Aide benevole pour le Kabaret 2016?
			$kino_benevole_boxes = bp_get_profile_field_data( array(
					'field'   => $kino_fields['benevole-kabaret'],
					'user_id' => $userid
			) );
			if ($kino_benevole_boxes) {
			
				$kup[] = "benevole-complete";
				
				foreach ($kino_benevole_boxes as $key => $value) {
				  if ( $value == "l’organisation du Kino Kabaret" ) {
				  	$kup[] = "benevole-kabaret";
				  }
				} // end foreach
			} //
			
			// ***************
			
			// Test des rôles pour le Kabaret 2016
			$kino16_particiation_boxes = bp_get_profile_field_data( array(
					'field'   => $kino_fields['role-kabaret'],
					'user_id' => $userid
			) );
			// test field 135 = participation en tant que
			if ($kino16_particiation_boxes) {
				foreach ($kino16_particiation_boxes as $key => $value) {
				
					$value = mb_substr($value, 0, 4);
				
				  if ( $value == "Réal" ) {
				  	$kup[] = "realisateur-2016";
				  }
				  if ( $value == "Comé" ) {
				  	$kup[] = "comedien-2016";
				  }
				  if ( $value == "Arti" ) {
				  	$kup[] = "technicien-2016";
				  }
				} // end foreach
			} //
			
			// ***************
			
			// Test statut réalisateur pour 2016
			
			// if ($kino16_particiation_boxes) {
			// NOTE: box could be disabled - if real has been rejected
				
				$ids_group_real_kab_valid = get_objects_in_term( 
						$kino_fields['group-real-kabaret'], 
						'user-group' 
					);
				$ids_group_real_kab_pending = get_objects_in_term( 
						$kino_fields['group-real-kabaret-pending'], 
						'user-group' 
					);
				$ids_group_real_kab_rejected = get_objects_in_term( 
						$kino_fields['group-real-kabaret-rejected'], 
						'user-group' 
					);
					
					if ( in_array( $userid, $ids_group_real_kab_valid ) ) {
					  $kup[] = "real-kab-valid";
					} else if ( in_array( $userid, $ids_group_real_kab_rejected ) ) {
					  $kup[] = "real-kab-rejected";
					} else if ( in_array( $userid, $ids_group_real_kab_pending ) ) {
						$kup[] = "real-kab-pending";
					}
				
			// } // END if 2016 participation
			
			// ***************
			
			// Is ID complete ?
			$kino_id_field = bp_get_profile_field_data( array(
					'field'   => $kino_fields['id-presentation'], // = Présentation
					'user_id' => $userid
			) );
			if ($kino_id_field != "" ) {
					$kup[] = "id-complete";
			}
			

			// Test avatar
			// src: https://buddypress.org/support/topic/detecting-if-user-uploaded-an-avatar/
			
			$kino_avatar = bp_core_fetch_avatar( array( 
				'item_id' => $userid, 
				'no_grav' => true, 
				'html' => false) );
			
			if ( $kino_avatar == 'https://kinogeneva.ch/wp-content/plugins/buddypress/bp-core/images/mystery-man.jpg' ) {
				// no avatar
			} else {
				$kup[] = "avatar-complete";
			}
			
			
			return $kup;
			
			/*
			// au final, les valeurs retournées:

			- realisateur
			- technicien
			- comedien
			- benevole
			
			- realisateur-2016
			- technicien-2016
			- comedien-2016
			- kabaret-2016
			- benevole-kabaret
			
			- real-kab-valid
			- real-kab-rejected
			- real-kab-pending
			
			- id-complete
			- realisateur-complete
			- comedien-complete
			- technicien-complete
			- benevole-complete = pour kabaret 2016
			- avatar-complete
			
			*/
			
}


// Same as above, but Kino 2016 role only

function kino_user_participation_role( $userid, $kino_fields ) {
	
	if ( empty( $userid ) ) {
		$userid = bp_loggedin_user_id();
	}
	if ( empty( $kino_fields ) ) {
		$kino_fields = kino_test_fields();
	}
	$kup = array();
	
	// Test des rôles pour le Kabaret 2016
	$kino16_particiation_boxes = bp_get_profile_field_data( array(
			'field'   => $kino_fields['role-kabaret'],
			'user_id' => $userid
	) );
	// test field 135 = participation en tant que
	if ($kino16_particiation_boxes) {
		foreach ($kino16_particiation_boxes as $key => $value) {
		
			$value = mb_substr($value, 0, 4);
		
		  if ( $value == "Réal" ) {
		  	$kup[] = "realisateur-2016";
		  }
		  if ( $value == "Comé" ) {
		  	$kup[] = "comedien-2016";
		  }
		  if ( $value == "Arti" ) {
		  	$kup[] = "technicien-2016";
		  }
		} // end foreach
	} //
	
	return $kup;
	
}


/*
 * Kino User Fields Light
 * This functions returns a basic array of fields, used for admin pages.
 * Contains:
 * Ville
 * Pays
*/

function kino_user_fields_light( $kino_userid, $kino_fields ) {

	if ( empty( $kino_userid ) ) {
		$kino_userid = bp_loggedin_user_id();
	}
	
	if ( empty( $kino_fields ) ) {
		$kino_fields = kino_test_fields();
	}
	
	$kino_userdata = array();
	
	$kino_userdata["ville"] = bp_get_profile_field_data( array(
			'field'   => $kino_fields["ville"],
			'user_id' => $kino_userid
	) );
	$kino_userdata["pays"] = bp_get_profile_field_data( array(
			'field'   => $kino_fields["pays"],
			'user_id' => $kino_userid
	) );
	
	$kino_userdata["presentation"] = bp_get_profile_field_data( array(
			'field'   => $kino_fields['id-presentation'],
			'user_id' => $kino_userid
	) );

	return $kino_userdata;

}

function kino_user_fields_superlight( $user, $kino_fields ) {
	
	$kino_userdata = array(
	      "user-id" => $kino_userid,
	      "user-name" => $user->display_name,
	      "user-slug" => $user->user_nicename,
	      "user-email" => $user->user_email,
	      "user-registered" => $user->user_registered
	  );

	return $kino_userdata;

}

/*
 * Kino User Logement
 * Created for Gestion-Logements
*/

function kino_user_fields_logement( $user, $kino_fields ) {

	$kino_userid = $user->ID ;

    if ( empty( $kino_fields ) ) {
        $kino_fields = kino_test_fields();
    }
    
    $kino_user_participation = kino_user_participation( 
    	$kino_userid, 
    	$kino_fields
    );
    
	$kino_userdata = array(
        "user-id" => $kino_userid,
        "user-name" => $user->display_name,
        "user-slug" => $user->user_nicename,
        "user-email" => $user->user_email,
        "user-registered" => $user->user_registered,
        "participation" => $kino_user_participation,
        "cherche-logement-remarque" => bp_get_profile_field_data( array(
            'field'   => $kino_fields['cherche-logement-remarque'],
            'user_id' => $kino_userid
        ) ),
        "offre-logement-remarque" => bp_get_profile_field_data( array(
            'field'   => $kino_fields['offre-logement-remarque'],
            'user_id' => $kino_userid
        ) ),
        "rue" => bp_get_profile_field_data( array(
            'field'   => $kino_fields["rue"],
            'user_id' => $kino_userid
        ) ),
        "ville" => bp_get_profile_field_data( array(
            'field'   => $kino_fields["ville"],
            'user_id' => $kino_userid
        ) ),
        "code-postal" => bp_get_profile_field_data( array(
            'field'   => $kino_fields["code-postal"],
            'user_id' => $kino_userid
        ) ),
        "pays" => bp_get_profile_field_data( array(
            'field'   => $kino_fields["pays"],
            'user_id' => $kino_userid
        ) ),
        "tel" => bp_get_profile_field_data( array(
            'field'   => $kino_fields["tel"],
            'user_id' => $kino_userid
        ) ),
        "dispo" =>  bp_get_profile_field_data( array(
            'field'   => $kino_fields["dispo"],
            'user_id' => $kino_userid
        ) ),
        "benevole-fonction" =>  bp_get_profile_field_data( array(
            'field'   => $kino_fields["benevole-fonction"],
            'user_id' => $kino_userid
        ) ),
        "benevole-charge-admin" =>  bp_get_profile_field_data( array(
            'field'   => $kino_fields["benevole-charge-admin"],
            'user_id' => $kino_userid
        ) ),
    );
	
	return $kino_userdata;

}

/*
 * Kino User Kabaret
 * Created for Gestion-Kabaret
*/

function kino_user_fields_kabaret( $user, $kino_fields ) {

	$kino_userid = $user->ID ;

    if ( empty( $kino_fields ) ) {
        $kino_fields = kino_test_fields();
    }
    
    $kino_user_participation_role = kino_user_participation( 
    	$kino_userid, 
    	$kino_fields
    );
    
	$kino_userdata = array(
        "user-id" => $kino_userid,
        "user-name" => $user->display_name,
        "user-slug" => $user->user_nicename,
        "user-email" => $user->user_email,
        "user-registered" => $user->user_registered,
        "participation" => $kino_user_participation_role
    );
	
	return $kino_userdata;

}



/*
 * Kino User Fields
 * This functions returns a very detailed array of fields, used for print-out.
*/

function kino_user_fields( $kino_userid, $kino_fields ) {
	
	if ( empty( $kino_userid ) ) {
		$kino_userid = bp_loggedin_user_id();
	}
	
	if ( empty( $kino_fields ) ) {
		$kino_fields = kino_test_fields();
	}
	
	$kino_userdata = array();
	
	$kino_userdata['participation'] = kino_user_participation( 
		$kino_userid, 
		$kino_fields
	);
	
	// Define our fields
	// Ville, Pays
	
	$kino_userdata["ville"] = bp_get_profile_field_data( array(
			'field'   => $kino_fields["ville"],
			'user_id' => $kino_userid
	) );
	$kino_userdata["pays"] = bp_get_profile_field_data( array(
			'field'   => $kino_fields["pays"],
			'user_id' => $kino_userid
	) );
	$kino_userdata["birthday"] = bp_get_profile_field_data( array(
			'field'   => $kino_fields["birthday"],
			'user_id' => $kino_userid
	) );
	$kino_userdata["tel"] = bp_get_profile_field_data( array(
			'field'   => $kino_fields["tel"],
			'user_id' => $kino_userid
	) );
	
	// Method to find age in years...
	 // Simple method:
	 $kino_birthyear = substr($kino_userdata["birthday"], -4);
	 $kino_userdata["userage"] = 2015 - $kino_birthyear;
	
	// Sessions
	
	$kino_userdata["sessions"] = bp_get_profile_field_data( array(
	 		'field'   => $kino_fields['session-attribuee'],
	 		'user_id' => $kino_userid
	 ) );
	 
	 // Disponibilité
	  
	  $kino_userdata["dispo"] = bp_get_profile_field_data( array(
	   		'field'   => $kino_fields['dispo'],
	   		'user_id' => $kino_userid
	   ) );
	   
	   $kino_userdata["dispo-partiel"] = bp_get_profile_field_data( array(
	     		'field'   => $kino_fields['dispo-partiel'],
	     		'user_id' => $kino_userid
	     ) );
	  
	  // Présentation
	  
	  $kino_userdata["presentation"] = bp_get_profile_field_data( array(
	  		'field'   => $kino_fields['id-presentation'],
	  		'user_id' => $kino_userid
	  ) );
	  
	  $kino_userdata["photo"] = bp_get_profile_field_data( array(
	   		'field'   => $kino_fields['id-photo'],
	   		'user_id' => $kino_userid
	   ) );
	  
	  // Role Technicien ??
	  if ( in_array( "technicien-2016", $kino_userdata["participation"] )) {
	  
						  $kino_userdata["role-tech"] = bp_get_profile_field_data( array(
						   		'field'   => $kino_fields['role-kabaret-tech'],
						   		'user_id' => $kino_userid
						   ) );
						   
						   $kino_userdata["comp-production"] = bp_get_profile_field_data( array(
						    		'field'   => $kino_fields['comp-production'],
						    		'user_id' => $kino_userid
						    ) );
						   $kino_userdata["comp-scenario"] = bp_get_profile_field_data( array(
						    		'field'   => $kino_fields['comp-scenario'],
						    		'user_id' => $kino_userid
						    ) );
						   $kino_userdata["comp-realisation"] = bp_get_profile_field_data( array(
						    		'field'   => $kino_fields['comp-realisation'],
						    		'user_id' => $kino_userid
						    ) );
						   $kino_userdata["comp-image"] = bp_get_profile_field_data( array(
						    		'field'   => $kino_fields['comp-image'],
						    		'user_id' => $kino_userid
						    ) );
						   $kino_userdata["comp-postprod-image"] = bp_get_profile_field_data( array(
						    		'field'   => $kino_fields['comp-postprod-image'],
						    		'user_id' => $kino_userid
						    ) );
						   $kino_userdata["comp-son"] = bp_get_profile_field_data( array(
						    		'field'   => $kino_fields['comp-son'],
						    		'user_id' => $kino_userid
						    ) );
						   $kino_userdata["comp-postprod-son"] = bp_get_profile_field_data( array(
						    		'field'   => $kino_fields['comp-postprod-son'],
						    		'user_id' => $kino_userid
						    ) );
						   $kino_userdata["comp-direction-artistique"] = bp_get_profile_field_data( array(
						    		'field'   => $kino_fields['comp-direction-artistique'],
						    		'user_id' => $kino_userid
						    ) );
						   $kino_userdata["comp-hmc"] = bp_get_profile_field_data( array(
						    		'field'   => $kino_fields['comp-hmc'],
						    		'user_id' => $kino_userid
						    ) );
						   $kino_userdata["comp-autres-liste"] = bp_get_profile_field_data( array(
						    		'field'   => $kino_fields['comp-autres-liste'],
						    		'user_id' => $kino_userid
						    ) );
						   $kino_userdata["comp-autres-champ"] = bp_get_profile_field_data( array(
						    		'field'   => $kino_fields['comp-autres-champ'],
						    		'user_id' => $kino_userid
						    ) );
						   $kino_userdata["equipement"] = bp_get_profile_field_data( array(
						    		'field'   => $kino_fields['equipement'],
						    		'user_id' => $kino_userid
						    ) );
						   $kino_userdata["equipement-spec"] = bp_get_profile_field_data( array(
						    		'field'   => $kino_fields['equipement-spec'],
						    		'user_id' => $kino_userid
						    ) );
	   }
	   
	  // Role Comédien ??
	  if ( in_array( "comedien-2016", $kino_userdata["participation"] )) {
	  
				  $kino_userdata["role-comed"] = bp_get_profile_field_data( array(
				     		'field'   => $kino_fields['role-kabaret-comed'],
				     		'user_id' => $kino_userid
				   ) );
				   $kino_userdata["age-camera-min"] = bp_get_profile_field_data( array(
				      		'field'   => $kino_fields['age-camera-min'],
				      		'user_id' => $kino_userid
				    ) );
				    $kino_userdata["age-camera-max"] = bp_get_profile_field_data( array(
				       		'field'   => $kino_fields['age-camera-max'],
				       		'user_id' => $kino_userid
				     ) );
				    $kino_userdata["langue-mat"] = bp_get_profile_field_data( array(
				        		'field'   => $kino_fields['langue-mat'],
				        		'user_id' => $kino_userid
				    ) );
				    $kino_userdata["langue-mat-autre"] = bp_get_profile_field_data( array(
				        		'field'   => $kino_fields['langue-mat-autre'],
				        		'user_id' => $kino_userid
				    ) );
				    $kino_userdata["langues-parlees"] = bp_get_profile_field_data( array(
				       		'field'   => $kino_fields['langues-parlees'],
				       		'user_id' => $kino_userid
				     ) );
				     $kino_userdata["langues-parlees-autre"] = bp_get_profile_field_data( array(
				        		'field'   => $kino_fields['langues-parlees-autre'],
				        		'user_id' => $kino_userid
				      ) );
				    $kino_userdata["langues-jouees"] = bp_get_profile_field_data( array(
				        		'field'   => $kino_fields['langues-jouees'],
				        		'user_id' => $kino_userid
				    ) );
				    $kino_userdata["langues-jouees-autre"] = bp_get_profile_field_data( array(
				        		'field'   => $kino_fields['langues-jouees-autre'],
				        		'user_id' => $kino_userid
				    ) );
				    $kino_userdata["talents"] = bp_get_profile_field_data( array(
				       		'field'   => $kino_fields['talents'],
				       		'user_id' => $kino_userid
				     ) );
				    $kino_userdata["talents-autre"] = bp_get_profile_field_data( array(
				       		'field'   => $kino_fields['talents-autre'],
				       		'user_id' => $kino_userid
				     ) );
				    
		}
	   
	  // Role Réalisateur ??
	   
	   $kino_userdata["role-real"] = bp_get_profile_field_data( array(
	      		'field'   => $kino_fields['role-kabaret-real'],
	      		'user_id' => $kino_userid
	    ) );
	   
	  // Is Staff ?? 
	  $kino_userdata["fonctions-staff"] = bp_get_profile_field_data( array(
	     		'field'   => $kino_fields['fonctions-staff'],
	     		'user_id' => $kino_userid
	   ) );
	    
	  // Véhicule
	  
	  $kino_userdata["vehicule"] = bp_get_profile_field_data( array(
	     		'field'   => $kino_fields['vehicule'],
	     		'user_id' => $kino_userid
	   ) );
	   $kino_userdata["vehicule-remarque"] = bp_get_profile_field_data( array(
	      		'field'   => $kino_fields['vehicule-remarque'],
	      		'user_id' => $kino_userid
	    ) );
	 
	return $kino_userdata;
	
}

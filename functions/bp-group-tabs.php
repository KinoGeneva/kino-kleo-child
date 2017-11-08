<?php  

// kino_list_of_excluded_profile_fields()
// A function to list the excluded profile fields: 
// Fields that should not be shown on a user profile, only for admins!

// Must be used both on Edit profile and View profile!

function kino_list_of_excluded_profile_fields() {	
	
	$kino_excluded_id = array();
	
	$kino_fields = kino_test_fields();
	
	if ( current_user_can( 'publish_pages' ) ) {
	
		// we show everything for: Admin and Editor roles
	
	} else {
		
		
		// exclude Session Attribuée (admin only)
		
		$kino_excluded_id[] = $kino_fields['session-attribuee'];
		
		// exclude Champs bénévoles Admin
		
		$kino_excluded_id[] = $kino_fields['benevole-activite-admin'];
		$kino_excluded_id[] = $kino_fields['benevole-charge-admin'];
		$kino_excluded_id[] = $kino_fields['benevole-charge-admin-test'];
		$kino_excluded_id[] = $kino_fields['fonctions-staff'];
		
		// Cacher uniquement sur la page Edition du profil: 
		// Définis dans la fonction ci-dessous
		
		if ( bp_is_user_profile_edit() ) {
		
		} else {
		
			// Cacher uniquement sur la page "Vue du profil": 
		
			// conditions d'utilisation:
			$kino_excluded_id[] = $kino_fields['conditions-generales'];
		}
		
		if ( !is_user_logged_in() ) {
		
			// cacher le champ email:
			$kino_excluded_id[] = $kino_fields['courriel'];
		
		}
		
	}
	
	return $kino_excluded_id;
	
}


function kino_hide_some_profile_fields( $retval ) {	
		
	if ( bp_is_user_profile_edit() ) {	
		
		// Hide field for normal users
		// see https://bitbucket.org/ms-studio/kinogeneva/issues/56/section-kabaret-3-menus-d-roulants-gestion
		// $retval['exclude_fields'] = '1,2';	//field ID's separated by comma
		
		$kino_excluded_id = array();
		
		$kino_fields = kino_test_fields();
		
		if ( current_user_can( 'publish_pages' ) ) {
		
			// we show everything for: Admin and Editor roles
		
		} else {
			
			$kino_excluded_id = kino_list_of_excluded_profile_fields();
			
			// is Realisateur for current Kino Kabaret?
			
			$kino_user_role = kino_user_participation( bp_loggedin_user_id(), $kino_fields );
			
			if ( !in_array( "realisateur", $kino_user_role ) && !in_array( "comedien", $kino_user_role ) && !in_array( "technicien", $kino_user_role ) ) {
			
				// Don't show role options for Kino Kabaret!
				
				$kino_excluded_id[] = $kino_fields['role-kabaret'];
			
			}
			
			if ( !in_array( "realisateur", $kino_user_role ) ) {
				
				// Don't show Session options for Kino Kabaret
				
				$kino_excluded_id[] = $kino_fields['session-un'];
				$kino_excluded_id[] = $kino_fields['session-deux'];
				$kino_excluded_id[] = $kino_fields['session-trois'];
				
				
				// And in addition to that...
				if ( !in_array( "comedien", $kino_user_role ) && !in_array( "technicien", $kino_user_role ) ) {
				
					// Don't show ANY role options for Kino Kabaret
					
					$kino_excluded_id[] = $kino_fields['role-kabaret'];
				
				}
				
			}
		
		} // end testing for Admin/Editor
	
	// turn into comma separated values:
	
	$kino_commalist = implode(', ', $kino_excluded_id);
	
	$retval['exclude_fields'] = $kino_commalist;
	
	}	
	
	return $retval;
}
add_filter( 'bp_after_has_profile_parse_args', 'kino_hide_some_profile_fields' );



/* modify form action = jump to next item in list */

function kino_the_profile_group_edit_form_action() {
	echo kino_get_the_profile_group_edit_form_action();
	
	/*
	 * Note: le lien généré sera ajouté dans le champ "form action=" du formulaire.
	 * Si c'est un lien autre qu'une page d'édition, les changements ne sont pas enregistrés.
	*/
	
}

function kino_get_the_profile_group_edit_form_action() {
		global $group;

		$bp = buddypress();
		
		$groups = bp_profile_get_field_groups();
		
		// figure out ID of current group
		$current_group_id = $group->id;
		
		// give a fallback value
		$next_group_id = $current_group_id;
		
		$count = count( $groups );
		
		for ( $i = 0, $count; $i < $count; ++$i ) {
			if ( $current_group_id == $groups[ $i ]->id ) {
			
					$j = $i+1;
					if ( $j < $count) { 
						
						//04.12.2016 #118 - rediriger vers page courant si id=17
						if($current_group_id==17) {
							$next_group_id = '/edit/group/17/';
						}
						else {
							$next_group_id = '/edit/group/' . $groups[ $j ]->id .'/';
						}
					} else {
					
					// $next_group_id = '/change-avatar/'; 
					// NOTE = problem, submissions don't get changed!
						
						$next_group_id = '/edit/group/' . $next_group_id .'/';
						
					}
				
			} // end if.
		} // end for.
		
		/**
		 * Filters the action for the profile group edit form.
		 *
		 * @since BuddyPress (1.1.0)
		 *
		 * @param string $value URL for the action attribute on the
		 *                      profile group edit form.
		 */
		 
		 $kino_form_action = bp_displayed_user_domain() . $bp->profile->slug . $next_group_id ;
		 
		 
		 // Exception pour le dernier groupe:
//		 if ( $current_group_id == 17 ) {
		 	// $kino_form_action = bp_displayed_user_domain() . $bp->profile->slug ;
		 	// NON, ça ne fonctionne pas, car les changements ne seront pas enregistrés.
//		 }
		 
		 // (could add #buddypress to jump in place)
				 
		return apply_filters( 'bp_get_the_profile_group_edit_form_action', $kino_form_action );
}






/* Decide what groups are visible, or hidden 
****************************** */

add_filter( 'bp_profile_get_field_groups', 'kino_get_field_group_conditions', 10 );

function kino_get_field_group_conditions( $groups ) {

		
		$forbidden_groups = array();
  	
  	// champs à tester:
  	$kino_fields = kino_test_fields();
  	
  	// Displayed User:
  	$kino_user_role = kino_user_participation( bp_displayed_user_id(), $kino_fields );
		
		// No need to show Conditions, they are filled at account creation...
		
		if ( bp_is_user_profile_edit() == false ) { // Notice: bp_is_profile_edit est déprécié depuis la version 1.5 ! Utilisez bp_is_user_profile_edit() à la place.
			$forbidden_groups[] = "Conditions";
		}
		
  	if (!in_array( "realisateur", $kino_user_role )) {
  		if (!in_array( "realisateur-kab", $kino_user_role )) {
  			$forbidden_groups[] = "Compétence Réalisateur";
  		}
  	}
  	
  	if (!in_array( "comedien", $kino_user_role )) {
  		$forbidden_groups[] = "Compétence Comédien";
  	}
  	
  	if (!in_array( "technicien", $kino_user_role )) {
  		$forbidden_groups[] = "Compétence Technicien";
  	}
  	
  	if (!in_array( "benevole", $kino_user_role )) {
  		$forbidden_groups[] = "Aide bénévole";
  	}
  	
  	if (!in_array( "kabaret-2017", $kino_user_role )) {
  		// pas inscrit au kabaret ?
  		if (!in_array( "benevole-kabaret", $kino_user_role )) {
  			// pas bénévole ?
  			$forbidden_groups[] = "Kino Kabaret 2016";
  		}
  	}
  	
  	
  	if (!current_user_can( 'publish_pages' )) {
  		  		
  		if (!in_array( "kabaret-2017", $kino_user_role )) {
  			// pas inscrit au kabaret ?
  			$forbidden_groups[] = "Kino Kabaret 2017";
  		}
  	
  	}
  	

  	$forbidden_group_ids = array(
  		// 7, // Technicien = 7
  		// 9, // Kabaret = 9
  		// 12, // Kabaret = 12
  	);
  	
  	$groups_updated = array();
  
  	for ( $i = 0, $count = count( $groups ); $i < $count; ++$i ) {
			
			$group_name = $groups[ $i ]->name;
			$group_id= $groups[ $i ]->id;
			
			// hide forbidden groups
			if (in_array( $group_name, $forbidden_groups)) {
				// Do Nothing = group is hidden
			} else {
				// Add to array
				$groups_updated[] = $groups[ $i ];
			}
			
  	} // end for loop.
  	  	
  return $groups_updated;
}

/* test des champs de profil 2018
 * on cache l'onglet kino kabaret 2018 si pas admin
 */

add_filter( 'bp_profile_get_field_groups', 'kino_get_field_group_temporaire2018', 10 );

function kino_get_field_group_temporaire2018( $groups ) {
	if ( current_user_can( 'publish_pages' ) ) {	
		// we show everything for: Admin and Editor roles
	}
	else {
		$forbidden_groups[] = "Kino Kabaret 2017";
		$forbidden_groups[] = "Kino Kabaret 2018";
	}
}

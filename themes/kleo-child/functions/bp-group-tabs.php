<?php  


function kino_hide_some_profile_fields( $retval ) {	
		
	if(  bp_is_profile_edit() ) {	
		
		// Hide field for normal users
		// see https://bitbucket.org/ms-studio/kinogeneva/issues/56/section-kabaret-3-menus-d-roulants-gestion
		// $retval['exclude_fields'] = '1,2';	//field ID's separated by comma
		
		$kino_excluded_id = array();
		
		$kino_fields = kino_test_fields();
		
		if ( current_user_can( 'publish_pages' ) ) {
			// show everything for Admin and Editor
		} else {
			
			// exclude Session Attribuée (admin only)
			
			$kino_excluded_id[] = $kino_fields['session-attribuee'];
			
			// is Realisateur for 2016?
			
			$kino_user_role = kino_user_participation( bp_loggedin_user_id() );
			
			if ( !in_array( "realisateur", $kino_user_role ) && !in_array( "comedien", $kino_user_role ) && !in_array( "technicien", $kino_user_role ) ) {
			
				// Don't show role options for Kino Kabaret
				
				$kino_excluded_id[] = $kino_fields['role-kabaret'];
			
			}
			
			if ( !in_array( "realisateur", $kino_user_role ) ) {
				
				// Don't show Session options for Kino Kabaret 2016
				
				$kino_excluded_id[] = $kino_fields['session-un'];
				$kino_excluded_id[] = $kino_fields['session-deux'];
				$kino_excluded_id[] = $kino_fields['session-trois'];
				
				
				// in addition to that...
				if ( !in_array( "comedien", $kino_user_role ) && !in_array( "technicien", $kino_user_role ) ) {
				
					// Don't show role options for Kino Kabaret
					
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
						$next_group_id = '/edit/group/' . $groups[ $j ]->id .'/';
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
		 
		 // (could add #buddypress to jump in place)
				 
		return apply_filters( 'bp_get_the_profile_group_edit_form_action', $kino_form_action );
}






/* Decide what groups are visible, or hidden 
****************************** */

add_filter( 'bp_profile_get_field_groups', 'kino_get_field_group_conditions', 10 );

function kino_get_field_group_conditions( $groups ){

  // $groups = array();
  // $number_of_groups = count( $groups );
  
//  	$kino_role = bp_get_profile_field_data( array(
//  		'field'   => '1',
//  		'user_id' => bp_loggedin_user_id()
//  	) );
		
		$forbidden_groups = array(
			"5.a Inscription Kabaret",
			"5.b Kabaret suite",
		);
  	
  	// champs à tester:
  	
  	$kino_user_role = kino_user_participation( bp_loggedin_user_id() );

  	
  	if (!in_array( "realisateur", $kino_user_role )) {
  		$forbidden_groups[] = "Compétence Réalisateur";
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
  	
  	if (!in_array( "kabaret-2016", $kino_user_role )) {
  		// bénévole pour kabaret ? 
  		if (!in_array( "benevole-kabaret", $kino_user_role )) {
  			$forbidden_groups[] = "Kino Kabaret 2016";
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



/* Prevent links on profile page
************************************ */

function remove_xprofile_links() {
    remove_filter( 'bp_get_the_profile_field_value', 'xprofile_filter_link_profile_data', 9, 2 );
}
add_action( 'bp_init', 'remove_xprofile_links', 20 );
// source: https://codex.buddypress.org/themes/bp-custom-php/




//
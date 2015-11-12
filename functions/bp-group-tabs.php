<?php  


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
						$next_group_id = $groups[ $j ]->id;
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
		 
		 $kino_form_action = bp_displayed_user_domain() . $bp->profile->slug . '/edit/group/' . $next_group_id .'/';
		 
		 // (could add #buddypress to jump in place)
				 
		return apply_filters( 'bp_get_the_profile_group_edit_form_action', $kino_form_action );
}


function kino_user_participation() {
	
			// we want to test:
			// is Comédien? // is Realisateur? // is Technicien?
			
			$kino_user_participation = array();
			
			$kino_particiation_boxes = bp_get_profile_field_data( array(
					'field'   => '135',
					'user_id' => bp_loggedin_user_id()
			) );
			
			$kino_particip_kabaret = bp_get_profile_field_data( array(
					'field'   => '100', // trouver ID du champ!
					'user_id' => bp_loggedin_user_id()
			) );
			
			
			// test field 135 = participation en tant que
			if ($kino_particiation_boxes) {
				foreach ($kino_particiation_boxes as $key => $value) {
				  if ( $value == "Réalisateur-rice" ) {
				  	$kino_user_participation[] = "realisateur";
				  }
				  if ( $value == "Comédien-ne (et/ou)" ) {
				  	$kino_user_participation[] = "comedien";
				  }
				  if ( $value == "Artisan-ne / technicien-ne (et/ou)" ) {
				  	$kino_user_participation[] = "technicien";
				  }
				} // end foreach
			} // end testing field #135
			
			if ( $kino_particip_kabaret == "oui" ) {
						$kino_user_participation[] = "kabaret-2016";
			} // end testing field #100
			
			return $kino_user_participation;
}

function kino_event_participation() {

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
  	
  	$kino_user_role = kino_user_participation();

  	
  	if (!in_array( "realisateur", $kino_user_role )) {
  		$forbidden_groups[] = "Compétence Réalisateur";
  	}
  	
  	if (!in_array( "comedien", $kino_user_role )) {
  		$forbidden_groups[] = "Compétence Comédien";
  	}
  	
  	if (!in_array( "technicien", $kino_user_role )) {
  		$forbidden_groups[] = "Compétence Technicien";
  	}
  	
  	if (!in_array( "kabaret-2016", $kino_user_role )) {
  		$forbidden_groups[] = "Kino Kabaret 2016";
  	}
  	
  	$forbidden_group_ids = array(
  		// 7, // Technicien = 7
  		// 9, // Kabaret = 9
  		// 12, // Kabaret = 12
  	);
  	
//  	if ( $kino_role === "Schmalstieg") {
//  	 	 $forbidden_groups[] = "2. Compétence Réal";
//  	 	 $forbidden_groups[] = "4. Compétence Technicien";
//  	}
  	
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
<?php 






// ORGINAL FUNCTION, modified // not used anymore.

function kino_bp_profile_group_tabs() {
	echo kino_bp_get_profile_group_tabs();

	/**
	 * Fires at the end of the tab output for switching between profile field
	 * groups. This action is in a strange place for legacy reasons.
	 *
	 * @since BuddyPress (1.0.0)
	 */
	do_action( 'xprofile_profile_group_tabs' );
}

function kino_bp_get_profile_group_tabs() {

	// Get field group data
	$groups     = bp_profile_get_field_groups();
	$group_name = bp_get_profile_group_name();
	$tabs       = array();
	
	$kino_role = bp_get_profile_field_data( array(
		'field'   => '1121',
		'user_id' => bp_loggedin_user_id()
	) );
	
	// Loop through field groups and put a tab-lst together
	for ( $i = 0, $count = count( $groups ); $i < $count; ++$i ) {

		// Setup the selected class
		$selected = '';
		if ( $group_name === $groups[ $i ]->name ) {
			$selected = 'current'; // = Skip rest of loop.
		}

		// Skip if group has no fields
		if ( empty( $groups[ $i ]->fields ) ) {
			continue;
		}
		
		// Example: Skip group Nr 5:
//		if ( 5 == $groups[ $i ]->id ) {
//			continue;
//		}

		// Build the profile field group link
		$link   = trailingslashit( bp_displayed_user_domain() . buddypress()->profile->slug . '/edit/group/' . $groups[ $i ]->id );
		
		// add group name to CSS class:
		$selected .= ' groupnr'. $groups[ $i ]->id;

		// Add tab to end of tabs array
		$tabs[] = sprintf(
			'<li class="%1$s"><a href="%2$s">%3$s</a></li>',
			$selected,
			esc_url( $link ),
			esc_html( apply_filters( 'bp_get_the_profile_group_name', $groups[ $i ]->name ) )
		);
	} // end for loop.

	/**
	 * Filters the tabs to display for profile field groups.
	 *
	 * @since BuddyPress (1.5.0)
	 *
	 * @param array  $tabs       Array of tabs to display.
	 * @param array  $groups     Array of profile groups.
	 * @param string $group_name Name of the current group displayed.
	 */
	$tabs = apply_filters( 'xprofile_filter_profile_group_tabs', $tabs, $groups, $group_name );

	return join( '', $tabs );
}






// https://codex.buddypress.org/developer/user-submitted-guides/how-to-add-members-to-a-group-via-wp-users-screen/
// How to Add Members to a Group via WP Users Screen

function add_users_to_bpgroup() {   
    if( bp_is_active('groups') ):
 
        if( isset( $_GET['action'] ) && isset( $_GET['bp_gid'] ) && isset( $_GET['users'] ) ) {
            $group_id = $_GET['bp_gid'];
            $users = $_GET['users'];
             
            foreach ( $users as $user_id ) {
                groups_join_group( $group_id, $user_id );
            }
        }
        //form submission
        add_action( 'admin_footer', function() { ?>
            <script type="text/javascript" charset="utf-8">
                jQuery("select[name='action']").append(jQuery('<option value="groupadd">Add to BP Group</option>'));
                jQuery("#doaction").click(function(e){
                    if(jQuery("select[name='action'] :selected").val()=="groupadd") { e.preventDefault();
                        gid=prompt("Enter a Group ID","1");
                        jQuery(".wrap form").append('<input type="hidden" name="bp_gid" value="'+gid+'" />').submit();
                    }
                });
            </script>
        <?php
        });
         
    endif;
}
// add_action ( 'load-users.php', 'add_users_to_bpgroup' );
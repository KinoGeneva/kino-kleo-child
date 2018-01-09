<?php

/**
 * BuddyPress - Users Profile
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

// Navigation de 2ème niveau
// Afficher seulement si utilisateur connecté

if ( is_user_logged_in() ) {

	bp_get_template_part( 'members/single/profile/subnav' );

}

do_action( 'bp_before_profile_content' ); ?>

<div class="profile clearfix" role="main">

	<?php 
				// Show notifications:
				
				if ( is_user_logged_in() ) {
				
					$kino_notifications = kino_edit_profile_notifications( bp_loggedin_user_id() );
					
					if ( !empty($kino_notifications) ) {
					
						?><figure class="callout-blockquote light big-blockquote"><blockquote><?php 
							echo $kino_notifications; ?>
						    </blockquote></figure>
						<?php
					} 
				
				}
	 ?>

<?php switch ( bp_current_action() ) :

	// Edit
	case 'edit'   :
		bp_get_template_part( 'members/single/profile/edit' );
		break;

	// Change Avatar
	case 'change-avatar' :
		bp_get_template_part( 'members/single/profile/change-avatar' );
		break;

	// Compose
	case 'public' :

		// Display XProfile
		if ( bp_is_active( 'xprofile' ) )
		
		
			bp_get_template_part( 'members/single/profile/profile-loop' );

		// Display WordPress profile (fallback)
		else
			bp_get_template_part( 'members/single/profile/profile-wp' );

		break;

	// Any other
	default :
		bp_get_template_part( 'members/single/plugins' );
		break;
endswitch; ?>
</div><!-- .profile -->

<?php do_action( 'bp_after_profile_content' ); ?>
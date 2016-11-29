<?php

/**
 * BuddyPress - Users Header
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>

<?php do_action( 'bp_before_member_header' ); 

	// NOTE: the toggle is inserted here via Kleo function : kleo_bp_expand_profile() in: buddypress-functions.php

	// Removed: item-header-avatar
	
?>

<div id="item-header-content" <?php if (isset($_COOKIE['bp-profile-header']) && $_COOKIE['bp-profile-header'] == 'small') {echo 'style="display:none;"';} ?>>

<?php 

	/*
	
	 NOTE: 
	 do_action( 'bp_member_header_actions' );
	 
	 has been moved into kino_title_filter() !!!
	 
	*/

 ?>

	<?php do_action( 'bp_before_member_header_meta' ); ?>

	
</div><!-- #item-header-content -->

<?php do_action( 'bp_after_member_header' ); ?>

<?php do_action( 'template_notices' ); ?>
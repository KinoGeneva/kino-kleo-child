<?php

/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter()
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

// Define fields
$kino_fields = kino_test_fields();

?>

<?php do_action( 'bp_before_members_loop' ); ?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' ). '&per_page='.sq_option('bp_members_perpage', 24) ) ) : ?>

	<div id="pag-top" class="pagination">

		<div class="pag-count" id="member-dir-count-top">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-top">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

	<?php do_action( 'bp_before_directory_members_list' ); ?>

	<ul id="members-list" class="item-list row kleo-isotope masonry">

	<?php while ( bp_members() ) : bp_the_member(); ?>

		<li class="kleo-masonry-item">
    	<div class="member-inner-list animated animate-when-almost-visible bottom-to-top">
	<?php
	//get user
	$userid = bp_get_member_user_id();
	
	//get users in group
	$ids_of_kino_complete = get_objects_in_term( $kino_fields['group-kino-complete'] , 'user-group' );
	
	//star display
	if(in_array($userid, $ids_of_kino_complete)){
		echo '<img src="'. get_stylesheet_directory_uri() .'/img/badges/Star_small.png" style="float: right; margin-right: 10px;" alt="Participant Kino Kabaret" title="Participant Kino Kabaret"/>';
	}
	?>
        <div class="item-avatar rounded">
          <a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar(); ?></a>
          <?php do_action('bp_member_online_status', bp_get_member_user_id()); ?>
        </div>
  
        <div class="item">
          <div class="item-title">
            <a href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
          </div>
          <div class="item-meta">
	<?php
	// Display Kino Roles
	$user_role = kino_user_participation_role( $userid, $kino_fields );
	$kino_roles = array();
	// Réalisateur ?
	if ( in_array( "realisateur-kab", $user_role )) {
		$kino_roles[] = 'Réalisateur-trice';
	}
	// Technicien ?
	if ( in_array( "technicien-kab", $user_role )) {
		$kino_roles[] = 'Artisan-ne / technicien-ne';
	}
	// Comédien ?
	if ( in_array( "comedien-kab", $user_role )) {
		$kino_roles[] = 'Comédien-ne';
	}
	// Bénévole? - benevole-complete
	if ( in_array( "benevole-complete", $user_role )) {
		$kino_roles[] = 'Bénévole';
	}
	foreach($kino_roles as $kino_role){
		echo '<span class="kp-pointlist">'. $kino_role .'</span>';
	}
	?>
          </div>
          
          <?php if ( bp_get_member_latest_update() ) : ?>
            <span class="update"> <?php bp_member_latest_update(); ?></span>
          <?php endif; ?>
  
          
  
          <?php do_action( 'bp_directory_members_item' ); ?>
  
          <?php
           /***
            * If you want to show specific profile fields here you can,
            * but it'll add an extra query for each member in the loop
            * (only one regardless of the number of fields you show):
            *
            * bp_member_profile_data( 'field=the field name' );
            */
			if ( current_user_can( 'read' ) ) {
				echo '<div class="item-meta">';
            	
				$kino_profil_email = bp_get_profile_field_data( array(
				'field'   => $kino_fields['courriel'],
				'user_id' => $userid
				) );
				
				$kino_profil_tel = bp_get_profile_field_data( array(
				'field'   => $kino_fields['tel'],
				'user_id' => $userid
				) );

				if ( $kino_profil_email != "" ) {
					echo $kino_profil_email;	
				}

				if ( $kino_profil_tel != "" ) {
					echo '<br><a href="tel:'.$kino_profil_tel.'">'.$kino_profil_tel.'</a>';	
				}
				echo '</div>';
            
            } // test user

          ?>
        </div>
  
        <div class="action">
  
          <?php do_action( 'bp_directory_members_actions' ); ?>
  
        </div>

			</div><!--end member-inner-list-->
		</li>

	<?php endwhile; ?>

	</ul>

	<?php do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="member-dir-count-bottom">

			<?php bp_members_pagination_count(); ?>

		</div>

		<div class="pagination-links" id="member-dir-pag-bottom">

			<?php bp_members_pagination_links(); ?>

		</div>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( "Sorry, no members were found.", 'buddypress' ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'bp_after_members_loop' ); ?>

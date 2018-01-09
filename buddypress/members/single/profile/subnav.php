<?php

/**
 * BuddyPress - Members - Single - Profile - Subnav
 *
 * @package BuddyPress
 * @subpackage kinogeneva
 */

?>

<div class="item-list-tabs no-ajax" id="subnav" role="navigation">
	<ul>
		<?php bp_get_options_nav(); ?>
		<?php $l_user = bp_loggedin_user_id();
		$d_user = bp_displayed_user_id();
		//echo 'logged'.$l_user;
		//echo '<br>displayed'. $d_user;
		 if ($l_user === $d_user) { ?>
	
	<?php } ?>
	<?php 
		
		// pour tout le monde: lien vers modif Mdp
		
		// echo '<li id="edit-password"><a href="'.site_url().'/wp-admin/profile.php">Email & Mot de passe</a></li>';
		
		// pour les admin: lien vers le backend
		if( current_user_can('administrator')) {
			echo '<li id="edit-in-backend"><a href="'.site_url().'/wp-admin/users.php?page=bp-profile-edit&user_id='.bp_displayed_user_id().'">Edition Admin</a></li>';
			
			echo '<li id="edit-groups"><a href="'.site_url().'/wp-admin/user-edit.php?user_id='.bp_displayed_user_id().'#user-logement">Groupes</a></li>';
			
			// http://kinogeneva.4o4.ch/wp-admin/users.php?page=bp-profile-edit&user_id=243&wp_http_referer=%2Fwp-admin%2Fusers.php
			
			
			//<li id="change-cover-image-personal-li"><a id="change-cover-image" href="http://kinogeneva.4o4.ch/members/rouge-cloe/profile/change-cover-image/">Modifier l'en-tête</a></li>
		}
	 ?>
	</ul>
</div><!-- #subnav -->
<?php 
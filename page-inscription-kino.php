<?php 

/*

- si connecté = redirige vers http://kinogeneva.ch/members/%username%/profile/edit/
- si non-connecté = dirige vers la page http://kinogeneva.ch/inscription-membre/

*/

if ( is_user_logged_in() ) {

	global $bp;
	
	$kino_redirect = bp_core_get_user_domain( bp_loggedin_user_id() ).'/profile/edit/group/1/'; // 

} else {
	
	$kino_redirect =  site_url( 'wp-login.php?redirect_to=http%3A%2F%2Fkinogeneva.ch%2Finscription-kino%2F' );
}

header("Location: ".$kino_redirect); /* Redirect browser */
exit();

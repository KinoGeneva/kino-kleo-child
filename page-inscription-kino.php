<?php 

/*

- template de redirection pour https://kinogeneva.ch/inscription-kino/
- si connecté = redirige vers https://kinogeneva.ch/members/%username%/profile/edit/
- si non-connecté = dirige vers la page https://kinogeneva.ch/inscription-membre/

*/

if ( is_user_logged_in() ) {

	global $bp;
	
	$kino_redirect = bp_core_get_user_domain( bp_loggedin_user_id() ).'/profile/edit/group/1/'; // 

} else {
	
	// $kino_redirect =  site_url( 'wp-login.php?redirect_to=http%3A%2F%2Fkinogeneva.ch%2Finscription-kino%2F' );
	$kino_redirect =  home_url( 'inscription-membre' );
	  
}

// Note: with some plugins such as popup-by-supsystic, it's possible that headers are already sent
// See http://stackoverflow.com/questions/2820639/how-to-check-if-headers-already-been-sent-in-php 
// for some solutions...

if ( headers_sent() ) { 
	
	// if headers already sent : print HTML redirect.
	// echo '<h1>Headers already sent</h1>';
	echo '<!DOCTYPE html>
	<html class="no-js" lang="fr-FR">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="refresh" content="0; url='.home_url().'">
	</head>
	<body></body>
	</html>';
	
} else {
	//send the user automatically to test.php
	header("Location: ".$kino_redirect);
	exit;

}

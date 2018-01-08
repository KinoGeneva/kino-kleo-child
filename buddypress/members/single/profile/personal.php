<?php

/**
 * BuddyPress - Personal User Profile
 *
 * @package BuddyPress
 * @subpackage Kino Geneva
 */

?>
<div id="bp-kino-personal" class="bp-kino-personal">
<?php


// define displayed user
$d_user = bp_displayed_user_id();
// echo 'ID: '.$d_user.'<br>';

// define $kino_fields
$kino_fields = kino_test_fields();

//- Name



$d_user_contact_info = kino_user_fields_auto( 
	$d_user, 
	$kino_fields, 
	array( 
		'birthday',
		'rue',
		'code-postal',
		'ville',
		'pays',
		'tel',
		'sexe',
		'site-web',
		'autres-liens'
	)
);

$user_query = new WP_User_Query( array( 
	'include' =>array( $d_user ), 
) );

foreach ( $user_query->results as $user ) {
  	
  	$d_user_personal_info = kino_user_fields_superlight( $user, $kino_fields );
  	
//  	echo '<pre>';
//  	var_dump($d_user_personal_info);
//  	echo '</pre>';
  	
} // End foreach

//  Retourne:
//  'user-id' => int 266
//  'user-name' => string 'Oscar García Martín'
//  'user-slug' => string 'oscaraligarci'
//  'user-email' => string 'oscar.garciamartin@hotmail.com'
//  'user-registered' => string '2015-12-03 18:00:39'

/*
 * First part = kino-personal-info
*/ 

echo '<div id="kino-personal-info" class="kino-personal-info">';
 
// Name
echo '<h2 class="kino-personal-name">'.$d_user_personal_info["user-name"].'</h2>';

/*
 Age, Country, Professional Status, CV link
 ******************************************
*/

//$d_user_birthday = bp_get_profile_field_data( array(
//  'field'   => $kino_fields['birthday'],
//  'user_id' => $d_user
//) );

if (!empty( $d_user_contact_info['birthday'] )) {

	$d_user_age = date_diff(
		date_create($d_user_contact_info['birthday']), 
		date_create('now')
	)->y;
	// https://stackoverflow.com/questions/3776682/
	
	$d_user_shortinfo = $d_user_age .' ans, ';

}


if (!empty( $d_user_contact_info['pays'] )) {
	$d_user_shortinfo .= $d_user_contact_info['pays'];
}


/*
 * Professional status
  
 Question: montrer statut plateforme, ou Kabaret actuel ?
 Etant donné que c'est le profil générique:
 Montrer statut plateforme.
*/

$d_user_role = bp_get_profile_field_data( array(
	'field'   => $kino_fields['profile-role'],
	'user_id' => $d_user
) );

//echo 'Role Plateforme:<pre>';
//var_dump($d_user_role);
//echo '</pre>';

if ($d_user_role) {
	foreach ($d_user_role as $key => $value) {
	
		// divider
		$d_user_shortinfo .= ' &ndash; ';

		// Remove (et/ou): 
		$value = str_replace( "(et/ou)", "", $value );
		
		// TODO: Take care of "Bénévole (organisation kabaret et/ou à l'année)" 
		
		$d_user_shortinfo .= $value;
	
	} // end foreach
}

/*
 * CV Link
*/

$d_user_cv = bp_get_profile_field_data( array(
  'field'   => $kino_fields['id-cv'],
  'user_id' => $d_user
) );

if (!empty($d_user_cv)) {
	
	$d_user_shortinfo .= ' &ndash; ';
	
	$d_user_shortinfo .= '<a href="';

	$d_user_shortinfo .= content_url('uploads');
	
	$d_user_shortinfo .= $d_user_cv ;
	
	$d_user_shortinfo .= '">Télécharger le CV</a>';
	
}

echo '<div class="kino-personal-shortinfo">'.$d_user_shortinfo.'</div>';

/*
 *
 * Description:
 ****************
 *
 * Correspond au champ "Présentez-vous !"
 *
*/

$d_user_presentation = bp_get_profile_field_data( array(
  'field'   => $kino_fields['id-presentation'],
  'user_id' => $d_user
) );


if (!empty($d_user_presentation)) {

	echo '<div class="personal-presentation">';
	echo $d_user_presentation;
	echo '</div>';

}

?>
</div><!-- #kino-personal-info-->
<?php

/*
 * Second part
 ****************
 One or several pictures
 Source: avatar, or uploaded photo?
 
*/

$kino_img_url = bp_core_fetch_avatar( array( 
 	'item_id' => $d_user, 
 	// 'no_grav' => true,
 	'type' => 'full', 
 	'object' => 'user',
 	'width' => 500,  
 	'height' => 500,
 	'html' => false) );

if ( $kino_img_url == 'https://kinogeneva.ch/wp-content/plugins/buddypress/bp-core/images/mystery-man.jpg' ) {
        	 	
 	// no avatar - 
 	// check if photo uploaded:
			$kino_userdata["photo"] = bp_get_profile_field_data( array(
				   		'field'   => $kino_fields['id-photo'],
				   		'user_id' => $d_user
				   ) );

		if ( $kino_userdata["photo"] ) {
		
			$kino_img_url = str_replace('" alt="" />','',$kino_userdata["photo"] );
			$kino_img_url = str_replace('<img src="','',$kino_img_url );			
		
		} else {
			
			$kino_img_url = '';
		
		}

}

if ( !empty($kino_img_url) ) {
	
	echo '<div class="personal-photos">';
	
	echo '<img src="'.$kino_img_url.'" alt="Portrait de '.$d_user_personal_info["user-name"].'" />';	
		
	echo '</div>';

}    	 	
        	 	


/*
 * Third part
 ****************
 * List of infos
 
 	
 
 
 - Name
 - Gender
 - Birth date
 - Address
 - PostCode
 - City
 - Country
 - Phone Nr
 - Email
 - Website
 - Other links
 
 'birthday',
 'rue',
 'code-postal',
 'ville',
 'pays',
 'tel',
 'sexe',
 'site-web',
 'autres-liens'
 
*/

?>
<div class="personal-contact">
	<?php 
		
	echo kino_make_user_field_markup( 
		'Nom', 
		$d_user_personal_info["user-name"] );
		
	echo kino_make_user_field_markup( 
		'Sexe', 
		$d_user_contact_info['sexe'] );
	
	if ( is_user_logged_in() ) {
		
		echo kino_make_user_field_markup( 
			'Date de naissance', 
			$d_user_contact_info['birthday'] );
	
		echo kino_make_user_field_markup( 
			'Adresse: rue, n°', 
			$d_user_contact_info["rue"] );
			
		echo kino_make_user_field_markup( 
			'Code postal', 
			$d_user_contact_info["code-postal"] );
	}
	
	
		
	echo kino_make_user_field_markup( 
		'Ville', 
		$d_user_contact_info["ville"] );
			
	echo kino_make_user_field_markup( 
		'Pays', 
		$d_user_contact_info["pays"] );
	
	if ( is_user_logged_in() ) {
			
		echo kino_make_user_field_markup( 
			'Téléphone', 
			$d_user_contact_info['tel'] );
	
		echo kino_make_user_field_markup( 
			'e-mail', 
			make_clickable(
				$d_user_personal_info["user-email"] ));
	}
	
	echo kino_make_user_field_markup( 
		'site-web', 
		make_clickable( $d_user_contact_info['site-web'] ));
		
		// xprofile_filter_link_profile_data( $field_value
	
	echo kino_make_user_field_markup( 
		'autres-liens', 
		make_clickable( 
			wpautop( 
				$d_user_contact_info['autres-liens'], true )));
		
	
	 ?>
</div>

<?php 


// Ajout du bouton "Follow"

// <div class="action">

// Note: le bouton Follow est généré par le plugin 
// BuddyPress Follow
// https://wordpress.org/plugins/buddypress-followers/

// Add button:
// https://github.com/r-a-y/buddypress-followers/wiki/Add-a-follow-button-on-a-regular-WP-page

//if ( function_exists( 'bp_follow_add_follow_button' ) ) {
//	if ( bp_loggedin_user_id() && bp_loggedin_user_id() != get_the_author_meta( 'ID' ) ) {
//	
//		bp_follow_add_follow_button( array(
//			'leader_id'   => $d_user,
//			'follower_id' => bp_loggedin_user_id()
//		) );
//	
//	}
// }

// </div>

/*
 * ps: ça bugge, à voir plus tard.
 [08-Jan-2018 10:12:21 UTC] PHP Fatal error:  Uncaught Error: [] operator not supported for strings in /Users/ms/Documents/WEB_SITES/COMMISSIONED/KINOGENEVA/WP/assets/plugins/buddypress-followers/_inc/bp-follow-notifications.php:308
*/

?>

</div> <!-- bp-kino-personal -->
<?php


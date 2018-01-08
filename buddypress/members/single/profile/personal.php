<?php

/**
 * BuddyPress - Personal User Profile
 *
 * @package BuddyPress
 * @subpackage Kino Geneva
 */

?>
<div class="bp-kino-personal">
<?php



// define displayed user
$d_user = bp_displayed_user_id();
// echo 'ID: '.$d_user.'<br>';

// are $kino_fields defined?
$kino_fields = kino_test_fields();

$user_query = new WP_User_Query( array( 
	'include' =>array( $d_user ), 
) );

foreach ( $user_query->results as $user ) {
  	
  	$d_user_personal_info = kino_user_fields_superlight( $user, $kino_fields );
  	
//  	echo '<pre>';
//  	var_dump($d_user_personal_info);
//  	echo '</pre>';
  	
} // End foreach


/*
 * First part
*/ 
 
// Name
echo '<h2 class="kino-personal-name">'.$d_user_personal_info["user-name"].'</h2>';

/*

 Age, Country, Professional Status, CV link
 
*/

$d_user_birthday = bp_get_profile_field_data( array(
  'field'   => $kino_fields['birthday'],
  'user_id' => $d_user
) );

if (!empty($d_user_birthday)) {

	$d_user_age = date_diff(
		date_create($d_user_birthday), 
		date_create('now')
	)->y;
	// https://stackoverflow.com/questions/3776682/
	
	$d_user_shortinfo = $d_user_age .' ans, ';

}

$d_user_pays = bp_get_profile_field_data( array(
  'field'   => $kino_fields['pays'],
  'user_id' => $d_user
) );

if (!empty($d_user_pays)) {
	$d_user_shortinfo .= $d_user_pays;
}


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
 
 - Description

*/

?>

<div class="personal-intro">
</div>

<?php
/*
 * Second part
 - One or several pictures
*/
?>

<div class="personal-photos">
</div>
<?php
/*
 * Third part
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
*/

?>
<div class="personal-contact">
</div>




</div> <!-- bp-kino-personal -->
<?php


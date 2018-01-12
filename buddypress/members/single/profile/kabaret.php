<?php

/**
 * BuddyPress - Personal User Profile - Kabaret
 *
 * @package BuddyPress
 * @subpackage Kino Geneva
 */

?>
<div id="bp-kino-kabaret" class="bp-kino-kabaret bp-widget clearfix">
	<div class="hr-title hr-full hr-double">
		<abbr>
			Kino Kabaret 2018
		</abbr>
	</div>
<?php

// define displayed user
if (empty($d_user)) {
	$d_user = bp_displayed_user_id();
}

// define admin_view
if (empty($admin_view)) {
	$admin_view = kino_admin_view();
}

// define $kino_fields
if (empty($kino_fields)) {
	$kino_fields = kino_test_fields();
}

$d_user_kab_info = kino_user_fields_auto( 
	$d_user, 
	$kino_fields, 
	array( 
		'dispo',
		'dispo-partiel',
		'real-niveau',
		'session-attribuee',
		'tech-niveau',
		'comedien-niveau',
		'real-coaching-scenario',
		'comp-scenario-file'
	)
);

$d_user_kab_info["participation"] = $kino_user_participation = kino_user_participation( 
	$d_user, 
	$kino_fields
);

/*
 * First part = presences
 **************************
*/ 

echo '<div id="kino-kabaret-presences" class="kino-kabaret-presences kab-cal">';
 
 $kabaret_dates = array(
 		'13' => 'Sa',
 		'14' => 'Di',
 	 	'15' => 'Lu',
 	 	'16' => 'Ma',
 	 	'17' => 'Me',
 	 	'18' => 'Je',
 	 	'19' => 'Ve',
 	 	'20' => 'Sa',
 	 	'21' => 'Di',
 	 	'22' => 'Lu',
 	 	'23' => 'Ma',
 	 	'24' => 'Me',
 	 	'25' => 'Je',
 	 	'26' => 'Ve'
 );

foreach ($kabaret_dates as $key => $value) {
		
		$date_dispo_class = '';
		
		if ( in_array( $value ." ". $key ." janvier", $d_user_kab_info["dispo"] )) {
			$date_dispo_class = ' kab-cal-dispo';
		}
		
		echo '<div class="kab-cal-jour cal-jour-'.strtolower($value).' '.$date_dispo_class.'">';
			
			echo '<div class="jour-name">'.$value.'</div>';
			
			echo '<div class="jour-nr">'.$key.'</div>';
			
		echo '</div>';
}

?>
</div><!-- #kino-presences-->
<?php

/*
 * Second part
 ****************
 Fonctions kabaret etc
 
*/

?>
<div class="kino-kabaret-infos">
	<?php 
		

// 3) Role
        	 echo '<div class="role-block">';
        	 if ( in_array( "realisateur-kab", $d_user_kab_info["participation"] )) {

        	 		echo '<div class="role-real">Réalisateur';
        	 		// niveau?
        	 			if (!empty( $d_user_kab_info["real-niveau"] )) {
    	 						echo ' ['.kino_process_niveau(
    	 							$d_user_kab_info["real-niveau"]).']';
        	 			}
        	 			
        	 		// session?
        	 		$kino_session_attrib = bp_get_profile_field_data( array(
      	 				'field'   => $kino_fields['session-attribuee'],
      	 				'user_id' => $kino_userid
        	 		) );
        	 		$kino_session_short = mb_substr($kino_session_attrib, 0, 9);
        	 		
        	 		if (!empty($kino_session_short)) {
        	 			echo ' ['.$kino_session_short.']';
        	 		}
        	 		echo '</div>';
        	 	}
        	 	
        	 	if ( in_array( "technicien-kab", $d_user_kab_info["participation"] )) {
        	 		echo '<div class="role-tech">Artisan-ne / Technicien-ne';
        	 		// niveau?
  	 					if (!empty( $d_user_kab_info["tech-niveau"] )) {
	 							echo ' ['.kino_process_niveau( 
	 								$d_user_kab_info["tech-niveau"] ).']';
  	 					}
        	 		echo '</div>';
        	 	}
        	 	
        	 	if ( in_array( "comedien-kab", $d_user_kab_info["participation"] )) {
        	 		echo '<div class="role-comed">Comédien-ne';
        	 		// niveau?
        	 		if (!empty( $d_user_kab_info["comedien-niveau"] )) {
      	 					echo ' ['.kino_process_niveau( 
      	 						$d_user_kab_info["comedien-niveau"] ).']';
        	 			}
        	 		echo '</div>';
        	 	}
        	 	
        	 	echo '</div>'; // .role-block
        	 	
        	 	// Scénario
        	 	
        	 	if ( $d_user_kab_info["real-coaching-scenario"] ) {
        	 				
      	 			echo kino_make_user_field_markup( 
      	 				'Mon scénario pour le Kino Kabaret 2018', 
      	 				$d_user_kab_info['real-coaching-scenario'] );
        	 	}
        	 	
        	 	if ( $d_user_kab_info["comp-scenario-file"] ) {
        	 					
        	 			echo kino_make_user_field_markup( 
        	 				'Mon scénario pour le Kino Kabaret 2018', 
        	 				$d_user_kab_info['comp-scenario-file'] );
        	 	}
        	 	
        	 	
        	 	if ( $d_user_kab_info["dispo-partiel"] ) {
        	 			
        	 		echo kino_make_user_field_markup( 
        	 			'Disponibilités partielles', 
        	 			$d_user_kab_info['dispo-partiel'] );
        	 	}
        	 	
        	 	
        	 	
	 ?>
	</div><!-- .kino-kabaret-infos -->

</div> <!-- bp-kino-kabaret -->
<?php


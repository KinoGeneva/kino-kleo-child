<?php
/**
 * Un template pour imprimer les Badges
 */
?>
<!doctype html>
<html class="no-js" lang="" moznomarginboxes mozdisallowselectionprint>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Impression des Fiches</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
				<meta name="robots" content="noindex, nofollow">
				
				<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700|Roboto:500,300' rel='stylesheet' type='text/css'>

    </head>
    <body>
<?php

$url = get_stylesheet_directory_uri();

?>

<style>

body {
	-webkit-print-color-adjust: exact;
	font-family: 'Roboto Condensed', Roboto, sans-serif;
	font-weight: 400;
	color: #000;
}

/*
 * Calcul largeur
 * A4: 210 mm
 * marge: 20 + 20
 * reste: 70
*/


.profile {
	width: 170mm;
	margin-left: auto;
	margin-right: auto;
}

.kp-photo img {
	height: 90mm;
	width: auto;
	margin-left: auto;
	margin-right: auto;
	margin-bottom: 0mm;
	display: block;	
}

.identity-name {
	text-transform: uppercase;
	font-weight: 700;
	font-size: 24pt;
	text-align: center;
	margin-top: 4mm;
	margin-bottom: 1mm;
}

.identity-info {
	text-align: center;
	font-size: 10pt;
	margin-top: 0mm;
}

.kabaret-role {
	margin-top: 0.2em;
	margin-bottom: 0mm;
	font-weight: 400;
	font-size: 16pt;
	text-transform: uppercase;
	text-align: center;
}

.kabaret-role .role-real {
	color: #00A650 !important;
	word-break: keep-all;
}
.kabaret-role .role-tech  {
	color: #0054A6 !important;
	word-break: keep-all;
}
.kabaret-role .role-comed  {
	color: #ED1B23 !important;
	word-break: keep-all;
}

.kabaret-role .role-staff {
	color: #ED0081 !important;
	word-break: keep-all;
}

.kabaret-role .kp-commalist {
	color: #000 !important;
	text-transform: lowercase;
	font-size: 10pt;
}

.titre-section {
	text-transform: uppercase;
	font-weight: 700;
	font-size: 12pt;
	margin-bottom: 1mm;
	clear: left;
}

.paragraphe {
	font-size: 10pt;
	font-family: Roboto, sans-serif;
	font-weight: 300;
	margin-top: 0mm;
	margin-bottom: 5mm;
}

.longtext {
	font-size: 9pt;
	-webkit-column-count: 2; /* Chrome, Safari, Opera */
	-moz-column-count: 2; /* Firefox */
	column-count: 2;
	
	-webkit-column-gap: 6mm; /* Chrome, Safari, Opera */
	-moz-column-gap: 6mm; /* Firefox */
	column-gap: 6mm;
	
	text-align: justify;
	text-justify: inter-word;
}

.superlongtext {
	font-size: 8pt;
}

.paragraphe strong, .paragraphe b {
	font-weight: 500;
}

.starbox {
	text-align: center;
	height: 10mm;
	margin-top: 5mm;
}

.starbox .star {
	padding-right: 3mm;
	max-height: 100%;
	width: auto;
}

.starbox .star:last-of-type {
	padding-right: 0mm;
}

/*
 * 170 - 140 : reste 30 
 */

.calendrier {
	width: 140mm;
	margin-left: 15mm;
	margin-top: 5mm;
	margin-bottom: 5mm;
}

.calendrier-list {
	min-height: 16mm;
}

.calendrier-jour {
	float: left;
	display: block;
	background-color: rgb(235, 235, 235);
	font-size: 18pt;
	color: #fff;
	width: 10mm;
	margin-right: 3mm;
	text-align: center;
	padding-top: 2mm;
	padding-bottom: 1mm;
}

.calendrier-dispo {
	/* background-color: #000; */
	color: #000;
	background-color: rgb(190, 190, 190);
}

.calendrier .paragraph {
	margin-bottom: 0mm;
}

.jour-name {
	font-size: 12pt;
	text-transform: uppercase;
}

.calendrier-jour:last-of-type {
	margin-right: 0mm;
}

.kp-pointlist:after {
	content: " \2219 "; /* bullet */
}
.kp-pointlist {
	padding-right: 0.2em;
}
.kp-pointlist:last-of-type:after {
	content: "";
}
.kp-pointlist:last-of-type {
	padding-right: 0;
}

.kp-commalist:after {
	content: ", "; /* bullet */
}
.kp-commalist {
	/* padding-right: 0.2em; */
}
.kp-commalist:last-of-type:after {
	content: "";
}
.kp-commalist:last-of-type {
	/* padding-right: 0; */
}


@media print {
	
	.print-profile {
	   page-break-after: always;
	   page-break-inside: avoid;
	}
	
	.profile {
		margin-left: 0mm;
		margin-right: 0mm;
	}
	
	@page {
	  size: A4 portrait;
	  
	  /* this affects the margin in the printer settings */ 
	  /* Marges pour les planches de badges:
	   * 20 mm en haut, 17 (auto) en bas, 5 sur les côtés
	  */
	  
	  margin: 5mm 10mm auto 15mm;
	  padding: 0mm;
	  
	}
	
	.no-print {
		display: none;
	}
	
} /* end @media print*/	

</style>

<?php 

/*
 ** Concept:
 
 Sans paramètres supplémentaires, cette page affiche une liste des participants au Kino Kabaret 2016, triés par: Nom de famille.

 Paramètres possibles: 
 
 * kinorole
 **************
 tri par groupe (Réalisateur, Comédien, Technicien)
 query variable: kinorole - accepte: 
 - realisateur, 
 - comedien, 
 - technicien,
 - pending (pour faire des tests!)
 as in : ?kinorole=realisateur = montre uniquement le groupe realisateur

 * kinodate
 **************
 tri par date d'inscription = ancienneté en jours
 query variable : kinodate
 as in : ?kinodate=5 = montre uniquement les utilisateurs inscrits dans les derniers 5 jours
 
*/

	//***************************************
	
	$kino_fields = kino_test_fields();
	
	$kino_debug_mode = ( get_query_var('kinodebug') ) ? get_query_var('kinodebug') : false;

	// Default group:  
	$ids_of_kino_participants = get_objects_in_term( 
		$kino_fields['group-kino-complete'], 
		'user-group' 
	);

	$kinorole_var = ( get_query_var('kinorole') ) ? get_query_var('kinorole') : false;
	
	if ( !empty($kinorole_var) ) {
	
		
		if ( $kinorole_var == 'realisateur' ) {
		// on restreint la liste:
		
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-real-kabaret'], 
				'user-group' 
			);
			
			// 
			if ( $kino_debug_mode == 'on' ) {
				echo '<pre>';
				echo 'list of IDs in "group-real-kabaret": ';
					var_dump($ids_of_kino_participants);
				echo '</pre>';
			}
			
		} // end 'realisateur'
		
		// Comédiens
		if ( $kinorole_var == 'comedien' ) {
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-comp-comedien'] , 
				'user-competences'  
			);
		}
		
		// Image
		if ( $kinorole_var == 'image' ) {
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-comp-image'] , 
				'user-competences'  
			);
		}
		
		// Postprod Image
		if ( $kinorole_var == 'postprod' ) {
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-comp-postprod-image'] , 
				'user-competences'  
			);
		}
		
		// Postprod Son
		if ( $kinorole_var == 'son' ) {
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-comp-postprod-son'] , 
				'user-competences'  
			);
		}
		
		// Production & Scénario
		if ( $kinorole_var == 'production' ) {
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-comp-prod-scenar'] , 
				'user-competences'  
			);
		}
		
		// Direction artistique & HMC
		if ( $kinorole_var == 'dirart' ) {
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-comp-da-hmc'] , 
				'user-competences'  
			);
		}
		
		// Autres talents
		if ( $kinorole_var == 'autres' ) {
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-comp-autres'] , 
				'user-competences'  
			);
		}
		
		// Staff
		if ( $kinorole_var == 'staff' ) {
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-comp-staff'] , 
				'user-competences'  
			);
		}
		
		// Sessions
		if ( $kinorole_var == 'session1' ) {
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-session-un'] , 
				'user-group'  
			);
		}
		if ( $kinorole_var == 'session2' ) {
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-session-deux'] , 
				'user-group'  
			);
		}
		if ( $kinorole_var == 'session3' ) {
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-session-trois'] , 
				'user-group'  
			);
		}
		if ( $kinorole_var == 'session4' ) {
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-session-superhuit'] , 
				'user-group'  
			);
		}
		
		// Pending
		if ( $kinorole_var == 'pending' ) {
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-kino-pending'], 
				'user-group' 
			);
		} // end 'pending'
		

	} // end $kinorole_var testing
	
	$kinodate_var = ( get_query_var('kinodate') ) ? get_query_var('kinodate') : false;
	
	if ( $kinodate_var ) {
	
		$kinodate_parameter = $kinodate_var -1;
		
		$kinodate_offset = date('Y-m-d\TH:i:s', strtotime('-'.$kinodate_parameter.' hours'));
		
	} else {

		$kinodate_offset = date('Y-m-d\TH:i:s', strtotime('-720 hours'));
	}
	
	// Test Kino-ID:
	
	$kino_id_var = ( get_query_var('id') ) ? get_query_var('id') : false;
	
	if ( $kino_id_var ) {
		// add parameter to the users query	
		$ids_of_kino_participants = $kino_id_var;
	}

 ?>

<!-- Begin Article -->
<article>
    <div class="article-content">
        
        <?php 
        
        /*
         * Liste: des membres
        *************************
        ****/
        
        $user_fields = array( 
        	'user_login', 
        	'user_nicename',  // = slug
        	'display_name',
        	'user_email', 
        	'ID' 
        );
        
        // order by: last_name
        
        if (empty($ids_of_kino_participants)) {
        	// il faut filtrer, sinon la page sera trop énorme!
        	$ids_of_kino_participants = get_objects_in_term( 
        		$kino_fields['group-kino-complete'], 
        		'user-group' 
        	);
        }
        
        $user_query = new WP_User_Query( array( 
        	'include' => $ids_of_kino_participants, // IDs incluses
        	// 'number' => 36,
        	'meta_key'  => 'last_name',
        	'orderby'  => 'last_name',
        	'meta_query' => array(
        	        array(
        	            'key' => 'kino_timestamp_complete',
        	            'value' => $kinodate_offset,
        	            'type' => 'CHAR',
        	            'compare' => '>'
        	        )
        	    )
        ) );
        
        // Show some info to the Admin:
        ?>
        
        <p class="admin-note no-print"><b>NOTES:</b><br/> <?php 
        
        if ( !empty($kinorole_var) ) {
        	echo '<b>Filtrage par rôle:</b> '.$kinorole_var.'<br/>';
        }
        if ( !empty($kinodate_var) ) {
        	echo '<b>Filtrage par fraîcheur:</b> profils complétés dans les '.$kinodate_var.' dernières heures (limite: '.$kinodate_offset.')<br/>';
        }
        if ( ! empty( $user_query->results ) ) {
        	echo '<b>Nombre de fiches: </b>'.count($user_query->results).'<br/>';
        }
        
        echo '<b>Filtres par groupe:</b> ';
        
        echo '<a href="https://kinogeneva.ch/impression-fiches/?kinorole=realisateur">Réalisateurs</a> / ';
        
        echo '<a href="https://kinogeneva.ch/impression-fiches/?kinorole=comedien">Comédiens</a> / ';
        
        echo '<a href="https://kinogeneva.ch/impression-fiches/?kinorole=image">Image</a> / ';
        
        echo '<a href="https://kinogeneva.ch/impression-fiches/?kinorole=postprod">Postproduction Image</a> / ';
        
        echo '<a href="https://kinogeneva.ch/impression-fiches/?kinorole=son">Son & Postproduction son</a> / ';
        
        echo '<a href="https://kinogeneva.ch/impression-fiches/?kinorole=production">Production & Scénario</a> / ';
        
        echo '<a href="https://kinogeneva.ch/impression-fiches/?kinorole=dirart">Direction artistique & HMC</a> / ';
        
        echo '<a href="https://kinogeneva.ch/impression-fiches/?kinorole=autres">Autres talents</a> / ';
        
        echo '<a href="https://kinogeneva.ch/impression-fiches/?kinorole=staff">Staff</a> / ';
        
        echo '<a href="https://kinogeneva.ch/impression-fiches/?id=8,203">Par identifiant (séparés par des virgules)</a> / ';
        
        echo '<a href="https://kinogeneva.ch/impression-fiches/?kinodate=2">Par âge du profil en heures (depuis que le profil est complet)</a> </br>';
        
        
        
        echo '<b>Sessions:</b> ';
        
        echo '<a href="https://kinogeneva.ch/impression-fiches/?kinorole=session1">Session #1</a> / ';
        echo '<a href="https://kinogeneva.ch/impression-fiches/?kinorole=session2">Session #2</a> / ';
        echo '<a href="https://kinogeneva.ch/impression-fiches/?kinorole=session3">Session #3</a> / ';
        echo '<a href="https://kinogeneva.ch/impression-fiches/?kinorole=session4">Session #4</a> </br>';
        
        
        // https://kinogeneva.ch/impression-fiches/?kinorole=dirart
        
         ?></p>
        
        <?php
        
        // User Loop
        if ( ! empty( $user_query->results ) ) {
        	foreach ( $user_query->results as $user ) {
        	
        		$kino_userid = $user->ID ;
        		
        		$kino_userdata = kino_user_fields($kino_userid , $kino_fields );

        		// tester si Transient, sinon charger les données.
//        		$transientname = 'userdata'.$user->ID;
//        		
//    		if ( false === ( $kino_userdata = get_transient( $transientname ) ) ) {
//    		    // It wasn't there, so regenerate the data and save the transient
//    		     $kino_userdata = kino_user_fields($kino_userid , $kino_fields );
//    		     
//    		     set_transient( $transientname, $kino_userdata, 60 );
//    		     //  * HOUR_IN_SECONDS
//    		     echo '<p>we just defined transient '.$transientname.'</p>';
//    		}
    		
//    		echo '<pre> Userdata: ';
//    		var_dump($kino_userdata);
//    		echo '</pre>';
        		
        		// Phase 2 : Générer le HTML de la fiche
        		
        		/*
        		 * Eléments à produire:
        		 * 1) nom
        		 * 2) adr, tel, email
        		 * 3) Rôle kino
        		 * 4) Etoiles
        		 
        		 * 5) Dates de présence
        		 
        		 * 6) Présentation
        		 * 7) Compétences Comédien
        		 * 8) Compétences Technicien
        		 * 9) Equipements
        		*/
        		
        	?>
        	<div class="profile clearfix print-profile">
        	
        	<?php
        	
        	 // Générer le HTML de la fiche
        	 
        	 if ( $kino_userdata["photo"] ) {
        	 	echo '<div class="kp-par kp-photo photo">';
        	 	echo $kino_userdata["photo"];
        	 	echo '</div>';
        	 } else {
        	 	// utiliser photo profil: 
        	 		$kino_img_url = bp_core_fetch_avatar( array( 
        	 			'item_id' => $kino_userid, 
        	 			// 'no_grav' => true,
        	 			'type' => 'full', 
        	 			'html' => true) );
        	 			echo '<div class="kp-par kp-photo photo">';
        	 			echo $kino_img_url;
        	 			echo '</div>';
        	 }
        	 
        	 // Identité 
        	 
        	 echo '<h2 class="identity-name">'.$user->display_name.'</h2>';

        	 echo '<p class="identity-info">';
        	 echo '<span class="kp-pointlist">'.$kino_userdata["ville"].', '.$kino_userdata["pays"].' </span>';
        	 
        	 echo '<span class="kp-pointlist">'.$kino_userdata["tel"].' </span>';
        	 echo '<span class="kp-pointlist">'.$user->user_email.' </span>';
        	 echo '</p>';
        	 
        	 // Role
        	 
        	 // test if STAFF
        	 $kino_is_staff = bp_get_profile_field_data( array(
        	 				'field'   => 1582,
        	 				'user_id' => $kino_userid
        	 ) );
        	 
        	 if ( in_array( "realisateur-2016", $kino_userdata["participation"] )
        	 	|| in_array( "technicien-2016", $kino_userdata["participation"] )
        	 	|| in_array( "comedien-2016", $kino_userdata["participation"] )
        	 	|| (!empty($kino_userdata["fonctions-staff"])) ) {
        	 
        	 	echo '<h3 class="kabaret-role">';
        	 		
        	 		if ( !empty($kino_userdata["fonctions-staff"]) ) {
        	 				echo '<span class="kp-pointlist role-staff">Staff';
        	 				
        	 					foreach ( $kino_userdata["fonctions-staff"] as $key => $value) {
        	 							echo '<span class="kp-commalist"> '.$value.'</span>';
        	 						}
        	 						
        	 				echo '</span>';
        	 			}
        	 		
        	 		if ( in_array( "realisateur-2016", $kino_userdata["participation"] )) {
        	 			echo '<span class="kp-pointlist role-real"><nobr>Réalisateur-trice';
        	 			
        	 			// niveau?
        	 					$kino_niveau = bp_get_profile_field_data( array(
        	 								'field'   => 1079,
        	 								'user_id' => $kino_userid
        	 						) );
        	 					if (!empty($kino_niveau)) {
        	 								echo ' ['.kino_process_niveau($kino_niveau).']';
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
        	 					
        	 			echo '</nobr></span>';
        	 			
        	 		}
        	 		
        	 		if ( in_array( "technicien-2016", $kino_userdata["participation"] )) {
        	 			echo '<span class="kp-pointlist role-tech"><nobr>Artisan-ne / technicien-ne';
        	 			
        	 			// niveau?
        	 						$kino_niveau = bp_get_profile_field_data( array(
        	 									'field'   => 1075,
        	 									'user_id' => $kino_userid
        	 							) );
        	 						if (!empty($kino_niveau)) {
        	 									echo ' ['.kino_process_niveau($kino_niveau).']';
        	 						}
        	 			
        	 			echo '</nobr></span>';
        	 		}
        	 		
        	 		if ( in_array( "comedien-2016", $kino_userdata["participation"] )) {
        	 			echo '<span class="kp-pointlist role-comed"><nobr>Comédien-ne';
        	 			
        	 			// niveau?
        	 						$kino_niveau = bp_get_profile_field_data( array(
        	 									'field'   => 927,
        	 									'user_id' => $kino_userid
        	 							) );
        	 						if (!empty($kino_niveau)) {
        	 									echo ' ['.kino_process_niveau($kino_niveau).']';
        	 						}
        	 			
        	 			echo '</nobr></span>';
        	 		}
        	 		
        	 	echo '</h3>';
        	 
        	 }
        	 
        	 // Etoiles
        	 // **************************
        	 
        	 echo '<div class="starbox">';
        	 		
        	 		if (!empty($kino_userdata["fonctions-staff"]) ) {
        	 				echo '<img class="star" src="'. $url .'/img/badges/Star_STAFF.png" />';
        	 			}
        	 		
        	 		if ( in_array( "realisateur-2016", $kino_userdata["participation"] )) {
        	 				echo '<img class="star" src="'. $url .'/img/badges/Star_G.png" />';
        	 			}
        	 			
        	 			if ( in_array( "technicien-2016", $kino_userdata["participation"] )) {
        	 				echo '<img class="star" src="'. $url .'/img/badges/Star_B.png" />';
        	 			}
        	 			
        	 			if ( in_array( "comedien-2016", $kino_userdata["participation"] )) {
        	 				echo '<img class="star" src="'. $url .'/img/badges/Star_R.png" />';
        	 			}
        	 
        	 echo '</div>';
        	 
        	 // Disponibilités
        	 // **************************
        	 
        	 // echo '<h4 class="titre-section">Disponibilités</h4>';
        	 
        	 echo '<div class="calendrier">';
        	 echo '<div class="calendrier-list">';
        	 
        	 $kabaret_dates = array(
	        	 	'18' => 'Lu',
	        	 	'19' => 'Ma',
	        	 	'20' => 'Me',
	        	 	'21' => 'Je',
	        	 	'22' => 'Ve',
	        	 	'23' => 'Sa',
	        	 	'24' => 'Di',
	        	 	'25' => 'Lu',
	        	 	'26' => 'Ma',
	        	 	'27' => 'Me',
	        	 	'28' => 'Je'
        	 );
        	 	
//        	 echo '<pre> dispo: ';
//        	 var_dump($kino_userdata["dispo"]);
//        	 echo '</pre>';	
        	 	
        	 foreach ($kabaret_dates as $key => $value) {
        	 		
        	 		$date_dispo_class = '';
        	 		
        	 		if ( in_array( $key." janvier", $kino_userdata["dispo"] )) {
        	 			$date_dispo_class = ' calendrier-dispo';
        	 		}
        	 		
        	 		echo '<div class="calendrier-jour'.$date_dispo_class.'">';
        	 			
        	 			echo '<div class="jour-name">'.$value.'</div>';
        	 			
        	 			echo '<div class="jour-nr">'.$key.'</div>';
        	 			
        	 		echo '</div>';
        	 }
        	 
        	 echo '</div>';
        	 
//        	 if ( $kino_userdata["dispo"] ) {
//        	 			echo '<p class="kp-par">';
//        	 			foreach ( $kino_userdata["dispo"] as $key => $value) {
//        	 				echo '<span class="jour-dispo">'.substr($value, 0, 2).'</span>';
//        	 			}
//        	 			echo ' janvier</p>';
//        	 }
        	 
        	 if ( $kino_userdata["dispo-partiel"] ) {
        	 		
        	 		echo '<h4 class="titre-section">Disponibilités partielles</h4>';
        	 		
        	 		echo '<p class="paragraphe">'.$kino_userdata["dispo-partiel"].'</p>';
        	 	}
        	 
        	 echo '</div>';
        	 
        	 // Présentation
        	 // **************************
        	 
        	 echo '<h4 class="titre-section">Présentation</h4>';
        	 
        	 $kino_present_css = '';
        	 
        	 if ( strlen($kino_userdata["presentation"]) > 500 ) {
        	 	$kino_present_css .= ' longtext';
        	 }
        	 
        	 if ( strlen($kino_userdata["presentation"]) > 900 ) {
        	 	$kino_present_css .= ' superlongtext';
        	 }
        	 
        	 echo '<div class="paragraphe'. $kino_present_css .'">';
        	 echo $kino_userdata["presentation"];
        	 echo '</div>';
        	 
						
        	 // Compétences
        	 
        	 // Section Comédien
        	 
        	 if ( in_array( "comedien-2016", $kino_userdata["participation"] )) {
        	 	
        	 		echo '<h4 class="titre-section">Comédien-ne</h4>';
        	 		
        	 		echo '<div class="paragraphe">';
        	 		
        	 		// test des champs comédien
        	 		
        	 		if ( !empty($kino_userdata["age-camera-min"]) ) {
        	 	 		echo '<span class="kp-pointlist">';
        	 	 		echo '<strong>Age min: </strong>';
        	 	 		echo $kino_userdata["age-camera-min"];
        	 	 		echo '</span>';
        	 		}
        	 		if ( !empty($kino_userdata["age-camera-max"]) ) {
        	 				echo '<span class="kp-pointlist">';
        	 				echo '<strong>Age max: </strong>';
        	 				echo $kino_userdata["age-camera-max"];
        	 				echo '</span>';
        	 		}
        	 		
        	 		if ( !empty($kino_userdata["langue-mat"]) ) {
        	 			echo '<span class="kp-pointlist">';
        	 			echo '<strong>Langue maternelle: </strong>';
        	 			
        	 			if ( is_string($kino_userdata["langue-mat"]) ) {
        	 				echo '<span class="kp-commalist">'.$kino_userdata["langue-mat"].'</span>';
        	 			} else if ( is_array($kino_userdata["langue-mat"]) ) {
        	 				foreach ( $kino_userdata["langue-mat"] as $key => $value) {
        	 					if ( $value == 'Autre' ) { } else {
        	 						echo '<span class="kp-commalist">'.$value.'</span>'; 
        	 						}
        	 				}
        	 			}
        	 			
        	 			if ( !empty($kino_userdata["langue-mat-autre"]) ) {
        	 				echo '<span class="kp-commalist">'.$kino_userdata["langue-mat-autre"].'</span>';
        	 			}
        	 			
        	 			echo '</span>';
        	 		}
        	 		
        	 		// Langues Parlées
        	 		if ( !empty($kino_userdata["langues-parlees"]) ) {
        	 				echo '<span class="kp-pointlist">';
        	 				echo '<strong>Langues parlées: </strong>';
        	 				
        	 				if ( is_string($kino_userdata["langues-parlees"]) ) {
        	 						echo '<span class="kp-commalist">'.$kino_userdata["langues-parlees"].'</span>';
        	 					} else if ( is_array($kino_userdata["langues-parlees"]) ) {
        	 						foreach ( $kino_userdata["langues-parlees"] as $key => $value) {
        	 							if ( $value == 'Autre' ) { } else {
        	 								echo '<span class="kp-commalist">'.$value.'</span>'; 
        	 								}
        	 						}
        	 				}
        	 				
        	 				if ( !empty($kino_userdata["langues-parlees-autre"]) ) {
        	 							echo '<span class="kp-commalist">'.$kino_userdata["langues-parlees-autre"].'</span>';
        	 				}
        	 				
        	 				echo '</span>';
        	 		}
        	 		
        	 		// Langues Jouées
        	 		if ( !empty($kino_userdata["langues-jouees"]) ) {
        	 					echo '<span class="kp-pointlist">';
        	 					echo '<strong>Langues jouées: </strong>';
        	 					
        	 					if ( is_string($kino_userdata["langues-jouees"]) ) {
        	 							echo $kino_userdata["langues-jouees"];
        	 					} else if ( is_array($kino_userdata["langues-jouees"]) ) {
        	 							foreach ( $kino_userdata["langues-jouees"] as $key => $value) {
        	 								if ( $value == 'Autre' ) { } else {
        	 									echo '<span class="kp-commalist">'.$value.'</span>'; 
        	 									}
        	 							}
        	 					}
        	 					
        	 					if ( !empty($kino_userdata["langues-jouees-autre"]) ) {
        	 							echo '<span class="kp-commalist">'.$kino_userdata["langues-jouees-autre"].'</span>';
        	 					}
        	 					
        	 					echo '</span>';
        	 			}
        	 			
    	 			// Talents
    	 				if ( !empty($kino_userdata["talents"]) ) {
    	 							echo '<span class="kp-pointlist">';
    	 							echo '<strong>Talents particuliers: </strong>';
    	 							
    	 							if ( is_string($kino_userdata["talents"]) ) {
    	 									echo $kino_userdata["talents"];
    	 							} else if ( is_array($kino_userdata["talents"]) ) {
    	 									foreach ( $kino_userdata["talents"] as $key => $value) {
    	 										if ( $value == 'Autre' ) { } else {
    	 											echo '<span class="kp-commalist">'.$value.'</span>'; 
    	 											}
    	 									}
    	 							}
    	 							
    	 							if ( !empty($kino_userdata["talents-autre"]) ) {
    	 								echo '<span class="kp-commalist">'.$kino_userdata["talents-autre"].'</span>';
    	 							}
    	 							
    	 							echo '</span>';
    	 					}
        	 	
        	 	echo '</div>';
        	 			
        	 } // end Section Comedien
        	
        	 
        	 // Section Artisan / technicien
        	 
        	 if ( in_array( "technicien-2016", $kino_userdata["participation"] )) {
        	 	
        	 		echo '<h4 class="titre-section">Artisan-ne / technicien-ne</h4>';
        	 		
        	 		echo '<div class="competences paragraphe">';
        	 		
        	 		// test des champs technicien
        	 		
        	 		if ( $kino_userdata["comp-production"] ) {
        	 			echo '<span class="kp-pointlist">';
        	 			echo '<strong>Production: </strong>';
        	 			foreach ( $kino_userdata["comp-production"] as $key => $value) {
        	 				echo '<span class="kp-pointlist">'.$value.'</span>';
        	 			}
        	 			echo '</span>';
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-scenario"] ) {
      	 				echo '<span class="kp-pointlist">';
      	 				echo '<strong>Scénario: </strong>';
      	 				foreach ( $kino_userdata["comp-scenario"] as $key => $value) {
      	 					echo '<span class="kp-commalist">'.$value.'</span>'; }
      	 				echo '</span>';
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-realisation"] ) {
	  	 					echo '<span class="kp-pointlist">';
	  	 					echo '<strong>Réalisation: </strong>';
	  	 					foreach ( $kino_userdata["comp-realisation"] as $key => $value) {
	  	 						echo '<span class="kp-commalist">'.$value.'</span>'; }
	  	 					echo '</span>';
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-image"] ) {
        	 				echo '<span class="kp-pointlist">';
        	 				echo '<strong>Image: </strong>';
        	 				foreach ( $kino_userdata["comp-image"] as $key => $value) {
        	 					echo '<span class="kp-commalist">'.$value.'</span>'; }
        	 				echo '</span>';
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-postprod-image"] ) {
        	 				echo '<span class="kp-pointlist">';
        	 				echo '<strong>Postproduction image: </strong>';
        	 				foreach ( $kino_userdata["comp-postprod-image"] as $key => $value) {
        	 					echo '<span class="kp-commalist">'.$value.'</span>'; }
        	 				echo '</span>';
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-son"] ) {
      	 					echo '<span class="kp-pointlist">';
      	 					echo '<strong>Son: </strong>';
      	 					foreach ( $kino_userdata["comp-son"] as $key => $value) {
      	 						echo '<span class="kp-commalist">'.$value.'</span>'; }
      	 					echo '</span>';
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-postprod-son"] ) {
    	 						echo '<span class="kp-pointlist">';
    	 						echo '<strong>Postproduction son: </strong>';
    	 						foreach ( $kino_userdata["comp-postprod-son"] as $key => $value) {
    	 							echo '<span class="kp-commalist">'.$value.'</span>'; }
    	 						echo '</span>';
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-direction-artistique"] ) {
  	 							echo '<span class="kp-pointlist">';
  	 							echo '<strong>Direction artistique: </strong>';
  	 							foreach ( $kino_userdata["comp-direction-artistique"] as $key => $value) {
  	 								echo '<span class="kp-commalist">'.$value.'</span>'; }
  	 							echo '</span>';
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-hmc"] ) {
  	 							echo '<span class="kp-pointlist">';
  	 							echo '<strong>HMC: </strong>';
  	 							foreach ( $kino_userdata["comp-hmc"] as $key => $value) {
  	 								echo '<span class="kp-commalist">'.$value.'</span>'; }
  	 							echo '</span>';
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-autres-liste"] ) {
  	 							echo '<span class="kp-pointlist">';
  	 							echo '<strong>Autres talents: </strong>';
  	 							foreach ( $kino_userdata["comp-autres-liste"] as $key => $value) {
  	 								if ( $value == 'Autre' ) { } else {
  	 								echo '<span class="kp-commalist">'.$value.'</span>'; 
  	 								}
  	 							}
  	 							echo '</span>';
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-autres-champ"] ) {
  	 							echo '<span class="kp-pointlist">';
  	 							// echo '<strong>Autres: </strong>';
  	 							echo $kino_userdata["comp-autres-champ"];
  	 							echo '</span>';
        	 		}
        	 		
        	 		echo '</div>';
        	 		
        	 		
        	 	} // end Technicien-2016
        	 
        	 // Equipement à disposition
        	
        	if ( (!empty($kino_userdata["equipement"])) || (!empty($kino_userdata["equipement-spec"])) || ( $kino_userdata["vehicule"] == 'OUI' ) ) {
        						
        						?>
        						<h4 class="titre-section">Équipements à disposition</h4>
        						<?php
        						
        						echo '<div class="equipements paragraphe">';
        						
        					if ( $kino_userdata["equipement"] ) {	
        						foreach ( $kino_userdata["equipement"] as $key => $value) {
        							if ( $value == 'autre') {
        							} else {
        							echo '<span class="kp-pointlist">'.$value.'</span>';
        							}
        						}
        					}
        					
        					if ( $kino_userdata["equipement-spec"] ) {
        								echo '<span class="kp-pointlist">';
        								echo $kino_userdata["equipement-spec"];
        								echo '</span>';
        					}
        						
        					if ( $kino_userdata["vehicule"] == 'OUI' ) {
  									echo '<span class="kp-pointlist">';
  									echo '<strong>Véhicule disponible: </strong>';
  									echo $kino_userdata["vehicule-remarque"];
  									echo '</span>';
        					}
        						
        						echo '</div>';
        	} // end Equipement
        		
        	
        	 ?>
        	 </div>
        	 <div class="page-break"></div>
        	 <?php
        	
        	} // end foreach
        } // end $user_query
        
        
         ?>
        
    </div><!--end article-content-->

    <?php  ?>
</article>
<!-- End  Article -->


    </body>
</html>
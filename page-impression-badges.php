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
        <title>Impression des Badges</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
				<meta name="robots" content="noindex, nofollow">
				
				<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700|Roboto:500,300' rel='stylesheet' type='text/css'>

    </head>
    <body>
<?php

if ( current_user_can( 'publish_pages' ) ) {

$url = get_stylesheet_directory_uri();

?>

<style>

body {
	-webkit-print-color-adjust: exact;
	font-family: 'Roboto Condensed', Roboto, sans-serif;
	font-weight: 400;
	color: #000;
}

.kp-h2,
.kp-h3,
.kp-h4 {
	text-transform: uppercase;
	font-weight: 700;
}


.print-badge {
/*	box-sizing: border-box;*/
	float: left !important;
}

.print-badge {
	border: 0px dashed #000 !important;
	width: 100mm;
	height: 65mm;
	overflow: hidden;
	background-size: 100% 100% !important;
}

.print-badge-inside {
	position: relative;
}

.print-badge {
	background-image: url("<?php echo $url; ?>/img/badges2018/Fond_STAFF.png")!important;
	-webkit-print-color-adjust: exact;
}

.print-badge.role-tech {
	background-image: url("<?php echo $url; ?>/img/badges2018/Fond_B.png")!important;
}
.print-badge.role-comed {
	background-image: url("<?php echo $url; ?>/img/badges2018/Fond_R.png")!important;
}
.print-badge.role-real {
	background-image: url("<?php echo $url; ?>/img/badges2018/Fond_G.png")!important;
}

.print-badge.role-tech-pro {
	background-image: url("<?php echo $url; ?>/img/badges2018/Fond_B.png")!important;
}
.print-badge.role-comed-pro {
	background-image: url("<?php echo $url; ?>/img/badges2018/Fond_R.png")!important;
}
.print-badge.role-real-pro {
	background-image: url("<?php echo $url; ?>/img/badges2018/Fond_G.png")!important;
}

.print-badge.role-staff {
	background-image: url("<?php echo $url; ?>/img/badges2018/Fond_STAFF.png")!important;
}

/*

.print-badge.role-real.role-tech { <?php // was : Fond_GB.png ?>
	background-image: url("<?php echo $url; ?>/img/badges2018/Fond_G.png")!important;
}

.print-badge.role-real.role-comed { <?php // was : Fond_RG.png ?>
	background-image: url("<?php echo $url; ?>/img/badges2018/Fond_G.png")!important;
}

.print-badge.role-tech.role-comed { <?php // was : Fond_RB.png ?>
	background-image: url("<?php echo $url; ?>/img/badges2018/Fond_R.png")!important;
}

.print-badge.role-real.role-tech.role-comed { <?php // was : Fond_RGB.png ?>
	background-image: url("<?php echo $url; ?>/img/badges2018/Fond_G.png")!important;
}

*/

.badge-photo,
.identity-block {
	display: block;
	position: absolute;
}

.badge-photo {
	width: 23mm;
	height: 23mm;
	top: 7.6mm;
	left: 5.8mm;
	background-size: cover !important;
	border-radius: 100%;
	border: 1mm solid rgb(203, 203, 203);
	box-sizing: border-box;
}

.identity-block {
	width: 62mm;
	height: auto;
	top: 7.6mm;
	left: 33mm;
}

.identity-name {
	font-size: 16pt;
	line-height: 1;
	padding: 0px;
	margin: 0 0 4mm 0;
	text-transform: uppercase;
	text-align: left;
}

.longname .identity-name {
	font-size: 14pt;
}
.verylongname .identity-name {
	font-size: 12pt;
}

.role-block {
	text-transform: uppercase;
	font-size: 9pt;
	font-weight: 400;
	font-family: "Roboto Condensed", sans-serif;
}

.role-block > div {
	line-height: 1.2;
	height: 16px;
}

.role-block .role-real {
	color: #00A650 !important;
}
.role-block .role-tech  {
	color: #0054A6 !important;
}
.role-block .role-comed  {
	color: #ED1B23 !important;
}

.baseline {
	position: absolute;
	top:  57mm;
	left: 14mm;
	width: 72mm;
	text-align: center;
	text-transform: uppercase;
	font-family: 'Roboto', sans-serif;
	font-weight: 300;
	font-size: 10pt;
}

.baseline .staff {
	color: #ED0081;
	font-weight: 500;
}

.starbox {
	position: absolute;
	top: 27mm;
	left: 18mm;
	width: 65mm;
	height: 18mm;
	text-align: center;
}

.stars-1 .starbox{
	top:  25mm;
	height: 22mm;
}

.stars-3 .starbox,
.stars-4 .starbox  {
	top: 32mm;
	height: 15mm;
}

.starbox .star {
	max-height: 100%;
	width: auto;
	padding-right: 3mm;
}

.starbox .star:last-of-type {
	padding-right: 0mm;
}

.talents {
	position: absolute;
	top: 45mm;
	/*left: 21mm;
	width: 58mm;*/
	height: 10mm;
	font-size: 8pt;
	font-family: Roboto, sans-serif;
	font-weight: 500;
	display: table;
	width: 68mm;
	left: 16mm;
}

.talents > div {
  display: table-cell;
  text-align: center;
  vertical-align: middle;
}

.stars-1 .talents {
	top: 48mm;
	height: 10mm;
}

.stars-2 .talents {
	top: 46mm;
	height: 10mm;
}

.stars-3 .talents,
.stars-4 .talents {
	top: 48mm;
	height: 8mm;
	
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

/* 
 * PRINT STYLES 
 *****************
 **/


@media print {
	
	.template-page,
	.col-sm-12,
	.container {
		margin: 0mm !important;
		padding: 0mm !important;
	}
	
	.main-title,
	#footer {
		display: none;
	}
	
	.print-badge {
		border: 0px solid #000;
		width: 100mm;
		height: 65mm;
	}
	
	.print-profile,
	.page-box {
	   page-break-after: always;
	   page-break-inside: avoid;
	}
	
	.no-print {
		display: none;
	}
	
	.page-box {
		position: relative;
		width: 200.5mm;
		height: 260mm;
		border: 0.2mm solid #ddd !important;
	}
	
	.badge-nr-1,
	.badge-nr-2,
	.badge-nr-3,
	.badge-nr-4,
	.badge-nr-5,
	.badge-nr-6,
	.badge-nr-7,
	.badge-nr-8 {
		position: absolute;
	}
	
	.badge-nr-1,
	.badge-nr-3,
	.badge-nr-5,
	.badge-nr-7 {
		border-right: 1mm solid #ddd !important;
		left: 0mm;
	}
		
	.badge-nr-2,
	.badge-nr-4,
	.badge-nr-6,
	.badge-nr-8 {
		top: 0mm;
		left: 100.5mm;
	}
	
	.badge-nr-1, .badge-nr-2 {
		top:  0mm;
	}
	
	.badge-nr-3, .badge-nr-4 {
		top:  65mm;
	}
	
	.badge-nr-5, .badge-nr-6 {
		top:  130mm;
	}
	
	.badge-nr-7, .badge-nr-8 {
		top:  195mm;
	}
	
	@page {
	  
	  size: A4 portrait;
	  
	  /* this affects the margin in the printer settings */ 
	  /* Marges pour les planches de badges:
	   * 20 mm en haut, 17 (auto) en bas, 5 sur les côtés
	  */
	  
	  margin: 7mm 4mm auto 4mm;
	  padding: 0mm;
	}
	
	
} /* end @media print*/	

</style>

<?php 

/*
 ** Concept:
 
 Sans paramètres supplémentaires, cette page affiche une liste des participants au Kino Kabaret, triés par: Nom de famille.

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
 tri par date d'inscription = ancienneté en heures
 query variable : kinodate
 as in : ?kinodate=5 = montre uniquement les utilisateurs inscrits dans les dernières 5 heures
 
 * kinodate
 **************
 ajouter ?kinodebug=on
 
*/

	//***************************************
	
	$kino_fields = kino_test_fields();
	
	$kino_debug_mode = ( get_query_var('kinodebug') ) ? get_query_var('kinodebug') : false;

	// First: filter participant IDs = 
	
	$ids_of_kino_participants = get_objects_in_term( 
		$kino_fields['group-kino-complete'], 
		'user-group' 
	);
	
	if ( $kino_debug_mode == 'on' ) {
		echo '<pre> $ids_of_kino_participants: ';
		var_dump( $ids_of_kino_participants );
		echo '</pre>';
	}


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
//				echo '<pre>';
//				echo 'list of IDs in "group-real-kabaret": ';
//					var_dump($ids_of_kino_participants);
//				echo '</pre>';
			}
			
		} // end 'realisateur'
		
		// pour comedien, technicien = voir si on créé des groupes.
		
		if ( $kinorole_var == 'pending' ) {
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-kino-complete'], 
				'user-group' 
			);

			
		} // end 'pending'
		
	} // end $kinorole_var testing
	
	
	// Retirer les comptes de test de la liste des ID
	
	$kino_test_accounts = kino_test_accounts();
	
	$ids_of_kino_participants = array_diff(
		$ids_of_kino_participants, 
		$kino_test_accounts
	);
	
	
	// Paramètre de date d'inscription
	
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
        /*
        if (empty($ids_of_kino_participants)) {
        	// il faut filtrer, sinon la page sera trop énorme!
        	$ids_of_kino_participants = get_objects_in_term( 
        		$kino_fields['group-kino-complete'], 
        		'user-group' 
        	);
        }*/
        
        $user_query = new WP_User_Query( array( 
        	'include' => $ids_of_kino_participants, // IDs incluses
        	// 'number' => 36,
        	// 'meta_key'  => 'last_name',
        	'orderby'  => 'login', // was: last_name
        	'meta_query' => array(
  	        array(
  	            'key' => 'kino_timestamp_complete_2018',
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
        	echo '<b>Filtrage par fraîcheur:</b> '.$kinodate_var.' heures<br/>';
        }
        if ( !empty($kino_id_var) ) {
        	echo '<b>Filtrage par ID:</b> '.$kino_id_var.'<br/>';
        }
        if ( ! empty( $user_query->results ) ) {
        	echo '<b>Nombre de badges: </b>'.count($user_query->results).'';
        }
         ?></p>
        
        <?php
        
        
        if ( $kino_debug_mode == 'on' ) {
        				echo '<pre>';
        				echo 'list of IDs in "group-real-kabaret": ';
        					var_dump($user_query->results);
        				echo '</pre>';
        }
        
        // User Loop
        if ( ! empty( $user_query->results ) ) {
        
        	// Variables
        	
        	$metronom = 1;
        
        
        	foreach ( $user_query->results as $user ) {
        	
        		// page opening tag
        		
        		if ( $metronom == 1 ) {
        			
        				echo '<div class="page-box">'; // .page-box
        			
        		}
        		
        	
        		$kino_userid = $user->ID ;
        		
        		$kino_userdata_base = kino_user_fields($kino_userid , $kino_fields );
        		
        		$kino_userdata_plus = kino_user_fields_auto( 
        			$kino_userid, 
        			$kino_fields, 
        			array( 
        				'session-attribuee',
        				'fonctions-staff',
        				'real-niveau',
        				'tech-niveau',
        				'comedien-niveau'
        			)
        		);
        		
        		// Note: array_merge doesn't do anything when one array is empty (unset)
        		
        		$kino_userdata = array();
        		
        		if ( is_array( $kino_userdata_base ) ) {
        		    $news_array = array_merge($kino_userdata, $kino_userdata_base);
        		}
        		
        		if ( is_array( $kino_userdata_plus ) ) {
        		    $news_array = array_merge($kino_userdata, $kino_userdata_plus);
        		}
        		
        		$kino_userdata = array_merge(
        			$kino_userdata_base, 
        			$kino_userdata_plus
        		);
						
						

        		// Phase 2 : Générer le HTML de la fiche
        		
        		// define CSS class
        		
        		$kino_role_css = '';
        		$kino_star_number = 0;
        		
        		$kinoite_name = $user->display_name;
        		
        		if ( strlen($kinoite_name) > 18 ) {
        			$kino_role_css = ' longname';
        		}
        		if ( strlen($kinoite_name) > 23 ) {
        			$kino_role_css = ' verylongname';
        		}
        		
        		// test if STAFF

        		if (!empty( $kino_userdata["fonctions-staff"] ) ) {
        			$kino_star_number++;
        		}
        		
        		if ( in_array( "realisateur-kab", $kino_userdata["participation"] )) {
      	 			// echo '<span class="kp-pointlist">Réalisateur-trice</span>';
      	 			$kino_role_css .= ' role-real';
      	 			$kino_star_number++;
      	 			// niveau?
      	 			if ( kino_process_niveau($kino_userdata["real-niveau"]) == 'pro' ) { $kino_role_css .= ' role-real-pro'; }
      	 		}
      	 		
      	 		if ( in_array( "technicien-kab", $kino_userdata["participation"] )) {
      	 			$kino_role_css .= ' role-tech';
      	 			$kino_star_number++;
      	 			// niveau?
      	 				if (!empty( $kino_userdata["tech-niveau"] )) {
      	 					if ( kino_process_niveau($kino_userdata["tech-niveau"]) == 'pro' ) { $kino_role_css .= ' role-tech-pro'; }
      	 				} 
      	 		}
      	 		
      	 		if ( in_array( "comedien-kab", $kino_userdata["participation"] )) {
      	 			$kino_role_css .= ' role-comed';
      	 			$kino_star_number++;
      	 			// niveau?
      	 				if (!empty( $kino_userdata["comedien-niveau"] )) {
      	 					if ( kino_process_niveau($kino_userdata["comedien-niveau"]) == 'pro' ) { $kino_role_css .= ' role-comed-pro'; }
      	 				} 
      	 		}
      	 		
      	 		// box nr
      	 		
      	 		$kino_role_css .= ' badge-nr-'.$metronom;
      	 		
      	 		$kino_role_css .= ' stars-'.$kino_star_number;
      	 		
        		
        	?>
        	<div class="print-badge<?php echo $kino_role_css; ?>">
        		<div class="print-badge-inside">
        	
        	<?php
        	
        	/*
        	 * Les éléments à afficher
        	 
        	 1) Photo
        	 2) Nom
        	 3) Rôles
        	 4) Etoiles selon rôles
        	 5) Talents des techniciens
        	 6) Kino Kabaret Genève
        	 7) Staff
        	 *
        	*/
        	
        	
        	 // Générer le HTML de la fiche
        	 // 1) Photo
        	 
        	 $kino_img_url = '';
        	 
        	 $kino_img_url = bp_core_fetch_avatar( array( 
        	 	'item_id' => $kino_userid, 
        	 	'no_grav' => true,
        	 	'type' => 'full', 
        	 	'html' => false) );
        	 
        	 if ( $kino_img_url == 'https://kinogeneva.ch/wp-content/plugins/buddypress/bp-core/images/mystery-man.jpg' ) {
        	 	
        	 	// no avatar :(
        	 	// check if photo uploaded:
        	 	
        	 			if ( $kino_userdata["photo"] ) {
        	 				// result: <img src="http://kinogeneva.4o4.ch/wp-content/uploads/profiles/8/JRoby.jpg" alt="" />
        	 				$kino_img_url = str_replace('" alt="" />','',$kino_userdata["photo"] );
        	 				$kino_img_url = str_replace('<img src="','',$kino_img_url );
        	 				
        	 			}
        	 	
        	 } else {
        	 	// has avatar!
        	 	// $kup[] = "avatar-complete";
        	 }
        	 
        	 echo '<div class="badge-photo" style="background-image:url('.$kino_img_url.')!important">';
        	 echo '</div>';
        	 
        	 
        	 // 2) Nom 
        	 
        	 echo '<div class="identity-block">';
        	 echo '<h2 class="identity-name kp-h2">'.$kinoite_name.'</h2>';


        	 // 3) Role
        	 echo '<div class="role-block">';
        	 if ( in_array( "realisateur-kab", $kino_userdata["participation"] )) {

        	 		echo '<div class="role-real">Réalisateur';
        	 		// niveau?
        	 			if (!empty( $kino_userdata["real-niveau"] )) {
        	 						echo ' ['.kino_process_niveau($kino_userdata["real-niveau"]).']';
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
        	 	
        	 	if ( in_array( "technicien-kab", $kino_userdata["participation"] )) {
        	 		echo '<div class="role-tech">Artisan-ne / Technicien-ne';
        	 		// niveau?
  	 					if (!empty( $kino_userdata["tech-niveau"] )) {
  	 							echo ' ['.kino_process_niveau( $kino_userdata["tech-niveau"] ).']';
  	 					}
        	 		echo '</div>';
        	 	}
        	 	
        	 	if ( in_array( "comedien-kab", $kino_userdata["participation"] )) {
        	 		echo '<div class="role-comed">Comédien-ne';
        	 		// niveau?
        	 		if (!empty( $kino_userdata["comedien-niveau"] )) {
        	 					echo ' ['.kino_process_niveau( $kino_userdata["comedien-niveau"] ).']';
        	 			}
        	 		echo '</div>';
        	 	}
        	 	echo '</div>'; // .role-block
        	 	
        	echo '</div>'; // end .identity-block
        	 
					// 4) Etoiles
					
					// $kino_star_number = 0;
					
					echo '<div class="starbox">';
					
					if ( in_array( "realisateur-kab", $kino_userdata["participation"] )) {
							echo '<img class="star" src="'. $url .'/img/badges/Star_G.png" />';
						}
						
						if ( in_array( "technicien-kab", $kino_userdata["participation"] )) {
							echo '<img class="star" src="'. $url .'/img/badges/Star_B.png" />';
						}
						
						if ( in_array( "comedien-kab", $kino_userdata["participation"] )) {
							echo '<img class="star" src="'. $url .'/img/badges/Star_R.png" />';
						}
						
						if (!empty( $kino_userdata["fonctions-staff"] ) ) {
							echo '<img class="star" src="'. $url .'/img/badges/Star_STAFF.png" />';
						}
					
					echo '</div>'; // starbox
					
					
        	 // Talents
        	 
        	 if ( in_array( "technicien-kab", $kino_userdata["participation"] ) 
        	 || !empty( $kino_userdata["fonctions-staff"] ) ) {
        	 		
        	 		echo '<div class="talents"><div>';
        	 		
        	 			// test des champs technicien
        	 				
        	 				if ( $kino_userdata["comp-production"] ) {
        	 					echo '<span class="kp-pointlist">Production </span>';
        	 				}
        	 				
        	 				if ( $kino_userdata["comp-scenario"] ) {
        	 					echo '<span class="kp-pointlist">Scénario </span>';
        	 				
        	 				}
        	 				
        	 				if ( $kino_userdata["comp-realisation"] ) {
        	 					echo '<span class="kp-pointlist">Réalisation </span>';
        	 					
        	 				}
        	 				
        	 				if ( $kino_userdata["comp-image"] ) {
        	 						echo '<span class="kp-pointlist">Image </span>';
        	 						
        	 				}
        	 				
        	 				if ( $kino_userdata["comp-postprod-image"] ) {
        	 					echo '<span class="kp-pointlist">Postprod image </span>';
        	 				}
        	 				
        	 				if ( $kino_userdata["comp-son"] ) {
        	 						echo '<span class="kp-pointlist">Son </span>';
        	 			
        	 				}
        	 				
        	 				if ( $kino_userdata["comp-postprod-son"] ) {
        	 						echo '<span class="kp-pointlist">Postprod son </span>';
        	 				
        	 				}
        	 				
        	 				if ( $kino_userdata["comp-direction-artistique"] ) {
        	 						echo '<span class="kp-pointlist">Direction artistique </span>';
        	 				}
        	 				
        	 				if ( $kino_userdata["comp-hmc"] ) {
        	 						echo '<span class="kp-pointlist">HMC </span>';
        	 						
        	 				}
        	 				        	 				
        	 				if ( !empty($kino_userdata["fonctions-staff"]) ) {
  	 								foreach ( $kino_userdata["fonctions-staff"] as $key => $value) {
  	 										echo '<span class="kp-pointlist"> '.$value.'</span>';
  	 									}
        	 				}
        	 		
        	 		echo '</div></div>';        	 		
        	 		  
        	 	} // end technicien-kab
        	 
        	 
        	 // Baseline - Is staff?
        	 
        	 echo '<div class="baseline">Kino Kabaret de Genève 2018';
        	 
        	 	if ( !empty( $kino_userdata["fonctions-staff"] ) ) {
        	 		echo ' – <span class="staff">staff</staff>';
        	 	}
        	 
        	  echo '</div>'; // end .baseline
        	 
        	 
        	 ?>
        	 	</div>
        	 </div><!-- .print-badge -->
        	 
        	 <?php
        	 
        	 // <div class="page-break"></div>
        		
        		// update $metronom 
        		if ( $metronom == 8 ) {
        			
        				// print closing block
        				echo '</div>'; // .page-box
        				$metronom = 1; // reset
        				
        		} else {
		        		$metronom++;
    				}
    				    	
        	} // end foreach
        } // end $user_query
        
        
         ?>
        
    </div><!--end article-content-->

    <?php  ?>
</article>
<?php } else {

echo '<h1>Désolé, l’accès à cette page est réservé aux administrateurs-trices de Kino Geneva!</h1>';

} // end if current user can...  ?>  
    </body>
</html>

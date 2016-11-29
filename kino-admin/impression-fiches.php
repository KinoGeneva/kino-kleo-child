<?php
/**
 * Un template pour imprimer les fiches
 */
?>

<?php
$extra_classes = 'clearfix';
if ( get_cfield( 'centered_text' ) == 1 )  {
    $extra_classes .= ' text-center';
}
?>

<style>

.kp-h2,
.kp-h3,
.kp-subtitle {
	text-align: center;
}

.kp-h2,
.kp-h3,
.kp-h4 {
	text-transform: uppercase;
	font-family: 'Roboto Condensed', Roboto, sans-serif;
	font-weight: bold;
}

.kp-h3 {
	margin-top: 0.2em;
	font-weight: normal;
}


.kp-subtitle {
	font-weight: bold;
	font-style: italic;
}

.kp-photo img {
	max-height: 200px;
	margin-left: auto;
	margin-right: auto;
	margin-bottom: 20px;
	display: block;	
}

html #main .print-profile {
	color: #000;
} 


.kleo-go-top,
.kleo-quick-contact-wrapper {
	display: none;
}


.hr-title {
	margin-top: 2em;
	margin-bottom: 1em;
}


@media print {

	.main-title,
	#footer {
		display: none;
	}
	
	.hr-title abbr {
		background-color: #fff !important;
	}
	
	.hr-title {
/*		margin-top: 1cm;
		margin-bottom: 1cm;*/
	}
	
	.print-profile {
	   page-break-after: always;
	   page-break-inside: avoid;
	}
	
	@page {
	  size: A4 portrait;
	}
	
	.no-print {
		display: none;
	}
	
}

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

	// First: filter participant IDs = 
	// Default = we only use Kino Participants:
	
	$ids_of_kino_participants = get_objects_in_term( 
		$kino_fields['group-kino-approved'], 
		'user-group' 
	);


	$kinorole_var = ( get_query_var('kinorole') ) ? get_query_var('kinorole') : false;
	
	if ( !empty($kinorole_var) ) {
	
		// Method: get user IDs in that taxonomy
		// Display specific users list
		// 'include' => array( 1, 2, 3 )
		
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
		
		// pour comedien, technicien = voir si on créé des groupes.
		
		if ( $kinorole_var == 'pending' ) {
			$ids_of_kino_participants = get_objects_in_term( 
				$kino_fields['group-kino-complete'], 
				'user-group' 
			);

			
		} // end 'pending'
		
	} // end $kinorole_var testing
	
	$kinodate_var = ( get_query_var('kinodate') ) ? get_query_var('kinodate') : false;
	
	if ( $kinodate_var ) {
	
		// add parameter to the users query	
		
		$kinodate_parameter = $kinodate_var;
		
	} else {
		$kinodate_parameter = '999';
	}


 ?>

<!-- Begin Article -->
<article id="post-<?php the_ID(); ?>" <?php post_class($extra_classes); ?>>

    <div class="article-content">
        <?php the_content(); ?>
        
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
        	'meta_key'  => 'last_name',
        	'orderby'  => 'last_name',
        	'date_query' => array( 
        	    array( 
        	    	'after' => $kinodate_parameter.' days ago', // filtrer par date d'adhésion
        	    	'inclusive' => false 
        	    )  
        	), 
        ) );
        
        // Show some info to the Admin:
        ?>
        
        <p class="admin-note no-print"><b>NOTES:</b><br/> <?php 
        
        if ( !empty($kinorole_var) ) {
        	echo '<b>Filtrage par rôle:</b> '.$kinorole_var.'<br/>';
        }
        if ( !empty($kinodate_var) ) {
        	echo '<b>Filtrage par fraîcheur:</b> '.$kinodate_var.' jours<br/>';
        }
        if ( ! empty( $user_query->results ) ) {
        	echo '<b>Nombre de fiches: </b>'.count($user_query->results).'';
        }
         ?></p>
        
        <?php
        
        // User Loop
        if ( ! empty( $user_query->results ) ) {
        	foreach ( $user_query->results as $user ) {
        	
        		$kino_userid = $user->ID ;

        		// tester si Transient, sinon charger les données.
        		
        		// Get any existing copy of our transient data
        		
        		// transient name
        		$transientname = 'userdata'.$user->ID;
        		
    		if ( false === ( $kino_userdata = get_transient( $transientname ) ) ) {
    		    // It wasn't there, so regenerate the data and save the transient
    		     $kino_userdata = kino_user_fields($kino_userid , $kino_fields );
    		     
    		     set_transient( $transientname, $kino_userdata, 60 );
    		     //  * HOUR_IN_SECONDS
    		     
//    		     echo '<p>we just defined transient '.$transientname.'</p>';
    		     
    		}
    		
//    		echo '<pre>';
//    		var_dump($kino_userdata);
//    		echo '</pre>';
        		
        		
        		// Phase 2 : Générer le HTML de la fiche
        		
        	?>
        	<div class="profile clearfix print-profile">
        	
        	<?php
        	
        	 // Générer le HTML de la fiche
        	 
        	 if ( $kino_userdata["photo"] ) {
        	 	echo '<div class="kp-par kp-photo">';
        	 	echo $kino_userdata["photo"];
        	 	echo '</div>';
        	 }
        	 
        	 // Identité 
        	 
        	 ?>
        	
        	 <div class="kp-section hr-title hr-full hr-double"><abbr>Identité</abbr></div>
        	 <?php
        	 
        	 echo '<h2 class="identity-name kp-h2">'.$user->display_name.'</h2>';

        	 echo '<p class="kp-subtitle">';
        	 echo $kino_userdata["ville"].' / '.$kino_userdata["pays"];
        	 echo ', '.$kino_userdata["userage"].' ans';
        	 echo '</p>';
        	 
        	 // Role
        	 
        	 if ( in_array( "realisateur-2016", $kino_userdata["participation"] )
        	 	|| in_array( "technicien-2016", $kino_userdata["participation"] )
        	 	|| in_array( "comedien-2016", $kino_userdata["participation"] ) ) {
        	 
        	 	echo '<h3 class="kp-h3">';
        	 		
        	 		if ( in_array( "realisateur-2016", $kino_userdata["participation"] )) {
        	 			echo '<span class="kp-pointlist">Réalisateur-trice</span>';
        	 		}
        	 		
        	 		if ( in_array( "technicien-2016", $kino_userdata["participation"] )) {
        	 			echo '<span class="kp-pointlist">Artisan-ne / technicien-ne</span>';
        	 		}
        	 		
        	 		if ( in_array( "comedien-2016", $kino_userdata["participation"] )) {
        	 			echo '<span class="kp-pointlist">Comédien-ne</span>';
        	 		}
        	 	
        	 	echo '</h3>';
        	 
        	 }
        	 
        	 // Présentation
        	 
        	 echo '<h4 class="kp-h4">Présentation</h4>';
        	 
        	 echo '<div class="kp-par">';
        	 echo $kino_userdata["presentation"];
        	 echo '</div>';
        	 
						
        	 // Compétences
        	 
        	 ?>
        	 <div class="kp-section hr-title hr-full hr-double"><abbr>Compétences</abbr></div>
        	 <?php
        	 
        	 // Disponibilités
        	 
        	 if ( $kino_userdata["dispo"] ) {
        	 
								echo '<h4 class="kp-h4">Disponibilités</h4>';
								
								echo '<p class="kp-par">';
								foreach ( $kino_userdata["dispo"] as $key => $value) {
									echo '<span class="jour-dispo">'.substr($value, 0, 2).'</span>';
								}
								echo ' janvier</p>';
								
								if ( $kino_userdata["dispo-partiel"] ) {
									echo '<p class="kp-par">'.$kino_userdata["dispo-partiel"].'</p>';
								}
        	 		
        	 }
        	 
        	 // Section Artisan / technicien
        	 
        	 if ( in_array( "technicien-2016", $kino_userdata["participation"] )) {
        	 	
        	 		echo '<h4 class="kp-h4">Artisan-ne / technicien-ne</h4>';
        	 		
        	 		echo '<p class="kp-par">';
        	 		
        	 		// test des champs technicien
        	 		
        	 		if ( $kino_userdata["comp-production"] ) {
        	 			echo '<strong>Production: </strong>';
        	 			foreach ( $kino_userdata["comp-production"] as $key => $value) {
        	 				echo '<span class="kp-pointlist">'.$value.'</span>';
        	 			}
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-scenario"] ) {
      	 				echo '<strong>Scénario: </strong>';
      	 				foreach ( $kino_userdata["comp-scenario"] as $key => $value) {
      	 					echo '<span class="kp-pointlist">'.$value.'</span>'; }
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-realisation"] ) {
	  	 					echo '<strong>Réalisation: </strong>';
	  	 					foreach ( $kino_userdata["comp-realisation"] as $key => $value) {
	  	 						echo '<span class="kp-pointlist">'.$value.'</span>'; }
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-image"] ) {
        	 				echo '<strong>Image: </strong>';
        	 				foreach ( $kino_userdata["comp-image"] as $key => $value) {
        	 					echo '<span class="kp-pointlist">'.$value.'</span>'; }
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-postprod-image"] ) {
        	 				echo '<strong>Postproduction image: </strong>';
        	 				foreach ( $kino_userdata["comp-postprod-image"] as $key => $value) {
        	 					echo '<span class="kp-pointlist">'.$value.'</span>'; }
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-son"] ) {
        	 					echo '<strong>Son: </strong>';
        	 					foreach ( $kino_userdata["comp-son"] as $key => $value) {
        	 						echo '<span class="kp-pointlist">'.$value.'</span>'; }
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-postprod-son"] ) {
        	 						echo '<strong>Postproduction son: </strong>';
        	 						foreach ( $kino_userdata["comp-postprod-son"] as $key => $value) {
        	 							echo '<span class="kp-pointlist">'.$value.'</span>'; }
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-direction-artistique"] ) {
        	 							echo '<strong>Direction artistique: </strong>';
        	 							foreach ( $kino_userdata["comp-direction-artistique"] as $key => $value) {
        	 								echo '<span class="kp-pointlist">'.$value.'</span>'; }
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-hmc"] ) {
        	 							echo '<strong>HMC: </strong>';
        	 							foreach ( $kino_userdata["comp-hmc"] as $key => $value) {
        	 								echo '<span class="kp-pointlist">'.$value.'</span>'; }
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-autres-liste"] ) {
        	 							echo '<strong>Autres: </strong>';
        	 							foreach ( $kino_userdata["comp-autres-liste"] as $key => $value) {
        	 								echo '<span class="kp-pointlist">'.$value.'</span>'; }
        	 		}
        	 		
        	 		if ( $kino_userdata["comp-autres-champ"] ) {
  	 								echo '<strong>Autres: </strong>';
  	 								echo $kino_userdata["comp-autres-champ"];
        	 		}
        	 		
        	 		echo '</p>';
        	 		
        	 		
        	 	} // end Technicien-2016
        	 
        	 // Equipement à disposition
        	
        	if ( (!empty($kino_userdata["equipement"])) || (!empty($kino_userdata["equipement-spec"])) ) {
        						
        						?>
        						<div class="kp-section hr-title hr-full hr-double"><abbr>Équipement personnel à disposition</abbr></div>
        						<?php
        					if ( $kino_userdata["equipement"] ) {	
        						foreach ( $kino_userdata["equipement"] as $key => $value) {
        							echo '<span class="kp-pointlist">'.$value.'</span>'; 
        						}
        					}
        						
        						if ( $kino_userdata["equipement-spec"] ) {
        										echo '<p>'.$kino_userdata["equipement-spec"].'</p>';
        						}
        	}
        		
        		
        	 // Section Comédien
        	 
        	 if ( in_array( "comedien-2016", $kino_userdata["participation"] )) {
        	 	
        	 		echo '<h4 class="kp-h4">Comédien-ne</h4>';
        	 		
        	 		echo '<p class="kp-par">';
        	 		
        	 		// test des champs comédien
        	 		
        	 		if ( !empty($kino_userdata["age-camera-min"]) ) {
	        	 		echo '<strong>Age min: </strong>';
	        	 		echo $kino_userdata["age-camera-min"];
        	 		}
        	 		if ( !empty($kino_userdata["age-camera-max"]) ) {
        	 				echo '<strong>Age max: </strong>';
        	 				echo $kino_userdata["age-camera-max"];
        	 		}
        	 		
        	 		if ( !empty($kino_userdata["langue-mat"]) ) {
        	 			echo '<strong>Langue maternelle: </strong>';
        	 			foreach ( $kino_userdata["langue-mat"] as $key => $value) {
        	 				echo '<span class="kp-pointlist">'.$value.'</span>';
        	 			}
        	 		}
        	 		
        	 		if ( !empty($kino_userdata["langues-parlees"]) ) {
        	 				echo '<strong>Langues parées: </strong>';
        	 				foreach ( $kino_userdata["langue-parlees"] as $key => $value) {
        	 					echo '<span class="kp-pointlist">'.$value.'</span>';
        	 				}
        	 			}
        	 	
        	 	echo '</p>';
        	 			
        	 } // end Section Comedien
        	 
        	 
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


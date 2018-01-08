<?php
/**
 * Un template pour valider les réalisateurs
 */
?>

<?php
$extra_classes = 'clearfix';
if ( get_cfield( 'centered_text' ) == 1 )  {
    $extra_classes .= ' text-center';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($extra_classes); ?>>

    <div class="article-content">
        <?php the_content(); ?>
        
        <?php 
        
        /*
         * pour gérer le suivi dans l’immédiat ainsi que par la suite, il peut s’avérer pratique d’avoir une vue tabulaire de ces groupes (comme fait pour bénévoles, les participants, etc.) en séparant donc les trois groupes:
         
         “En attente: Réalisateurs Plateforme”
         
         “En attente: Réalisateurs Plateforme ONLY”
         
         “En attente: Réalisateurs Kino Kabaret 2017”
        
        ****/
        
        $kino_debug_mode = 'off';
        	
        $url = home_url();
        	
        $kino_fields = kino_test_fields();
        
        //plateforme
        $ids_real_platform_pending = get_objects_in_term( 
        	$kino_fields['group-real-platform-pending'] , 
        	'user-group' 
        );
        
        $ids_real_platform_accepted = get_objects_in_term( 
        	$kino_fields['group-real-platform'] , 
        	'user-group' 
        );
        
        $ids_real_platform_rejected = get_objects_in_term( 
        	$kino_fields['group-real-platform-rejected'] , 
        	'user-group' 
        );
        
         $ids_real_platform_canceled = get_objects_in_term( 
        	$kino_fields['group-real-platform-canceled'] , 
        	'user-group' 
        );
        
        // *********
        //kabaret uniquement
        $ids_real_kabaret_pending = get_objects_in_term( 
        	$kino_fields['group-real-kabaret-pending'] , 
        	'user-group' 
        );
        
        $ids_real_kabaret_accepted = get_objects_in_term( 
        	$kino_fields['group-real-kabaret'] , 
        	'user-group' 
        );
        
        $ids_real_kabaret_rejected = get_objects_in_term( 
        	$kino_fields['group-real-kabaret-rejected'] , 
        	'user-group' 
        );
        
         $ids_real_kabaret_canceled = get_objects_in_term( 
        	$kino_fields['group-real-kabaret-canceled'] , 
        	'user-group' 
        );
        
        $ids_of_kino_complete = get_objects_in_term( 
        	$kino_fields['group-kino-complete'] , 
        	'user-group' 
        );
        
        // Additional sorting
        
        $ids_candidats_moyens = get_objects_in_term(
        	$kino_fields['group-candidats-vus-moyens'] , 
        	'user-group' 
        );
        
        $ids_candidats_biens = get_objects_in_term( 
        	$kino_fields['group-candidats-vus-biens'] , 
        	'user-group' 
        );
        
        // enlever les champs zéro: 
        $ids_real_platform_pending = array_filter($ids_real_platform_pending);
        $ids_real_kabaret_pending = array_filter($ids_real_kabaret_pending);
        
        $ids_real_platform_accepted = array_filter($ids_real_platform_accepted);
        $ids_real_kabaret_accepted = array_filter($ids_real_kabaret_accepted);
        
        $ids_real_platform_rejected = array_filter($ids_real_platform_rejected);
        $ids_real_kabaret_rejected = array_filter($ids_real_kabaret_rejected);
        
        $ids_real_platform_canceled = array_filter($ids_real_platform_canceled);
        $ids_real_kabaret_canceled = array_filter($ids_real_kabaret_canceled);
        
        $ids_candidats_moyens = array_filter($ids_candidats_moyens);
        $ids_candidats_biens = array_filter($ids_candidats_biens);
        

 				
		$ids_real_both = array_intersect( $ids_real_platform_pending, $ids_real_kabaret_pending );
		
		$ids_platform_only = array_diff( $ids_real_platform_pending, $ids_real_kabaret_pending );
			
		$ids_kabaret_only = array_diff( $ids_real_kabaret_pending, $ids_real_platform_pending );
 				
		echo '<h4><b>Réalisateurs en attente:</b></h4>';
		
		echo '<p><b>Plateforme</b>: '.count($ids_real_platform_pending).' / ';
		echo '<b>Kabaret</b>: '.count($ids_real_kabaret_pending).' / ';
		echo '<b>pour les deux</b>: '.count($ids_real_both).'</p>';
		
		echo '<p><b>Plateforme uniquement</b>: '.count($ids_platform_only).' ';
		echo '/ <b>Kabaret uniquement</b>: '.count($ids_kabaret_only).'</p>';
		
		echo '<p>Sélection <b><a href="#candid-moyen">Candidats Moyens</a></b>: '.count($ids_candidats_moyens).' / ';
		echo '<b><a href="#candid-bien">Candidats Bien</a></b>: '.count($ids_candidats_biens).'</p>';
		
		echo '<p>Réalisateurs pour Kabaret: ';
		echo '<b><a href="#real-kabaret-accepted-h2">validés</a></b>: '.count($ids_real_kabaret_accepted);
		echo ' / annulés: '.count($ids_real_kabaret_canceled);
		echo ' / refusés: '.count($ids_real_kabaret_rejected).'</p>';
		
		echo '<p>Réalisateurs validés pour <b>Plateforme</b>: '.count($ids_real_platform_accepted).'</p>';
 				
		// http://kinogeneva.ch/kino-admin/validation-realisateurs-plateforme/
		
		echo '<p><b>Voir aussi: <a href="'.$url.'/kino-admin/validation-realisateurs-plateforme/">Validation Réalisateurs Plateforme</a>.</b></p>';
		
		echo '<p><b>Voir aussi: <a href="'.$url.'/kino-admin/sessions/">Les quatre sessions</a>.</b></p>';
 				
 				// **************
 				
		// “En attente: Réalisateurs Kabaret only”	
		if (!empty($ids_kabaret_only)) {
			if( !empty( $users_kabaret_only = get_users( array( 'include' => $ids_kabaret_only ) ) ) ) {
				$metronom = 1;
				$kino_show_validation = 'kabaret';
				echo '<h2>En attente: Réalisateurs Kino Kabaret ONLY ('.count( $users_kabaret_only ).')</h2>';
				echo kino_table_header($kino_show_validation , 'kabaret_only');
				foreach ( users_kabaret_only as $user ) {
					include('validation-real-loop.php');
				}
				echo '</tbody></table>';
			}
		}
		
		// En attente: Réalisateurs Kino Kabaret
		if (!empty($ids_real_kabaret_pending)) {
			if( !empty( $users_real_kabaret_pending = get_users( array( 'include' => $ids_real_kabaret_pending ) ) ) ) {
				$metronom = 1;
				$kino_show_validation = 'kabaret-plus';
				echo '<h2>En attente: Réalisateurs Kino Kabaret ('.count( $users_real_kabaret_pending ).')</h2>';
				echo kino_table_header($kino_show_validation, 'kabaret_pending');
				foreach ( $users_real_kabaret_pending as $user ) {
					include('validation-real-loop.php');
				}
				echo '</tbody></table>';
			}
		}
		
		//******* Candidats Moyens ****
		if (!empty($ids_candidats_moyens)) {
			if( !empty( $users_candidats_moyens = get_users( array( 'include' => $ids_candidats_moyens ) ) ) ) {
				$metronom = 1;
				$kino_show_validation = 'kabaret';
				echo '<h2 id="candid-moyen">En attente: Candidats Moyens ('.count( $users_candidats_moyens ).')</h2>';
				echo kino_table_header($kino_show_validation , 'kabaret-moyen');
				foreach ( $users_candidats_moyens as $user ) {
					include('validation-real-loop.php');
				}
				echo '</tbody></table>';
			}
		}
		
		//******* Candidats Bien ****
		if (!empty($ids_candidats_biens)) {
			if( !empty( $users_candidats_biens = get_users( array( 'include' => $ids_candidats_biens ) ) ) ) {
				$metronom = 1;
				$kino_show_validation = 'kabaret';
				echo '<h2 id="candid-bien">En attente: Candidats Bien ('.count( $users_candidats_biens ).')</h2>';
				echo kino_table_header($kino_show_validation, 'kabaret-bien');
				foreach ( $users_candidats_biens as $user ) {
					include('validation-real-loop.php');
				}
				echo '</tbody></table>';
			}
		}
		
		// “Acceptés: Réalisateurs Kabaret ”
		if (!empty($ids_real_kabaret_accepted)) {
			if( !empty( $users_real_kabaret_accepted = get_users( array( 'include' => $ids_real_kabaret_accepted ) ) ) ) {
				$metronom = 1;
				$kino_show_validation = 'accepted';
				echo '<h2 id="real-kabaret-accepted-h2">Réalisateurs Kino Kabaret: Acceptés ('.count( $users_real_kabaret_accepted ).')</h2>';
				echo '<div id="real-kabaret-accepted">';
				echo kino_table_header($kino_show_validation, 'kabaret-accept');
				foreach ( $users_real_kabaret_accepted as $user ) {
					include('validation-real-loop.php');
				}
			}
			echo '</tbody></table></div>';
		}
        
        // Annulation: Réalisateurs Kabaret ”
		if (!empty($ids_real_kabaret_canceled)) {
			if( !empty( $users_real_kabaret_canceled = get_users( array( 'include' => $ids_real_kabaret_canceled ) ) ) ) {
				$metronom = 1;
				$kino_show_validation = 'false';
				echo '<h2>Réalisateurs Kino Kabaret: Annulation ('.count( $users_real_kabaret_canceled ).')</h2>';
				echo '<div id="real-kabaret-canceled">';
				echo kino_table_header($kino_show_validation, 'kabaret-cancel');
				foreach ( $users_real_kabaret_canceled as $user ) {
					include('validation-real-loop.php');
				}
			}
			echo '</tbody></table></div>';	 		 	
		} // test !empty

		//***************************************
		// “Refusés: Réalisateurs Kabaret ”
		if (!empty($ids_real_kabaret_rejected)) {
			if( !empty( $users_real_kabaret_rejected = get_users( array( 'include' => $ids_real_kabaret_rejected ) ) ) ) {
				$metronom = 1;
				$kino_show_validation = 'false';
				echo '<h2>Réalisateurs Kino Kabaret: Refusés ('.count( $users_real_kabaret_rejected ).')</h2>';
				echo '<div id="real-kabaret-rejected">';
				echo kino_table_header($kino_show_validation, 'kabaret-reject');
				foreach ( $users_real_kabaret_rejected as $user ) {
					include('validation-real-loop.php');
				}
			}
			echo '</tbody></table></div>';
		} // test !empty
     ?>
    </div><!--end article-content-->

</article>
<!-- End  Article -->
<?php 
kino_js_tablesort("kabaret_pending");

?>


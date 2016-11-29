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
         
         “En attente: Réalisateurs Kino Kabaret 2016”
        
        ****/
        
        $kino_debug_mode = 'off';
        	
        $url = site_url();
        	
        $kino_fields = kino_test_fields();
        
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
        
        // *********
        
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
        
        $ids_of_kino_complete = get_objects_in_term( 
        	$kino_fields['group-kino-complete'] , 
        	'user-group' 
        );
        
        // enlever les champs zéro: 
        $ids_real_platform_pending = array_filter($ids_real_platform_pending);
        $ids_real_kabaret_pending = array_filter($ids_real_kabaret_pending);
        
        $ids_real_platform_accepted = array_filter($ids_real_platform_accepted);
        $ids_real_platform_rejected = array_filter($ids_real_platform_rejected);
        
        // remove user 0
//        wp_remove_object_terms( 
//        	0, 
//        	$kino_fields['group-real-kabaret-pending'], 
//        	'user-group' 
//        );
 				
 				
 				$ids_platform_only = array_diff( $ids_real_platform_pending, $ids_real_kabaret_pending );
 					
 				
 				echo '<p>Réalisateurs en attente <b>Plateforme</b>: '.count($ids_real_platform_pending).'</p>';
 				// echo '<p>Réalisateurs en attente <b>Kabaret</b>: '.count($ids_real_kabaret_pending).'</p>';

 				echo '<p>Réalisateurs en attente <b>Plateforme uniquement</b>: '.count($ids_platform_only).'</p>';
 				
 				echo '<p>Réalisateurs validés pour la <b>Plateforme</b>: '.count($ids_real_platform_accepted).'</p>';
 				echo '<p>Réalisateurs rejetés pour la <b>Plateforme</b>: '.count($ids_real_platform_rejected).'</p>';
 				
 				echo '<p><b>Voir aussi: <a href="'.$url.'/kino-admin/validation-realisateurs/">Validation réalisateurs Kabaret</a>.</b></p>';
 				

 				//***************************************
 				
 				// “En attente: Réalisateurs Plateforme”
 				if (!empty($ids_real_platform_pending)) {
	 				$user_query = new WP_User_Query( array( 
	 					'include' => $ids_real_platform_pending, 
	 					'orderby' => 'registered',
	 					'order' => 'DESC'
	 				) );
	 				if ( ! empty( $user_query->results ) ) {
	 					$metronom = 1;
	 					$kino_show_validation = 'plateforme';
	 					echo '<h2>En attente: Réalisateurs Plateforme ('.count($user_query->results).')</h2>';
	 					echo kino_table_header($kino_show_validation);
	 					foreach ( $user_query->results as $user ) {
	 						include('validation-real-loop.php');
	 					}
	 					echo '</tbody></table>';
	 				}
 				}
        
				  
				 //***************************************
				 // “Acceptés: Réalisateurs Plateforme ”
				 
				 if (!empty($ids_real_platform_accepted)) {
				 	$user_query = new WP_User_Query( array( 
				 		'include' => $ids_real_platform_accepted, 
				 		'orderby' => 'registered',
				 		'order' => 'DESC'
				 	) );
				 	if ( ! empty( $user_query->results ) ) {
				   	$metronom = 1;
				   	$kino_show_validation = 'false';
				   	echo '<h2>Réalisateurs Plateforme: Acceptés ('.count($user_query->results).')</h2>';
				   	echo '<div id="real-platform-accepted">';
				   		echo kino_table_header($kino_show_validation);
				   	foreach ( $user_query->results as $user ) {
				   		include('validation-real-loop.php');
				   		// Add to list
//				   		kino_add_to_mailpoet_list( $user->ID, 
//					   		$kino_fields['mailpoet-real-platform'] 
//					   	);
				   	}
				 	  echo '</tbody></table></div>';
				 	}
				 }
				 
				 //***************************************
				 // “Refusés: Réalisateurs Plateforme ”
				 
				 if (!empty($ids_real_platform_rejected)) {
				 	$user_query = new WP_User_Query( array( 
				 		'include' => $ids_real_platform_rejected, 
				 		'orderby' => 'registered',
				 		'order' => 'DESC'
				 	) );
				 	if ( ! empty( $user_query->results ) ) {
				   	$metronom = 1;
				   	$kino_show_validation = 'false';
				   	echo '<h2>Réalisateurs Plateforme: Refusés ('.count($user_query->results).')</h2>';
				   	echo '<div id="real-platform-rejected">';
				   	echo kino_table_header($kino_show_validation);
				   	foreach ( $user_query->results as $user ) {
				   		include('validation-real-loop.php');
				   	}
				 	  echo '</tbody></table></div>';
				 	}
				 	
				 	// add users to mailpoet list:
//					 	kino_add_to_mailpoet_list( $ids_real_platform_rejected, 
//					 		$kino_fields['mailpoet-real-platform-rejected'] 
//					 	);
				 } // test !empty

				
     ?>
        
    </div><!--end article-content-->

    <?php  ?>
</article>
<!-- End  Article -->
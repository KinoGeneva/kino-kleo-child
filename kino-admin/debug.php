<?php
/**
 * Un template pour valider les inscriptions
 */
?>

<?php
$extra_classes = 'clearfix';
if ( get_cfield( 'centered_text' ) == 1 )  {
    $extra_classes .= ' text-center';
}
?>

<!-- Begin Article -->
<article id="post-<?php the_ID(); ?>" <?php post_class($extra_classes); ?>>

    <div class="article-content">
        <?php the_content(); ?>
        
        <?php 
        
        /*
         * On teste TOUS les membres du site
        ***************
        ****/
        	
        $kino_debug_mode = 'on';
        
        $url = site_url();
        
        $kino_fields = kino_test_fields();
        
        $kinoites_benevoles = array();
        
//        $ids_of_benevoles = get_objects_in_term( 
//        	$kino_fields['group-benevoles-kabaret'] , 
//        	'user-group' 
//        );

				$ids_of_kino_participants = get_objects_in_term( 
					$kino_fields['group-kino-pending'] , 
					'user-group' 
				);
        
        $ids_of_kino_complete = get_objects_in_term( 
        	$kino_fields['group-kino-complete'] , 
        	'user-group' 
        );
        
        $ids_of_kino_incomplete = get_objects_in_term( 
        	$kino_fields['group-kino-incomplete'] , 
        	'user-group' 
        );
        
        $ids_real_kabaret_accepted = get_objects_in_term( 
        	$kino_fields['group-real-kabaret'] , 
        	'user-group' 
        );

//        
//        $ids_real_kabaret_accepted = get_objects_in_term( 
//        	$kino_fields['group-real-kabaret'] , 
//        	'user-group' 
//        );

				// enlever les champs zéro: 
//				$ids_of_kino_complete = array_filter($ids_of_kino_complete);
        
//        echo '<p>Profil complet: '.count($ids_of_kino_complete).'</p>';
        
        // user query 1
        //***************************************
        
	        $user_query = new WP_User_Query( array( 
	        	 'include' => $ids_of_kino_participants, // IDs incluses
	        	// 'include' => $ids_real_kabaret_accepted, 
//	        	'orderby' => 'name',
//	        	'order' => 'ASC' 
	        	'orderby' => 'registered',
	        	'order' => 'DESC'
	        ) );
	        
	        echo '<p>Nombre total: '.count($user_query->results).'</p>';
        
        $metronom = 1;
        
        $users_in_kabaret = array();
        $users_not_in_kabaret = array();
        $users_nom_manquant = array();
        
        if ( ! empty( $user_query->results ) ) {
        	foreach ( $user_query->results as $user ) {
        		
        		// infos about WP_user object
        		$userid = $user->ID ;
        		// echo $user->user_email.'</br>';
        		        		
        		// étape 1: tester si le champ Participation Kino est coché!
        		
        		echo $user->user_email.'</br>';
        		
        		// Participe au cabaret 2016??
        		$kino_test = bp_get_profile_field_data( array(
        				'field'   => $kino_fields['kabaret'], 
        				'user_id' => $userid
        		) );
        		if ( ( $kino_test == "oui" ) || ( $kino_test == "yes" ) ) {
        					
        					$users_in_kabaret[] = $userid;
        					
        					// 
        				
        				        					
//        					echo ' / '.$metronom++;
//        					echo ' : '.$userid;
//        					
//        					if ( in_array( $userid, $ids_of_kino_participants ) ) {          				            				
//        					  echo ' ok ';
//        					} else {
//        						echo ' PROBLEM! ';
//        						
//        						// add to group
//        						 kino_add_to_usergroup( $userid, 
//        						 	$kino_fields['group-kino-pending'] );
//        					}
        					
        					// Ajouter au groupe "group-kino-pending"
        					
        					// Add to Mailpoet List
//        					kino_add_to_mailpoet_list( $id, 
//        						$kino_fields['mailpoet-real-platform'] );
        					
        					// Profil Cabaret 2016 complet? 
        					
        		} else {
        			
        			// is part of $ids_of_kino_participants? 
        			
//        			if ( in_array( $userid, $ids_of_kino_participants )) {
//        				// echo ' / paradox: user '.$userid ;
//        			}
        			
        			
//        			echo ' / '.$metronom++;
//        			echo ' user not : '.$userid;
        			// echo ' '.$userid.' <br/>';
        				
        				$users_not_in_kabaret[] = $userid;
        				
        				// echo $user->user_email.'</br>';
        			
        		}
        		
        		// $kinoite_participation = kino_user_participation( $kino_userid, $kino_fields );
        	
        	} // End foreach
        } // End testing user_query_cherche	
        
        
        echo '<p>Users in kabaret: '.count($users_in_kabaret).'</p>';
        
        echo '<p>Users not in kabaret: '.count($users_not_in_kabaret).'</p>';
        
        echo '<p>Users au nom manquant: '.count($users_nom_manquant).'</p>';
        
//        echo '<pre> $users_nom_manquant: ';
//        var_dump($users_nom_manquant);
//        echo '</pre>';
        
        // Add to mailpoet list: Identity Name= id 21
        
//        kino_add_to_mailpoet_list_array( 
//        	$users_nom_manquant, 
//        	21 
//        );
        
//        echo '<p>Users in group-kino-pending: '.count($ids_of_kino_participants).'</p>';
        
        // diff between them:
//        $ids_paradox = array_diff( $ids_of_kino_participants, $users_in_kabaret );
        
//        echo '<pre> $ids_paradox: ';
//        var_dump($ids_paradox);
//        echo '</pre>';
        
        // ***********************************
        
        
        ?>
        
    </div><!--end article-content-->

    <?php  ?>
</article>
<!-- End  Article -->
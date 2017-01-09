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
        
        $users_bp_email_missing = array();
        $users_email_same = array();
        $users_email_diff = array();

				// enlever les champs zéro: 
//				$ids_of_kino_complete = array_filter($ids_of_kino_complete);
        
//        echo '<p>Profil complet: '.count($ids_of_kino_complete).'</p>';
        
        // user query 1
        //***************************************
        
	        $user_query = new WP_User_Query( array( 
	        	//  'include' => $ids_of_kino_participants, // IDs incluses
	        	// 'include' => $ids_real_kabaret_accepted, 
//	        	'orderby' => 'name',
//	        	'order' => 'ASC' 
	        	'orderby' => 'registered',
	        	'order' => 'DESC'
	        ) );
	        
	        $total = count($user_query->results);
	        
	        echo '<p>Nombre total: '. $total .'</p>';
        
//        $users_in_kabaret = array();
//        $users_not_in_kabaret = array();
//        $users_nom_manquant = array();
        
        if ( ! empty( $user_query->results ) ) {
        	foreach ( $user_query->results as $user ) {
        		
        		// infos about WP_user object
        		$user_id = $user->ID ;
        		// echo $user->user_email.'</br>';
        		        		
        		// Participe au cabaret 2016??
        		
        		$kino_test = bp_get_profile_field_data( array(
        				'field'   => $kino_fields['kabaret'], 
        				'user_id' => $user_id
        		) );
        		
        		if ( ( $kino_test == "oui" ) || ( $kino_test == "yes" ) ) {
        					
        					$participants_kino[] = $user_id;
        					
        					// étape 1: tester si le champ Participation Kino est coché!
        					
        					$kino_wp_email = $user->user_email;
        					
        					$kino_wp_email = strtolower( $kino_wp_email );
        					
			        		$kino_bp_email = bp_get_profile_field_data( array(
			        				'field'   => $kino_fields['courriel'],
			        				'user_id' => $user_id
			        		) );
			        		
			        		// produit du HTML - <a href="mailto:yan.marquet80@gmail.com" rel="nofollow">yan.marquet80@gmail.com</a>
			        		
			        		$kino_bp_email = strip_tags( $kino_bp_email );
			        		
			        		$kino_bp_email = strtolower( $kino_bp_email );
			        		
			        		if ( empty( $kino_bp_email ) ) {
			        							
			        							echo '<a href="/members/'.$user->user_nicename.'/">'.$kino_wp_email.'</a> - BP Email missing</br>';
			        							
			        							$users_bp_email_missing[] = $user_id;
			        							
			        							// updating all email fields
      												xprofile_set_field_data(  
      													$kino_fields['courriel'],  
      													$user_id, 
      													$kino_wp_email
      												);
			        							
									} else if ( strcmp ( $kino_bp_email, $kino_wp_email ) == 0  ) {
									
										// echo 'Email ('.$kino_wp_email.') identique</br>';
										
										$users_email_same[] = $user_id;
										
									} else {
									
										$users_email_diff[] = $user_id;
										
										echo 'Email différent:  WP: ';
										echo '<a href="/members/'.$user->user_nicename.'/profile/">'.$kino_wp_email.'</a> ';
										echo 'et BP: '.$kino_bp_email.'</br>';
										
//										 $update_result = wp_update_user( array( 
//											'ID' => $user_id, 
//											'user_email' => $kino_bp_email ) 
//										);
//										
//										if ( is_wp_error( $update_result ) ) {
//											echo 'There was an error with user '.$user_id.'.</br>';
//											echo '<pre>';
//											var_dump($update_result);
//											echo '</pre>';
//										} else {
//											echo 'Success!</br>';
//										}
									
									} // end email testing
        		
        		} // end kabaret testing
        		
        	
        	} // End foreach
        } // End testing user_query_cherche	
        
        $participants_nr = count($participants_kino);
        
        echo '<p>Participants: '.$participants_nr.' / '. $total .' </p>';
        
        echo '<p>Users avec mail BP vide: '.count($users_bp_email_missing).' / '. $participants_nr .' </p>';
        
        echo '<p>Users avec mail similaire: '.count($users_email_same).' / '. $participants_nr .' </p>';
        
        echo '<p>Users avec mail différent: '.count($users_email_diff).' / '. $participants_nr .' </p>';
        
  
        // ***********************************
        
        ?>
        
    </div><!--end article-content-->

    <?php  ?>
</article>
<!-- End  Article -->
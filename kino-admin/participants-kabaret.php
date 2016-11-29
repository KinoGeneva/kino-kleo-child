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
         * Une page pour faciliter la gestion des inscriptions kino
        ***************
        
        ****/
        
        $kino_debug_mode = 'off';
        $url = site_url();
        $kino_fields = kino_test_fields();
        
        // On montre les membres faisant partie du groupe: 
        // Participants Kino 2016 : profil complet
        
        $ids_of_kino_participants = get_objects_in_term( 
        	$kino_fields['group-kino-pending'] , 
        	'user-group' 
        );
        
        $ids_of_kino_complete = get_objects_in_term( 
        	$kino_fields['group-kino-complete'] , 
        	'user-group' 
        );
        
//        $ids_of_kino_participants = get_objects_in_term( 
//        	$kino_fields['group-kino-complete'] , 
//        	'user-group' 
//        );
        
        $ids_of_kino_participants = array_filter($ids_of_kino_participants);
        $ids_of_kino_complete = array_filter($ids_of_kino_complete);
        
        echo '<h3>Total des participants: '.count( $ids_of_kino_participants ) .'</h3>';
        
        echo '<p>Total des participants au profil complet: '.count( $ids_of_kino_complete );
        
        $kino_complete_percentage = round( ( count( $ids_of_kino_complete ) / count( $ids_of_kino_participants ) ) * 100 );
        
        echo ' ('.$kino_complete_percentage.'%)</p>';
        
        echo '<p><b>Note: </b> Ce tableau liste tous les '.count( $ids_of_kino_participants ) .' utilisateurs qui ont coché la participation au Kabaret 2016.</p>';
        	
        echo '<p><b>Voir aussi les <a href="'.$url.'/kino-admin/membres-hors-kabaret/">membres hors-Kabaret</a>.</b></p>';
        // Voir Participants Kabaret pour une vue plus détaillée	
        	
        $user_fields = array( 
        	'user_login', 
        	'user_nicename', // = slug
        	'display_name',
        	'user_email', 
        	'ID',
        	'registered', 
        );
        
        $user_query = new WP_User_Query( array( 
        	// 'fields' => $user_fields,
        	'include' => $ids_of_kino_participants,
        	'orderby' => 'display_name', // nicename
        	'order' => 'ASC'
        ) );
        
        //***************************************
       
        if ( ! empty( $user_query->results ) ) {
        
        // Contenu du tableau
        	// Nom
        	// email
        	// Init:
        	$metronom = 1;
        	
        	?>
        	<table class="table table-hover table-bordered table-condensed">
        		<thead>
        			<tr>
        				<th>#</th>
        				<th>ID</th>
        				<th>Nom/Email</th>
        		    <th>Rôle Kabaret</th>
        		    <th>Réal?</th>
        		    <th>Profil complet?</th>
        		    <th>Inscription</th>
        			</tr>
        		</thead>
        		<tbody>
        		<?php
        
        	foreach ( $user_query->results as $user ) {
        		
        		?>
        		<tr>
        			<th><?php echo $metronom++; ?></th>
        			<?php 
        					$kino_userid = $user->ID;
        					$kino_user_role = kino_user_participation( 
        						$user->ID, 
        						$kino_fields
        					);
        					
        					// ID
        					echo '<td>'.$user->ID.'</td>';
        					
        					// Name
        					echo '<td>';
        					
        					if ( !empty($user->display_name) ) {
        						echo '('.$user->display_name .') ';
        					}
        					
        					echo '<a href="'.$url.'/members/'.$user->user_nicename.'/profile/" target="_blank">';
		        					echo $user->user_nicename;
        					echo '</a>';
        					
        					// Email
        			echo ' – <a href="mailto:'. $user->user_email .'?Subject=Kino%20Kabaret" target="_top">'. $user->user_email .'</a></td>';
        					
        					// Rôle Kino?
        					
        					// Rôles Kino
        					// ******************
        					
        					echo '<td>'; 
        					
        						// Réalisateur ?
        						if ( in_array( "realisateur-2016", $kino_user_role )) {
        							echo '<span class="kp-pointlist">Réalisateur-trice';
        								// niveau?
        										$kino_niveau = bp_get_profile_field_data( array(
        													'field'   => 1079,
        													'user_id' => $kino_userid
        											) );
        										if (!empty($kino_niveau)) {
        													echo ' ['.kino_process_niveau($kino_niveau).']';
        										}
        							echo '</span>';
        							
        						}
        						// Technicien ?
        						if ( in_array( "technicien-2016", $kino_user_role )) {
        							echo '<span class="kp-pointlist">Artisan-ne / technicien-ne';
        									$kino_niveau = bp_get_profile_field_data( array(
        											'field'   => 1075,
        											'user_id' => $kino_userid
        										) );
        										if (!empty($kino_niveau)) {
        												echo ' ['.kino_process_niveau($kino_niveau).']';
        										}
        							
        							echo '</span>';
        						}
        						// Comédien ?
        						if ( in_array( "comedien-2016", $kino_user_role )) {
        							echo '<span class="kp-pointlist">Comédien-ne';
        							
        										// niveau?
        											$kino_niveau = bp_get_profile_field_data( array(
        													'field'   => 927,
        													'user_id' => $kino_userid
        											) );
        											if (!empty($kino_niveau)) {
        														echo ' ['.kino_process_niveau($kino_niveau).']';
        												}
        							
        							echo '</span>';
        						}
        						
        					echo '</td>';
        					
            			            			
            			// Participe commme Réal ?
            			// ******************
            			
            			// Test if : 
            				
          				if ( in_array( "real-kab-valid", $kino_user_role ) ) {          				            				
          				  echo '<td class="success">Approved</td>';
          				
          				} else if ( in_array( "real-kab-rejected", $kino_user_role ) ) {
          				
          				  echo '<td class="error">Rejected</td>';
          				
          				} else if ( in_array( "real-kab-pending", $kino_user_role ) ) {
          				
          					echo '<td class="warning">Pending</td>';
          				
          				} else {

          					echo '<td></td>';
          				}
            			
            			// Profil complet ?
            			// ******************
            			
            			// Test if : 
            			
            			if ( in_array( $user->ID, $ids_of_kino_complete ) ) {          				            				
            			  echo '<td class="success">Complet';
            			  
//            			  $user_timestamp_complete = get_user_meta( $user->ID, 'kino_timestamp_complete', true );
//            			  
//            			  if ($user_timestamp_complete) {
//            			  	echo $user_timestamp_complete;
//            			  } else {
//            			  	echo ' timestamp missing!';
//            			  }
            			              			  
            			  echo '</td>';
            			} else {
            				echo '<td></td>';	
            			}
            			
            			
            			// Registration date
            			$shortdate = substr( $user->user_registered, 0, 10 );
            			echo '<td>'. $shortdate .'</td>';
            			
            			// Ajouter à Mailpoet: Participants Kabaret
//            			kino_add_to_mailpoet_list( 
//  			        	 	$user->ID, 
//  			        	 	$kino_fields['mailpoet-participant-kabaret'] 
//  			        	);
        			
        		echo '</tr>';
        		
        	}
        	
        	echo '</tbody></table>';
        
        	// Ajouter à Mailpoet: Participants Kabaret
//        	kino_add_to_mailpoet_list( 
//        	 	$ids_of_kino_complete, 
//        	 	$kino_fields['mailpoet-participant-kabaret'] 
//        	 	);
        
        } // test !empty
        
         ?>
        
    </div><!--end article-content-->
  
    <?php  ?>
</article>
<!-- End  Article -->


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

// Delete unwanted users:
// wp_delete_user( 90, 8 );


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
        
        $ids_of_kino_realisateurs = get_objects_in_term( 
        	$kino_fields['group-real-kabaret'] , 
        	'user-group' 
        );
        
        $ids_of_kino_comediens = get_objects_in_term( 
        	$kino_fields['group-comp-comedien'] , 
        	'user-competences' 
        );
        
        $ids_of_kino_techniciens = get_objects_in_term( 
        	$kino_fields['group-comp-technicien'] , 
        	'user-competences' 
        );
        
        $ids_of_paid_25 = get_objects_in_term( 
        	$kino_fields['compta-paid-25'] , 
        	'user-compta' 
        );
        $ids_of_paid_100 = get_objects_in_term( 
        	$kino_fields['compta-paid-100'] , 
        	'user-compta' 
        );
        $ids_of_repas_60 = get_objects_in_term( 
        	$kino_fields['compta-repas-60'] , 
        	'user-compta' 
        );
        $ids_of_repas_100 = get_objects_in_term( 
        	$kino_fields['compta-repas-100'] , 
        	'user-compta' 
        );
        
//        $ids_of_kino_participants = get_objects_in_term( 
//        	$kino_fields['group-kino-complete'] , 
//        	'user-group' 
//        );
        
        $ids_of_kino_participants = array_filter($ids_of_kino_participants);
        $ids_of_kino_complete = array_filter($ids_of_kino_complete);
        
        echo '<h3>Total des participants: '.count( $ids_of_kino_participants ) .'</h3>';
        
//        echo '<p>Total des participants au profil complet: '.count( $ids_of_kino_complete );
//        
//        $kino_complete_percentage = round( ( count( $ids_of_kino_complete ) / count( $ids_of_kino_participants ) ) * 100 );
//        
//        echo ' ('.$kino_complete_percentage.'%)</p>';
        
        // echo '<p><b>Note: </b> Ce tableau liste tous les '.count( $ids_of_kino_participants ) .' utilisateurs qui ont coché la participation au Kabaret 2016.</p>';
        	
        // echo '<p><b>Voir aussi les <a href="'.$url.'/kino-admin/membres-hors-kabaret/">membres hors-Kabaret</a>.</b></p>';
        // Voir Participants Kabaret pour une vue plus détaillée	
        	
        $user_fields = array( 
        	'user_login', 
        	'user_nicename', // = slug
        	'display_name',
        	'user_email', 
        	'ID',
        	'registered', 
        );
        
        $transientname = 'kinoites_inscription_users';
                		
    		if ( false === ( $user_query = get_transient( $transientname ) ) ) {
    		
    		    // It wasn't there, so regenerate the data and save the transient
    		     $user_query = new WP_User_Query( array( 
    		     	// 'fields' => $user_fields,
    		     	'include' => $ids_of_kino_participants,
    		     	'orderby' => 'nicename',
    		     	'order' => 'ASC'
    		     ) );
    		     
    		     set_transient( $transientname, $user_query, 180 );
    		     //  * HOUR_IN_SECONDS
    		     
    		     echo '<p>we just defined transient '.$transientname.'</p>';
    		}
        
        //***************************************
       
        if ( ! empty( $user_query->results ) ) {
        
        // Contenu du tableau
        	// Nom
        	// email
        	// Init:
        	$metronom = 1;
        	
        	?>
        	<div id="table-container">
        	<table id="inscription-table" class="table table-hover table-bordered table-condensed pending-form">
        		<thead>
        			<tr>
        				<th>#</th>
        				<th>ID</th>
        				<th width="200">Nom/Email</th>
        				<th>Profil complet?</th>
        		    <th width="200">Rôle Kabaret</th>
        		    <th>Réal?</th>
        		    <th>Inscription</th>
        		    <th>Carte Repas</th>
        			</tr>
        		</thead>
        		<tbody>
        		<?php
        
        	foreach ( $user_query->results as $user ) {
        		
        		?>
        		<tr class="inscription-kino pending-candidate" data-id="<?php echo $user->ID; ?>">
        			<th><?php echo $metronom++; ?></th>
        			<?php 
        					
        					$id = $user->ID;
        					
//        					$kino_user_role = kino_user_participation( 
//        						$id, 
//        						$kino_fields
//        					);
        					
        					// ID
        					echo '<td>'.$id.'</td>';
        					
        					// Name
        					echo '<td>';
        					
        					if ( !empty($user->display_name) ) {
        						echo '('.$user->display_name .') ';
        					}
        					
        					echo '<a href="'.$url.'/members/'.$user->user_nicename.'/" target="_blank">';
		        					echo $user->user_nicename;
        					echo '</a>';
        					
        					// Email
        			echo ' – <a href="mailto:'. $user->user_email .'?Subject=Kino%20Kabaret" target="_top">'. $user->user_email .'</a></td>';
        					
        					
        					// Profil complet ?
        					// ******************
        					
        					// Test if : 
        					
        					if ( in_array( $id, $ids_of_kino_complete ) ) {          				            				
        					  echo '<td class="success">Complet</td>';
        					} else {
        						echo '<td></td>';	
        					}
        					
        					
        					// Rôles Kino
        					// ******************
        					
        					echo '<td>'; 
        					
        						// Réalisateur ?
        						if ( in_array( $id, $ids_of_kino_realisateurs )) {
        							echo '<span class="kp-pointlist">Réalisateur-trice</span>';
        						}
        						// Technicien ?
        						if ( in_array( $id, $ids_of_kino_techniciens )) {
        							echo '<span class="kp-pointlist">Artisan-ne / technicien-ne</span>';
        						}
        						// Comédien ?
        						if ( in_array( $id, $ids_of_kino_comediens )) {
        							echo '<span class="kp-pointlist">Comédien-ne</span>';
        						}
        						
        					echo '</td>';
        					
            			            			
            			// Participe commme Réal ?
            			// ******************
            			
            			// Test if : 
            				
          				if ( in_array( $id, $ids_of_kino_realisateurs ) ) { 
          				  echo '<td class="success">Réalisateur-trice</td>';
          				} else {
          					echo '<td></td>';
          				}
            			
            			
            			// Actions Inscription!!!
            			// ***********************

            			echo '<td>';
            			
            			// tester le statut du kinoïte!
            			// payer 100 / 25 / reset
//	            			
	            			if ( in_array( $id, $ids_of_paid_25 ) ) {
	            				echo '<span class="has-paid">Payé: 25.-</span>';
	            			}
	            			
	            			if ( in_array( $id, $ids_of_paid_100 ) ) {
	            				echo '<span class="has-paid">Payé: 100.-</span>';
	            			}	
	            			
	            			if ( ( in_array( $id, $ids_of_paid_25 ) ) || ( in_array( $id, $ids_of_paid_100 ) ) ) {
	            				
	            				echo '<a class="admin-action payment-reset pending-reject" data-action="payment-reset">Reset</a>';
	            			} else {
	            				
	            				echo '<a class="admin-action payment-25 pending-other" data-action="payment-25">Payer 25</a>';
	            				echo '<a class="admin-action payment-100 pending-other" data-action="payment-100">Payer 100</a>';
	            			}
            			
            			echo '</td>';
            			
            			// Actions Carte Repas
            			// ***********************
            				
            			echo '<td>';
            			// carte 60
            			// carte 100
            			// reset
            			// tester le statut du kinoïte!
            				
            				if ( in_array( $id, $ids_of_repas_60 ) ) {
            					echo '<span class="has-paid">Payé: 60.-</span>';
            				}
            				
            				if ( in_array( $id, $ids_of_repas_100 ) ) {
            					echo '<span class="has-paid">Payé: 100.-</span>';
            				}
            				
            				if ( ( in_array( $id, $ids_of_repas_60 ) ) || ( in_array( $id, $ids_of_repas_100 ) ) ) {
            					
            					echo '<a class="admin-action repas-reset pending-reject" data-action="repas-reset">Reset</a>';
            				} else {
            					
            					echo '<a class="admin-action repas-60 pending-other" data-action="repas-60">Carte 60</a>';
            					
            					echo '<a class="admin-action repas-100 pending-other" data-action="repas-100">Carte 100</a>';
            				}
            				
            			echo '</td>';
            			
            			// Ajouter à Mailpoet: Participants Kabaret
//            			kino_add_to_mailpoet_list( 
//  			        	 	$user->ID, 
//  			        	 	$kino_fields['mailpoet-participant-kabaret'] 
//  			        	);
        			
        		echo '</tr>';
        		
        	}
        	
        	echo '</tbody></table></div>';
        
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


<?php
/**
 * Un template pour les Véhicules et Logements
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
          
          
          $liste_vehicules = array();
          $liste_logements = array();
          
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
          
          $ids_of_kino_participants = array_filter($ids_of_kino_participants);
          $ids_of_kino_complete = array_filter($ids_of_kino_complete);
          
          /*
           * Note : Ultimately, we want to build two arrays
           * And store them as Transients
          */
        	
        // user query
        //***************************************
        
        $user_query = new WP_User_Query( array( 
        	'include' => $ids_of_kino_participants, // IDs incluses
        	'orderby' => 'nicename',
        	'order' => 'ASC'
        ) );
        
        // Function to generate users
        // kino_user_fields_logement()
        // in : bp-user-fields.php
        
        if ( ! empty( $user_query->results ) ) {
        	foreach ( $user_query->results as $user ) {
        		
        		// infos about WP_user object
        		$kino_userid = $user->ID ;
        		
        		// Test Vehicle
        		// $kino_fields['vehicule'] = 1155;
        		// $kino_fields['vehicule-remarque'] = 1160;
        		$kino_vehicule = bp_get_profile_field_data( array(
        				'field'   => $kino_fields['vehicule'],
        				'user_id' => $kino_userid
        		) );
        		if ( $kino_vehicule == 'OUI' ) {
        		
        			// add item to array
        			$kino_vehicule_remarque = bp_get_profile_field_data( array(
        					'field'   => $kino_fields['vehicule-remarque'],
        					'user_id' => $kino_userid
        			) );
        			
        			$liste_vehicules[] = array(
        				"user-id" => $kino_userid,
        				"user-name" => $user->display_name,
        				"user-slug" => $user->user_nicename,
        				"user-email" => $user->user_email,
        				"user-registered" => $user->user_registered,
        				"vehicule-remarque" => $kino_vehicule_remarque,
        				"tel" => bp_get_profile_field_data( array(
        				    'field'   => $kino_fields["tel"],
        				    'user_id' => $kino_userid
        				) ),
        				"dispo" =>  bp_get_profile_field_data( array(
        				    'field'   => $kino_fields["dispo"],
        				    'user_id' => $kino_userid
        				) ),
        			);

        		} // Test Véhicule
        		
        		// Test Logement
        		// possib tournage: 1151
        		
        		// $kino_fields['possible-tournage'] = 1151;
        		// $kino_fields['possible-tournage-remarque'] = 1154;
        		
        		$kino_logement = bp_get_profile_field_data( array(
        				'field'   => $kino_fields['possible-tournage'],
        				'user_id' => $kino_userid
        		) );
        		
        		if ( $kino_logement == 'OUI' ) {
        		        		
	        			// add item to array
	        			$kino_logement_remarque = bp_get_profile_field_data( array(
	        					'field'   => $kino_fields['possible-tournage-remarque'],
	        					'user_id' => $kino_userid
	        			) );
	        			
	        			$liste_logements[] = array(
	        				"user-id" => $kino_userid,
	        				"user-name" => $user->display_name,
	        				"user-slug" => $user->user_nicename,
	        				"user-email" => $user->user_email,
	        				"user-registered" => $user->user_registered,
	        				"logement-remarque" => $kino_logement_remarque,
	        				"tel" => bp_get_profile_field_data( array(
	        				    'field'   => $kino_fields["tel"],
	        				    'user_id' => $kino_userid
	        				) ),
	        				"dispo" =>  bp_get_profile_field_data( array(
	        				    'field'   => $kino_fields["dispo"],
	        				    'user_id' => $kino_userid
	        				) ),
	        				"rue" => bp_get_profile_field_data( array(
	        				    'field'   => $kino_fields["rue"],
	        				    'user_id' => $kino_userid
	        				) ),
	        				"ville" => bp_get_profile_field_data( array(
	        				    'field'   => $kino_fields["ville"],
	        				    'user_id' => $kino_userid
	        				) ),
	        				"code-postal" => bp_get_profile_field_data( array(
	        				    'field'   => $kino_fields["code-postal"],
	        				    'user_id' => $kino_userid
	        				) ),
	        			);
        		
        		 } // Test Logement
        		
        		
        	} // End foreach
        } // End testing user_query_cherche	
        
   
        //***************************************
        
        // OUTPUT!
        
        // Kinoïtes qui cherchent un logement:
        if ( !empty($liste_vehicules) ) {
        	echo '<h2>Véhicules ('.count($liste_vehicules).'):</h2>';
        	?>
        	<table class="table table-hover table-bordered table-condensed">
        		<thead>
        			<tr>
        				<th>#</th>
        				<th>ID</th>
        				<th>Nom</th>
        				<th>Remarque</th>
        				<th>Disponibilité</th>
        		    <th>Email</th>
        		    <th>Tel.</th>
        		    
        			</tr>
        		</thead>
        		<tbody>
        		<?php
        			$metronom = 1;
        			
        			foreach ($liste_vehicules as $key => $item) {
        					?>
        					<tr>
        						<th><?php echo $metronom++; ?></th>
        						<?php  
        									
        								// ID
        								echo '<td>'.$item["user-id"].'</td>';	
        									
        								// Nom:
        								echo '<td><a href="'.$url.'/members/'.$item["user-slug"].'/" target="_blank">'.$item["user-name"].'</a></td>';
        								
        								// Remarque
        								echo '<td>'.$item["vehicule-remarque"].'</td>';
        								
        								// Dispo
        								echo '<td>';
        								if ( !empty($item["dispo"]) ) {
        									echo 'Jours: ';
        									foreach ( $item["dispo"] as $key => $value) {
        										echo '<span class="jour-dispo"> '.substr($value, 0, 2).'</span>';
        									}
        								}
        								echo '</td>';
        								
        								// Email
        						?><td><a href="mailto:<?php echo $item["user-email"] ?>?Subject=Kino%20Kabaret" target="_top"><?php echo $item["user-email"] ?></a></td>
        							<td><?php 
        							
        							// Tel
        							echo $item["tel"] ?></td>
        							
        							<?php 
        							
	        						
	        						
        					echo '</tr>'; 
        			} // end foreach
        	echo '</tbody></table>';
        } // end if empty
        
            
        // Logements: Générer le tableau
        if ( !empty($liste_logements) ) {
        	echo '<h2>Logements pour tournage ('.count($liste_logements).'):</h2>';
        	
        	?>
        	<table class="table table-hover table-bordered table-condensed">
        		<thead>
          		<tr>
          			<th>#</th>
          			<th>ID</th>
          			<th>Nom</th>
          			<th>Adresse</th>
          			<th>Remarques</th>
        		    <th>Disponibilité</th>
        		    <th>Email</th>
        		    <th>Tel.</th>
          		</tr>
          	</thead>
          	<tbody>
        		<?php
        				$metronom = 1;
        				foreach ($liste_logements as $key => $item) {
        						?>
        						<tr>
        							<th><?php echo $metronom++; ?></th>
        							<?php 
        							
        							// ID
        							echo '<td>'.$item["user-id"].'</td>';	
        							
        							// Nom
        							echo '<td><a href="'.$url.'/members/'.$item["user-slug"].'/" target="_blank">'.$item["user-name"].'</a></td>';
        							 
        							// Adresse
        							 echo '<td>'.$item["rue"].', '.$item["code-postal"].' '.$item["ville"].'</td>';
        							
        							// Remarques
        							echo '<td>'.$item["logement-remarque"].'</td>';
        							
        							// Dispo
        									echo '<td>';
        									if ( !empty($item["dispo"]) ) {
        										echo 'Jours: ';
        										foreach ( $item["dispo"] as $key => $value) {
        											echo '<span class="jour-dispo"> '.substr($value, 0, 2).'</span>';
        										}
        									}
        									echo '</td>';
        									
        									// Email
        							?><td><a href="mailto:<?php echo $item["user-email"] ?>?Subject=Kino%20Kabaret" target="_top"><?php echo $item["user-email"] ?></a></td>
        								<?php 
        								
        								// Tel
        								echo '<td>'.$item["tel"].'</td>'; ?>

        						</tr> <?php
        				} // end foreach
        		echo '</tbody></table>';
        }
        // Fin logements
        
        ?>
       
    </div><!--end article-content-->

    <?php  ?>
</article>
<!-- End  Article -->
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
         * Une page pour faciliter la gestion des bénévoles kino
        ***************
        ****/
        	
        $kino_debug_mode = 'off';
        
        $url = site_url();
        
        $kino_fields = kino_test_fields();
        
        // Kinoites qui cherchent un logement
        $kinoites_benevoles = array();

				$ids_of_benevoles = get_objects_in_term( 
					$kino_fields['group-benevoles-kabaret'] , 
					'user-group' 
				);
				
				// tester aussi si la personne participe au Kabaret 2017 :
				
				$ids_of_participants = get_objects_in_term( 
					$kino_fields['group-kino-pending'] , 
					'user-group' 
				);
				
				// tester aussi si le profil est complet
				
				$ids_of_complete = get_objects_in_term( 
					$kino_fields['group-kino-complete'] , 
					'user-group' 
				);
				
				// ne garder que les bénévoles qui participent au Kabaret
				
//				$ids_of_benevoles = array_intersect( $ids_of_benevoles, $ids_of_participants );
				
				$ids_of_benevoles = array_intersect( $ids_of_benevoles, $ids_of_complete );
				
//				echo '<pre>$ids_of_benevoles:';
//				var_dump($ids_of_benevoles);
//				echo '</pre>';
				
				// enlever les champs zéro: 
				$ids_of_benevoles = array_filter($ids_of_benevoles);
        
        echo '<p>Nombre de bénévoles: '.count($ids_of_benevoles).'</p>';
        
        // user query 1
        //***************************************
        
        if ( !empty($ids_of_benevoles) ) {
        
	        $user_query = new WP_User_Query( array( 
	        	'include' => $ids_of_benevoles, // IDs incluses
	        	'orderby' => 'name',
	        	'order' => 'ASC' 
	        ) );
	        
	        if ($kino_debug_mode == "on") {
	        	echo '<pre>';
	        	var_dump($user_query->results);
	        	echo '</pre>';
	        }
        
        	// add to mailpoet
        	
        	// Add to Mailpoet List
//        	kino_add_to_mailpoet_list_array( 
//        		$ids_of_benevoles, 
//        		$kino_fields['mailpoet-benevoles'] 
//        	);
        
        }
        
        // Function to generate users
        // kino_user_fields_logement()
        // in : bp-user-fields.php
        
        if ( ! empty( $user_query->results ) ) {
        	foreach ( $user_query->results as $user ) {
        		
        		// infos about WP_user object
        		$kino_userid = $user->ID ;
        		
        		// $kinoite_participation = kino_user_participation( $kino_userid, $kino_fields );
        		
        			// add to array
        			$kinoites_benevoles[] = kino_user_fields_logement( $user, $kino_fields );
        			
        			// 	    
        			
        		if ($kino_debug_mode == "on") {
        		
        			echo '<pre>';
        			echo 'DUMP';
        			var_dump($kinoites_benevoles);
        			echo '</pre>';
        		}
        	
        	} // End foreach
        } // End testing user_query_cherche	
        
        // ***********************************
        
        if ( !empty($kinoites_benevoles) ) {
        	echo '<h2>Kinoïtes <a href="'.$url.'/wp-admin/users.php?user-group=benevoles-kabaret">Bénévoles</a> ('.count($kinoites_benevoles).'):</h2>';
        	
        	echo '<p>Liste des kinoïtes bénévoles ayant coché "l’organisation du Kino Kabaret".';
        	
        	?>
        	<table id="gestion-benevoles" class="table table-hover table-bordered table-condensed">
        		<thead>
          		<tr>
          			<th>#</th>
          			<th>Nom</th>
          			<th>Real?</th>
          			<th>Rôle Kino</th>
          			<th>Fonction?</th>
          			<th>Choix admin</th>
          			<th>Activités Kino?</th>
          			<th>Adresse</th>
        		    <th>Email / Tel.</th>
          		</tr>
          	</thead>
          	<tbody>
        		<?php
        		
        				$metronom = 1;
						$email_benevoles = '';
        				foreach ($kinoites_benevoles as $key => $item) {
        				$email_benevoles.=$item['user-email'] .';';
//        							 Add to Mailpoet List
//        							kino_add_to_mailpoet_list( 
//        								$item["user-id"], 
//        								$kino_fields['mailpoet-benevoles'] 
//        							);
        							
        						?>
        						<tr>
        							<th><?php echo $metronom++; ?></th>
        							<?php 
        							
        							// Nom
        							echo '<td><a href="'.$url.'/members/'.$item["user-slug"].'/" target="_blank">'.$item["user-name"].'</a></td>';
        							
        							// Real?
        							// ******************
        							  							  								
				  								if ( in_array( "real-kab-valid", $item["participation"] ) ) {          				            				
				          				  echo '<td class="success">Approved</td>';
				          				
				          				} else if ( in_array( "real-kab-rejected", $item["participation"] ) ) {
				          				
				          				  echo '<td class="error">Rejected</td>';
				          				
				          				} else if ( in_array( "real-kab-pending", $item["participation"] ) ) {
				          				
				          					echo '<td class="warning">Pending</td>';
				          				
				          				} else {
				
				          					echo '<td></td>';
				  								}
        							
        							
        							// Rôles Kino
        							// ******************
        							
        							echo '<td>'; 
        							
        								// Réalisateur ?
        								if ( in_array( "realisateur-kab", $item["participation"] )) {
        									echo '<span class="kp-pointlist">Réalisateur-trice</span>';
        								}
        								// Technicien ?
        								if ( in_array( "technicien-kab", $item["participation"] )) {
        									echo '<span class="kp-pointlist">Artisan-ne / technicien-ne</span>';
        								}
        								// Comédien ?
        								if ( in_array( "comedien-kab", $item["participation"] )) {
        									echo '<span class="kp-pointlist">Comédien-ne</span>';
        								}
        								
        							echo '</td>';
        							
        							// ******************
        							
        							// Fonction
        							echo '<td>';
        							if ( $item["benevole-fonction"] ) {
        										foreach ( $item["benevole-fonction"] as $key => $value) {
        											echo '<span class="kp-pointlist">'.$value.'</span>';
        										}
        							}
        							echo '</td>';
        							
        							// Fonction
        							echo '<td>';
        							if ( $item["benevole-charge-admin"] ) {
        										foreach ( $item["benevole-charge-admin"] as $key => $value) {
        											echo '<span class="kp-pointlist">'.$value.'</span>';
        										}
        							}
        							echo '</td>';
        							
        							
        							//Activités Kino
        							echo '<td>';
        							if ( $item["benevole-kabaret"] ) {
        										foreach ( $item["benevole-kabaret"] as $key => $value) {
        											echo '<span class="kp-pointlist">'.$value.'</span>';
        										}
        							}
        							
        							echo '</td>';
        							
        							// ******************
        							
        							// Adresse
        							echo '<td>'.$item["rue"].', '.$item["code-postal"].' '.$item["ville"].', '.$item["pays"].'</td>';
        							
        							// Email
        							?><td><a href="mailto:<?php echo $item["user-email"] ?>?Subject=Kino%20Kabaret" target="_top"><?php echo $item["user-email"] ?></a> - <?php echo $item["tel"] ?></td>
        					<?php		
        					echo '</tr>';
        					
        				} // end foreach
        		echo '</tbody></table>';
        		
        		echo '<a class="btn btn-default" href="mailto:onvafairedesfilms@kinogeneva.ch?bcc='.$email_benevoles.'">écrire à tous les bénévoles</a>';
        		
        		echo '<h3>Liste des emails:</h3>';
        		echo '<pre>'.$email_benevoles.'</pre>';
        }

        
        ?>
        
    </div><!--end article-content-->

    <?php  ?>
</article>
<!-- End  Article -->

<?php 

// 

kino_js_tablesort("gestion-benevoles");



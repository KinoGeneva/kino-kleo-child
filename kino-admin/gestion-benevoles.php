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
        
        $url = home_url();
        
        $kino_fields = kino_test_fields();
        
        // Kinoites bénévoles
        $kinoites_benevoles = array();

				$ids_of_benevoles = get_objects_in_term( 
					$kino_fields['group-benevoles-kabaret'] , 
					'user-group' 
				);
				
				// tester aussi si la personne participe au Kabaret
				
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
				
				//$ids_of_benevoles = array_intersect( $ids_of_benevoles, $ids_of_complete );
				
//				echo '<pre>$ids_of_benevoles:';
//				var_dump($ids_of_benevoles);
//				echo '</pre>';
				
				// enlever les champs zéro: 
				$ids_of_benevoles = array_filter($ids_of_benevoles);
        
        echo '<p>Nombre de bénévoles: '.count($ids_of_benevoles).'</p>';
        
        // user query 1
        //***************************************
        /*
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
        */
        // Function to generate users
        // kino_user_fields_benevoles()
        // in : bp-user-fields.php
        
        //if ( ! empty( $user_query->results ) ) {
        if( ! empty( $ids_of_benevoles ) ) {
        	foreach ( $ids_of_benevoles as $kino_userid ) {
        		
        		// $kinoite_participation = kino_user_participation( $kino_userid, $kino_fields );
        		
        			// add to array
        			$kinoites_benevoles[] = kino_user_fields_benevoles( get_user_by('id', $kino_userid), $kino_fields );
        			
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
        	echo '<h2>Gestion des bénévoles Kabaret</h2>
        	<h3>Kinoïtes <a href="'.$url.'/wp-admin/users.php?user-group=benevoles-kabaret">Bénévoles</a> ('.count($kinoites_benevoles).'):</h3>';
        	
        	echo '<p>Liste des kinoïtes bénévoles ayant coché "l’organisation du Kino Kabaret".';
        	
        	?>
        	<table id="gestion-benevoles" class="table table-hover table-bordered table-condensed pending-form">
        		<thead>
          		<tr>
          			<th>#</th>
          			<th>Nom</th>
          			<?php //<th>Real?</th> ?>
          			<th>Rôle Kino</th>
          			<?php //<th>Fonction?</th> ?>
          			<?php //<th>Choix admin</th> ?>
          			<?php //<th>Activités Kino?</th> ?>
          			<th>Dispo</th>
          			<th>Dispo partielle</th>
          			<th>Adresse</th>
        		    <th>Email</th>
        		    <th>Tél.</th>
        		    <th>Véhicule?</th>
        		    <th>Permis conduire?</th>
        		    <th>Inscription</th>        		    
        		    <th>Note</th>
          		</tr>
          	</thead>
          	<tbody>
        		<?php
        		
        				$metronom = 1;
						$email_benevoles = '';
        				foreach ($kinoites_benevoles as $key => $item) {
        				$email_benevoles.=$item['user-email'] .';';
        				
        				//Add to Mailpoet List si id trouvé
						if(getMailpoetId($item["user-id"])){
							kino_add_to_mailpoet_list( 
								getMailpoetId($item["user-id"]), 
								$kino_fields['mailpoet-benevoles'] 
							);
						}
//        							 Add to Mailpoet List
//        							kino_add_to_mailpoet_list( 
//        								$item["user-id"], 
//        								$kino_fields['mailpoet-benevoles'] 
//        							);
        							
        						?>
        						<tr class="pending-candidate" data-id="<?php echo $item["user-id"] ?>">
        							<th><?php echo $metronom++; ?></th>
        							<?php 
        							
        							// Nom
        							echo '<td><a href="'.$url.'/members/'.$item["user-slug"].'/" target="_blank">'.$item["user-name"].'</a></td>';
        							
        							// Real?
        							// ******************
        							  /*							  								
				  								if ( in_array( "real-kab-valid", $item["participation"] ) ) {          				            				
				          				  echo '<td class="success">Approved</td>';
				          				
				          				} else if ( in_array( "real-kab-rejected", $item["participation"] ) ) {
				          				
				          				  echo '<td class="error">Rejected</td>';
				          				
				          				} else if ( in_array( "real-kab-pending", $item["participation"] ) ) {
				          				
				          					echo '<td class="warning">Pending</td>';
				          				
				          				} else {
				
				          					echo '<td></td>';
				  								}
        							
        							*/
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
        								// Bénévole ?
        								if ( in_array( "benevole-kab", $item["participation"] )) {
        									echo '<span class="kp-pointlist">Bénévole</span>';
        								}
        								
        							echo '</td>';
        							
        							// ******************
        							
        							// Fonction
        							/*
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
        							*/
        							//dispo
        							echo '<td style="white-space:nowrap;">';
        							if ( $item["dispo"] ) {
        										foreach ( $item["dispo"] as $key => $value) {
        											echo $value.'<br/>';
        										}
        							}
        							echo '</td>';
        							
        							echo '<td>';
									echo $item["dispo-partiel"];
        							echo '</td>';
        							
        							// ******************
        							
        							// Adresse
        							echo '<td>'.$item["rue"].', '.$item["code-postal"].' '.$item["ville"].', '.$item["pays"].'</td>';
        							
        							// Email
        							?><td><a href="mailto:<?php echo $item["user-email"] ?>?Subject=Kino%20Kabaret" target="_top"><?php echo $item["user-email"] ?></a></td>
										<td>
        					<?php
									//tel
									echo $item["tel"] ?>
									</td>
							<?php
									//véhicule ?>
									<td>
							<?php echo $item["vehicule"] ?>
									</td>
									
        					<?php
									//permis de conduire ?>
									<td>
							<?php echo $item["permis"] ?>
									</td>
        					<?php
									//date d'inscription kino
									//date d'inscription bénévole
									$user_timestamp_complete = get_user_meta( 
										$item["user-id"], 
										'kino_timestamp_complete_2017', 
										true 
									);
									$user_timestamp_complete = str_replace("T", " ", $user_timestamp_complete);
									// supprimer secondes:
									$user_timestamp_complete = substr($user_timestamp_complete, 0, 16);
									echo '<td>Kino: '. $user_timestamp_complete .'<br/>';
									
									$timestamp_benevole = get_user_meta( 
										$item["user-id"], 
										'kino_timestamp_benevole_2017', 
										true  
									);
									$timestamp_benevole = str_replace("T", " ", $timestamp_benevole);
									// supprimer secondes:
									$user_timestamp_complete = substr($user_timestamp_complete, 0, 16);
									echo 'Bénévole: '. $timestamp_benevole .'</td>';
									
									//note admin
									echo '<td>';
									$note_admin = get_user_meta( $item["user-id"], 'kino-admin-gestion-benevole-remarque', true );
									echo '<div id="note_admin_'. $item["user-id"] .'_db">'. $note_admin .'</div>';
									echo '<textarea id="note_admin_'. $item["user-id"] .'" rows="3" cols="20"></textarea>
									<a class="admin-action pending-other" data-action="benevole-add-info">ajouter</a>';

        							echo '</td>';
									
									// fin de la rangée
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



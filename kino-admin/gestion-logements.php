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
        
        - Total of users
        
        - in group: Kinogeneva 2016 (pending)
        
        - in group: real.plateforme (pending)
        
        - in group: real.kino (pending)
        
        ****/
        	
        $kino_debug_mode = 'off';
        
        $url = site_url();
        
        $kino_fields = kino_test_fields();
        
        // Kinoites qui cherchent un logement
        $kinoites_cherchent_logement = array();
        
        $kinoites_cherchent_logement_ids = get_objects_in_term( 
        	$kino_fields['group-cherche-logement'],
        	'user-group' 
        );
        
        // Kinoites qui offrent un logement
        $kinoites_offrent_logement = array();
        
        $kinoites_offrent_logement_ids = get_objects_in_term( 
        	$kino_fields['group-offre-logement'] , 
        	'user-group' 
        );
   
        //Infos des users qui cherchent un logement
        $kinoites_cherchent_logement = array();
        foreach($kinoites_cherchent_logement_ids as $kino_userid) {
			// Add info to array 
        	$kinoites_cherchent_logement[] = kino_user_fields_logement( get_user_by('id', $kino_userid), $kino_fields );
			
			// TODO: tester si le kinoïte est déjà logé!
			
		}
		 //Infos des users qui offrent un logement
		$kinoites_offrent_logement = array();
        foreach($kinoites_offrent_logement_ids as $kino_userid) {
			//get info
			$kino_user_fields_logement = kino_user_fields_logement( get_user_by('id', $kino_userid), $kino_fields );
			// Add info to user array
        	$kinoites_offrent_logement[$kino_userid] = $kino_user_fields_logement;
        	
        	//set term name and add to user array
        	$logement_name = 'Chez '.$kino_user_fields_logement['user-name'];
			$kinoites_offrent_logement[$kino_userid]['logement-name'] = $logement_name;
			$logement_addr = $kino_user_fields_logement["rue"] .', '. $kino_user_fields_logement["code-postal"] .' '. $kino_user_fields_logement["ville"] . ', ' . $kino_user_fields_logement["pays"] . ' Tél. '. $kino_user_fields_logement["tel"];
			
			 // Ajout du code qui génère automatiquement les logements
        	
        			
			$kino_term_logement = term_exists('logement-'.$kino_userid, 'user-logement');
			
			
			if ( $kino_term_logement !== 0 && $kino_term_logement !== null ) {
				// term exists
				$term_id = $kino_term_logement['term_id'];
				//Returns an array if the parent exists. (format: array('term_id'=>term id, 'term_taxonomy_id'=>taxonomy id)) 
				//add id to user array
				
				$kinoites_offrent_logement[$kino_userid]['logement-term-id'] = $term_id;
				
			} else {
				//$adresse_logement = $logement["rue"].', '.$logement["code-postal"].' '.$logement["ville"].', '.$logement["pays"];
				$logement_descr = $kino_user_fields_logement['offre-logement-remarque']; //description
				

				$nktl = wp_insert_term(
					$logement_name, // the term name = "chez ..."
					'user-logement', // the taxonomy
					array(
						'description'=> $logement_descr,
						'slug' => 'logement-'.$kino_userid,
					)
				);
					
				if ( ! is_wp_error( $nktl ) ) {
					// Get term_id, set default as 0 if not set
					$term_id = isset( $nktl['term_id'] ) ? $nktl['term_id'] : 0;
					
					//add id to user array
					$kinoites_offrent_logement[$kino_userid]['logement-term-id'] = $term_id;
					
				} else {
					 // Trouble in Paradise:
					 // echo $nktl->get_error_message();
				}
				
				
			} // end Creating New Term
			//update term info
			// we can add metadata!
			if($term_id){
				update_term_meta( $term_id, 'kino_adresse_logement', $logement_addr );
				update_term_meta( $term_id, 'kino_nombre_couchages', $kino_user_fields_logement['offre-logement-nb'] );
				//logement description=>
			}
			// Fin du code qui génère automatiquement les logements
		}
        
        // OUTPUT!
        
        /*
		echo '<pre>';
		var_dump($kino_user_fields_logement);
		echo '</pre>';
        */
        
        // Kinoïtes qui cherchent un logement:
        if ( !empty($kinoites_cherchent_logement) ) {
        	echo '<h2>Kinoïtes <a href="'.$url.'/wp-admin/users.php?user-group=cherche-logement">qui cherchent un logement</a> ('.count($kinoites_cherchent_logement).'):</h2>';
        	?>
        	<table id="cherche-logement" class="table table-hover table-bordered table-condensed">
        		<thead>
        			<tr>
        				<th>#</th>
        				<th>ID</th>
        				<th>Nom</th>
        				<th>Enregistrement</th>
        				<th>Real?</th>
        				<th>Rôle</th>
        				<th>Adresse</th>
        		    <th>Email</th>
        		    <th>Tel.</th>
        		    <th>Remarque</th>
        		    
        			</tr>
        		</thead>
        		<tbody>
        		<?php
        			$metronom = 1;
        			
        			foreach ($kinoites_cherchent_logement as $key => $item) {
        					?>
        					<tr>
        						<th><?php echo $metronom++; ?></th>
        						<?php  
        						
        						// ID
        						echo '<td>'.$item["user-id"].'</td>';
        						
        						// Nom:
        								// echo '<td><a href="'.$url.'/members/'.$item["user-slug"].'/" target="_blank">'.$item["user-name"].'</a></td>';
        								// link to groups!
        								echo '<td><a href="'.$url.'/wp-admin/user-edit.php?user_id='.$item["user-id"].'#user-logement" target="_blank">'.$item["user-name"].'</a></td>';
        								
        								
        								//date d'inscription
										$user_timestamp_complete = get_user_meta( $item["user-id"], 'kino_timestamp_complete_2017', true );
										echo '<td>'. $user_timestamp_complete .'</td>';
        								
        								// Real?
        								
        								if ( in_array( "real-kab-valid", $item["participation"] ) ) {          				            				
			          				  echo '<td class="success">Approved</td>';
			          				
			          				} else if ( in_array( "real-kab-rejected", $item["participation"] ) ) {
			          				
			          				  echo '<td class="error">Rejected</td>';
			          				
			          				} else if ( in_array( "real-kab-pending", $item["participation"] ) ) {
			          				
			          					echo '<td class="warning">Pending</td>';
			          				
			          				} else {
			
			          					echo '<td></td>';
        								}
        								
        								// Rôles
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
        									// Bénévole? - benevole-complete
        									if ( in_array( "benevole-complete", $item["participation"] )) {
        										echo '<span class="kp-pointlist">Bénévole</span>';
        									}
        									
        								echo '</td>';
        								
        								// Adresse
        								echo '<td>'.$item["rue"].', '.$item["code-postal"].' '.$item["ville"].', '.$item["pays"].'</td>';
        								
        								// Email
        						?><td><a href="mailto:<?php echo $item["user-email"] ?>?Subject=Kino%20Kabaret" target="_top"><?php echo $item["user-email"] ?></a></td>
        							<td><?php 
        							
        							// Tel
        							echo $item["tel"] ?></td>
        							<td><?php 
        							
        							// Remarque
	        						if ( !empty($item["cherche-logement-remarque"]) ) {
	        							echo $item["cherche-logement-remarque"].' &mdash; ';
	        						}
	        						if ( !empty($item["dispo"]) ) {
	        							echo 'Jours: ';
	        							foreach ( $item["dispo"] as $key => $value) {
	        								echo '<span class="jour-dispo"> '.substr($value, 0, 2).'</span>';
	        							}
	        						}
	        						echo '</td>';
	        						
	        						
        					echo '</tr>'; 
        			} // end foreach
        	echo '</tbody></table>';
        }
        else {
			echo '<h2>Aucun Kinoïtes <a href="'.$url.'/wp-admin/users.php?user-group=cherche-logement">ne cherche de logement</a></h2>';
		}
        
        // Les logements existants:
        // **********************************
        
        $kino_logements_dispo = array();
        $kino_logements_occup = array();
        $kino_logements_total = 0;
		//offre un logement
		if ( !empty($kinoites_offrent_logement) ) {
			
			$kino_logements_total = count( $kinoites_offrent_logement );
			
			foreach ( $kinoites_offrent_logement as $user_id => $logement ) {
				
				$term_id = $logement['logement-term-id'];

				//Nombre de places dispo: 
				$nombre_couchages = $logement['offre-logement-nb'];
				
				//adresse
	        	$adresse_logement = $logement["rue"].', '.$logement["code-postal"].' '.$logement["ville"].', '.$logement["pays"];
				// Nombre de places occupées: 
        	   
				// trouver les objets liés à ce terme!
				$ids_occupants = array();

				$ids_occupants = get_objects_in_term(
					$term_id , 
					'user-logement' // taxonomie
				);
        	   
        	   $nombre_occupants = count($ids_occupants);
        	   
        	   $liste_occupants = array();
        	   
        	   if( !empty($ids_occupants) ) {
					foreach($ids_occupants as $occupant){
						$occupant = kino_user_fields_superlight(get_user_by('id', $occupant), $kino_fields);
						$liste_occupants[] = '<a href="'. $url .'/wp-admin/user-edit.php?user_id='. $occupant['user-id'] .'#user-logement">'. $occupant['user-name'] .'</a>';
					}
				}

        	   // end if empty $ids_occupants
        	   
        	   // Générer les données
        	   
        	   $kino_logement_data = array( 
        	   		"id" => $term_id,
        	   		"name" => $logement['logement-name'],
        	   		"remarques" => $logement['offre-logement-remarque'],
        	   		"adresse" => $adresse_logement,
        	   		"couchages" => $nombre_couchages,
        	   		"occupants" => $ids_occupants,
        	   		"nombre-occupants" => $nombre_occupants,
        	   		"liste-occupants" => $liste_occupants,
        	   	);
        	   
        	   /* si le nombre de places occupées >= places dispo 
        	   	= ajouter aux logements occupés
        	   	= sinon = ajouter aux logements disponibles
        	   */ 
        
        	   if ( $nombre_occupants >= $nombre_couchages ) {
        	   	// ajouter aux logements occupés
        	   		$kino_logements_occup[] = $kino_logement_data;
        	   } else {
        	   	// ajouter aux logements disponibles
        	   		$kino_logements_dispo[] = $kino_logement_data;
        	   }
        	   
        	 } // foreach	
        }
        // Fin du test logements
        
        // Logements dispo: Générer le tableau

        if ( !empty($kino_logements_dispo) ) {
        	echo '<h2>Logements disponibles ('.count( $kino_logements_dispo ).' / '.$kino_logements_total.'):</h2>';
        	
        	?>
        	<table class="table table-hover table-bordered table-condensed">
        		<thead>
          		<tr>
          			<th>#</th>
          			<th>Nom</th>
          			<th>Description</th>
          			<th>Adresse</th>
        		    <th>Couchages</th>
        		    <th>Kinoïtes logés</th>
          		</tr>
          	</thead>
          	<tbody>
        		<?php
        				$metronom = 1;
        				foreach ($kino_logements_dispo as $key => $item) {
        						?>
        						<tr>
        							<th><?php echo $metronom++; ?></th>
        							<?php 
        							// Nom et lien
        									echo '<td><a href="'.$url.'/wp-admin/edit-tags.php?action=edit&taxonomy=user-logement&tag_ID='.$item["id"].'" target="_blank">'.$item["name"].'</a></td>';
											// Description
        							?><td><?php echo $item["remarques"] ?></td>
        							<?php 
        							// Adresse
        							 ?><td><?php echo $item["adresse"] ?></td>
        							<?php 
        							// Nombre couchages
        							 ?><td><?php echo $item["couchages"] ?></td>
        							 <?php 
        							 // Occupants
        							  ?>
        							<td><?php 
        							if ( !empty($item["liste-occupants"]) ) {
        								//
        								foreach ( $item["liste-occupants"] as $occupant ) {
        									echo '<span class="liste-occupants">'.$occupant.' </span>';
        								}
        							}
        							echo '</td>';
        					echo '</tr>';
        				} // end foreach
        		echo '</tbody></table>';
        }
        else {
			echo '<h2>Il n\'y a plus de logements disponibles (0 / '.$kino_logements_total.'):</h2>';
		}
        // Fin logements dispo
        
        // Logements occupés: Générer le tableau
        // $kino_logements_occup = array();
        if ( !empty($kino_logements_occup) ) {
        	echo '<h2>Logements occupés ('.count($kino_logements_occup).' / '.$kino_logements_total.'):</h2>';
        	
        	?>
        	<table class="table table-hover table-bordered table-condensed">
        		<thead>
          		<tr>
          			<th>#</th>
          			<th>Nom</th>
          			<th>Description</th>
          			<th>Adresse</th>
        		    <th>Couchages</th>
        		    <th>Kinoïtes logés</th>
          		</tr>
          	</thead>
          	<tbody>
        		<?php
        				$metronom = 1;
        				foreach ($kino_logements_occup as $key => $item) {
        						?>
        						<tr>
        							<th><?php echo $metronom++; ?></th>
        							<?php 
        							// Nom et lien
        									echo '<td><a href="'.$url.'/wp-admin/edit-tags.php?action=edit&taxonomy=user-logement&tag_ID='.$item["id"].'" target="_blank">'.$item["name"].'</a></td>';
        							// Description
        							?><td><?php echo $item["remarques"] ?></td>
        							<?php 
        							// Adresse
        							 ?><td><?php echo $item["adresse"] ?></td>
        							<?php 
        							// Nombre couchages
        							 ?><td><?php echo $item["couchages"] ?></td>
        							 <?php 
        							 // Occupants
        							  ?>
        							<td><?php 
        							if ( !empty($item["liste-occupants"]) ) {
        								//
        								echo $item["nombre-occupants"].': ';
        								foreach ( $item["liste-occupants"] as $occupant ) {
        									echo '<span class="liste-occupants">'.$occupant.'</span>';
        								}
        							}
        							echo '</td>';
        					echo '</tr>';
        				} // end foreach
        		echo '</tbody></table>';
        }
        else {
			echo '<h2>Aucun logement occupés (0 / '.$kino_logements_total.'):</h2>';
		}
        // Fin logements dispo
        
        // Kinoïtes qui offrent un logement:
        if ( !empty($kinoites_offrent_logement) ) {
        	echo '<h2>Kinoïtes <a href="'.$url.'/wp-admin/users.php?user-group=offre-logement">qui offrent un logement</a> ('.count($kinoites_offrent_logement).'):</h2>';
        	
        	?>
        	<table id="offre-logement" class="table table-hover table-bordered table-condensed">
        		<thead>
	        		<tr>
	        			<th>#</th>
	        			<th>ID</th>
	        			<th>Nom</th>
	        			<th>Adresse</th>
	    			    <th>Email</th>
	    			    <th>Tel.</th>
	    			    <th>Nombre</th>
	    			    <th>Type</th>
	        		</tr>
	        	</thead>
	        	<tbody>
        		<?php
        		
        				$metronom = 1;
        		
        				foreach ($kinoites_offrent_logement as $key => $item) {
        						?>
        						<tr>
        							<th><?php echo $metronom++; ?></th>
        							<?php 
        							
        							// ID
        							echo '<td>'.$item["user-id"].'</td>';
        							
        							// Nom
        							echo '<td><a href="'.$url.'/members/'.$item["user-slug"].'/" target="_blank">'.$item["user-name"].'</a></td>';
        							
        							// Adresse
        							echo '<td>'.$item["rue"].', '.$item["code-postal"].' '.$item["ville"].', '.$item["pays"].'</td>';
        							
        							// Email
        							?><td><a href="mailto:<?php echo $item["user-email"] ?>?Subject=Kino%20Kabaret" target="_top"><?php echo $item["user-email"] ?></a></td>
        							<td><?php echo $item["tel"] ?></td>
        							<td><?php echo $item["offre-logement-nb"] ?></td>
        							<td><?php 
        							if ( !empty($item["offre-logement-remarque"]) ) {
        								echo $item["offre-logement-remarque"];
        							}
        							echo '</td>';
        					echo '</tr>';
        				} // end foreach
        		echo '</tbody></table>';
        }
        else {
			echo '<h2>Aucun kinoïtes <a href="'.$url.'/wp-admin/users.php?user-group=offre-logement">n\'offrent de logement</a> ('.count($kinoites_offrent_logement).'):</h2>';
		}
        // Notes
        
        ?>
        <h3>NOTES:</h3>
        
        <p>La distinction entre "logements disponibles / logements occupés" se fait en fonction du nombre de couchages indiqués, et du nombre de kinoïtes logés.</p>
        
        <ul>
	        <li><a href="<?php echo $url; ?>/wp-admin/edit-tags.php?taxonomy=user-logement ">Ajouter un logement</a></li>
	        <li><a href="<?php echo $url; ?>/wp-admin/users.php?user-group=cherche-logement">Voir la liste des demandeurs</a> - pour ajouter des Kinoïtes à un logement: cocher la personne, utiliser le menu tout en bas: "Actions pour: Logements".
	        </li>
        </ul>
        </p>
        <?php      
         ?>
        
    </div><!--end article-content-->

    <?php  ?>
</article>
<!-- End  Article -->
<?php
kino_js_tablesort("cherche-logement");
kino_js_tablesort("offre-logement");
?>

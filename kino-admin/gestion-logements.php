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
        	$kino_fields['group-cherche-logement'] , 
        	'user-group' 
        );
        
        // Kinoites qui offrent un logement
        $kinoites_offrent_logement = array();
        
        $kinoites_offrent_logement_ids = get_objects_in_term( 
        	$kino_fields['group-offre-logement'] , 
        	'user-group' 
        );
        
        // user query 1
        //***************************************
        
        $user_fields = array( 
        	'user_login', 
        	'user_nicename', 
        	'display_name',
        	'user_email', 
        	'ID',
        	'registered', 
        );
        
        $user_query_cherche = new WP_User_Query( array( 
        	'include' => $kinoites_cherchent_logement_ids, // IDs incluses
        	'orderby' => 'registered',
        	'order' => 'ASC' 
        ) );
        
        
//        echo '<pre> $user_query_cherche :';
//        			var_dump($user_query_cherche);
//        			echo '</pre>';
        
        if ($kino_debug_mode == "on") {
        	echo '<pre>';
        	var_dump($user_query_cherche->results);
        	echo '</pre>';
        }
        
        // Function to generate users
        // kino_user_fields_logement()
        // in : bp-user-fields.php
        
        if ( ! empty( $user_query_cherche->results ) ) {
        	foreach ( $user_query_cherche->results as $user ) {
        		
        		// infos about WP_user object
        		$kino_userid = $user->ID ;
        		
        		// TODO: tester si le kinoïte est déjà logé!
        		
        		// Add info to array 
        		$kinoites_cherchent_logement[] = kino_user_fields_logement( $user, $kino_fields );
        		
        		if ($kino_debug_mode == "on") {
        		
        			echo '<pre>';
        			echo 'DUMP';
        			var_dump($kinoites_cherchent_logement);
        			echo '</pre>';
        		}
        	
        	} // End foreach
        } // End testing user_query_cherche	
        
        // user query 2
        //***************************************
        
        $user_query_offre = new WP_User_Query( array( 
        	'include' => $kinoites_offrent_logement_ids, // IDs incluses 
        	'orderby' => 'registered',
        	'order' => 'DESC' 
        ) );
        
        if ( ! empty( $user_query_offre->results ) ) {
        	foreach ( $user_query_offre->results as $user ) {
        			
        			// infos about WP_user object
        			$kino_userid = $user->ID ;
        			
        			// Add info to array 
        			$kinoites_offrent_logement[] = kino_user_fields_logement( $user, $kino_fields );
        			
        	} // End foreach
        } // End testing User_Query
        	
        //***************************************
        
        // OUTPUT!
        
        // Kinoïtes qui cherchent un logement:
        if ( !empty($kinoites_cherchent_logement) ) {
        	echo '<h2>Kinoïtes <a href="'.$url.'/wp-admin/users.php?user-group=cherche-logement">qui cherchent un logement</a> ('.count($kinoites_cherchent_logement).'):</h2>';
        	?>
        	<table class="table table-hover table-bordered table-condensed">
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
        								
        								
        								// Enregistrement
        								echo '<td>'.$item["user-registered"].'</td>';
        								
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
        									if ( in_array( "realisateur-2016", $item["participation"] )) {
        										echo '<span class="kp-pointlist">Réalisateur-trice</span>';
        									}
        									// Technicien ?
        									if ( in_array( "technicien-2016", $item["participation"] )) {
        										echo '<span class="kp-pointlist">Artisan-ne / technicien-ne</span>';
        									}
        									// Comédien ?
        									if ( in_array( "comedien-2016", $item["participation"] )) {
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
        
        // Les logements existants:
        // **********************************
        
        $kino_logements_dispo = array();
        $kino_logements_occup = array();
        $kino_logements_total = 0;
        
        // obtenir les termes de la taxonomie user-logement
        // get terms of taxonomy : 
        
        $args = array(
            'orderby'                => 'name',
            'order'                  => 'ASC',
            'hide_empty'             => false,
            // 'meta_query'             => ''
        ); 
        $kino_logements = get_terms('user-logement', $args);
        
        if ( !empty($kino_logements) && !is_wp_error( $kino_logements ) ) {
        		
        		$kino_logements_total = count($kino_logements);
        		
//        	echo '<pre>';
//        	var_dump($kino_logements);
//        	echo '</pre>';
        	
        	/*
        	 * Valeurs disponibles: 
        	  - term_id
        	  - name
        	  - slug
        	  - term_taxonomy_id
        	  - description
        	*/
        	
        	foreach ( $kino_logements as $logement ) {
        	   
        	   // on a : ["term_id"]
        	   
        	   // Init
        	   $nombre_couchages = 0;
        	   $nombre_occupants = 0;
        	   $ids_occupants = array();
        	   $liste_occupants = array();
        	   
        	   // Nombre de places dispo: 
        	   
        	   $nombre_couchages = get_metadata( 
	        	   	'term', 
	        	   	$logement->term_id, 
	        	   	'kino_nombre_couchages', 
	        	   	true );
	        	   	
//	        	 echo '<p>nombre de couchages: '.$nombre_couchages.'</p>';
	        	   	
	        	 $adresse_logement = get_metadata( 
	        	   	'term', 
	        	   	$logement->term_id, 
	        	   	'kino_adresse_logement', 
	        	   	true );
        	   
        	   // Nombre de places occupées: 
        	   
        	   // trouver les objets liés à ce terme!
        	   $ids_occupants = get_objects_in_term( 
        	   	$logement->term_id , 
        	   	'user-logement' // taxonomie
        	   );
        	   
//	           	echo '<pre> IDs occupants: ';
//	           	var_dump($ids_occupants);
//	           	echo '</pre>';
        	   
        	   if ( !empty($ids_occupants) ) {
        	   	
		        	   	/*
		        	   	 * Liste des occupants */
		        	   	
		        	   	$occupants_query = new WP_User_Query( array( 
		        	   		'include' => $ids_occupants, // IDs incluses
		        	   		'meta_key'  => 'last_name',
		        	   		'orderby'  => 'last_name',
		        	   		// 'fields' => $user_fields,
		        	   	) );
		        	   	
//		        	   	echo '<pre> $occupants_query :';
//		        	   	var_dump($occupants_query);
//		        	   	echo '</pre>';
		        	   	
		        	   	$occupants = $occupants_query->get_results();
		        	   	
//		        	   	echo '<pre> $occupants :';
//		        	   			var_dump($occupants);
//		        	   			echo '</pre>';
		        	   	
		        	   	$nombre_occupants = count($occupants_query->results);
		        	   	$liste_occupants = array();
		        	   	
		        	   	if ( ! empty( $occupants ) ) {
		        	   		foreach ( $occupants as $user ) {
		        	   			
//		        	   			echo '<pre> processing user :';
//		        	   					var_dump($user);
//		        	   					echo '</pre>';
		        	   			// name and edit link:
		        	   			// http://kinogeneva.4o4.ch/wp-admin/user-edit.php?user_id=243&wp_http_referer=%2Fwp-admin%2Fusers.php
		        	   			$liste_occupants[] = '<a href="'.$url.'/wp-admin/user-edit.php?user_id='.$user->ID.'#user-logement">'.$user->display_name.'</a>';
		        	   			
		        	   		} // end foreach
		        	   		
//		        	   		echo '<pre> occupants :';
//		        	   			var_dump($liste_occupants);
//		        	   			echo '</pre>';
		        	   		
		        	   	} else {
		        	   	
//		        	   	echo '<pre> $occupants_query->results is empty </pre>';
		        	   	}
		        	   	
		        	   	// end if empty $occupants_query
        	   			
        	   }
        	   // end if empty $ids_occupants
        	   
        	   // Générer les données
        	   
        	   $kino_logement_data = array( 
        	   		"id" => $logement->term_id,
        	   		"name" => $logement->name,
        	   		"remarques" => $logement->description,
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
        // $kino_logements_dispo = array();
        if ( !empty($kino_logements_dispo) ) {
        	echo '<h2>Logements disponibles ('.count($kino_logements_dispo).' / '.$kino_logements_total.'):</h2>';
        	
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
        // Fin logements dispo
        
        // Kinoïtes qui offrent un logement:
        if ( !empty($kinoites_offrent_logement) ) {
        	echo '<h2>Kinoïtes <a href="'.$url.'/wp-admin/users.php?user-group=offre-logement">qui offrent un logement</a> ('.count($kinoites_offrent_logement).'):</h2>';
        	
        	?>
        	<table class="table table-hover table-bordered table-condensed">
        		<thead>
	        		<tr>
	        			<th>#</th>
	        			<th>ID</th>
	        			<th>Nom</th>
	        			<th>Adresse</th>
	    			    <th>Email</th>
	    			    <th>Tel.</th>
	    			    <th>Nombre et type</th>
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
        							<td><?php 
        							if ( !empty($item["offre-logement-remarque"]) ) {
        								echo $item["offre-logement-remarque"];
        							}
        							echo '</td>';
        					echo '</tr>';
        				} // end foreach
        		echo '</tbody></table>';
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
<?php
/**
 * Un template pour la gestion des logements durant le Kabaret
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
		
		$kino_debug_mode = 'off';
		
		$url = site_url();
		
		$kino_fields = kino_test_fields();
		
#Kinoites qui cherchent un logement
		$kinoites_cherchent_logement_ids = array();
		
		$kinoites_cherchent_logement_ids = get_objects_in_term( 
			$kino_fields['group-cherche-logement'],
			'user-group' 
		);
		$kinoites_cherchent_logement_ids = array_filter($kinoites_cherchent_logement_ids);
		
#Kinoites qui offrent un logement
		$kinoites_offrent_logement_ids = array();
		
		$kinoites_offrent_logement_ids = get_objects_in_term( 
			$kino_fields['group-offre-logement'] , 
			'user-group' 
			);
		$kinoites_offrent_logement_ids = array_filter($kinoites_offrent_logement_ids);
		
#Infos des users qui cherchent un logement
		$kinoites_cherchent_logement = array();

		foreach($kinoites_cherchent_logement_ids as $kino_userid) {
			// Add info to array 
			$kinoites_cherchent_logement[] = kino_user_fields_logement( get_user_by('id', $kino_userid), $kino_fields );
			// TODO: tester si le kinoïte est déjà logé!
		}
		
#Infos des users qui offrent un logement
		$kinoites_offrent_logement = array();
		foreach($kinoites_offrent_logement_ids as $kino_userid) {
			//get info
			$kino_user_fields_logement = kino_user_fields_logement( get_user_by('id', $kino_userid), $kino_fields );
			
			// Ajout du code qui génère les logements (term)
			$kino_term_logement = term_exists('logement-'.$kino_userid, 'user-logement');

			if ( $kino_term_logement !== 0 && $kino_term_logement !== null ) {
				//term exists
				//Returns an array if the parent exists. (format: array('term_id'=>term id, 'term_taxonomy_id'=>taxonomy id))
				//récupère les infos user
				$kinoites_offrent_logement[$kino_term_logement['term_id']] = $kino_user_fields_logement;
			}
			
			//création du logement (term)
			else {
				$logement_name = 'Chez '.$kino_user_fields_logement['user-name'];
				$adresse_logement = $kino_user_fields_logement["rue"] .', '. $kino_user_fields_logement["code-postal"] .' '. $kino_user_fields_logement["ville"] . ', ' . $kino_user_fields_logement["pays"];
				
				$nktl = wp_insert_term(
					$logement_name, // the term name = "chez ..."
					'user-logement', // the taxonomy
					array(
						'description'=> $kino_user_fields_logement['offre-logement-remarque'],
						'slug' => 'logement-'.$kino_userid,
					)
				);
					
				if ( ! is_wp_error( $nktl ) ) {
					// Get term_id, set default as 0 if not set
					$term_id = isset( $nktl['term_id'] ) ? $nktl['term_id'] : 0;
					//update term info
					//we can add metadata!
					update_term_meta( $term_id, 'kino_adresse_logement', $adresse_logement );
					update_term_meta( $term_id, 'kino_nombre_couchages', $kino_user_fields_logement['offre-logement-nb'] );
					
					//récupère les infos user
					$kinoites_offrent_logement[$nktl['term_id']] = $kino_user_fields_logement;
				}
				else {
					// Trouble in Paradise:
					// echo $nktl->get_error_message();
				}
			}
			// Fin du code qui génère automatiquement les logements
			
			//récupère les infos des utilisateurs?
			//$kino_term_logement
		}
		
#logements présents dans la liste des logements (TERMS)
		$kino_logements_dispo = array();
		$kino_logements_occup = array();
		$kino_logements_all = array();

        // obtenir les termes de la taxonomie user-logement
        $args = array(
            'orderby' => 'name',
            'order' => 'ASC',
            'hide_empty' => false,
            //'meta_query' => ''
        ); 
        $kino_logements = get_terms('user-logement', $args);
	
		$kino_logements_total = 0;
		if ( !empty($kino_logements) && !is_wp_error( $kino_logements ) ) {
			
			$kino_logements_total = count($kino_logements);
			$kino_logements_all['nombre_couchages_total'] = 0;
			$kino_logements_all['nombre_couchages_restant'] = 0;
			
			foreach ( $kino_logements as $logement ) {
				// Nombre de places dispo:
				$nombre_couchages = 0;
				$nombre_couchages = get_metadata(
					'term', 
					$logement->term_id, 
					'kino_nombre_couchages', 
					true
				);
				
				//adresse
				$adresse_logement = get_metadata(
					'term', 
					$logement->term_id, 
					'kino_adresse_logement', 
					true
				);
        	   
				//Places occupées:
				$ids_occupants = array();
				$nombre_occupants = 0;
				
				//les logés: trouver les objets liés à ce terme!
				$ids_occupants = get_objects_in_term( 
					$logement->term_id , 
					'user-logement' // taxonomie
				);
				$nombre_occupants = count($ids_occupants);

				//les infos des logés
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
        	   		"id" => $logement->term_id,
        	   		"name" => $logement->name,
        	   		"remarques" => $logement->description,
        	   		"adresse" => $adresse_logement,
        	   		"couchages" => $nombre_couchages,
        	   		"occupants" => $ids_occupants,
        	   		"nombre-occupants" => $nombre_occupants,
        	   		"liste-occupants" => $liste_occupants,
        	   	);
        	   	
        	   	//ajoutent les infos du logeant si elles sont dispo
				if (array_key_exists($logement->term_id, $kinoites_offrent_logement)) {
					 $kino_logement_data["logeant"] = $kinoites_offrent_logement[$logement->term_id];
				}
				else {
					$kino_logement_data["logeant"] = array();
				}

				/* si le nombre de places occupées >= places dispo 
				= ajouter aux logements occupés
				= sinon = ajouter aux logements disponibles
				*/ 

				if ( $nombre_couchages!=0 && $nombre_occupants >= $nombre_couchages ) {
					// ajouter aux logements occupés
					$kino_logements_occup[] = $kino_logement_data;
				}
				else {
					// ajouter aux logements disponibles
					$kino_logements_dispo[] = $kino_logement_data;
				}
				if(!empty($kino_logement_data)){
					$kino_logements_all[] = $kino_logement_data;
					$kino_logements_all['nombre_couchages_total']+= $nombre_couchages;
					$kino_logements_all['nombre_couchages_restant']+= ($nombre_couchages - $nombre_occupants);
				}
			} // foreach	
		}
        // Fin du test logements
/*
        if($kino_debug_mode = 'on'){
			echo '<pre>';
			print_r($kino_logements_dispo);
			echo '</pre>';
		}
*/

// OUTPUT!
#1 - Kinoïtes qui cherchent un logement:
        if ( !empty($kinoites_cherchent_logement) ) {
        	echo '<h2>Kinoïtes <a href="'.$url.'/wp-admin/users.php?user-group=cherche-logement">qui cherchent un logement</a> ('.count($kinoites_cherchent_logement).'):</h2>';
        	?>
        	<table id="cherche-logement" class="table table-hover table-bordered table-condensed pending-form">
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
						<th>Note</th>
        			</tr>
        		</thead>
        		<tbody>
        		<?php
        			$metronom = 1;
        			
        			foreach ($kinoites_cherchent_logement as $key => $item) { 
					echo '<tr class="pending-candidate" data-id="'. $item['user-id'] .'">';
					echo '<th>'. $metronom++ .'</th>';
						
						// ID
						echo '<td>'.$item["user-id"].'</td>';
        						
						// Nom: link to groups!
						echo '<td><a href="'.$url.'/wp-admin/user-edit.php?user_id='.$item["user-id"].'#user-logement" target="_blank">'.$item["user-name"].'</a></td>';

						//date d'inscription
						$user_timestamp_complete = get_user_meta( $item["user-id"], 'kino_timestamp_complete_2017', true );
						echo '<td>'. $user_timestamp_complete . ' / '. $item['user-registered'] .'</td>';
						
						// Real?
						if ( in_array( "real-kab-valid", $item["participation"] ) ) {
							echo '<td class="success">Approved</td>';
	          			}
	          			else if ( in_array( "real-kab-rejected", $item["participation"] ) ) {
							echo '<td class="error">Rejected</td>';
	          			}
	          			else if ( in_array( "real-kab-pending", $item["participation"] ) ) {
							echo '<td class="warning">Pending</td>';
						}
						else {
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
						// Bénévole Kab? - benevole-complete
						if ( in_array( "benevole-complete", $item["participation"] )) {
							echo '<span class="kp-pointlist">Bénévole</span>';
						}
						echo '</td>';
								
						// Adresse
						echo '<td>'.$item["ville"].', '.$item["pays"].'</td>';
								
						// Email
						echo '<td><a href="mailto:'. $item["user-email"] .'?Subject=Kino%20Kabaret" target="_top">'. $item["user-email"] .'</a></td>';
						
						// Tel
						echo '<td>'. $item["tel"] .'</td>';

						// Remarque
						echo '<td>';
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
						
						//note admin : TODO
						echo '<td>';
							$note_admin = get_user_meta( $item["user-id"], 'kino-admin-cherche-logement-remarque', true );
							echo '
							<div id="note_admin_'. $item["user-id"] .'_db">'. $note_admin .'</div>
							<textarea id="note_admin_'. $item["user-id"] .'" rows="3" cols="20"></textarea>
							<a class="admin-action pending-other" data-action="cherche-logement-add-info">ajouter</a>';
						echo '</td>';
					echo '</tr>'; 
        			} // end foreach
        	echo '</tbody></table>';
        }
        else {
			echo '<h2>Aucun Kinoïtes <a href="'.$url.'/wp-admin/users.php?user-group=cherche-logement">ne cherche de logement</a></h2>';
		}

#2. Les logements existants:
// **********************************

		if ( !empty($kino_logements_all) ) {
			echo '<h2>Lits disponibles: '. $kino_logements_all['nombre_couchages_restant'] .'/'. $kino_logements_all['nombre_couchages_total'] .'</h2>';
			echo '<h3>Logements disponibles ('.count( $kino_logements_dispo ).' / '.$kino_logements_total.'):</h3>';
			?>
			<table id="offre-logement" class="table table-hover table-bordered table-condensed pending-form">
				<thead>
					<tr>
						<th>#</th>
						<th>Nom</th>
						<th>Kinoïte</th>
						<th>Description</th>
						<th>Adresse</th>
						<th>Email</th>
						<th>Tel.</th>
						<th>Couchages</th>
						<th>Kinoïtes logés</th>
						<th>Notes</th>
	          		</tr>
				<thead>
				<tbody>
        	<?php
        	$metronom = 1;
			foreach ($kino_logements_all as $key => $item) {
				//classe du tableau
				if(!empty($item['logeant'])){
					echo '<tr class="pending-candidate" data-id="'. $item['logeant']['user-id'] .'">';
				}
				else {
					echo '<tr>';
				}
				
				echo '<th>'. $metronom++ .'</th>';

				//Nom et lien du logement
				echo '<td><a href="'.$url.'/wp-admin/edit-tags.php?action=edit&taxonomy=user-logement&tag_ID='.$item["id"].'" target="_blank">'.$item["name"].'</a></td>';
					
				// Nom et lien de l'utilisateur
				echo '<td>';
				if(!empty($item['logeant'])){
					echo '<a href="'.$url.'/members/'.$item['logeant']["user-slug"].'/" target="_blank">'.$item['logeant']["user-name"].'</a>';
				}
				echo '</td>';
				
				// Description
				echo '<td>'. $item["remarques"] .'</td>';

				// Adresse
				echo '<td>'. $item["adresse"] .'</td>';

				//email
				echo '<td>';
				if(!empty($item['logeant'])){
					echo '<a href="mailto:'. $item['logeant']["user-email"] .'?Subject=Kino%20Kabaret" target="_top">'. $item['logeant']["user-email"] .'</a>';
				}
				echo '</td>';
				
				//tel
				echo '<td>';
				if(!empty($item['logeant'])){
					echo $item['logeant']["tel"];
				}
				echo '</td>';

				// Nombre couchages
				$nb_restant = intval($item["couchages"]) - intval($item["nombre-occupants"]);
				echo '<td>'. $nb_restant .'/'. ( $item["couchages"] != 0  ? $item["couchages"] : '?' ) .'</td>';
					
				// Occupants
				echo '<td>';
				if ( !empty($item["liste-occupants"]) ) {
					foreach ( $item["liste-occupants"] as $occupant ) {
						echo '<span class="liste-occupants">'.$occupant.' </span>';
					}
				}
				echo '</td>';
				
				//note admin
				echo '<td>';
				if(!empty($item['logeant'])){
					$note_admin = get_user_meta( $item['logeant']["user-id"], 'kino-admin-offre-logement-remarque', true );
					echo '
					<div id="note_admin_'. $item['logeant']["user-id"] .'_db">'. $note_admin .'</div>
					<textarea id="note_admin_'. $item['logeant']["user-id"] .'" rows="3" cols="20"></textarea>
					<a class="admin-action pending-other" data-action="offre-logement-add-info">ajouter</a>';
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

#Notes
        
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

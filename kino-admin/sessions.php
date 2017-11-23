<?php
/**
 * page kino-admin pour afficher et modifier les sessions attribuées aux réalisateurs validés
 * modif 22.11.2017
 * commit liés:
 * /wp-content/plugins/kinogeneva-settings/buddypress/bp-fields.php 
 * ajout des clé numériques mailpoet
 * 
 * /wp-content/plugins/kinogeneva-settings/mailpoet-add.php
 * ajout d'une fonction pour supprimer de plusieurs listes en même temps
 * 
 * /wp-content/themes/kleo-child/kino-admin/sessions-loop.php
 * suppression de la session super8
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
        
        $ids_real_kabaret_accepted = get_objects_in_term(
        	$kino_fields['group-real-kabaret'] , 
        	'user-group' 
        );
        
        //sessions
		$ids_real_session1 = array();
        $ids_real_session1 = get_objects_in_term( 
        	$kino_fields['group-session-un'] , 
        	'user-group' 
        );
        //print_r($ids_real_session1);
        $ids_real_session2 = array();
        $ids_real_session2 = get_objects_in_term( 
        	$kino_fields['group-session-deux'] , 
        	'user-group' 
        );
        $ids_real_session3 = array();
        $ids_real_session3 = get_objects_in_term( 
        	$kino_fields['group-session-trois'] , 
        	'user-group' 
        );
        $ids_real_sessions8 = array();
        $ids_real_sessions8 = get_objects_in_term( 
        	$kino_fields['group-session-superhuit'] , 
        	'user-group' 
        );
        
        //on cherche les kinoïtes dans chaque session
        $kinoites_session_all = array();
        $kinoites_session_sans = array();
        
        foreach($ids_real_kabaret_accepted as $id) {
			$user_fields = kino_user_fields_superlight( get_user_by('id', $id), $kino_fields );

			//3 sessions
			for ($i = 1; $i <= 3; $i++) {
				$kino_title_session = 'ids_real_session'. $i;
				if ( in_array( $id , $$kino_title_session) ) {
					$kinoites_session_all[$i][] = $user_fields;
				}
			}
			ksort($kinoites_session_all);

			//sans session
			if ( !in_array( $id , $ids_real_session1) &&  !in_array( $id , $ids_real_session2) &&  !in_array( $id , $ids_real_session3)){
				$kinoites_session_sans[] = $user_fields;
			}
			
		}
        
        //affichage
        /* la classe pending-form permet d'enclencher le script dans /wp-content/themes/kleo-child/js/kino-admin.js
         * => ajout et suppression des groupes d'utilisateurs buddypress
         * voir aussi /wp-content/plugins/kinogeneva-settings/ajax.php
         */
         //on créé un tbody avec un identifiant unique pour refléter le changement de session en affichant le réal dans le nouveau tableau
         function kino_session_table_header($session) {
			echo '<table class="table table-hover table-bordered table-condensed pending-form">
			<thead>
				<tr>
					<th>#</th>
					<th>Nom</th>
					<th>Email</th>
					<th>Changement de session</th>
				</tr>
			</thead>
		<tbody id="session'. $session .'">';
		}
		
		//3 sessions
		foreach($kinoites_session_all as $session => $kinoites_session) {
			echo '<h2>'. count($kinoites_session) .' réalisateurs-trices en session '. $session .':</h2>';
			kino_session_table_header($session);
			if(!empty($kinoites_session)){
				
				$metronom = 1;
					foreach ($kinoites_session as $key => $item) {
						include('sessions-loop.php');
					}
			}
			echo '
				</tbody></table>';
		}
		
		//sans session
		echo '<h2>'. count($kinoites_session_sans) .' réalisateurs-trices sans session attribuée:</h2>';
		$session = 0;
		if(!empty($kinoites_session_sans)){
			kino_session_table_header($session);
			$metronom = 1;
				foreach ($kinoites_session_sans as $key => $item) {
					include('sessions-loop.php');
				}
			echo '
			</tbody></table>';
		}        
?>

    </div><!--end article-content-->

</article>
<!-- End  Article -->

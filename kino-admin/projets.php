<?php
/**
 * Un template qui liste les projets et les membres
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
<?php the_content(); 

$url = home_url();

$kino_fields = kino_test_fields();

//get all groups
$args = array(
	'status' => array('public','private'),
	'per_page' => 120
);
$projets = groups_get_groups( $args );

/*
echo '<pre>';
print_r( $projets['groups']);
echo '</pre>';
*/


if ( !empty($projets['groups']) ) { 
	foreach ($projets['groups'] as $project) {
		$group_id = $project->id;
		
		//les données du groupe
		$sessions = wp_get_object_terms( $group_id , 'bp_group_tags', array('fields' => 'names') );
		$project_display = '<a href="'. $url .'/projets/'. $project->slug .'" target="_blank">'. $project->name .'</a>';
		$project_status = $project->status;
		
		//tableau 1: par projet
		$all_projects[$group_id] = array();
		$all_projects[$group_id]['sessions'] = $sessions;
		$all_projects[$group_id]['project_display'] = $project_display;
		$all_projects[$group_id]['project_status'] = $project_status;

		//les données de la fiche projet
		if($fiche_projet_post_id = groups_get_groupmeta($group_id, 'fiche-projet-post-id')) {
			
			//tableau 1
			$all_projects[$group_id]['fiche_projet_post_id'] = $fiche_projet_post_id;
			
			//info réal
			$real_id = get_field('realisateur', $fiche_projet_post_id)['ID'];
			$real = get_user_by('id', $real_id);
			
			$real_display = '<a href="'. $url .'/members/'. $real->user_nicename .'/" target="_blank">'. $real->display_name .'</a>';
			
			//empty tech et comédien tab 1
			$all_projects[$group_id]['tech'] = array();
			$all_projects[$group_id]['comediens'] = array();
			
			//empty roles tableau 2
			$all_members[$real_id]['groups'][$group_id]['roles'] = array();

			//tableau 1
			$all_projects[$group_id]['real_display'] = $real_display;
			
			//tableau 2: par participant
			$all_members[$real_id]['member_display'] = $real_display;
			$all_members[$real_id]['groups'][$group_id] = array(
				'sessions' => $sessions,
				'project_display' => $project_display,
				'project_status' => $project_status,
			);
			$all_members[$real_id]['groups'][$group_id]['roles'][] = 'réalisateur-trice';
			
			//équipe
			
			//classement dans les différentes colonnes à afficher sur le tableau 1
			$roles_2_display = array('Chef-fe opérateur-rice', 'Ingénieur-e / Preneur-se Son', 'Monteur-se image', 'Étalonneur-se', 'Mixeur-se son / Ingénieur-e du son');
			
			//les membres techniciens de la fiche projet : plateforme  + non plateforme
			if( have_rows('equipe', $fiche_projet_post_id) ){
				while ( have_rows('equipe', $fiche_projet_post_id) ) {
					the_row();
					
					//les membres de la plateforme
					if($portfolio_member = get_sub_field('membre_kino_kabaret_2017', $fiche_projet_post_id)){
						$kino_member_id = $portfolio_member['ID'];
						$kino_member = get_user_by('id', $kino_member_id);
						$member_display = '<a href="'. $url .'/members/'. $kino_member->user_nicename .'/" target="_blank">'. $kino_member->display_name .'</a>';
						//role
						if(!$role = get_sub_field('role', $fiche_projet_post_id) ){
							$role = '-';
						}
						
						//tableau 2: seulement membre plateforme
						$all_members[$kino_member_id]['member_display'] = $member_display;
						$all_members[$kino_member_id]['groups'][$group_id]['sessions'] = $sessions;
						$all_members[$kino_member_id]['groups'][$group_id]['project_display'] = $project_display;
						$all_members[$kino_member_id]['groups'][$group_id]['roles'][] = $role;
					}

					//les participants non membres
					elseif($member_display = get_sub_field('membre_hors_plateforme', $fiche_projet_post_id)){
						//role
						if(!$role = get_sub_field('role', $fiche_projet_post_id) ){
							$role = '-';
						}
					}
					//tableau 1 : tous les participants
					$all_projects[$group_id]['tech'][$role][] = $member_display;
				}
			}
			
			//comédiens

			//les comédiens plateforme
			if($portfolio_members = get_field('comedien-nes', $fiche_projet_post_id) ){
				foreach($portfolio_members as $portfolio_member) {
					$kino_member_id = $portfolio_member['ID'];
					$kino_member = get_user_by('id', $kino_member_id);
					
					$member_display = '<a href="'. $url .'/members/'. $kino_member->user_nicename .'/" target="_blank">'. $kino_member->display_name .'</a>';
					
					//tableau 1
					$all_projects[$group_id]['comediens'][] = $member_display;
					
					//tableau 2
					$all_members[$kino_member_id]['member_display'] = $member_display;
					$all_members[$kino_member_id]['groups'][$group_id]['sessions'] = $sessions;
					$all_members[$kino_member_id]['groups'][$group_id]['project_display'] = $project_display;
					$all_members[$kino_member_id]['groups'][$group_id]['roles'][] = 'comedien-ne';
				}
			}
			//tableau 1: les comédiens hors-plateforme
			if( $other_comediens = get_field('autres_comediens', $fiche_projet_post_id) ){
				$all_projects[$group_id]['comediens'][] = wp_strip_all_tags($other_comediens);
			}
		} //fin de la fiche projet
	} //fin des groupes
/*
echo '<pre>';
print_r($all_members);
echo '</pre>';
*/
	?>
		<h2><?php echo count($all_projects); ?> projets visibles (public ou privé)</h2>
		<p>Note: ce tableau n'affiche pas les projets "masqués" (qui concerne généralement les éditions précédentes).</p>
		<p>Se rendre au <a href="#by-users">tableau par participant-e-s</a></p>
        <table id="projets" class="table table-hover table-bordered table-condensed pending-form">
        	<thead>
				<tr>
          			<th>#</th>
          			<th>Titre</th>
          			<th>Status</th>
					<th>Sessions</th>
					<th>Réal</th>
					<th>Chef-op</th>
					<th>Ingé Son</th>
					<th>Monteur</th>
					<th>Etalonneur</th>
					<th>Mixeur</th>
					<th>Autres tech.</th>
					<th>Comédiens</th>
          		</tr>
          	</thead>
			<tbody>
	<?php

	$metronom = 1;

	foreach ($all_projects as $group_id => $project) {
	?>
				<tr class="pending-candidate" data-id="<?php echo $group_id; ?>">
					<th><?php echo $metronom++; ?></th>
					<td>
						<?php echo $project['project_display']; ?>
					</td>
					<td>
					<?php
						echo $project['project_status'];
					?>
					</td>
					<td>
			<?php
			foreach($project['sessions'] as $session) {
				echo '<span class="kp-pointlist">'. $session .'</span>';
			}
			?>
					</td>

			<?php
			if(isset($project['fiche_projet_post_id'])){ ?>
					<td>
					<?php echo $project['real_display']; ?>
					</td>
			<?php

				foreach($roles_2_display as $role ) {
					echo '<td>';
					if(isset($project['tech'][$role])){
						foreach ($project['tech'][$role] as $member) {
							echo '<span class="kp-pointlist">'. $member .'</span>';
						}
					}
					echo '</td>';
				}
				#les autres techniciens
				echo '<td>';
				foreach ($project['tech'] as $tech => $members){
					if(!in_array($tech, $roles_2_display)){
						foreach($members as $member){
							echo '<span class="kp-pointlist">['. $tech .'] '. $member .'</span>';
						}
					}
				}
				echo '</td>';
				echo '<td>';
				#affichage comédiens
				if(!empty($project['comediens'])){
					foreach( $project['comediens'] as $member) {
						echo '<span class="kp-pointlist">'. $member .'</span>'; 
					}
				}
				echo '</td>';

			}
			//pas de fiche projet
			else {
				echo '<td colspan="8"></td>';
			}
			?>

				</tr>
<?php

	} //end foreach $projets ?>
			</tbody>
		</table>
<?php	
} //end if $projets

#tableau 2
if(!empty($all_members)){ ?>
	<h2><a name="by-users"></a><?php echo count($all_members); ?> participants à des projets de films</h2>
	<p>Note: ce tableau n'affiche pas les membres de projets "masqués" (qui concerne généralement les éditions précédentes).</p>

        <table id="projets_by_users" class="table table-hover table-bordered table-condensed pending-form">
			<thead>
				<th>#</th>
				<th>Participant</th>
				<th>Projets et rôles</th>
			</thead>
			<tbody>
	<?php
	$metronom = 1;
	
	foreach($all_members as $user_id => $member){
		echo '<tr class="pending-candidate" data-id="'. $user_id .'">';
		echo '<th>'. $metronom++ .'</th>';
		echo '<td>';
		echo $member['member_display'];
		echo '</td>';
		echo '<td>';
		echo '<ul>';
		foreach($member['groups'] as $group_id => $project_info){
			echo '<li>';
			//lien et nom du groupe
			echo $project_info['project_display'];
			echo '<br/>';
			foreach($project_info['roles'] as $role){
				echo '<span class="kp-pointlist">'. $role .'</span>';
			}
			echo '</li>';
		}
		echo '</ul>';
		echo '</td>';
		echo '</tr>';
		
	} //end foreach $all_members
	?>
			</tbody>
		</table>
<?php
} // end if $all_members
?>
	</div><!--end article-content-->


</article>
<!-- End  Article -->

<?php 
kino_js_tablesort("projets");
kino_js_tablesort("projets_by_users");




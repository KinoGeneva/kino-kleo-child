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


if ( !empty($projets['groups']) ) { ?>
		<h2><?php echo count($projets['groups']); ?> groupes visibles (public ou privé)</h2>
		<p>Note: cette page n'affiche pas les projets "masqués" (qui concerne généralement les éditions précédentes).</p>

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

	foreach ($projets['groups'] as $projet) {
		$group_id = $projet->id;

		if($fiche_projet_post_id = groups_get_groupmeta($group_id, 'fiche-projet-post-id')) {
			$id_real = get_field('realisateur', $fiche_projet_post_id)['ID'];
			$object_real = get_user_by('id', $id_real);
			
			$tel_real = xprofile_get_field_data('Téléphone', $id_real);
		}

		$sessions = wp_get_object_terms( $group_id , 'bp_group_tags', array('fields' => 'names') );
	?>
				<tr class="pending-candidate" data-id="<?php echo $group_id; ?>">
					<th><?php echo $metronom++; ?></th>
					<td>
						<a href="<?php echo $url;?>/projets/<?php echo $projet->slug ?>" target="_blank"><?php echo $projet->name; ?></a>
					</td>
					<td>
					<?php
						echo  $projet->status;
					?>
					</td>
					<td>
			<?php
			foreach($sessions as $session) {
				echo '<span class="kp-pointlist">'. $session .'</span>';
			}
			?>
					</td>
					<td>
			<?php
			if($fiche_projet_post_id){ ?>
					<a href="<?php echo $url; ?>/members/<?php echo $object_real->user_nicename; ?>"><?php echo $object_real->display_name; ?></a>
			<?php
			}
			?>
					</td>

			<?php
			


			#les membres techniciens de la fiche projet
			if($fiche_projet_post_id){
				
				#les membres selon la fiche projet : plateforme  + non plateforme 
				$project_members = array();
				
				if( have_rows('equipe', $fiche_projet_post_id) ){
					while ( have_rows('equipe', $fiche_projet_post_id) ) {
						the_row();
						
						//les membres de la plateforme
						if($portfolio_member = get_sub_field('membre_kino_kabaret_2017', $fiche_projet_post_id)){
							$kino_member = get_user_by('id', $portfolio_member['ID']);
							$member_display = '<a href="'. $url .'/members/'. $kino_member->user_nicename .'/" target="_blank">'. $kino_member->display_name .'</a>';
							//role
							if(!$role = get_sub_field('role', $fiche_projet_post_id) ){
								$role = '-';
							}
							
						}
						//les participants non membres
						elseif($member_display = get_sub_field('membre_hors_plateforme', $fiche_projet_post_id)){
							//role
							if(!$role = get_sub_field('role', $fiche_projet_post_id) ){
								$role = '-';
							}
						}
						$project_members[$role][] = $member_display;
					}
				}
/*
	echo '<pre>';
	print_r($project_members);
	echo '</pre>';
*/
				
				#classement dans les différentes colonnes à afficher
				$roles_2_display = array('Chef-fe opérateur-rice', 'Ingénieur-e / Preneur-se Son', 'Monteur-se image', 'Étalonneur-se', 'Mixeur-se son / Ingénieur-e du son');
				
				foreach($roles_2_display as $role ) {
					echo '<td>';
					if(isset($project_members[$role])){
						foreach ($project_members[$role] as $member) {
							echo '<span class="kp-pointlist">'. $member .'</span>';
						}
					}
					echo '</td>';
				}
				#les autres techniciens
				echo '<td>';
				foreach ($project_members as $role => $members){
					if(!in_array($role, $roles_2_display)){
						foreach($members as $member){
							echo '<span class="kp-pointlist">['. $role .'] '. $member .'</span>';
						}
					}
				}
				echo '</td>';
			}
			//pas de fiche projet
			else {
				echo '<td colspan="6"></td>';
			}
			?>

				<td>
			<?php
			#les membres comédiens de la fiche projet
			
			if($fiche_projet_post_id){
				$comediens = array();
				#les comédiens plateforme
					if($portfolio_members = get_field('comedien-nes', $fiche_projet_post_id) ){
						foreach($portfolio_members as $portfolio_member) {
							$kino_member = get_user_by('id', $portfolio_member['ID']);
							$comediens[] = '<a href="'. $url .'/members/'. $kino_member->user_nicename .'/" target="_blank">'. $kino_member->display_name .'</a>';
						}
					}
					#les comédiens hors-plateforme
					if( $other_comediens = get_field('autres_comediens', $fiche_projet_post_id) ){
						$comediens[] = wp_strip_all_tags($other_comediens);
					}
					
				
				#affichage
				if(!empty($comediens)){
					foreach( $comediens as $member) {
						echo '<span class="kp-pointlist">'. $member .'</span>'; 
					}
				}
			}
			
			
			?>
					</td>
				</tr>
<?php

	} //end foreach $projets ?>
			</tbody>
		</table>
<?php	
} //end if $projets
/*
//affichage des participations aux projets par kinoite
$ids_of_kino_participants = get_objects_in_term(
	$kino_fields['group-kino-pending'] , 
	'user-group' 
);

foreach($ids_of_kino_participants as $id){
	$member_groups = groups_get_user_groups($id);
	if(!empty($member_groups['groups'])){
		$all_members_projects[$id] = $member_groups['groups'];
	}
}
echo '<pre>';
print_r( $all_members_projects);
echo '</pre>';
/*
foreach($all_members_projects as $user_id => $array) {
	$user = get_user_by('id', $user_id);
	$groups = $array['groups'];
	echo $user->display_name;

}*/
?>
	</div><!--end article-content-->


</article>
<!-- End  Article -->

<?php 
kino_js_tablesort("projets");

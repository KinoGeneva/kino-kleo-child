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

//dispo: n'indiquer que la date en chiffres


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
			
			#tous les membres et suiveurs du projet (du groupe buddypress)
			$group_members[] = array();
			$args = array(
				'group_id' => $group_id,
				'exclude_admins_mods'=> 0
			); 
			if ( bp_group_has_members( $args ) ) {
				while ( bp_group_members() ) {
					bp_group_the_member();
					$group_members[bp_get_member_user_id()] = bp_get_group_member_link();
				}
			}
			
			#les membres techniciens de la fiche projet
			$portfolio_members= array();
			if($fiche_projet_post_id){
				#les membres selon la fiche projet : # plateforme (on stock l'identifiant) + # non plateforme (on stock le nom renseigné)
				
				if( have_rows('equipe', $fiche_projet_post_id) ){
					while ( have_rows('equipe', $fiche_projet_post_id) )  {
						the_row();
						$portfolio_member = '';
						if(get_sub_field('membre_kino_kabaret_2017', $fiche_projet_post_id)){
							$portfolio_member = get_sub_field('membre_kino_kabaret_2017', $fiche_projet_post_id)['ID'];
						}
						else if(get_sub_field('membre_hors_plateforme', $fiche_projet_post_id)){
							$portfolio_member = get_sub_field('membre_hors_plateforme', $fiche_projet_post_id);
						}
						if($portfolio_member) {
							$portfolio_members[get_sub_field('role', $fiche_projet_post_id)][] = $portfolio_member;
						}
					}
				}
/*
	echo '<pre>';
	print_r($portfolio_members);
	echo '</pre>';
*/
				#equipe par rôle
				$roles_2_display = array('Chef-fe opérateur-rice'=> array(), 'Ingénieur-e / Preneur-se Son' => array(), 'Monteur-se image' => array(), 'Étalonneur-se' => array(), 'Mixeur-se son / Ingénieur-e du son' => array(), 'Autre' => array());
/*
	echo '<pre>';
	print_r($roles_2_display);
	echo '</pre>';
*/
				#classement dans les différentes colonnes à afficher
				foreach($portfolio_members as $role => $members){
					foreach($members as $member){
						if(array_key_exists($role, $roles_2_display)){
							
							if(array_key_exists($member, $group_members) ){
								$roles_2_display[$role][] = '<span class="kp-pointlist">'. $group_members[$member] .'</span>';
							}
							else {
								$roles_2_display[$role][] = '<span class="kp-pointlist">'. $member .'</span>';
							}
						}
						else {
							if(array_key_exists($member, $group_members) ){
								$roles_2_display['Autre'][] = '<span class="kp-pointlist"> ['. $role .'] '. $group_members[$member] .'</span>';
							}
							else {
								$roles_2_display['Autre'][] = '<span class="kp-pointlist"> ['. $role .'] '. $member .'</span>';
							}
						}
							
					}
				}
				
				
				foreach($roles_2_display as $role_2_display){
					echo '<td>';
					foreach($role_2_display as $member){
						echo $member;						
					}
					echo '</td>';
				}
				
				//les autres techniciens
				
				
			
			}
			
			else {
				echo '<td colspan="6"></td>';
			}
			?>

				<td>
			<?php
			#les membres comédiens de la fiche projet
			$comediens = array();
			if($fiche_projet_post_id){
				#les comédiens plateforme
					if( get_field('comedien-nes', $fiche_projet_post_id) ){
						foreach(get_field('comedien-nes', $fiche_projet_post_id) as $comedien) {
							$comediens[] = $comedien['ID'];
						}
					}
					#les comédiens hors-plateforme
					if( get_field('autres_comediens', $fiche_projet_post_id) ){
						$comediens[] = get_field('autres_comediens', $fiche_projet_post_id);
					}
				
				#comédiens
				if(!empty($comediens)){
					
					foreach( $comediens as $m=> $member) {
					if(array_key_exists($member, $group_members)){
							echo '<span class="kp-pointlist">'. $group_members[$member] .'</span>'; 
						}
						else {
							echo '<span class="kp-pointlist">'. wp_strip_all_tags($member) .'</span>';
						}

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
//affichage par kinoite
$ids_of_kino_participants = get_objects_in_term( 
	$kino_fields['group-kino-pending'] , 
	'user-group' 
);

foreach($ids_of_kino_participants as $id){
	
	$all_members_projects[$id] = groups_get_user_groups($id);
}
echo '<pre>';
print_r( $all_members_projects);
echo '</pre>';

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

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
//display

//séparé technicien et comédien
//supprimé email et tél du réal
//dispo: n'indiquer que la date en chiffres


//chef op - ingé son - monteur - mixeur - étalonneur

//xprofile:
//Image->Chef-fe opérateur-rice
//son->Ingénieur-e / Preneur-se Son
//Postproduction image -> Monteur-se image
//Postproduction son -> Mixeur-se son / Ingénieur-e du son

//ou dans les champs ACF.
//champ ACF => équipe => liste d'équipe avec id des membres
//membre du groupe => id => chercher dans le champs ACF correspondant l'id du membre

//s'il a ça de coché, l'indiquer
if ( !empty($projets['groups']) ) { ?>
		<h2>Par projets</h2>

        <table id="projets" class="table table-hover table-bordered table-condensed pending-form">
        	<thead>
				<tr>
          			<th>#</th>
          			<th>Titre</th>
          			<th>Status</th>
					<th>Sessions</th>
					<th>Réal</th>
        		    <th>Email</th>
					<th>Tél</th>
					<th>Membres</th>
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
		//members
		$args = array(
			'group_id' => $group_id,
			'value' => false,
			);
		$members = groups_get_group_members( $args );
		/*
		echo '<pre>';
		print_r($members);
		echo '</pre>';
*/
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
				echo $session;
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
					<td>
			<?php
			if($fiche_projet_post_id){ ?>
					<a href="mailto:'. <?php echo $object_real->user_email; ?> .'?Subject=Kino%20Kabaret" target="_top"><?php echo $object_real->user_email; ?></a>
				<?php
			}
			?>
					</td>
					<td>
			<?php
			if($fiche_projet_post_id){
				echo $tel_real;
			}
			?>	
					</td>
					<td>
			<?php
			//members
			if(!empty($members['members'])){
				foreach($members['members'] as $member){
					//rôle kino
					$kino_user_role = kino_user_participation(
						$member->ID, 
						$kino_fields
					);
					echo '<div><a href="'. $url .'/members/'. $member->user_nicename .'">'. $member->display_name .'</a><br/>';
					// Technicien ?
					if ( in_array( "technicien-kab", $kino_user_role )) {
						echo '<span class="kp-pointlist">Artisan-ne / technicien-ne';
						$kino_niveau = bp_get_profile_field_data( array(
								'field'   => 1075,
								'user_id' => $member->ID
							) );
							if (!empty($kino_niveau)) {
									echo ' ['.kino_process_niveau($kino_niveau).']';
							}
						
						echo '</span>';
					}
					// Comédien ?
					if ( in_array( "comedien-kab", $kino_user_role )) {
						echo '<span class="kp-pointlist">Comédien-ne';
						
						// niveau?
						$kino_niveau = bp_get_profile_field_data( array(
								'field'   => 927,
								'user_id' => $member->ID
						) );
						if (!empty($kino_niveau)) {
							echo ' ['.kino_process_niveau($kino_niveau).']';
						}
						
						echo '</span>';
					}
					//dispo
					$dispo = bp_get_profile_field_data( array(
						'field'   => $kino_fields["dispo"],
						'user_id' => $member->ID
					) );
					if ( $dispo ) {
						echo '<div>DISPO: </div>';
						foreach ( $dispo as $key => $value) {
							echo '<span class="jour-dispo"> '. substr($value, 0, 5) .'</span>';
						}
					}
					echo '</div>';
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

}
?>
	</div><!--end article-content-->


</article>
<!-- End  Article -->

<?php 
kino_js_tablesort("projets");

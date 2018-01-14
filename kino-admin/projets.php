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
if ( !empty($projets['groups']) ) { ?>
		<h2>Projets</h2>

        <table id="projets" class="table table-hover table-bordered table-condensed pending-form">
        	<thead>
				<tr>
          			<th>#</th>
          			<th>Titre</th>
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
					echo '<span class="kp-pointlist"><a href="'. $url .'/members/'. $member->user_nicename .'">'. $member->display_name .'</a></span>';
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
?>
	</div><!--end article-content-->


</article>
<!-- End  Article -->

<?php 
kino_js_tablesort("projets");

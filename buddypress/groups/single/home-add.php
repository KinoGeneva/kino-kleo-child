<?php

//meta info, id du groupe et id de l'article associé
$group_id = bp_get_group_id(); 
$fiche_projet_post_id = groups_get_groupmeta($group_id, 'fiche-projet-post-id');

//on affiche les données ACF	

?>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDJ-H97yAa8Ze_h_Vu9Vd05of-i__ozsA4"></script>
<script type="text/javascript">
(function($) {

/*
*  new_map
*
*  This function will render a Google Map onto the selected jQuery element
*
*  @type	function
*  @date	8/11/2013
*  @since	4.3.0
*
*  @param	$el (jQuery element)
*  @return	n/a
*/

function new_map( $el ) {
	
	// var
	var $markers = $el.find('.marker');
	
	
	// vars
	var args = {
		zoom		: 16,
		center		: new google.maps.LatLng(0, 0),
		mapTypeId	: google.maps.MapTypeId.ROADMAP
	};
	
	
	// create map	        	
	var map = new google.maps.Map( $el[0], args);
	
	
	// add a markers reference
	map.markers = [];
	
	
	// add markers
	$markers.each(function(){
		
    	add_marker( $(this), map );
		
	});
	
	
	// center map
	center_map( map );
	
	
	// return
	return map;
	
}

/*
*  add_marker
*
*  This function will add a marker to the selected Google Map
*
*  @type	function
*  @date	8/11/2013
*  @since	4.3.0
*
*  @param	$marker (jQuery element)
*  @param	map (Google Map object)
*  @return	n/a
*/

function add_marker( $marker, map ) {

	// var
	var latlng = new google.maps.LatLng( $marker.attr('data-lat'), $marker.attr('data-lng') );

	// create marker
	var marker = new google.maps.Marker({
		position	: latlng,
		map			: map
	});

	// add to array
	map.markers.push( marker );

	// if marker contains HTML, add it to an infoWindow
	if( $marker.html() )
	{
		// create info window
		var infowindow = new google.maps.InfoWindow({
			content		: $marker.html()
		});

		// show info window when marker is clicked
		google.maps.event.addListener(marker, 'click', function() {

			infowindow.open( map, marker );

		});
	}

}

/*
*  center_map
*
*  This function will center the map, showing all markers attached to this map
*
*  @type	function
*  @date	8/11/2013
*  @since	4.3.0
*
*  @param	map (Google Map object)
*  @return	n/a
*/

function center_map( map ) {

	// vars
	var bounds = new google.maps.LatLngBounds();

	// loop through all markers and create bounds
	$.each( map.markers, function( i, marker ){

		var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );

		bounds.extend( latlng );

	});

	// only 1 marker?
	if( map.markers.length == 1 )
	{
		// set center of map
	    map.setCenter( bounds.getCenter() );
	    map.setZoom( 16 );
	}
	else
	{
		// fit to bounds
		map.fitBounds( bounds );
	}

}

/*
*  document ready
*
*  This function will render each map when the document is ready (page has loaded)
*
*  @type	function
*  @date	8/11/2013
*  @since	5.0.0
*
*  @param	n/a
*  @return	n/a
*/
// global var
var map = null;

$(document).ready(function(){

	$('.acf-map').each(function(){

		// create map
		map = new_map( $(this) );

	});
	
	//toogle menu
	$('.besoins h4').next().show();
	$('.besoins h4').click(function() {
		$(this).toggleClass('closeit');
        $(this).next().toggle(200);
        return false;
        
    });
});

})(jQuery);
</script>
<div class="row">
	<div class="col-sm-8 projet">

		<h1><?php bp_group_name(); ?></h1>

		Un film de <span class="strong">
		<?php 
		$id_real = get_field('realisateur', $fiche_projet_post_id)['ID'];
		echo bp_core_get_user_displayname($id_real) .' | ';
		the_field('duree', $fiche_projet_post_id); ?> | <?php the_field('genre', $fiche_projet_post_id); 
		echo '</span>';

		//obtenir la session automatiquement
		$sessions_terms = get_terms( array(
			'taxonomy' => 'user-group',
			'name__like' => 'session' ,
			'fields' => 'names',
		) );
		$user_terms = wp_get_object_terms( $id_real , 'user-group', array('fields' => 'names'));
		
		echo '<h3 class="red">'. current( ( array_intersect( $sessions_terms,$user_terms ) ) ) .'</h3>';
		?>
		
		<h3>Le réalisateur</h3>
		<div class="item-avatar rounded">
			  <?php echo get_avatar($id_real,80); ?>
		</div>
		<?php
		echo '<h4 class="no-color">'. bp_core_get_userlink($id_real) .'</h4>'.
		xprofile_get_field_data('e-mail', $id_real) .'<br/>'. xprofile_get_field_data('Téléphone', $id_real);
		?>
		
		<div style="clear: both;"></div>
		
		<div id="item-buttons" style="text-align: right;">

		<?php do_action( 'bp_group_header_actions' ); ?>

		</div><!-- #item-buttons -->
		
		<hr/>
		<h3>Synopsis</h3>
		<?php bp_group_description() ?>

		<hr/>
		<h3>Équipe</h3>

		<?php
		#les membres selon la fiche projet : # plateforme (on stock l'identifiant) + # non plateforme (on stock le nom renseigné)
		$projet_members= array();
		if( have_rows('equipe', $fiche_projet_post_id) ){
			while ( have_rows('equipe', $fiche_projet_post_id) )  {
				the_row();
				if(get_sub_field('membre_kino_kabaret_2017', $fiche_projet_post_id)){
					$projet_member = get_sub_field('membre_kino_kabaret_2017', $fiche_projet_post_id)['ID'];
				}
				else if(get_sub_field('membre_hors_plateforme', $fiche_projet_post_id)){
					$projet_member = get_sub_field('membre_hors_plateforme', $fiche_projet_post_id);
				}
				if($projet_member) {
					$projet_members[get_sub_field('role', $fiche_projet_post_id)][] = $projet_member;
				}
			}
		}
		//print_r($projet_members);
		
		#les membres du projet qui sont membre du projet (du groupe buddypress)
		$members_KK[] = array();
		if ( bp_group_has_members(  ) ) {
			while ( bp_group_members() ) {
				bp_group_the_member();
				$members_KK[bp_get_member_user_id()] = bp_get_group_member_link();
			}
		}
		# affichage de tous les membres de l'équipe et de leurs rôles
		$display_members = '';
		foreach( $projet_members as $role => $members) {
			$display_members.= '<b>'. $role .' | </b>';
			foreach($members as $member){
				if(array_key_exists($member, $members_KK)){
					$display_members.= $members_KK[$member]; 
				}
				else {
					$display_members.= $member;
				}
				$display_members.= '<br/>';
			}
		}
		echo $display_members;
		?>

		<h4 class="red">membres de la communautés engagés sur le film</h4>
					
		<?php
		if ( bp_group_has_members(  ) ) {
			while ( bp_group_members() ) {
				bp_group_the_member(); ?>
				 <div class="item-avatar rounded">
				  <a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar('type=full&width=40&height=40'); ?></a>
				</div>
				<?php
			}
		}
		?>
		<div style="clear: both"></div>
		<div id="item-buttons" style="text-align: right;">

		<?php do_action( 'bp_group_header_actions' ); ?>

		</div><!-- #item-buttons -->
		<hr/>
		<h2>Tournage</h2>

		<?php
		if( have_rows('lieux_de_tournage', $fiche_projet_post_id) ){
			echo '<div class="acf-map">';
			while ( have_rows('lieux_de_tournage', $fiche_projet_post_id) )  {
				the_row();
				$location = get_sub_field('adresse', $fiche_projet_post_id);
				echo '<div class="marker" data-lat="'. $location['lat'] .'" data-lng="'. $location['lng'] .'">';
				the_sub_field('adresse', $fiche_projet_post_id);
				echo '<br/>';
				the_sub_field('jours', $fiche_projet_post_id); 
				echo ' de ';
				the_sub_field('tournage_debut', $fiche_projet_post_id);
				echo ' à ';
				the_sub_field('tournage_fin', $fiche_projet_post_id);
				echo '</div>';
			}
			echo '</div>';
		}
		?>
		<?php //the_field('lieux_de_tournage', $fiche_projet_post_id); ?>
		<h3>Calendrier du tournage</h3>
		<div class="red">
			<?php
		if( have_rows('lieux_de_tournage', $fiche_projet_post_id) ){
			while ( have_rows('lieux_de_tournage', $fiche_projet_post_id) )  {
				the_row();
				echo '<div class="boxcal">';
				echo '<div class="day">
				'. preg_replace('`[^0-9]`', '', get_sub_field('jours', $fiche_projet_post_id)) .'
				</div>';
				echo '<div class="strong">'. get_sub_field('jours', $fiche_projet_post_id) .', de ';
				the_sub_field('tournage_debut', $fiche_projet_post_id);
				echo ' à ';
				the_sub_field('tournage_fin', $fiche_projet_post_id);
				echo '</div>';
				the_sub_field('adresse', $fiche_projet_post_id);
				echo '<hr/></div>';
			}
		}
		?>
		</div>
	</div>
	<div class="col-sm-4 besoins projet">

		<h3 class="red">Besoins</h3>
		
		<?php
		//link to group forum
		$link_2_forum = '<div class="forumlink"><a href="'. bp_get_group_forum_permalink() .'">SUGGÉRER | POSTULER / FOURNIR</a></div>';
		?>
		
		<?php
		if(get_field('besoin_equipe', $fiche_projet_post_id)){
			echo '<h4>Équipe</h4>
			<div><ul>';
			foreach(get_field('besoin_equipe', $fiche_projet_post_id) as $need){
				echo '<li>'. $need .'</li>';
			}
			echo '</ul>'. $link_2_forum .'
			</div>';
		}
		?>

		<?php
		if( have_rows('besoin_comediens', $fiche_projet_post_id) ){
			echo '<h4>Casting</h4>
			<div><ul>';
			while ( have_rows('besoin_comediens', $fiche_projet_post_id) )  {
				the_row();
				$casting = array();
				if(get_sub_field('besoin_comedien_sexe', $fiche_projet_post_id) && get_sub_field('besoin_comedien_sexe', $fiche_projet_post_id)!='Indifférent'){
					$casting[] = get_sub_field('besoin_comedien_sexe', $fiche_projet_post_id);
				}

				if(get_sub_field('besoin_comedien_age_minimum', $fiche_projet_post_id) && get_sub_field('besoin_comedien_age_maximum', $fiche_projet_post_id)) {
					$casting[] = 'âge caméra de '. get_sub_field('besoin_comedien_age_minimum', $fiche_projet_post_id) .' à '. get_sub_field('besoin_comedien_age_maximum', $fiche_projet_post_id) .' ans';
				}
				
				if(get_sub_field('besoin_comedien_cheveux', $fiche_projet_post_id) && get_sub_field('besoin_comedien_cheveux', $fiche_projet_post_id)!='Indifférent'){
					$casting[] = 'cheveux '. get_sub_field('besoin_comedien_cheveux', $fiche_projet_post_id);
				}
				
				if(get_sub_field('besoin_comedien_yeux', $fiche_projet_post_id) && get_sub_field('besoin_comedien_yeux', $fiche_projet_post_id)!='Indifférent'){
					$casting[] = 'Yeux '. get_sub_field('besoin_comedien_yeux', $fiche_projet_post_id);
				}
				
				if(get_sub_field('besoin_comedien_langues_jouees', $fiche_projet_post_id)){
					$casting[] = 'parlant '. implode(' + ', get_sub_field('besoin_comedien_langues_jouees', $fiche_projet_post_id));
				}
				
				if(get_sub_field('besoin_comedien_talents', $fiche_projet_post_id)){
					$casting[] = implode(', ', get_sub_field('besoin_comedien_talents', $fiche_projet_post_id));
				}
				
				//display casting
				echo '<li>';
				foreach($casting as $n => $need){
					$need = strtolower($need);
					if($n == 0){
						echo ucfirst($need);
					}
					else {
						echo $need;
					}
					if($n < (count($casting)-1)){
						echo ', ';
					}
				}
				echo '</li>';
			}
			echo '</ul>'. $link_2_forum .'</div>';
		}
		?>

		<?php
		if(get_field('casting_et_direction_acteur', $fiche_projet_post_id)) {
			echo "<h4>Casting et direction d'acteur</h4><div>$link_2_forum</div>";
		}
		?>
		
		<?php 
		if(get_field('besoin_lieux_de_tournage', $fiche_projet_post_id)){
			echo '<h4>Lieux de tournage</h4><div>';
			the_field('besoin_lieux_de_tournage', $fiche_projet_post_id);
			$images = get_field('besoin_lieux_de_tournage_photos', $fiche_projet_post_id);

			if( $images ) {
				foreach( $images as $image ) {
					echo '
					<div class="image">
						<a href="'. $image['url'] .'">
							 <img src="'. $image['sizes']['thumbnail'] .'" alt="'. $image['alt'] .'" />
						</a>
					</div>';
				}

			}
			echo '<div style="clear: both"></div>'. $link_2_forum .'</div>';
		}
		?>
		 
		 <?php 
		if(get_field('besoin_accessoires', $fiche_projet_post_id)){
			echo '<h4>Accessoires</h4><div>';
			the_field('besoin_accessoires', $fiche_projet_post_id);
			echo $link_2_forum .'</div>';
		}
		 ?>
		
		 <?php 
		if(get_field('besoin_costumes', $fiche_projet_post_id)){
			echo '<h4>Costumes</h4><div>';
			the_field('besoin_costumes', $fiche_projet_post_id);
			echo $link_2_forum .'</div>';
		}
		 ?>

		
			<?php
			//besoin_maquillage
			if(get_field('besoin_maquillage', $fiche_projet_post_id)) {
				echo '<h4>Maquillage</h4>
				<div><ul>';
				if(get_field('maquillage_1', $fiche_projet_post_id)) {
					echo '<li>'. get_field('maquillage_1', $fiche_projet_post_id) .' comédien(s) | naturel | PAT '. get_field('jour_et_horaire_pat_1', $fiche_projet_post_id) .'</li>';
				}
				if(get_field('maquillage_2', $fiche_projet_post_id)) {
					echo '<li>'. get_field('maquillage_2', $fiche_projet_post_id) . ' comédien(s) | soutenu | PAT '. get_field('jour_et_horaire_pat_2', $fiche_projet_post_id) .'</li>';
				}
				if(get_field('maquillage_3', $fiche_projet_post_id)) {
					echo '<li>'. get_field('maquillage_3', $fiche_projet_post_id) .' comédien(s) | vieillir | PAT '. get_field('jour_et_horaire_pat_3', $fiche_projet_post_id) .'</li>';
				}
				if(get_field('maquillage_4', $fiche_projet_post_id)) {
					echo '<li>'. get_field('maquillage_4', $fiche_projet_post_id) .' comédien(s) | FX | PAT '. get_field('jour_et_horaire_pat_4', $fiche_projet_post_id) .'</li>';
				}
				echo '</ul>'. $link_2_forum .'</div>';
			}
			?>
	
			<?php
			if(get_field('besoin_coiffure', $fiche_projet_post_id)) {
				echo '<h4>Coiffure</h4>';
				echo '<div>'. get_field('coiffure_nombre_de_comedien', $fiche_projet_post_id) .' comédien(s) | coiffure type '. get_field('type_de_coiffure', $fiche_projet_post_id) .' | PAT: '. get_field('coiffure_jour_et_horaire_pat', $fiche_projet_post_id) .'<br/>'. $link_2_forum .'</div>';
			}
			?>
			
			<?php
			//fil d'activité déplacé de home
			// Load appropriate front template
			bp_groups_front_template_part(); 
			?>
	</div>

</div>

<?php
/*

$images = get_field('medias', $fiche_projet_post_id);

if( $images ): ?>
<h3>Galerie</h3>
		<?php foreach( $images as $image ): ?>
			<div style="float: left; margin-right: 10px; margin-bottom: 10px;">
				<a href="<?php echo $image['url']; ?>">
					 <img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
				</a>
				<br/><?php echo $image['caption']; ?>
			</div>
		<?php endforeach; ?>

<?php endif;
*/
?>

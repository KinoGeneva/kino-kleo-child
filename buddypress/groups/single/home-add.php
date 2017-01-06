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

});

})(jQuery);
</script>
<div class="row">
	<div class="col-sm-6">
		
		<h3><?php bp_group_name(); ?></h3>

		<h4>Synopsis</h4>
		<?php bp_group_description() ?>
		
		<h4>Réalisateur</h3>
		<?php //echo bp_core_get_userlink( bp_get_group_creator_id(groups_get_current_group()) );?>
		<?php echo bp_core_get_userlink(get_field('realisateur', $fiche_projet_post_id)['ID']); ?>

		<h4>Équipe</h4>

		<?php
		$projet_members= array();
		if( have_rows('equipe', $fiche_projet_post_id) ){
			while ( have_rows('equipe', $fiche_projet_post_id) )  {
				the_row();
				$projet_members[get_sub_field('role', $fiche_projet_post_id)][] = get_sub_field('membre_kino_kabaret_2017', $fiche_projet_post_id)['ID'];
			}
		}
		?>


		<?php
		/*
		#les rôles
		$fields = array();
		$fields = acf_get_fields_by_id( 3107 );
		//print_r($fields);
        if( $fields ) {
			$members = array();
			$projet_members = array();
			foreach( $fields as $field ) {
				$members = get_field($field['name'], $fiche_projet_post_id);
				if($members) {
					foreach($members as $member) {
						if(stristr($field['name'],'autre')) {
							$role = $field['name'];
						}
						else {
							$role = $field['label'];
						}
						$projet_members[$role][] = $member['ID'];
					}
				}
			}			
		}*/
		
		#les membres de ce groupe
		if ( bp_group_has_members(  ) ) {
			while ( bp_group_members() ) {
				bp_group_the_member();
				bp_group_member_link();
				foreach( $projet_members as $role => $ids) {
					 if(in_array(bp_get_member_user_id(), $ids)) {
						if(stristr($role,'autre')){
							echo ' | ';
							the_field('preciser_'. substr($role, -1, 1), $fiche_projet_post_id);
						}
						else {
							  echo ' | '. $role;
						}
					}
				}
				echo '<br/>';   
			}
		}
		?>

		<h4>Titre définitif</h4>
		<?php the_field('titre_definitif', $fiche_projet_post_id); ?>
		<h4>Durée</h4>
		<?php the_field('duree', $fiche_projet_post_id); ?>
		<h4>Genre</h4>
		<?php the_field('genre', $fiche_projet_post_id); ?>
	</div>
	<div class="col-sm-6">
		<?php
		//obtenir la session automatiquement
		$sessions_terms = get_terms( array(
			'taxonomy' => 'user-group',
			'name__like' => 'session' ,
			'fields' => 'names',
		) );
		$user_terms = wp_get_object_terms( get_current_user_id() , 'user-group', array('fields' => 'names'));
		
		echo '<h3>'. current( ( array_intersect( $sessions_terms,$user_terms ) ) ) .'</h3>';
		
		?>
		
		<h4>tournage</h4>
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
	</div>
</div>

<?php 

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

		<?php endif; ?>

		<h3>Besoins</h3>
		<h4>Équipe technique</h4>
		<?php the_field('besoin_equipe', $fiche_projet_post_id); ?>
		
		<h4>Comédiens</h4>
		<ul>
			<?php
		if( have_rows('besoin_comediens', $fiche_projet_post_id) ){
			while ( have_rows('besoin_comediens', $fiche_projet_post_id) )  {
				the_row();
				echo '<li>';
				echo 'Sexe: ';
				the_sub_field('besoin_comedien_sexe', $fiche_projet_post_id);
				echo '<br/>Talents particuliers: ';
				the_sub_field('besoin_comedien_talents', $fiche_projet_post_id);
				echo '<br/>Langues: ';
				the_sub_field('besoin_comedien_langues_jouees', $fiche_projet_post_id);
				echo '<br/>Couleurs des yeux: ';
				the_sub_field('besoin_comedien_yeux', $fiche_projet_post_id);
				echo '<br/>Teinte des cheveux: ';
				the_sub_field('besoin_comedien_cheveux', $fiche_projet_post_id);
				echo '<br/>Âge caméra: de ';
				the_sub_field('besoin_comedien_age_minimum', $fiche_projet_post_id);
				echo ' à ';
				the_sub_field('besoin_comedien_age_maximum', $fiche_projet_post_id);
				echo '</li>';
			}
		}
/*
		for($c=1; $c<5; $c++) {
			if(get_field('besoin_comedien_'.$c, $fiche_projet_post_id)) {
				echo '<li>';
				echo 'Sexe: ';
				the_field('sexe_'.$c, $fiche_projet_post_id);
				echo '<br/>Talents particuliers: ';
				the_field('talents_particuliers_'.$c, $fiche_projet_post_id);
				echo '<br/>Langues: ';
				the_field('langues_'.$c, $fiche_projet_post_id);
				echo '</li>';
			}
		}*/
		?>
		</ul>
		<?php
		if(get_field('casting_et_direction_dacteur', $fiche_projet_post_id)) {
			echo "<h4>Casting et direction d'acteur</h4>";
		}
		?>
		
		<?php 
		if(get_field('besoin_lieux_de_tournage', $fiche_projet_post_id)){
			echo '<h4>Lieux de tournage</h4>';
			the_field('besoin_lieux_de_tournage', $fiche_projet_post_id);
		}
		 ?>
		 
		 <?php 
		if(get_field('besoin_accessoires', $fiche_projet_post_id)){
			echo '<h4>Accessoires</h4>';
			the_field('besoin_accessoires', $fiche_projet_post_id);
		}
		 ?>
		
		 <?php 
		if(get_field('besoin_costumes', $fiche_projet_post_id)){
			echo '<h4>Costumes</h4>';
			the_field('besoin_costumes', $fiche_projet_post_id);
		}
		 ?>

		
			<?php
			//besoin_maquillage
			if(get_field('besoin_maquillage', $fiche_projet_post_id)) { 
				echo '<h4>Maquillage</h4>
					<ul>';
				if(get_field('maquillage_1', $fiche_projet_post_id)) {
					echo '<li>naturel: '. get_field('maquillage_1', $fiche_projet_post_id) .'<br/>';
					echo 'Jour et horaire PAT: '. get_field('jour_et_horaire_pat_1', $fiche_projet_post_id) .'</li>';
				}
				if(get_field('maquillage_2', $fiche_projet_post_id)) {
					echo '<li>soutenu: '. get_field('maquillage_2', $fiche_projet_post_id) .'<br/>';
					echo 'Jour et horaire PAT: '. get_field('jour_et_horaire_pat_2', $fiche_projet_post_id) .'</li>';
				}
				if(get_field('maquillage_3', $fiche_projet_post_id)) {
					echo '<li>veillir: '. get_field('maquillage_3', $fiche_projet_post_id) .'<br/>';
					echo 'Jour et horaire PAT: '. get_field('jour_et_horaire_pat_3', $fiche_projet_post_id) .'</li>';
				}
				if(get_field('maquillage_4', $fiche_projet_post_id)) {
					echo '<li>Fx: '. get_field('maquillage_4', $fiche_projet_post_id) .'<br/>';
					echo 'Jour et horaire PAT: '. get_field('jour_et_horaire_pat_4', $fiche_projet_post_id) .'</li>';
				}
				echo '</ul> ';
			}
			?>
		
		
		<?php
				if(get_field('besoin_coiffure', $fiche_projet_post_id)) {
					echo '<h4>Coiffure</h4>
			<ul>
				<li>Nombre de comédiens: '. get_field('coiffure_nombre_de_comedien', $fiche_projet_post_id) .'</li>
				<li>Type de coiffure: '. get_field('type_de_coiffure', $fiche_projet_post_id) .'</li>
				<li>Jour et horaires PAT: '. get_field('coiffure_jour_et_horaire_pat', $fiche_projet_post_id) .'</li>
			</ul>';
		}
		?>
		
		<?php
				if(get_field('besoins_transport', $fiche_projet_post_id)) {
					echo '<h4>Besoins de transport: '. get_field('transport_jour_et_horaires', $fiche_projet_post_id) .'</h4>';
				}
		?>

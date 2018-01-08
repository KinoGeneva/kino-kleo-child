<?php
//recherche les champs ACF du groupe de champs "besoin"
$group_ID = 3164; //3113;
$fields = acf_get_fields($group_ID);

if( $fields ) {
	//echo '<pre>';
	//print_r($fields);
	//echo '</pre>';

	$form = '<form id="formneed" method="POST">';

	foreach( $fields as $field ) 	{
		switch($field['name']){
			case 'besoin_equipe':
			$form.= '<div class="col-sm-4"><h5 class="toogleneed">'. $field['label'] .'</h5>
			<div>
			<select name="'. $field['name'].'" id="'. $field['name'] .'" >
			<option value="">---</option>'; 
			foreach($field['choices'] as $value){
				$form.= '
				<option value="'. $value .'">'. $value .'</option>';
			}
			$form .= '</select></div></div>';
			break;
			
			case 'casting_et_direction_acteur':
			case 'besoin_maquillage':
			case 'besoin_coiffure':
				$form.= '<div class="col-sm-4 projet" ><h5 class="toogleneed">'. ucfirst(str_replace("J’ai ", "", $field['label'])) .'</h5>';
				$form.= '<div><input type="checkbox" name="'. $field['name'] .'" id="'. $field['name'] .'" value="1" /> '. ucfirst(str_replace("J’ai ", "", $field['label'])); 
				$form.= '</div></div>';
			break;
	
			case 'besoin_comediens':
			$form.= '<div class="col-sm-4 projet" ><h5 class="toogleneed">'. $field['label'] .'</h5><div>';
			foreach($field['sub_fields'] as $subfield){
				switch($subfield['name']){
					case 'besoin_comedien_sexe':
						$form.= '<div style="margin: 5px 0px;"><b>'.$subfield['label'] .'</b><br/>';
						foreach($subfield['choices'] as $subvalue){
							$form.= '
							<input type="radio" name="'. $subfield['name'] .'" id="'. $subfield['name'] .'" value="'. $subvalue .'" /> '. $subvalue .'<br/>';
						}
						$form.= '</div>';
					break;
					
					case 'besoin_comedien_age_minimum':
					case 'besoin_comedien_age_maximum':
						$form.= '<div style="margin: 5px 0px;"><b>'.$subfield['label'] .'</b><br/><input type="text" name="'. $subfield['name'] .'" id="'. $subfield['name'] .'" value="" /></div>';
					break;
					/*
					case 'besoin_comedien_cheveux':
					case 'besoin_comedien_yeux':
					case 'besoin_comedien_langues_jouees':
					case 'besoin_comedien_talents':
						$form.= '<div style="margin: 5px 0px;"><b>'. $subfield['label'] .'</b><br/><select name="'. $subfield['name'].'" id="'. $subfield['name'].'"><option value="">---</option>'; 
						foreach($subfield['choices'] as $subvalue){
							$form.= '
							<option value="'. $subvalue .'">'. $subvalue .'</option>';
						}
						$form .= '</select></div>';
					break;
					*/
				}
				
			}
			$form.= '</div></div>';
		}
	}
	$form.= '<div style="clear: left;"></div>
	<div class="col-sm-12"><input type="submit" id="submitsearch" 
	class="searchbesoin-button" value="Recherche"> <a href="'. $_SERVER['REQUEST_URI'] .'" class=btn btn-highlight form-submit">Tout afficher</a></div>';
	$form.= '</form>';
}
echo $form;
?>
<div style="clear: left;"></div>

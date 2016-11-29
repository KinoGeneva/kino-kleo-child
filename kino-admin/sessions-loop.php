<?php 
?>
<tr>
	<th><?php echo $metronom++; ?></th>
	<?php 
	
	// Nom
	echo '<td><a href="'.$url.'/members/'.$item["user-slug"].'/profile/" target="_blank">'.$item["user-name"].'</a></td>';
	
	// Email
	?><td><a href="mailto:<?php echo $item["user-email"] ?>?Subject=Kino%20Kabaret" target="_top"><?php echo $item["user-email"] ?></a></td>
	</tr>
<?php 
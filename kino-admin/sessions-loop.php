<?php 
?>
<tr class="pending-candidate" data-id="<?php echo $item["user-id"]; ?>">
	<th><?php echo $metronom++; ?></th>
	<?php 
	
	// Nom
	echo '<td><a href="'.$url.'/members/'.$item["user-slug"].'/profile/" target="_blank">'.$item["user-name"].'</a></td>';
	
	// Email
	?><td><a href="mailto:<?php echo $item["user-email"] ?>?Subject=Kino%20Kabaret" target="_top"><?php echo $item["user-email"] ?></a></td>
	<td>
	<?php
	
	//Tél
	
	echo bp_get_profile_field_data( array( 
            'field'   => $kino_fields["tel"],
            'user_id' => $item["user-id"]
        ) );
        
	//attribution de session
	?>
		</td>
		<td>
			<a class="admin-action pending-accept" data-action="kabaret-session1">session 1</a>
			<a class="admin-action pending-accept" data-action="kabaret-session2">session 2</a>
			<a class="admin-action pending-accept" data-action="kabaret-session3">session 3</a>
		</td> 
	</tr>
<?php 

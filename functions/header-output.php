<?php 

add_action('wp_head', 'kino_wp_head');
function kino_wp_head(){
    
    echo '<style>';
    
    /*
    
    Membres
    #menu-item-1795
    
    My account: 
    #menu-item-633
    
    Login:
    #menu-item-615
    
    Logout:
    #menu-item-632
    
    */
    
    /*
    Hide stuff from BuddyPress Profile Navigation :
    */
    
    ?>
    
    .bp-user #activity-personal-li,
    .bp-user #notifications-personal-li,
    .bp-user #groups-personal-li,
    .bp-user #forums-personal-li,
    .bp-user #invite-anyone-personal-li,
    .bp-user #docs-personal-li {
    	display: none;
    }
    
    <?php
    
    if ( !current_user_can( 'publish_pages' ) ) {
    
    ?>
    
    .bp-user #settings-personal-li {
    	display: none;
    }
    
    <?php
    
    }

    
    if ( !is_user_logged_in() ) {
    	
    	// Hide Membres & My account
    	?>
    	 #menu-item-1795,
    	 #menu-item-633 {
    		display: none;
    	}
    	<?php
    	// Hide Advanced Profile Search if not logged in:
    	?>
    	body.members #buddypress .bps_header {
    		display: none;
    	}
    	
    	.public-menu-hidden,
    	.top-menu .public-menu-hidden {
    		display: none;
    	}
    	
    	<?php 
    } 
    
    /*
    	Code for non-Editors and non-Admin
    */
    
    if ( !current_user_can( 'publish_pages' ) ) {
    	// Hide Admin , Recherche = 2011, Impression = 2012
    	?>
    	#menu-item-2045,
    	.top-menu .kino-menu-hidden
    	 {
    		display: none;
    	}
    	
    	#group-create-nav {
    	 display: none;
    	}
    	
    	
    	
    	<?php 
    } // end Code for non-Editors and non-Admin
    
    echo '</style>';

}


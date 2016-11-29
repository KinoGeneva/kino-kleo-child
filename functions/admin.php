<?php
/**
 * @package WordPress
 */


/**
 * Remove Menu Pages
 ****************
 * http://codex.wordpress.org/Function_Reference/remove_menu_page
 */

add_action( 'admin_menu', 'kgva_remove_menus', 999);
function kgva_remove_menus() {
  remove_menu_page( 'edit.php?post_type=kleo-testimonials' );
  remove_menu_page( 'edit.php?post_type=kleo_clients' );
  // remove user types from WP User Groups plugin
  $page = remove_submenu_page( 'users.php', 'edit-tags.php?taxonomy=user-type' );
}


/// http://kinogeneva.4o4.ch/wp-admin/edit.php?post_type=kleo-testimonials
/// http://kinogeneva.4o4.ch/wp-admin/edit.php?post_type=kleo_clients


/* On the Users Page : Remove columns we don't need */

add_action('manage_users_columns','kgva_remove_users_columns');
function kgva_remove_users_columns($column_headers) {
    unset($column_headers['posts']);
    unset($column_headers['backwpup_role']);
    return $column_headers;
}

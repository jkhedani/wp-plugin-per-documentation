<?php
/**
 * Plugin Name: Codex
 * Plugin URI: https://github.com/jkhedani/wp-plugin-codex
 * Description: A WordPress Plugin aimed at making documentation easier!
 * Version: 0.1.0
 * Text Domain: codex
 * Author: Justin Hedani
 * Author URI: https://github.com/jkhedani
 * License: MIT
 */

if ( ! class_exists('') ) :

class codex {

  function __construct() {

  }

  /**
   * Work, work.
   * Add styles, scripts, custom post types and menu items.
   *
   * @since 0.1.0
   */
  function codex_init() {
    // Styles
    // Functions
    // Admin stuff
    if ( is_user_logged_in() && current_user_can('edit_posts') ) {

    }
  }

  function codex_activate() {
    // Create documentation post type
  }

}

endif; // check if class exists

?>

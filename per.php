<?php
/**
 * Plugin Name: Per Documentation
 * Plugin URI: https://github.com/jkhedani/wp-plugin-per
 * Description: Document your site with ease.
 * Version: 0.1.0
 * Text Domain: per
 * Author: Justin Hedani
 * Author URI: https://github.com/jkhedani
 * License: MIT
 */

if ( ! class_exists('per') ) :

class per {

  /**
   * Construct Functions
   *
   * Functions and actions to be run while plugin
   * is active.
   *
   * @link http://codex.wordpress.org/Plugin_API/Action_Reference/
   * @since 0.1.0
   */
  function __construct() {
    add_action( 'init', array( $this, 'per_init') );
    add_action( 'admin_enqueue_scripts', array( $this, 'per_enqueue_admin_scripts' ) );
    add_action( 'login_enqueue_scripts', array( $this, 'per_register_admin_scripts' ) );

    // Attempt to register menus at the very end of all menus.
    add_action( 'admin_bar_menu', array( $this, 'per_register_toolbar_menus'), 900 );

    // @require WP 3.7+ save_post_{post_type}
    add_action('save_post_documentation', array( $this, 'per_save_post_documentation') );
  }

  /**
   * Enqueue Scripts
   *
   * Register necessary scripts for admin-facing pages.
   *
   * @since 0.1.0
   */
  function per_enqueue_admin_scripts() {
    wp_enqueue_style( 'per-styles', plugins_url( 'assets/css/per-admin.min.css', __FILE__) );
    wp_enqueue_script( 'per-scripts', plugins_url( 'assets/js/per.js', __FILE__) );
  }

  /**
   * Add Functions to Init
   *
   * Any function or action that should run on init.
   *
   * @since 0.1.0
   */
  function per_init() {
    $labels = array(
  		'name'               => _x( 'Documentation', 'post type general name', 'per' ),
  		'singular_name'      => _x( 'Documentation', 'post type singular name', 'per' ),
  		'menu_name'          => _x( 'Documentation', 'admin menu', 'per' ),
  		'name_admin_bar'     => _x( 'Documentation', 'add new on admin bar', 'per' ),
  		'add_new'            => _x( 'Add New', 'book', 'per' ),
  		'add_new_item'       => __( 'Add New Documentation', 'per' ),
  		'new_item'           => __( 'New Documentation', 'per' ),
  		'edit_item'          => __( 'Edit Documentation', 'per' ),
  		'view_item'          => __( 'View Documentation', 'per' ),
  		'all_items'          => __( 'All Documentation', 'per' ),
  		'search_items'       => __( 'Search Documentation', 'per' ),
  		'parent_item_colon'  => __( 'Parent Documentation:', 'per' ),
  		'not_found'          => __( 'No documentation found.', 'per' ),
  		'not_found_in_trash' => __( 'No documentation found in Trash.', 'per' )
  	);
    register_post_type( 'documentation', array(
      'labels'             => $labels,
      'public'             => true,
      'publicly_queryable' => true,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'query_var'          => true,
      'rewrite'            => array( 'slug' => 'documentation' ),
      'has_archive'        => true,
      'hierarchical'       => true,
      'menu_position'      => null,
      'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
    ));

  }

  /**
   * Register Toolbar Menus
   *
   * Add context appropriate toolbar menu items.
   *
   * @since 0.1.0
   */
  function per_register_toolbar_menus() {
    global $wp_admin_bar;
    global $post;
    $current_screen_object = get_current_screen();

    /*
     * Create a menu item on post editing pages that aren't documentation
     * themselves allow editors to create a new documentation entry.
     */
    if ( current_user_can('edit_posts') &&
         $current_screen_object->post_type !== '' &&
         $current_screen_object->post_type !== 'documentation' &&
         $current_screen_object->base === 'post'
        ) :

        /*
         * Determine if any documentation has been created for
         * the post you are editing.
         */
        $documentation = new WP_Query( array(
          'post_type'   => 'documentation',
          'post_status' => 'publish',
          'meta_query'  => array (
            array (
              'key'     => 'documentation_for',
              'value' => $post->ID,
              'compare' => 'IN',
            )
          ),
        ));

        /*
         * If documentation exists, generate an edit link. Otherwise,
         * generate a create link.
         */
        if ( $documentation->have_posts() ) {
          $title = 'Edit Documentation';
          $href  = admin_url() . 'post.php?post="'.$documentation->post->ID.'"&action="edit"';
        } else {
          $title = 'Document This Page';
          $href  = admin_url() . 'post-new.php?post_type=documentation&documentation_for="'.$post->ID.'"';
        }

        // Create admin menu item
        $wp_admin_bar->add_menu( array(
          'id' => 'per-new-documentation',
          'title' => $title,
          'href' => $href,
        ));
    endif;
  }

  /**
   * Save the ID of the post the documentation is for if
   * user requests page specific documentation.
   *
   * @since 0.1.0
   */
  function per_save_post_documentation( $post_id ) {
    if ( isset( $_REQUEST['documentation_for'] ) ) {
      $documentation_for =  get_post_meta( $post_id, 'documentation_for', true );
      if ( $documentation_for === "" ) :
        add_post_meta( $post_id, 'documentation_for', $_REQUEST['documentation_for'] );
        error_log($_REQUEST['documentation_for']);
      endif;
    }
  }


}
$per = new per();
$per;

endif; // check if class exists

?>

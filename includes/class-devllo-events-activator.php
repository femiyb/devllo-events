<?php

/**
 * Fired during plugin activation
 *
 * @link       https://devllo.com/
 * @since      1.0.0
 *
 * @package    Devllo_Events
 * @subpackage Devllo_Events/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Devllo_Events
 * @subpackage Devllo_Events/includes
 * @author     Devllo <94sam.fem@gmail.com>
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Devllo_Events_Activator {

	public function __construct(){
		//add_action( 'init', array( $this, 'pluginprefix_setup_post_type' ));

		add_action( 'admin_init', array( $this, 'load_plugin' ) );




	}

	/**
	 * Called on plugin activation
	 *
	 *
	 * @since    1.0.0
	 */
	/*
	public static function activate() {
echo 'working';
	}*/

	public static function devllo_events_activate() { 
		// Trigger our function that registers the custom post type plugin.
		// Clear the permalinks after the post type has been registered.
		
	//	add_action('init', 'Devllo_Events_Post_Types, register_post_type');

        add_option( 'Activated_Plugin', 'devllo-event' );
    }

    function load_plugin() {

        if ( is_admin() && get_option( 'Activated_Plugin' ) == 'devllo-event' ) {
    
			delete_option( 'Activated_Plugin' );
            /* do stuff once right after activation */
			// example: add_action( 'init', 'my_init_function' );
			global $wpdb;

			// Create Event Page
			if ( null === $wpdb->get_row( "SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'events'", 'ARRAY_A' ) ) {
				$current_user = wp_get_current_user();
				$new_page_content = '[dv-events]';
				// create post object
				$page = array(
					'post_title'  => __( 'Events' ),
					'post_status' => 'publish',
					'post_content' => $new_page_content,
					'post_author' => $current_user->ID,
					'post_type'   => 'page',
				  );
				  // insert the post into the database
					wp_insert_post( $page );
				}

			// Create Calendar Page
			if ( null === $wpdb->get_row( "SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'calendar'", 'ARRAY_A' ) ) {
				$current_user = wp_get_current_user();
				$new_page_content = '[dv-events-calendar]';
				// create post object
				$page = array(
					'post_title'  => __( 'Calendar' ),
					'post_status' => 'publish',
					'post_content' => $new_page_content,
					'post_author' => $current_user->ID,
					'post_type'   => 'page',
					);
					// insert the post into the database
					wp_insert_post( $page );
				}
				
				flush_rewrite_rules(); 

				exit( wp_redirect( admin_url( 'edit.php?page=devllo-events-settings&tab=devllo_events_options&post_type=devllo_event' ) ) );
        }
    }
    

    /**
     * Deactivation hook.
     */
    public static function devllo_events_deactivate() {
    // Unregister the post type, so the rules are no longer in memory.
    unregister_post_type( 'devllo_event' );
    // Clear the permalinks to remove our post type's rules from the database.
    flush_rewrite_rules();
    }
	 

	
}

new Devllo_Events_Activator();


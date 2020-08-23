<?php

/**
 * Devllo Events Post Types
 *
 * @link       https://devllo.com/
 * @since      1.0.0
 *
 * @package    Devllo_Events
 * @subpackage Devllo_Events/includes
 */


/**
 * Prevent loading file directly
 */
defined( 'ABSPATH' ) || exit;


/**
 * Register Post Type
 */

 class Devllo_Events_Post_Types {

	private static $_instance = null;
    
    public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

   

	public function __construct() {

        // register post types
		add_action( 'init', array( $this, 'register_post_type' ), 0 );
		add_action( 'init', array( $this, 'register_taxonomies' ), 0 );
	//	add_action( 'init', array( $this, 'register_location_post_type' ), 0 );
		
    }


    /**
     * Register Post Type Function
     */
    public function register_post_type(){
        // post type
        $labels = array(
            'name'               => _x( 'Events','devllo-events' ),
			'singular_name'      => _x( 'Event', 'devllo-events' ),
			'menu_name'          => _x( 'Events', 'devllo-events' ),
			'name_admin_bar'     => _x( 'Event', 'devllo-events' ),
			'add_new'            => _x( 'Add Event', 'devllo-events' ),
			'add_new_item'       => __( 'Add New Event', 'devllo-events' ),
			'new_item'           => __( 'New Event', 'devllo-events' ),
			'edit_item'          => __( 'Edit Event', 'devllo-events' ),
			'view_item'          => __( 'View Event', 'devllo-events' ),
			'all_items'          => __( 'Events', 'devllo-events' ),
			'search_items'       => __( 'Search Events', 'devllo-events' ),
			'parent_item_colon'  => __( 'Parent Events:', 'devllo-events' ),
			'not_found'          => __( 'No events found.', 'devllo-events' ),
			'not_found_in_trash' => __( 'No events found in Trash.', 'devllo-events' )
        );

        $args = array(
			'labels'             => $labels,
			'description'        => __( 'Event post type.', 'devllo-events' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => _x( 'event', 'URL slug', 'devllo-events' ), 'with_front' => false ),
			//'taxonomies'         => array( 'devllo_event_category' ),
			//'capability_type'    => 'devllo_event',
			'map_meta_cap'       => true,
			'has_archive'        => true,
			'hierarchical'       => true,
			'show_in_menu'		=> true,
			'menu_icon' => 'dashicons-calendar-alt',
			'show_in_rest' => true,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		);
        
		register_post_type( 'devllo_event', $args );
	}


	
	/**
	 * Register Location Post Type
	 */
	/*
	public function register_location_post_type(){
        // post type
        $labels = array(
            'name'               => _x( 'Locations', 'post type general name', 'devllo-events' ),
			'singular_name'      => _x( 'Location', 'post type singular name', 'devllo-events' ),
			'menu_name'          => _x( 'Locations', 'admin menu', 'devllo-events' ),
			'name_admin_bar'     => _x( 'Location', 'add new on admin bar', 'devllo-events' ),
			'add_new'            => _x( 'Add Location', 'event', 'devllo-events' ),
			'add_new_item'       => __( 'Add New Location', 'devllo-events' ),
			'new_item'           => __( 'New Location', 'devllo-events' ),
			'edit_item'          => __( 'Edit Location', 'devllo-events' ),
			'view_item'          => __( 'View Location', 'devllo-events' ),
			'all_items'          => __( 'Locations', 'devllo-events' ),
			'search_items'       => __( 'Search Locations', 'devllo-events' ),
			'parent_item_colon'  => __( 'Parent Locations:', 'devllo-events' ),
			'not_found'          => __( 'No locations found.', 'devllo-events' ),
			'not_found_in_trash' => __( 'No locations found in Trash.', 'devllo-events' )
        );

        $args = array(
			'labels'             => $labels,
			'description'        => __( 'Location post type.', 'devllo-events' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_admin_bar'  => false,
			'show_in_menu'		=> 'edit.php?post_type=devllo_event',
			'query_var'          => true,
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
			'has_archive'        => true,
			'hierarchical'       => true,
			'supports'           => array( 'title', 'editor', 'author' )
		);
        
		register_post_type( 'devllo_events_location', $args );
	}

	*/
	

	// Register Taxonomies

	public function register_taxonomies(){

        $labels = array(
		'name'              => _x( 'Event Tags', 'devllo-events' ),
		'singular_name'     => _x( 'Event Tag', 'tdevllo-events' ),
		'search_items'      => __( 'Search Event Tags', 'devllo-events' ),
		'all_items'         => __( 'All Event Tags', 'devllo-events' ),
		'parent_item'       => __( 'Parent Event Tags', 'devllo-events' ),
		'parent_item_colon' => __( 'Parent Event Tags:', 'devllo-events' ),
		'edit_item'         => __( 'Edit Event Tag', 'devllo-events' ),
		'update_item'       => __( 'Update Event Tag', 'devllo-events' ),
		'add_new_item'      => __( 'Add New Event Tag', 'devllo-events' ),
		'new_item_name'     => __( 'New Event Tag Name', 'devllo-events' ),
		'menu_name'         => __( 'Event Tags', 'devllo-events' ),
	);

	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_menu'		=> 'devllo-events-setting',
		'show_in_rest' => true,
		'rewrite'           => array( 'slug' => 'devllo_events_tag' ),
	);
	register_taxonomy( 'devllo_events_tags', 'devllo_event', $args );
        
        $labels = array(
		'name'              => _x( 'Event Types', 'devllo-events' ),
		'singular_name'     => _x( 'Event Type', 'devllo-events' ),
		'search_items'      => __( 'Search Event Types', 'devllo-events' ),
		'all_items'         => __( 'All Event Types', 'devllo-events' ),
		'parent_item'       => __( 'Parent Event Types', 'devllo-events' ),
		'parent_item_colon' => __( 'Parent Event Types:', 'devllo-events' ),
		'edit_item'         => __( 'Edit Event Type', 'devllo-events' ),
		'update_item'       => __( 'Update Event Type', 'devllo-events' ),
		'add_new_item'      => __( 'Add New Event Type', 'devllo-events' ),
		'new_item_name'     => __( 'New Event Type Name', 'devllo-events' ),
		'menu_name'         => __( 'Event Types', 'devllo-events' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_menu'		=> 'devllo-events-setting',
		'show_in_rest' => true,
		'rewrite'           => array( 'slug' => 'devllo_events_type' ),
	);
	register_taxonomy( 'devllo_events_types', 'devllo_event', $args );
        
        $labels = array(
		'name'              => _x( 'Event Categories', 'devllo-events' ),
		'singular_name'     => _x( 'Category', 'devllo-events' ),
		'search_items'      => __( 'Search Categories', 'devllo-events' ),
		'all_items'         => __( 'All Event Categories', 'devllo-events' ),
		'parent_item'       => __( 'Parent Category', 'devllo-events' ),
		'parent_item_colon' => __( 'Parent Category:', 'devllo-events' ),
		'edit_item'         => __( 'Edit Category', 'devllo-events' ),
		'update_item'       => __( 'Update Category', 'devllo-events' ),
		'add_new_item'      => __( 'Add New Event Category', 'devllo-events' ),
		'new_item_name'     => __( 'New Event Category Name', 'devllo-events' ),
		'menu_name'         => __( 'Event Categories', 'devllo-events' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'show_in_menu'		=> 'devllo-events-setting',
		'show_in_rest' => true,
		'rewrite'           => array( 'slug' => 'event-category' ),
	);
	register_taxonomy( 'devllo_event_categories', 'devllo_event', $args );
        
        
	}

 }

 new Devllo_Events_Post_Types();
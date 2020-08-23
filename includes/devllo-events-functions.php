<?php

/**
 * Devllo Events functions
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
 * template hook function
 */

class Devllo_Events_Functions {

	private static $_instance = null;
    
    public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
    }
    
    public function __construct() {
      
    add_filter( 'single_template', array( $this, 'load_single_event_template' ));

    add_filter( 'archive_template', array( $this, 'load_archive_event_template' ));

    add_filter( 'template_include', array( $this, 'devllo_event_template_redirect' ));

    $this->add_devllo_event_organiser();

    add_filter( 'gettext', array( $this, 'change_author_organiser_text' ), 20, 3);

    add_action( 'admin_action_devllo_event_duplicate_post_as_draft', array( $this, 'devllo_event_duplicate_post_as_draft' ));

    add_filter( 'post_row_actions', array( $this, 'devllo_event_duplicate_post_link' ), 10, 2);


    

    }



   //  Load Event Archive Template 
  function load_archive_event_template( $archive_template ) {
    global $post;

    if ( is_post_type_archive ( 'devllo_event' ) ) {
          $archive_template = DEVLLO_EVENTS_TEMPLATES . 'archive-devllo_event.php';
    }
    return $archive_template;
 }

 function devllo_event_template_redirect( $template ) {
	if ( is_tax( 'devllo_event_categories' ) ) 
		$template = DEVLLO_EVENTS_TEMPLATES . 'archive-devllo_event.php';
	return $template;
}
 
 /*
//  Load Event Page Template
function load_single_event_template( $template ) {
    global $post;

    if ( 'devllo_event' === $post->post_type ) {
    //
        return DEVLLO_EVENTS_TEMPLATES . 'single-devllo_event.php';
    }

    return $template;
}
*/

//  Load Event Page Template

function load_single_event_template($single_template){
  global $post;

     if ($post->post_type == 'devllo_event' ) {
          $single_template = DEVLLO_EVENTS_TEMPLATES . 'single-devllo_event.php';
     }
     return $single_template;
}

// Add Organiser Role
function add_devllo_event_organiser(){


    add_role('devllo_event_organiser', __(
      'Event Organiser'),
      array(
          'level_2'         => true,
          'read'            => true, // Allows a user to read
          'create_devllo_events'      => true, // Allows user to create new posts
          'edit_devllo_events'        => true, // Allows user to edit their own posts
         // 'edit_others_p' => true, // Allows user to edit others posts too
          'publish_devllo_events' => true, // Allows the user to publish posts
          'manage_categories' => true, // Allows user to manage post categories
      )
      );   
}

// Change Author Text to Organiser
/**
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
 */
function change_author_organiser_text( $translated_text, $text, $domain ) {  
  global $pagenow;
  if (( $pagenow == 'post-new.php' ) && (get_post_type() == 'devllo_event')) {
    switch ( $translated_text ) {
      case 'Author' :
          $translated_text = __( 'Event Organiser', 'devllo-events' );
          break;
      case 'Add title' :
        $translated_text = __( 'Add Event', 'devllo-events' );
        break;
        case 'Start writing or type / to choose a block' :
          $translated_text = __( 'Event Description', 'devllo-events' );
          break;
  }
}

if (( $pagenow == 'post.php' ) && (get_post_type() == 'devllo_event')) {
  switch ( $translated_text ) {
    case 'Author' :
        $translated_text = __( 'Event Organiser', 'devllo-events' );
        break;
}
}
return $translated_text;
}


// Duplicate Event Posts
function devllo_event_duplicate_post_as_draft(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'devllo_event_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
		wp_die('No post to duplicate has been supplied!');
	}
 
	/*
	 * Nonce verification
	 */
	if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
		return;
 
	/*
	 * get the original post id
	 */
	$post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
	/*
	 * and all the original post data then
	 */
	$post = get_post( $post_id );
 
	/*
	 * if you don't want current user to be the new post author,
	 * then change next couple of lines to this: $new_post_author = $post->post_author;
	 */
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;
 
	/*
	 * if post data exists, create the post duplicate
	 */
	if (isset( $post ) && $post != null) {
 
		/*
		 * new post data array
		 */
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);
 
		/*
		 * insert the post by wp_insert_post() function
		 */
		$new_post_id = wp_insert_post( $args );
 
		/*
		 * get all current post terms ad set them to the new post draft
		 */
		$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
		}
 
		/*
		 * duplicate all post meta just in two SQL queries
		 */
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				if( $meta_key == '_wp_old_slug' ) continue;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}
 
 
		/*
		 * finally, redirect to the edit post screen for the new draft
		 */
		wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		exit;
	} else {
		wp_die('Post creation failed, could not find original post: ' . $post_id);
	}
}


/*
 * Add the duplicate link to action list for post_row_actions
 */
function devllo_event_duplicate_post_link( $actions, $post ) {
  global $post;

	if ( 'devllo_event' === $post->post_type ) {
		$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=devllo_event_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
	}
	return $actions;
}
 


}

new Devllo_Events_Functions();
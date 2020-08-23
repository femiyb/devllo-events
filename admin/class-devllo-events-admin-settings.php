<?php


/**
 * Devllo Events Admin Settings Page
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

class Devllo_Events_Admin_Settings{

    private static $_instance = null;
    
    public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
    }

    public function __construct() {
      add_action( 'admin_init', array( $this, 'init_settings'  ) );
      

    }

    public function init_settings() {
	  register_setting( 'devllo-events-options', 'devllo-map-api-key' );
	  register_setting( 'devllo-events-pages', 'devllo-events-page' );
    }


    
    public static function devllo_events_settings_page(){
      
      ?>
        <h1><?php echo get_admin_page_title(); ?></h1>

        <?php
        $active_tab = "devllo_events_options";
        if( isset( $_GET[ 'tab' ] ) ) {
            $active_tab = $_GET[ 'tab' ];
          } ?>

        <h2 class="nav-tab-wrapper">
				<a href="?page=devllo-events-settings&tab=devllo_events_options&post_type=devllo_event" class="nav-tab <?php echo $active_tab == 'devllo_events_options' ? 'nav-tab-active' : ''; ?>">Options</a>
				<a href="?page=devllo-events-settings&tab=devllo_events_pages&post_type=devllo_event" class="nav-tab <?php echo $active_tab == 'devllo_events_pages' ? 'nav-tab-active' : ''; ?>">Pages</a>
				</h2>
        
          <form method="post" action="options.php">
<?php

       
        //  $api_key = 'AIzaSyCf3y9wvZWfhgFtGE9YE6_sgDwUwNhT1ow';
        if( $active_tab == 'devllo_events_options' ) {
          settings_fields( 'devllo-events-options' );
          do_settings_sections( 'devllo-events-options' );
           ?>

			<h2><?php _e('Options', 'devllo-events'); ?></h2>
			<?php
            if (!get_option('devllo-map-api-key')){ ?>
                <div class="error notice">
                <?php
                echo 'Please Add Your Google Map API Key. <a href="https://developers.google.com/maps/documentation/javascript/get-api-key">Click Here to get a key</a></div>';
            }
            ?>
		<table class="table">
            <tr>
			<td>Google Map API Key</td>
			</tr>
			<tr>
            <td><input name="devllo-map-api-key" type="text" class="regular-text" value="<?php echo get_option('devllo-map-api-key'); ?>"></td>
			</tr>
            </table>
           
          <?php

		} 
		// Event Pages Settings Page
		elseif ( $active_tab == 'devllo_events_pages' ) {
          settings_fields( 'devllo-events-pages' );
          do_settings_sections( 'devllo-events-pages' );
           ?>
			<h2><?php _e('Pages', 'devllo-events'); ?></h2>
			<table class="table">
			<?php
			 function devllo_post_exists_by_slug( $post_slug ) {
				$loop_posts = new WP_Query( array( 'post_type' => 'page', 'post_status' => 'any', 'name' => $post_slug, 'posts_per_page' => 1, 'fields' => 'ids' ) );
				return ( $loop_posts->have_posts() ? $loop_posts->posts[0] : false );
			} ?>
			<tr>
			<?php
			if (devllo_post_exists_by_slug( 'events' )) {
				?>
			<td>Events Page:</td> <td><input name="devllo-events-page" type="text" class="regular-text" value="<?php echo get_site_url(); ?>/events"></td> <td><a href="<?php echo get_site_url(); ?>/events" class="button">View Page</a></td>
			<?php } 
			else { 
				?>
			<td>Events Page:</td> <td><input name="devllo-events-page" type="text" class="regular-text" value="<?php echo get_option('devllo-events-page'); ?>"></td><td></td>
			</tr>
			<?php  } ?>
			<tr>
			<?php
			if (devllo_post_exists_by_slug( 'calendar' )) {
				?>
			<td>Calendar Page:</td> <td><input name="devllo-calendar-page" type="text" class="regular-text" value="<?php echo get_site_url(); ?>/calendar"></td> <td><a href="<?php echo get_site_url(); ?>/calendar" class="button">View Page</a></td>
			<?php } 
			else { 
				?>
			<td>Calendar Page:</td> <td><input name="devllo-calendar-page" type="text" class="regular-text" value="<?php echo get_option('devllo-calendar-page'); ?>"></td><td></td>
			</tr>
			<?php  } ?>
			
            </table>
          <?php
        }
     			submit_button();
            ?>
             </form>
      <?php
    }

}
Devllo_Events_Admin_Settings::instance();

<?php

defined( 'ABSPATH' ) || exit;

class Devllo_Events_Posts_Admin {

    private static $_instance = null;
    
    public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
    }
    
    public function __construct() {
        add_action( 'save_post', array( $this, 'save_metabox' ), 1, 2 );
        add_action ('add_meta_boxes', array (&$this, 'add_metabox' ));
        add_action( 'save_post', array( $this, 'devllo_eventposts_save_meta' ), 1, 2 );

     
    
    
    }

    // Add Meta Boxes
	public function add_metabox() {  
        add_meta_box(
            'devllo_events_details',
            __( 'Event Details', 'textdomain' ),
            array( $this, 'render_metabox' ),
            'devllo_event',
            'advanced',
            'high'
        );
    }

    public function render_metabox( $post, $args ) {

        global $post, $wp_locale;


		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'devllo_event_inner_custom_box', 'devllo_event_inner_custom_box_nonce' );


		// Use get_post_meta to retrieve an existing value from the database.
		$location = get_post_meta( $post->ID, 'devllo_event_location_key', true );
        $url = get_post_meta( $post->ID, 'devllo_event_url_key', true );
        $location_name = get_post_meta( $post->ID, 'devllo_event_location_name_key', true );
        $event_link = get_post_meta( $post->ID, 'devllo_event_event_link_key', true );
        $location_street = get_post_meta( $post->ID, 'devllo_event_location_street_key', true );
        $location_route = get_post_meta( $post->ID, 'devllo_event_location_route_key', true );
        $location_city = get_post_meta( $post->ID, 'devllo_event_location_city_key', true );
        $location_state = get_post_meta( $post->ID, 'devllo_event_location_state_key', true );
        $location_zip = get_post_meta( $post->ID, 'devllo_event_location_zip_key', true );
        $location_country = get_post_meta( $post->ID, 'devllo_event_location_country_key', true );
        $location_lat = get_post_meta( $post->ID, 'devllo_event_location_lat_key', true );
        $location_long = get_post_meta( $post->ID, 'devllo_event_location_long_key', true );
		
		// Display the form, using the current value.
        ?>
        <label for="devllo_event_location_field">
           <h3> <?php _e( 'Event Location', 'devllo-events' ); ?></h3>
        </label>        

    <div class="container">
	    <div class="panel panel-primary">
            <h4 class="panel-title"><?php _e( 'Event Online Link:', 'devllo-events' ); ?></h4> 
            <input type="url" id="devllo_event_event_link_field" name="devllo_event_event_link_field" value="<?php echo esc_attr( $event_link ); ?>" size="25" />

            <h4 class="panel-title"><?php _e( 'Location Name:', 'devllo-events' ); ?></h4>
            <input type="text" id="devllo_event_location_name_field" name="devllo_event_location_name_field" value="<?php echo esc_attr( $location_name ); ?>" size="25" />

		    <div class="panel-heading">

			<h4 class="panel-title"><?php _e( 'Address', 'devllo-events' ); ?></h4>
            <?php
            if (!get_option('devllo-map-api-key')){ ?>
                <div class="error notice">
                <?php
                echo 'Please Add Your Google Map API Key on the Event Settings Page.</div>';
            }
            ?>
            </div> <!-- /panel-heading-->
        
		<div class="panel-body">
			<input id="autocomplete" value="<?php echo esc_attr( $location );?>" name="autocomplete" placeholder="Enter your address" onFocus="geolocate()" type="search" class="form-control">
			<br>
			<div id="address">
				<div class="row">
					<div class="col-md-6">
						<label class="control-label">Street address</label><br/>
						<input class="form-control" value="<?php echo esc_attr( $location_street );?>" name="street_number" id="street_number" disabled="true">
					</div>
					<div class="col-md-6">
						<label class="control-label">Route</label><br/>
						<input class="form-control" value="<?php echo esc_attr( $location_route );?>" name="route" id="route" disabled="true">
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label class="control-label">City</label><br/>
						<input class="form-control field" value="<?php echo esc_attr( $location_city );?>" name="locality" id="locality" disabled="true">
					</div>
					<div class="col-md-6"> 
						<label class="control-label">State</label><br/>
						<input class="form-control" value="<?php echo esc_attr( $location_state );?>" name="administrative_area_level_1" id="administrative_area_level_1" disabled="true">
					</div>
				</div>
				<div class="row">
					 <div class="col-md-6">
						<label class="control-label">Zip code</label><br/>
						<input class="form-control" value="<?php echo esc_attr( $location_zip );?>" name="postal_code" id="postal_code" disabled="true">
					 </div>
					 <div class="col-md-6">
						<label class="control-label">Country</label><br/>
						<input class="form-control" value="<?php echo esc_attr( $location_country );?>" name="country" id="country" disabled="true">
					 </div>
                     <div class="col-md-6">
						<label class="control-label">Lat</label><br/>
						<input class="form-control" value="<?php echo esc_attr( $location_lat );?>" name="lat" id="lat" disabled="true">
					 </div>
                     <div class="col-md-6">
						<label class="control-label">Long</label><br/>
						<input class="form-control" value="<?php echo esc_attr( $location_long );?>" name="long" id="long" disabled="true">
                     </div>
                     <div class="col-md-6">
						<label class="control-label"><?php _e( 'Event Website:', 'devllo-events' ); ?></label><br/>
                        <input type="url" id="devllo_event_url_field" name="devllo_event_url_field" value="<?php echo esc_attr( $url ); ?>" size="25" />
					 </div>
				</div>
		   </div>
		</div> <!-- /panel-body -->
	</div> <!-- /panel-primary-->
</div>  <!-- /container-->


<!--
    <div id="map"></div>
    <div id="infowindow-content">
      <img src="" width="16" height="16" id="place-icon">
      <span id="place-name"  class="title"></span><br>
      <span id="place-address"></span>
    </div> -->
       <?php


        $metabox_ids = array( 'Event Start Date'=>'_start', 'Event End Date'=>'_end' );

        foreach ($metabox_ids as $key => $metabox_id ) {

        // Use nonce for verification

        $time_adj = current_time( 'timestamp' );
        $month = get_post_meta( $post->ID, $metabox_id . '_month', true );

        if ( empty( $month ) ) {
            $month = gmdate( 'm', $time_adj );
        }

        $day = get_post_meta( $post->ID, $metabox_id . '_day', true );

        if ( empty( $day ) ) {
            $day = gmdate( 'd', $time_adj );
        }

        $year = get_post_meta( $post->ID, $metabox_id . '_year', true );
        if ( empty( $year ) ) {
            $year = gmdate( 'Y', $time_adj );
        }

        $hour = get_post_meta($post->ID, $metabox_id . '_hour', true);

        if ( empty($hour) ) {
            $hour = gmdate( 'H', $time_adj );
        }

        $min = get_post_meta($post->ID, $metabox_id . '_minute', true);

        if ( empty($min) ) {
            $min = '00';
        }

        $month_s = '<select name="' . $metabox_id . '_month">';
        for ( $i = 1; $i < 13; $i = $i +1 ) {
            $month_s .= "\t\t\t" . '<option value="' . zeroise( $i, 2 ) . '"';
            if ( $i == $month )
                $month_s .= ' selected="selected"';
            $month_s .= '>' . $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) . "</option>\n";
        }
        $month_s .= '</select>';

        ?><br>
        <label for="<?php echo $metabox_id; ?>">
            <?php _e( $key, 'devllo-events' ); ?>
        </label><br/>
        <?php
        echo $month_s;
        echo '<input type="text" name="' . $metabox_id . '_day" value="' . $day  . '" size="2" maxlength="2" />';
        echo '<input type="text" name="' . $metabox_id . '_year" value="' . $year . '" size="4" maxlength="4" /> @ ';
        echo '<input type="text" name="' . $metabox_id . '_hour" value="' . $hour . '" size="2" maxlength="2"/>:';
        echo '<input type="text" name="' . $metabox_id . '_minute" value="' . $min . '" size="2" maxlength="2" /> <br>';
            }
}


    public function save_metabox( $post_id, $post ) {
        // Add nonce for security and authentication.
        if ( ! isset( $_POST['devllo_event_inner_custom_box_nonce'] ) ) {
            return $post_id;
		}
		
		$nonce = $_POST['devllo_event_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'devllo_event_inner_custom_box' ) ) {
            return $post_id;
		}
		
		/*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
		}
		
        // Check the user's permissions.
        /*
        if ( 'page' == $_POST['devllo_event'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
		} */
		
		/* OK, it's safe for us to save the data now. */
 
        // Sanitize the user input.
        if (isset($_POST['autocomplete'])){
        $devllo_event_location = sanitize_text_field( $_POST['autocomplete'] );
        }

        if (isset($_POST['devllo_event_url_field'])){
        $devllo_event_url = sanitize_text_field( $_POST['devllo_event_url_field'] );
        }
        
        if (isset($_POST['devllo_event_location_name_field'])){
        $devllo_event_location_name = sanitize_text_field( $_POST['devllo_event_location_name_field'] );
        }

        if (isset($_POST['devllo_event_location_name_field'])){
        $devllo_event_event_link = sanitize_text_field( $_POST['devllo_event_event_link_field'] );
        }

        if (isset($_POST['street_number'])){
        $devllo_event_street = sanitize_text_field( $_POST['street_number'] );
        }
        if (isset($_POST['route'])){
        $devllo_event_route = sanitize_text_field( $_POST['route'] );
        }
        if (isset($_POST['locality'])){
        $devllo_event_city = sanitize_text_field( $_POST['locality'] );
        }
        if (isset($_POST['administrative_area_level_1'])){
        $devllo_event_state = sanitize_text_field( $_POST['administrative_area_level_1'] );
        }
        if (isset($_POST['postal_code'])){
        $devllo_event_zip = sanitize_text_field( $_POST['postal_code'] );
        }
        if (isset($_POST['country'])){
        $devllo_event_country = sanitize_text_field( $_POST['country'] );
        }
        if (isset($_POST['lat'])){
        $devllo_event_lat = sanitize_text_field( $_POST['lat'] );
        }
        if (isset($_POST['long'])){
        $devllo_event_long = sanitize_text_field( $_POST['long'] );
        }

        // Update the meta field.
        if (isset($_POST['autocomplete'])){
        update_post_meta( $post_id, 'devllo_event_location_key', $devllo_event_location );
        }
        
        if (isset($_POST['devllo_event_location_name_field'])){
        update_post_meta( $post_id, 'devllo_event_location_name_key', $devllo_event_location_name ); 
        }

        if (isset($_POST['devllo_event_event_link_field'])){
        update_post_meta( $post_id, 'devllo_event_event_link_key', $devllo_event_event_link ); 
        }
            
        if (isset($_POST['devllo_event_url_field'])){
        update_post_meta( $post_id, 'devllo_event_url_key', $devllo_event_url );
        }

        if (isset($_POST['street_number'])){
        update_post_meta( $post_id, 'devllo_event_location_street_key', $devllo_event_street );
        }

        if (isset($_POST['route'])){
        update_post_meta( $post_id, 'devllo_event_location_route_key', $devllo_event_route );
        }

        if (isset($_POST['locality'])){
        update_post_meta( $post_id, 'devllo_event_location_city_key', $devllo_event_city );
        }

        if (isset($_POST['administrative_area_level_1'])){
        update_post_meta( $post_id, 'devllo_event_location_state_key', $devllo_event_state );
        }

        if (isset($_POST['country'])){
        update_post_meta( $post_id, 'devllo_event_location_country_key', $devllo_event_country );
        }

        if (isset($_POST['postal_code'])){
        update_post_meta( $post_id, 'devllo_event_location_zip_key', $devllo_event_zip );
        }

        if (isset($_POST['lat'])){
        update_post_meta( $post_id, 'devllo_event_location_lat_key', $devllo_event_lat );
        }

        if (isset($_POST['long'])){
        update_post_meta( $post_id, 'devllo_event_location_long_key', $devllo_event_long );
        }


        

        // OK, we're authenticated: we need to find and save the data
        // We'll put it into an array to make it easier to loop though
    
        $metabox_ids = array( '_start', '_end' );

        foreach ($metabox_ids as $key ) {
            $events_meta[$key . '_month'] = $_POST[$key . '_month'];
            $events_meta[$key . '_day'] = $_POST[$key . '_day'];
                if($_POST[$key . '_hour']<10){
                     $events_meta[$key . '_hour'] = '0'.$_POST[$key . '_hour'];
                 } else {
                       $events_meta[$key . '_hour'] = $_POST[$key . '_hour'];
                 }
            $events_meta[$key . '_year'] = $_POST[$key . '_year'];
            $events_meta[$key . '_hour'] = $_POST[$key . '_hour'];
            $events_meta[$key . '_minute'] = $_POST[$key . '_minute'];
            $events_meta[$key . '_eventtimestamp'] = $events_meta[$key . '_year'] . $events_meta[$key . '_month'] . $events_meta[$key . '_day'] . $events_meta[$key . '_hour'] . $events_meta[$key . '_minute'];
        }

        foreach ( $events_meta as $key => $value ) { // Cycle through the $events_meta array!
            if ( $post->post_type == 'revision' ) return; // Don't store custom data twice
            $value = implode( ',', (array)$value ); // If $value is an array, make it a CSV (unlikely)
            if ( get_post_meta( $post->ID, $key, FALSE ) ) { // If the custom field already has a value
                update_post_meta( $post->ID, $key, $value );
            } else { // If the custom field doesn't have a value
                add_post_meta( $post->ID, $key, $value );
            }
            if ( !$value ) delete_post_meta( $post->ID, $key ); // Delete if blank
        }
 
    }

    /**
     * Add Time and Date Meta Boxes
     */

    public function devllo_event_time_date_metabox(){
        add_meta_box(
            'devllo_event_date_start',
            __( 'Start Date and Time', 'devllo-events' ),
            array( $this, 'devllo_event_date' ),
            'devllo_event',
            'advanced',
            'high',
            array( 'id' => '_start')
        );
        
        add_meta_box(
            'devllo_event_date_end',
            __( 'End Date and Time', 'devllo-events' ),
            array( $this, 'devllo_event_date' ),
            'devllo_event',
            'advanced',
            'high',
            array( 'id' => '_end')
        );
 }

    public function devllo_event_date($post, $args){
        
    }


    // Save Metabox Data
    function devllo_eventposts_save_meta( $post_id, $post ) {
        
    }

}
new Devllo_Events_Posts_Admin();
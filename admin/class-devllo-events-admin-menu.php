<?php

defined( 'ABSPATH' ) || exit;

class Devllo_Events_Admin_Menu {
    	/**
	 * menus
	 * @var array
	 */
	public $_menus = array();

	/**
	 * instead new class
	 * @var null
	 */
    static $_instance = null;
    
    public function __construct() {
		// admin menu
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		
	}
	
    

    //  admin menu callback
	public function admin_menu() {
		/**
		 * menus
		 * @var array
		 */
		$menus = apply_filters( 'devllo_events_admin_menu', $this->_menus );
		if ( $menus ) {
			foreach ( $menus as $menu ) {
				call_user_func_array( 'add_submenu_page', $menu );
			}
		}
        add_submenu_page( 'edit.php?post_type=devllo_event', __('Devllo Events Settings', 'devllo-events'), __('Settings', 'devllo-events'), 'manage_options', 'devllo-events-settings', array( 'Devllo_Events_Admin_Settings', 'devllo_events_settings_page'  )); 

	}
	
    /**
	 * add menu item
	 *
	 * @param $params
	 */
	public function add_menu( $params ) {
		$this->_menus[] = $params;
	}

	/**
	 * instance
	 * @return object class
	 */
	public static function instance() {
		if ( self::$_instance )
			return self::$_instance;

		return new self();
	}
}

Devllo_Events_Admin_Menu::instance();

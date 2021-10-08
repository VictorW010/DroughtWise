<?php
/**
 * WP Top News: main plugin class
*/
class WTN_Master {

	protected $wtn_loader;
	protected $wtn_version;
	
	public function __construct() {
		$this->wtn_version = WTN_VRSN;
		add_action( 'plugins_loaded', array($this, WTN_PRFX . 'load_plugin_textdomain') );
		$this->wtn_load_dependencies();
		$this->wtn_trigger_admin_hooks();
		$this->wtn_trigger_front_hooks();
	}
	
	function wtn_load_plugin_textdomain(){
		load_plugin_textdomain( WTN_TXT_DMN, FALSE, WTN_TXT_DMN . '/languages/' );
	}

	private function wtn_load_dependencies(){
		require_once WTN_PATH . 'admin/' . WTN_CLS_PRFX . 'admin.php';
		require_once WTN_PATH . 'front/' . WTN_CLS_PRFX . 'front.php';
		require_once WTN_PATH . 'inc/' . WTN_CLS_PRFX . 'loader.php';
		$this->wtn_loader = new WTN_Loader();
	}

	private function wtn_trigger_admin_hooks(){
		$wtn_admin = new WTN_Admin( $this->wtn_version() );
		$this->wtn_loader->add_action( 'admin_menu', $wtn_admin, WTN_PRFX . 'admin_menu' );
		$this->wtn_loader->add_action( 'admin_enqueue_scripts', $wtn_admin, WTN_PRFX . 'enqueue_assets' );
	}

	private function wtn_trigger_front_hooks(){
		$wtn_front = new WTN_Front( $this->wtn_version() );
		$this->wtn_loader->add_action( 'wp_enqueue_scripts', $wtn_front, WTN_PRFX . 'front_assets' );
		$wtn_front->wtn_load_shortcode();
	}

	public function wtn_run(){
		$this->wtn_loader->wtn_run();
	}
	
	public function wtn_version() {
		return $this->wtn_version;
	}

	function wtn_unregister_settings(){
		global $wpdb;
	
		$tbl = $wpdb->prefix . 'options';
		$search_string = WTN_PRFX . '%';
		
		$sql = $wpdb->prepare( "SELECT option_name FROM $tbl WHERE option_name LIKE %s", $search_string );
		$options = $wpdb->get_results( $sql , OBJECT );
	
		if(is_array($options) && count($options)) {
			foreach( $options as $option ) {
				delete_option( $option->option_name );
				delete_site_option( $option->option_name );
			}
		}
	}
}

<?php
/*
@ Admin Panel Parent Class
*/
class WTN_Admin 
{	
	protected $wtn_version;
	protected $wtn_assets_prefix;

	function __construct( $version ){
		$this->wtn_version = $version;
		$this->wtn_assets_prefix = substr(WTN_PRFX, 0, -1) . '-';
	}
	
	/*
	@	Loading the admin menu
	*/
	function wtn_admin_menu(){
		
		add_menu_page(  esc_html__('WP Top News', WTN_TXT_DMN),
						esc_html__('WP Top News', WTN_TXT_DMN),
						'manage_options',
						'wp-top-news',
						'',
						'dashicons-admin-site-alt',
						100 
					);
		
		add_submenu_page( 	'wp-top-news', 
							esc_html__('API Settings', WTN_TXT_DMN), 
							esc_html__('API Settings', WTN_TXT_DMN), 
							'manage_options', 
							'wtn-api-settings', 
							array( $this, WTN_PRFX . 'api_settings' )
						);

		add_submenu_page( 	'wp-top-news', 
							esc_html__('Settings', WTN_TXT_DMN), 
							esc_html__('General Settings', WTN_TXT_DMN), 
							'manage_options', 
							'wtn-settings', 
							array( $this, WTN_PRFX . 'settings' )
						);
    }
	
	/*
	@	Loading admin panel styles
	*/
	function wtn_enqueue_assets() {
		
		wp_enqueue_style( 'wp-color-picker');
		wp_enqueue_script( 'wp-color-picker');

		wp_enqueue_style('wtn-admin', WTN_ASSETS . 'css/wtn-admin.css', array(), $this->wtn_version, FALSE );
		
		if ( !wp_script_is( 'jquery' ) ) {
			wp_enqueue_script('jquery');
		}

		wp_enqueue_script('wtn-admin', WTN_ASSETS . 'js/wtn-admin.js', array('jquery'), $this->wtn_version, true );
	}
	
	/**
	*	Loading admin panel view/forms
	*/
	function wtn_settings() {

		require_once WTN_PATH . 'admin/view/' . $this->wtn_assets_prefix . 'settings.php';
	}
	
	function wtn_api_settings() {

		require_once WTN_PATH . 'admin/view/' . $this->wtn_assets_prefix . 'api-settings.php';
    }
    
    private function wtnGetNewsSources() {

		return array( 
			'abc-news' 				=> 'ABC News',
			'abc-news-au' 			=> 'ABC News (AU)',
			'al-jazeera-english' 	=> 'Al Jazeera English',
			'ary-news' 				=> 'Ary News',
			'bbc-news' 				=> 'BBC News',
			'bbc-sport' 			=> 'BBC Sport',
			'bloomberg' 			=> 'Bloomberg',
			'business-insider' 		=> 'Business Insider',
			'business-insider-uk'	=> 'Business Insider (UK)',
			'cbc-news' 				=> 'CBC News',
			'cbs-news' 				=> 'CBS News',
			'cnbc'					=> 'CNBC',
			'cnn' 					=> 'CNN',
			'cnn-es' 				=> 'CNN Spanish',
			'daily-mail'			=> 'Daily Mail',
			'der-tagesspiegel'		=> 'Der Tagesspiegel', //Germany
			'el-mundo'				=> 'El Mundo',
			'espn'					=> 'ESPN',
			'fox-news' 				=> 'Fox News',
			'google-news' 			=> 'Google News',
			'marca'					=> 'Marca',
			'mirror'				=> 'Mirror',
			'nbc-news' 				=> 'NBC News',
			'rt'					=> 'RT',
			'the-huffington-post' 	=> 'The Huffington Post',
			'the-new-york-times' 	=> 'The New York Times',
			'the-guardian-uk' 		=> 'The Guardian (UK)',
			'the-economist' 		=> 'The Economist',
			'the-washington-post' 	=> 'The Washington Post',
			'the-washington-times' 	=> 'The Washington Times',
			'the-hindu' 			=> 'The Hindu'
		);
    }

    private function wtn_set_api_data_to_cache( $s, $a ) {
		delete_transient( 'wtn_api_cached_data' );
		$url = "https://newsapi.org/v2/top-headlines?sources={$s}&apiKey={$a}";
		$api = wp_remote_get($url);
		$api_data = (array)json_decode(wp_remote_retrieve_body( $api ));
		set_transient( 'wtn_api_cached_data', $api_data, 16*60 );
		return true;
	}

	protected function wtn_display_notification( $type, $msg ) { 
		?>
		<div class="wtn-alert <?php esc_attr_e( $type ); ?>">
			<span class="wtn-closebtn">&times;</span> 
			<strong><?php esc_html_e( ucfirst( $type ), WTN_TXT_DMN ); ?>!</strong> <?php esc_html_e($msg, WTN_TXT_DMN); ?>
		</div>
		<?php 
	}
}
?>
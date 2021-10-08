<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
*	Front CLass
*/
class WTN_Front 
{	
	protected $wtn_vrsn;
	protected $wtn_api_cached_data, $wtn_api, $wtn_api_data;

	function __construct( $version ) {
		$this->wtn_vrsn = $version;
		$this->wtn_assets_prefix = substr(WTN_PRFX, 0, -1) . '-';
	}

	// Adding styles and js
    function wtn_front_assets() {

		wp_enqueue_style( 
			$this->wtn_assets_prefix .'front', 
			WTN_ASSETS . 'css/'. $this->wtn_assets_prefix .'front.css', 
			array(), 
			$this->wtn_vrsn 
		);
    }
	
	function wtn_load_shortcode(){

		add_shortcode( 'wp_top_news', array( $this, 'wtn_load_shortcode_view' ) );
	}
	
	public function wtn_load_shortcode_view( $wtnAttr ) {
		
		$output = '';
		ob_start();
		include WTN_PATH . 'front/view/' . $this->wtn_assets_prefix . 'front-view.php';
		$output .= ob_get_clean();
		return $output;
	}

	private function wtn_get_api_data( $source, $api ) {

		$this->wtn_api_cached_data = get_transient( 'wtn_api_cached_data' );
		delete_transient( 'wtn_api_cached_data' );
		if( (false === $this->wtn_api_cached_data) or (empty($this->wtn_api_cached_data)) ) { //echo "New";
			delete_transient( 'wtn_api_cached_data' );
			$urla = "https://newsapi.org/v2/top-headlines?sources={$source}&apiKey={$api}";
			$this->wtn_api = wp_remote_get($urla);
			$this->wtn_api_data = (array)json_decode(wp_remote_retrieve_body( $this->wtn_api ));
			set_transient( 'wtn_api_cached_data', $this->wtn_api_data, 16*60 );
			$this->wtn_api_cached_data = get_transient( 'wtn_api_cached_data' );
		}
		return (!empty($this->wtn_api_cached_data['articles'])) ? $this->wtn_api_cached_data['articles'] : die($this->wtn_api_cached_data['message']);
	}
}
?>
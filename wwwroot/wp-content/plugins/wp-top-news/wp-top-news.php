<?php
/**
 * Plugin Name: 	WP Top News
 * Plugin URI:		http://wordpress.org/plugins/wp-top-news/
 * Description: 	Best News Plugin for WordPress that will display world's 31 famous newspaper's Breaking News Headlines in your website.
 * Version: 		1.6
 * Author: 			Hossni Mubarak
 * Author URI: 		http://www.hossnimubarak.com
 * License:         GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'wtn_fs' ) ) {

    wtn_fs()->set_basename( false, __FILE__ );

} else {

    if ( ! class_exists('WTN_Master') ) {

        define( 'WTN_PATH', plugin_dir_path( __FILE__ ) );
        define( 'WTN_ASSETS', plugins_url( '/assets/', __FILE__ ) );
        define( 'WTN_LANG', plugins_url( '/languages/', __FILE__ ) );
        define( 'WTN_SLUG', plugin_basename( __FILE__ ) );
        define( 'WTN_PRFX', 'wtn_' );
        define( 'WTN_CLS_PRFX', 'cls-top-news-' );
        define( 'WTN_VRSN', '1.6' );
        define( 'WTN_TXT_DMN', 'wp-top-news' );

        require_once WTN_PATH . '/lib/freemius-integrator.php';
        require_once WTN_PATH . 'inc/' . WTN_CLS_PRFX . 'master.php';
        $wtn = new WTN_Master();
        $wtn->wtn_run();

        //  text widgets don’t process shortcodes
        add_filter('widget_text', 'do_shortcode');

        function wtn_fs_uninstall_cleanup() {
            
            global $wpdb;
            $wtn_tbl            = $wpdb->prefix . 'options';
            $wtn_search_string  = 'wtn_%';

            $wtn_sql            = $wpdb->prepare("SELECT option_name FROM $wtn_tbl WHERE option_name LIKE %s", $wtn_search_string);
            $wtn_options        = $wpdb->get_results( $wtn_sql, OBJECT );

            if ( is_array( $wtn_options ) && count( $wtn_options ) ) {
                foreach ( $wtn_options as $option ) {
                    delete_option( $option->option_name );
                    delete_site_option( $option->option_name );
                }
            }
        }
        wtn_fs()->add_action('after_uninstall', 'wtn_fs_uninstall_cleanup');
        
    }
}
?>
<?php
/*
 * Plugin Name: WPM News API
 * Plugin URI: https://wpmagic.cloud
 * Description: WP Plugin autoblog News API
 * Version: 2.0.9
 * Author: WPMagic
 * License: GPL2
 * @package wpm-news-api
 * Text Domain: wpm-news-api
 * Domain Path: /languages
 */

 /*
  WPM News API is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 2 of the License, or
  any later version.
  
  WPM News API is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
  GNU General Public License for more details.
  
  You should have received a copy of the GNU General Public License
  along with WPM News API. if not, write to the support@mail.wpmagic.cloud
*/

/*
 * blocking direct access to your plugin PHP files
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * define pluglin const
 */
require_once( 'wpm-news-api-settings.php' );

/*
 * make sure the language file(s) are loaded
 * use https://github.com/fxbenard/Blank-WordPress-Pot
 * Escaping strings: esc_html_e( 'Select Menu:', 'wpm-news-api' ); instead of _e( 'Select Menu:', 'wpm-news-api' );
 * ref: https://developer.wordpress.org/plugins/internationalization/how-to-internationalize-your-plugin/#translate-escape-functions
 * for the php vars in the string use ex. 
 * printf( esc_html__( 'We deleted %d spam messages.', 'my-text-domain' ), $count );
 * or
 * printf( esc_html__( 'Your city is %1$s, and your zip code is %2$s.', 'my-text-domain' ), $city, $zipcode );
 * Example of a link in a paragraph:
  * <?php
  * $url = 'http://example.com';
  * $link = sprintf( wp_kses( __( 'Check out this link to my <a href="%s">website</a> made with WordPress.', 'my-text-domain' ), array(  'a' => array( 'href' => array() ) ) ), esc_url( $url ) );
  * echo $link;
  * ?>
 */
load_plugin_textdomain(WPMNAPI_WPMAGIC_SLUG, false, WPMNAPI_WPMAGIC_PATH . '/languages' );

/*
 * Autoloads files when requested
 * 
 * @since  1.0.0
 * @param  string $class_name Name of the class being requested
 * use...as... make possible autoload with namespaces
 */
spl_autoload_register( 'wpmnapi_classes_autoloader' );

function wpmnapi_classes_autoloader ( $class_name ) {
  if ( false !== strpos( $class_name, 'Wpm_' ) ) {
    $classes_dir = realpath( WPMNAPI_WPMAGIC_PATH ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    $parts = explode('\\', $class_name);
    $class_file = strtolower( end($parts) ) . '.php';
    // If a file is found
    if ( file_exists( $classes_dir . $class_file ) ) {
        // Then load it up!
        require_once $classes_dir . $class_file;
    }
  }
}

use wpm_news_api\mainfunction as WpmClasses;
$wpm_News_Api_PluginClass = new WpmClasses\Wpm_News_Api_PluginClass($wpmTexts);
new WpmClasses\Wpm_News_Api_Class($wpm_News_Api_PluginClass, $wpmTexts);






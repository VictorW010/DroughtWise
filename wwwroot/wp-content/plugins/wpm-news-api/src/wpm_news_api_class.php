<?php
namespace wpm_news_api\mainfunction;

/*
 * blocking direct access to your plugin PHP files
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/*
 * main plugin Class
 */
if ( !class_exists( 'Wpm_News_Api_Class' ) ) {
  class Wpm_News_Api_Class {

    private $wpmTexts;

    /*
    * set plugin filters & actions
    */
    function __construct(wpm_News_Api_PluginClass $wpm_News_Api_PluginClass, $wpmTexts) {
      /*
      * add plugin action links (settings & find more)
      */
      add_filter( 'plugin_action_links_' . plugin_basename( WPMNAPI_WPMAGIC_BASEFILE ), array( $this, 'wpm_action_links' ) );
      /*
      * create Admin menu for plugin
      */
      add_action( 'admin_menu', array( $this, 'wpmnewsapi_menu_pages' ) );
      /**
       * Trigger the block registration on init.
       */
      add_action( 'admin_enqueue_scripts', array( $this, 'wpm_news_api_register_block' ) );
      /*
      * add Plugin Version Nr. as option
      */
      if( !get_option( WPMNAPI_WPMAGIC_NAME ) ) add_option(WPMNAPI_WPMAGIC_NAME, WPMNAPI_WPMAGIC_VERSION);
      /*
      * use global array defined in settings for Plugin Texts
      */ 
      $this->wpmTexts = $wpmTexts;
      /*
      * inject class Wpm_News_Api_PluginClass via the constructor
      */ 
      $this->wpm_News_Api_PluginClass = $wpm_News_Api_PluginClass;
      /*
      * clean the scheduler on deactivation
      */
      register_deactivation_hook( WPMNAPI_WPMAGIC_BASEFILE, array($this, 'wpm_deactivation') );
      /*
      * clean the options on uninstall
      * (uninstall.php not working on multisite bug)
      */
      register_uninstall_hook( WPMNAPI_WPMAGIC_BASEFILE, 'wpm_uninstall' );
      /**
       * plugin internationalization files
       */
      add_action( 'plugins_loaded', array( $this, 'wpm_internationalization' ) );
    }
    
    /*
    * add plugin action links in plugins list
    * @param array $links
    *
    * @return $links
    */
    public function wpm_action_links( $links ) {
      $links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=' . WPMNAPI_WPMAGIC_SETTINGS) ) .'">Settings</a>'; // ... options-general.php when linked to Settings Menu
      $links[] = '<a href="'. WPMNAPI_WPMAGIC_WEBSITE .'" target="_blank">'.__( $this->wpmTexts['txt_11'], "wpm-news-api" ).'</a>';
      return $links;
    }

    /*
    * add plugin link in settings menu
    * Not used. Used wpmnewsapi_menu_pages() instead to add a menu for the plugin direct into Admin Menu
    */
    public function wpmnewsapi_menu() {
      $links[] = '<a href="'. esc_url( get_admin_url(null, 'options-general.php?page=' . WPMNAPI_WPMAGIC_SETTINGS) ) .'">Settings</a>';
      add_options_page( WPMNAPI_WPMAGIC_NAME . ' Options', WPMNAPI_WPMAGIC_NAME, 'manage_options', WPMNAPI_WPMAGIC_SETTINGS, array($this, 'wpmnewsapi_options') );
    }

    /*
    * add plugin Menu in Admin
    */
    public function wpmnewsapi_menu_pages() {
      // Add the top-level admin menu
      $page_title = WPMNAPI_WPMAGIC_NAME . $this->wpmTexts['txt_12'];
      $menu_title = WPMNAPI_WPMAGIC_NAME;
      $capability = 'manage_options';
      $menu_slug = WPMNAPI_WPMAGIC_SETTINGS;
      $function = array($this, 'wpm_settings');
      $icon_url = WPMNAPI_WPMAGIC_URL . 'images/' . WPMNAPI_WPMAGIC_MENUICON;
      add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, WPMNAPI_WPMAGIC_MENUICON);

      // Add submenu page with same slug as parent to ensure no duplicates
      $sub_menu_title = __( $this->wpmTexts['submenu_1'], "wpm-news-api" );
      add_submenu_page($menu_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);

      // Now add the submenu page for Help
      $submenu_page_title = WPMNAPI_WPMAGIC_NAME . ' Help';
      $submenu_title = __( $this->wpmTexts['submenu_2'], "wpm-news-api" );
      $submenu_slug = WPMNAPI_WPMAGIC_HELP;
      $submenu_function = array($this, 'wpm_api_help');
      add_submenu_page($menu_slug, $submenu_page_title, $submenu_title, $capability, $submenu_slug, $submenu_function);
    }

    public function wpm_settings() {
        if (!current_user_can('manage_options')) {
            wp_die( $this->wpmTexts['error_1'] );
        }

        // Render the HTML for the Settings page or include a file that does
        $this->wpmnewsapi_options();
    }

    public function wpm_api_help() {
        if (!current_user_can('manage_options')) {
            wp_die( $this->wpmTexts['error_1'] );
        }

        // Render the HTML for the Help page or include a file that does
        $this->wpmnewsapi_help();
    }

    /*
    * Render the HTML for the plugin settings page
    */
    public function wpmnewsapi_options() {
      if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( $this->wpmTexts['error_1'] ) );
      }
      $this->wpm_News_Api_PluginClass->plugin_settings_page(WPMNAPI_WPMAGIC_NAME, WPMNAPI_WPMAGIC_BASEFILE);
    }

    /*
    * Render the HTML for the plugin help page
    */
    public function wpmnewsapi_help() {

      _e( '<h5><a href="https://news.docs.wpmagic.cloud" target="_blank" class="helpqmark">'.$this->wpmTexts['docs'].'</a></h5>', "wpm-news-api" );
      
      echo str_replace('alerthelp1', '', $this->wpmTexts['help_1']);

      // Display debuging Info. Possible to add when DEBUG is true ----> if( defined('WP_DEBUG') && WP_DEBUG === true )
      $lastError = get_option( WPMNAPI_WPMAGIC_LASTERROROPTIONNAME );
      if((is_array($lastError) && sizeof($lastError) > 0) || ! empty($lastError)) {
        _e( '<div class="alert alert-warning" role="alert">'. __($this->wpmTexts['help_2'], "wpm-news-api") .'</div>' . '<pre class="alert alert-danger" role="alert">'.print_r($lastError, true).'</pre>' );
      }
      
      // Force Publish fetched News if requested
      $getRequest = $_GET;
      if(isset($getRequest["fetchednews"]) && $getRequest["fetchednews"] == 'fpublish') {
        $_GET = array(
          'forcepublish'  => 'y'
        );
        $request = $this->wpm_News_Api_PluginClass->my_ajax_action_function();
        if( is_wp_error( $request ) ) {
          // Publish the error in Help Page
          $errorToPost = $this->wpmTexts['help_8'];
          $errorToPost .= "<hr />" . json_encode($request);
          update_option( WPMNAPI_WPMAGIC_LASTERROROPTIONNAME, __( $errorToPost, "wpm-news-api" ) );
          // redirect to the plugin help page in order to see the message
          $this->wpm_News_Api_PluginClass->refreshPage(0, "?page=".WPMNAPI_WPMAGIC_HELP);
        } else {
          /**
           * check if API some general error occured in fetch.php
           */
          $err = get_option( WPMNAPI_WPMAGIC_LASTERROROPTIONNAME );
          if( $err == '' ){
            // all clear
            _e( $this->wpmTexts['success_3'], "wpm-news-api" );
            // redirect to the plugin page
            $this->wpm_News_Api_PluginClass->refreshPage(0, "?page=".WPMNAPI_WPMAGIC_SETTINGS);
          } else {
            // redirect to the plugin help page in order to see the message
            $this->wpm_News_Api_PluginClass->refreshPage(0, "?page=".WPMNAPI_WPMAGIC_HELP);
            // empty error - not having a redirect loop
            update_option( WPMNAPI_WPMAGIC_LASTERROROPTIONNAME, '' );
          }
        }
      }

    }

    /**
     * Registers the plugin's scripts.
     *
     * @since 1.0.0
     */
    public function wpm_news_api_register_block() {
      wp_enqueue_script(
        WPMNAPI_WPMAGIC_SLUG,
        plugins_url( '../js/index.min.js', __FILE__ ) ,
        WPMNAPI_WPMAGIC_VERSION, 
        true
      );
      /**
       * Localize the plugin script texts
       * ref: https://pippinsplugins.com/use-wp_localize_script-it-is-awesome
       */
      wp_localize_script(
        WPMNAPI_WPMAGIC_SLUG,
        WPMNAPI_WPMAGIC_LOCALIZEJSOBJ, 
        array(
          'msg1'          => __( $this->wpmTexts['msg_1'], "wpm-news-api"),
          'errorMsg'      => __( $this->wpmTexts['error_2'], "wpm-news-api" ),
        )
      );
    }

    /**
     * clean scheduler on Plugin Deactivation
     */
    public function wpm_deactivation() {
      wp_clear_scheduled_hook( WPMNAPI_WPMAGIC_HOURLYEVENT );
      // delete_option( WPMNAPI_WPMAGIC_SOURCECATOPT );
      // delete_option( WPMNAPI_WPMAGIC_JSONDATA );
    }

    /**
     * load wpm_internationalization
     */
    public function wpm_internationalization() {
      load_plugin_textdomain('wpm-news-api', false, WPMNAPI_WPMAGIC_DIR . '/languages/');
    }

    /**
     * clear plugins options on activation
     * if previous installed (uninstall.php not working on multisite)
     */
    public function wpm_uninstall() {
      delete_option( WPMNAPI_WPMAGIC_SOURCECATOPT );
    }

  }
}
?>

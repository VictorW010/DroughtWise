<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

/*
 * get pluglin const
 */
require_once( 'wpm-news-api-settings.php' );

/*
 * Delete Plugin Options
 */
delete_option( WPMNAPI_WPMAGIC_SLUG );
delete_option( WPMNAPI_WPMAGIC_JSONDATA );
delete_option( WPMNAPI_WPMAGIC_LASTERROROPTIONNAME );
delete_option( WPMNAPI_WPMAGIC_APIOPTIONNAME );
delete_option( WPMNAPI_WPMAGIC_DEFAULTCATOPT );
delete_option( WPMNAPI_WPMAGIC_SOURCECATOPT );
delete_option( "WPM News API" );
delete_option( "external_updates-wpm-news-api" );

/*
 * for site options in Multisite
 */
// delete_site_option($option_name);

/*
 * drop a custom database table
 */
// global $wpdb;
// $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}mytable");

/**
 * delete cron job
 */
wp_unschedule_event(time(), WPMNAPI_WPMAGIC_HOURLYEVENT);
?>
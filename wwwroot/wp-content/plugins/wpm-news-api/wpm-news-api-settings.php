<?php
/*
 * Define Plugin Main File
 */
$pluginMainFile = plugin_dir_path(__FILE__) . 'wpm-news-api.php';

/*
 * Define global constants
 */
$plugin_data = get_file_data( $pluginMainFile, array( 'name'=>'Plugin Name', 'version'=>'Version', 'text'=>'Text Domain', 'uri'=>'Plugin URI' ) );

define( 'WPMNAPI_WPMAGIC_DIR', dirname( plugin_basename( __FILE__ ) ) );         // Plugin Dir
define( 'WPMNAPI_WPMAGIC_BASEFILE', $pluginMainFile );                           // Plugin Base File
define( 'WPMNAPI_WPMAGIC_URL', plugin_dir_url( __FILE__ ) );                     // Plugin URI
define( 'WPMNAPI_WPMAGIC_PATH', plugin_dir_path( __FILE__ ) );                   // Plugin Path
define( 'WPMNAPI_WPMAGIC_SLUG', dirname( plugin_basename( __FILE__ ) ) );        // Plugin slug name
define( 'WPMNAPI_WPMAGIC_NAME', $plugin_data['name'] );                          // Plugin Name
define( 'WPMNAPI_WPMAGIC_VERSION', $plugin_data['version'] );                    // Plugin Ver.
define( 'WPMNAPI_WPMAGIC_TEXT', $plugin_data['text'] );                          // Plugin Dscr.
define( 'WPMNAPI_WPMAGIC_PREFIX', 'wpmnapi' );                                   // Plugin prefix
define( 'WPMNAPI_WPMAGIC_SETTINGS', 'wpm-settings' );                            // Plugin Settings Page link
define( 'WPMNAPI_WPMAGIC_HELP', 'wpm-api-help' );                                // Plugin Help Page link
define( 'WPMNAPI_WPMAGIC_WEBSITE', $plugin_data['uri'] );                        // Plugin Webage link
define( 'WPMNAPI_WPMAGIC_LOCALIZEJSOBJ', 'wpm_news_api_vars' );                  // Plugin wp_localize_script var name
define( 'WPMNAPI_WPMAGIC_APIURL', 'https://newsapi.org' );                       // Plugin API URL
define( 'WPMNAPI_WPMAGIC_APIURLTXT', 'newsapi.org' );                            // Plugin API URL Text
define( 'WPMNAPI_WPMAGIC_JSONDATA', 'jsonData' );                                // Plugin jsonData Option name
define( 'WPMNAPI_WPMAGIC_HOURLYEVENT', 'publishNews_hourly_event' );             // Plugin my_hourly_event name
define( 'WPMNAPI_WPMAGIC_APIFETCHFILE', 'fetch.php' );                           // Name of the file that fetch API Data (called with Ajax & Cron)
define( 'WPMNAPI_WPMAGIC_APIOPTIONNAME', 'wpm_api_data' );                       // Name of the option to store the API Credentials
define( 'WPMNAPI_WPMAGIC_LASTERROROPTIONNAME', 'erroroption' );                  // Fetch the errors into option
define( 'WPMNAPI_WPMAGIC_MENUICON', 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgaGVpZ2h0PSIxOHB4IiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAyMCAxOCIgd2lkdGg9IjIwcHgiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6c2tldGNoPSJodHRwOi8vd3d3LmJvaGVtaWFuY29kaW5nLmNvbS9za2V0Y2gvbnMiIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIj48dGl0bGUvPjxkZXNjLz48ZGVmcy8+PGcgZmlsbD0ibm9uZSIgZmlsbC1ydWxlPSJldmVub2RkIiBpZD0iUGFnZS0xIiBzdHJva2U9Im5vbmUiIHN0cm9rZS13aWR0aD0iMSI+PGcgZmlsbD0iIzAwMDAwMCIgaWQ9Ikljb25zLUFWIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMTY4LjAwMDAwMCwgLTQ0LjAwMDAwMCkiPjxnIGlkPSJuZXdzIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgxNjguMDAwMDAwLCA0NC4wMDAwMDApIj48cGF0aCBkPSJNMTguMywxLjcgTDE2LjcsMCBMMTUsMS43IEwxMy4zLDAgTDExLjYsMS43IEwxMCwwIEw4LjMsMS43IEw2LjcsMCBMNSwxLjcgTDMuMywwIEwxLjcsMS43IEwwLDAgTDAsMTYgQzAsMTcuMSAwLjksMTggMiwxOCBMMTgsMTggQzE5LjEsMTggMjAsMTcuMSAyMCwxNiBMMjAsMCBMMTguMywxLjcgTDE4LjMsMS43IFogTTEwLDE2IEwyLDE2IEwyLDkgTDEwLDkgTDEwLDE2IEwxMCwxNiBaIE0xOCwxNiBMMTEsMTYgTDExLDE1IEwxOCwxNSBMMTgsMTYgTDE4LDE2IFogTTE4LDE0IEwxMSwxNCBMMTEsMTMgTDE4LDEzIEwxOCwxNCBMMTgsMTQgWiBNMTgsMTIgTDExLDEyIEwxMSwxMSBMMTgsMTEgTDE4LDEyIEwxOCwxMiBaIE0xOCwxMCBMMTEsMTAgTDExLDkgTDE4LDkgTDE4LDEwIEwxOCwxMCBaIE0xOCw4IEwyLDggTDIsNSBMMTgsNSBMMTgsOCBMMTgsOCBaIiBpZD0iU2hhcGUiLz48L2c+PC9nPjwvZz48L3N2Zz4=' );        // Plugin Icon
define( 'WPMNAPI_WPMAGIC_SOURCECATOPT', 'wpm_sourcetopostcat' );                 // Name of the option to store the Source->Cat Pairing (article source -> post category)
define( 'WPMNAPI_WPMAGIC_DEFAULTCATID', 'uncategorized' );                       // cat_ID of the default Post Category to post in
define( 'WPMNAPI_WPMAGIC_DEFAULTCATOPT', 'wpm_defaultcat' );                     // Name of the option to store the Default Post Category ID to publish the News
define( 'WPMNAPI_WPMAGIC_TMPFOLDER', 'wpm_tmp' );                                // Folder name where images are saved
define( 'WPMNAPI_WPMAGIC_DUMMYIMG', 'https://www.wpmagic.cloud/wp-content/uploads/2020/03/news-250x250-1.jpg' ); // Dummy Featured Image
define( 'WPMNAPI_WPMAGIC_LITE_LIMIT', 5 );                                       // Plugin Lite (Free) version categorys limit

/*
 * Define Folder Path & Url where temporary images are saved
 */
$uploadWPDir    = wp_upload_dir();
$uploadDir      = $uploadWPDir['basedir'] . DIRECTORY_SEPARATOR . WPMNAPI_WPMAGIC_TMPFOLDER;
$uploadDirUrl   = $uploadWPDir['baseurl'] . DIRECTORY_SEPARATOR . WPMNAPI_WPMAGIC_TMPFOLDER;

define( 'WPMNAPI_WPMAGIC_IMGTMPFOLDER', $uploadDir );                            // Folder path where images are saved
define( 'WPMNAPI_WPMAGIC_IMGTMPFOLDERURL', $uploadDirUrl );                      // Folder Url path where images are saved

/*
 * Define Plugin Texts
 */
$wpmTexts = array(
    "help_1"        => sprintf(  
                        "
                        <div class='alert alert-info alert-dismissible fade show alerthelp1' role='alert'>
                            <details>
                                <summary>Plug-in Setup</summary>
                                <button type='button' class='close wpminfo1' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                <p class='mt-3 ml-2'>" . 
                                    __( "WPMagic Automated News plugin will run on its own after a quick setup.", "wpm-news-api" ) . "<br />" . 
                                    __( "Folow the instructions bellow and fill the required data. After that, sit back and enjoy a fully automated News website.", "wpm-news-api" ) . 
                                "</p>
                                <p class='mt-3 ml-2'>" . 
                                    __( "First add your API Key. Go ahead and hit the \"Fetch latest News\" button in order to fetch some News (see that is working).", "wpm-news-api" ) . "<br />" . 
                                    __( "After that configure the article source as post category in <u>two steps</u>. On the 'Configure Article Source as Post Category' menu: <ul style='font-size: 13px; padding-left: 20px;'><li>1) Choose the default \"News\" Category.</li><li>2) Choose categories to publish for each news source (News to Post category Pairing).</li></ul><p>New sources will be available periodically, as they appear with the fetched News, so make sure you check the new news sources (News to Post category Pairing) periodicaly. Click on the \"Save changes\" button.", "wpm-news-api" ) . "<br />" . 
                                    __( "Press the \"Publish all\" button in order to publish the news. After that the news will be published automatically when the <a href='https://www.google.com/search?q=WP-Cron+Scheduling' target='_blank'>WP-Cron</a> run.", "wpm-news-api" ) . "<br />" . 
                                    __( "That's it. New content from your chosen API Url will be fetched and posted on the website every hour.", "wpm-news-api" ) . 
                                "</p>
                            </details>
                        </div>"
                    ),
    "help_2"        => sprintf(
                        __( "Debugging Info:", "wpm-news-api" )
                    ),
    "help_3"        => sprintf( 
                            '<div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                                __( "The sources have been saved. You can now edit them into \"Configure Article Source as Post Category\" Menu.", "wpm-news-api") . 
                            '</div>'
                        ),
    "help_4"        =>  sprintf(
                            '<div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' .
                                __( "The default \"Post Category\" to publish the News have been saved. You can change it into \"Configure Article Source as Post Category\" Menu.", "wpm-news-api") . 
                            '</div>'
                        ),
    "help_5"        => sprintf( 
                            __( 'The News have not changed since last fetch. Please wait.', "wpm-news-api") . 
                            '<p>' . 
                            __( 'Force publish fetched News?', "wpm-news-api") . 
                            '<a href="%s"> ' . __( 'Yes. Publish them anyway.', "wpm-news-api") . '</a>
                            </p>', 
                            '?page='.WPMNAPI_WPMAGIC_HELP.'&fetchednews=fpublish' 
                        ),
    "help_6"        => sprintf( 
                            __( 'post thumbnail success', "wpm-news-api")
                        ),
    "help_7"        => sprintf( 
                            __( 'no file extension', "wpm-news-api")
                        ),
    "help_8"        => sprintf( 
                            __( 'Error. The News can\'t be published using Force Publish method. Please wait new content from the API to be fetched on Cron\'s run.', "wpm-news-api")
                        ),
    "txt_1"         => sprintf( 
                            __( "View/Edit News API Key", "wpm-news-api")
                        ),
    "txt_2"         => sprintf( 
                            __( "Configure Article Source as Post Category", "wpm-news-api")
                        ),
    "txt_5"         => sprintf( 
                            __( 'News API Key:', "wpm-news-api")
                        ),
    "txt_6"         => sprintf( 
                            __( 'API URL:', "wpm-news-api")
                        ),
    "txt_7"         => sprintf( 
                            __( 'Save', "wpm-news-api")
                        ),
    "txt_8"         => sprintf( 
                            __( 'Fetch latest news', "wpm-news-api")
                        ),
    "txt_9"         => sprintf( 
                            __( 'Loading...', "wpm-news-api")
                        ),
    "txt_10"        => sprintf( 
                            __( 'Click on the link above to get the latest news from choosed API URL.', "wpm-news-api")
                        ),
    "txt_11"        => sprintf( 
                            __( 'More plugins by', "wpm-news-api") . ' WPMagic'
                        ),
    "txt_12"        => sprintf( 
                            __( ' Settings', "wpm-news-api" )
                        ),
    "txt_13"        => sprintf( 
                            __( 'Source:', "wpm-news-api" )
                        ),
    "txt_14"        => sprintf( 
                            __( 'Target categories:', "wpm-news-api" )
                        ),
    "txt_15"        => sprintf( 
                            __( 'Define News Source => Post Category.', "wpm-news-api" ) . 
                            '<br />' . __( 'This will help organise the Site. Each News from a particular source (ex. news.com.au) will be published into a specific category (ex. "News" or "Latest").', "wpm-news-api" ) .
                            '<br />' . __( 'You can see the source for each article bellow, in the list of fetched articles.', "wpm-news-api" )
                    ),
    "txt_16"        => sprintf( 
                            __( 'New * ', "wpm-news-api" )
                        ),
    "txt_17"        => sprintf( 
                            __( 'Default Post category for News:', "wpm-news-api" )
                        ),
    "txt_18"        => sprintf( 
                            '<h5>' . __( 'News to Post category Pairing:', "wpm-news-api" ) . '</h5>'
                        ),
    "txt_19"        => sprintf( 
                            '<h5>' . __( 'Choose the default Post category for the News:', "wpm-news-api" ) . '</h5>'
                        ),
    "txt_22"        => sprintf( 
                            __( 'License Key', "wpm-news-api" )
                        ),
    "txt_23"        => sprintf( 
                            __( 'You\'re using WPM News API Lite - no license needed. Enjoy!', "wpm-news-api" )
                        ),
    "txt_24"        => sprintf( 
                            __( '<h3>;)</h3>', "wpm-news-api" )
                        ),
    "txt_25"        => sprintf( 
                            wp_kses( 
                                __( 'Use the '.WPMNAPI_WPMAGIC_NAME.' with any News Specific WP Theme. Please note that the '.WPMNAPI_WPMAGIC_NAME.' it\'s not optimized for all the <a href="%s" target="_blank">%s</a> and it will not work as aspected with some of them.', "wpm-news-api" ), 
                                array(  
                                    'a' => array(
                                            'href' => array(),
                                            'target' => array('_blank', '_top'),
                                            ),
                                )
                            ), 
                            esc_url( 'https://wordpress.org/themes/search/news/' ),
                            'WP News Themes'
                        ),
    "txt_26"        => sprintf( 
                            wp_kses( 
                                __( 'Create an account at <a href="%s" target="_blank">%s</a> and fill the required data bellow.', "wpm-news-api" ), 
                                array(  
                                    'a' => array(
                                            'href' => array(),
                                            'target' => array('_blank', '_top'),
                                            ),
                                )
                            ), 
                            esc_url( WPMNAPI_WPMAGIC_APIURL ),
                            WPMNAPI_WPMAGIC_APIURLTXT
                        ),

    "header"        => "<h5 class='mt-3 ml-2'>".WPMNAPI_WPMAGIC_NAME."</h5>",
    // "help"          => sprintf( 
    //                         __( "<h5 class='mt-3 ml-2'>".WPMNAPI_WPMAGIC_NAME." Help</h5>", "wpm-news-api" )
    //                     ),
    "submenu_1"     => sprintf( 
                            __( "Settings", "wpm-news-api" )
                        ),
    "submenu_2"     => sprintf( 
                            __( "Help", "wpm-news-api" )
                        ),
    "error_1"       => sprintf( 
                            __( 'You do not have sufficient permissions to access this page.', "wpm-news-api" )
                        ),
    "error_2"       => sprintf( 
                            __( 'An error occurred', "wpm-news-api" )
                        ),
    "error_3"       => sprintf( 
                            '<div class="alert alert-danger" role="alert">' . __( 'Error! API credentials were not saved.', "wpm-news-api") . '</div>'
                        ),
    "msg_1"         => 'Hello from '.WPMNAPI_WPMAGIC_NAME.' JS.',
    "tooltip_1"     => sprintf( 
                            __( 'API key from %s/account', "wpm-news-api" ), WPMNAPI_WPMAGIC_APIURL
                        ),
    "tooltip_2"     => sprintf( 
                            __( 'ex. Top headlines from Australia: %s/v2/top-headlines?country=au', "wpm-news-api" ), WPMNAPI_WPMAGIC_APIURL
                        ),
    "success_1"     => sprintf( 
                            '<div class="alert alert-success" role="alert">' . __( 'API credentials have been saved.', "wpm-news-api") . '</div>'
                        ),
    "success_2"     => sprintf( 
                            '<div class="alert alert-success" role="alert">' . __( 'API crededatantials have been updated.', "wpm-news-api" ) . '</div>'
                        ),
    "success_3"     => sprintf( 
                            '<div class="alert alert-success" role="alert">' . __( 'The News have been published.', "wpm-news-api" ) . '</div>'
                        ),
    "success_4"     => sprintf( 
                            '<div class="alert alert-success" role="alert">' . __( 'License Key have been saved. If your licence is valid, the automatic updates will be available for this plugin.', "wpm-news-api" ) . '</div>'
                        ),
    "button_1"      => sprintf( 
                            __( 'Save changes', "wpm-news-api" )
                        ),
    "docs"          => sprintf( 
                            __( 'Questions<i class="far fa-question-circle fa-lg" aria-hidden="true"></i> Please read the documentation.', "wpm-news-api" )
                        ),
    "docsqmarkt"    => sprintf( 
                            __( '<i class="far fa-question-circle fa-lg" aria-hidden="true"></i>', "wpm-news-api" )
                        ),
    "litevinfo"     => sprintf( 
                            __( '<section class="litevinfo">
                                    <p><i class="fa fa-info-circle" aria-hidden="true"></i> You have reached the limit for the News to Post category Pairing.</p>
                                    <p>Download the <a href="https://www.wpmagic.cloud/product/wpm-news-api-premium-wordpress-plugin/?pk_campaign=wordpress.org&pk_source=wpmnewslite" target="_blanck">WPM News API Plugin Premium</a> and unlock <i class="fa fa-unlock-alt" aria-hidden="true"></i> all News sources.</p>
                                </section>',
                                "wpm-news-api" )
                        ),
    "locksign"      => sprintf( 
                            __( '<i class="fa fa-lock locked" aria-hidden="true"></i>', "wpm-news-api" )
                        ),
);

define( 'WPMNAPI_WPMAGIC_WPMTEXTS', $wpmTexts );                                 // Place text to global var
?>
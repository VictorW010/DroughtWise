<?php
namespace wpm_news_api\mainfunction;

/*
 * blocking direct access to your plugin PHP files
 */
if ( ! defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'Wpm_News_Api_PluginClass' ) ) {
    class Wpm_News_Api_PluginClass {

        private $wpmTexts;

        /*
        * set plugin filters & actions
        */
        function __construct($wpmTexts) {
            /**
            * schedule an hourly event in a plugin on activation (otherwise you will end up with a lot of scheduled events!)
            */
            register_activation_hook(WPMNAPI_WPMAGIC_BASEFILE, array($this, 'publishNews'));
            /**
             * Add hourly event.
             */
            add_action(WPMNAPI_WPMAGIC_HOURLYEVENT, array($this, 'publishNews_hourly'));
            /**
             * Trigger the block registration on init.
             */
            add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_libraries' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'load_front_libraries' ) );
            /**
             * Add ajax action.
             */
            add_action('wp_ajax_my_action', array( $this, 'my_ajax_action_function' ));
            /*
            * use global array defined in settings for Plugin Texts
            */ 
            $this->wpmTexts = $wpmTexts;
            /**
             * 
             */
            add_filter( 'wp_get_attachment_image_attributes', array( $this, 'set_thumbnail_ext_url' ), 10, 3 );
            /**
             * 
             */
            add_filter( 'post_thumbnail_html', array( $this, 'wpb_autolink_featured_images'), 10, 3 );
            
            /**
             * add _thumbnail_ext_url Info in "meta" when using WP REST API
             */
            register_rest_field( 'post', 'meta', array(
                'get_callback' => function ( $data ) {
                    return get_post_meta( $data['id'], '', '' );
                }, 
            ));
        }

        /*
        * load Font Awesome to plugin Admin Page
        * load custom CSS
        * load Bootstrap CSS & JS
        */
        public function load_custom_libraries() {
            // load the scripts on only the plugin admin page 
            if (isset($_GET['page']) && ($_GET['page'] == WPMNAPI_WPMAGIC_SETTINGS || $_GET['page'] == WPMNAPI_WPMAGIC_HELP)) {
                wp_enqueue_style( 'bootstrap'   , WPMNAPI_WPMAGIC_URL . 'css/bootstrap.min.css'  );
                wp_enqueue_style( 'custom-fa'   , WPMNAPI_WPMAGIC_URL . 'css/fontawesome/all.min.css' );
                wp_enqueue_style(
                    WPMNAPI_WPMAGIC_SLUG,
                    plugins_url( '../css/style.min.css', __FILE__ ) ,
                    WPMNAPI_WPMAGIC_VERSION, 
                    true
                );
                wp_enqueue_script( 'bootstrapjs'  , WPMNAPI_WPMAGIC_URL . 'js/bootstrap.min.js', $deps = array(), $ver = false, $in_footer = true );
            }
        }
        
        /*
        * add plugin settings page
        */
        public function plugin_settings_page(string $pluginMenuName = NULL, string $WPMNAPI_WPMAGIC_BASEFILE = NULL) {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $getPluginOptions = $this->getPluginOptions();
                
                /**
                 * quescion mark - help sign
                 */
                _e( '<a href="https://news.docs.wpmagic.cloud" target="_blank" class="qmark">'.$this->wpmTexts['docsqmarkt'].'</a>', "wpm-news-api" );
                
                /*
                * set the header of the Page
                */
                _e( $this->wpmTexts['header'], "wpm-news-api" );

                
                echo $this->wpmTexts['help_1'];

                /*
                * set the collapsed links in top of page
                */
                if($this->checkIfOptions()){
                    echo '
                    <p class="mt-3 bg-light">
                        <a class="btn btn-link dropdown-toggle" data-toggle="collapse" href="#collapseLicenseKey" role="button" aria-expanded="false" aria-controls="collapseLicenseKey">
                            '; esc_html_e( $this->wpmTexts['txt_22'], "wpm-news-api" ); echo '
                        </a> | 
                        <a class="btn btn-link dropdown-toggle" data-toggle="collapse" href="#collapseKey" role="button" aria-expanded="false" aria-controls="collapseKey">
                            '; esc_html_e( $this->wpmTexts['txt_1'], "wpm-news-api" ); echo '
                        </a> |
                        <a class="btn btn-link dropdown-toggle" data-toggle="collapse" href="#collapseCat" role="button" aria-expanded="false" aria-controls="collapseCat">
                            '; esc_html_e( $this->wpmTexts['txt_2'], "wpm-news-api" ); echo '
                        </a>
                    </p>';
                }

                /*
                * first link - collapsed section
                * API Options Form
                */
                echo '
                <div class="'; if($this->checkIfOptions()) echo 'collapse'; else echo 'noCollapse'; echo '" id="collapseKey">
                    <div class="card mw-100 p-3">
                        <div class="card-header">
                            <div class="wrap">';
                                $link = $this->wpmTexts['txt_26'];
                                echo $link;
                                echo '
                            </div>
                            <div class="wrap">';
                                $link = $this->wpmTexts['txt_25'];
                                echo $link;
                                echo '
                            </div>
                        </div>    
                        <div class="card-body">
                            <form action="" method="post" id="wpm-news-api-form">
                                <label for="apikey">
                                    <span>'; esc_html_e( $this->wpmTexts['txt_5'], "wpm-news-api" ); echo '</span>
                                    <input type="text" name="apikey" value="'; if(is_object($getPluginOptions)) echo $getPluginOptions->params->apikey; echo '" />
                                    <i class="far fa-question-circle fa-lg tooltip" title="'.__( $this->wpmTexts['tooltip_1'], "wpm-news-api" ).'"></i>
                                </label>
                                <label for="apiurl">
                                    <span>'; esc_html_e( $this->wpmTexts['txt_6'], "wpm-news-api" ); echo '</span>
                                    <input type="text" name="apiurl" value="'; if(is_object($getPluginOptions)) echo $getPluginOptions->params->apiurl; echo '" />
                                    <i class="far fa-question-circle fa-lg tooltip" title="'.__( $this->wpmTexts['tooltip_2'], "wpm-news-api" ).'"></i>
                                </label>
                                <label for="save">
                                    <span></span>
                                    <input type="submit" value="'; esc_html_e( $this->wpmTexts['txt_7'], "wpm-news-api" ); echo '" />
                                    <i class="far fa-save fa-lg nolink"></i>
                                </label>
                            </form>
                        </div>
                    </div>
                </div>
                ';

                /*
                * Create Post Categories area.
                * Set the pairing Article Source -> Post CAtegory 
                * (ex. from Techtimes.com -> will post news under Technology/Tech Category on WP).
                * Categories need to exist on the Category Menu on Admin Page 
                */

                /*
                * Get Post Categories from WP
                */    
                $args = array(
                    'taxonomy'           => 'category',
                    'child_of'           => 0,
                    'hide_empty'         => 0,
                    'orderby'            => 'name',
                    'order'              => 'ASC',
                    'show_count'         => 0,
                    'use_desc_for_title' => 0,
                    'title_li'           => 0,
                    'style'              => '',
                    'echo'               => false,
                );
                $categories = get_categories($args);
                
                /*
                * Get the News Sources from News saved in option,
                * in order to define Sources => Categories
                */ 
                $fetchedNews = json_decode(get_option( WPMNAPI_WPMAGIC_JSONDATA), true);

                /*
                * √ Set the default Post Category for News & 
                * √ Populate Form with Sorces & Categories
                */ 
                echo '
                <div class="'; if($this->checkIfOptions()) echo 'collapse'; else echo 'noCollapse'; echo '" id="collapseCat">
                    <div class="card mw-100 p-3">
                        <div class="card-header">
                            <div class="wrap">';
                                _e( $this->wpmTexts['txt_15'], "wpm-news-api" );
                                echo '
                            </div>
                        </div>
                        <div class="card-body">';
                            
                            /*
                            * Set the default Post Category for News
                            */
                            _e( $this->wpmTexts['txt_19'], "wpm-news-api" );
                            // get saved default cat
                            $defaultCat = $this -> getDefaultCat();
                            // populate default cat form
                            echo '
                            <form action="" method="post" id="wpm-default-cat-form">';
                                echo '
                                <label for="savdefaultpostcate">
                                    <span>'.__( $this->wpmTexts['txt_17'], "wpm-news-api" ).'</span>    
                                    <select name="defaultpostcat">';
                                        if(!empty($categories)) foreach($categories as $postcat){
                                            echo '<option value="'.$postcat->cat_ID.'"'; if($postcat->cat_ID == $defaultCat)  echo ' selected'; echo '>'.$postcat->name.'</option>';
                                        }
                                        echo '
                                    </select>
                                </label>  
                                <input type="submit" class="btn btn-info btnsubmit" value="'.__( $this->wpmTexts['button_1'], "wpm-news-api" ).'" />
                                <input type="hidden" name="frmname" value="wpm-default-cat-form" />
                            </form>
                            <hr />';

                            /*
                            * Populate Form with Sorces & Categories
                            */
                            _e( $this->wpmTexts['txt_18'], "wpm-news-api" );
                            echo '
                            <form action="" method="post" id="wpm-source-cat-form">';
                                $counter = 1;    
                                /*
                                * show existing sources pairing
                                * get the existing source->postcat pairing
                                * populate the Form with existing values
                                */   
                                $getSourceToCat = get_option( WPMNAPI_WPMAGIC_SOURCECATOPT );
                                /*
                                * restore the array from $getSourceToCat
                                * "true" will make an array
                                */
                                if(!empty($getSourceToCat)) {
                                    $sourceToCat = $getSourceToCat;
                                } else $sourceToCat = array();
                                /*
                                * populate the Form with existing values
                                */
                                if( ! empty($sourceToCat) ) foreach($sourceToCat as $key => $existingSource) {
                                    $this->populateSourcesForm(array('source'=> array('name' => $key), 'existing' => $existingSource), $categories, $counter, $this->wpmTexts['txt_13'], $this->wpmTexts['txt_14']);
                                    $counter++;
                                }
                                /*
                                * show the new sources
                                * compare the source
                                * in order to display only unique sources
                                * populate the Form with new sources (non existing)
                                */
                                $savedSource = array();
                                if(! empty($fetchedNews["articles"])) foreach($fetchedNews["articles"] as $news){
                                    if(isset($news["source"]["name"])){
                                        /*
                                        * compare the source already listed
                                        * compare with the sources already saved
                                        * in order to display only unique sources
                                        */
                                        if( !array_search($news["source"]["name"], $savedSource) & !array_key_exists($news["source"]["name"], $sourceToCat) ){
                                            $this->populateSourcesForm($news, $categories, $counter, $this->wpmTexts['txt_13'], $this->wpmTexts['txt_14'], $news);
                                            /*
                                            * save the source for comparing with the next
                                            * in order to display only unique sources
                                            */
                                            $savedSource[] = $news["source"]["name"];
                                            $counter++;
                                        }
                                    }
                                }
                                echo '
                                <input type="submit" class="btn btn-info btnsubmit" value="'.__( $this->wpmTexts['button_1'], "wpm-news-api" ).'" />
                                <input type="hidden" name="frmname" value="wpm-source-cat-form" />
                            </form>
                        </div>
                    </div>
                </div>
                ';

                /*
                * 3th link - collapsed section
                * License Key Form
                */
                $license_key = get_option('wpm-news-api-license-key');
                echo '
                <div class="'; if($this->checkIfOptions()) echo 'collapse'; else echo 'noCollapse'; echo '" id="collapseLicenseKey">
                    <div class="card mw-100 p-3">
                        <div class="card-header">';
                            _e( $this->wpmTexts['txt_23'], "wpm-news-api" );
                            echo '
                        </div>    
                        <div class="card-body">';
                            _e( $this->wpmTexts['txt_24'], "wpm-news-api" );
                            echo '
                        </div>
                    </div>
                </div>
                ';

                /*
                * if API Credential exist
                * fetch new content
                * display existing fetched content
                */
                if($this->checkIfOptions()) $this -> plugin_actions();
            } else {
                /*
                * check if a Form was submitted
                * check what Form was submitted and take action accordingly
                */
                /*
                * check if APIKey was submited and save the API Data
                */
                if(isset($_POST["apikey"]) & isset($_POST["apiurl"])) $this -> save_plugin_data($_POST);
                /*
                * check if Source -> Category Form was submitted
                */
                if(isset($_POST["frmname"]) && $_POST["frmname"] == 'wpm-source-cat-form') $this -> save_sorce_cat_pairing($_POST);
                /*
                * check if Default Category Form was submitted
                */
                if(isset($_POST["frmname"]) && $_POST["frmname"] == 'wpm-default-cat-form') $this -> save_sorce_defaultcat($_POST);
                /*
                * save License Key into option in order to use it in wsh_filter_update_checks
                */
                if(isset($_POST["license-key"])) {
                    $getLicence = $this -> sanitize_text_or_array_field($_POST);
                    $response = update_option('wpm-news-api-license-key', $getLicence);
                    $response_site = update_option( 'wpm-news-api-license-key', $getLicence );
                    if (!is_wp_error($response) & !is_wp_error($response_site)) {
                        _e( $this->wpmTexts['success_4'], "wpm-news-api" );
                        $this->refreshPage();
                    }
                }                
            }
        }

        /*
        * get plugin options
        */
        public function getPluginOptions() {
            $getPluginOptions = get_option( WPMNAPI_WPMAGIC_APIOPTIONNAME );

            return $getPluginOptions;
        }

        /*
        * add plugin settings page
        */
        public function save_plugin_data($data = array()) { 
            $myOptions = new \stdClass();
            $myOptions->name = WPMNAPI_WPMAGIC_APIOPTIONNAME;
            $myOptions->params = (object) $this -> sanitize_text_or_array_field($data);
            /*
            * check if plugin option already exists
            */
            $getPluginOptions = get_option( WPMNAPI_WPMAGIC_APIOPTIONNAME );
            if(is_object($getPluginOptions)) {
                /*
                * Options exist already so update them
                */
                update_option(WPMNAPI_WPMAGIC_APIOPTIONNAME, $myOptions);
                _e( $this->wpmTexts['success_2'], "wpm-news-api" );
                $this->refreshPage();
            } else {
                /*
                * Options don't exist so go ahead and create new ones
                */
                $tst = add_option(WPMNAPI_WPMAGIC_APIOPTIONNAME, $myOptions);
                if($tst) {
                    _e( $this->wpmTexts['success_1'], "wpm-news-api" );
                    $this->refreshPage();
                } else {
                    _e( $this->wpmTexts['error_3'], "wpm-news-api" );
                }
            }
        }

        /*
        * refresh page
        */
        function refreshPage($sec = 1, $url = '') {
            echo("<meta http-equiv='refresh' content='".$sec.";url=".$url."'>"); //Refresh/ Redirect by HTTP META
        }

        /*
        * add plugin actions page section
        * plugin settings page - mews section
        */
        public function plugin_actions() {
            echo '<hr />';
            echo '<button type="button" class="btn btn-light fetch">'; esc_html_e( $this->wpmTexts['txt_8'], "wpm-news-api" ); echo '</button>';
            $pluginurl = plugins_url( WPMNAPI_WPMAGIC_APIFETCHFILE, __FILE__ );
            $getPluginOptions = $this->getPluginOptions();
            
            /**
             * set vars for js ajax fetch in index.js
             */
            echo '<div class="fetchloadingtxt">'; esc_html_e( $this->wpmTexts['txt_9'], "wpm-news-api" ); echo '</div>';
            echo '<div class="apikey">'; if(isset($getPluginOptions) && is_object($getPluginOptions)) echo $getPluginOptions->params->apikey; echo '</div>';
            echo '<div class="apiurl">'; if(isset($getPluginOptions) && is_object($getPluginOptions)) echo $getPluginOptions->params->apiurl; echo '</div>';
            echo '<div class="helpurltxt">'; echo WPMNAPI_WPMAGIC_HELP; echo '</div>';
            echo '<div id="div1" class="p-3">'; esc_html_e( $this->wpmTexts['txt_10'], "wpm-news-api" ); echo '</div>';
            echo '<hr />';
            /*
            * display the news already in cache
            */ 
            $this->displayDataOnScreen();
        }

        /*
        * check if options saved already
        */
        public function checkIfOptions() {
            $getPluginOptions = get_option( WPMNAPI_WPMAGIC_APIOPTIONNAME );

            if(is_object($getPluginOptions) && $getPluginOptions->params->apikey != '') return true;
            else return false;
        }

        /*
        * display data on screen
        */
        private function displayDataOnScreen () {
            /*
            * get jsonData already saved into Ajax call - fetch.php when 'Fetch latest news' from plugin_actions() clicked
            */ 
            $getCachedJsonData = get_option( WPMNAPI_WPMAGIC_JSONDATA );
            $data = json_decode($getCachedJsonData);
            echo '
            <div class="">';
                if(!empty($getCachedJsonData)) {
                    echo '
                    <div class="row">';
                        echo sprintf( '<a href="%s" class="btn btn-light mr-3 ml-3">' . __( 'Publish all', "wpm-news-api") . '</a>', '?page='.WPMNAPI_WPMAGIC_HELP.'&fetchednews=fpublish' );
                        echo '<h6>' . sprintf( __( "Latest fetched News (%s):", "wpm-news-api" ), count($data->articles)) . '</h6>';
                        echo '
                    </div>';
                    foreach( $data->articles as $articles ) {
                        if( isset($articles->urlToImage) && strpos($articles->urlToImage, 'http') !== false ) {
                            echo '
                            <div class="card m-2 float-left" style="width: 18rem;">
                                <a href="' . esc_url( $articles->url ) . '" target="_blank">
                                    <img class="card-img-top" src="' . esc_url( $articles->urlToImage ) . '" alt="">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title">'; echo $this->editTxt( $articles->title, 7 ); echo '</h5>
                                    <p class="card-text">'; echo $this->editTxt( $articles->description, 18 ); echo '</p>
                                    <p class="card-info">'; esc_html_e( $this->wpmTexts['txt_13'], "wpm-news-api" ); echo ' ' . $articles->source->name; echo '</p>
                                    <a href="' . esc_url( $articles->url ) . '" target="_blank" class="btn btn-primary">'; esc_html_e( 'Read more', "wpm-news-api" ); echo '</a>
                                </div>
                            </div>';
                        }
                    }
                }
                echo '
                <div class="clear"></div>
            </div>';
        }

        /*
        * edit/trim text
        */
        private function editTxt($txt, $txtCut) {
            $strx = $this->trimWords(strip_tags($txt), $txtCut);
            $strx = $strx . '...';

            return $strx;
        }

        /*
        * trim words
        */
        private function trimWords($s, $limit=3) {
            return preg_replace('/((\w+\W*){'.($limit-1).'}(\w+))(.*)/', '${1}', $s);   
        }

        /*
        * schedule to fetch & publish new articles every hour
        */
        public function publishNews() {
            //if (! wp_next_scheduled ( WPMNAPI_WPMAGIC_HOURLYEVENT )) {
                wp_schedule_event(time(), 'hourly', WPMNAPI_WPMAGIC_HOURLYEVENT);
            //}
        }

        /*
        * schedule to fetch & publish new articles every hour
        * when code needs to run on each blog upon Network Activation
        */
        public function publishNews_hourly($network_wide = false) {
            /**
             * clear previous error/ messages
             */
            update_option( WPMNAPI_WPMAGIC_LASTERROROPTIONNAME, "" );
            /**
             * check if multisite or not
             */
            $this -> publishNews_hourly_action();
        }

        public function publishNews_hourly_action() {
            /**
             * switch the blog if multisite
             */
            if(is_multisite()) {
                $blog_id = get_current_blog_id();
                switch_to_blog($blog_id);
            }
            
            // do something every hour
            $getPluginOptions = $this->getPluginOptions();
            
            /*
            * fetch the news only if APIKey & APIUrl defined
            */
            if(isset($getPluginOptions) && is_object($getPluginOptions)) {
                /*
                * get the page that fetch the API
                */
                $_GET = array(
                    'apikey'    => $getPluginOptions->params->apikey,
                    'apiurl'    => $getPluginOptions->params->apiurl,
                    'eschtmle'  => 'wpm-news-api'
                );
                $response = $this -> my_ajax_action_function();
                
                /*
                * handle Errors if any
                */
                if (is_wp_error($response)) {
                    $error = $response->get_error_message();
                    // Fetch the errors into an option
                    update_option(WPMNAPI_WPMAGIC_LASTERROROPTIONNAME, $error);
                } else {
                    /*
                    * do nothing
                    * the logic will be performed when wp_remote_get runs
                    * calling the php script that will do the rest
                    * check the file permisions - need to be 604 or the WP-Apache/Nginx user have the rights to read it
                    */
                }
            }
        }

        /*
        * create article source -> post category pairing
        * save it to an WP Option and use it globaly
        */
        private function save_sorce_cat_pairing($data) {
            /**
             * sanitize $_POST
             */
            $data = $this -> sanitize_text_or_array_field($data);
            /*
            * format the array to save from $data
            */
            $newSources = array();
            if(! empty($data)) for($i = 1; $i < (count($data)/2); ++$i) {
                $newSources[$data["source_".$i]] = $data["targetcat_". $i];
            }
            /*
            * insert the new pairing into $sourceToCat
            */
            if(! empty($newSources)) foreach($newSources as $key => $newSource) {
                /*
                * update sources array
                */
                $sourceToCat[$key] = $newSource;
            }
            /*
            * make the new array a string
            * & save it into option
            */
            update_option( WPMNAPI_WPMAGIC_SOURCECATOPT, $sourceToCat );
            
            echo $this->wpmTexts['help_3'];
            $this->refreshPage(3);
        }

        /*
        * populate Form for pairing 
        * Sorces->Post Categories
        */
        private function populateSourcesForm($sources, $categories, $counter, $txt_13, $txt_14, $news = array()) {
            $sourceVal = '';
            /*
            * check if new source
            * place a "New *" text is source is new (not saved untill now) 
            */
            if(isset($sources["source"]["name"])) $sourceVal = $sources["source"]["name"];
            /*
            * set the default cat for the news to publish in the following order:
            * - set the default cat - just in case none exists
            * - check if defined in settings. Then get the value
            * - check if choosed of Admin. Then set the value to this.
            */
            $defaultCat = $this -> getDefaultCat();
            /*
            * start populating row 
            */
            $isLiteVersion = false;
            if( $counter <= WPMNAPI_WPMAGIC_LITE_LIMIT ) $isLiteVersion = true;
            echo '
            <div class="row row-striped'; if(!isset($sources["existing"])) echo " red_txt"; echo '">
                ' . $counter . ' -> ';
                if( !$isLiteVersion ) _e( $this->wpmTexts['locksign'], "wpm-news-api" );
                echo '
                <p class="text-center font-italic font-weight-bold w-100">'; if(isset($news["title"])) echo $news["title"]; echo '</p>
                <label for="source_' . $counter . '">
                    <span>'; if(!isset($sources["existing"])) _e( $this->wpmTexts['txt_16'], "wpm-news-api" ); _e( $txt_13, "wpm-news-api" ); echo '</span>
                    <div class="imgpreview">'; 
                        if(isset($news["urlToImage"])) {
                            echo '
                            <a href="' . esc_url( $news["url"] ) . '" target="_blank">
                                <img class="card-img-top catimg pt-2 pr-2" src="' . esc_url( $news["urlToImage"] ) . '" alt="">
                            </a>';
                        }
                        echo '
                    </div>';
                    if( $isLiteVersion ) echo '<input type="text" name="source_' . $counter . '" value="'.$sourceVal.'" readonly />';
                    else echo '<input type="text" name="lockedsource" value="'.$sourceVal.'" readonly />';
                    echo '
                </label>
                <label for="targetcat_' . $counter . '">
                    <span class="pl-2">'; _e( $txt_14, "wpm-news-api" ); echo '</span>';
                    if( $isLiteVersion ){
                    echo '
                    <select name="targetcat_' . $counter . '[]"  class="custom-select" multiple size="6">';
                        $flag = false;
                        if(!empty($categories)) foreach($categories as $postcat){
                            echo '
                            <option value="'.$postcat->cat_ID.'"'; 
                                // loop in saved categories for the source
                                if( isset($sources["existing"]) && sizeof($sources["existing"]) > 0 ) foreach( $sources["existing"] as $savedSource ) {
                                    if(isset($savedSource) && $savedSource == $postcat->cat_ID) { 
                                        echo ' selected'; $flag =  true; 
                                    }
                                }
                                if(!$flag) {
                                    if($postcat->cat_ID == $defaultCat)  echo ' selected'; 
                                }
                                echo '>'.$postcat->name.'
                            </option>';
                        }
                        echo '
                    </select>';
                    } else {
                        _e( $this->wpmTexts['litevinfo'], "wpm-news-api" );
                    }
                    echo '
                </label>
            </div>';
        }

        /*
        * change default category to post the news
        */
        private function save_sorce_defaultcat($defaultChoosedCat = WPMNAPI_WPMAGIC_DEFAULTCATID){
            /*
            * save the default cat slug into an option
            */
            if(isset($defaultChoosedCat["defaultpostcat"]) && ! empty($defaultChoosedCat["defaultpostcat"]) ) {
                update_option( WPMNAPI_WPMAGIC_DEFAULTCATOPT, sanitize_text_field($defaultChoosedCat["defaultpostcat"]) );
                _e( $this->wpmTexts['help_4'], "wpm-news-api" );
                $this->refreshPage(5, "?page=".WPMNAPI_WPMAGIC_SETTINGS);
            }
        }

        /*
        * get the default category to post the news
        */
        private function getDefaultCat(){
            $defaultCat = 1;
            if(defined(WPMNAPI_WPMAGIC_DEFAULTCATID)) $defaultCat = WPMNAPI_WPMAGIC_DEFAULTCATID;
            if(!empty(get_option(WPMNAPI_WPMAGIC_DEFAULTCATOPT)) && get_option(WPMNAPI_WPMAGIC_DEFAULTCATOPT) > 0) $defaultCat = get_option(WPMNAPI_WPMAGIC_DEFAULTCATOPT);

            return $defaultCat;
        }

        /**
         * add the ajax action function
         */
        function my_ajax_action_function() {
            // echo json_encode($_GET);
            
            /*
            * delaying repeated requests
            */
            sleep(1);

            /*
            * call the remote site and fetch the JSON data using the API
            */
            $url = ''; $apiKey = ''; $apiCredentials = array();
            if( isset($_GET) && !empty($_GET) ) $apiCredentials = $_GET;
            if( sizeof($apiCredentials) > 0 ) {
                if(isset($apiCredentials['apikey']) & isset($apiCredentials['apiurl'])) {   
                    $url    = $apiCredentials['apiurl'];
                    $apiKey = $apiCredentials['apikey'];
                } else {
                    $getPluginOptions = defined('WPMNAPI_WPMAGIC_APIOPTIONNAME') ? get_option( WPMNAPI_WPMAGIC_APIOPTIONNAME ) : '';
                    if(! empty($getPluginOptions)) $url = $getPluginOptions->params->apiurl;
                    $apiKey = $getPluginOptions->params->apikey;
                }
            }

            /*
            * fetch JSON from URL
            */
            $headers = array();
            $headers['Accept']    = 'application/json';
            $headers['x-api-key'] = $apiKey;
            $request = wp_remote_get( $url, array( 'headers' => $headers ) );
            if( is_wp_error( $request ) ) {
                $error_string = $request->get_error_message();
                // echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
                echo $error_string; // Bail early
            }
            
            /*
            * use fetched JSON
            */
            $body = wp_remote_retrieve_body( $request );
            $data = json_decode( $body );
            
            if( isset($data->status) && $data->status == 'ok' ) {
                /* 
                * $body = the JSON data
                * √ save JSON data into WP option & use it on the pluginclass
                * √ compare saved data with the new fetch
                * update only in case new content exists
                * post it as WP Post - check the article source and post it into category accordingly
                * this will run on every WP Cron run or manual when 'Fetch latest news' button is clicked
                */
                $fetchedNews = get_option( WPMNAPI_WPMAGIC_JSONDATA );
                if( ! is_array ($fetchedNews) & ! empty($fetchedNews) ) {
                    $compareNewsStrings = strcmp( $fetchedNews, $body );
                    // Compare the strings if equal or check if Force Publish was requested
                    if( $compareNewsStrings <> 0 || (isset($apiCredentials['forcepublish']) && $apiCredentials['forcepublish'] == 'y') ) { 
                        /*
                        * $body is different (new) from $fetchedNews
                        * update jsonData option
                        */
                        if( ! isset($apiCredentials['forcepublish']) ) $this -> takeActions ( $body, $fetchedNews ); // no force to publish request received - normal
                        else {
                            $this -> publishAsWPPost(json_decode( $body, true )); // skip the fetched article already published check and publish them anyway
                            // display a message
                            update_option( WPMNAPI_WPMAGIC_LASTERROROPTIONNAME, _e( $this->wpmTexts['success_3'], "wpm-news-api" ) );
                            // reload the help page in order to see the message
                            // $this -> refreshPage(0, "?page=".WPMNAPI_WPMAGIC_HELP);
                        }
                    } else {
                        /* 
                        * do nothing - the json have not changenged since last fetch
                        * offer the option to delete fetched news in help page
                        */
                        $this -> forcePublishMessage();
                    }
                } else {
                    /*
                    * create jsonData option
                    */
                    update_option(WPMNAPI_WPMAGIC_JSONDATA, $body);
                }
            }

            /**
             * in case of API error
             */
            if( isset($data->status) && $data->status == 'error' ) {
            /**
             * update Plugin error
             */
            if( isset($data->message) ) update_option( WPMNAPI_WPMAGIC_LASTERROROPTIONNAME, __( $data->message, "wpm-news-api" ) );
            // and redirect to help page to see the error
            echo 'error';
            }

            // header( "Content-Type: application/json" );
            // echo json_encode($response);
        
            //Don't forget to always exit in the ajax function.
            exit();
        }

        /*
        * take actions if news changed from last fetch
        * √ update option
        * compare what was changed and publish only the unique news
        */
        function takeActions ( $newNews, $oldNews = array() ) {
            /*
            * make JSON to Obj
            */
            $oldNews = ! empty($oldNews) ? json_decode($oldNews, true) : '';
            $newNews = json_decode($newNews, true);
            /*
            * compare the objects and create new one with the new Articles
            */
            $combinedObj['articles'] = array();
            foreach( $newNews['articles'] as $news ) {
                /*
                * √ set the triger, if the same title exist, to false for each iteration
                * √ iterate all articles from news already fetched (with 1 hour before) & compare all titles with each title from new fetched articles
                */
                $flag = false; 
                //echo '<br />new = '; echo wp_strip_all_tags($news['title']);
                foreach ( $oldNews['articles'] as $oldArticles ) {
                    //echo '<br />old = '; echo wp_strip_all_tags($oldArticles['title']);
                    $compareTitles = strcmp( wp_strip_all_tags($news['title']), wp_strip_all_tags($oldArticles['title']) );
                    if ($compareTitles == 0) {
                        $flag = true;
                        // will leave the foreach loop and also the if statement
                        break;
                    }
                    //echo " - "; echo $compareTitles;
                }
                /*
                * if Titles not identical save the article to new Obj
                */
                if( ! $flag ) array_push($combinedObj['articles'], $news);
            }

            /*
            * √ update WP Plugin Option accordingly in order to post articles on site.
            * √ check if empty and make no updates
            * Hint! View changes in plugin settings Page
            */
            if(isset($combinedObj['articles']) && sizeof($combinedObj['articles']) > 0) {
                update_option(WPMNAPI_WPMAGIC_JSONDATA, json_encode($combinedObj));
                /*
                * √ Check the article source and create / update an array mapping source to wp post category
                * Publish the unique articles as WP Posts
                */
                $this -> publishAsWPPost($combinedObj);
            } else {
                /* 
                * do nothing - the json have not changenged since last fetch
                * offer the option to delete fetched news in help page
                */
                $this -> forcePublishMessage();
            }
        }


        /*
        * publish the News as WP Posts
        */
        function publishAsWPPost($combinedObj){
            
            global $apiCredentials;
            /*
            * get the existing source->postcat pairing
            * compare source from fetched News with postcat pairing and post News in pointed Category
            */    
            $getSourceToCat = get_option( WPMNAPI_WPMAGIC_SOURCECATOPT );
            /*
            * get the Obj with the latest 100 articles published on site
            */
            $getLatestPostsObj = $this -> getLatestArticlesObj();
            /*
            * publish News the posts
            */
            $publishThrumbImage = array(); $test = array(); $freePluginCounter = 0; $freePluginNewsH = 1000;
            if( isset($combinedObj['articles']) && sizeof($combinedObj['articles']) > 0 ) foreach($combinedObj['articles'] as $news) {
                /**
                 * check if more than n post published / h
                 */
                if( $freePluginCounter < $freePluginNewsH ) {
                    /*
                    * sanitize title for Post
                    */
                    $post_name = sanitize_text_field($news['title']);
                    $post_name = str_replace(',','',$post_name); // sanitize_text further
                    /*
                    * set NO GO signal to false for each of the news
                    * check if force publish requested or not
                    * if force publish requested skip the same article post check and publis any fetched article even if already posted on the website
                    */
                    $noGoSignal = false;
                    if(! isset($apiCredentials['forcepublish'])) {
                        /*
                        * search article title among existing Post to be sure there are no duplicates articles posted
                        * ››› even if we check the fetched articles with the chached ones the post sometimes are repeated during the day
                        * √ need to check within the last 100 posted articles the same title
                        * √ if the title not found » create the article
                        */
                        
                        // check the article title between the last posts on the site
                        foreach($getLatestPostsObj as $latestPost) {
                            if($latestPost->post_title == $post_name) $noGoSignal = true;
                        }
                    }
                    // if the same title finded bettwen the article already posted - skip this particular news from publishing
                    if(! $noGoSignal ) {
                        /*
                        * search article category into existing source->postcat pairing
                        * get the default cat if the category pairing was not yet defined
                        * stop if no category (default or defined) found
                        */
                        $catToPost = 0;
                        if( is_array($getSourceToCat) && sizeof($getSourceToCat) > 0 ) foreach($getSourceToCat as $key => $catPairing) {
                            if($news['source']['name'] == $key) {
                                $catToPost = $catPairing;
                            }
                        }
                        /*
                        * check if a source->category pairing was founded
                        * if not set the category to default
                        */
                        if( is_array($catToPost) && sizeof($catToPost) == 0 ) $catToPost = array(get_option( WPMNAPI_WPMAGIC_DEFAULTCATOPT ));
                        /*
                        * if no default category was founded (defined) go ahead and ››››
                        * √ set the category to 1 (first that exists)
                        * ››› we make it like this to be sure that News will be posted on Blog even if no default category was choosed or defined
                        */
                        if( is_array($catToPost) && sizeof($catToPost) == 0 ) $catToPost = array(1);
                        if( (is_array($catToPost) && sizeof($catToPost) > 0) & (isset($news['urlToImage']) && $news['urlToImage'] != '') ) { 
                            /*
                            * create postarr array to publish the post
                            */
                            $content = $this -> replace_between($news["content"], "[", "]",  "<a href='".$news['url']."' target='_blank'>" . __("read more", "wpm-news-api") . "</a>" );
                            $postarr = array(
                                'post_content'      => $content,
                                'post_title'        => $post_name,
                                'post_category'     => $catToPost,
                                'post_type'         => 'post',
                                'post_status'       => 'publish' // pending
                            );
                            /* 
                            * save Article as Pending Post in order to publish it later
                            * will be published if an image was founded for the article
                            * if not will remain draft
                            */
                            $result = $this -> create_custom_post($postarr);
                            if ( is_wp_error($result) ){
                                /*
                                * show the error into Help Page if problem publishing
                                */
                                update_option( WPMNAPI_WPMAGIC_LASTERROROPTIONNAME, $result->get_error_message());
                                // and redirect to help page to see the error
                                echo "error create_custom_post";
                            } else { 
                                if($result > 0) {
                                    // attache an image to post
                                    // $publishThrumbImage[] = $this -> generate_Featured_Image( $news['urlToImage'], $result, $news['title'], $post_name );
                                    /**
                                     * add dummy Featured_Image in order to be able to dinamicaly change it
                                     * with _thumbnail_ext_url
                                     */
                                    $publishThrumbImage[] = $this -> generate_Featured_Image( WPMNAPI_WPMAGIC_DUMMYIMG, $result, $news['title'], $post_name );
                                    $this -> addRemoteImg( $result, $news['urlToImage'] );
                                }
                            }
                        } else {
                            /* 
                            * no category found - do nothing
                            * or
                            * no images for the article found - do nothing
                            */
                            
                        }
                        /** */
                        $freePluginCounter++;
                    } // end $noGoSignal
                    
                } // end check news published / h
            }
        }

        /* 
        * create custom Post
        */
        function create_custom_post($postarr = array()) {
            $post = wp_insert_post($postarr);
            
            return $post;
        }

        /* 
        * replace chars between
        */
        function replace_between($str, $needle_start, $needle_end, $replacement) {
        $pos = strpos($str, $needle_start);
        $start = $pos === false ? 0 : $pos + strlen($needle_start);

        $pos = strpos($str, $needle_end, $start);
        $end = $pos === false ? strlen($str) : $pos;

        return substr_replace(strip_tags($str), $replacement, $start, $end - $start);
        }

        /**
        * Downloads an image from the specified URL and attaches it to a post as a post thumbnail.
        *
        * @param string $file    The URL of the image to download.
        * @param int    $post_id The post ID the post thumbnail is to be associated with.
        * @param string $desc    Optional. Description of the image.
        * @return string|WP_Error Attachment ID, WP_Error object otherwise.
        */
        function generate_Featured_Image( $remoteFile, $post_id, $desc, $post_name ){
            /**
             * loadthe WP files when running from wp-cron.php
             */
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';
            require_once ABSPATH . 'wp-admin/includes/image.php';
            /* 
            * Set variables for storage, fix file filename for query strings.
            * fix filename for query strings
            */
            preg_match( '/[^\?]+\.(jpg|jpe|jpeg|gif|png|JPG|JPEG|webp)/i', $remoteFile, $matches );
            
            /* 
            * Set variable $returnTest to check for errors and display them into help page
            * set image file name & get file type
            */
            $returnTest = array();
            $image_file_name = ''; if(isset($matches[0])) $image_file_name = basename( $matches[0] );
            $filetype = wp_check_filetype($image_file_name, null );
            
            /* 
            * check if file have extension of defined type
            * don't download if not
            */
            if(isset($filetype['ext']) && !empty($filetype['ext'])) {
                // Download file to temp dir
                $timeout_seconds = 5;
                $temp_file = download_url( $remoteFile, $timeout_seconds ); //loaded from file.php

                if ( !is_wp_error( $temp_file ) ) {
                    // Array based on $_FILE as seen in PHP file uploads
                    $file = array(
                        'name'     => $image_file_name, // ex: wp-header-logo.png
                        'type'     => $filetype,
                        'tmp_name' => $temp_file,
                        'error'    => 0,
                        'size'     => filesize($temp_file),
                    );

                    $overrides = array(
                        // Tells WordPress to not look for the POST form
                        // fields that would normally be present as
                        // we downloaded the file from a remote server, so there
                        // will be no form fields
                        // Default is true
                        'test_form' => false,

                        // Setting this to false lets WordPress allow empty files, not recommended
                        // Default is true
                        'test_size' => true,
                    );

                    // Move the temporary file into the uploads directory
                    $attachmentID = media_handle_sideload( $file, $post_id, $desc, $overrides );
                    
                    $returnTest = array(
                        'postID'        => $post_id,
                        'name'          => $image_file_name,
                        'file'          => $file,
                        'url'           => $remoteFile,
                    );
                    
                    if ( is_wp_error( $attachmentID ) ) {
                        // If error storing permanently, unlink.
                        @unlink( $temp_file );
                        // Update result to view it
                        $returnTest = array_merge($returnTest, array('attachmentID' => $attachmentID->get_error_message()));
                    } else {
                        // Sets the post thumbnail (featured image) for the given post.
                        set_post_thumbnail( $post_id, $attachmentID );
                        // Change Post status from Draft to Publish & the post slug
                        $publishPost = wp_update_post( array('ID' => $post_id, 'post_name'=> $post_name, 'post_status' => 'publish') );
                        if ( ! is_wp_error( $publishPost ) ) {
                            // Update result to view it
                            array_push($returnTest, __( WPMNAPI_WPMAGIC_WPMTEXTS['help_6'], "wpm-news-api" ) );
                        } else {
                            // Errors publishing the Post
                            array_push($returnTest, $publishPost->get_error_message());
                        }
                    }
                } else {
                    // Check for download errors. If error, unlink.
                    @unlink( $temp_file );
                    // Update result to view it
                    array_push($returnTest, $remoteFile . ' - ' . $temp_file->get_error_message());
                }
            } else {
                /* 
                * no extension was found
                * create a function to proces images from Url's that don't have an image type but still publish a image
                */
                $getRemoteImg = $this -> get_remote_img( $remoteFile, $post_id );
                
                // test if file is an jpeg image
                preg_match( '/[^\?]+\.(jpeg)/i', $getRemoteImg, $matches );
                if(isset($matches[0])){
                    $downloadedImageNameAndExt = basename( $matches[0] );
                    $imgtype = wp_check_filetype($downloadedImageNameAndExt, null );
                    if( isset($imgtype['ext']) && !empty($imgtype['ext']) ) {
                        // Set the Url path for image in order to get a file ext from the Url & recall the function recursively
                        $uploadWPDir = wp_upload_dir();
                        $newRemoteImg = WPMNAPI_WPMAGIC_IMGTMPFOLDERURL . DIRECTORY_SEPARATOR . $downloadedImageNameAndExt;
                        // call the function once again recursively & link the image to post - publish the post eventualy
                        $returnTest[] = $this -> generate_Featured_Image( $newRemoteImg, $post_id, $desc, $post_name );
                        // delete the tmp image
                        wp_delete_file( WPMNAPI_WPMAGIC_IMGTMPFOLDER . DIRECTORY_SEPARATOR . $downloadedImageNameAndExt );
                        // make a Info for debugging
                        array_push($returnTest);
                    }
                } else { 
                    // If no image once again was not founded, just make an Info for debugging
                    array_push($returnTest, __( $remoteFile . ' - ' . WPMNAPI_WPMAGIC_WPMTEXTS['help_7'], "wpm-news-api" ) );
                }
            }
            
            return $returnTest;
        }

        /**
        * get the content body from Url
        * store the remote HTML in a transient if need it
        */
        function get_remote_html( $url, $storeit = false, $transientName = 'foo_remote_html', $transientTime = 24) {
        
            // Check for transient, if none, grab remote HTML file
            if ( false === ( $html = get_transient( 'foo_remote_html' ) ) ) {
                
                // Get remote HTML file
                $response = wp_remote_get( $url, array(
                                'method'      => 'GET',
                                'timeout'     => 5
                            ) );
                
                // Check for error
                if ( is_wp_error( $response ) ) {
                    return $response->get_error_message();
                }

                // Parse remote HTML file in order to store it in transient
                $data = wp_remote_retrieve_body( $response );

                // Check for error
                if ( is_wp_error( $data ) ) {
                    return $data->get_error_message();
                }

                // Store remote HTML file in transient, expire after x hours
                if( $storeit === true ) set_transient( $transientName, $data, $transientTime * HOUR_IN_SECONDS );
                else $html = $data;
            }

            return $html;
        }

        /**
        * get the content body from Url
        * check if is an image (jpeg)
        * create and save image file into a TMP folder in order to use it later
        */
        function get_remote_img( $url, $post_id, $imgType = 'image/jpeg' ) {

            $fileExt = $this -> getExtension ($imgType);

            // Get remote HTML file
            $response = wp_remote_get( $url, array(
                'method'      => 'GET',
                'timeout'     => 5,
                'headers'     => array('Accept' => $imgType)
            ) );

            // Check for error
            if ( is_wp_error( $response ) ) {
                return $response->get_error_message();
            }

            // Parse remote HTML file & look for images (jpeg)
            $reqStatus = wp_remote_retrieve_response_code( $response );
            if( $reqStatus == 200 ) {
                $reqHeaders = wp_remote_retrieve_headers( $response );
                $reqHeadersArray = $reqHeaders->getAll();
                if( is_array($reqHeadersArray) && sizeof($reqHeadersArray) > 0 ) {
                    if( $reqHeadersArray['content-type'] == 'image/jpeg' ) {
                        // Get the image from Body
                        $reqBody = wp_remote_retrieve_body( $response );
                        // Stop if any errors found
                        if ( is_wp_error( $reqBody ) ) return $reqBody->get_error_message();
                        // If no errors create Image file and save it into a tmp folder in WP Upload Dir
                        $im = imagecreatefromstring($reqBody);
                        if ( $im !== false & defined('WPMNAPI_WPMAGIC_IMGTMPFOLDER') ) {
                            $uploadDir = WPMNAPI_WPMAGIC_IMGTMPFOLDER;
                            $createUploadDir = wp_mkdir_p($uploadDir);
                            if($createUploadDir === true ) {
                                $newImage = $uploadDir . DIRECTORY_SEPARATOR . $post_id . "." . $fileExt;
                                imagejpeg($im, $newImage);
                                imagedestroy($im);

                                return $newImage;
                            }
                        }
                    }
                }
            }
        }

        /**
        * set the extension of a file based on the mime_type
        * usefull for images 'image/jpeg' => 'jpeg'
        * can be extended if more mime_type are used
        */
        function getExtension ($mime_type){
            $extensions = array('image/jpeg' => 'jpeg',
                                'text/xml' => 'xml'
                                );

            // Add as many other Mime Types / File Extensions as you like

            return $extensions[$mime_type];
        }

        /**
        * cURL function - *** not used *** - here only to debug wp_remote_get() when needed
        * Defines the function used to initial the cURL library.
        * @param  string  $url        To URL to which the request is being made
        * @return string  $response   The response, if available; otherwise, null
        */
        function curl( $url ) {

            $curl = curl_init( $url );

            curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $curl, CURLOPT_HEADER, 0 );
            curl_setopt( $curl, CURLOPT_USERAGENT, '' );
            curl_setopt( $curl, CURLOPT_TIMEOUT, 10 );
            curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );

            $response = curl_exec( $curl );
            if( 0 !== curl_errno( $curl ) || 200 !== curl_getinfo( $curl, CURLINFO_HTTP_CODE ) ) {
            $response = null;
            } // end if
            curl_close( $curl );

            return $response;

        } // end curl

        /**
        * Get the latest 100 published articles
        */
        function getLatestArticlesObj( $nrOfArticlesToGet = 100 ) {
            $args = array(
                'numberposts'      => $nrOfArticlesToGet,
                'orderby'          => 'date',
                'order'            => 'DESC',
                'include'          => array(),
                'exclude'          => array(),
                'meta_key'         => '',
                'meta_value'       => '',
                'post_type'        => 'post',
                'suppress_filters' => true,
            );

            $getLatestPostsObj = get_posts($args);

            return $getLatestPostsObj;
        }

        /* 
        * if nothing changed from the last fetch »
        * offer the option to delete fetched news in help page
        */
        function forcePublishMessage() {
            update_option( WPMNAPI_WPMAGIC_LASTERROROPTIONNAME, WPMNAPI_WPMAGIC_WPMTEXTS['help_5'] );
            // and redirect to help page to see the help page
            echo "error forcePublishMessage";
        }

        /**
         * Recursive sanitation for text or array
         * 
         * @param $array_or_string (array|string)
         * @since  0.1
         * @return mixed
         */
        function sanitize_text_or_array_field($array_or_string) {
            if( is_string($array_or_string) ){
                $array_or_string = sanitize_text_field($array_or_string);
            }elseif( is_array($array_or_string) ){
                foreach ( $array_or_string as $key => &$value ) {
                    if ( is_array( $value ) ) {
                        $value = $this -> sanitize_text_or_array_field($value);
                    }
                    else {
                        $value = sanitize_text_field( $value );
                    }
                }
            }

            return $array_or_string;
        }

        /**
         * link featured image of post to remote image
         */
        function addRemoteImg( $postId, $remoteImg ) {
            update_post_meta( $postId, '_thumbnail_ext_url', $remoteImg );
        }

        /**
         * 
         */
        function set_thumbnail_ext_url( $attr, $attachment, $size ) {
            $my_meta = get_post_meta( get_the_ID() );
            if ( isset($my_meta['_thumbnail_ext_url'][0]) ) {
                if ( isset( $attr['class'] ) && $attr['class'] != 'custom-logo' ) {
                    $attr['src'] = $my_meta['_thumbnail_ext_url'][0];
                    $attr['srcset'] = $my_meta['_thumbnail_ext_url'][0];
                }
            }
            
            return $attr;
        }

        /**
         * change post attachment link
         */
        function wpb_autolink_featured_images( $html, $post_id, $post_image_id ) {
            $my_meta = get_post_meta( $post_id );
            if( is_single($post_id) && isset($my_meta['_thumbnail_ext_url'][0]) ) {
                $html = '<a href="' . $my_meta['_thumbnail_ext_url'][0] . '" title="' . esc_attr( get_the_title( $post_id ) ) . '" class="image-popup">' . $html . '</a>';
            } else {
                $search_pattern = '/(?<=src=").*?(?=")/';
                preg_match_all($search_pattern, $html, $matches);
                $html = str_replace($matches[0][0], $my_meta['_thumbnail_ext_url'][0], $html);
            }
            return $html;
        }

        /*
        * load custom CSS
        * in FrontEnd
        */
        public function load_front_libraries() {
            wp_enqueue_style(
                wp_get_theme()->theme_root_uri,
                plugins_url( '../css/stylefrontpage.min.css', __FILE__ ),
                WPMNAPI_WPMAGIC_VERSION, 
                true
            );
        }
        
    }
}
?>
<?php

$wtn_api_settings = get_option( 'wtn_api_settings' );
$apiKey = ( isset( $wtn_api_settings['wtn_api_key'] ) ? $wtn_api_settings['wtn_api_key'] : '' );
$wtn_settings = get_option( 'wtn_settings' );
$newsSource = ( isset( $wtn_settings['wtn_select_source'] ) ? $wtn_settings['wtn_select_source'] : 'cnn' );
$wtn_news_number = ( isset( $wtn_settings['wtn_news_number'] ) ? $wtn_settings['wtn_news_number'] : 10 );
$newsLayout = ( isset( $wtn_settings['wtn_layout'] ) ? $wtn_settings['wtn_layout'] : 'list' );
$wtn_grid_columns = ( isset( $wtn_settings['wtn_grid_columns'] ) ? $wtn_settings['wtn_grid_columns'] : 1 );
$wtn_title_length = ( isset( $wtn_settings['wtn_title_length'] ) ? $wtn_settings['wtn_title_length'] : 4 );
$wtn_desc_length = ( isset( $wtn_settings['wtn_desc_length'] ) ? $wtn_settings['wtn_desc_length'] : 18 );
$wtn_display_news_source = ( isset( $wtn_settings['wtn_display_news_source'] ) ? $wtn_settings['wtn_display_news_source'] : '' );
$wtn_display_date = ( isset( $wtn_settings['wtn_display_date'] ) ? $wtn_settings['wtn_display_date'] : '' );
$wtnDesc = '';
if ( $wtn_grid_columns > 3 ) {
    $wtn_grid_columns = 3;
}
$wtn_news_init_stdclass = ( !empty($this->wtn_get_api_data( $newsSource, $apiKey )) ? $this->wtn_get_api_data( $newsSource, $apiKey ) : array() );
?>
<style>
.wtn-main-wrapper {
    grid-template-columns: repeat(<?php 
echo  esc_html( $wtn_grid_columns ) ;
?>, 1fr);
}
@media(max-width:500px) {
    .wtn-main-wrapper {
       grid-template-columns: repeat(1, 1fr);
    }
}
</style>
<?php 

if ( 'list' === $newsLayout ) {
    ?>
    <div class="wtn-main-container">
        <?php 
    for ( $i = 0 ;  $i < $wtn_news_number ;  $i++ ) {
        $wtn_news = ( isset( $wtn_news_init_stdclass[$i] ) ? (array) $wtn_news_init_stdclass[$i] : [] );
        
        if ( !empty($wtn_news) ) {
            ?>
                <div class="wtn-feed-container">
                    <div class="wtn-img-container">
                        <div class="wtn-img" style="background-image: url('<?php 
            echo  esc_attr( $wtn_news['urlToImage'] ) ;
            ?>');" ></div>
                    </div>
                    <div class="wtn-feeds">
                        <a href="<?php 
            printf( '%s', esc_url( $wtn_news['url'] ) );
            ?>" target="_blank" class="wtn-feeds-title">
                            <?php 
            echo  esc_html( wp_trim_words( $wtn_news['title'], $wtn_title_length, '...' ) ) ;
            ?>
                        </a>

                        <?php 
            
            if ( $wtnDesc !== 'hide' ) {
                ?>
                                <p class="wtn-feeds-description">
                                    <?php 
                echo  esc_html( wp_trim_words( $wtn_news['description'], $wtn_desc_length, '...' ) ) ;
                ?>
                                </p>
                                <?php 
            }
            
            ?>
                        <span>
                            <?php 
            
            if ( '1' === $wtn_display_news_source ) {
                $wtn_source = (array) $wtn_news['source'];
                printf( '%s', $wtn_source['name'] );
                ?> |
                            <?php 
            }
            
            ?>
                            <?php 
            if ( '1' === $wtn_display_date ) {
                echo  date( 'd M, Y', strtotime( $wtn_news['publishedAt'] ) ) ;
            }
            ?>
                        </span>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <?php 
        }
    
    }
    ?>
    </div>
    <?php 
}

?>

<?php 

if ( 'grid' === $newsLayout ) {
    ?>
    <div class="wtn-main-wrapper">
        <?php 
    for ( $i = 0 ;  $i < $wtn_news_number ;  $i++ ) {
        $wtn_news = (array) $wtn_news_init_stdclass[$i];
        
        if ( !empty($wtn_news) ) {
            ?>
                <div class="wtn-item">
                    <div class="wtn-img-container">
                        <div class="wtn-img" style="background-image: url('<?php 
            echo  esc_attr( $wtn_news['urlToImage'] ) ;
            ?>');" ></div>
                    </div>
                    <a href="<?php 
            printf( '%s', esc_url( $wtn_news['url'] ) );
            ?>" target="_blank">
                        <?php 
            echo  esc_html( wp_trim_words( $wtn_news['title'], $wtn_title_length, '...' ) ) ;
            ?>
                    </a>

                    <?php 
            
            if ( $wtnDesc !== 'hide' ) {
                ?>
                            <p class="wtn-item-description">
                                <?php 
                echo  esc_html( wp_trim_words( $wtn_news['description'], $wtn_desc_length, '...' ) ) ;
                ?>
                            </p>
                            <?php 
            }
            
            ?>
                    <span>
                        <?php 
            
            if ( '1' === $wtn_display_news_source ) {
                $wtn_source = (array) $wtn_news['source'];
                printf( '%s', $wtn_source['name'] );
                ?> |
                        <?php 
            }
            
            ?>
                        <?php 
            if ( '1' === $wtn_display_date ) {
                echo  date( 'd M, Y', strtotime( $wtn_news['publishedAt'] ) ) ;
            }
            ?>
                    </span>
                </div>
                <?php 
        }
    
    }
    ?>
    </div>
    <?php 
}

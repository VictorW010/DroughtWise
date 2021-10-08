<?php
$wtnShowMessage = false;
$wtn_setting = array( 'swtn_select_source' => 'cnn');

if ( isset( $_POST['updateSettings'] ) ) {
     
     $wtnSettingsInfo = array(
          'wtn_select_source'           => !empty($_POST['wtn_select_source']) ? filter_var($_POST['wtn_select_source'], FILTER_SANITIZE_STRING) : 'cnn',
          'wtn_news_number'             => ( isset( $_POST['wtn_news_number'] ) && filter_var( $_POST['wtn_news_number'], FILTER_SANITIZE_NUMBER_INT ) ) ? $_POST['wtn_news_number'] : 10,
          'wtn_layout'                  => ( filter_var( $_POST['wtn_layout'], FILTER_SANITIZE_STRING ) ) ? $_POST['wtn_layout'] : '',
          'wtn_grid_columns'            => ( isset( $_POST['wtn_grid_columns'] ) && filter_var( $_POST['wtn_grid_columns'], FILTER_SANITIZE_NUMBER_INT ) ) ? $_POST['wtn_grid_columns'] : 1,
          'wtn_title_length'            => ( isset( $_POST['wtn_title_length'] ) && filter_var( $_POST['wtn_title_length'], FILTER_SANITIZE_NUMBER_INT ) ) ? $_POST['wtn_title_length'] : 4,
          'wtn_desc_length'             => ( isset( $_POST['wtn_desc_length'] ) && filter_var( $_POST['wtn_desc_length'], FILTER_SANITIZE_NUMBER_INT ) ) ? $_POST['wtn_desc_length'] : 18,
          'wtn_display_news_source'     => ( isset( $_POST['wtn_display_news_source'] ) && filter_var( $_POST['wtn_display_news_source'], FILTER_SANITIZE_NUMBER_INT ) ) ? $_POST['wtn_display_news_source'] : '',
          'wtn_display_date'            => ( isset( $_POST['wtn_display_date'] ) && filter_var( $_POST['wtn_display_date'], FILTER_SANITIZE_NUMBER_INT ) ) ? $_POST['wtn_display_date'] : '',
     );

     $wtnShowMessage     = update_option( 'wtn_settings', $wtnSettingsInfo );
     $wtn_api_settings   = get_option('wtn_api_settings');
     $wtn_api_key        = isset( $wtn_api_settings['wtn_api_key'] ) ? $wtn_api_settings['wtn_api_key'] : '';
     $this->wtn_set_api_data_to_cache( $_POST['wtn_select_source'], $wtn_api_key );
}

$wtn_settings            = get_option('wtn_settings');
$wtn_news_number         = isset( $wtn_settings['wtn_news_number'] ) ? $wtn_settings['wtn_news_number'] : 10;
$wtn_grid_columns        = isset( $wtn_settings['wtn_grid_columns'] ) ? $wtn_settings['wtn_grid_columns'] : 1;
$wtn_title_length        = isset( $wtn_settings['wtn_title_length'] ) ? $wtn_settings['wtn_title_length'] : 4;
$wtn_desc_length         = isset( $wtn_settings['wtn_desc_length'] ) ? $wtn_settings['wtn_desc_length'] : 18;
$wtn_display_news_source = isset( $wtn_settings['wtn_display_news_source'] ) ? $wtn_settings['wtn_display_news_source'] : '';
$wtn_display_date        = isset( $wtn_settings['wtn_display_date'] ) ? $wtn_settings['wtn_display_date'] : '';
$wtn_layout              = isset( $wtn_settings['wtn_layout'] ) ? $wtn_settings['wtn_layout'] : 'grid';
$wtn_select_source       = isset( $wtn_settings['wtn_select_source'] ) ? $wtn_settings['wtn_select_source'] : 'cnn';
?>
<div id="wph-wrap-all" class="wrap">

     <div class="settings-banner">
          <h2><?php _e('General Settings', WTN_TXT_DMN); ?></h2>
     </div>
     
     <?php 
     if ( $wtnShowMessage ) {
          $this->wtn_display_notification('success', 'Your information updated successfully.');
     } 
     ?>

     <form name="wpre-table" role="form" class="form-horizontal" method="post" action="" id="wtn-settings-form">
          <table class="form-table">
          <tr>
               <th scope="row">
                    <label><?php _e('News Source', WTN_TXT_DMN); ?>:</label>
               </th>
               <td>
                    <div class="wtn-template-selector">
                         <?php 
                         $wtnNewsSourceArray = $this->wtnGetNewsSources();
                         $i=1;
                         foreach ( $wtnNewsSourceArray as $source => $name ) {
                              ?>
                              <div class="wtn-template-item">
                                   <input type="radio" name="wtn_select_source" id="<?php esc_attr_e( $name ); ?>" value="<?php esc_attr_e( $source ); ?>" <?php echo ( $wtn_select_source === $source ) ? 'checked' : ''; ?>>
                                   <label for="<?php esc_attr_e( $name ); ?>" class="wtn-template">
                                        <?php esc_html_e( $name ); ?>
                                   </label>
                              </div>
                              <?php 
                              $i++; 
                         } 
                         ?>
                    </div>
               </td>
          </tr>
          <tr>
               <th scope="row">
                    <label><?php _e('Number of News', WTN_TXT_DMN); ?>:</label>
               </th>
               <td>
                    <input type="number" min="1" max="10" step="1" name="wtn_news_number" class="medium-text" min="1" max="10" value="<?php esc_attr_e( $wtn_news_number ); ?>">
               </td>
          </tr>
          <tr>
               <th scope="row">
                         <label><?php _e('Layout', WTN_TXT_DMN); ?>:</label>
               </th>
               <td>
                    <input type="radio" name="wtn_layout" id="wtn_layout_list" value="list" <?php if ( 'list' === $wtn_layout ) { echo 'checked'; } ?>>
                    <label for="wtn_layout_list"><span></span><?php _e('List', WTN_TXT_DMN); ?></label>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="wtn_layout" id="wtn_layout_grid" value="grid" <?php if ( 'grid' === $wtn_layout ) { echo 'checked'; } ?>>
                    <label for="wtn_layout_grid"><span></span><?php _e('Grid', WTN_TXT_DMN); ?></label>
               </td>
          </tr>
          <tr>
               <th scope="row">
                    <label><?php _e('Grid View Columns', WTN_TXT_DMN); ?>:</label>
               </th>
               <td>
                    <input type="number" name="wtn_grid_columns" class="medium-text" min="1" max="3" value="<?php esc_attr_e( $wtn_grid_columns ); ?>">
               </td>
          </tr>
          <tr>
               <th scope="row">
                    <label><?php _e('Title Word Length', WTN_TXT_DMN); ?>:</label>
               </th>
               <td>
                    <input type="number" name="wtn_title_length" class="medium-text" min="1" max="50" step="1" value="<?php esc_attr_e( $wtn_title_length ); ?>">
               </td>
          </tr>
          <tr>
               <th scope="row">
                    <label><?php _e('Description Word Length', WTN_TXT_DMN); ?>:</label>
               </th>
               <td>
                    <input type="number" name="wtn_desc_length" class="medium-text" min="1" max="100" step="1" value="<?php esc_attr_e( $wtn_desc_length ); ?>">
               </td>
          </tr>
          <tr>
               <th scope="row">
                    <label for="wtn_display_news_source"><?php _e('Display Source', WTN_TXT_DMN); ?>:</label>
               </th>
               <td>
                    <input type="checkbox" name="wtn_display_news_source" id="wtn_display_news_source" value="1" <?php echo ( '1' === $wtn_display_news_source ) ? 'checked' : ''; ?> >
               </td>
          </tr>
          <tr>
               <th scope="row">
                    <label for="wtn_display_date"><?php _e('Display Date', WTN_TXT_DMN); ?>?</label>
               </th>
               <td>
                    <input type="checkbox" name="wtn_display_date" id="wtn_display_date" value="1" <?php echo ( '1' === $wtn_display_date ) ? 'checked' : ''; ?> >
               </td>
          </tr>
          <tr>
               <th scope="row">
                    <label for="wtn_shortcode"><?php _e('Shortcode', WTN_TXT_DMN); ?>:</label>
               </th>
               <td>
                    <input type="text" name="wtn_shortcode" id="wtn_shortcode" class="regular-text" value="[wp_top_news]" readonly />
               </td>
          </tr>
          </table>
          <p class="submit"><button id="updateSettings" name="updateSettings" class="button button-primary"><?php _e('Update Settings', WTN_TXT_DMN); ?></button></p>
     </form>
</div>
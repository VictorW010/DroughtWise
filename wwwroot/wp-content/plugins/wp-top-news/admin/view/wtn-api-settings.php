<?php
$wtnShowMessage = false;

if ( isset( $_POST['updateSettings'] ) ) {

     $wtnSettingsInfo = array(
          'wtn_api_key' => isset( $_POST['wtn_api_key'] ) ? sanitize_text_field( $_POST['wtn_api_key'] ) : '',
     );
     $wtnShowMessage = update_option( 'wtn_api_settings', $wtnSettingsInfo );

     $wtn_settings = get_option('wtn_settings');

     $this->wtn_set_api_data_to_cache( $wtn_settings['wtn_select_source'], sanitize_text_field( $_POST['wtn_api_key'] ) );
}

$wtn_api_settings   = get_option('wtn_api_settings');
$wtn_api_key        = isset( $wtn_api_settings['wtn_api_key'] ) ? $wtn_api_settings['wtn_api_key'] : '';
?>
<div id="wph-wrap-all" class="wrap">
     
     <div class="settings-banner">
          <h2><?php _e('WP Top News API Settings', WTN_TXT_DMN); ?></h2>
     </div>
     <?php 
     if ( $wtnShowMessage ) { 
          $this->wtn_display_notification('success', 'Your information updated successfully'); 
     } 
     ?>

     <form name="wpre-table" role="form" class="form-horizontal" method="post" action="" id="wtn-settings-form">
          <table class="form-table">
          <tr class="wtn_api_key">
               <th scope="row">
                    <label for="wtn_api_key"><?php _e('API Key', WTN_TXT_DMN); ?>:</label>
               </th>
               <td>
                    <input type="password" name="wtn_api_key" class="regular-text" value="<?php esc_attr_e( $wtn_api_key ); ?>" autocomplete="off">
               </td>
          </tr>
          </table>
          <p class="submit"><button id="updateSettings" name="updateSettings" class="button button-primary"><?php _e('Update Settings', WTN_TXT_DMN); ?></button></p>
     </form>
</div>
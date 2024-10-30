<?php

function box_now_delivery_menu()
{
  add_menu_page(
    'BOX NOW Delivery',
    'BOX NOW Delivery',
    'manage_options',
    'box-now-delivery',
    'box_now_delivery_options',
    'dashicons-location',
    80
  );
}

require_once 'box-now-delivery-validation.php';

// Enqueue admin scripts
function box_now_delivery_enqueue_admin_scripts($hook)
{
  if ($hook != 'toplevel_page_box-now-delivery') {
    return;
  }

  wp_enqueue_script('box_now_delivery_admin_page_script', plugins_url('../js/box-now-delivery-admin-page.js', __FILE__), array(), '1.0', true);
}

add_action('admin_enqueue_scripts', 'box_now_delivery_enqueue_admin_scripts');

function box_now_delivery_options()
{
?>
  <div class="wrap">
    <h1>BOX NOW Delivery Plugin</h1>
    <?php settings_fields('box-now-delivery-settings-group'); ?>
    <?php do_settings_sections('box-now-delivery-settings-group'); ?>
    <label style="width: 100%; float: left;">Thank you for choosing BOX NOW as your delivery option! To learn more about our services, visit our <a href="https://boxnow.gr/">website</a> or contact us at <a href="mailto:info@boxnow.gr">info@boxnow.gr</a>.</label>
    <br>
    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
      <input type="hidden" name="action" value="boxnow-settings-save">
      <?php wp_nonce_field('boxnow-settings-save', 'boxnow-custom-message'); ?>

      <div id="main-container">

        <!-- Main inputs and credentials -->
        <div style="width: 100%; float: left;">
          <h2 style="width: 100%; float: left;">Your API details</h2>
          <div style="width:30%; float: left;">
            <p>
              <label>Your API URL:</label>
             <br />
              <select name="boxnow_api_url">
               <option value="api-stage.boxnow.gr" <?php selected(get_option('boxnow_api_url', ''), 'api-stage.boxnow.gr'); ?>>api-stage.boxnow.gr</option>
               <option value="api-production.boxnow.gr" <?php selected(get_option('boxnow_api_url', ''), 'api-production.boxnow.gr'); ?>>api-production.boxnow.gr</option>
              </select>
            </p>
            <p>
              <label>Warehouse IDs:</label>
              <br />
              <input type="text" name="boxnow_warehouse_id" value="<?php echo esc_attr(get_option('boxnow_warehouse_id', '')); ?>" placeholder="Enter your Warehouse ID" />
              <p >
          </div>
          <div style="width:30%; float: left;">
            <p>
              <label>Your Client ID:</label>
              <br />
              <input type="text" name="boxnow_client_id" value="<?php echo esc_attr(get_option('boxnow_client_id', '')); ?>" placeholder="Enter your Client ID" />
            </p>
            <p>
              <label>Your Partner ID:</label>
              <br />
              <input type="text" name="boxnow_partner_id" value="<?php echo esc_attr(get_option('boxnow_partner_id', '')); ?>" placeholder="Enter your Partner ID" />
            </p>
          </div>
          <div style="width:30%; float: left;">
            <p>
              <label>Your Client Secret:</label>
              <br />
              <input type="text" name="boxnow_client_secret" value="<?php echo esc_attr(get_option('boxnow_client_secret', '')); ?>" placeholder="Enter your Client Secret" />
            </p>
          </div>
          <label style="width: 100%; float: left;">If you deliver to multiple Warehouses, divide IDs by comma.</label>

          <!-- Button options -->
          <h2 style="width: 100%; float: left;">Button customization</h2>
          <div style="width:30%; float: left;">
            <p>
              <label>Change button color:</label>
              <br />
              <input type="text" name="boxnow_button_color" value="<?php echo esc_attr(get_option('boxnow_button_color', '#84C33F')); ?>" placeholder="#84C33F" />
            </p>
          </div>
          <div style="width:30%; float: left;">
            <p>
              <label>Change button text:</label>
              <br />
              <input type="text" id="button_text_input" name="boxnow_button_text" value="<?php echo esc_attr(get_option('boxnow_button_text', 'Pick a locker')); ?>" placeholder="Pick a locker" />
            </p>
          </div>

          <!-- Map options -->
          <h2 style="width: 100%; float: left;">Map customization</h2>
          <div style="width: 100%; float: left;">
            <p>
              <input type="radio" id="box_now_display_mode_popup" name="box_now_display_mode" value="popup" <?php checked(get_option('box_now_display_mode', 'popup'), 'popup'); ?>>
              <label for="box_now_display_mode_popup">Popup modal window</label>
            </p>
            <p>
              <input type="radio" id="box_now_display_mode_embedded" name="box_now_display_mode" value="embedded" <?php checked(get_option('box_now_display_mode', 'popup'), 'embedded'); ?>>
              <label for="box_now_display_mode_embedded">Embedded iFrame</label>
            </p>
          </div>

          <!-- GPS Options -->
          <h2 style="width: 100%; float: left;">GPS tracking location toggle</h2>
          <div style="width: 100%; float: left;">
            <p>
              <input type="radio" id="gps_tracking_on" name="boxnow_gps_tracking" value="on" <?php checked(get_option('boxnow_gps_tracking', 'on'), 'on'); ?>>
              <label for="gps_tracking_on">GPS ON</label>
            </p>
            <p>
              <input type="radio" id="gps_tracking_off" name="boxnow_gps_tracking" value="off" <?php checked(get_option('boxnow_gps_tracking', 'on'), 'off'); ?>>
              <label for="gps_tracking_off">GPS OFF</label>
            </p>
          </div>

          <!-- Voucher options -->
          <h2 style="width: 100%; float: left;">Choose voucher option</h2>
          <div style="width: 100%; float: left;">
            <p>
              <input type="radio" id="send_voucher_email" name="boxnow_voucher_option" value="email" <?php checked(get_option('boxnow_voucher_option', 'email'), 'email'); ?>>
              <label for="send_voucher_email">Send voucher via email</label>
            </p>
            <!-- Email input for voucher -->
            <div id="email_input_container" style="width: 100%; float: left; display: <?php echo (get_option('boxnow_voucher_option', 'email') === 'email') ? 'block' : 'none'; ?>;">
              <p>
                <label>Please insert your email here(It will be used a origin contact email):</label>
                <br />
                <input type="text" name="boxnow_voucher_email" value="<?php echo esc_attr(get_option('boxnow_voucher_email', '')); ?>" placeholder="Please insert your email here" />
              <p id="email_validation_message" style="color: red;"></p>
              </p>
              <br />
            </div>
            <p>
              <input type="radio" id="display_voucher_button" name="boxnow_voucher_option" value="button" <?php checked(get_option('boxnow_voucher_option', 'email'), 'button'); ?>>
              <label for="display_voucher_button">Display a button in WooCommerce admin order page for printing the vouchers</label>
            </p>
          </div>

          <!-- Message input when locker is not selected -->
          <h2 style="width: 100%; float: left;">Message shown when locker is not selected</h2>
          <div style="width: 30%; float: left;">
            <p>
              <label>Message:</label>
              <br />
              <input type="text" name="boxnow_locker_not_selected_message" value="<?php echo esc_attr(get_option('boxnow_locker_not_selected_message', '')); ?>" placeholder="Enter your message" />
            </p>
          </div>
            <!-- Sender's Contact field -->
            <h2 style="width: 100%; float: left;">Sender's Contact field</h2>
          <div style="width: 30%; float: left;">
            <p>
              <label>Sender's Phone(Will be used for returns):</label>
              <br />
              <input type="text" name="boxnow_mobile_number" value="<?php echo esc_attr(get_option('boxnow_mobile_number', '')); ?>" placeholder="Enter your phone with country preffix (+30 , +357, etc)" />
            </p>
          </div>

          <!-- Save button -->
          <div style="width:100%; float: left; clear: both;">
            <?php submit_button(); ?>
          </div>
        </div>
      </div>
    </form>
  </div>
<?php
}

function box_now_delivery_settings()
{
  $serializer = new BNDP_Serializer();
  $serializer->init();
}

add_action('admin_menu', 'box_now_delivery_menu');
add_action('admin_init', 'box_now_delivery_settings');

function box_now_delivery_enqueue_admin_styles($hook)
{
  if ($hook != 'toplevel_page_box-now-delivery') {
    return;
  }

  wp_register_style('box_now_delivery_admin_styles', plugin_dir_url(__FILE__) . '../css/box-now-delivery-admin.css');
  wp_enqueue_style('box_now_delivery_admin_styles');
}

add_action('admin_enqueue_scripts', 'box_now_delivery_enqueue_admin_styles');

function box_now_delivery_enqueue_styles()
{
  wp_register_style('box_now_delivery_styles', plugin_dir_url(__FILE__) . '../css/box-now-delivery.css', array(), '1.0.0');
  wp_enqueue_style('box_now_delivery_styles');
}

add_action('admin_enqueue_scripts', 'box_now_delivery_enqueue_styles');

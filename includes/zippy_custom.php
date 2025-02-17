<?php
add_action('wp_enqueue_scripts', 'shin_scripts');

function shin_scripts()
{
    $version = time();

    wp_enqueue_style('main-style-css', THEME_URL . '-child' . '/assets/dist/css/main.min.css', array(), $version, 'all');

    wp_enqueue_script('main-scripts-js', THEME_URL . '-child' . '/assets/dist/js/main.min.js', array('jquery'), $version, true);
}




// Add First Name, Last Name, Display Name to Registor Form
add_action( 'woocommerce_register_form_start', 'custom_register_form' );

function custom_register_form() {
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="first_name">First Name <span class="required" aria-hidden="true">*</span></label>
        <input type="text" name="first_name" id="first_name" class="input" required value="" size="25"/>
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="last_name">Last Name <span class="required" aria-hidden="true">*</span></label>
        <input type="text" name="last_name" id="last_name" class="input" required value="" size="25"/>
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="display_name">Display Name <span class="required" aria-hidden="true">*</span></label>
        <input type="text" name="display_name" id="display_name" class="input" required value="" size="25"/>
    </p>
    <?php
}


add_filter('woocommerce_registration_errors', 'custom_validate_extra_register_fields', 10, 3);
function custom_validate_extra_register_fields($errors, $username, $email) {
    if (empty($_POST['first_name'])) {
        $errors->add('first_name_error', 'First Name is required!');
    }
    if (empty($_POST['last_name'])) {
        $errors->add('last_name_error', 'Last Name is required!');
    }
    if (empty($_POST['display_name'])) {
        $errors->add('display_name_error', 'Display Name is required!');
    }
    return $errors;
}


add_action('woocommerce_created_customer', 'custom_save_extra_register_fields');
function custom_save_extra_register_fields($customer_id) {
    if (isset($_POST['first_name'])) {
        update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['first_name']));
    }
    if (isset($_POST['last_name'])) {
        update_user_meta($customer_id, 'last_name', sanitize_text_field($_POST['last_name']));
    }
    if (isset($_POST['display_name'])) {
        wp_update_user([
            'ID' => $customer_id,
            'display_name' => sanitize_text_field($_POST['display_name']),
        ]);
    }
}
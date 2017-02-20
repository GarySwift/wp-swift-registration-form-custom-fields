<?php
/**
 * Plugin Name:       WP Swift: Registration Form Custom Fields
 * Description:       Add custom input fields 'First Name' and 'Last Name' to the default WordPress registration page
 * Version:           1.0.0
 * Author:            Gary Swift
 * License:           GPL-2.0+
 * Text Domain:       registration-form-custom-fields
 */
class Registration_Form_Custom_Fields_Plugin {
    /*
     * Initializes the plugin.
     */
    public function __construct() {
        //1. Add a new form element...
        add_action( 'register_form', array( $this, 'myplugin_register_form') );
        //2. Add validation. In this case, we make sure first_name is required.
        add_filter( 'registration_errors', array( $this, 'myplugin_registration_errors'), 10, 3 );
        //3. Finally, save our extra registration user meta.
        add_action( 'user_register', array( $this, 'myplugin_user_register') );
    }
    /*
     * Add a new form elements
     */
    public function myplugin_register_form() {
        $first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
        $last_name = ( ! empty( $_POST['last_name'] ) ) ? trim( $_POST['last_name'] ) : '';
        ?>
        <p>
            <label for="first_name"><?php _e( 'First Name', 'mydomain' ) ?>
            <br />
            <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" /></label>
        </p>
        <p>
            <label for="last_name"><?php _e( 'Last Name', 'mydomain' ) ?>
            <br />
            <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( wp_unslash( $last_name ) ); ?>" size="25" /></label>
        </p>
        <?php
    }
    /*
     * Add validation. In this case, we make sure first_name and last_name are required.
     */
    public function myplugin_registration_errors( $errors, $sanitized_user_login, $user_email ) {
        if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
            $errors->add( 'first_name_error', __( '<strong>ERROR</strong>: You must include a first name.', 'mydomain' ) );
        }
        if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['last_name'] ) == '' ) {
            $errors->add( 'last_name_error', __( '<strong>ERROR</strong>: You must include a last name.', 'mydomain' ) );
        }
        return $errors;
    }
    /*
     * Save the extra registration user meta.
     */
    public function myplugin_user_register( $user_id ) {
        if ( ! empty( $_POST['first_name'] ) ) {
            update_user_meta( $user_id, 'first_name', trim( $_POST['first_name'] ) );
        }
        if ( ! empty( $_POST['last_name'] ) ) {
            update_user_meta( $user_id, 'last_name', trim( $_POST['last_name'] ) );
        }    
    }
}
// Initialize the plugin
$registration_form_custom_fields_plugin = new Registration_Form_Custom_Fields_Plugin();
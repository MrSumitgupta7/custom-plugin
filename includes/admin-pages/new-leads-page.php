<?php
// Ensure WordPress is loaded
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


// Check if the function doesn't already exist
if (!function_exists('custom_plugin_new_lead_page')) {
    /* Callback function to create new lead */
    function custom_plugin_new_lead_page() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if form is submitted
            $name = sanitize_text_field($_POST['name']);
            $email = sanitize_email($_POST['email']);
            $phone = sanitize_text_field($_POST['phone']);
            $location = sanitize_text_field($_POST['location']);
            $address = sanitize_text_field($_POST['address']);
            $status = sanitize_text_field($_POST['status']); // New line added for status field

            // Insert the new lead into the database
            global $wpdb;
            $table_name = $wpdb->prefix . 'custom_leads';
            $wpdb->insert(
                $table_name,
                array(
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'location' => $location,
                    'address' => $address,
                    'status' => $status // New line added for status field
                )
            );

            // Display success message
            add_action('admin_notices', 'custom_plugin_new_lead_success_notice');
        }
        ?>
        <div class="wrap custom-plugin-new-lead">
            <h1><?php esc_html_e('Create New Lead', 'custom-plugin'); ?></h1>
            <form method="post" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="name"><?php esc_html_e('Name:', 'custom-plugin'); ?></label></th>
                            <td><input type="text" id="name" name="name" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="email"><?php esc_html_e('Email:', 'custom-plugin'); ?></label></th>
                            <td><input type="email" id="email" name="email" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="phone"><?php esc_html_e('Phone:', 'custom-plugin'); ?></label></th>
                            <td><input type="text" id="phone" name="phone" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="location"><?php esc_html_e('Location:', 'custom-plugin'); ?></label></th>
                            <td><input type="text" id="location" name="location"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="address"><?php esc_html_e('Address:', 'custom-plugin'); ?></label></th>
                            <td><input type="text" id="address" name="address"></td>
                        </tr>
                        <tr> <!-- New row for status field -->
                            <th scope="row"><label for="status"><?php esc_html_e('Status:', 'custom-plugin'); ?></label></th>
                            <td>
                                <label><input type="radio" name="status" value="pending" checked> <?php esc_html_e('Pending', 'custom-plugin'); ?></label>
                                <label><input type="radio" name="status" value="completed"> <?php esc_html_e('Completed', 'custom-plugin'); ?></label>
                                <label><input type="radio" name="status" value="cancelled"> <?php esc_html_e('Cancelled', 'custom-plugin'); ?></label>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php esc_attr_e('Create Lead', 'custom-plugin'); ?>">
            </form>
        </div>
        <?php
    }
}


?>

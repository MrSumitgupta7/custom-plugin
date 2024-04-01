<?php
// Ensure WordPress is loaded
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Callback function to handle lead deletion
function custom_plugin_delete_lead() {
    if (isset($_POST['bulk_delete'])) {
        $leads_to_delete = $_POST['delete'];
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_leads';
        foreach ($leads_to_delete as $lead_id) {
            $wpdb->delete($table_name, array('id' => $lead_id));
        }
    }
}
add_action('admin_post_delete_lead', 'custom_plugin_delete_lead');

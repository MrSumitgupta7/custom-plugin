<?php
/* Add top-level menu */
function custom_plugin_add_menu() {
    add_menu_page(
        'Custom Plugin',    // Page Title
        'Custom Plugin',    // Menu Title
        'manage_options',   // Capability (who can access this menu)
        'custom-plugin',    // Menu Slug
        'custom_lead_page', // Callback function to display the page content
        'dashicons-admin-plugins', // Icon for the menu item
        70                // Position in the menu order
    );

    // Add submenu item to display all leads
    add_submenu_page(
        'custom-plugin',            // Parent Slug
        'View Leads',                // Page Title
        'View Leads',                // Menu Title
        'manage_options',           // Capability (who can access this menu)
        'custom-plugin-all-leads',  // Menu Slug
        'custom_plugin_all_leads_page' // Callback function to display all leads
    );

    // Add submenu item to create new leads
    add_submenu_page(
        'custom-plugin',            // Parent Slug
        'Create  Lead',          // Page Title
        'Create  Lead',          // Menu Title
        'manage_options',           // Capability (who can access this menu)
        'custom-plugin-new-lead',   // Menu Slug
        'custom_plugin_new_lead_page' // Callback function to create new lead
    );
}
add_action('admin_menu', 'custom_plugin_add_menu');


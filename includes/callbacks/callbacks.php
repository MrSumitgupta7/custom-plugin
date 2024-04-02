<?php
/* Callback function to display all leads */
function custom_plugin_all_leads_page() {
    // Code to display all leads goes here
    echo '<h1>All Leads Page</h1>';
}

/* Callback function to create new lead */
function custom_plugin_new_lead_page() {
    // Code to create new lead goes here
    echo '<h1>Create New Lead Page</h1>';
}



// Hook the menu function to WordPress admin menu action
add_action('admin_menu', 'custom_plugin_add_menu');
?>

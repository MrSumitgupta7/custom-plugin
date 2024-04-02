<?php
/**
 * Plugin Name:       custom plugin
 * Plugin URI:        https://example.com/plugins/the-basics/custom
 * Description:       Handles CRUD operations and creates a custom table with REST controller.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Sumit Gupta
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       custom-plugin
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Activation Hook
 *
 * This function is called when the plugin is activated.
 * It includes the database file and creates a custom table.
 */
function custom_plugin_activate() {
    require_once(plugin_dir_path(__FILE__) . 'includes/database.php');
    custom_plugin_create_table();
}
register_activation_hook(__FILE__, 'custom_plugin_activate');

/**
 * Enqueue Scripts and Styles
 *
 * This function enqueues CSS and JavaScript files.
 * It is hooked into both the wp_enqueue_scripts and admin_enqueue_scripts actions.
 */
function custom_plugin_enqueue_scripts() {
    // Enqueue CSS
    wp_enqueue_style('custom-plugin-css', plugin_dir_url(__FILE__) . 'assets/css/custom-plugin.css', array(), '1.0.0', 'all');

    // Enqueue JavaScript
    wp_enqueue_script('custom-plugin-js', plugin_dir_url(__FILE__) . 'assets/js/custom-plugin.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'custom_plugin_enqueue_scripts');
add_action('admin_enqueue_scripts', 'custom_plugin_enqueue_scripts');

/**
 * Deactivation Hook
 *
 * This function is called when the plugin is deactivated.
 * It executes cleanup tasks.
 */
function custom_plugin_deactivate() {
    // Code to execute on plugin deactivation
}
register_deactivation_hook(__FILE__, 'custom_plugin_deactivate');

/**
 * Include Menu Page Files
 *
 * This section includes the files for various admin menu pages.
 */
require_once(plugin_dir_path(__FILE__) . 'includes/admin-pages/menu-page.php');
require_once(plugin_dir_path(__FILE__) . 'includes/admin-pages/all-leads-page.php');
require_once(plugin_dir_path(__FILE__) . 'includes/admin-pages/new-leads-page.php');

?>

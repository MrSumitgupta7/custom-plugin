<?php
// Ensure WordPress is loaded
defined('ABSPATH') || exit;

// Callback function to handle lead creation (can be integrated elsewhere)
function custom_plugin_create_lead() {
    // Implement lead creation logic here (e.g., form submission handling, data validation, and database insertion)
    // ...
}

// Callback function to display all leads and handle search
function custom_plugin_all_leads_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_leads';

   // Handle bulk delete action
if (isset($_POST['bulk_delete']) && isset($_POST['delete'])) {
    $leads_to_delete = $_POST['delete'];
    foreach ($leads_to_delete as $lead_id) {
        $wpdb->delete($table_name, array('id' => $lead_id));
    }
}


    // Sorting
    $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'name';
    $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'DESC';
    $allowed_orderby = ['id', 'name', 'phone', 'email', 'address', 'status', 'location'];

    if (!in_array($orderby, $allowed_orderby)) {
        $orderby = 'name';
    }

    // Search functionality
    $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

    // Pagination
    $per_page = get_option('leads_per_page', 10); // Use screen option for leads per page
    $page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($page - 1) * $per_page;

    // Construct the SQL query with search parameters
    $query = $wpdb->prepare(
        "SELECT * FROM $table_name WHERE name LIKE %s OR phone LIKE %s OR email LIKE %s OR location LIKE %s OR address LIKE %s OR status LIKE %s ORDER BY $orderby $order LIMIT %d OFFSET %d",
        '%' . $search . '%',
        '%' . $search . '%',
        '%' . $search . '%',
        '%' . $search . '%',
        '%' . $search . '%',
        '%' . $search . '%',
        $per_page,
        $offset
    );
    $leads = $wpdb->get_results($query, ARRAY_A);

    // Total number of leads (consider search criteria)
    $total_leads = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE name LIKE %s OR phone LIKE %s OR email LIKE %s OR location LIKE %s OR address LIKE %s OR status LIKE %s",
        '%' . $search . '%',
        '%' . $search . '%',
        '%' . $search . '%',
        '%' . $search . '%',
        '%' . $search . '%',
        '%' . $search . '%'
    ));

    // Display the search form and leads table
    ?>
    <div class="wrap custom-plugin-all-leads">
        <h1><?php esc_html_e('Manage Leads', 'custom-plugin'); ?></h1>

        <form method="get">
            <input type="hidden" name="page" value="custom-plugin-leads">
            <input type="search" name="s" id="search-box" value="<?php echo esc_attr($search); ?>" placeholder="<?php esc_attr_e('Search', 'custom-plugin'); ?>">
            <button type="submit" class="button"><?php esc_html_e('Search', 'custom-plugin'); ?></button>
        </form>
        <form method="post" style="float: left;">
            <div class="alignleft actions">
                <select name="bulk_delete">
                    <option value=""><?php esc_html_e('Bulk Actions', 'custom-plugin'); ?></option>
                    <option value="delete"><?php esc_html_e('Delete', 'custom-plugin'); ?></option>
                </select>
                <input type="submit" name="do_action" id="do_action" class="button action" value="<?php esc_attr_e('Apply', 'custom-plugin'); ?>">
            </div>
        </form>
    </div>

    <form method="post">
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-cb check-column"><input type="checkbox" id="check-all"></th>
                    <th scope="col" class="manage-column column-id"><?php esc_html_e('ID', 'custom-plugin'); ?></th>
                    <th scope="col" class="manage-column column-name">
                        <a href="<?php echo esc_url(add_query_arg(['orderby' => 'name', 'order' => $order === 'DESC' && $orderby === 'name' ? 'ASC' : 'DESC'], admin_url('admin.php?page=custom-plugin-leads'))); ?>">
                            <span><?php esc_html_e('Name', 'custom-plugin'); ?></span>
                            <?php if ($orderby === 'name') { ?>
                                <span class="dashicons dashicons-arrow-<?php echo $order === 'DESC' ? 'down' : 'up'; ?>"></span>
                            <?php } ?>
                        </a>
                    </th>
                    <th scope="col" class="manage-column column-phone">
                        <a href="<?php echo esc_url(add_query_arg(['orderby' => 'phone', 'order' => $order === 'DESC' && $orderby === 'phone' ? 'ASC' : 'DESC'], admin_url('admin.php?page=custom-plugin-leads'))); ?>">
                            <span><?php esc_html_e('Phone', 'custom-plugin'); ?></span>
                            <?php if ($orderby === 'phone') { ?>
                                <span class="dashicons dashicons-arrow-<?php echo $order === 'DESC' ? 'down' : 'up'; ?>"></span>
                            <?php } ?>
                        </a>
                    </th>
                    <th scope="col" class="manage-column column-email">
                        <a href="<?php echo esc_url(add_query_arg(['orderby' => 'email', 'order' => $order === 'DESC' && $orderby === 'email' ? 'ASC' : 'DESC'], admin_url('admin.php?page=custom-plugin-leads'))); ?>">
                            <span><?php esc_html_e('Email', 'custom-plugin'); ?></span>
                            <?php if ($orderby === 'email') { ?>
                                <span class="dashicons dashicons-arrow-<?php echo $order === 'DESC' ? 'down' : 'up'; ?>"></span>
                            <?php } ?>
                        </a>
                    </th>
                    <th scope="col" class="manage-column column-address">
                        <a href="<?php echo esc_url(add_query_arg(['orderby' => 'address', 'order' => $order === 'DESC' && $orderby === 'address' ? 'ASC' : 'DESC'], admin_url('admin.php?page=custom-plugin-leads'))); ?>">
                            <span><?php esc_html_e('Address', 'custom-plugin'); ?></span>
                            <?php if ($orderby === 'address') { ?>
                                <span class="dashicons dashicons-arrow-<?php echo $order === 'DESC' ? 'down' : 'up'; ?>"></span>
                            <?php } ?>
                        </a>
                    </th>
                    <th scope="col" class="manage-column column-status">
                        <a href="<?php echo esc_url(add_query_arg(['orderby' => 'status', 'order' => $order === 'DESC' && $orderby === 'status' ? 'ASC' : 'DESC'], admin_url('admin.php?page=custom-plugin-leads'))); ?>">
                            <span><?php esc_html_e('Status', 'custom-plugin'); ?></span>
                            <?php if ($orderby === 'status') { ?>
                                <span class="dashicons dashicons-arrow-<?php echo $order === 'DESC' ? 'down' : 'up'; ?>"></span>
                            <?php } ?>
                        </a>    
                    </th>
                    <th scope="col" class="manage-column column-location">
                        <a href="<?php echo esc_url(add_query_arg(['orderby' => 'location', 'order' => $order === 'DESC' && $orderby === 'location' ? 'ASC' : 'DESC'], admin_url('admin.php?page=custom-plugin-leads'))); ?>">
                            <span><?php esc_html_e('Location', 'custom-plugin'); ?></span>
                            <?php if ($orderby === 'location') { ?>
                                <span class="dashicons dashicons-arrow-<?php echo $order === 'DESC' ? 'down' : 'up'; ?>"></span>
                            <?php } ?>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody id="the-list">
                <?php foreach ($leads as $lead) : ?>
                    <tr>
                        <th scope="row" class="check-column"><input type="checkbox" name="delete[]" value="<?php echo $lead['id']; ?>"></th>
                        <td><?php echo $lead['id']; ?></td>
                        <td><?php echo esc_html($lead['name']); ?></td>
                        <td><?php echo esc_html($lead['phone']); ?></td>
                        <td><?php echo esc_html($lead['email']); ?></td>
                        <td><?php echo esc_html($lead['address']); ?></td>
                        <td><?php echo esc_html($lead['status']); ?></td>
                        <td><?php echo esc_html($lead['location']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="tablenav">
            <div class="tablenav-pages">
                <?php
                $big = 999999999;
                echo paginate_links(array(
                    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'format' => '?paged=%#%',
                    'current' => $page,
                    'total' => ceil($total_leads / $per_page)
                ));
                ?>
            </div>
            <div class="alignleft actions">
                <select name="bulk_delete">
                    <option value=""><?php esc_html_e('Bulk Actions', 'custom-plugin'); ?></option>
                    <option value="delete"><?php esc_html_e('Delete', 'custom-plugin'); ?></option>
                </select>
                <input type="submit" name="do_action" id="do_action" class="button action" value="<?php esc_attr_e('Apply', 'custom-plugin'); ?>">
            </div>
        </div>
    </form>
    <?php
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

// Leads screen options
function custom_plugin_leads_screen_options() {
    $args = array(
        'label' => esc_html__('Leads Per Page', 'custom-plugin'),
        'default' => 10,
        'option_name' => 'leads_per_page'
    );
    add_screen_option('toplevel_page_custom-plugin-leads', $args);
}

// Register the menu and its callbacks
add_action('admin_menu', function() {
    add_menu_page(
        __('Leads', 'custom-plugin'), // Menu title
        __('Leads', 'custom-plugin'), // Menu label
        'manage_options', // Capability required
        'custom-plugin-leads', // Menu slug
        'custom_plugin_all_leads_page', // Callback function
        '', // Menu icon URL (optional)
        60 // Menu position
    );
    add_action('admin_post_custom_plugin_leads', 'custom_plugin_delete_lead'); // Handle bulk delete after form submission
});

// Add screen options for leads per page
add_action('admin_init', 'custom_plugin_leads_screen_options');

// Register scripts and styles for the leads page (optional)
function custom_plugin_leads_scripts() {
    wp_enqueue_script('custom-plugin-leads-script', plugin_dir_url(__FILE__) . 'assets/leads.js', array('jquery'), '1.0.0', true);
    wp_enqueue_style('custom-plugin-leads-style', plugin_dir_url(__FILE__) . 'assets/leads.css');
}
add_action('admin_enqueue_scripts', 'custom_plugin_leads_scripts');
?>

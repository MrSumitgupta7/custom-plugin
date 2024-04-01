<?php
// Ensure WordPress is loaded
defined('ABSPATH') || exit;

// Callback function to create new lead
function custom_plugin_new_lead_page() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if form is submitted
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        $location = sanitize_text_field($_POST['location']);
        $address = sanitize_text_field($_POST['address']);
        $status = sanitize_text_field($_POST['status']);

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
                'status' => $status
            )
        );

        // Display success message
        add_action('admin_notices', 'custom_plugin_new_lead_success_notice');
    }
    ?>

    <div class="wrap custom-plugin-new-lead">
        <h1><?php esc_html_e('Create New Lead', 'custom-plugin'); ?></h1>
        <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=custom-plugin-leads')); ?>">
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
                    <tr>
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

// Success notice callback function
function custom_plugin_new_lead_success_notice() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p><?php esc_html_e('New lead created successfully!', 'custom-plugin'); ?></p>
    </div>
    <?php
}

// Callback function to display all leads
function custom_plugin_all_leads_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'custom_leads';

    // Handle delete action
    if (isset($_POST['bulk_delete'])) {
        $leads_to_delete = $_POST['delete'];
        foreach ($leads_to_delete as $lead_id) {
            $wpdb->delete($table_name, array('id' => $lead_id));
        }
    }

    // Sorting
    $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : 'id';
    $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : 'DESC';
    $allowed_orderby = ['id', 'name', 'phone', 'email', 'address', 'status', 'location'];
    if (!in_array($orderby, $allowed_orderby)) {
        $orderby = 'id';
    }

    // Search functionality
    $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

    // Pagination
    $per_page = 10;
    $page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($page - 1) * $per_page;

    // Construct the SQL query
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

    // Total number of leads
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
        <h1><?php esc_html_e('All Leads', 'custom-plugin'); ?></h1>

        <!-- Search Form -->
        <form method="get">
            <input type="hidden" name="page" value="custom-plugin-leads">
            <input type="search" name="s" id="search-box" value="<?php echo esc_attr($search); ?>" placeholder="<?php esc_attr_e('Search', 'custom-plugin'); ?>">
            <button type="submit" class="button"><?php esc_html_e('Search', 'custom-plugin'); ?></button>
        </form>

        <!-- Leads Table -->
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
                        <th scope="col" class="manage-column column-phone"><?php esc_html_e('Phone', 'custom-plugin'); ?></th>
                        <th scope="col" class="manage-column column-email"><?php esc_html_e('Email', 'custom-plugin'); ?></th>
                        <th scope="col" class="manage-column column-address"><?php esc_html_e('Address', 'custom-plugin'); ?></th>
                        <th scope="col" class="manage-column column-status"><?php esc_html_e('Status', 'custom-plugin'); ?></th>
                        <th scope="col" class="manage-column column-location"><?php esc_html_e('Location', 'custom-plugin'); ?></th>
                        <!-- Add other table headers for additional columns here -->
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
                            <!-- Add other table cells for additional columns here -->
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
    </div>
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
        'label' => 'Leads Per Page',
        'default' => 10,
        'option' => 'leads_per_page'
    );
    add_screen_option('per_page', $args);
}

// Hooks
add_action('admin_menu', 'custom_plugin_lead_admin_menu');
add_action('admin_init', 'custom_plugin_leads_screen_options');
add_action('admin_post_delete_lead', 'custom_plugin_delete_lead');

// Function to add lead management page to admin menu
function custom_plugin_lead_admin_menu() {
    add_menu_page(
        __('Manage Leads', 'custom-plugin'),
        __('Manage Leads', 'custom-plugin'),
        'manage_options',
        'custom-plugin-leads',
        'custom_plugin_all_leads_page',
        'dashicons-businessman',
        6
    );


 

}

<?php

function register_custom_lead_routes() {
    register_rest_route('custom-lead/v1', '/leads', array(
        'methods' => 'GET',
        'callback' => 'get_all_leads',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        }
    ));

    register_rest_route('custom-lead/v1', '/leads/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_single_lead',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        }
    ));

    register_rest_route('custom-lead/v1', '/leads/(?P<id>\d+)', array(
        'methods' => 'PUT',
        'callback' => 'update_single_lead',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        }
    ));

    register_rest_route('custom-lead/v1', '/leads/(?P<id>\d+)', array(
        'methods' => 'DELETE',
        'callback' => 'delete_single_lead',
        'permission_callback' => function () {
            return current_user_can('manage_options');
        }
    ));
}
?>
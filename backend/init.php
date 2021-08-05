<?php

function hb_admin_scripts()
{

    wp_enqueue_script('vue', 'https://cdn.jsdelivr.net/npm/vue');
    wp_enqueue_script('vue-router', 'https://unpkg.com/vue-router');
    wp_enqueue_script('vue-input-tag', 'https://unpkg.com/vue-input-tag');
//    wp_enqueue_script('vue-table-dynamic', 'https://unpkg.com/vue-table-dynamic');
//    wp_enqueue_script('vue-datatable-light', 'https://unpkg.com/v-datatable-light');
    wp_enqueue_script('vue-axios', 'https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js');

}

add_action('admin_enqueue_scripts', 'hb_admin_scripts');
add_action('admin_menu', 'hb_menu');

function hb_menu()
{
    add_menu_page(
        __('Hotel Booking', 'xfor'),
        __('Hotel Booking', 'xfor'),
        8,
        'hb-console',
        'plugin_page',
        'dashicons-bank'
    );
}

function plugin_page()
{
    require_once VIEWS_DIR . 'home.php';
}

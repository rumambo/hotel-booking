<?php

function hb_admin_scripts()
{
    wp_enqueue_script('vue', 'https://cdn.jsdelivr.net/npm/vue');
    wp_enqueue_script('vue-router', 'https://unpkg.com/vue-router');
    wp_enqueue_script('vue-input-tag', 'https://unpkg.com/vue-input-tag');
    wp_enqueue_script('qs', 'https://unpkg.com/qs/dist/qs.js');
    wp_enqueue_script('axios', 'https://unpkg.com/axios');
    wp_enqueue_script('vuex', 'https://unpkg.com/vuex@3.0.1/dist/vuex.min.js');

    wp_enqueue_script('dx-scheduler', ASSETS_DIR . 'dx/dhtmlxscheduler.js');
    wp_enqueue_script('dx-scheduler-limit', ASSETS_DIR . 'dx/ext/dhtmlxscheduler_limit.js');
    wp_enqueue_script('dx-scheduler-collision', ASSETS_DIR . 'dx/ext/dhtmlxscheduler_collision.js');
    wp_enqueue_script('dx-scheduler-timeline', ASSETS_DIR . 'dx/ext/dhtmlxscheduler_timeline.js');
    wp_enqueue_script('dx-scheduler-editors', ASSETS_DIR . 'dx/ext/dhtmlxscheduler_editors.js');
    wp_enqueue_script('dx-scheduler-minical', ASSETS_DIR . 'dx/ext/dhtmlxscheduler_minical.js');
    wp_enqueue_script('dx-scheduler-tooltip', ASSETS_DIR . 'dx/ext/dhtmlxscheduler_tooltip.js');

    wp_enqueue_style('dx-scheduler', ASSETS_DIR . 'dx/dhtmlxscheduler_material.css');
    wp_enqueue_style('dx-styles', ASSETS_DIR . 'styles.css');

    wp_enqueue_script('np', 'https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js');
    wp_enqueue_style('np', 'https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css');

    wp_enqueue_script('main', ASSETS_DIR . 'main.js', [], false, true);

}

add_action('admin_enqueue_scripts', 'hb_admin_scripts');


function wpse_remove_footer()
{
    add_filter('admin_footer_text', '__return_false', 11);
    add_filter('update_footer', '__return_false', 11);
}

add_action('admin_init', 'wpse_remove_footer');


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

add_action('admin_menu', 'hb_menu');

function plugin_page()
{
    require_once VIEWS_DIR . 'home.php';
}

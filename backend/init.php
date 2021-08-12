<?php

function hb_admin_scripts()
{
    wp_enqueue_script('vue', ASSETS_DIR . 'vue.js');
    wp_enqueue_script('vue-router', ASSETS_DIR . 'vue-router.min.js');
    wp_enqueue_script('vue-input-tag', ASSETS_DIR . 'vueInputTag.umd.min.js');
    wp_enqueue_script('qs', ASSETS_DIR . 'qs.js');
    wp_enqueue_script('axios', ASSETS_DIR . 'axios.min.js');
    wp_enqueue_script('vuex', ASSETS_DIR . 'vuex.min.js');

    wp_enqueue_script('dx', ASSETS_DIR . 'dx/dhtmlxscheduler.js');
    wp_enqueue_script('dx-limit', ASSETS_DIR . 'dx/ext/dhtmlxscheduler_limit.js');
    wp_enqueue_script('dx-collision', ASSETS_DIR . 'dx/ext/dhtmlxscheduler_collision.js');
    wp_enqueue_script('dx-timeline', ASSETS_DIR . 'dx/ext/dhtmlxscheduler_timeline.js');
    wp_enqueue_script('dx-editors', ASSETS_DIR . 'dx/ext/dhtmlxscheduler_editors.js');
    wp_enqueue_script('dx-minical', ASSETS_DIR . 'dx/ext/dhtmlxscheduler_minical.js');
    wp_enqueue_script('dx-tooltip', ASSETS_DIR . 'dx/ext/dhtmlxscheduler_tooltip.js');

    wp_enqueue_style('dx', ASSETS_DIR . 'dx/dhtmlxscheduler_material.css');
    wp_enqueue_style('styles', ASSETS_DIR . 'backend.css');

    wp_enqueue_script('np', ASSETS_DIR . 'nprogress/nprogress.min.js');
    wp_enqueue_style('np', ASSETS_DIR . 'nprogress/nprogress.min.css');

    wp_enqueue_script('main', ASSETS_DIR . 'backend.js', [], false, true);
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

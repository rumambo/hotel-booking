<?php

function hb_admin_scripts()
{

    wp_enqueue_script('vue', 'https://cdn.jsdelivr.net/npm/vue');
    wp_enqueue_script('vue-router', 'https://unpkg.com/vue-router');
    wp_enqueue_script('vue-input-tag', 'https://unpkg.com/vue-input-tag');
//    wp_enqueue_script('vue-table-dynamic', 'https://unpkg.com/vue-table-dynamic');
//    wp_enqueue_script('vue-datatable-light', 'https://unpkg.com/v-datatable-light');
    wp_enqueue_script('qs', 'https://unpkg.com/qs/dist/qs.js');
    wp_enqueue_script('axios', 'https://unpkg.com/axios');
    wp_enqueue_script('vuex', 'https://unpkg.com/vuex@3.0.1/dist/vuex.min.js');


    wp_enqueue_script('dh-scheduler', ASSETS_DIR . 'dhtmlx/lib/dhtmlxScheduler/dhtmlxscheduler.js');
//    wp_enqueue_script('dh-scheduler', '/assets/dhtmlx/lib/dhtmlxScheduler/locale/locale_ru.js');
    wp_enqueue_script('dh-scheduler-limit', ASSETS_DIR . 'dhtmlx/lib/dhtmlxScheduler/ext/dhtmlxscheduler_limit.js');
    wp_enqueue_script('dh-scheduler-collision', ASSETS_DIR . 'dhtmlx/lib/dhtmlxScheduler/ext/dhtmlxscheduler_collision.js');
    wp_enqueue_script('dh-scheduler-timeline', ASSETS_DIR . 'dhtmlx/lib/dhtmlxScheduler/ext/dhtmlxscheduler_timeline.js');
    wp_enqueue_script('dh-scheduler-editors', ASSETS_DIR . 'dhtmlx/lib/dhtmlxScheduler/ext/dhtmlxscheduler_editors.js');
    wp_enqueue_script('dh-scheduler-minical', ASSETS_DIR . 'dhtmlx/lib/dhtmlxScheduler/ext/dhtmlxscheduler_minical.js');
    wp_enqueue_script('dh-scheduler-tooltip', ASSETS_DIR . 'dhtmlx/lib/dhtmlxScheduler/ext/dhtmlxscheduler_tooltip.js');
//    wp_enqueue_script('dh-scripts', ASSETS_DIR . 'dhtmlx/js/scripts.js');

    wp_enqueue_style('dh-scheduler', ASSETS_DIR . 'dhtmlx/lib/dhtmlxScheduler/dhtmlxscheduler.css');
    wp_enqueue_style('dh-styles', ASSETS_DIR . 'dhtmlx/css/styles.css');


    wp_enqueue_script('nprogress', 'https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js');
    wp_enqueue_style('nprogress', 'https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css');


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

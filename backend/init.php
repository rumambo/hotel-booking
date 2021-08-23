<?php
/**
 *=====================================================
 * @author Hotel Booking by Xfor.top
 *=====================================================
 **/


function xfor_admin_scripts()
{
    wp_enqueue_script('vue', XFOR_LIBS_DIR . 'vue.js');
    wp_enqueue_script('vue-router', XFOR_LIBS_DIR . 'vue-router.min.js');
    wp_enqueue_script('vue-input-tag', XFOR_LIBS_DIR . 'vueInputTag.umd.min.js');
    wp_enqueue_script('axios', XFOR_LIBS_DIR . 'axios.min.js');
    wp_enqueue_script('vuex', XFOR_LIBS_DIR . 'vuex.min.js');

    wp_enqueue_script('vue-image-upload', XFOR_LIBS_DIR . 'vue-upload-component.min.js');

    wp_enqueue_script('dx', XFOR_LIBS_DIR . 'dx/dhtmlxscheduler.js');
    wp_enqueue_script('dx-limit', XFOR_LIBS_DIR . 'dx/ext/dhtmlxscheduler_limit.js');
    wp_enqueue_script('dx-collision', XFOR_LIBS_DIR . 'dx/ext/dhtmlxscheduler_collision.js');
    wp_enqueue_script('dx-timeline', XFOR_LIBS_DIR . 'dx/ext/dhtmlxscheduler_timeline.js');
    wp_enqueue_script('dx-editors', XFOR_LIBS_DIR . 'dx/ext/dhtmlxscheduler_editors.js');
    wp_enqueue_script('dx-minical', XFOR_LIBS_DIR . 'dx/ext/dhtmlxscheduler_minical.js');
    wp_enqueue_script('dx-tooltip', XFOR_LIBS_DIR . 'dx/ext/dhtmlxscheduler_tooltip.js');

    wp_enqueue_style('dx', XFOR_LIBS_DIR . 'dx/dhtmlxscheduler_material.css');
    wp_enqueue_style('styles', XFOR_ASSETS_DIR . 'backend.css');

    wp_enqueue_script('np', XFOR_LIBS_DIR . 'nprogress/nprogress.min.js');
    wp_enqueue_style('np', XFOR_LIBS_DIR . 'nprogress/nprogress.min.css');

    wp_enqueue_script('main', XFOR_ASSETS_DIR . 'backend.js', [], false, true);
}
add_action('admin_enqueue_scripts', 'xfor_admin_scripts');


function wpse_remove_footer()
{
    add_filter('admin_footer_text', '__return_false', 11);
    add_filter('update_footer', '__return_false', 11);
}
add_action('admin_init', 'wpse_remove_footer');


function xfor_menu()
{
    add_menu_page(
        __('Hotel Booking', 'xfor'),
        __('Hotel Booking', 'xfor'),
        8,
        'hb-console',
        'plugin_page',
        'dashicons-bank',
        3
    );
}
add_action('admin_menu', 'xfor_menu');


function plugin_page()
{
    require_once XFOR_VIEWS_DIR . 'home.php';
}

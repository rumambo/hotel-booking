<?php

//function vue_settings()
//{
//    global $wpdb;
//
//    $settings = [];
//    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}hb_settings", ARRAY_A);
//    foreach ($result as $row) {
//        $settings[$row['param']] = $row['value'];
//    }
//    unset($result);
//
//    header('Content-Type: application/json');
//    echo json_encode($settings, JSON_UNESCAPED_UNICODE);
//    die();

//}

//add_action('wp_ajax_settings', 'vue_settings');
//add_action('wp_ajax_nopriv_settings', 'vue_settings');


function vue_get_data()
{
    global $wpdb;

    $data['data'] = [];
    $data['collections']['roomType'] = [];
    $data['collections']['roomStatus'] = [];
    $data['collections']['bookingStatus'] = [];
    $data['collections']['room'] = [];

    // settings
    $settings = [];
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}hb_settings", ARRAY_A);
    foreach ($result as $row) {
        $settings[$row['param']] = $row['value'];
    }
    unset($result);

    // data
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}hb_orders", ARRAY_A);
    foreach ($result as $row) {
        $row['is_paid'] = (int)$row['is_paid'] == 0 ? false : true;
        $data['data'][] = $row;
    }
    unset($result);

    // roomType
    $result = $wpdb->get_results("SELECT id, id as value, shortcode as label FROM {$wpdb->prefix}hb_room_types",
        ARRAY_A);
    foreach ($result as $row) {
        $data['collections']['roomType'][] = $row;
    }
    unset($result);

    // roomStatus
    $rs = explode(',', $settings['ROOM_STATUSES']);
    foreach ($rs as $i => $item) {
        $id = $i + 1;
        $data['collections']['roomStatus'][$i]['id'] = $id;
        $data['collections']['roomStatus'][$i]['value'] = $id;
        $data['collections']['roomStatus'][$i]['label'] = $item;
    }

    // bookingStatus
    $bs = explode(',', $settings['BOOKING_STATUS']);
    foreach ($bs as $i => $item) {
        $id = $i + 1;
        $data['collections']['bookingStatus'][$i]['id'] = $id;
        $data['collections']['bookingStatus'][$i]['value'] = $id;
        $data['collections']['bookingStatus'][$i]['label'] = $item;
    }

    // room
    $result = $wpdb->get_results("
        SELECT 
            id, id as value, 
            name as label, 
            type_id as type, 
            cleaner as status 
        FROM {$wpdb->prefix}hb_rooms
        WHERE `status` = 1
        ORDER BY name ASC, type_id ASC 
    ", ARRAY_A);
    foreach ($result as $row) {
        $data['collections']['room'][] = $row;
    }
    unset($result);

//    echo '<pre>';
//    print_R($data);
//    echo '</pre>';
//    die();

    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();

}

//add_action('wp_ajax_vue_get_data', 'vue_get_data');
//add_action('wp_ajax_nopriv_vue_get_data', 'vue_get_data');

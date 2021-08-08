<?php

/*function hb_get_data()
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
            id as value,
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

add_action('wp_ajax_hb_get_data', 'hb_get_data');
# add_action('wp_ajax_nopriv_hb_get_data', 'hb_get_data');*/


function hb_get_data()
{

    echo '{"data":[{"room":"2","start_date":"2021-08-29","end_date":"2021-09-02","id":"4","status":"2","is_paid":true,"fio":"Пупкин Иван Иванович","email":"2","tel":"3","noty":"4"},{"room":"2","start_date":"2021-08-07","end_date":"2021-08-13","id":"52","status":"1","is_paid":false,"fio":"asdf","email":"asdf@dasf.com","tel":"324324","noty":"asdf asdf, дней: 1, доп.услуги(Трансфер 1|Массаж|) , прибытие: 12:00, завтрак: yes, парковка: yes"},{"room":"2","start_date":"2019-11-19","end_date":"2019-11-21","id":"55","status":"1","is_paid":false,"fio":"авпвы","email":"dsf@fds.com","tel":"324324","noty":"ds fdsaf, дней: 2, доп.услуги(Трансфер 1|) , прибытие: 12:00, завтрак: yes, парковка: yes"}],"collections":{"roomType":[{"id":"3","value":"3","label":"STN"},{"id":"4","value":"4","label":"LUX"}],"roomStatus":[{"id":1,"value":1,"label":"Готов"},{"id":2,"value":2,"label":"Уборка"},{"id":3,"value":3,"label":"Грязный"}],"bookingStatus":[{"id":"1","value":"1","label":"Новый"},{"id":"2","value":"2","label":"Подтвердили"},{"id":"3","value":"3","label":"Прибыли"},{"id":"4","value":"4","label":"Освободили"}],"room":[{"id":"2","value":"2","label":"101","type":"3","status":"1"},{"id":"5","value":"5","label":"103","type":"3","status":"2"},{"id":"3","value":"3","label":"201","type":"4","status":"1"}]}}';
    die();

}

add_action('wp_ajax_hb_get_data', 'hb_get_data');
# add_action('wp_ajax_nopriv_hb_get_data', 'hb_get_data');


function hb_get_rooms()
{

    global $wpdb;

    // roomType
    $result = $wpdb->get_results("
        SELECT *
        FROM {$wpdb->prefix}hb_room_types",
        ARRAY_A);
    foreach ($result as $row) {
        $data['roomType'][$row['id']] = $row;
    }
    unset($result);

    // room
    $result = $wpdb->get_results("
        SELECT *
        FROM {$wpdb->prefix}hb_rooms
    ", ARRAY_A);
    foreach ($result as $row) {
        $type_title = $data['roomType'][$row['type_id']]['title'];

        $data['room'][$type_title.'|'.$row['type_id']][$row['id']]['id'] = $row['id'];
        $data['room'][$type_title.'|'.$row['type_id']][$row['id']]['name'] = $row['name'];
        $data['room'][$type_title.'|'.$row['type_id']][$row['id']]['type_id'] = $row['type_id'];
        $data['room'][$type_title.'|'.$row['type_id']][$row['id']]['type'] = $type_title;
        $data['room'][$type_title.'|'.$row['type_id']][$row['id']]['status'] = $row['status'];
        $data['room'][$type_title.'|'.$row['type_id']][$row['id']]['cleaner'] = $row['cleaner'];
    }
    unset($result);

//    echo '<pre>';
//    print_r($data['room']);
//    echo '</pre>';
//    die();

    header('Content-Type: application/json');
    echo json_encode($data['room'], JSON_UNESCAPED_UNICODE);
    die();

}

add_action('wp_ajax_hb_get_rooms', 'hb_get_rooms');
# add_action('wp_ajax_nopriv_hb_get_rooms', 'hb_get_rooms');


function hb_get_orders()
{

    global $wpdb;

    $result = $wpdb->get_results("
        SELECT *
        FROM {$wpdb->prefix}hb_orders
        ORDER BY id DESC",
        ARRAY_A);

    $data = [];
    foreach ($result as $row) {
        $data[] = $row;
    }
    unset($result);

//    echo '<pre>';
//    print_r($data);
//    echo '</pre>';
//    die();

    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();

}

add_action('wp_ajax_hb_get_orders', 'hb_get_orders');
# add_action('wp_ajax_nopriv_hb_get_orders', 'hb_get_orders');


function hb_get_room_types()
{

    global $wpdb;

    $result = $wpdb->get_results("
        SELECT *
        FROM {$wpdb->prefix}hb_room_types
        ORDER BY id ASC",
        ARRAY_A);

    $data = [];
    foreach ($result as $row) {
        $data[] = $row;
    }
    unset($result);

//    echo '<pre>';
//    print_r($data);
//    echo '</pre>';
//    die();

    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();

}

add_action('wp_ajax_hb_get_room_types', 'hb_get_room_types');
# add_action('wp_ajax_nopriv_hb_get_room_types', 'hb_get_room_types');


function hb_get_settings()
{

    global $wpdb;

    $result = $wpdb->get_results("
        SELECT *
        FROM {$wpdb->prefix}hb_settings
        ORDER BY id ASC",
        ARRAY_A);

    $data = [];
    foreach ($result as $row) {
        if (
            $row['param'] === 'ROOM_STATUSES' ||
            $row['param'] === 'BOOKING_STATUS' ||
            $row['param'] === 'COMFORTS_LIST' ||
            $row['param'] === 'SERVICES_LIST' ||
            $row['param'] === 'SETS_LIST'
        ) {
            $data[$row['param']] = array_map('trim', explode(',', $row['value']));
        } elseif ($row['param'] === 'CUR' || $row['param'] === 'PROMO') {
            $data[$row['param']] = json_decode($row['value'], true);
        } else {
            $data[$row['param']] = $row['value'];
        }

    }
    unset($result);

//    echo '<pre>';
//    print_r($data);
//    echo '</pre>';
//    die();

    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();

}

add_action('wp_ajax_hb_get_settings', 'hb_get_settings');
# add_action('wp_ajax_nopriv_hb_get_settings', 'hb_get_settings');


function hb_store_settings()
{
    global $wpdb;

    foreach ($_POST as $key => $value) {

        if (
            $key === 'ROOM_STATUSES' ||
            $key === 'BOOKING_STATUS' ||
            $key === 'COMFORTS_LIST' ||
            $key === 'SERVICES_LIST' ||
            $key === 'SETS_LIST'
        ) {

            $wpdb->update("{$wpdb->prefix}hb_settings",
                ['value' => implode(',', $value)],
                ['param' => $key]
            );
        } elseif ($key === 'CUR' || $key === 'PROMO') {
            $wpdb->update("{$wpdb->prefix}hb_settings",
                ['value' => json_encode($value)],
                ['param' => $key]
            );
        } else {
            $wpdb->update("{$wpdb->prefix}hb_settings",
                ['value' => $value],
                ['param' => $key]
            );
        }

    }

//    echo '<pre>';
//    print_r($_POST);
//    echo '</pre>';
//    die();

    echo hb_get_settings();
    die();
}

add_action('wp_ajax_hb_store_settings', 'hb_store_settings');
# add_action('wp_ajax_nopriv_hb_store_settings', 'hb_store_settings');


function hb_delete_order()
{
    global $wpdb;

    if (is_admin()) {
        $wpdb->delete("{$wpdb->prefix}hb_orders", ['id' => (int)$_POST['id']]);
    }

    echo 1;
    die();
}

add_action('wp_ajax_hb_delete_order', 'hb_delete_order');
# add_action('wp_ajax_nopriv_hb_delete_order', 'hb_delete_order');


function hb_add_room()
{
    global $wpdb;

    if (is_admin()) {
//        print_r($_POST);
        $wpdb->insert( "{$wpdb->prefix}hb_rooms", [
            'name' => $_POST['name'],
            'type_id' => (int)$_POST['type_id'],
            'cleaner' => $_POST['cleaner'],
            'status' => (int)$_POST['status'],
        ]);
    }

    echo hb_get_rooms();
    die();
}

add_action('wp_ajax_hb_add_room', 'hb_add_room');
# add_action('wp_ajax_nopriv_hb_add_room', 'hb_add_room');


function hb_delete_room()
{
    global $wpdb;

//    print_r($_POST);
//    die();

    if (is_admin()) {
        $wpdb->delete("{$wpdb->prefix}hb_rooms", ['id' => (int)$_POST['id']]);
    }

    echo 1;
    die();
}

add_action('wp_ajax_hb_delete_room', 'hb_delete_room');
# add_action('wp_ajax_nopriv_hb_delete_room', 'hb_delete_room');


function hb_switch_room_status()
{
    global $wpdb;

    $id = (int)$_POST['id'];
    $status = (int)$_POST['status'] === 1 ? 0 : 1;

//    print_r($_POST);
//    die();

    if (is_admin()) {

        $wpdb->update("{$wpdb->prefix}hb_rooms",
            ['status' => $status],
            ['id' => $id]
        );

    }

    echo $status;
    die();
}

add_action('wp_ajax_hb_switch_room_status', 'hb_switch_room_status');
# add_action('wp_ajax_nopriv_hb_switch_room_status', 'hb_switch_room_status');


function hb_update_room()
{
    global $wpdb;

    $id = (int)$_POST['id'];
    $cleaner = sanitize_text_field($_POST['cleaner']);

//    print_r($_POST);
//    die();

    if (is_admin()) {

        $wpdb->update("{$wpdb->prefix}hb_rooms",
            ['cleaner' => $cleaner],
            ['id' => $id]
        );

    }

    echo 1;
    die();
}

add_action('wp_ajax_hb_update_room', 'hb_update_room');
# add_action('wp_ajax_nopriv_hb_update_room', 'hb_update_room');

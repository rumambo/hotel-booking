<?php

function hb_get_data()
{
    global $wpdb;

    if (isset($_POST['ids'])) {

        $command = helper::parseRequestArguments($_POST);

        switch ($command['action']) {
            case 'inserted':
                helper::insertEvent($wpdb, $command['event']);
                break;
            case 'updated':
                helper::updateEvent($wpdb, $command['event']);
                break;
            case 'deleted':
                helper::deleteEvent($wpdb, $command['event']);
                break;
        }

        $data = [
            'action' => $command['action'],
            'tid' => $command['event']['id'],
            'sid' => $command['event']['id'],
        ];
        echo json_encode($data);
        die();
    }

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
        $data['collections']['roomStatus'][$i]['value'] = $item;
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

//    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();

}

add_action('wp_ajax_hb_get_data', 'hb_get_data');
# add_action('wp_ajax_nopriv_hb_get_data', 'hb_get_data');


/*function hb_get_data()
{
     $data = [
         "data" => [
             [
                 "room" => "1",
                 "start_date" => "2021-08-02",
                 "end_date" => "2021-08-23",
                 "text" => "A-12",
                 "id" => "1",
                 "status" => "1",
                 "is_paid" => "1"
             ],
         ],
         "collections" => [
             "roomType" => [
                 [
                     "id" => "1",
                     "value" => "1",
                     "label" => "1 bed"
                 ],
                 [
                     "id" => "2",
                     "value" => "2",
                     "label" => "2 beds"
                 ],
                 [
                     "id" => "3",
                     "value" => "3",
                     "label" => "3 beds"
                 ],
                 [
                     "id" => "4",
                     "value" => "4",
                     "label" => "4 beds"
                 ]
             ],
             "roomStatus" => [
                 [
                     "id" => "1",
                     "value" => "1",
                     "label" => "Ready"
                 ],
                 [
                     "id" => "2",
                     "value" => "2",
                     "label" => "Dirty"
                 ],
                 [
                     "id" => "3",
                     "value" => "3",
                     "label" => "Clean up"
                 ]
             ],
             "bookingStatus" => [
                 [
                     "id" => "1",
                     "value" => "1",
                     "label" => "New"
                 ],
                 [
                     "id" => "2",
                     "value" => "2",
                     "label" => "Confirmed"
                 ],
                 [
                     "id" => "3",
                     "value" => "3",
                     "label" => "Arrived"
                 ],
                 [
                     "id" => "4",
                     "value" => "4",
                     "label" => "Checked Out"
                 ]
             ],
             "room" => [
                 [
                     "id" => "1",
                     "value" => "1",
                     "label" => "101",
                     "type" => "1",
                     "status" => "1"
                 ],
                 [
                     "id" => "2",
                     "value" => "2",
                     "label" => "102",
                     "type" => "1",
                     "status" => "3"
                 ],
             ]
         ]
     ];
    echo json_encode($data);
    die();
}

add_action('wp_ajax_hb_get_data', 'hb_get_data');*/
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

        $data['room'][$type_title . '|' . $row['type_id']][$row['id']]['id'] = $row['id'];
        $data['room'][$type_title . '|' . $row['type_id']][$row['id']]['name'] = $row['name'];
        $data['room'][$type_title . '|' . $row['type_id']][$row['id']]['type_id'] = $row['type_id'];
        $data['room'][$type_title . '|' . $row['type_id']][$row['id']]['type'] = $type_title;
        $data['room'][$type_title . '|' . $row['type_id']][$row['id']]['status'] = $row['status'];
        $data['room'][$type_title . '|' . $row['type_id']][$row['id']]['cleaner'] = $row['cleaner'];
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
        $wpdb->insert("{$wpdb->prefix}hb_rooms", [
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


function hb_check()
{
    global $wpdb;

    $_POST = json_decode(file_get_contents('php://input'), true);

    $order_id = (int)$_POST['order_id'];
    $tel = str_replace(['+', ' ', ' ', ')', '('], '', strip_tags(trim($_POST['tel'])));

    $check = $wpdb->get_row("
        SELECT *
        FROM " . $wpdb->prefix . "hb_orders
        WHERE id = $order_id AND tel = $tel
    ");

    if (empty($check)) {
        echo 'Sorry, your order not find';
        die();
    }

//    print_r($check);
    $res = '
    <ul>
        <li>Arrival: ' . $check->start_date . '</li>
        <li>Departure: ' . $check->end_date . '</li>
        <li>Room: ' . $check->room . '</li>
        <li>Price per day: ' . $check->cost . '</li>
        <li>Guests: ' . $check->guest . '</li>
    </ul>
    ';
    echo $res;
    die();
}

add_action('wp_ajax_hb_check', 'hb_check');
add_action('wp_ajax_nopriv_hb_check', 'hb_check');


function hb_send()
{
    global $wpdb;

    $_POST = json_decode(file_get_contents('php://input'), true);

    $room_type_id = (int)$_POST['room_type_id'];
    $start_date = date('Y-m-d', strtotime(strip_tags(trim($_POST['datestart']))));
    $end_date = date('Y-m-d', strtotime(strip_tags(trim($_POST['dateend']))));
    $rooms_all_list = helper::getAvailableRoomsByRoomTypeId(
        $room_type_id, $start_date, $end_date
    );
    if (!count($rooms_all_list)) {
        die('Error not found available room');
    }

    $room_id = $rooms_all_list[0];

    $fullname = strip_tags(trim($_POST['fullname']));
    $tel = str_replace(['+', ' ', ' ', ')', '('], '', strip_tags(trim($_POST['tel'])));
    $email = strip_tags(trim($_POST['email']));
    $noty = strip_tags(trim($_POST['noty']));
    $status = 1;
    $is_paid = 0;
    $locale = strip_tags(trim($_POST['locale']));
    $cost = strip_tags(trim($_POST['cost']));
    $guest = strip_tags(trim($_POST['guest']));

    $noty .= ', days: ' . (int)$_POST['days'];
    if (count($_POST['add_services'])) {
        $services = '';
        foreach ($_POST['add_services'] as $item) {
            $services .= $item . '|';
        }
        $noty .= ', add.services(' . $services . ') ';
    }
    $noty .= ', arrival: ' . strip_tags(trim($_POST['arrival']));
    $noty .= ', breakfast: ' . strip_tags(trim($_POST['breakfast']));
    $noty .= ', parking: ' . strip_tags(trim($_POST['parking']));

    $wpdb->insert("{$wpdb->prefix}hb_orders", [
        'room' => $room_id,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'fullname' => $fullname,
        'email' => $email,
        'tel' => $tel,
        'noty' => $noty,
        'status' => $status,
        'is_paid' => $is_paid,
        'cost' => $cost,
        'guest' => $guest,
    ]);

    echo $wpdb->insert_id;
    die();
}

add_action('wp_ajax_hb_send', 'hb_send');
add_action('wp_ajax_nopriv_hb_send', 'hb_send');


function hb_get()
{
    global $wpdb;

    $start_date = '';
    $end_date = '';
    $promocode = 0;

    $data = [];
    $rooms_list = [];

    $rooms_all_list = helper::getAvailableRoomsList($start_date, $end_date);

//    echo '<pre>';
//    print_r($rooms_all_list);
//    echo '</pre>';
//    die();

    if (count($rooms_all_list) > 0) {
        $rooms_all_list = implode(',', $rooms_all_list);

        $result = $wpdb->get_results("
            SELECT *
            FROM " . $wpdb->prefix . "hb_rooms
            WHERE id IN ($rooms_all_list) AND status = 1
        ");
        foreach ($result as $row) {
            $rooms_list[$row->type_id][] = $row->id;
        }
        unset($result);
    }

//    echo '<pre>';
//    print_r($rooms_list);
//    echo '</pre>';
//    die();

    $comfort_data = [];
    $add_services_data = [];
    $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hb_settings");
    foreach ($result as $row) {
        if ( $row->param === 'SERVICES_LIST') {
            $add_services_data = explode(',', $row->value);
        }
        if ( $row->param === 'COMFORTS_LIST') {
            $comfort_data = explode(',', $row->value);
        }
    }
    unset($result);

//    print_r($add_services_data);
//    print_r($comfort_data);
//    die();

    $result = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "hb_room_types");
    foreach ($result as $row) {

        echo '<pre>';
        print_r($row);
        echo '</pre>';
        die();

        $images = [];
        $images_list = explode('|||', $row['images']);
        foreach ($images_list as $value) {
            $images[] = ['name' => FRONTEND_LINK . '/uploads/room_types/md/' . $value];
        }
        $capacity_data = json_decode($row['capacity'], true);
        $capacity_guest = [];
        $capacity_cost = [];
        foreach ($capacity_data as $guest => $cost) {
            if (!empty($cost)) {
                $cost = $promocode != 0 ? $cost - ($cost * $promocode) / 100 : $cost;
                $capacity_cost[] = $cost;
                $capacity_guest[] = $guest;
            }
        }
        $comfort_list = [];
        $comfort_list_data = explode(',', $row['comfort_list']);
        foreach ($comfort_list_data as $c_id) {
            $comfort_list['en'][] = $comfort_data[$c_id]['name_en'];
        }
//    ddd($comfort_list);
        $add_services_list = [];
        $add_services_list_data = explode(',', $row['add_services_list']);
        foreach ($add_services_list_data as $a_id) {
            $add_services_list['en'][] = $add_services_data[$a_id]['name_en'];
        }
//    ddd($add_services_list);

        $available_rooms = 0;
        if (isset($rooms_list[$row['id']]) && count($rooms_list[$row['id']])) {
            $available_rooms = count($rooms_list[$row['id']]);
        }

        $capacity = [
            'en' => $row['capacity_desc_en']
        ];

        $data[] = [
            'id' => $row['id'],
            'en' => [
                'name' => $row['name_en'],
                'desc' => $row['desc_en'],
            ],
            'images' => $images,
            'area' => $row['area'],
            'capacity' => $capacity,
            'capacity_guest' => $capacity_guest,
            'capacity_cost' => $capacity_cost,
            'available' => $available_rooms,
            'comfort_list' => $comfort_list,
            'add_services' => $add_services_list,
        ];
    }
    unset($result);

    echo '<pre>';
    print_r($data);
    echo '</pre>';
    die();

    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
}

add_action('wp_ajax_hb_get', 'hb_get');
add_action('wp_ajax_nopriv_hb_get', 'hb_get');

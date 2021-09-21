<?php
/**
 *=====================================================
 * @author Hotel Booking by Xfor.top
 *=====================================================
 **/

use XFOR_Helper\Helper;


/**
 * Dashboard
 */
function xfor_dashboard()
{
    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    if (isset($_POST['ids'])) {

        $command = Helper::parseRequestArguments($_POST);

        switch ($command['action']) {
            case 'inserted':
                Helper::insertEvent($wpdb, $command['event']);
                break;
            case 'updated':
                Helper::updateEvent($wpdb, $command['event']);
                break;
            case 'deleted':
                Helper::deleteEvent($wpdb, $command['event']);
                break;
            default:
                break;
        }

        echo json_encode([
            'action' => $command['action'],
            'tid' => $command['event']['id'],
            'sid' => $command['event']['id'],
        ]);
        die();
    }

    $data['data'] = [];
    $data['collections']['roomType'] = [];
    $data['collections']['roomStatus'] = [];
    $data['collections']['bookingStatus'] = [];
    $data['collections']['room'] = [];

    // settings
    $settings = [];
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}xfor_settings", ARRAY_A);
    foreach ($result as $row) {
        $settings[$row['param']] = $row['value'];
    }
    unset($result);

    // data
    $result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}xfor_orders", ARRAY_A);
    foreach ($result as $row) {
        $row['is_paid'] = (int)$row['is_paid'] !== 0;
        $data['data'][] = $row;
    }
    unset($result);

    // roomType
    $result = $wpdb->get_results("
        SELECT DISTINCT 
               a.id, a.id as value, 
               a.shortcode as label 
        FROM {$wpdb->prefix}xfor_room_types a, {$wpdb->prefix}xfor_rooms as b
        WHERE a.id = b.type_id AND b.status = 1
        ",
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
        $data['collections']['bookingStatus'][$i]['value'] = $item;
        $data['collections']['bookingStatus'][$i]['label'] = $item;
    }

    // room
    $result = $wpdb->get_results("
        SELECT name as value, name as label, type_id, cleaner as status
        FROM {$wpdb->prefix}xfor_rooms
        WHERE `status` = 1
        ORDER BY name ASC, type_id ASC
    ", ARRAY_A);
    foreach ($result as $row) {
        $data['collections']['room'][] = $row;
    }
    unset($result);

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
}

add_action('wp_ajax_xfor_dashboard', 'xfor_dashboard');


/**
 * Rooms
 */
function xfor_get_rooms()
{

    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    $data = [];
    $types = [];

    // roomType
    $result = $wpdb->get_results("
        SELECT id, title
        FROM {$wpdb->prefix}xfor_room_types",
        ARRAY_A);

    foreach ($result as $row) {
        $data[$row['title'] . '|' . $row['id']] = [];
        $types[$row['id']] = $row['title'];
    }
    unset($result);

    // room
    $result = $wpdb->get_results("
        SELECT * FROM {$wpdb->prefix}xfor_rooms
    ", ARRAY_A);

    foreach ($result as $row) {
        $type_title = $types[$row['type_id']];
        $title_and_id = $type_title . '|' . $row['type_id'];

        $data[$title_and_id][$row['id']]['id'] = $row['id'];
        $data[$title_and_id][$row['id']]['name'] = $row['name'];
        $data[$title_and_id][$row['id']]['type_id'] = $row['type_id'];
        $data[$title_and_id][$row['id']]['type'] = $type_title;
        $data[$title_and_id][$row['id']]['status'] = $row['status'];
        $data[$title_and_id][$row['id']]['cleaner'] = $row['cleaner'];
    }
    unset($result);

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
}

add_action('wp_ajax_xfor_get_rooms', 'xfor_get_rooms');


/**
 * Room Add
 */
function xfor_add_room()
{
    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    $_REQUEST = Helper::getRequest();

    check_ajax_referer('hotel_booking_by_xfor');

    $wpdb->insert("{$wpdb->prefix}xfor_rooms", [
        'name' => sanitize_text_field($_REQUEST['tmp']['name']),
        'type_id' => (int)$_REQUEST['tmp']['type_id'],
        'cleaner' => sanitize_text_field($_REQUEST['tmp']['cleaner']),
        'status' => (int)$_REQUEST['tmp']['status'],
    ]);

    xfor_get_rooms();
    die();
}

add_action('wp_ajax_xfor_add_room', 'xfor_add_room');


/**
 * Room Delete
 */
function xfor_delete_room()
{
    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    $_REQUEST = Helper::getRequest();

    check_ajax_referer('hotel_booking_by_xfor');

    $wpdb->delete("{$wpdb->prefix}xfor_rooms", ['id' => (int)$_REQUEST['id']]);

    echo 1;
    die();
}

add_action('wp_ajax_xfor_delete_room', 'xfor_delete_room');


/**
 * Room Switch status
 */
function xfor_switch_room_status()
{
    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    $_REQUEST = Helper::getRequest();

    check_ajax_referer('hotel_booking_by_xfor');

    $status = (int)$_REQUEST['status'] === 1 ? 0 : 1;

    $wpdb->update("{$wpdb->prefix}xfor_rooms",
        ['status' => $status],
        ['id' => (int)$_REQUEST['id']]
    );

    echo esc_html($status);
    die();
}

add_action('wp_ajax_xfor_switch_room_status', 'xfor_switch_room_status');


/**
 * Room Update
 */
function xfor_update_room()
{
    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    $_REQUEST = Helper::getRequest();

    check_ajax_referer('hotel_booking_by_xfor');

    $wpdb->update("{$wpdb->prefix}xfor_rooms",
        ['cleaner' => sanitize_text_field($_REQUEST['cleaner'])],
        ['id' => (int)$_REQUEST['id']]
    );

    echo 1;
    die();
}

add_action('wp_ajax_xfor_update_room', 'xfor_update_room');


/**
 * Orders
 */
function xfor_get_orders()
{

    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    $result = $wpdb->get_results("
        SELECT *
        FROM {$wpdb->prefix}xfor_orders
        ORDER BY id DESC",
        ARRAY_A);

    $data = [];
    foreach ($result as $row) {
        $data[] = $row;
    }
    unset($result);

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
}

add_action('wp_ajax_xfor_get_orders', 'xfor_get_orders');


/**
 * Order Delete
 */
function xfor_delete_order()
{
    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    $_REQUEST = Helper::getRequest();

    check_ajax_referer('hotel_booking_by_xfor');

    $wpdb->delete("{$wpdb->prefix}xfor_orders", ['id' => (int)$_REQUEST['id']]);

    echo 1;
    die();
}

add_action('wp_ajax_xfor_delete_order', 'xfor_delete_order');


/**
 * Room Types
 */
function xfor_get_room_types()
{

    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    $result = $wpdb->get_results("
        SELECT *
        FROM {$wpdb->prefix}xfor_room_types
        ORDER BY id ASC",
        ARRAY_A);

    $data = [];
    foreach ($result as $row) {
        if (empty($row['images'])) {
            $row['images'] = plugin_dir_url(__DIR__) . 'assets/images/no_photo.png';
        } else {
            $img = explode(',', $row['images']);
            $row['images'] = wp_get_attachment_image_src($img[0], 'thumbnail')[0];
        }
        $data[] = $row;
    }
    unset($result);

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();

}

add_action('wp_ajax_xfor_get_room_types', 'xfor_get_room_types');


/**
 * Room Type Add
 */
function xfor_add_room_type()
{
    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    $_REQUEST = Helper::getRequest();

    check_ajax_referer('hotel_booking_by_xfor');

    $capacity = [];
    foreach ($_REQUEST['tmp']['price'] as $key => $value) {
        $capacity[sanitize_text_field($key)] = sanitize_text_field($value);
    }

    $wpdb->insert("{$wpdb->prefix}xfor_room_types", [
        'title' => sanitize_text_field($_REQUEST['tmp']['title']),
        'area' => sanitize_text_field($_REQUEST['tmp']['area']),
        'capacity' => json_encode($capacity),
        'desc' => sanitize_text_field($_REQUEST['tmp']['desc']),
        'comfort_list' => sanitize_text_field(implode(',', $_REQUEST['tmp']['comfort_list'])),
        'add_services_list' => sanitize_text_field(implode(',', $_REQUEST['tmp']['add_services'])),
        'shortcode' => sanitize_text_field($_REQUEST['tmp']['shortcode']),
        'capacity_desc' => sanitize_text_field($_REQUEST['tmp']['capacity_text']),
    ]);

    xfor_get_room_types();
    die();
}

add_action('wp_ajax_xfor_add_room_type', 'xfor_add_room_type');


/**
 * Room Type Delete
 */
function xfor_del_room_type()
{
    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    $_REQUEST = Helper::getRequest();

    $id = (int)$_REQUEST['id'];
    if ($id === 0) {
        die();
    }

    check_ajax_referer('hotel_booking_by_xfor');

    $row = $wpdb->get_row("
        SELECT images 
        FROM {$wpdb->prefix}xfor_room_types
        WHERE type_id = $id
    ");
    if (!empty($row->images)) {
        $images_data = explode(',', $row->images);
        foreach ($images_data as $value) {
            wp_delete_attachment($value, true);
        }
    }
    $wpdb->delete("{$wpdb->prefix}xfor_rooms", ['type_id' => $id]);
    $wpdb->delete("{$wpdb->prefix}xfor_room_types", ['id' => $id]);
    $wpdb->delete("{$wpdb->prefix}xfor_room_types_images", ['type_id' => $id]);

    xfor_get_room_types();
    die();
}

add_action('wp_ajax_xfor_del_room_type', 'xfor_del_room_type');


/**
 * Room Type
 */
function xfor_get_room_type()
{
    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    $_REQUEST = Helper::getRequest();

    $id = (int)$_REQUEST['id'];
    if ($id === 0) {
        die();
    }

    check_ajax_referer('hotel_booking_by_xfor');

    $row = $wpdb->get_row("
        SELECT * 
        FROM {$wpdb->prefix}xfor_room_types 
        WHERE id = $id
    ");

    $capacity = json_decode($row->capacity, true);
    $price = [];
    foreach ($capacity as $key => $value) {
        $price[$key] = $value;
    }

    $images = [];
    if (!empty($row->images)) {
        $img = explode(',', $row->images);
        foreach ($img as $attach_id) {
            $images[] = wp_get_attachment_image_src($attach_id)[0];
        }
    }

    echo json_encode([
        'id' => $row->id,
        'shortcode' => $row->shortcode,
        'title' => $row->title,
        'images' => $images,
        'area' => $row->area,
        'capacity_text' => $row->capacity_desc,
        'add_services' => explode(',', $row->add_services_list),
        'price' => $price,
        'photos' => '',
        'comfort_list' => explode(',', $row->comfort_list),
        'desc' => $row->desc,
    ]);
    die();
}

add_action('wp_ajax_xfor_get_room_type', 'xfor_get_room_type');


/**
 * Room Type Edit
 */
function xfor_edit_room_type()
{
    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    $_REQUEST = Helper::getRequest();

    $id = (int)$_REQUEST['tmp']['id'];
    if ($id === 0) {
        die();
    }

    check_ajax_referer('hotel_booking_by_xfor');

    $capacity = [];
    foreach ($_REQUEST['tmp']['price'] as $key => $value) {
        $capacity[sanitize_text_field($key)] = sanitize_text_field($value);
    }


    $wpdb->update("{$wpdb->prefix}xfor_room_types", [
        'title' => sanitize_text_field($_REQUEST['tmp']['title']),
        'area' => sanitize_text_field($_REQUEST['tmp']['area']),
        'capacity' => json_encode($capacity),
        'desc' => sanitize_text_field($_REQUEST['tmp']['desc']),
        'comfort_list' => sanitize_text_field(implode(',', $_REQUEST['tmp']['comfort_list'])),
        'add_services_list' => sanitize_text_field(implode(',', $_REQUEST['tmp']['add_services'])),
        'shortcode' => sanitize_text_field($_REQUEST['tmp']['shortcode']),
        'capacity_desc' => sanitize_text_field($_REQUEST['tmp']['capacity_text']),
    ], ['id' => $id]);

    xfor_get_room_types();
    die();
}

add_action('wp_ajax_xfor_edit_room_type', 'xfor_edit_room_type');


/**
 * Image Upload
 */
function xfor_upload_images()
{
    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    check_ajax_referer('hotel_booking_by_xfor');

    $id = (int)$_REQUEST['id'];
    $images_data = [];
    $is_set = false;

    $row = $wpdb->get_row("
        SELECT images 
        FROM {$wpdb->prefix}xfor_room_types_images
        WHERE type_id = $id
    ");
    if (!empty($row->images)) {
        $images_data = explode(',', $row->images);
        $is_set = true;
    }

    $wordpress_upload_dir = wp_upload_dir();
    $i = 1;

    $photo = $_FILES['file'];
    $new_file_path = $wordpress_upload_dir['path'] . '/' . $photo['name'];
    $new_file_mime = mime_content_type($photo['tmp_name']);

    if (empty($photo)) {
        die(__('File is not selected.', 'hotel-booking-by-xfor'));
    }

    if ($photo['error']) {
        die($photo['error']);
    }

    if ($photo['size'] > wp_max_upload_size()) {
        die(__('It is too large than expected.', 'hotel-booking-by-xfor'));
    }

    if (!in_array($new_file_mime, get_allowed_mime_types())) {
        die(__('WordPress doesn\'t allow this type of uploads.', 'hotel-booking-by-xfor'));
    }

    if (($new_file_mime !== 'image/jpeg') && ($new_file_mime !== 'image/jpg') && ($new_file_mime !== 'image/png')) {
        die(__('Error type, please upload: jpg, jpeg, png', 'hotel-booking-by-xfor'));
    }

    while (file_exists($new_file_path)) {
        $i++;
        $new_file_path = $wordpress_upload_dir['path'] . '/' . $i . '_' . $photo['name'];
    }

    if (move_uploaded_file($photo['tmp_name'], $new_file_path)) {

        $upload_id = wp_insert_attachment([
            'guid' => $new_file_path,
            'post_mime_type' => $new_file_mime,
            'post_title' => preg_replace('/\.[^.]+$/', '', $photo['name']),
            'post_content' => '',
            'post_status' => 'inherit'
        ], $new_file_path);

        // wp_generate_attachment_metadata() won't work if you do not include this file
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        wp_update_attachment_metadata($upload_id, wp_generate_attachment_metadata($upload_id, $new_file_path));

        array_push($images_data, $upload_id);
        $images_data = implode(',', $images_data);

        // add
        if ($is_set === false) {

            $wpdb->insert("{$wpdb->prefix}xfor_room_types_images", [
                'images' => $images_data,
                'type_id' => $id,
            ]);

            if ($id !== 0) {
                $wpdb->update("{$wpdb->prefix}xfor_room_types", [
                    'images' => $images_data,
                ], ['id' => $id]);
            }

        } // update
        else {

            $wpdb->update("{$wpdb->prefix}xfor_room_types", [
                'images' => $images_data,
            ], ['id' => $id]);

            $wpdb->update("{$wpdb->prefix}xfor_room_types_images", [
                'images' => $images_data,
            ], ['type_id' => $id]);

        }

    }

    die();
}

add_action('wp_ajax_xfor_upload_images', 'xfor_upload_images');


/**
 * Image Delete
 */
function xfor_delete_image()
{
    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    $_REQUEST = Helper::getRequest();

    check_ajax_referer('hotel_booking_by_xfor');

    $id = (int)$_REQUEST['id'];
    $index = (int)$_REQUEST['index'];

    $images_data = [];
    $is_set = false;

    $row = $wpdb->get_row($wpdb->prepare("
        SELECT images 
        FROM {$wpdb->prefix}xfor_room_types_images
        WHERE type_id = %d
    ", $id));

    if (!empty($row->images)) {
        $images_data = explode(',', $row->images);
        $is_set = true;
    }

    wp_delete_attachment($images_data[$index], true);

    unset($images_data[$index]);

    if (empty($images_data)) {

        $wpdb->delete("{$wpdb->prefix}xfor_room_types_images",
            ['type_id' => $id]
        );
        if ($id !== 0) {
            $wpdb->update("{$wpdb->prefix}xfor_room_types", [
                'images' => '',
            ], ['id' => $id]);
        }

    } else {

        $images_data = implode(',', $images_data);

        // add
        if ($is_set === false) {

            $wpdb->update("{$wpdb->prefix}xfor_room_types_images", [
                'images' => $images_data,
            ], ['type_id' => $id]);


        } // update
        else {

            $wpdb->update("{$wpdb->prefix}xfor_room_types", [
                'images' => $images_data,
            ], ['id' => $id]);

            $wpdb->update("{$wpdb->prefix}xfor_room_types_images", [
                'images' => $images_data,
            ], ['type_id' => $id]);

        }

    }

    echo 1;
    die();
}

add_action('wp_ajax_xfor_delete_image', 'xfor_delete_image');


/**
 * Images
 */
function xfor_get_room_type_images()
{
    global $wpdb;

    $_REQUEST = Helper::getRequest();

    check_ajax_referer('hotel_booking_by_xfor');

    $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

    $row = $wpdb->get_row($wpdb->prepare("
        SELECT images 
        FROM {$wpdb->prefix}xfor_room_types_images
        WHERE type_id = %d
    ", $id));

    $images = [];
    if (!empty($row->images)) {
        $img = explode(',', $row->images);
        foreach ($img as $attach_id) {
            $images[] = wp_get_attachment_image_src($attach_id)[0];
        }
    }

    echo json_encode($images);
    die();
}

add_action('wp_ajax_xfor_get_room_type_images', 'xfor_get_room_type_images');
add_action('wp_ajax_nopriv_xfor_get_room_type_images', 'xfor_get_room_type_images');


/**
 * Settings
 */
function xfor_get_settings()
{
    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    $result = $wpdb->get_results("
        SELECT *
        FROM {$wpdb->prefix}xfor_settings
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

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    die();
}

add_action('wp_ajax_xfor_get_settings', 'xfor_get_settings');


/**
 * Settings Store
 */
function xfor_store_settings()
{
    global $wpdb;

    if (!current_user_can('xfor_options')) {
        die('Error! Not access');
    }

    $_REQUEST = Helper::getRequest();

    check_ajax_referer('hotel_booking_by_xfor');

    foreach ($_REQUEST['settings'] as $key => $value) {

        if (
            $key === 'ROOM_STATUSES' ||
            $key === 'BOOKING_STATUS' ||
            $key === 'COMFORTS_LIST' ||
            $key === 'SERVICES_LIST' ||
            $key === 'SETS_LIST'
        ) {
            $wpdb->update("{$wpdb->prefix}xfor_settings",
                ['value' => sanitize_text_field(implode(',', $value))],
                ['param' => $key]
            );
        } elseif ($key === 'CUR' || $key === 'PROMO') {
            $wpdb->update("{$wpdb->prefix}xfor_settings",
                ['value' => json_encode($value)],
                ['param' => $key]
            );
        } else {
            $wpdb->update("{$wpdb->prefix}xfor_settings",
                ['value' => sanitize_text_field($value)],
                ['param' => $key]
            );
        }

    }

    xfor_get_settings();
    die();
}

add_action('wp_ajax_xfor_store_settings', 'xfor_store_settings');


/**
 * Order Check
 */
function xfor_check()
{
    global $wpdb;

    $_POST = Helper::getRequest();

    $order_id = (int)$_POST['order_id'];
    $tel = str_replace(['+', ' ', ' ', ')', '('], '', strip_tags(trim(sanitize_text_field($_POST['tel']))));

    $check = $wpdb->get_row($wpdb->prepare("
        SELECT *
        FROM " . $wpdb->prefix . "xfor_orders
        WHERE id = %d AND tel = %s
    ", [$order_id, $tel]));

    if (empty($check)) {
        die(__('Sorry, your order not find', 'hotel-booking-by-xfor'));
    }

    echo '
    <ul>
        <li>' . __('Arrival', 'hotel-booking-by-xfor') . ': ' . $check->start_date . '</li>
        <li>' . __('Departure', 'hotel-booking-by-xfor') . ': ' . $check->end_date . '</li>
        <li>' . __('Room', 'hotel-booking-by-xfor') . ': ' . $check->room . '</li>
        <li>' . __('Price per day', 'hotel-booking-by-xfor') . ': ' . $check->cost . '</li>
        <li>' . __('Guests', 'hotel-booking-by-xfor') . ': ' . $check->guest . '</li>
    </ul>
    ';
    die();
}

add_action('wp_ajax_xfor_check', 'xfor_check');
add_action('wp_ajax_nopriv_xfor_check', 'xfor_check');


/**
 * Order Send
 */
function xfor_send()
{
    global $wpdb;

    $_POST = Helper::getRequest();

    $room_type_id = (int)$_POST['room_type_id'];
    $start_date = sanitize_text_field(trim($_POST['datestart']));
    $end_date = sanitize_text_field(trim($_POST['dateend']));

    $start_date = date('Y-m-d', \DateTime::createFromFormat('d.m.Y', $start_date)->getTimestamp());
    $end_date = date('Y-m-d', \DateTime::createFromFormat('d.m.Y', $end_date)->getTimestamp());

    $rooms_all_list = Helper::getAvailableRoomsByRoomTypeId(
        $room_type_id, $start_date, $end_date
    );
    if (!count($rooms_all_list)) {
        die(__('Error not found available room', 'hotel-booking-by-xfor'));
    }

    $room = array_shift($rooms_all_list);

    $fullname = sanitize_text_field(trim($_POST['fullname']));
    $tel = str_replace(['+', ' ', ' ', ')', '('], '', sanitize_text_field(trim($_POST['tel'])));
    $email = sanitize_email(trim($_POST['email']));
    $noty = sanitize_text_field(trim($_POST['noty']));
    $status = 'New';
    $is_paid = 0;
    $cost = sanitize_text_field(trim($_POST['cost']));
    $guest = sanitize_text_field(trim($_POST['guest']));

    $noty .= ', ' . __('days', 'hotel-booking-by-xfor') . ': ' . (int)$_POST['days'];
    if (count($_POST['add_services'])) {
        $services = '';
        foreach ($_POST['add_services'] as $item) {
            $services .= sanitize_text_field($item) . '|';
        }
        $noty .= ', ' . __('add.services', 'hotel-booking-by-xfor') . '(' . $services . ') ';
    }
    $noty .= ', ' . __('arrival', 'hotel-booking-by-xfor') . ': ' . sanitize_text_field(trim($_POST['arrival']));
    $noty .= ', ' . __('breakfast', 'hotel-booking-by-xfor') . ': ' . sanitize_text_field(trim($_POST['breakfast']));
    $noty .= ', ' . __('parking', 'hotel-booking-by-xfor') . ': ' . sanitize_text_field(trim($_POST['parking']));

    $wpdb->insert("{$wpdb->prefix}xfor_orders", [
        'room' => $room,
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
    $id = $wpdb->insert_id;

    wp_mail(
        get_option('admin_email'),
        __('New Order', 'hotel-booking-by-xfor') . ' ' . $id,
        __('New Order', 'hotel-booking-by-xfor') . ' ' . $id . __('check your site', 'hotel-booking-by-xfor') . '.'
    );

    echo esc_html($id);
    die();
}

add_action('wp_ajax_xfor_send', 'xfor_send');
add_action('wp_ajax_nopriv_xfor_send', 'xfor_send');


/**
 *  Available Room
 */
function xfor_get()
{
    global $wpdb;

    $search_data = Helper::preparePublicSearchData();

    $start_date = $search_data['start_date'];
    $end_date = $search_data['end_date'];
    $promocode = $search_data['promocode'];

    $res = [];
    $data = [];
    $rooms_list = [];

    $rooms_all_list = Helper::getAvailableRoomsList($start_date, $end_date);

    if (count($rooms_all_list) > 0) {
        $count_rooms_all_list = count($rooms_all_list);
        $stringPlaceholders = array_fill(0, $count_rooms_all_list, '%s');
        $placeholders = implode(', ', $stringPlaceholders);

        $result = $wpdb->get_results($wpdb->prepare("
            SELECT *
            FROM " . $wpdb->prefix . "xfor_rooms
            WHERE name IN ({$placeholders}) AND status = 1
        ", $rooms_all_list));
        foreach ($result as $row) {
            $rooms_list[$row->type_id][] = $row->name;
        }
        unset($result);
    }

    $result = $wpdb->get_results("
        SELECT * FROM " . $wpdb->prefix . "xfor_room_types
    ", ARRAY_A);
    foreach ($result as $row) {

        $images = Helper::getPublicImages($row['images']);
        $capacity_data = json_decode($row['capacity'], true);
        $capacity_guest = [];
        $capacity_cost = [];
        foreach ($capacity_data as $guest => $cost) {
            if (!empty($cost)) {
                $cost = $promocode !== 0 ? $cost - ($cost * $promocode) / 100 : $cost;
                $capacity_cost[] = $cost;
                $capacity_guest[] = $guest;
            }
        }

        $available_rooms = 0;
        if (isset($rooms_list[$row['id']])) {
            $available_rooms = count($rooms_list[$row['id']]);
        }

        $data[] = [
            'id' => $row['id'],
            'name' => $row['title'],
            'desc' => $row['desc'],
            'images' => $images,
            'area' => $row['area'],
            'capacity' => $row['capacity_desc'],
            'capacity_guest' => $capacity_guest,
            'capacity_cost' => $capacity_cost,
            'available' => $available_rooms,
            'comfort_list' => explode(',', $row['comfort_list']),
            'add_services' => explode(',', $row['add_services_list']),
        ];
    }
    unset($result);

    $res['rooms'] = $data;

    $result = $wpdb->get_row("
        SELECT `value`
        FROM {$wpdb->prefix}xfor_settings
        WHERE param = 'CUR'",
        ARRAY_A);

    $res['currencies'] = json_decode($result['value'], true);

    echo json_encode($res, JSON_UNESCAPED_UNICODE);
    die();
}

add_action('wp_ajax_xfor_get', 'xfor_get');
add_action('wp_ajax_nopriv_xfor_get', 'xfor_get');


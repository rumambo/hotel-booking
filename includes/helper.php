<?php

class helper
{

    public static function parseRequestArguments($request)
    {

        $id = $request['ids'];
        $prefix = $id . '_';

        $action = '';
        $event = [];

        foreach ($request as $key => $value) {

            $key = str_replace($prefix, '', $key);

            if ($key === '!nativeeditor_status') {
                $action = $value;
            } else {
                $event[$key] = $value;
            }
        }

        return [
            'action' => $action,
            'event' => $event,
        ];
    }


    public static function insertEvent($wpdb, $event)
    {
        $room = sanitize_text_field($event['room']);
        $start_date = sanitize_text_field($event['start_date']);
        $end_date = sanitize_text_field($event['end_date']);
        $fullname = sanitize_text_field($event['fullname']);
        $email = sanitize_text_field($event['email']);
        $tel = sanitize_text_field($event['tel']);
        $noty = sanitize_text_field($event['noty']);
        $status = (int)$event['status'];
        $is_paid = (int)$event['is_paid'];

        $wpdb->insert( "{$wpdb->prefix}hb_orders", [
            'room' => $room,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'fullname' => $fullname,
            'email' => $email,
            'tel' => $tel,
            'noty' => $noty,
            'status' => $status,
            'is_paid' => $is_paid,
        ]);

        return $wpdb->insert_id;
    }


    public static function updateEvent($wpdb, $event)
    {

        $id = (int)$event['id'];
        $room = sanitize_text_field($event['room']);
        $start_date = sanitize_text_field($event['start_date']);
        $end_date = sanitize_text_field($event['end_date']);
        $fullname = sanitize_text_field($event['fullname']);
        $email = sanitize_text_field($event['email']);
        $tel = sanitize_text_field($event['tel']);
        $noty = sanitize_text_field($event['noty']);
        $status = (int)$event['status'];
        $is_paid = (int)$event['is_paid'];

        $wpdb->update("{$wpdb->prefix}hb_orders",
            [
                'room' => $room,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'fullname' => $fullname,
                'email' => $email,
                'tel' => $tel,
                'noty' => $noty,
                'status' => $status,
                'is_paid' => $is_paid,
            ],
            ['id' => $id]
        );
    }

    public static function deleteEvent($wpdb, $event)
    {
        $wpdb->delete("{$wpdb->prefix}hb_orders", ['id' => $event['id']]);
    }



    public static function getAvailableRoomsByRoomTypeId (
        $room_type_id, $start_date, $end_date
    )
    {
        global $wpdb;

        $rooms_all_list = [];

        $rooms = $wpdb->get_results("
            SELECT *
            FROM " . $wpdb->prefix . "hb_rooms
            WHERE room_types_id = $room_type_id AND status = 1
            ORDER BY cleaner ASC
        ");
        foreach ($rooms as $row) {
            $rooms_all_list[] = $row->id;
        }
        unset($rooms);

        $rooms_not_available_list = [];

        $rooms = $wpdb->get_results("
            SELECT room as room_id
            FROM " . $wpdb->prefix . "hb_orders
            WHERE start_date >= $start_date  AND end_date <= $end_date
        ");
        foreach ($rooms as $row) {
            $rooms_not_available_list[] = $row->room_id;
        }
        unset($rooms);

        if (count($rooms_not_available_list)) {
            foreach ($rooms_all_list as $key => $item) {
                if (in_array($item, $rooms_not_available_list)) {
                    unset($rooms_all_list[$key]);
                }
            }
        }

        return $rooms_all_list;
    }


    function getAvailableRoomsList ( $start_date = '', $end_date = '' )
    {
        global $db;

        if ( empty($start_date) && empty($end_date) ) {
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime("+1 day"));
        }

//    echo $start_date;
//    echo '<br>';
//    echo $end_date;

        $rooms_all_list = [];
        $rooms = $db->query("
        SELECT
            *
        FROM " . PREFIX . "_rooms
        WHERE
            status = 1
        ORDER BY cleaner ASC
    ");
        foreach ($rooms as $row) {
            $rooms_all_list[] = $row['id'];
        }
        unset($rooms);
//    dd($rooms_all_list);

        $rooms_not_available_list = [];
        $rooms = $db->query("
        SELECT 
            room as room_id
        FROM 
            " . PREFIX . "_orders
        WHERE 
            start_date BETWEEN '{$start_date}' AND '{$end_date}' OR 
            end_date BETWEEN '{$start_date}' AND '{$end_date}' OR 
            (start_date <= '{$start_date}' AND end_date >= '{$end_date}')
    ");
        foreach ($rooms as $row) {
            $rooms_not_available_list[] = $row['room_id'];
        }
        unset($rooms);
//    ddd($rooms_not_available_list);

        if (count($rooms_not_available_list) != 0) {

            foreach ($rooms_all_list as $key => $item) {
                if (in_array($item, $rooms_not_available_list)) {
                    unset($rooms_all_list[$key]);
                }
            }
        }

        return $rooms_all_list;
    }


}

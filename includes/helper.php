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


}

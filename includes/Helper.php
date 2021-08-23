<?php
/**
 *=====================================================
 * @author Hotel Booking by Xfor.top
 *=====================================================
 **/
namespace XFOR_Helper;

class Helper
{

    public static function parseRequestArguments($request): array
    {
        $prefix = $request['ids'] . '_';
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


    public static function insertEvent($wpdb, $event): int
    {
        $wpdb->insert("{$wpdb->prefix}xfor_orders", [
            'room' => sanitize_text_field($event['room']),
            'start_date' => sanitize_text_field($event['start_date']),
            'end_date' => sanitize_text_field($event['end_date']),
            'fullname' => sanitize_text_field($event['fullname']),
            'email' => sanitize_email($event['email']),
            'tel' => sanitize_text_field($event['tel']),
            'noty' => sanitize_textarea_field($event['noty']),
            'status' => sanitize_text_field($event['status']),
            'is_paid' => (int)$event['is_paid'],
        ]);

        return $wpdb->insert_id;
    }


    public static function updateEvent($wpdb, $event)
    {
        $wpdb->update("{$wpdb->prefix}xfor_orders",
            [
                'room' => sanitize_text_field($event['room']),
                'start_date' => sanitize_text_field($event['start_date']),
                'end_date' => sanitize_text_field($event['end_date']),
                'fullname' => sanitize_text_field($event['fullname']),
                'email' => sanitize_email($event['email']),
                'tel' => sanitize_text_field($event['tel']),
                'noty' => sanitize_textarea_field($event['noty']),
                'status' => sanitize_text_field($event['status']),
                'is_paid' => (int)$event['is_paid'],
            ],
            ['id' => (int)$event['id']]
        );
    }

    public static function deleteEvent($wpdb, $event)
    {
        $wpdb->delete("{$wpdb->prefix}xfor_orders", ['id' => $event['id']]);
    }


    public static function getAvailableRoomsByRoomTypeId(
        $type_id,
        $start_date,
        $end_date
    ): array {
        global $wpdb;

        $rooms_list = [];

        $rooms = $wpdb->get_results("
            SELECT *
            FROM " . $wpdb->prefix . "xfor_rooms
            WHERE type_id = $type_id AND status = 1
            ORDER BY cleaner ASC
        ");
        foreach ($rooms as $row) {
            $rooms_list[$row->name] = $row->name;
        }
        unset($rooms);

        $rooms_busy_list = [];

        $rooms = $wpdb->get_results("
            SELECT room
            FROM " . $wpdb->prefix . "xfor_orders
            WHERE start_date >= '$start_date' AND end_date <= '$end_date'
        ");
        foreach ($rooms as $row) {
            $rooms_busy_list[$row->room] = $row->room;
        }
        unset($rooms);

        if (count($rooms_busy_list)) {
            foreach ($rooms_list as $key => $item) {
                if (in_array($item, $rooms_busy_list)) {
                    unset($rooms_list[$key]);
                }
            }
        }

        return $rooms_list;
    }


    public static function getAvailableRoomsList($start_date = '', $end_date = ''): array
    {
        global $wpdb;

        if (empty($start_date) && empty($end_date)) {
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime("+1 day"));
        }

        $rooms_list = [];
        $rooms = $wpdb->get_results("
            SELECT *
            FROM " . $wpdb->prefix . "xfor_rooms
            WHERE status = 1
            ORDER BY cleaner ASC
        ");
        foreach ($rooms as $row) {
            $rooms_list[$row->name] = $row->name;
        }
        unset($rooms);

        $rooms_busy_list = [];

        $rooms = $wpdb->get_results("
            SELECT room
            FROM " . $wpdb->prefix . "xfor_orders
            WHERE 
                start_date BETWEEN '$start_date' AND '$end_date' OR 
                end_date BETWEEN '$start_date' AND '$end_date' OR 
                (start_date <= '$start_date' AND end_date >= '$end_date')
        ");
        foreach ($rooms as $row) {
            $rooms_busy_list[$row->room] = $row->room;
        }
        unset($rooms);

        if (count($rooms_busy_list)) {
            foreach ($rooms_list as $key => $item) {
                if (in_array($item, $rooms_busy_list)) {
                    unset($rooms_list[$key]);
                }
            }
        }

        return $rooms_list;
    }


    public static function getRequest()
    {
        return json_decode(file_get_contents('php://input'), true);
    }


    public static function getPublicImages($data): array
    {
        $images = [];
        if (!empty($data)) {
            $img = explode(',', $data);
            foreach ($img as $attach_id) {
                $images[] = [
                    'name' => wp_get_attachment_image_src($attach_id, 'full')[0],
                ];
            }
        } else {
            $images[] = [
                'name' => plugin_dir_url(__DIR__) . 'assets/images/no_photo.png',
            ];
        }

        return $images;
    }

    public static function preparePublicSearchData(): array
    {
        global $wpdb;

        $start_date = '';
        $end_date = '';
        $promocode = 0;

        if (file_get_contents('php://input')) {
            $_POST = Helper::getRequest();

            if (isset($_POST['range']) && !empty($_POST['range'])) {
                $range = explode(' - ', sanitize_text_field($_POST['range']));
                $start_date = date('Y-m-d', strtotime($range[0]));
                $end_date = date('Y-m-d', strtotime($range[1]));
            }

            if (isset($_POST['promocode']) && !empty($_POST['promocode'])) {
                $promocode = sanitize_text_field(trim($_POST['promocode']));
                $settings_promo = $wpdb->get_row("
                SELECT `value`
                FROM " . $wpdb->prefix . "xfor_settings
                WHERE param = 'PROMO'
            ");

                $settings_promo = json_decode($settings_promo->value, true);
                foreach ($settings_promo as $value) {
                    if ($value[0] === $promocode && $value[2] === 1) {
                        $promocode = (float)$value[1];
                    }
                }
            }
        }

        return [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'promocode' => $promocode,
        ];
    }

}

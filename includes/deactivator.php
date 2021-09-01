<?php
/**
 *=====================================================
 * @author Hotel Booking by Xfor.top
 *=====================================================
 **/

namespace XFOR_HB_Deactivator;

class HotelBookingDeactivator
{

    public static function deactivate()
    {

        global $wpdb;

        $admin = get_role('administrator');
        $admin->remove_cap('xfor_options');

        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}xfor_orders");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}xfor_rooms");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}xfor_room_types");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}xfor_room_types_images");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}xfor_settings");

    }

}

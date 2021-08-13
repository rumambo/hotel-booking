<?php

class HotelBooking_Activator
{

    public static function activate(): void
    {

        $admin = get_role('administrator');
        $admin->add_cap('hb_options');

        // create tables
        global $wpdb;

        $collate = '';

        if ($wpdb->has_cap('collation')) {
            if (!empty($wpdb->charset)) {
                $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
            }

            if (!empty($wpdb->collate)) {
                $collate .= " COLLATE $wpdb->collate";
            }
        }

        $table_schema = [

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hb_orders` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `room` tinyint(4) DEFAULT NULL,
                `start_date` date DEFAULT NULL,
                `end_date` date DEFAULT NULL,
                `fullname` varchar(250) DEFAULT NULL,
                `email` varchar(100) DEFAULT NULL,
                `tel` varchar(6) DEFAULT NULL,
                `noty` varchar(255) DEFAULT NULL,
                `status` tinyint(4) DEFAULT NULL,
                `is_paid` tinyint(4) DEFAULT NULL,
                `locale` varchar(2) DEFAULT NULL,
                `cost` varchar(50) DEFAULT NULL,
                `guest` varchar(50) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

            "INSERT INTO `{$wpdb->prefix}hb_orders` (`room`, `start_date`, `end_date`, `fullname`, `email`, `tel`, `noty`, `status`, `is_paid`, `locale`, `cost`, `guest`) VALUES
                 (2, '2021-09-19', '2021-08-25', 'John Dou', '2', '3', '4', 2, 1, '', '', '');",

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hb_rooms` (
                `id` tinyint(4) NOT NULL AUTO_INCREMENT,
                `name` varchar(250) DEFAULT NULL,
                `type_id` smallint(6) DEFAULT NULL,
                `status` tinyint(4) DEFAULT NULL,
                `cleaner` varchar(250) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

            "INSERT INTO `{$wpdb->prefix}hb_rooms` (`name`, `type_id`, `status`, `cleaner`) VALUES
                    (101, 1, 1, 'Ready'),
                    (201, 2, 1, 'Ready'),
                    (102, 1, 1, 'Dirty'),
                    (103, 1, 1, 'Cleaning');",

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hb_room_types` (
                `id` tinyint(4) NOT NULL AUTO_INCREMENT,
                `title` varchar(250) DEFAULT NULL,
                `images` text DEFAULT NULL,
                `area` tinyint(4) DEFAULT NULL,
                `capacity` varchar(250) DEFAULT NULL,
                `desc` text DEFAULT NULL,
                `comfort_list` varchar(250) DEFAULT NULL,
                `add_services_list` varchar(250) DEFAULT NULL,
                `shortcode` varchar(250) DEFAULT NULL,
                `capacity_desc` varchar(250) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

            "INSERT INTO `{$wpdb->prefix}hb_room_types` (`title`, `images`, `area`, `capacity`, `desc`, `comfort_list`, `add_services_list`, `shortcode`, `capacity_desc`) VALUES
                    ('Standart room', '', 32, '{\"1\":\"1200\",\"1 + 1\":\"\",\"1 + 2\":\"1600\",\"1 + 3\":\"1800\"}', 'The standard double rooms with a double bed or twin beds are simple and functional, tastefully furnished. The rooms offer views of the quiet courtyard.', 'Wifi,Safe,Slippers,Bathrobe,Conditioner,TV,Balcony,Hair dryer,Refrigerator', 'Transfer,Massage,Dinner,Supper', 'STN', '1 man 2 add place'),
('De luxe suite', '', 64, '{\"1\":\"1300\",\"1 + 1\":\"\",\"1 + 2\":\"\",\"1 + 3\":\"\"}', 'Описание анг', 'Wifi,Slippers,Conditioner,TV,Refrigerator,Balcony', 'Transfer,Dinner', 'LUX', '2 man 2 add place');",


            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hb_room_types_images` (
                `id` tinyint(4) NOT NULL AUTO_INCREMENT,
                `images` text DEFAULT NULL,
                `type_id` tinyint(4) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",


            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hb_settings` (
                `id` tinyint(4) NOT NULL AUTO_INCREMENT,
                `param` varchar(20) DEFAULT NULL,
                `value` text DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

            "INSERT INTO `{$wpdb->prefix}hb_settings` (`param`, `value`) VALUES
                 ('ROOM_STATUSES', 'Ready,Cleaning,Dirty'),                  
                 ('BOOKING_STATUS', 'New,Confirmed,Arrived,Freed'),
                 ('COMFORTS_LIST', 'Wifi,Conditioner,Refrigerator,Safe,Bathrobe,Hair dryer,Slippers,TV,Balcony'),
                 ('SERVICES_LIST', 'Transfer, Massage, Dinner, Supper'),
                 ('SETS_LIST', '1,1 + 1,1 + 2,1 + 3'), 
                 ('IMG_LARGE', 1000), 
                 ('IMG_SMALL', 100), 
                 ('IMG_MEDIUM', 500),
                 ('CUR', '[[\"USD\",\"$\",\"1.00\"],[\"RUB\",\"₽\",\"38.00\"],[\"UAH\",\"₴\",\"28.00\"]]'),
                 ('PROMO', '[[\"TEST1\", 10, 1]]');",

        ];

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        foreach ($table_schema as $table) {
            dbDelta($table);
        }


    }

}

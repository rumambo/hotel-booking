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

            "INSERT INTO `{$wpdb->prefix}hb_orders` (`room`, `start_date`, `end_date`, `fio`, `email`, `tel`, `noty`, `status`, `is_paid`, `locale`, `cost`, `guest`) VALUES
                 (2, '2019-09-29', '2019-10-05', 'Пупкин Иван Иванович', '2', '3', '4', 2, 1, '', '', ''),
(2, '2019-10-05', '2019-10-06', 'asdf', 'asdf@dasf.com', '324324', 'asdf asdf, дней: 1, доп.услуги(Трансфер 1|Массаж|) , прибытие: 12:00, завтрак: yes, парковка: yes', 1, 0, 'ru', '1200', '1'),
(2, '2019-10-30', '2019-10-31', 'Тест', 'dgdf@sdf.com', '23424', 'dfsd, дней: 1, доп.услуги(Трансфер 1|) , прибытие: 13:00, завтрак: yes, парковка: yes', 1, 0, 'ru', '1600', '1 + 2'),
(3, '2019-10-08', '2019-10-11', 'dsf', 'gfdg@fsd.com', '535', 'dsff', 2, 1, '', '', ''),
(2, '2019-11-19', '2019-11-21', 'авпвы', 'dsf@fds.com', '324324', 'ds fdsaf, дней: 2, доп.услуги(Трансфер 1|) , прибытие: 12:00, завтрак: yes, парковка: yes', 1, 0, 'ru', '1080', '1');",

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hb_rooms` (
                `id` tinyint(4) NOT NULL AUTO_INCREMENT,
                `name` varchar(250) DEFAULT NULL,
                `type_id` smallint(6) DEFAULT NULL,
                `status` tinyint(4) DEFAULT NULL,
                `cleaner` varchar(250) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

            "INSERT INTO `{$wpdb->prefix}hb_rooms` (`name`, `type_id`, `status`, `cleaner`) VALUES
                    (101, 1, 0, 1),
                    (201, 2, 1, 1),
                    (102, 1, 0, 1),
                    (103, 1, 1, 2);",

            "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}hb_room_types` (
                `id` tinyint(4) NOT NULL AUTO_INCREMENT,
                `title` varchar(250) DEFAULT NULL,
                `images` varchar(250) DEFAULT NULL,
                `area` tinyint(4) DEFAULT NULL,
                `capacity` varchar(250) DEFAULT NULL,
                `desc` text DEFAULT NULL,
                `comfort_list` varchar(250) DEFAULT NULL,
                `add_services_list` varchar(250) DEFAULT NULL,
                `shortcode` varchar(3) DEFAULT NULL,
                `capacity_desc` varchar(250) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) $collate;",

            "INSERT INTO `{$wpdb->prefix}hb_room_types` (`title`, `images`, `area`, `capacity`, `desc`, `comfort_list`, `add_services_list`, `shortcode`, `capacity_desc`) VALUES
                    ('Standart room', '', 32, '{\"1\":\"1200\",\"1 + 1\":\"\",\"1 + 2\":\"1600\",\"1 + 3\":\"1800\"}', '<p>The standard double rooms with a double bed or twin beds are simple and functional, tastefully furnished. The rooms offer views of the quiet courtyard.</p>', '5,6,7,8,9,10,11,12,13,14,15', '2,3', 'STN', '1 man 2 доп. места'),
('De luxe suite', '', 64, '{\"1\":\"1300\",\"1 + 1\":\"\",\"1 + 2\":\"\",\"1 + 3\":\"\"}', 'Описание анг', '5,6,7,8,9,10,11,12,13,14,15', '2,3,4,5', 'LUX', '2 man 2 доп. места');",


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

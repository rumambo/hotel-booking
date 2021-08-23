<?php
/*
Plugin Name: Hotel Booking by Xfor
Plugin URI: https://github.com/utz0r2/hotel-booking
Author: Igor Veselov
Text Domain: hotel-booking
Domain Path: hotel-booking
Description: Plugin Hotel booking. Ideal solution for creating your own hotel's booking system.
Version: 0.1.1
Author URI: https://xfor.top/en
*/

use XFOR_HB_Activator\HotelBOokingActivator;
use XFOR_HB_Deactivator\HotelBookingDeactivator;

if (!defined('ABSPATH')) {
    exit();
}

define('XFOR_INC_DIR', plugin_dir_path(__FILE__) . 'includes/');
define('XFOR_CLASSES_DIR', plugin_dir_path(__FILE__) . 'backend/classes/');
define('XFOR_VIEWS_DIR', plugin_dir_path(__FILE__) . 'backend/views/');
define('XFOR_ASSETS_DIR', plugin_dir_url(__FILE__) . 'assets/');
define('XFOR_LIBS_DIR', plugin_dir_url(__FILE__) . 'assets/libs/');

function xfor_activate()
{
    require_once XFOR_INC_DIR . 'activator.php';
    HotelBOokingActivator::activate();
}

register_activation_hook(__FILE__, 'xfor_activate');

function xfor_deactivate()
{
    require_once XFOR_INC_DIR . 'deactivator.php';
    HotelBookingDeactivator::deactivate();
}

register_deactivation_hook(__FILE__, 'xfor_deactivate');

include XFOR_INC_DIR . 'helper.php';
include XFOR_INC_DIR . 'ajax.php';

if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . '/backend/init.php';
} else {
    require_once plugin_dir_path(__FILE__) . '/public/init.php';
}



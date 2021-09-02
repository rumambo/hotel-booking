<?php
add_thickbox();
?>
<div id="hotel_bookin_xfor">
    <h2 class="nav-tab-wrapper">
        <router-link class="nav-tab" exact-active-class="nav-tab-active" to="/">
            <span class="dashicons dashicons-calendar-alt"></span> <?php _e('Dashboard', 'hotel-booking-by-xfor') ?>
        </router-link>
        <router-link class="nav-tab" active-class="nav-tab-active" to="/orders">
            <span class="dashicons dashicons-cart"></span> <?php _e('Orders', 'hotel-booking-by-xfor') ?>
        </router-link>
        <router-link class="nav-tab" active-class="nav-tab-active" to="/rooms">
            <span class="dashicons dashicons-building"></span> <?php _e('Rooms', 'hotel-booking-by-xfor') ?>
        </router-link>
        <router-link class="nav-tab" active-class="nav-tab-active" to="/room_types">
            <span class="dashicons dashicons-tag"></span> <?php _e('Room Types', 'hotel-booking-by-xfor') ?>
        </router-link>
        <router-link class="nav-tab" active-class="nav-tab-active" to="/settings">
            <span class="dashicons dashicons-admin-settings"></span> <?php _e('Settings', 'hotel-booking-by-xfor') ?>
        </router-link>
    </h2>
    <div v-cloak id="main-content">
        <router-view></router-view>
        <div id="block"></div>
    </div>
</div>



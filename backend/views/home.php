<?php
add_thickbox();
?>
<style>
    .vue-input-tag-wrapper .new-tag {
        margin-bottom: 0;
        padding: 0;
    }

    .row {
        display: flex;
        margin: 0 -5px;
    }

    .column {
        flex: 50%;
        margin: 0 5px;
    }

    a:focus, input[type=text]:focus {
        box-shadow: none;
    }

    [v-cloak] {
        display: none;
    }
</style>

<div id="app">
    <h2 class="nav-tab-wrapper">
        <router-link class="nav-tab" exact-active-class="nav-tab-active" to="/">
            <span class="dashicons dashicons-calendar-alt"></span> Dashboard
        </router-link>
        <router-link class="nav-tab" active-class="nav-tab-active" to="/orders">
            <span class="dashicons dashicons-cart"></span> Orders
        </router-link>
        <router-link class="nav-tab" active-class="nav-tab-active" to="/rooms">
            <span class="dashicons dashicons-building"></span> Rooms
        </router-link>
        <router-link class="nav-tab" active-class="nav-tab-active" to="/room_types">
            <span class="dashicons dashicons-tag"></span> Room Types
        </router-link>
        <router-link class="nav-tab" active-class="nav-tab-active" to="/settings">
            <span class="dashicons dashicons-admin-settings"></span> Settings
        </router-link>
    </h2>
    <div v-cloak style="margin-right:10px;">
        <router-view></router-view>
    </div>
</div>



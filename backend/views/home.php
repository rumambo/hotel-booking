<style>
    body {background:#fff;}
    [v-cloak] {
        display: none;
    }</style>
<div id="app">
    <h2 class="nav-tab-wrapper">
        <router-link class="nav-tab" exact-active-class="nav-tab-active" to="/">Dashboard</router-link>
        <router-link class="nav-tab" active-class="nav-tab-active" to="/room_types">Room Types</router-link>
        <router-link class="nav-tab" active-class="nav-tab-active" to="/rooms">Rooms</router-link>
        <router-link class="nav-tab" active-class="nav-tab-active" to="/orders">Orders</router-link>
        <router-link class="nav-tab" active-class="nav-tab-active" to="/settings">Settings</router-link>
    </h2>
    <div v-cloak style="padding-right:10px;">
        <router-view></router-view>
    </div>
</div>

<script>
const Dashboard = {template: '<div>Dashboard</div>'}
const RoomTypes = {template: '<div>Room Types</div>'}
const Rooms = {
    data() {
        return {
            rooms: {},
        };
    },
    mounted() {

        axios
            .get( ajaxurl + '?action=test')
            .then(response => (this.rooms = response.data))
            .catch(error => console.log(error));
    },
    template: `<div>Rooms</div>`
}
const Orders = {
    template: `<div><br/>
<table class="widefat" style="background:#f7f7f7;">
	<thead>
	<tr>
		<th class="row-title">Table header cell #1</th>
		<th>Table header cell #2</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td class="row-title"><label for="tablecell">Table Cell #1, with label</label></td>
		<td>Table Cell #2</td>
	</tr>
	<tr class="alternate">
		<td class="row-title"><label for="tablecell">Table Cell #3, with label and class <code>alternate</code></label></td>
		<td>Table Cell #4</td>
	</tr>
	<tr>
		<td class="row-title">Table Cell #5, without label</td>
		<td>Table Cell #6</td>
	</tr>
	<tr class="alt">
		<td class="row-title">Table Cell #7, without label and with class <code>alt</code></td>
		<td>Table Cell #8</td>
	</tr>
	<tr class="form-invalid">
		<td class="row-title">Table Cell #9, without label and with class <code>form-invalid</code></td>
		<td>Table Cell #10</td>
	</tr>
	</tbody>
	<tfoot>
	<tr>
		<th class="row-title">Table header cell #1</th>
		<th>Table header cell #2</th>
	</tr>
	</tfoot>
</table>
</div>`
}
const Settings = {
    template: `<div>
<table class="form-table">
	<tbody>
        <tr>
            <td class="row-title"><label for="tablecell">Table Cell #1, with label</label></td>
            <td>Table Cell #2</td>
        </tr>
        <tr class="alternate">
            <td class="row-title"><label for="tablecell">Table Cell #3, with label and class <code>alternate</code></label></td>
            <td>Table Cell #4</td>
        </tr>
        <tr>
            <td class="row-title">Table Cell #5, without label</td>
            <td>Table Cell #6</td>
        </tr>
    </tbody>
</table>
</div>`
}

const router = new VueRouter({
    routes: [
        {path: "/", component: Dashboard,},
        {path: "/room_types", component: RoomTypes,},
        {path: "/rooms", component: Rooms,},
        {path: "/orders", component: Orders,},
        {path: "/settings", component: Settings,},
    ]
});

const app = new Vue({
    el: '#app',
    router: router,
    data() {
    },
    mounted() {
    }
});
</script>


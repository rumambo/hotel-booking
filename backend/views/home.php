<style>

    /*body {*/
    /*    background: #fff;*/
    /*}*/
    /*.nav-tab-active, .nav-tab-active:focus, .nav-tab-active:focus:active, .nav-tab-active:hover {*/
    /*    background:#fff !important;*/
    /*    border-bottom: 1px solid #fff;*/
    /*}*/

    .vue-input-tag-wrapper .new-tag {
        margin-bottom: 0;
        padding: 0;
    }

    [v-cloak] {
        display: none;
    }
</style>

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

    const RoomTypes = {
        template: `<div>
        <a href="#" class="button">
            <span class="dashicons dashicons-plus" style="line-height: 32px;"></span>
        </a>
        <br/>
        <table class="widefat">
            <thead>
            <tr>
                <th>Фото</th>
                <th>Название</th>
                <th>Площадь</th>
                <th>Вместимость</th>
                <th>Цена</th>
                <th style="width:70px">
                    <div class="tip text-center" title="Удалить">
                        <span class="fa fa-pencil-alt"></span>
                    </div>
                </th>
                <th style="width:70px">
                    <div class="tip text-center" title="Удалить">
                        <span class="fa fa-trash"></span>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td class="align-middle">
                    <img src="/uploads/room_types/sm/15693996465d8b235eba3184.45308519_wallhaven-58951.jpg">
                </td>
                <td class="align-middle">
                    <b>STN</b><br>
                    Стандартный номер<br>
                     Стандартний номер<br>
                    Standart room
                </td>
                <td class="align-middle">
                    32
                </td>
                <td class="align-middle">
                     1<br>1 + 2<br>1 + 3<br>
                </td>
                <td class="align-middle">
                    1200<br>1600<br>1800<br>
                </td>
                <td class="align-middle text-center">
                    <a href="#" class="button">
                        <span class="dashicons dashicons-edit"></span>
                    </a>
                </td>
                <td class="text-center align-middle">
                    <a class="button" title="Удалить" href="#">
                        <span class="dashicons dashicons-trash"></span>
                    </a>
                </td>
            </tr>

            <tr>
                <td class="align-middle">
                    <img src="/uploads/room_types/sm/15694011755d8b295703ec97.75311481_wallhaven-153264.jpg">
                </td>
                <td class="align-middle">
                    <b>LUX</b><br>
                    Номер люкс<br>
                    Номер люкс<br>
                    De luxe suite
                </td>
                <td class="align-middle">
                    64
                </td>
                <td class="align-middle">
                     1<br>
                </td>
                <td class="align-middle">
                    1300<br>
                </td>
                <td class="align-middle text-center">
                    <a href="/cp/room_types/edit/4" class="btn btn-sm btn-outline-info">
                        <span class="fa fa-pencil-alt"></span>
                    </a>
                </td>
                <td class="text-center align-middle">
                    <a class="btn btn-sm btn-danger" title="Удалить" href="/cp/room_types/del/4">
                        <span class="fa fa-trash"></span>
                    </a>
                </td>
            </tr>

            </tbody>
        </table>

        </div>`
    }

    const Rooms = {
        data() {
            return {
                rooms: {},
            };
        },
        mounted() {

            // axios
            //     .get(ajaxurl + '?action=test')
            //     .then(response => (this.rooms = response.data))
            //     .catch(error => console.log(error));
        },
        template: `<div>

<a href="#" class="button" style="float:right;">
    <span class="dashicons dashicons-plus" style="line-height: 32px;" ></span>
</a>

<h3 class="mt-3">Стандартный номер</h3>
<table class="widefat">
    <thead>
    <tr>
        <th>Номер</th>
        <th class="w-50">Тип номера</th>
        <th>Статус</th>
        <th style="width:70px">
            <div class="tip text-center" title="Статус">
                <span class="fa fa-eye"></span>
            </div>
        </th>
        <th style="width:70px">
            <div class="tip text-center" title="Править">
                <span class="fa fa-pencil-alt"></span>
            </div>
        </th>
        <th style="width:70px">
            <div class="tip text-center" title="Удалить">
                <span class="fa fa-trash"></span>
            </div>
        </th>
    </tr>
    </thead>
    <tbody>

        <tr>
            <td class="align-middle">
                101
            </td>
            <td class="align-middle">
                Стандартный номер
            </td>
            <td class="align-middle">
                Готов
            </td>
            <td class="align-middle text-center">

            <a href="#" title="Активен" class="button">
                <span class="dashicons dashicons-yes" style="line-height: 32px;"></span>
            </a>

            </td>
            <td class="align-middle text-center">
                <a href="#" class="button">
                    <span class="dashicons dashicons-edit" style="line-height: 32px;"></span>
                </a>
            </td>
            <td class="text-center">
                <a class="button" title="Удалить" href="#">
                    <span class="dashicons dashicons-trash" style="line-height: 32px;"></span>
                </a>
            </td>
        </tr>

        <tr>
            <td class="align-middle">
                102
            </td>
            <td class="align-middle">
                Стандартный номер
            </td>
            <td class="align-middle">
                Готов
            </td>
            <td class="align-middle text-center">

            <a href="/cp/rooms/status/4" title="Не активен" class="btn btn-sm btn-warning">
                <span class="fa fa-eye-slash"></span>
            </a>

            </td>
            <td class="align-middle text-center">
                <a href="/cp/rooms/edit/4" class="btn btn-sm btn-outline-info">
                    <span class="fa fa-pencil-alt"></span>
                </a>
            </td>
            <td class="text-center">
                <a class="btn btn-sm btn-danger" title="Удалить" href="/cp/rooms/del/4">
                    <span class="fa fa-trash"></span>
                </a>
            </td>
        </tr>

        <tr>
            <td class="align-middle">
                103
            </td>
            <td class="align-middle">
                Стандартный номер
            </td>
            <td class="align-middle">
                Уборка
            </td>
            <td class="align-middle text-center">

            <a href="/cp/rooms/status/5" title="Активен" class="btn btn-sm btn-success">
                <span class="fa fa-eye"></span>
            </a>

            </td>
            <td class="align-middle text-center">
                <a href="/cp/rooms/edit/5" class="btn btn-sm btn-outline-info">
                    <span class="fa fa-pencil-alt"></span>
                </a>
            </td>
            <td class="text-center">
                <a class="btn btn-sm btn-danger" title="Удалить" href="/cp/rooms/del/5">
                    <span class="fa fa-trash"></span>
                </a>
            </td>
        </tr>
        </tbody></table>


        <h3 class="mt-3">Номер люкс</h3>

        <table class="widefat">
            <thead>
            <tr>
                <th>Номер</th>
                <th class="w-50">Тип номера</th>
                <th>Статус</th>
                <th style="width:70px">
                    <div class="tip text-center" title="Статус">
                        <span class="fa fa-eye"></span>
                    </div>
                </th>
                <th style="width:70px">
                    <div class="tip text-center" title="Править">
                        <span class="fa fa-pencil-alt"></span>
                    </div>
                </th>
                <th style="width:70px">
                    <div class="tip text-center" title="Удалить">
                        <span class="fa fa-trash"></span>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>

                <tr>
                    <td class="align-middle">
                        201
                    </td>
                    <td class="align-middle">
                        Номер люкс
                    </td>
                    <td class="align-middle">
                        Готов
                    </td>
                    <td class="align-middle text-center">

                    <a href="/cp/rooms/status/3" title="Активен" class="btn btn-sm btn-success">
                        <span class="fa fa-eye"></span>
                    </a>

                    </td>
                    <td class="align-middle text-center">
                        <a href="/cp/rooms/edit/3" class="btn btn-sm btn-outline-info">
                            <span class="fa fa-pencil-alt"></span>
                        </a>
                    </td>
                    <td class="text-center">
                        <a class="btn btn-sm btn-danger" title="Удалить" href="/cp/rooms/del/3">
                            <span class="fa fa-trash"></span>
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>


        </div>`
    }

    const Orders = {
        template: `<div>

        <table class="" style="width:100%;">
            <thead>
            <tr style="background: #f6f7f7;">
                <th>№</th>
                <th>Customer</th>
                <th>Phone</th>
                <th>Room</th>
                <th style="width:200px;">Check-in - Check-out date</th>
                <th>Price</th>
                <th style="width:200px;">Status</th>
                <th style="width:70px">
                    <div class="tip text-center" title="Delete">
                        <span class="fa fa-trash"></span>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>

            <tr style="background: #dcdcde;">
                 <td class="align-middle">
                    55
                </td>
                <td class="align-middle">
                    авпвы <br>
                    Email: dsf@fds.com <br>Примечание: ds fdsaf, дней: 2, доп.услуги(Трансфер 1|) , прибытие: 12:00, завтрак: yes, парковка: yes
                </td>
                <td class="align-middle">
                    324324
                </td>
                <td class="align-middle">
                   101
                </td>
                <td class="align-middle">
                    19.11.2019 - 21.11.2019
                </td>
                <td class="align-middle">
                    1080
                </td>
                <td class="align-middle">
                     Новый - Не оплачен
                </td>
                <td class="align-middle text-center" style="text-align: center">
                    <a class="button" title="Удалить" href="#">
                        <span class="dashicons dashicons-trash" style="line-height: 28px"></span>
                    </a>
                </td>
            </tr>

            <tr style="background: #dcdcde;">
                 <td class="align-middle">
                    54
                </td>
                <td class="align-middle">
                    dsf <br>
                    Email: gfdg@fsd.com <br>Примечание: dsff
                </td>
                <td class="align-middle">
                    535
                </td>
                <td class="align-middle">
                   201
                </td>
                <td class="align-middle">
                    08.10.2019 - 11.10.2019
                </td>
                <td class="align-middle">

                </td>
                <td class="align-middle">
                     Новый - Оплачен
                </td>
                <td class="align-middle text-center">
                    <a class="btn btn-sm btn-danger" title="Удалить" href="/cp/orders/del/54">
                        <span class="fa fa-trash"></span>
                    </a>
                </td>
            </tr>

            </tbody>
        </table>
        </div>`
    }

    const Settings = {
        data() {
            return {
                roomStatuses: ['Ready', 'Cleaning', 'Dirty'],
                bookingStatuses: ['New', 'Confirmed', 'Arrived', 'Freed'],
                comfortsList: ['Wifi', 'Conditioner', 'Refrigerator', 'Safe', 'Bathrobe', 'Hair dryer', 'Slippers', 'TV', 'Balcony'],
                servicesList: ['Transfer', 'Massage', 'Dinner', 'Supper'],
                setsList: ['1', '1 + 1', '1 + 2', '1 + 3'],
                imageLarge: 1000,
                imageMedium: 500,
                imageSmall: 100,
                currencyList: [
                    ['USD', '$', '1.00'],
                    ['RUB', '₽', '38.00'],
                    ['UAH', '₴', '28.00'],
                ],
                promocodeList: [
                    ['TEST1', 10, 1],
                ],
            }
        },
        template: `<div>
            <table class="form-table">
                <tr>
                    <th>Room Statuses</th>
                    <td>
                        <input-tag v-model="roomStatuses" :add-tag-on-blur="true"></input-tag>
                    </td>
                </tr>
                <tr>
                    <th>Booking Statuses</th>
                    <td>
                        <input-tag v-model="bookingStatuses" :add-tag-on-blur="true"></input-tag>
                    </td>
                </tr>
                <tr>
                    <th>Comforts List</th>
                    <td>
                        <input-tag v-model="comfortsList" :add-tag-on-blur="true"></input-tag>
                    </td>
                </tr>
                <tr>
                    <th>Services List</th>
                    <td>
                        <input-tag v-model="servicesList" :add-tag-on-blur="true"></input-tag>
                    </td>
                </tr>
                <tr>
                    <th>Sets List</th>
                    <td>
                        <input-tag v-model="setsList" :add-tag-on-blur="true"></input-tag>
                    </td>
                </tr>
                <tr>
                    <th><label>Room Images, px</label></th>
                    <td>
                        Large
                        <input style="width:100px; margin-right:15px;" name="IMG_LARGE" type="number" v-model="imageLarge">
                        Medium
                        <input style="width:100px; margin-right:15px;" name="IMG_MEDIUM" type="number" v-model="imageMedium">
                        Small
                        <input style="width:100px;" name="IMG_SMALL" type="number" v-model="imageSmall">
                    </td>
                </tr>
            </table>

            <h3>Addition</h3>

            <div style="width:49%; float:left; margin-right:1%">
                <table class='widefat'>
                    <thead>
                        <tr>
                            <th>Currencies</th>
                            <th>Sign</th>
                            <th>Coef</th>
                            <th style="width:30px; text-align: center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="rowList in currencyList" >
                          <td v-for="item in rowList"><input type="text" style="width:100%;" :value="item" ></td>
                          <td><button class="button" style="background: #DC3232;color: #fff; line-height: normal; border-color:#DC3232;" type="submit"><span class="dashicons dashicons-trash"></span></button></td>
                        </tr>
                    </tbody>
                    <tr style="background:#f0f6fc;">
                        <td><input type="text" style="width:100%;" placeholder="Currency" ></td>
                        <td><input type="text" style="width:100%;" placeholder="Sign" ></td>
                        <td><input type="text" style="width:100%;" placeholder="Coef" ></td>
                        <td><button class="button-primary button" style="line-height: normal;" type="submit"><span class="dashicons dashicons-plus"></span></button></td>
                    </tr>
                </table>
            </div>

            <div style="width:50%; float:left;">
                <table class='widefat'>
                    <thead>
                    <tr>
                        <th>Promocodes</th>
                        <th>Sum</th>
                        <th>Status</th>
                        <th style="width:30px; text-align: center"></th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="rowList in promocodeList" >
                          <td v-for="item in rowList"><input type="text" style="width:100%;" :value="item" ></td>
                          <td><button class="button" style="background: #DC3232;color: #fff; line-height: normal; border-color:#DC3232;" type="submit"><span class="dashicons dashicons-trash"></span></button></td>
                        </tr>
                    </tbody>
                    <tr style="background:#f0f6fc;">
                        <td><input type="text" style="width:100%;" placeholder="Promocode" ></td>
                        <td><input type="text" style="width:100%;" placeholder="Sum" ></td>
                        <td><input type="text" style="width:100%;" placeholder="Status" ></td>
                        <td><button class="button-primary button" style="line-height: normal;" type="submit"><span class="dashicons dashicons-plus"></span></button></td>
                    </tr>
                </table>
            </div>

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


    Vue.component('input-tag', vueInputTag.default)
    // Vue.use(VueTableDynamic)
    // Vue.component('VueTableDynamic')
    // Vue.use(VueTableDynamic)

    const app = new Vue({
        router,
        el: '#app',
        data() {
        },
        mounted() {
        }
    });
</script>


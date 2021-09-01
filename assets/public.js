const {__, _x, _n, _nx} = wp.i18n;

Vue.prototype.message = {
    my_reserv: __('My booking', 'hotel-booking-by-xfor'),
    area: __('Area', 'hotel-booking-by-xfor'),
    capacity: __('Capacity', 'hotel-booking-by-xfor'),
    to_book: __('To book', 'hotel-booking-by-xfor'),
    guest: __('Guest', 'hotel-booking-by-xfor'),
    breakfast: __('Breakfast', 'hotel-booking-by-xfor'),
    parking: __('Parking', 'hotel-booking-by-xfor'),
    arrival: __('Arrival Time', 'hotel-booking-by-xfor'),
    price: __('Price', 'hotel-booking-by-xfor'),
    yes: __('Yes', 'hotel-booking-by-xfor'),
    no: __('No', 'hotel-booking-by-xfor'),
    promocode: __('Promo code', 'hotel-booking-by-xfor'),
    arrival_date: __('Arrival', 'hotel-booking-by-xfor'),
    departure_date: __('Departure', 'hotel-booking-by-xfor'),
    check_availability: __('Check availability', 'hotel-booking-by-xfor'),
    time: __('Time', 'hotel-booking-by-xfor'),
    meter: __('m', 'hotel-booking-by-xfor'),
    booking_form: __('Booking form', 'hotel-booking-by-xfor'),
    fullname: __('Full Name', 'hotel-booking-by-xfor'),
    tel: __('Phone', 'hotel-booking-by-xfor'),
    email: __('Email', 'hotel-booking-by-xfor'),
    noty: __('Wishes', 'hotel-booking-by-xfor'),
    send: __('Send', 'hotel-booking-by-xfor'),
    back: __('Back', 'hotel-booking-by-xfor'),
    left: __('left', 'hotel-booking-by-xfor'),
    qty_night: __('Nights', 'hotel-booking-by-xfor'),
    add_services: __('Additional services', 'hotel-booking-by-xfor'),
    order: __('Order Form', 'hotel-booking-by-xfor'),
    guests: __('Guests', 'hotel-booking-by-xfor'),
    check_booking: __('Check booking', 'hotel-booking-by-xfor'),
    order_id: __('Order id', 'hotel-booking-by-xfor'),
    check: __('Check', 'hotel-booking-by-xfor'),
    order_success: __('Order Success', 'hotel-booking-by-xfor'),
    return: __('Return', 'hotel-booking-by-xfor'),
    sorry: __('This date is already taken', 'hotel-booking-by-xfor'),
    per_night: __('per night', 'hotel-booking-by-xfor'),
};

Vue.prototype.datepicker_lang = {
    night: __('Night', 'hotel-booking-by-xfor'),
    nights: __('Nights', 'hotel-booking-by-xfor'),
    'day-names-short': [
        __('Sun', 'hotel-booking-by-xfor'),
        __('Mon', 'hotel-booking-by-xfor'),
        __('Tue', 'hotel-booking-by-xfor'),
        __('Wed', 'hotel-booking-by-xfor'),
        __('Thu', 'hotel-booking-by-xfor'),
        __('Fri', 'hotel-booking-by-xfor'),
        __('Sat', 'hotel-booking-by-xfor')
    ],
    'day-names': [
        __('Sunday', 'hotel-booking-by-xfor'),
        __('Monday', 'hotel-booking-by-xfor'),
        __('Tuesday', 'hotel-booking-by-xfor'),
        __('Wednesday', 'hotel-booking-by-xfor'),
        __('Thursday', 'hotel-booking-by-xfor'),
        __('Friday', 'hotel-booking-by-xfor'),
        __('Saturday', 'hotel-booking-by-xfor')
    ],
    'month-names-short': [
        __('Jan', 'hotel-booking-by-xfor'),
        __('Feb', 'hotel-booking-by-xfor'),
        __('Mar', 'hotel-booking-by-xfor'),
        __('Apr', 'hotel-booking-by-xfor'),
        __('May', 'hotel-booking-by-xfor'),
        __('Jun', 'hotel-booking-by-xfor'),
        __('Jul', 'hotel-booking-by-xfor'),
        __('Aug', 'hotel-booking-by-xfor'),
        __('Sep', 'hotel-booking-by-xfor'),
        __('Oct', 'hotel-booking-by-xfor'),
        __('Nov', 'hotel-booking-by-xfor'),
        __('Dec', 'hotel-booking-by-xfor')
    ],
    'month-names': [
        __('January', 'hotel-booking-by-xfor'),
        __('February', 'hotel-booking-by-xfor'),
        __('March', 'hotel-booking-by-xfor'),
        __('April', 'hotel-booking-by-xfor'),
        __('May', 'hotel-booking-by-xfor'),
        __('June', 'hotel-booking-by-xfor'),
        __('July', 'hotel-booking-by-xfor'),
        __('August', 'hotel-booking-by-xfor'),
        __('September', 'hotel-booking-by-xfor'),
        __('October', 'hotel-booking-by-xfor'),
        __('November', 'hotel-booking-by-xfor'),
        __('December', 'hotel-booking-by-xfor')
    ],
};

Vue.use(VueAwesomeSwiper);

Vue.component('modal0', {
    data() {
        return {
            fields: {},
            errors: {},
        }
    },
    methods: {
        submit() {
            axios.post(ajaxurl + '?action=xfor_check', this.fields).then(response => {
                document.getElementById('check_result').innerHTML = '<div class="mt-3">' + response.data + '</div>';
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
    },
    template: `
      <div>
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">{{ message.check_booking }}</h5>
                <button type="button" class="close" @click="$emit('close')" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="checkBooking">
                <form class="row" @submit.prevent="submit">
                  <div class="col-4">
                    <input type="text" v-model="fields.tel" class="form-control" :placeholder="message.tel" required/>
                  </div>
                  <div class="col-4">
                    <input type="text" v-model="fields.order_id" class="form-control" :placeholder="message.order_id"
                           required/>
                  </div>
                  <div class="col-4">
                    <button class="btn btn-primary btn-block" type="submit">{{ message.check }}</button>
                  </div>
                </form>
                <div id="check_result"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>`
});

Vue.component('modal1', {
    data() {
        return {
            errors: {},
            booking: {},
            selected_days: this.$root.$data.selected_days,
            selected_datestart: this.$root.$data.selected_datestart,
            selected_dateend: this.$root.$data.selected_dateend,
            selected_room_type: this.$root.$data.selected_room_type,
            selected_room_type_id: this.$root.$data.selected_room_type_id,
            selected_arrival: this.$root.$data.selected_arrival,
            selected_breakfast: this.$root.$data.selected_breakfast,
            selected_parking: this.$root.$data.selected_parking,
            add_services_list: this.$root.$data.add_services_list,
            currentCurrency: this.$root.$data.currentCurrency,
            currencies: this.$root.$data.currencies,
            selected_cost: this.$root.$data.selected_cost,
            selected_guest: this.$root.$data.selected_guest,
        }
    },
    methods: {
        submit() {

            let inputElements = document.getElementsByClassName('add_services');
            let a = 0;
            this.booking.add_services = {};
            for (let i = 0; inputElements[i]; i++) {
                if (inputElements[i].checked) {
                    this.booking.add_services[a] = inputElements[i].value;
                    a++;
                }
            }

            this.booking.days = this.selected_days;
            this.booking.datestart = this.selected_datestart;
            this.booking.dateend = this.selected_dateend;
            this.booking.arrival_date = this.selected_arrival_date;
            this.booking.departure_date = this.selected_departure_date;
            this.booking.promocode = this.selected_promocode;
            this.booking.room_type = this.selected_room_type;
            this.booking.room_type_id = this.selected_room_type_id;
            this.booking.arrival = this.selected_arrival;
            this.booking.breakfast = this.selected_breakfast;
            this.booking.parking = this.selected_parking;
            this.booking.currency = this.currentCurrency;
            this.booking.cost = this.selected_cost;
            this.booking.guest = this.selected_guest;

            this.errors = {};
            axios.post(ajaxurl + '?action=xfor_send', this.booking)
                .then(function (response) {
                    console.log(response.data);
                    window.document.getElementsByClassName('modal-content')[0].innerHTML = '<div class="text-center p-5"><h1 >' + Vue.prototype.message.order_success + '</h1> <a class="btn btn-success" style="text-decoration:none" href="">' + Vue.prototype.message.back + '</a><br/><br/></div>';
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getCurrencySign() {
            for (let i = 0; i < this.currencies.length; i++) {
                if (this.currencies[i][0] === this.currentCurrency) {
                    return this.currencies[i][1]
                }
            }
        },
    },
    template: `
      <div>
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <form class="modal-content" @submit.prevent="submit">
              <div class="modal-header">
                <h5 class="modal-title">{{ message.order }} - {{ selected_room_type }}</h5>
                <button type="button" class="close" @click="$emit('close')" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-4">
                    <ul class="list-group">
                      <li class="list-group-item py-1">
                        {{ message.arrival_date }} <b class="float-right">{{ selected_datestart }}</b><br/>
                        <b class="float-right">{{ selected_arrival }}</b>
                      </li>
                      <li class="list-group-item py-1">
                        {{ message.departure_date }} <b class="float-right">{{ selected_dateend }}</b>
                      </li>

                      <li class="list-group-item py-1">
                        {{ message.qty_night }} <b class="float-right">{{ selected_days }}</b>
                      </li>
                      <li class="list-group-item py-1">
                        {{ message.price }} <b class="float-right">{{ selected_cost }} {{ getCurrencySign() }}</b>
                      </li>

                      <li class="list-group-item py-1">
                        {{ message.breakfast }} <b class="float-right">{{ message[selected_breakfast] }}</b>
                      </li>
                      <li class="list-group-item py-1">
                        {{ message.parking }} <b class="float-right">{{ message[selected_parking] }}</b>
                      </li>
                      <li class="list-group-item py-1">
                        {{ message.guests }} <b class="float-right">{{ selected_guest }}</b>
                      </li>
                    </ul>
                  </div>
                  <div class="col-8">
                    <div class="mb-3 row">
                      <label class="col-form-label col-3">{{ message.fullname }}</label>
                      <div class="col-9">
                        <input type="text" v-model="booking.fullname" class="form-control"
                               :placeholder="message.fullname" required/>
                      </div>
                    </div>
                    <div class="mb-3 row">
                      <label class="col-form-label col-3">{{ message.tel }}</label>
                      <div class="col-6">
                        <input type="text" v-model="booking.tel" class="form-control" :placeholder="message.tel"
                               required/>
                      </div>
                    </div>
                    <div class="mb-3 row">
                      <label class="col-form-label col-3">{{ message.email }}</label>
                      <div class="col-6">
                        <input type="email" v-model="booking.email" class="form-control" placeholder="E-mail"/>
                      </div>
                    </div>
                    <div class="mb-3 row">
                      <label class="col-form-label col-3">{{ message.add_services }}</label>
                      <div class="col-9">
                        <div v-for="list in add_services_list">
                          <label class="d-inline">
                            <input class="add_services" type="checkbox" :value="list"/>
                            {{ list }}
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="mb-3 row">
                      <label class="col-form-label col-3">{{ message.noty }}</label>
                      <div class="col-9">
                        <textarea class="form-control" v-model="booking.noty" rows="3"
                                  :placeholder="message.noty"></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-warning" style="margin-right:15px; color:white;"
                        @click="$emit('close')">{{ message.back }}
                </button>
                <button class="btn btn-success" type="submit">{{ message.send }}</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      </div>`
});

if (document.getElementById("hotel-booking")) {

    const hotel_booking = new Vue({
        el: '#hotel-booking',
        data: {
            add_services_list: {},
            datepicker: {},
            selected_days: 0,
            selected_datestart: 0,
            selected_dateend: 0,
            selected_room_type: '',
            selected_room_type_id: '',
            selected_arrival: '',
            selected_breakfast: '',
            selected_parking: '',
            selected_cost: '',
            selected_guest: '',
            cost_item: 0,
            date_range: '',
            promocode: '',
            swiperOption: {
                pagination: {
                    el: '.swiper-pagination',
                    dynamicBullets: true
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                },
                loop: true,
            },
            showModalCheckBooking: false,
            showModalBooking: false,
            currentCurrency: 'USD',
            currencies: [],
            rooms: [],
            showLoader: true,
        },
        created: function () {
            this.showLoader = false
        },
        mounted() {

            axios.get(ajaxurl + '?action=xfor_get').then(response => {
                this.rooms = response.data.rooms;
                this.currencies = response.data.currencies;
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });

            this.initDatePicker();

            let today = new Date();
            let tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            let input1 = document.querySelector('#input-id');
            input1.value = fecha.format(today, 'DD.MM.YYYY') + ' - ' + fecha.format(tomorrow, 'DD.MM.YYYY');
            this.date_range = input1.value;

            this.selected_datestart = fecha.format(today, 'DD.MM.YYYY');
            this.selected_dateend = fecha.format(tomorrow, 'DD.MM.YYYY');
            this.selected_days = datepicker.getNights();

            input1.addEventListener('afterClose', this.getNight, false);

        },
        methods: {
            toBook(room) {
                this.showModalBooking = true;
                this.selected_room_type = room.name;
                this.selected_room_type_id = room.id;
                this.selected_arrival = window.document.getElementById('time_' + room.id).value;
                this.selected_breakfast = window.document.getElementById('breakfast_' + room.id).value;
                this.selected_parking = window.document.getElementById('parking_' + room.id).value;
                this.add_services_list = room.add_services;
                this.selected_cost = window.document.getElementById('guest_' + room.id).value;
                this.selected_guest = window.document.getElementById('guest_' + room.id).options[window.document.getElementById('guest_' + room.id).selectedIndex].text;
            },
            getCurrencySign() {
                for (var i = 0; i < this.currencies.length; i++) {
                    if (this.currencies[i][0] === this.currentCurrency) {
                        return this.currencies[i][1]
                    }
                }
            },
            getCurrencyRatio() {
                for (var i = 0; i < this.currencies.length; i++) {
                    if (this.currencies[i][0] === this.currentCurrency) {
                        return this.currencies[i][2]
                    }
                }
            },
            search() {
                let data = {
                    'range': this.date_range,
                    'promocode': this.promocode,
                };

                this.showLoader = true

                axios.post(ajaxurl + '?action=xfor_get', data).then(response => {
                    this.rooms = response.data.rooms;
                    this.showLoader = false
                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors || {};
                    }
                });

            },
            getNight() {
                this.selected_days = datepicker.getNights();
                let dateRange = document.querySelector('#input-id').value;
                this.date_range = dateRange;
                dateRange = dateRange.split(' - ');
                this.selected_datestart = dateRange[0];
                this.selected_dateend = dateRange[1];
            },
            initDatePicker() {
                datepicker = new HotelDatepicker(document.getElementById('input-id'), {
                    format: 'DD.MM.YYYY',
                    startOfWeek: 'monday',
                    showTopbar: false,
                    selectForward: true,
                    i18n: this.datepicker_lang,
                    onSelectRange: function () {
                        hotel_booking.search()
                    },
                });
                return datepicker;
            },
            showLightbox(imageName, index) {
                this.$refs.lightbox[index].show(imageName);
            },
            changeGuest: function (e, item) {
                this.$refs.cost[item].innerText = e.target.value;
            },
            changeCurrentCurrency: function () {
                let i = 0;
                while (i < this.rooms.length) {
                    this.$el.getElementsByClassName('guest')[i].selectedIndex = 0;
                    let selectedCost = parseInt(this.rooms[i].capacity_cost[0] * this.getCurrencyRatio());
                    this.$refs.cost[i].innerText = selectedCost;
                    i++;
                }
            },
        },
    });

}

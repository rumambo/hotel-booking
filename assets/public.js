Vue.prototype.message = {
    // title: 'Room reservation',
    my_reserv: 'My booking',
    area: 'Area',
    capacity: 'Capacity',
    to_book: 'To book',
    guest: 'Guest',
    breakfast: 'Breakfast',
    parking: 'Parking',
    arrival: 'Arrival Time',
    price: 'Price',
    yes: 'Yes',
    no: 'No',
    promocode: 'Promo code',
    arrival_date: 'Arrival',
    departure_date: 'Departure',
    check_availability: 'Check availability',
    time: 'Time',
    meter: 'm',
    booking_form: 'Booking form',
    fullname: 'Full Name',
    tel: 'Phone',
    email: 'Email',
    noty: 'Wishes',
    send: 'Send',
    back: 'Back',
    left: 'left',
    qty_night: 'Nights',
    add_services: 'Additional services',
    order: 'Order Form',
    guests: 'Guests',
    check_booking: 'Check booking',
    order_id: 'Order id',
    check: 'Check',
    order_success: 'Order Success',
    return: 'Return',
    sorry: 'This date is already taken',
};

Vue.prototype.datepicker_lang = {

    night: 'Night',
    nights: 'Nights',
    'day-names-short': ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    'day-names': ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
    'month-names-short': ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    'month-names': ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],

};

Vue.use(VueAwesomeSwiper);

Vue.component('modal0', {
    template: '#modal-checkBooking',
    data() {
        return {
            fields: {},
            errors: {},
        }
    },
    methods: {
        submit() {
            // this.errors = {};
            axios.post(ajaxurl + '?action=hb_check', this.fields).then(response => {
                document.getElementById('check_result').innerHTML = '<div class="mt-3">' + response.data + '</div>';
                // console.log(response.data);
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });
        },
    }
});

Vue.component('modal1', {
    template: '#modal-booking',
    data() {
        return {
            fields: {},
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
            bookingImage: this.$root.$data.bookingImage,
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
            axios.post(ajaxurl + '?action=hb_send', this.booking)
                .then(function (response) {
                    console.log(response.data);
                    this.document.getElementsByClassName('modal-content')[0].innerHTML = '<div class="text-center p-5"><h1 >' + this.booking.message.order_success + '</h1> <a class="btn btn-success" href="">' + this.booking.message.return + '</a></div>';
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getCurrencySign() {
            for (var i = 0; i < this.currencies.length; i++) {
                if (this.currencies[i][0] === this.currentCurrency) {
                    return this.currencies[i][1]
                }
            }
        },
    }
});

const booking = new Vue({
    el: '#hotel-booking',
    data: {
        add_services_list: {},
        bookingImage: '',
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
    },
    created: function () {
        var element = document.getElementById("loader");
        element.parentNode.removeChild(element);
    },
    mounted() {

        axios.get(ajaxurl + '?action=hb_get').then(response => {
            // console.log(response.data);
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
            // console.log(event);
            console.log(this.date_range);
            console.log(this.promocode);

            let data = {
                'range': this.date_range,
                'promocode': this.promocode,
            };

            axios.post(ajaxurl + '?action=hb_get', data).then(response => {
                // console.log(response.data);
                this.rooms = response.data;
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors || {};
                }
            });

            // event.preventDefault();
            // return;
        },
        getNight() {
            this.selected_days = datepicker.getNights();
            let dateRange = document.querySelector('#input-id').value;
            this.date_range = dateRange;
            dateRange = dateRange.split(' - ');
            this.selected_datestart = dateRange[0];
            this.selected_dateend = dateRange[1];
        },
        showLightbox(imageName, index) {
            // console.log(imageName.name, index);
            this.$refs.lightbox[index].show(imageName);
        },
        // changeLang: function () {
        //     this.initDatePicker().destroy();
        //     this.initDatePicker();
        // },
        initDatePicker() {
            datepicker = new HotelDatepicker(document.getElementById('input-id'), {
                format: 'DD.MM.YYYY',
                startOfWeek: 'monday',
                showTopbar: false,
                selectForward: true,
                i18n: this.datepicker_lang,
            });
            return datepicker;
        },
        changeGuest: function (e, item) {
            this.$refs.cost[item].innerText = e.target.value;
        },
        currentCurrencyChange: function () {
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

<div id="hotel-booking">

    <div class="row mb-3">
        <div class="col-md-2">
            <select class="custom-select" v-model="currentCurrency"  @change="currentCurrencyChange">
                <option v-for="currency in currencies" :value="currency.id">
                    {{currency.name}}
                </option>
            </select>
        </div>
        <div class="col-md-3">
            <input id="input-id" v-model="date_range" type="text" class="form-control text-center" :placeholder="message.arrival_date+' - '+message.departure_date" required>
        </div>
        <div class="col-md-2">
            <input type="text" v-model="promocode" class="form-control" :placeholder="message.promocode">
        </div>
        <div class="col-md-3">
            <button type="button" @click="search" class="btn btn-block btn-primary">
                {{ message.check_availability }}
            </button>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-block btn-secondary" @click="showModalCheckBooking=true">
                {{ message.my_reserv }}
            </button>
        </div>
    </div>

    <div v-for="(room, item) in rooms" class="" style="border:1px solid #ced4da; margin-bottom:15px; padding:15px; background:#fff;">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-5">
                        <template>
                            <swiper :options="swiperOption">
                                <swiper-slide v-for="(image, index) in room.images" :key="index">
                                    <img class="card-img" :src="image.name" @click="showLightbox(image, item)" />
                                </swiper-slide>
                                <div class="swiper-pagination swiper-pagination-white" slot="pagination"></div>
                                <div class="swiper-button-prev swiper-button-white" slot="button-prev"></div>
                                <div class="swiper-button-next swiper-button-white" slot="button-next"></div>
                            </swiper>
                            <lightbox
                                ref="lightbox"
                                :images="room.images"
                            />
                        </template>
                    </div>
                    <div class="col-md-7">
                        <h5 class="">{{ room.name }}</h5>
                        <div>
                            <b>{{ message.area }}:</b>
                            <span>{{ room.area }} {{ message.meter }}<sup>2</sup></span>
                        </div>
                        <div>
                            <b>{{ message.capacity }}:</b>
                            <span> {{ room.capacity }}</span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <p class="mt-3" v-html="room.desc">/p>

                        <ul class="list-inline text-muted" style="font-size:12px">
                            <li v-for="comfort_item in room.comfort_list" class="list-inline-item">
                                <i class="fa fa-check text-success"></i> {{ comfort_item }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">

                    <label class="col-md-5 col-form-label text-right">
                        {{ message.guest }}
                    </label>
                    <div class="col-md-7">
                        <select class="custom-select guest mb-2" ref="guest" @change="changeGuest($event, item)" :id="'guest_'+room.id">
                            <option v-for="(guest, opt) in room.capacity_guest" :value="parseInt(room.capacity_cost[opt]/currenciesRatio[currentCurrency])">{{ guest }}</option>
                        </select>
                    </div>

                    <label class="col-md-5 col-form-label text-right">
                        {{ message.breakfast }}
                    </label>
                    <div class="col-md-7">
                        <select class="custom-select mb-2" :id="'breakfast_'+room.id">
                            <option value="yes">{{ message.yes }}</option>
                            <option value="no">{{ message.no }}</option>
                        </select>
                    </div>

                    <label class="col-md-5 col-form-label text-right">
                        {{ message.parking }}
                    </label>
                    <div class="col-md-7">
                        <select class="custom-select mb-2" :id="'parking_'+room.id">
                            <option value="yes">{{ message.yes }}</option>
                            <option value="no">{{ message.no }}</option>
                        </select>
                    </div>

                    <label class="col-md-5 col-form-label text-right">
                        {{ message.arrival }}
                    </label>
                    <div class="col-md-7">
                        <input type="time" :id="'time_'+room.id" class="form-control mb-2" :placeholder="message.time" value="12:00">
                    </div>

                </div>

            </div>

            <div class="col-md-3 text-right col-form-label">
                {{ message.left }} <span class="badge badge-warning text-white">{{ room.available }}</span>
            </div>

            <div class="col-md-2 text-right col-form-label">
                {{ message.price }}
            </div>
            <div class="col-md-4">
                {{ currencies_sign[currentCurrency] }}
                <h5 class="d-inline-block" ref="cost" :id="'cost_'+room.id">{{ parseInt(room.capacity_cost[0]/currenciesRatio[currentCurrency]) }}</h5>
                     per night

            </div>
            <div class="col-md-3 pr-3">
                <span v-if="room.available != 0">
                <button
                    class="btn btn-success btn-block"
                    type="button"
                    onclick=""
                    @click="
                        showModalBooking=true;
                        selected_room_type=room.name;
                        selected_room_type_id=room.id;
                        bookingImage=room.images[0].name;
                        selected_arrival=this.document.getElementById('time_'+room.id).value;
                        selected_breakfast=this.document.getElementById('breakfast_'+room.id).value;
                        selected_parking=this.document.getElementById('parking_'+room.id).value;
                        add_services_list=room.add_services;
                        selected_cost=this.document.getElementById('guest_'+room.id).value;
                        selected_guest=this.document.getElementById('guest_'+room.id).options[this.document.getElementById('guest_'+room.id).selectedIndex].text;
                        selected_days=selected_days;
                        selected_datestart=selected_datestart;
                        selected_dateend=selected_dateend;
                    "
                >{{ message.to_book }}</button>
                </span>
                <span v-if="room.available == 0">
                    <b class="text-danger text-right pr-3 pt-2 d-block">
                        {{ message.sorry }}
                    </b>
                </span>
            </div>
        </div>
    </div><!-- end rooms -->


    <modal0 v-if="showModalCheckBooking" @close="showModalCheckBooking=false"></modal0>
    <modal1 v-if="showModalBooking" @close="showModalBooking=false"></modal1>

    <div id="loader" tabindex="0" aria-label="Loading" class="vld-overlay is-active is-full-page" style="" aria-busy="true">
        <div class="vld-background"></div>
        <div class="vld-icon">
            <svg viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="#000" width="60" height="240">
                <circle cx="15" cy="15" r="10.5062">
                    <animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate>
                    <animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate>
                </circle>
                <circle cx="60" cy="15" r="13.4938" fill-opacity="0.3">
                    <animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;15;9" calcMode="linear" repeatCount="indefinite"></animate>
                    <animate attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s" values=".5;1;.5" calcMode="linear" repeatCount="indefinite"></animate>
                </circle>
                <circle cx="105" cy="15" r="10.5062">
                    <animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate>
                    <animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate>
                </circle>
            </svg>
        </div>
    </div>

</div><!-- end app -->

<script type="text/x-template" id="modal-checkBooking">
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
                        <form class="row"  @submit.prevent="submit">
                            <div class="col-md-4">
                                <input type="text" v-model="fields.tel" class="form-control" :placeholder="message.tel" required/>
                            </div>
                            <div class="col-md-4">
                                <input type="text" v-model="fields.order_id" class="form-control" :placeholder="message.order_id" required/>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-primary btn-block" type="submit">{{ message.check }}</button>
                            </div>
                        </form>
                        <div id="check_result"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="text/x-template" id="modal-booking">
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
                            <div class="col-md-4">

                                <!-- <img :src="bookingImage" class="img-fluid" /> -->

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
                                        {{ message.price }} <b class="float-right">{{ selected_cost }} {{ currencies_sign[currentCurrency] }}</b>
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
                            <div class="col-md-8">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3">{{ message.fullname }}</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="booking.fullname" class="form-control" :placeholder="message.fullname" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3">{{ message.tel }}</label>
                                    <div class="col-md-6">
                                        <input type="text" v-model="booking.tel" class="form-control" :placeholder="message.tel" required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3">{{ message.email }}</label>
                                    <div class="col-md-6">
                                        <input type="email" v-model="booking.email" class="form-control" placeholder="E-mail" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3">{{ message.add_services }}</label>
                                    <div class="col-md-9">
                                        <div v-for="list in add_services_list">
                                            <label class="d-inline">
                                                <input class="add_services" type="checkbox" :value="list" />
                                                {{ list }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-form-label col-md-3">{{ message.noty }}</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" v-model="booking.noty" rows="3" :placeholder="message.noty"></textarea>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" @click="$emit('close')">{{ message.back }}</button>
                        <button class="btn btn-success" type="submit">{{ message.send }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</script>

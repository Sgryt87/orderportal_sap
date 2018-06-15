<template>
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Orders</a>
            </li>
            <li class="breadcrumb-item active">Single</li>
        </ol>
        <div v-if="notifications.length">
            <div class="alert alert-success" role="alert" v-for="item in notifications">
                <strong>{{item}}</strong>
            </div>
        </div>
        <form v-on:submit.prevent="create_order">
            <div class="form-group">
                <label for="nsn">NSN</label>
                <input type="text" class="form-control" v-model="order.nsn" id="nsn" placeholder=""
                       @blur="fetch_address_by_nsn(order.nsn)">
            </div>
            <div v-if="address" class="form-group">
                <label for="address">Store Address</label>
                <textarea class="form-control" name="" id="address" cols="30" rows="4" readonly>
                    {{address.store_address}}
                    {{address.store_city}}
                    {{address.store_state}}
                    {{address.store_zip}}
                </textarea>
            </div>
            <div class="form-group">
                <label for="presell">Presells</label>
                <select class="form-control" v-model="order.presell" id="presell">
                    <option v-for="(item) in presells"
                            :value="item">{{item.value}}
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label for="order_board">Order Boards</label>
                <select class="form-control" v-model="order.order_board" id="order_board">
                    <option v-for="(item) in order_boards"
                            :value="item">{{item.value}}
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label for="protective_cover">Protective Covers</label>
                <select class="form-control" v-model="order.protective_cover" id="protective_cover">
                    <option v-for="(item) in protective_covers"
                            :value="item">{{item.value}}
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label for="height_requirement">Height Requirement</label>
                <select class="form-control" v-model="order.height_requirement" id="height_requirement">
                    <option v-for="(item) in height_requirements"
                            :value="item">{{item.value}}
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label>Requested Enclosure Delivery Date</label>
<!--min date will be calculated now + 35 dayts + holidays -->
                <!--           :available-dates='{
                        weekdays: [2, 6]
                        }'
                                :available-attribute='availableAttribute'
                                -->
                <v-date-picker
                        mode='single'
                        :formats='formats'
                        :disabled-dates='{
                             weekdays: [1, 7]
                         }'
                        :disabled-attribute='disabledAttribute'
                        :min-date='new Date()'
                        :max-date='new Date(new Date().setFullYear(new Date().getFullYear() + 1))'
                        v-model='selectedDate'
                        show-caps>
                </v-date-picker>
            </div>
            <div class="form-group">
                <label for="">Delivery Notes</label>
                <textarea class="form-control" id="" v-model="order.delivery_note" rows="3"
                          maxlength="255"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Order</button>
        </form>
    </div>
</template>


<script>
    import axios from 'axios';


    export default {
        components: {},
        data: function () {
            return {
                order: {},
                nsn: '',
                address: null,
                presells: [],
                order_boards: [],
                protective_covers: [],
                height_requirements: [],
                notifications: [],
                selectedDate: null,
                formats: {
                    title: 'MMMM YYYY',
                    weekdays: 'W',
                    navMonths: 'MMM',
                    input: ['L', 'YYYY-MM-DD', 'YYYY/MM/DD'], // Only for `v-date-picker`
                    dayPopover: 'L', // Only for `v-date-picker`
                    data: ['L', 'YYYY-MM-DD', 'YYYY/MM/DD'] // For attribute dates
                },
                disabledAttribute: {
                    dot: {
                        backgroundColor: 'red'
                    },
                    contentStyle: {
                        opacity: 0.5
                    }
                },
               availableAttribute: {
                    dot: {
                        backgroundColor: 'green'
                    }
                }
            }
        },

        created() {
            this.fetch_order_boards_data();
            this.fetch_presells_data();
            this.fetch_protective_covers_data();
            this.fetch_height_requirements_data();
        },
        methods: {
            fetch_presells_data() {
                axios.get(`api/presells`)
                    .then(response => {
                        this.presells = response.data;
                        this.order.presell = this.presells[0];
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            fetch_order_boards_data() {
                axios.get(`api/order-boards`)
                    .then(response => {
                        this.order_boards = response.data;
                        this.order.order_board = this.order_boards[0];
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            fetch_protective_covers_data() {
                axios.get(`api/protective-covers`)
                    .then(response => {
                        this.protective_covers = response.data;
                        this.order.protective_cover = this.protective_covers[0];
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            fetch_height_requirements_data() {
                axios.get(`api/height-requirements`)
                    .then(response => {
                        this.height_requirements = response.data;
                        this.order.height_requirement = this.height_requirements[0];
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            fetch_address_by_nsn(nsn) {
                if (nsn > 0) {
                    axios.post(`api/orders-nsn`, {nsn})
                        .then(response => {
                            // this.notifications.push('Address');
                            this.address = response.data.data;
                            console.log('address', response.data.data);
                        })
                        .catch(e => {
                            this.notifications.push(e);
                        })
                }
            },
            create_order() {
                axios.post(`api/orders`, this.order)
                // todo READ STATUS CODE
                    .then(response => {
                        this.notifications.push('Submitted');
                        console.log(response, 'submitted');
                    })
                    .catch(e => {
                        this.notifications.push(e);
                    })
            }
        }
    }
</script>



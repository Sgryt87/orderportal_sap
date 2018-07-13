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
            <div v-if="address_by_nsn" class="form-group">
                <label for="address">Store Address</label>
                <textarea class="form-control" name="" id="address" cols="30" rows="4" readonly>
                    {{address_by_nsn.store_address}}
                    {{address_by_nsn.store_city}}
                    {{address_by_nsn.store_state}}
                    {{address_by_nsn.store_zip}}
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
                <v-date-picker
                        mode='single'
                        :formats='formats'
                        :disabled-dates='disabled_days'
                        :disabled-attribute='disabledAttribute'
                        :min-date='new Date()'
                        :max-date='new Date(new Date().setFullYear(new Date().getFullYear() + 1))'
                        v-model='order.requested_enclosure_delivery_date'
                        show-caps>
                </v-date-picker>
            </div>
            <div class="form-group">
                <label for="">Delivery Notes</label>
                <textarea class="form-control" id="" v-model="order.delivery_note" rows="3"
                          maxlength="255"></textarea>
            </div>
            <button type="submit" class="btn btn-outline-success mb-3">Submit Order</button>
        </form>
    </div>
</template>


<script>


    import axios from 'axios';
    import DisabledDays from './../../../disabledDays';

    export default {
        components: {},
        data: function () {
            return {
                order: {},
                address_by_nsn: null,
                presells: [],
                order_boards: [],
                protective_covers: [],
                height_requirements: [],
                notifications: [],
                errors: [],
                asyncResult: false,

                // labels: {
                //     tip: "Your custom tip box label"
                // },

                disabled_days: null,

                formats: {
                    title: 'MMMM YYYY',
                    weekdays: 'WW',
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
            this.disabled_days = DisabledDays.disabledDays();
            // console.log(this.disabled_days);
        },
        methods: {
            fetch_presells_data() {
                axios.get(`api/presells`)
                    .then(response => {
                        this.presells = response.data.data;
                        this.order.presell = this.presells[0];
                    })
                    .catch((error) => {
                        this.errors.push(error.response.data.errors);
                        console.log(error.response);
                    });
            },
            fetch_order_boards_data() {
                axios.get(`api/order-boards`)
                    .then(response => {
                        this.order_boards = response.data.data;
                        this.order.order_board = this.order_boards[0];
                    })
                    .catch((error) => {
                        this.errors.push(error.response.data.errors);
                        console.log(error.response);
                    });
            },
            fetch_protective_covers_data() {
                axios.get(`api/protective-covers`)
                    .then(response => {
                        this.protective_covers = response.data.data;
                        this.order.protective_cover = this.protective_covers[0];
                    })
                    .catch((error) => {
                        this.errors.push(error.response.data.errors);
                        console.log(error.response);
                    });
            },
            fetch_height_requirements_data() {
                axios.get(`api/height-requirements`)
                    .then(response => {
                        this.height_requirements = response.data.data;
                        this.order.height_requirement = this.height_requirements[0];
                    })
                    .catch((error) => {
                        this.errors.push(error.response.data.errors);
                        console.log(error.response);
                    });
            },
            create_order() {
                // this.$awn.async(this.asyncResult);
                axios.post(`api/orders`, this.order)
                // todo READ STATUS CODE
                    .then(response => {
                        this.asyncResult = true;
                        this.notifications.push('Submitted');
                        console.log(response, 'submitted');
                        this.$awn.success("Order Created!");
                    })
                    .catch((error) => {
                        this.errors.push(error.response.data.errors);
                        this.$awn.alert("Error");
                        console.log('errrr', error.response.data.errors);
                    });
            },
            fetch_address_by_nsn(nsn) {
                if (nsn > 0) {
                    axios.post(`api/address-by-nsn-single`, {'nsn': nsn})
                        .then(response => {
                            console.log('address', response.data.data);
                            this.address_by_nsn = response.data.data;
                        })
                        .catch((error) => {
                            this.errors.push(error.response.data.errors);
                            console.log(error.response);
                        })
                }
            }
        }
    }
</script>





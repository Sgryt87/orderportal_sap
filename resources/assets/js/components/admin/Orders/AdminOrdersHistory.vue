<template>
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Orders</a>
            </li>
            <li class="breadcrumb-item active">History</li>
        </ol>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Email</th>
                    <th>NSN</th>
                    <th>Presells</th>
                    <th>Order Boards</th>
                    <th>Protective Covers</th>
                    <th>Height Requirement</th>
                    <th>Requested Enclosure Delivery Date</th>
                    <th>Delivery Notes</th>
                    <th>Notes</th>
                    <th>Ship Date</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody v-if="orders && orders.length">
                <tr v-for="(order, index) in orders">
                    <td>{{index + 1}}</td>
                    <td>email@static.com</td>
                    <td>
                        <div>
                            {{order.nsn}}
                        </div>
                    </td>
                    <td>
                        <div v-if="order.id === editId">
                            <select class="form-control" v-model="order.presell" name="">
                                <option v-for="(item,index) in presells"
                                        :value="item">{{item.value}}
                                </option>
                            </select>
                        </div>
                        <div v-else>
                            {{order.presell.value}}
                        </div>
                    </td>
                    <td>
                        <div v-if="order.id === editId">
                            <select class="form-control" v-model="order.order_board" name="">
                                <option v-for="(item,index) in order_boards"
                                        :value="item">{{item.value}}
                                </option>
                            </select>
                        </div>
                        <div v-else>
                            {{order.order_board.value}}
                        </div>
                    </td>
                    <td>
                        <div v-if="order.id === editId">
                            <select class="form-control" v-model="order.protective_cover" name="">
                                <option v-for="(item,index) in protective_covers"
                                        :value="item">{{item.value}}
                                </option>
                            </select>
                        </div>
                        <div v-else>
                            {{order.protective_cover.value}}
                        </div>
                    </td>
                    <td>
                        <div v-if="order.id === editId">
                            <select class="form-control" v-model="order.height_requirement" name="">
                                <option v-for="(item,index) in height_requirements"
                                        :value="item">{{item.value}}
                                </option>
                            </select>
                        </div>
                        <div v-else>
                            {{order.height_requirement.value}}
                        </div>
                    </td>
                    <td>
                        <div v-if="order.id === editId">
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
                        <div v-else>
                            {{ date_to_string(order.requested_enclosure_delivery_date) }}
                        </div>
                    </td>
                    <td>
                        <div v-if="order.id === editId">
                            <input type="text" v-model="order.delivery_note">
                        </div>
                        <div v-else>
                            {{order.delivery_note}}
                        </div>
                    </td>
                    <td>
                        <div v-if="order.id === editId">
                            <input type="text" v-model="order.note">
                        </div>
                        <div v-else>
                            {{order.note}}
                        </div>
                    </td>
                    <td>
                        <div v-if="order.id === editId">
                            <input type="text" v-model="order.ship_date">
                        </div>
                        <div v-else>
                            {{order.ship_date}}
                        </div>
                    </td>
                    <td>
                        <div v-if="order.id === editId">
                            <i class="fa fa-check" @click="save_edit_order(order)"></i>
                            <i class="fas fa-ban" @click="cancel_edit_order(index)"></i>
                        </div>
                        <div v-else>
                            <i class="far fa-edit" @click="edit_order(order)"></i>
                            <i class="far fa-trash-alt" @click="delete_order(order, index)"></i>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import DisabledDays from './../../../disabledDays';
    import Pagination from './../../services/Pagination';

    export default {
        components: {},
        name: "admin-orders-history",
        data: function () {
            return {
                orders: [],
                tempOrder: null,
                presells: [],
                order_boards: [],
                protective_covers: [],
                height_requirements: [],
                errors: [],
                editId: null,
                loading: false,

                current_page: null,
                total_pages: null,

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
            this.fetch_orders_data();
            this.disabled_days = DisabledDays.disabledDays();
        },
        methods: {
            //SELECTS
            fetch_presells_data() {
                axios.get(`api/presells`)
                    .then(response => {
                        this.presells = response.data.data;
                    })
                    .catch((error) => {
                        this.errors.push(error.response.data.errors);
                        console.log(error.response);
                    })
            },
            fetch_order_boards_data() {
                axios.get(`api/order-boards`)
                    .then(response => {
                        this.order_boards = response.data.data;
                    })
                    .catch((error) => {
                        this.errors.push(error.response.data.errors);
                        console.log(error.response);
                    })
            },
            fetch_protective_covers_data() {
                axios.get(`api/protective-covers`)
                    .then(response => {
                        this.protective_covers = response.data.data;
                    })
                    .catch((error) => {
                        this.errors.push(error.response.data.errors);
                        console.log(error.response);
                    })
            },
            fetch_height_requirements_data() {
                axios.get(`api/height-requirements`)
                    .then(response => {
                        this.height_requirements = response.data.data;
                    })
                    .catch((error) => {
                        this.errors.push(error.response.data.errors);
                        console.log(error.response);
                    })
            },
            fetch_orders_data(page) {
                axios.get(`api/orders`)
                    .then(response => {
                        this.orders = response.data.data;
                        for (let i = 0; i < this.orders.length; i++) {
                            this.orders[i].requested_enclosure_delivery_date = new
                            Date(this.orders[i].requested_enclosure_delivery_date);
                        }
                        // console.log(this.orders);
                        // this.current_page = response.data.current_page;
                        // this.total_pages = response.data.last_page;
                    })
                    .catch((error) => {
                        this.errors.push(error.response);
                        console.log(error.response);
                    })
            },
            //ORDERS
            update_order(order) {
                // console.log('TIME', order.requested_enclosure_delivery_date = moment.tz(
                //     order.requested_enclosure_delivery_date, "America/Toronto"));
                axios.put(`api/orders/${order.id}`, order)
                    .then(response => {
                        order = response.data.data;
                        // this.current_page = response.data.current_page;
                        // this.total_pages = response.data.last_page;
                    })
                    .catch((error) => {
                        this.errors.push(error.response);
                        console.log(error.response);
                    })
            },
            delete_order(order, index) {
                this.$awn.confirm("Delete?",this.onOk(order),this.onCancel());
                // if (confirm('Delete?')) {
                //     axios.delete(`api/orders/${order.id}`)
                //         .then(response => {
                //             this.orders.splice(index, 1);
                //             this.editId = null;
                //             this.$awn.success("Deleted!");
                //         })
                //         .catch((error) => {
                //             this.errors.push(error.response);
                //             console.log(error.response);
                //         })
                // }
            },
            onOk(order){
                console.log('del');
                axios.delete(`api/orders/${order.id}`)
                    .then(response => {
                        this.orders.splice(index, 1);
                        this.editId = null;
                        this.$awn.success("Deleted!");
                        console.log('del');
                    })
                    .catch((error) => {
                        this.errors.push(error.response);
                        console.log(error.response);
                    })
            },
            onCancel(){
                this.editId = null;
            },
            //EDITING IN LINE
            edit_order(order) {
                this.tempOrder = Object.assign({}, order);
                this.editId = order.id;
            },
            cancel_edit_order(index) {
                Object.assign(this.orders[index], this.tempOrder);
                this.editId = null;
                this.tempOrder = null;
            },
            save_edit_order(order) {
                // console.log('TIME', order.requested_enclosure_delivery_date = moment.tz(
                //     order.requested_enclosure_delivery_date, "America/Toronto"));
                console.log(order);
                this.update_order(order);
                this.editId = null;
                this.$awn.success("Saved!");
            },
            date_to_string(date) {
                return date.toISOString().slice(0, 10);
            },


        }
    }
</script>

<style scoped>

</style>
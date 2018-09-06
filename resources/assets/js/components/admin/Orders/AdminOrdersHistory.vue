<template>
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Orders</a>
            </li>
            <li class="breadcrumb-item active">History</li>
        </ol>
        <div v-if="orders && total_rows > 20">
            <label>Show pages:</label>
            <select v-model="per_page" @change="change_per_page">
                <option v-if="total_rows > 20">20</option>
                <option v-if="total_rows > 50">50</option>
                <option v-if="total_rows > 100">100</option>
                <option v-if="total_rows > 250">250</option>
                <option v-if="total_rows > 500">500</option>
                <option v-if="total_rows > 1000">1000</option>
                <option v-if="total_rows > 10000">10000</option>
            </select>
        </div>
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
                            <!--{{ date_to_string(order.requested_enclosure_delivery_date) }}-->
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
                            <v-date-picker
                                    mode='single'
                                    :formats='formats'
                                    :disabled-dates='disabled_days'
                                    :disabled-attribute='disabledAttribute'
                                    :min-date='new Date()'
                                    :max-date='new Date(new Date().setFullYear(new Date().getFullYear() + 1))'
                                    v-model='order.ship_date'
                                    show-caps>
                            </v-date-picker>
                        </div>
                        <div v-else>
                            {{ date_to_string(order.ship_date) }}
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
        <nav aria-label="Page navigation example" v-if="total_pages > 1">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" aria-label="Previous" @click="navigate_to_the_prev()">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                    </a>
                <li v-bind:class="[page_index == page ? 'active' : '']" class="page-item" v-for="page_index in
                paginate()">
                    <a class="page-link" @click="navigate_to_the_page(page_index)">{{page_index}}</a>
                </li>
                <li class="page-item">
                    <a class="page-link" aria-label="Next" @click="navigate_to_the_next()">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    import axios from 'axios';
    import DisabledDays from './../../../disabledDays';
    import PaginateService from '../../services/Pagination';
    import moment from 'moment';

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

                page: 1,
                per_page: 20,
                total_rows: 0,
                total_pages: 0,

                loading: false,
                disabled_days: null,

                //datepicker
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
            fetch_orders_data() {
                axios.get(`api/orders?page=${this.page}&per_page=${this.per_page}`)
                    .then(response => {
                        this.orders = response.data.data;
                        for (let i = 0; i < this.orders.length; i++) {
                            this.orders[i].requested_enclosure_delivery_date =
                                this.string_to_date(this.orders[i].requested_enclosure_delivery_date);
                            this.orders[i].ship_date = this.string_to_date(this.orders[i].ship_date);
                        }
                        this.it_imports = response.data.data;
                        this.current_page = response.data.page;
                        this.total_pages = response.data.total_pages;
                        this.total_rows = response.data.total_rows;
                    })
                    .catch((error) => {
                        this.errors.push(error.response);
                        console.log(error.response);
                    })
            },
            //ORDERS
            update_order(order) {

                order.requested_enclosure_delivery_date =
                    moment(order.requested_enclosure_delivery_date).format('YYYY-MM-DD');
                order.ship_date = moment(order.ship_date).format('YYYY-MM-DD');

                axios.put(`api/orders/${order.id}`, order)
                    .then(response => {
                        console.log('res', response);
                        order = response.data.data;
                        this.editId = null;
                        this.$awn.success("Saved!");
                        // this.current_page = response.data.current_page;
                        // this.total_pages = response.data.last_page;
                    })
                    .catch((error) => {

                        this.push_errors(error.response.data.errors);
                        // console.log(this.errors);
                        this.show_errors();

                    })
            },
            delete_order(order, index) {
                this.$awn.confirm("Delete?", this.onOk(order), this.onCancel());
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
            onOk(order) {
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
            onCancel() {
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
            },

            //date conversion
            date_to_string(date) {
                return moment(date).format('MM-DD-YYYY');
            },
            string_to_date(date) {
                // return moment(date).format('YYYY-MM-DD');
                return moment(date, 'YYYY-MM-DD').toDate();
            },

            //
            push_errors(error) {
                for (let err of Object.values(error)) {
                    for (let i = 0; i < err.length; i++) {
                        this.errors.push(err[i]);
                    }
                }
            },
            //errors
            show_errors() {
                for (let i = 0; i < this.errors.length; i++) {
                    console.log(this.errors[i]);
                    this.$awn.alert(this.errors[i]);
                }
                this.errors = [];
            },
            //pagination
            paginate() {
                return PaginateService.pagination(this.page, this.total_pages);
            },
            change_per_page() {
                this.page = 1;
                this.fetch_orders_data();
            },
            navigate_to_the_page(page) {
                this.page = Number(page);
                this.fetch_orders_data();
            },
            navigate_to_the_next() {
                if (this.current_page < this.total_pages) {
                    this.navigate_to_the_page(Number(this.current_page) + 1);
                }
            },
            navigate_to_the_prev() {
                if (this.current_page > 1) {
                    this.navigate_to_the_page(this.current_page - 1)
                }
            }
        }
    }
</script>

<style scoped>

</style>
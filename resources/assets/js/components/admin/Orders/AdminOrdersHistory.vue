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
                        <div v-if="order.id === editId">
                            <input type="text" v-model="order.nsn">
                        </div>
                        <div v-else>
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
                        <div>
                            {{order.height_requirement.value}}
                        </div>
                    </td>
                    <td>
                        <div>
                            {{ order.requested_enclosure_delivery_date }}
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
                            <i class="fa fa-check" @click="saveElement(order)"></i>
                            <i class="fas fa-ban" @click="cancelElement(index)"></i>
                        </div>
                        <div v-else>
                            <!--<i class="fas fa-plus" @click="addElement(order)"></i>-->
                            <i class="far fa-edit" @click="editElement(order)"></i>
                            <i class="far fa-trash-alt" @click="deleteElement(order, index)"></i>
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
            }
        },

        created() {
            this.fetch_order_boards_data();
            this.fetch_presells_data();
            this.fetch_protective_covers_data();
            this.fetch_height_requirements_data();
            this.fetch_order_data();
        },
        methods: {
            //SELECTS
            fetch_presells_data() {
                axios.get(`api/presells`)
                    .then(response => {
                        this.presells = response.data;
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            fetch_order_boards_data() {
                axios.get(`api/order-boards`)
                    .then(response => {
                        this.order_boards = response.data;
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            fetch_protective_covers_data() {
                axios.get(`api/protective-covers`)
                    .then(response => {
                        this.protective_covers = response.data;
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            fetch_height_requirements_data() {
                axios.get(`api/height-requirements`)
                    .then(response => {
                        this.height_requirements = response.data;
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            fetch_order_data(page) {
                axios.get(`api/orders`)
                    .then(response => {
                        this.orders = response.data;
                        // this.current_page = response.data.current_page;
                        // this.total_pages = response.data.last_page;
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            //ORDERS
            create_order(order, index) {
                axios.post(`api/orders`, order)
                    .then(response => {
                        this.orders[index] = response.data;
                        // this.orders.push(response.data);
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            addOrder(order) {
                this.create_orders(order);
            },
            update_order_data(order) {
                // console.log('TIME', order.requested_enclosure_delivery_date = moment.tz(
                //     order.requested_enclosure_delivery_date, "America/Toronto"));
                axios.put(`api/orders/` + order.id, order)
                    .then(response => {
                        order = response.data;
                        // this.current_page = response.data.current_page;
                        // this.total_pages = response.data.last_page;
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            saveElement(order, index) {
                if (order.id === -1) {
                    console.log(order);
                    this.create_order(order, index);
                } else {
                    // console.log('TIME', order.requested_enclosure_delivery_date = moment.tz(
                    //     order.requested_enclosure_delivery_date, "America/Toronto"));
                    this.update_order_data(order);
                }
                this.editId = null;
            },
            delete_order(order, index) {
                axios.delete(`api/orders/` + order.id)
                    .then(response => {
                        this.orders.splice(index, 1);
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            deleteElement(order, index) {
                if (confirm('Delete?')) {
                    if (order.id === -1) {
                        this.orders.splice(index, 1);
                    } else {
                        this.delete_order(order, index);
                    }
                    this.editId = null;
                }
            },
            editElement(order) {
                this.tempOrder = Object.assign({}, order);
                this.editId = order.id;
            },
            cancelElement(index) {
                Object.assign(this.orders[index], this.tempOrder);
                this.editId = null;
                this.tempOrder = null;
            }
        }
    }
</script>

<style scoped>

</style>
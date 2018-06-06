<template>
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Order Portal</a>
            </li>
            <li class="breadcrumb-item active">Orders</li>
        </ol>
        <div class="table-responsive">
            <button class="btn btn-outline-info" @click="addEmptyRow()">Add Row</button>
            <button @click="storeCSV()" class="btn btn-outline-success">Submit Your CSV</button>
            <vue-dropzone @vdropzone-file-added="uploadCSV()" :options="dropzoneOptions" id="dropzone"></vue-dropzone>
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
                        <div v-else>
                            {{order.height_requirement.value}}
                        </div>
                    </td>
                    <td>
                        <div v-if="order.id === editId">
                            <Datepicker v-model="order.requested_enclosure_delivery_date"
                                        :format="customFormatter"></Datepicker>
                            <!--<v-date-picker></v-date-picker>-->
                        </div>
                        <div v-else>
                            {{ order.requested_enclosure_delivery_date | moment("D MMMM YYYY") }}
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
    import moment from 'moment';
    import Datepicker from 'vuejs-datepicker';
    import Papa from 'papaparse';
    import vueDropzone from 'vue2-dropzone';
    import 'vue2-dropzone/dist/vue2Dropzone.min.css';

    export default {
        components: {
            Datepicker,
            Papa,
            vueDropzone
        },
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
                doc: null,
                dropzoneOptions: {
                    url: 'https://httpbin.org/post',
                    thumbnailWidth: 200,
                    addRemoveLinks: true,
                    dictDefaultMessage: "<i class='fas fa-cloud-upload-alt'></i>UPLOAD CSV"
                },
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
            create_orders(order) {
                axios.post(`api/orders`, order)
                    .then(response => {
                        console.log('ORDERS !!!', response.data);
                        for(let i = 0; i < response.data.length; i++) {
                            this.orders.push(response.data[i]);
                        }
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
            },
            customFormatter(date) {
                return moment(date).format('d MMM YYYY');
            },
            addEmptyRow: function () {
                let orderTemp = {
                    id: -1,
                    nsn: '',
                    presell: '',
                    order_board: '',
                    protective_cover: '',
                    height_requirement: '',
                    requested_enclosure_delivery_date: '',
                    delivery_note: '',
                    note: ''
                };
                this.orders.push(orderTemp);
                this.editId = -1;
            },
            //Paparse
            uploadCSV(e) {
                //otherwise JS doesnt read a function ...
                let that = this;
                const fileToLoad = event.target.files[0];
                const reader = new FileReader();
                reader.onload = fileLoadedEvent => {
                    Papa.parse(fileLoadedEvent.target.result, {
                        header: true,
                        dynamicTyping: true,
                        skipEmptyLines: true,
                        complete(results) {
                            console.log('complete', results);
                            this.doc = JSON.stringify(results.data, null, 2);

                            let csvJsonToDBArray = [];
                            for (let i = 0; i < results.data.length; i++) {
                                csvJsonToDBArray.push(that.csvJsonToApiJson(results.data[i]))
                            }
                            if (that.validateCSV(csvJsonToDBArray)) {
                                console.log('11111');
                                that.addOrder(csvJsonToDBArray);
                            }
                        },
                        error(errors) {
                            console.log('error', errors)
                        },
                    })
                };
                reader.readAsText(fileToLoad)
            },
            isEquivalent(a, b) {
                // Create arrays of property names
                var aProps = Object.getOwnPropertyNames(a);
                var bProps = Object.getOwnPropertyNames(b);

                // If number of properties is different,
                // objects are not equivalent
                if (aProps.length != bProps.length) {
                    return false;
                }

                for (var i = 0; i < aProps.length; i++) {
                    var propName = aProps[i];

                    // If values of same property are not equal,
                    // objects are not equivalent
                    if (a[propName] !== b[propName]) {
                        return false;
                    }
                }

                // If we made it this far, objects
                // are considered equivalent
                return true;
            },
            validateCSV(data) {
                for (let i = 0; i < data.length; i++) {
                    for (let j = i + 1; j < data.length; j++) {
                        if (this.isEquivalent(data[i], data[j])) {
                            this.errors.push(`Duplicated data in row: ${j}`);
                            return false;
                        }
                    }
                }


                const columnHeaders = [
                    'Submitter Email',
                    'NSN',
                    'Store Street Address',
                    'Store City',
                    'Store State',
                    'Store Zip',
                    '# of PreSells',
                    '# of Order Boards',
                    'Protective Covers',
                    'Height Requirements',
                    'Requested Enclosure Delivery Date',
                    'Delivery Notes'
                ];

                const dataHeaders = Object.keys(data[0]);

                return true;
            },
            csvJsonToApiJson(data) {
                let presell = this.getItemByValue(data['# of PreSells'], this.presells);
                let order_board = this.getItemByValue(data['# of Order Boards'], this.order_boards);
                let protective_cover = this.getItemByValue(data['Protective Covers'], this.protective_covers);
                let height_requirement = this.getItemByValue(data['Height Requirements'], this.height_requirements);
                return {
                    'nsn': data['NSN'],
                    'presell': presell,
                    'order_board': order_board,
                    'protective_cover': protective_cover,
                    'height_requirement': height_requirement,
                    'requested_enclosure_delivery_date':
                        data['Requested Enclosure Delivery Date'],
                    'delivery_note': data['Delivery Notes'],
                    'note' : null,
                    'ship_date' : null
                }
            },
            getItemByValue(value, collection) {
                for (let i = 0; i < collection.length; i++) {
                    if (collection[i].value === value) {
                        return collection[i];
                    }
                }
                return false;
            },
            storeCSV() {

            }
        },
    }
</script>



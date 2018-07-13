<template>
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Orders</a>
            </li>
            <li class="breadcrumb-item active">Bulk</li>
        </ol>
        <button @click="warning()" class="btn btn-outline-info mb-3">Download Template</button>
        <button @click="storeCSV()" class="btn btn-outline-success mb-3">Submit Your CSV</button>
        <div class="table-responsive">
            <vue-dropzone @vdropzone-file-added="uploadCSV" :options="dropzoneOptions" id="dropzone"></vue-dropzone>
            <table v-if="orders && orders.length" class="table table-bordered" id="dataTable" width="100%"
                   cellspacing="0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Store Address</th>
                    <th>NSN</th>
                    <th>Presells</th>
                    <th>Order Boards</th>
                    <th>Protective Covers</th>
                    <th>Height Requirement</th>
                    <th>Requested Enclosure Delivery Date</th>
                    <th>Delivery Notes</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="(order, index) in orders">
                    <tr>
                        <td>{{index + 1}}</td>
                        <td>
                            {{order.nsn}}
                        </td>
                        <td v-if="order.nsn">
                            {{address_by_nsn[index].store_address}}
                            {{address_by_nsn[index].store_city}}
                            {{address_by_nsn[index].store_state}}
                            {{address_by_nsn[index].store_zip}}
                        </td>
                        <td>
                            {{order.presell}}

                        </td>
                        <td>
                            {{order.order_board}}
                        </td>
                        <td>
                            {{order.protective_cover}}
                        </td>
                        <td>
                            {{order.height_requirement}}
                        </td>
                        <td>
                            {{ order.requested_enclosure_delivery_date }}
                        </td>
                        <td>
                            {{order.delivery_note}}
                        </td>
                    </tr>
                    <tr v-if="order.errors">
                        <td align="center" colspan="9">
                            <span v-for="error in order.errors">{{error}}</span>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Papa from 'papaparse';
    import vueDropzone from 'vue2-dropzone';
    import 'vue2-dropzone/dist/vue2Dropzone.min.css';
    import BulkValidator from './BulkUploadValidation';

    export default {
        name: "admin-order-bulk",
        components: {
            Papa,
            vueDropzone,
        },
        data: function () {
            return {
                orders: [],
                notifications: [],
                address_by_nsn: [],
                errors: [],
                doc: null,
                dropzoneOptions: {
                    url: 'null',
                    autoProcessQueue: false,
                    thumbnailWidth: 200,
                    addRemoveLinks: true,
                    dictDefaultMessage: "<i class='fas fa-cloud-upload-alt'></i>UPLOAD CSV"
                },
            }
        },
        methods: {
            //Parsing API from PAPARSE
            csvJsonToApiJson(data) {

                return {
                    'nsn': data['NSN'],
                    'presell': data['# of PreSells'],
                    'order_board': data['# of Order Boards'],
                    'protective_cover': data['Protective Covers'],
                    'height_requirement': data['Height Requirements'],
                    'requested_enclosure_delivery_date':
                        data['Requested Enclosure Delivery Date'],
                    'delivery_note': data['Delivery Notes'],
                    'note': null,
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
            //Paparse
            uploadCSV(e) {
                let that = this;
                const reader = new FileReader();
                reader.onload = fileLoadedEvent => {
                    Papa.parse(fileLoadedEvent.target.result, {
                        header: true,
                        dynamicTyping: true,
                        skipEmptyLines: true,
                        complete(results) {
                            console.log('papa', results);
                            that.cleanArrays();
                            if (!BulkValidator.validate(results.data)) {
                                that.$awn.alert(BulkValidator.validationErrors.toString());
                                console.log('ERROR VAL', BulkValidator.validationErrors);
                                return that.errors.concat(BulkValidator.validationErrors);
                            } else {
                                let csvJsonToDBArray = [];
                                for (let i = 0; i < results.data.length; i++) {
                                    csvJsonToDBArray.push(that.csvJsonToApiJson(results.data[i]))
                                }
                                axios.post(`api/orders-validate-bulk`, csvJsonToDBArray)
                                    .then(response => {
                                        that.orders = response.data.data;
                                        that.address_by_nsn = that.orders.map(order => order.nsn);
                                        that.fetch_address_by_nsn(that.address_by_nsn);
                                        console.log('ORDERS !!!', that.orders);
                                        console.log('ERR !!!', that.orders.errors);
                                    })
                                    .catch((error) => {
                                        that.orders = error.response.data.data;
                                        that.address_by_nsn = that.orders.map(order => order.nsn);
                                        that.fetch_address_by_nsn(that.address_by_nsn);
                                        // that.errors.push(error.response);
                                        that.$awn.warning("You have syntax errors in your file!");
                                        console.log('catch err', error.response);
                                    })
                            }
                        },
                        error(errors) {
                            console.log('error PAPA', errors)
                        },
                    })
                };
                reader.readAsText(event.target.files[0]);
            },
            storeCSV() {
                axios.post(`api/orders-bulk-store`, this.orders)
                    .then(response => {
                        this.orders = response.data.data;
                        this.$awn.success("You order has been stored!");
                        console.log('ORDERS STORED !!!', this.orders);
                    })
                    .catch((error) => {
                        this.errors.push(error.response);
                        console.log(error.response);
                    })
            },
            fetch_address_by_nsn(nsn) {
                axios.post(`api/address-by-nsn-bulk`, {data: nsn})
                    .then(response => {
                        this.address_by_nsn = response.data.data;
                        console.log('address_by_nsn', response.data.data);
                    })
                    .catch((error) => {
                        this.errors.push(error.response);
                        console.log(error.response);
                    })
            },
            warning() {
                this.$awn.alert("Deleted!");
            },
            cleanArrays() {
                this.orders = [];
                this.notifications = [];
                this.address_by_nsn = [];
                this.errors = [];
            }

        },
    }

</script>

<style scoped>

</style>
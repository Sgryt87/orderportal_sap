<template>
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Order Portal</a>
            </li>
            <li class="breadcrumb-item active">It Export</li>
        </ol>
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th>#</th>
                    <th>NSN</th>
                    <th>Coates Hardware Board Type</th>
                    <th>Coates Hardware Screen Protection</th>
                    <th>Coates Hardware # of Screens</th>
                    <th>Coates Hardware Configuration</th>
                    <th>Coates Order Received Date</th>
                    <th>Coates Order Quote Submitted</th>
                    <th>Coates Order PO Approved</th>
                    <th>Coates Order Ship Date</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody v-if="it_exports.data && it_exports.data.length">
                <tr v-for="(it_export, index) of it_exports.data">
                    <td>{{index + 1}}</td>
                    <td>{{it_export.nsn}}</td>
                    <td>{{it_export.coates_hardware_board_type}}</td>
                    <td>{{it_export.coates_hardware_screen_protection}}</td>
                    <td>{{it_export.coates_hardware_number_of_screens}}</td>
                    <td>{{it_export.coates_hardware_configuration}}</td>
                    <td>{{it_export.coates_order_received_date}}</td>
                    <td>{{it_export.coates_order_quote_submitted}}</td>
                    <td>{{it_export.coates_order_po_approved}}</td>
                    <td>{{it_export.coates_order_ship_date}}</td>
                    <td>
                        <div v-if="order.id === editId">
                            <i class="fa fa-check" @click="saveElement(it_export)"></i>
                            <i class="fas fa-ban" @click="cancelElement()"></i>
                        </div>
                        <div v-else>
                            <i class="fas fa-plus" @click="addElement(it_export)"></i>
                            <i class="far fa-edit" @click="editElement(it_export)"></i>
                            <i class="far fa-trash-alt" @click="deleteElement(it_export, index)"></i>
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
        name: "admin-it-export",

        data() {
            return {
                it_exports: [],
                errors: [],
                current_page: null,
                total_pages: null,
                editId: null
            }
        },
        created() {
            this.fetch_it_exports_data();
        },
        methods: {
            fetch_it_exports_data: function (page) {
                // axios.get(`api/it-exports?page=` + page)
                axios.get(`api/it-exports`)
                    .then(response => {
                        console.log(response);
                        this.it_exports = response.data;
                        // this.current_page = response.data.current_page;
                        // this.total_pages = response.data.last_page;
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            create_it_export(it_export) {
                axios.post(`api/it_exports`, it_export)
                    .then(response => {
                        this.it_exports.push(response.data);
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            addElement(it_export) {
                this.create_it_export(it_export);
            },
            update_it_export_data(it_export) {
                // console.log('TIME', it_export.requested_enclosure_delivery_date = moment.tz(
                //     it_export.requested_enclosure_delivery_date, "America/Toronto"));
                axios.put(`api/it_exports/` + it_export.id, it_export)
                    .then(response => {
                        it_export = response.data;
                        // this.current_page = response.data.current_page;
                        // this.total_pages = response.data.last_page;
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            saveElement(it_export) {
                // console.log('TIME', it_export.requested_enclosure_delivery_date = moment.tz(
                //     it_export.requested_enclosure_delivery_date, "America/Toronto"));
                this.update_it_export_data(it_export);
                this.editId = null;
            },
            delete_it_export(it_export, index) {
                axios.delete(`api/it_exports/` + it_export.id)
                    .then(response => {
                        this.it_exports.splice(index, 1);
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            deleteElement(it_export, index) {
                if (confirm('Delete?')) {
                    this.delete_it_export(it_export, index);
                    this.editId = null;
                }
            },
            editElement(it_export) {
                this.editId = it_export.id;
            },
            cancelElement() {
                this.editId = null;
            }
        }
    }
</script>

<style scoped>

</style>
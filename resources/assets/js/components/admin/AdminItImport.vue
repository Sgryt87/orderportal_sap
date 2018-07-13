<template>
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">It Import</li>
        </ol>

        <div v-if="it_imports">
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
                    <th>NSN</th>
                    <th>Store Address</th>
                    <th>Store City</th>
                    <th>Store State</th>
                    <th>Store Zip</th>
                    <th>Restaurant Drive Thru Type</th>
                    <th>Coates Hardware Board Type</th>
                    <th>Coates Hardware Screen Protection</th>
                    <th>Coates Hardware # of Screens</th>
                    <th>Coates Hardware Configuration</th>
                    <th>Coates Order Received Date</th>
                    <th>Coates Order Quote Submitted</th>
                    <th>Coates Order PO Approved</th>
                    <th>Coates Order Ship Date</th>
                </tr>
                </thead>
                <tbody v-if="it_imports && it_imports.length">
                <tr v-for="it_import of it_imports">
                    <td>{{it_import.nsn}}</td>
                    <td>{{it_import.store_address}}</td>
                    <td>{{it_import.store_city}}</td>
                    <td>{{it_import.store_state}}</td>
                    <td>{{it_import.store_zip}}</td>
                    <td>{{it_import.restaurant_drive_thru_type}}</td>
                    <td>{{it_import.coates_hardware_board_type}}</td>
                    <td>{{it_import.coates_hardware_screen_protection}}</td>
                    <td>{{it_import.coates_hardware_number_of_screens}}</td>
                    <td>{{it_import.coates_hardware_configuration}}</td>
                    <td>{{it_import.coates_order_received_date}}</td>
                    <td>{{it_import.coates_order_quote_submitted}}</td>
                    <td>{{it_import.coates_order_po_approved}}</td>
                    <td>{{it_import.coates_order_ship_date}}</td>
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
        <!--Errors-->
        <ul v-if="errors && errors.length">
            <li v-for="error of errors">
                {{error.message}}
            </li>
        </ul>
    </div>
</template>

<script>
    import axios from 'axios';
    import PaginateService from '../services/Pagination';

    export default {

        data() {
            return {
                it_imports: [],
                errors: [],

                page: 1,
                per_page: 20,
                total_rows: 0,
                total_pages: 0,
            }
        },
        methods: {
            navigate_to_the_page(page) {
                this.page = Number(page);
                this.fetch_data();
            },
            navigate_to_the_next() {
                if (this.current_page < this.total_pages) {
                    this.navigate_to_the_page(Number(this.current_page) + 1)
                }
            },
            navigate_to_the_prev() {
                if (this.current_page > 1) {
                    this.navigate_to_the_page(this.current_page - 1)
                }
            },
            fetch_data() {
                axios.get(`api/it-imports?page=${this.page}&per_page=${this.per_page}`)
                    .then(response => {
                        console.log(response);
                        this.it_imports = response.data.data;
                        this.current_page = response.data.page;
                        this.total_pages = response.data.total_pages;
                        this.total_rows = response.data.total_rows;
                    })
                    .catch((error) => {
                        this.errors.push(error.response.data.errors);
                        console.log(error.response);
                    })
            },
            paginate() {
                return PaginateService.pagination(this.page, this.total_pages);
            },
            change_per_page() {
                this.page = 1;
                this.fetch_data();
            }
        },
        created() {
            this.fetch_data();
        }
    }
</script>

<style scoped>

</style>
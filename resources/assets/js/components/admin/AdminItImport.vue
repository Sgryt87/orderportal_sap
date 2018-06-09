<template>
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">It Import</li>
        </ol>
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
                <tbody v-if="it_imports.data && it_imports.data.length">
                <tr v-for="it_import of it_imports.data">
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
                <li v-bind:class="[current_page ==  page ? 'active' : '']" class="page-item" v-for="page in
                paginate()">
                    <a class="page-link" @click="navigate_to_the_page(page)">{{page}}</a>
                </li>

                <li class="page-item">
                    <a class="page-link" aria-label="Next" @click="navigate_to_the_next()">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                    </a>
                </li>

                <!--custom-->
                <!--<li class="page-item">-->
                <!--<a class="page-link" aria-label="Previous" @click="navigate_to_the_prev()">-->
                <!--<span aria-hidden="true">&laquo;</span>-->
                <!--<span class="sr-only">Previous</span>-->
                <!--</a>-->
                <!--</li>-->

                <!--<li class="page-item" v-if="current_page >= 5" @click="navigate_to_the_page(1)">-->
                <!--<a class="page-link">1</a>-->
                <!--</li>-->
                <!--<li class="page-item" v-if="current_page >= 5">-->
                <!--<a class="page-link">...</a>-->
                <!--</li>-->
                <!--&lt;!&ndash;&ndash;&gt;-->
                <!--<li class="page-item" v-if="current_page > 2">-->
                <!--<a class="page-link"-->
                <!--@click="navigate_to_the_page(current_page-2)">{{current_page-2}}</a>-->
                <!--</li>-->
                <!--<li class="page-item" v-if="current_page > 1" @click="navigate_to_the_page(current_page-1)">-->
                <!--<a class="page-link">{{current_page -1}}</a>-->
                <!--</li>-->

                <!--<li class="page-item active">-->
                <!--<a class="page-link" @click="navigate_to_the_page(current_page)">{{current_page}}</a>-->
                <!--</li>-->

                <!--<li class="page-item" v-if="current_page < total_pages - 1">-->
                <!--<a class="page-link" @click="navigate_to_the_page(current_page+1)">{{current_page+1}}</a>-->
                <!--</li>-->

                <!--<li class="page-item" v-if="current_page < total_pages - 2">-->
                <!--<a class="page-link" @click="navigate_to_the_page(current_page+2)">{{current_page+2}}</a>-->
                <!--</li>-->

                <!--<li class="page-item" v-if="current_page < total_pages - 5">-->
                <!--<a class="page-link">...</a>-->
                <!--</li>-->
                <!--<li class="page-item" v-if="current_page < total_pages - 5" @click="navigate_to_the_page(total_pages)">-->
                <!--<a class="page-link">{{total_pages}}</a>-->
                <!--</li>-->

                <!--<li class="page-item">-->
                <!--<a class="page-link" aria-label="Next" @click="navigate_to_the_next()">-->
                <!--<span aria-hidden="true">&raquo;</span>-->
                <!--<span class="sr-only">Next</span>-->
                <!--</a>-->
                <!--</li>-->
                <!--custom-->

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
                current_page: null,
                total_pages: null
            }
        },
        methods: {
            navigate_to_the_page: function (page) {
                this.fetch_data(page);
            },
            navigate_to_the_next: function () {
                if (this.current_page < this.total_pages) {
                    this.navigate_to_the_page(this.current_page + 1)
                }
            },
            navigate_to_the_prev: function () {
                if (this.current_page > 1) {
                    this.navigate_to_the_page(this.current_page - 1)
                }
            },
            fetch_data: function (page) {
                axios.get(`api/it-imports?page=` + page)
                    .then(response => {
                        console.log(response);
                        this.it_imports = response.data;
                        this.current_page = response.data.current_page;
                        this.total_pages = response.data.last_page;
                    })
                    .catch(e => {
                        this.errors.push(e)
                    })
            },
            paginate() {
                return PaginateService.pagination(this.current_page, this.total_pages);
            }
        },
        mounted() {
            this.fetch_data(1);
        }
    }
</script>

<style scoped>

</style>
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import AdminHeader from './components/admin/AdminHeader';
import AdminNavbar from './components/admin/AdminNavbar';
import AdminFooter from './components/admin/AdminFooter';
import AdminDashboard from './components/admin/AdminDashboard';
import AdminOrderSingle from './components/admin/Orders/AdminOrderSingle';
import AdminOrderBulk from './components/admin/Orders/AdminOrderBulk';
import AdminOrdersHistory from './components/admin/Orders/AdminOrdersHistory';
import AdminItImport from './components/admin/AdminItImport';
import AdminItExport from './components/admin/AdminItExport';



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */



Vue.use(VueRouter);
// Vue.use(require('vue-moment'));
// 0. If using a module system (e.g. via vue-cli), import Vue and VueRouter
// and then call `Vue.use(VueRouter)`.
// 1. Define route components.
// These can be imported from other files
// 2. Define some routes
// Each route should map to a component. The "component" can
// either be an actual component constructor created via
// `Vue.extend()`, or just a component options object.
// We'll talk about nested routes later.


const routes = [
    {path: '/admin/dashboard', component: AdminDashboard},
    {path: '/admin/orders/single', component: AdminOrderSingle},
    {path: '/admin/orders/bulk', component: AdminOrderBulk},
    {path: '/admin/orders/history', component: AdminOrdersHistory},
    {path: '/admin/it-imports', component: AdminItImport},
    {path: '/admin/it-exports', component: AdminItExport}
];

// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = new VueRouter({
    routes // short for `routes: routes`
});

// 4. Create and mount the root instance.
// Make sure to inject the router with the router option to make the
// whole app router-aware.
const app = new Vue({
    el: '#app',
    router,
    components: {
        AdminHeader,
        AdminNavbar,
        AdminFooter
    }
});


// Now the app has started!


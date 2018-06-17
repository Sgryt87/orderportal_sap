/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


window.Vue = require('vue');

import VueRouter from 'vue-router';
import Routes from './routes';

import AdminHeader from './components/admin/AdminHeader';
import AdminNavbar from './components/admin/AdminNavbar';
import AdminFooter from './components/admin/AdminFooter';

import VCalendar from 'v-calendar';
import 'v-calendar/lib/v-calendar.min.css';

import VueAWN from "vue-awesome-notifications";






/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
var options = {
    labels: {
        tip: "Your custom tip box label"
    }
};


Vue.use(VueRouter);
Vue.use(VCalendar, {firstDayOfWeek: 2});
Vue.use(VueAWN, options);


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


// 3. Create the router instance and pass the `routes` option
// You can pass in additional options here, but let's
// keep it simple for now.
const router = new VueRouter({
    routes: Routes // short for `routes: routes`,
});

// 4. Create and mount the root instance.
// Make sure to inject the router with the router option to make the
// whole app router-aware.
const app = new Vue({
    el: '#app',
    router: router,
    components: {
        VCalendar,
        AdminHeader,
        AdminNavbar,
        AdminFooter
    }
});


// Now the app has started!


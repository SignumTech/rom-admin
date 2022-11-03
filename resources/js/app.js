/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import store from './store'
import router from './router'
import VModal from 'vue-js-modal'
import 'vue-js-modal/dist/styles.css'
import OtpInput from "@bachdgvn/vue-otp-input";
import Notifications from 'vue-notification'
import {ColorPicker, ColorPanel} from 'one-colorpicker'
import numeral from 'numeral';
import numFormat from 'vue-filter-number-format';
Vue.filter('numFormat', numFormat(numeral));
Vue.use(ColorPanel)
Vue.use(ColorPicker)
Vue.use(Notifications)
Vue.component("v-otp-input", OtpInput);
Vue.use(VModal)
Vue.use(require('vue-moment'));
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('app', require('./components/app.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
 store.dispatch('auth/me').then(() => {
    router.beforeEach((to, from, next) => {
        
        if (to.name !== 'Signin' && !store.state.auth.authenticated ){
          next({ name: 'Signin' })
        } 
        else{
          next()
        } 
     })
    const app = new Vue({
        el: '#app',
        store,
        router
    });
});

// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
require('../../assets/js/bootstrap')
import Vue from 'vue'
window.Vue = Vue;
import BootstrapVue from 'bootstrap-vue'
import VueSweetalert2 from 'vue-sweetalert2'
import VueGoogleAutocomplete from 'vue-google-autocomplete'
import App from './App'
import router from './router'
import money from 'v-money'


import Datetime from 'vue-datetime'
// You need a specific loader for CSS files
import 'vue-datetime/dist/vue-datetime.css'
 
Vue.use(Datetime)

import vueSignature from "vue-signature"
Vue.use(vueSignature)

import VueSignaturePad from 'vue-signature-pad';
Vue.use(VueSignaturePad);

import fullscreen from 'vue-fullscreen'
Vue.use(fullscreen)
// register directive v-money and component <money>
Vue.use(money, {precision: 4})
Vue.use(BootstrapVue)
Vue.use(VueSweetalert2)

import Axios from 'axios'
Vue.prototype.$http = Axios;
window._ = require('lodash');

import JsonExcel from 'vue-json-excel'
Vue.component('downloadExcel', JsonExcel)

import VueTelInput from 'vue-tel-input'
Vue.use(VueTelInput)

import VueLazyload from 'vue-lazyload'
Vue.use(VueLazyload)

// Vee validate
import VeeValidate from 'vee-validate';
Vue.use(VeeValidate, {fieldsBagName: 'formFields'})

import wysiwyg from "vue-wysiwyg"
Vue.use(wysiwyg, {
    image: {
        uploadURL: "/api/editor-images-upload",
        dropzoneOptions: {}
    },
})

import VueYoutube from 'vue-youtube'
Vue.use(VueYoutube)

import Notifications from 'vue-notification'
Vue.use(Notifications)

import Vuex from 'vuex';
Vue.use(Vuex);
import VuexPersistence from 'vuex-persist'
const vuexLocal = new VuexPersistence({
    storage: window.localStorage
})

const store = new Vuex.Store({
    state: {
        user: null,
        token: null // default value
    },
    getters: {
        getUser() {
            return state.user
        }
    },
    mutations: {
        changeUser(state, user) {
            console.log('Mutation: changeuser to', user)
            state.user = user
        },
        changeToken(state, token){
            state.token = token
        }
    },
    plugins: [vuexLocal.plugin]
})




/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    store,
    template: '<App/>',
    components: {
        App
    },
    data() {
        return {
            notifications: []
        }
    },
    mounted() {
        // Echo.channel('comment')
        // .listen('NotifyComment', (e) => {
        //     console.log(e)
        // });

        // this.$http.post('/notification/get').then(response => {
        //     console.log(response.data)
        //     this.notifications = response.data;

        // //this.updateNumber()
        // })

        // let userId = this.$store.state.user.id
        // console.log('Here user id', userId)
        // window.Echo.private('App.User.' + userId).notification((notification) => {
        //     console.log('Notification from pusher', notification)
        //     this.notifications.unshift(notification)
        //     //this.updateNumber()
        // })
    }
})

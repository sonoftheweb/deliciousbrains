import './bootstrap'
import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import store from './store/index'
import router from './router/router'
import DatetimePicker from 'vuetify-datetime-picker'
import io from "socket.io-client";
import Echo from "laravel-echo";

Vue.use(DatetimePicker)

store.dispatch('bootstrapApp').then(() => {
    router.initialize().then(() => console.log('Router initialized...'))

    let user = store.getters.user

    // if there is a user, listen to events
    if (user.hasOwnProperty('id')) {
        window.io = io;

        window.Echo = new Echo({
            broadcaster: 'socket.io',
            host: window.location.hostname + ':6001' // laravel's socket server host. Change to yours
        });

        window.Echo.channel('laravel_database_private-App.User.' + user.id)
        .listen('CsvFileUploaded', e => {
            $ev.$emit('show-uploading', e)
        })
        .listen('UploadImportDone', e => {
            $ev.$emit('hide-uploading', e)
        })
    }

    new Vue({
        vuetify,
        router,
        store,
        render: h => h(App)
    }).$mount('#app')
})

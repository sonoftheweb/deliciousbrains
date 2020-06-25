import './bootstrap'
import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import store from './store/index'
import router from './router/router'
import DatetimePicker from 'vuetify-datetime-picker'

Vue.use(DatetimePicker)

store.dispatch('bootstrapApp').then(() => {
    router.initialize().then(() => console.log('Router initialized...'))

    new Vue({
        vuetify,
        router,
        store,
        render: h => h(App)
    }).$mount('#app')
})

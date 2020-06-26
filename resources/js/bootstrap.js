import Vue from 'vue'
import lodash from 'lodash'
import axios from 'axios'
import Cookies from 'js-cookie'
import router from './router/router'
import store from './store/index'

const $eventBus = new Vue();

axios.defaults.baseURL = process.env.MIX_APP_URL
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.withCredentials = true

axios.interceptors.request.use(config => {
    // when a request is sent
    $eventBus.$emit('toggle-loading', true)

    // If token exist as a cookie add it to request
    if (Cookies.get('token')) {
        config.headers.common['Authorization'] = 'Bearer ' + Cookies.get('token')
    }

    return config
}, error => {
    return Promise.reject(error)
})

axios.interceptors.response.use(response => {

    $eventBus.$emit('toggle-loading', false )

    return response

}, error => {

    $eventBus.$emit('toggle-loading', false )

    let data = error.response.data

    if (error.response.status === 401) {

        store.dispatch('loggedOut').then(() => {
            window.location.replace("/login")
        })

        return
    }

    $eventBus.$emit({
        status: 'error',
        message: 'Unexpected error encountered. This issue has been logged.'
    })

    return Promise.reject(error)
})

Vue.prototype.$http = axios
Vue.prototype.$eventBus = $eventBus
window.$ev = $eventBus
Vue.prototype._ = lodash
Vue.prototype.$modalMaxWidths = {
    alerts: '450px',
    messages: '600px'
}

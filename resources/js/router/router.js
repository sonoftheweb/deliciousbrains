import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes'
import store from '../store/index'

Vue.use(VueRouter)
const router = new VueRouter({
    routes: routes,
    mode: 'history'
})

router.initialize = async () => {
    router.beforeEach(async (to, from, next) => {
        if (to.name === 'logout' || from.name === 'logout') {
            next()
        }

        if (to.name === 'login' && store.getters.authenticated) {
            return router.push('/')
        }

        if (store.getters.authenticated && store.getters.needsUserData) {
            await store.dispatch('fetchUserData')
            return next()
        }

        if (to.meta.hasOwnProperty('requiresAuth') && to.meta.requiresAuth && !store.getters.authenticated) {
            return router.push('/login')
        }

        return next();
    })
}

export default router

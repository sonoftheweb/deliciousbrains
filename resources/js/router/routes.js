import Login from '../components/pages/Login'
import Dashboard from '../components/pages/Dashboard'

export default [
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {displayableName: 'Login'}
    },
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
        meta: {requiresAuth: true}
    }
]

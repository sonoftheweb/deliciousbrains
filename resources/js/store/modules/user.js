import Cookies from 'js-cookie'
import axios from 'axios'
import router from "../../router/router";

const state = {
	token: Cookies.get('token') || '',
	authenticated: Cookies.get('token') !== undefined && Cookies.get('token') !== null && Cookies.get('token'),
	dataset: false
}

const mutations = {
	set_token(state, token) {
		state.token = token
	},
	set_authenticated(state, truthy) {
		state.authenticated = truthy
	},
	set_data_set(state, truthy) {
		state.dataset = truthy
	},
	subscribed(state, truthy) {
		state.subscribed = truthy
	}
}

const actions = {
	loggedIn(context, tokenData) {
		Cookies.set('token', tokenData.token, {
			expires: tokenData.expires_in
		})
		context.commit('set_token', tokenData.token)
		context.commit('set_authenticated', true)
		context.commit('set_data_set', true)
	},
	async fetchUserData(context) {
		await axios.get('/api/v1/users/me?load=details').then((response) => {
		    let userObject  = {
		        user: response.data.data
		    }

			context.commit('putSettingsInStore', userObject)
			context.commit('set_data_set', true)
			context.commit('updateState')
		})
	},
	async logout(context) {
		if (context.getters.authenticated) {
			try {
				await axios.post('/api/auth/logout').then(() => {
					context.dispatch('loggedOut')
				});
			} catch(e) {
				await context.dispatch('loggedOut')
			}
		}
	},
	loggedOut(context) {
		// use is forced out. Cookie id removed and user data is cleared
		Cookies.remove('token')
		context.commit('deleteUser')
		router.push('/')
	}
}

const getters = {
	domain: () => {
		return window.location.hostname
	}
}

export default {
	state: state,
	mutations,
	actions,
	getters
}

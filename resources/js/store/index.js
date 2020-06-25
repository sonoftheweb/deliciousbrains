import Vue from 'vue'
import Vuex from 'vuex'
import modules from './modules'
import axios from 'axios'
import moment from "moment";

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        openRequests: 0
    },
    modules,
    mutations: {
        putSettingsInStore(state, data) {
            for (let config in data) {
                let configValue = data[config]
                if (state.hasOwnProperty(config)) {
                    Object.keys(configValue).forEach(conf => {
                        state[config][conf] = configValue[conf]
                    })
                } else {
                    state[config] = configValue && configValue.data ? configValue.data : configValue
                }
            }
        },
        deleteUser(state) {
            this.replaceState({})
            let curState = state
            curState.user = {}
            this.replaceState(curState)
        },
        updateState(state) {
            // Force a state refresh to put it to an empty object {} and then send it back to the previous state.
            // Same solution used than in the deleteUser function
            let curState = state
            this.replaceState({})
            this.replaceState(curState)
        }
    },
    actions: {
        bootstrapApp(context) {
            return new Promise((resolve) => {
                let appUrl = '/api/v1/public/application'

                if (context.getters.user.token.length) {
                    appUrl = '/api/v1/application'
                }

                axios.get(appUrl).then(response => {
                    context.commit('putSettingsInStore', response.data)
                    if (context.getters.needsUserData) {
                        context.dispatch('fetchUserData').then(() => {
                            resolve()
                        })
                    } else {
                        resolve()
                    }
                }).catch(err => {
                    console.log(err);
                })
            })
        }
    },
    getters: {
        authenticated: state => state.user.authenticated,
        user: state => state.user,
        isLoggedIn: state => Object.prototype.hasOwnProperty.call(state.user, 'id'),
        needsUserData: state => {
            return !state.user.dataset && state.user.authenticated
        },
        numericFieldRule: () => {
            return [
                (v) => !v || v === '' || new RegExp(/^-?[\d]+(\.[\d]+)?$/g).test(v) || "Please enter a numeric value",
            ]
        },
        requiredFieldRule: () => {
            return [
                (v) => !!v || "Please fill in this field.",
            ]
        },
        emailValidationRules: () => {
            return [
                (v) => !!v || "Please fill in your email address",
                (v) => /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@(([[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(v) || "Please enter a valid email"
            ]
        },
        avatarRules: () => {
            return [
                (v) => !v || v.size < 2000000 || 'Avatar size should be less than 2 MB!',
            ]
        },
        formatMoney: () => {
            return cents => {
                const formatter = new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: 'USD',
                    minimumFractionDigits: 2
                })
                return formatter.format(cents / 100)
            }
        },
        firstLetterCaps: () => {
            return string => {
                return string.charAt(0).toUpperCase() + string.slice(1)
            }
        },
        dateFormat: state => 'ddd, D MMM',
        dateTimeFormat: state => 'D MMM, YYYY [at] hh:mm A',
        now: (state, getters) => moment().format(getters.dateFormat),
        nowDateTime: (state, getters) => moment().format(getters.dateTimeFormat),
        dateFormatted: (state, getters) => {
            return (date, format) => {
                if (!format)
                    format = getters.dateFormat

                return moment(date).format(format);
            }
        },
        dateTimeFormatted: (state, getters) => {
            return (date, format) => {
                if (!format)
                    format = getters.dateTimeFormat

                return moment(date).format(format);
            }
        },
        relativeDateTime: (state, getters) => {
            return (date) => {
                if (moment().format(getters.dateFormat) === moment(date).format(getters.dateFormat))
                    return 'Today'

                if (moment().subtract(1, 'day').format(getters.dateFormat) === moment(date).format(getters.dateFormat))
                    return 'Yesterday'

                return getters.dateFormatted(date)
            }
        }
    }
})

<template>
    <div>
        <alerts :alert-data="alertData" class=""/>
        <v-app>
            <app-bar/>
            <v-main>
                <v-container v-if="authenticated && isLoggedIn" fluid class="pa-0 white--text balance-area">
                    <balance-bar/>
                </v-container>
                <v-container fluid class="main pa-0" :class="containerClass()">
                    <transition name="fade" mode="out-in">
                        <router-view></router-view>
                    </transition>
                </v-container>
            </v-main>
        </v-app>
    </div>
</template>

<script>
    import Alerts from './components/utils/Alerts'
    import AppBar from './components/utils/AppBar'
    import BalanceBar from './components/utils/BalanceBar'
    import { mapGetters } from 'vuex'

    export default {
        components: {
            Alerts,
            AppBar,
            BalanceBar
        },
        computed: {
            ...mapGetters(['authenticated', 'isLoggedIn'])
        },
        data: () => ({
            alertData: {}
        }),
        methods: {
            containerClass() {
                let className = ''
                if (!this.authenticated)
                    className = 'fill-height container'

                if (this.$route.name === 'registration')
                    className = ''

                return className
            }
        },
        mounted() {
            this.$eventBus.$on('alert', alertData => {
                this.alertData = alertData
            })
        }
    };
</script>

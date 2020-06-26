<template>
    <div>
        <v-app-bar app light fixed flat color="white">
            <!--<v-app-bar-nav-icon v-if="authenticated" @click="toggleMenu"/>-->

            <img src="images/logo.svg" alt="YourBalance">
            <span class="title black--text ml-3 mr-5">Your<span class="title blue--text">Balance</span>
			</span>

            <v-progress-linear
                v-if="isLoading"
                active
                indeterminate
                absolute
                bottom
                color="indigo lighten-4"
            ></v-progress-linear>

            <v-spacer/>

            <v-btn icon>
                <v-icon small v-if="authenticated">mdi-bell</v-icon>
            </v-btn>

            <v-menu close-delay="30" open-on-hover offset-y v-if="authenticated">
                <template v-slot:activator="{ on }">
                    <v-btn text class="mr-2" v-on="on">
                        <v-avatar class="mx-3" color="indigo" size="24">
                            <v-icon v-if="!user.profile.avatar" dark>mdi-account-circle</v-icon>
                            <img v-else :src="user.profile.avatar" alt="John">
                        </v-avatar>
                        {{ user.name }}
                    </v-btn>
                </template>
                <v-list>
                    <v-list-item @click.stop="logout">
                        <v-list-item-title>Logout</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>
        <confirm-action/>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'
    import ConConfirm from '../utils/ConfirmAction'
    import ConfirmAction from "./ConfirmAction";

    export default {
        components: {
            ConfirmAction,
            ConConfirm
        },
        computed: {
            ...mapGetters(['user', 'authenticated'])
        },
        data() {
            return {
                isLoading: false
            }
        },
        methods: {
            toggleMenu() {
                this.$eventBus.$emit('toggle-menu')
            },
            logout() {
                this.$store.dispatch('logout')
            },
            gotoPage(page) {
                this.$router.push(page)
            }
        },
        mounted() {
            this.$eventBus.$on('toggle-loading', (val) => {
                this.isLoading = val;
            });
        }
    }
</script>

<style scoped>

</style>

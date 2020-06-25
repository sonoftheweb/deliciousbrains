<template>
    <v-layout fill-height>
        <v-flex xs12 md6>
            <div class="login-form">
                <div class="spacer"></div>
                <h2 class="thin font-weight-light mb-3">Login</h2>

                <v-form ref="form" lazy-validation @submit.prevent="login">
                    <v-text-field
                        v-model="auth.email"
                        type="email"
                        label="Email address"
                        placeholder="email@example.com"
                        prepend-inner-icon="mdi-at"
                        :rules="emailValidationRules"
                        @input="auth.email = $event.toLowerCase()"
                        outlined
                    />
                    <v-text-field
                        v-model="auth.password"
                        label="Password"
                        placeholder="Password"
                        prepend-inner-icon="mdi-key-variant"
                        :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                        :type="showPassword ? 'text' : 'password'"
                        :rules="requiredFieldRule"
                        @click:append="showPassword = !showPassword"
                        outlined
                    />
                    <v-checkbox class="mt-0" v-model="auth.remember_me" label="Remember me"></v-checkbox>
                    <div>
                        <v-btn depressed large dark color="info" class="mr-3" type="submit">Let's go!</v-btn>
                    </div>
                </v-form>

            </div>
        </v-flex>

    </v-layout>
</template>

<script>
    import { mapGetters } from 'vuex'
	export default {
        computed: {
            ...mapGetters(['emailValidationRules','requiredFieldRule', 'authenticated'])
        },
        data() {
            return {
                showPassword: false,
                auth: {
                    email: null,
                    password: null,
                    remember_me: false
                }
            }
        },
        methods: {
            login() {
                if (this.$refs.form.validate()) {
                    this.$http.post('/api/v1/public/login', this.auth).then(response => {
                        this.$store.dispatch('loggedIn', response.data.data).then(async () => {
                            window.location.replace('/')
                        })
                    }).catch(err => {
                        console.log('Encountered error: ', err)
                        this.$eventBus.$emit('alert', {
                            displayAlert: 'error',
                            message: 'We were unable to authenticate you with the credentials given. Please try again.'
                        })
                    })
                }
            }
        }
	}
</script>

<style scoped>

</style>

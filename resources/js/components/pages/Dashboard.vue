<template>
    <div>
        <v-alert :value="uploadAlert.show" :icon="false" class="mx-auto my-10 text-center" max-width="700px" type="warning">
            <v-progress-circular
                class="mr-1"
                indeterminate
                color="white"
                size="26"
            ></v-progress-circular>
            <span v-if="uploadAlert.show">We are importing {{ uploadAlert.data.uploadData.row_count - 1 }} balance entries. Sit tight.</span>
        </v-alert>
        <daily-list :key="listKey" v-if="events" :events="events" />
    </div>
</template>

<script>
    import DailyList from '../items/DailyList'

    import { mapGetters } from 'vuex'

	export default {
        computed: {
            ...mapGetters(['user'])
        },
		components: { DailyList },
        data() {
		    return {
		        uploadAlert: {
		            show: false,
                    data: null
                },
                events: null,
                listKey: Math.random()
            }
        },
        methods: {
		    fetchData() {
                this.$http.get('/api/v1/account-activity')
                    .then(response => {
                        this.$eventBus.$emit('accountBalance', response.data.balance);
                        this.events = response.data.data
                        this.listKey += 1
                    })
            }
        },
        mounted() {
		    this.fetchData()

            this.$eventBus.$on('show-uploading', (event) => {
                this.uploadAlert.data = event
                this.uploadAlert.show = true
            })

            this.$eventBus.$on('hide-uploading', (event) => {
                this.uploadAlert.data = null
                this.uploadAlert.show = false

                this.fetchData()
            })

            this.$eventBus.$on('refresh', () => {
                this.fetchData()
            })
        }
    }
</script>

<style scoped>

</style>

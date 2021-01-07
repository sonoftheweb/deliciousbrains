<template>
    <div>
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
                events: null,
                listKey: Math.random()
            }
        },
        methods: {
		    fetchData() {
                this.$http.get('/api/v1/users/me?load=account-details')
                    .then(response => {
                        this.$eventBus.$emit('accountBalance', response.data.data.account_balance);
                        this.events = response.data.data.account_activity
                        this.listKey += 1
                    })
            }
        },
        mounted() {
		    this.fetchData()

            this.$eventBus.$on('refresh', () => {
                this.fetchData()
            })
        }
    }
</script>

<style scoped>

</style>

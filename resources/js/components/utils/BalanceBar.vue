<template>
    <div class="py-10">

        <v-container class="pa-0 main container">
            <v-layout>
                <v-flex class="align-center mt-5">
                    <div class="font-weight-medium headline d-inline-block">Your Balance</div>
                    <v-btn color="info" class="mx-4" @click="addEntry">
                        <v-icon class="ml-0 mr-3">mdi-plus</v-icon>
                        Add Entry
                    </v-btn>
                    <v-btn color="info" @click="uploadActivities">
                        <v-icon class="ml-0 mr-3">mdi-upload</v-icon>
                        Import CSV
                    </v-btn>
                </v-flex>
                <v-flex class="text-right align-center">

                    <div class="text-uppercase">Total Balance</div>
                    <div class="balance" :class="balanceClass(balanceValue)" v-if="balance">
                        {{ balance.split('.')[0] }}.<span class="smaller">{{ balance.split('.')[1] }}</span>
                    </div>

                </v-flex>
            </v-layout>
        </v-container>

    </div>
</template>

<script>
    import { mapGetters } from 'vuex'

	export default {
	    computed: {
	        ...mapGetters(['formatMoney'])
        },
        data() {
	        return {
	            balance: null,
                balanceValue: null
            }
        },
        methods: {
            balanceClass(balance) {
                let className = ''
                if (balance >= 0)
                    className = 'green--text'
                else
                    className = 'red--text'

                return className
            },
            addEntry() {
                this.$eventBus.$emit('addEntry')
            },
            uploadActivities() {
                this.$eventBus.$emit('uploadEntries')
            }
        },
		mounted() {
		    this.$eventBus.$on('accountBalance', (balance) => {
		        this.balanceValue = balance
                this.balance = this.formatMoney(balance) // from cents to dollars
            })
        }
    }
</script>

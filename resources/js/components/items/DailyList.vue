<template>
    <div>

        <div v-if="days" v-for="(day, index) in days" :key="index" class="day-group">
            <v-layout class="mb-5">
                <v-flex sm12 md6 lg6>
                    <h3 class="grey--text text-uppercase mb-5">{{ relativeDateTime(day) }}</h3>
                </v-flex>
                <v-flex sm12 md6 lg6 class="text-right pr-3">
                    <h2 class="text-uppercase mb-5 amount" :class="sumOfDayClass(groupedEvents[day])">{{ formatMoney(sumInGroup(groupedEvents[day])) }}</h2>
                </v-flex>
            </v-layout>
            <list-item :items="groupedEvents[day]"/>
        </div>

        <v-dialog v-model="add" persistent max-width="800px">
            <v-card elevation="0">
                <v-card-title class="border-bottom py-7">
                    <h4>Add Balance Entry</h4>
                </v-card-title>
                <v-card-text>
                    <v-form>
                        <v-row>
                            <v-col cols="12" sm="12" md="4" lg="4">
                                <label class="custom-label">Label</label>
                                <v-text-field
                                    v-model="description"
                                    class="mt-2"
                                    placeholder="Groceries"
                                    single-line
                                    outlined
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="12" md="4" lg="4">
                                <label class="custom-label">Date</label>
                                <v-datetime-picker
                                    :text-field-props="datetimeField.fieldProps"
                                    v-model="datetimeField.dateTime"
                                    date-format="d MMMM, yyyy"
                                    time-format="'at' HH:mm a"
                                >
                                    <template v-slot:dateIcon>
                                        <v-icon>mdi-calendar</v-icon>
                                    </template>
                                    <template v-slot:timeIcon>
                                        <v-icon>mdi-clock-outline</v-icon>
                                    </template>
                                </v-datetime-picker>
                            </v-col>
                            <v-col cols="12" sm="12" md="4" lg="4">
                                <label class="custom-label">Amount</label>
                                <v-text-field
                                    class="mt-2"
                                    v-model="amount"
                                    prepend-inner-icon="mdi-currency-usd"
                                    placeholder="0.00"
                                    single-line
                                    outlined
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-form>
                </v-card-text>
                <v-card-actions class="border-top py-7">
                    <v-spacer></v-spacer>
                    <v-btn large class="info lighten-4 blue--text text--darken-3" @click="add = false" depressed>Cancel</v-btn>
                    <v-btn large class="info mr-5" depressed @click="saveItem">Save entry</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import ListItem from './ListItem'

	export default {
		props: ['events'],
        components: {
		    ListItem
        },
        computed: {
		    ...mapGetters(['user', 'relativeDateTime', 'formatMoney', 'nowDateTime'])
        },
        data() {
		    return {
		        groupedEvents: null,
                add: false,
                datetimeField: {
		            dateTime: new Date(),
                    fieldProps: {
                        'single-line': true,
                        'outlined': true,
                        'class': 'mt-2',
                        placeholder: null
                    }
                },
                description: null,
                amount: null,
                days: null
            }
        },
        methods: {
		    filterEventsByDateCreated() {
		        let obj = this.events

                obj = obj.reduce(function (r, a) {
                    r[a.group] = r[a.group] || []
                    r[a.group].push(a)
                    return r
                }, Object.create(null))

                this.days = Object.keys(obj)
                    .sort(function order(key1, key2) {
                        if (key1 < key2) return 1
                        else if (key1 > key2) return -1
                        else return 0
                    });

                this.groupedEvents = obj;
            },
            sumInGroup(data) {
                return data.reduce((a, b) => a + (b.amount || 0), 0)
            },
            sumOfDayClass(data) {
		        let positive = data.filter(d => {
		            return d.amount > 0
                })

                if (positive.length)
                    return 'green--text'
                else
                    return 'grey--text'
            },
            saveItem() {
		        let data = {
		            description: this.description,
                    amount: this.amount,
                    activity_date: this.datetimeField.dateTime,
                    user_id: this.user.profile.user_id
                }

                this.$http.post('/api/v1/account-activity', data).then(response => {
                    this.$eventBus.$emit('alert', {
                        status: 'success',
                        message: 'Operation successful'
                    })
                    this.add = false
                    this.$eventBus.$emit('refresh')
                })
            }
        },
        mounted() {
		    this.filterEventsByDateCreated()

            this.datetimeField.fieldProps.placeholder = this.nowDateTime

            this.$eventBus.$on('addEntry', () => {
                this.add = true
            })
        }
    }
</script>

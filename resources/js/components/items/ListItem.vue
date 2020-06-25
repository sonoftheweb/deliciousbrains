<template>
    <div>
        <v-hover v-if="itemsInDay" v-for="(item, index) in itemsInDay" :key="index" v-slot:default="{ hover }">
            <v-card class="mb-5" :elevation="hover ? 3 : 1" :ref="'activity_' + item.id + '_card'">
                <v-card-text>

                    <v-layout>
                        <v-flex sm12 md6 lg6>
                            <h2 class="mb-3 grey--text text--darken-3">{{ item.description }}</h2>
                            {{ dateTimeFormatted(item.activity_date) }}
                        </v-flex>
                        <v-flex sm12 md4 lg4>
                            <div v-if="hover" class="mt-3 pr-10 text-right">
                                <v-btn class="blue--text" text @click="showEdit('activity_' + item.id + '_card', item)">edit</v-btn>
                                <v-btn class="blue--text" text>delete</v-btn>
                            </div>
                        </v-flex>
                        <v-flex sm12 md2 lg2 class="text-right">
                            <h2 class="text-uppercase amount mt-4" :class="classOfItem(item.amount)">{{ formatMoney(item.amount) }}</h2>
                        </v-flex>
                    </v-layout>

                    <v-card flat class="edit-item hidden-screen-only mt-10">
                        <v-card-text class="border-top">
                            <v-form>
                                <v-row>
                                    <v-col cols="12" sm="12" md="5" lg="5">
                                        <label class="custom-label">Label</label>
                                        <v-text-field
                                            v-model="item.description"
                                            class="mt-2"
                                            placeholder="Groceries"
                                            single-line
                                            outlined
                                        ></v-text-field>
                                    </v-col>
                                    <v-col cols="12" sm="12" md="5" lg="5">
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
                                    <v-col cols="12" sm="12" md="2" lg="2">
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
                            <v-btn large class="info lighten-4 blue--text text--darken-3" depressed>Cancel</v-btn>
                            <v-btn large class="info mr-5" depressed @click="updateItem(item)">Update entry</v-btn>
                        </v-card-actions>
                    </v-card>

                </v-card-text>
            </v-card>
        </v-hover>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'

	export default {
		props: ['items'],
        computed: {
            ...mapGetters(['formatMoney', 'dateTimeFormatted'])
        },
        data() {
		    return {
		        itemsInDay:null,
                datetimeField: {
                    dateTime: new Date(),
                    fieldProps: {
                        'single-line': true,
                        'outlined': true,
                        'class': 'mt-2',
                        placeholder: null
                    }
                },
                amount: null
            }
        },
        methods: {
            classOfItem(amount) {
                if (amount >= 0)
                    return 'green--text'
                else
                    return 'black--text'
            },
            showEdit(ref, item) {
                this.datetimeField.dateTime = new Date(item.activity_date)
                this.amount = item.amount / 100

                let form = this.$refs[ref][0].$el.querySelector('.edit-item');
                let isHidden = form.classList.contains('hidden-screen-only')

                if (isHidden) {
                    form.classList.remove('hidden-screen-only')
                } else {
                    form.classList.add('hidden-screen-only')
                }
            },
            updateItem(item) {
                item.activity_date = this.datetimeField.dateTime
                item.amount = this.amount

                this.$http.put('/api/v1/account-activity/' + item.id, item).then(response => {
                    this.$eventBus.$emit('alert', {
                        status: 'success',
                        message: 'Operation successful'
                    })
//                    this.showEdit('activity_' + item.id + '_card', item)
                    this.$eventBus.$emit('refresh')
                })
            }
        },
        mounted() {
		    this.itemsInDay = this.items // to avoid modifying the data in state
        }
    }
</script>

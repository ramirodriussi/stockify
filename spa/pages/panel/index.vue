<template>
    
    <v-row>

        <v-col cols="12">

            <v-alert
            color="grey lighten-4"
            prominent
            icon="mdi-calendar"
            class="rounded-lg mb-0"
            >
            <v-row align="center">
                <v-col class="grow">
                Estadísticas de {{ calendarDate }}
                </v-col>
                <v-col class="shrink">

                    <!-- calendar dialog  -->

                    <v-dialog
                        ref="dialog"
                        v-model="dialog"
                        width="290px"
                    >
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn
                            v-bind="attrs"
                            v-on="on"
                            outlined
                            color="var(--custom-grey)"
                            rounded
                            >
                            Personalizar fecha
                            </v-btn>
                        </template>
                        <v-date-picker
                        v-model="dates"
                        scrollable
                        range
                        locale="es-AR"
                        color="var(--primary)"
                        >
                        <v-spacer></v-spacer>
                        <v-btn
                            @click="dialog = false"
                            rounded
                            outlined
                            color="var(--primary)"
                        >
                            Cancelar
                        </v-btn>
                        <v-btn
                            color="var(--primary)"
                            @click="changeDate"
                            rounded
                            dark
                        >
                            Aplicar
                        </v-btn>
                        </v-date-picker>
                    </v-dialog>

                </v-col>
            </v-row>
            </v-alert>

        </v-col>

        <v-col cols="12" md="4">

            <DashboardCard title="Ventas del día" color="blue-grey lighten-3" :info="sales" icon="mdi-currency-usd" />

        </v-col>

        <v-col cols="12" md="4">

            <DashboardCard title="Ganancias del día" color="blue-grey lighten-3" :info="earnings" icon="mdi-cash-register" />

        </v-col>

        <v-col cols="12" md="4">

            <DashboardCard :title="`Ganancias de ${month}`" color="blue-grey lighten-3" :info="earningsThisMonth" icon="mdi-calendar" />

        </v-col>

    </v-row>

</template>

<script>

    // import { mapState } from 'vuex';
    import DashboardCard from '@/components/DashboardCard';

    export default {

        components: {
            DashboardCard,
        },

        data() {
         
            return {

                dialog: false,
                dates: [],
                from: '',
                to: '',
                month: '',
                sales: 0,
                earnings: 0,
                earningsThisMonth: 0,

            }

        },
        
        computed: {

            calendarDate() {

                let date;
                let today = this.$moment(this.dates[0]).isSame(this.$moment(), 'day');

                if(this.dates.length == 1){

                    if(today){
                        date = 'hoy';
                    } else {
                        date = this.formatDate(this.dates[0]);
                    }

                } else {

                    date = `${this.formatDate(this.dates[0])} al ${this.formatDate(this.dates[1])}`

                }

                return date;

            },

            // ...mapState(['skeleton']),

        },

        watch: {

            dates(){

                if(this.dates.length > 1){
                    this.from = this.dates[0];
                    this.to = this.dates[1];
                } else {
                    this.from = this.dates[0];
                    this.to = '';
                }

            }

        },

        mounted(){

            let today = (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10);

            this.dates.push(today);
            this.from = today;
            this.month = this.getCurrentMonth();

            this.getData();

            // let resp = this.$axios.get(`/api/dashboard?from=${this.from}`);

            // this.$store.commit('showSkeleton', true);

        },

        methods: {

            formatDate(date){

                return this.$moment(date).format('LL');

            },

            async getData(){

                this.$store.commit('showSkeleton', true);

                let resp =  await this.$axios.get(`/api/dashboard?from=${this.from}&to=${this.to}`);
 
                this.sales = resp.data.data.sales;
                this.earnings = this.formatCurrency(resp.data.data.earnings);
                this.earningsThisMonth = this.formatCurrency(resp.data.data.earningsThisMonth);

                this.$store.commit('showSkeleton', false);

            },

            formatCurrency(val){

                let currencyLocale = Intl.NumberFormat('es-AR', {
                    style: 'currency',
                    currency: 'ARS'
                });

                return currencyLocale.format(val);

            },

            getCurrentMonth(){

                let arr = [
                    'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
                ];

                let date = new Date();

                return arr[date.getMonth()];

           },

           changeDate(){

               this.getData();
               this.dialog = false;

           }

        }

    }

</script>

<style scoped>

  .v-sheet--offset {
    top: -24px;
    position: relative;
  }

</style>
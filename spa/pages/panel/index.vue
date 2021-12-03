<template>
    
    <v-row>

        <v-col cols="12" md="4">

            <DashboardCard title="Ventas del día" color="deep-purple lighten-2" />

        </v-col>

        <v-col cols="12" md="4">

            <DashboardCard title="Ganancias del día" color="deep-purple lighten-2" />

        </v-col>

        <v-col cols="12" md="4">

            <DashboardCard title="Ganancias de febrero" color="green lighten-2" />

        </v-col>

        <v-col cols="12" md="4">

            <v-date-picker
                v-model="dates"
                range
                full-width
                class="rounded-xl"
            ></v-date-picker>

            {{dates}}

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

                dates: [],
                from: '',
                to: '',

            }

        },
        
        computed: {

            // ...mapState(['skeleton']),

        },

        watch: {

            dates(){

                console.log('cambia');

            }

        },

        mounted(){

            let today = (new Date(Date.now() - (new Date()).getTimezoneOffset() * 60000)).toISOString().substr(0, 10);

            this.dates.push(today);
            this.from = today;

            // let resp = this.$axios.get(`/api/dashboard?from=${this.from}`);

            // this.$store.commit('showSkeleton', true);

        },

        methods: {

            changeDate(){

                // console.log('bb');

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
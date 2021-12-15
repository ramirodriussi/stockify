<template>

    <v-app>
        <v-main>
            <Nuxt />
        </v-main>

      <Snackbar/>

    </v-app>

</template>

<script>

    import Snackbar from '@/components/Snackbar';

    export default {

        components: {
            Snackbar,
        }
        
    }

</script>
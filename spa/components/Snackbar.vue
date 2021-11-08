<template>

    <v-snackbar
      :value="snackbar.show"
      @input="closeSnackbar"
      :timeout="timeout"
      :color="snackbar.color"
      bottom
      center
      shaped
    >
      {{ snackbar.text }}

      <template v-slot:action="{ attrs }">
        <v-btn
          color="white"
          text
          v-bind="attrs"
          @click="closeSnackbar"
        >
          Cerrar
        </v-btn>
      </template>
    </v-snackbar>

</template>

<script>

  import { mapState } from 'vuex';

  export default {

    data () {

      return {

        timeout: 3000,

      }

    },

    computed: {

      ...mapState(['snackbar']),

    },

    methods: {

      closeSnackbar(){

            this.$store.commit('showSnackbar', null);

      }

    },


  }

</script>

<style scoped>

  >>>.v-snack__content {
    justify-content: normal;
  }

  .fas {
    margin-right: 10px;
  }

</style>
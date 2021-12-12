<template>

  <section>

    <v-container fill-height>

      <v-row justify="center" align="center">

        <v-col cols="12" md="4">

          <LoginRegister/>

        </v-col>

      </v-row>

    </v-container>

  </section>

</template>

<script>

  export default {

    middleware: 'logged',
    layout: 'login'

  }

</script>

<style scoped>

  section {
    width: 100%;
    height: 100%;
    background-image: url('~/assets/images/bg1.jpg');
    background-size: cover;
    background-position: center;
  }

</style>
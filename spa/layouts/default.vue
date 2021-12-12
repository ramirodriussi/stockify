<template>
  <v-app>

    <!-- navigation drawer  -->

    <v-navigation-drawer
      v-if="$auth.loggedIn"
      app
      clipped
      :permanent="$vuetify.breakpoint.mdAndUp"
      :mini-variant="($vuetify.breakpoint.mdAndUp) ? drawer : false"
      mini-variant-width="75"
      v-model="drawer"
      :overlay="$vuetify.breakpoint.smAndDown"
      width="290"
    >
      <Navbar />

    </v-navigation-drawer>

    <!-- app bar  -->

    <v-app-bar v-if="$auth.loggedIn" class="px-3" app clipped-left flat color="var(--primary)" dark>

      <v-app-bar-nav-icon @click="drawer = !drawer"></v-app-bar-nav-icon>

      <v-toolbar-title>{{ user.name }}</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-menu offset-y transition="slide-y-transition">

        <template v-slot:activator="{ on }">
          <v-avatar
            size="40"
            v-on="on"
          >
          <img :src="user.logo != null ? user.logo : require('~/assets/images/user.png')" alt="logo" @click="dialog = true" style="cursor:pointer">
          </v-avatar>
        </template>

        <v-list class="elevation-4">

          <v-list-item color="var(--primary)" @click="openDialog()">

              <v-list-item-icon>
                <v-icon>mdi-account-circle-outline</v-icon>
              </v-list-item-icon>
              <v-list-item-content>                
                <v-list-item-title>Mi Perfil</v-list-item-title>      
              </v-list-item-content>

          </v-list-item>

          <div class="d-flex justify-center mt-3 mb-2 mx-3">

          <v-btn
            large
            outlined
            color="error"
            @click="logout"
            block
            rounded
          >

            <v-icon
              left
              dark
            >
              mdi-logout
            </v-icon>

            Cerrar Sesión
          </v-btn>

          </div>

        </v-list>

      </v-menu>

    </v-app-bar>

    <!-- main  -->

    <v-main>
      <v-container fill-height d-block align-content-start fluid class="pa-5 indigo lighten-5">
        <Nuxt />
      </v-container>
    </v-main>

    <!-- components  -->

      <Snackbar/>
      <ProfileDialog v-if="$auth.loggedIn"/>

  </v-app>
</template>

<script>

  import { mapState } from 'vuex';
  import ProfileDialog from '@/components/ProfileDialog';
  import Navbar from '@/components/Navbar';

  export default {

  components: {
    ProfileDialog,
    Navbar
  },

  data () {
    return {
      drawer: true,
      clipped: false,
      on: false,
      emailVerified: false,

    }
  },

  computed: {

    ...mapState(['user']),

  },

  beforeMount() {

    if(localStorage.getItem('admin')){

      this.$store.dispatch('setUser');

    }



  },

  methods: {

        openDialog(){

          this.$store.commit('setProfileDialog');

        },

        async logout(){

          try {

              await this.$auth.logout();
              this.$store.commit('setUser', {});
              localStorage.removeItem('admin');

          } catch (error) {
              
              console.log(error);

          }

        }

  },

}



</script>

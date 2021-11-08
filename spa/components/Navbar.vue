<template>

  <div>

      <v-list shaped>

        <div v-for="(item, i) in items" :key="i">

          <v-list-item exact-path color="primary" v-if="!item.subitems" :to="item.route">
            <v-list-item-icon>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>

            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item>

          <v-list-group
            v-else
            :prepend-icon="item.icon"
          >
            <template v-slot:activator>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </template>

              <v-list-item
                v-for="(subitem, i) in item.subitems"
                :key="i"
                :to="subitem.route"
                link
                exact-path
              >

                <v-list-item-icon>
                  <v-icon v-text="subitem.icon"></v-icon>
                </v-list-item-icon>

                <v-list-item-title v-text="subitem.title"></v-list-item-title>

              </v-list-item>

          </v-list-group>

        </div>

      </v-list>

  </div>

</template>

<script>

  export default {
    data () {
      return {
        // drawer: true,
        items: [

            { title: 'Inicio', icon: 'mdi-monitor-dashboard', route: {name:'panel'}},
            { title: 'Reservas', icon: 'mdi-calendar-check', route: {name:'panel-reservations'}},
            { title: 'Mensajes', icon: 'mdi-comment-text-multiple-outline', route: {name:'panel-questions'}},
            { title: 'Ayuda', icon: 'mdi-help-circle-outline', route: {name:'panel-help'}},

        ],
        // right: null
      }
    },




  }

</script>

<style scoped>

  >>>.v-list-item {
    padding: 0 35px;
  }

  .v-list-item__title {
    font-size: .875rem;
    /* color: #a2a2a2; */
  }

  >>>.v-icon {
    font-size: 20px;
    color: #a2a2a2;
  }

</style>
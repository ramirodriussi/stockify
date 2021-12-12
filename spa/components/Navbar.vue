<template>

  <div>

      <v-list shaped>

        <div v-for="(item, i) in filteredItems" :key="i">

          <div v-if="item.show">

            <v-list-item exact-path color="red accent-1" v-if="!item.subitems" :to="item.route">
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

            { title: 'Inicio', icon: 'mdi-monitor-dashboard', route: {name:'panel'}, show: true},
            { title: 'Usuarios', icon: 'mdi-account-multiple-outline', route: {name:'panel-users'}, show: true},
            { title: 'Locales', icon: 'mdi-storefront-outline', route: {name:'panel-stores'}, show: true},
            { title: 'Productos', icon: 'mdi-shopping-outline', route: {name:'panel-products'}, show: true},
            { title: 'Ventas', icon: 'mdi-cash-multiple', route: {name:'panel-sales'}, show: true},

        ],
        // right: null
      }

    },

    computed: {

      filteredItems(){

        if(!this.$auth.hasScope("Administrador")){

          let list = ['Locales', 'Usuarios'];

          this.items.map((item) => {

            if(list.includes(item.title)){

              return item.show = false;

            }

          })

        }

        return this.items;
    
      }

    }

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
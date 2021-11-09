<template>
  <div class="text-center">
    <v-pagination
      v-model="page"
      :length="pagination.total"
      class="mt-4"
      @input="onPageChange"
      circle
      color="blue darken-3"      
    ></v-pagination>
  </div>
</template>

<script>

  import { mapState } from 'vuex';

  export default {

    data () {

      return {

        data:[],

      }

    },

    props: {

      pagination:{
        type: Object
      },
      
    },

    computed: {

      ...mapState('pagination', {
          paginationSection: state => state.paginationSection,
      }),

      ...mapState('search', {
          searchBoxPaginationActive: state => state.searchBoxPaginationActive
      }),

      page: {

        get() {

          return this.$store.getters['pagination/getPagination'].page;

        }, 

        set(value) {

          this.$store.commit('pagination/changePage', value);

        }

      }

    },

    methods: {

      onPageChange(){

        let url;

        if (this.paginationSection == 'products') {   

            if (this.searchBoxPaginationActive) {

                url = '/api/bookshop/reservations?word='+this.$store.getters['search/getSearch']+'&status='+this.$store.getters['search/getStatusModel']+'&page='+this.$store.getters['pagination/getPagination'].page;

            } else {

                url = '/api/bookshop/reservations?page='+this.$store.getters['pagination/getPagination'].page;

            }

        }

        // stores

        if (this.paginationSection == 'stores') {

            if (this.searchBoxPaginationActive) {

              url = '/api/stores?word='+this.$store.getters['search/getSearch']+'&page='+this.$store.getters['pagination/getPagination'].page;

            } else {

              url = '/api/stores?page='+this.$store.getters['pagination/getPagination'].page;
              
            }
        
        }

        this.$store.dispatch('pagination/setItemsPagination', url);

      }

    }

  }

</script>

<style scoped>

  >>>.v-icon {
    font-size: .7rem;
  }

</style>
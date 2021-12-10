<template>
  <div class="text-center" v-if="pagination.total > 1">
    <v-pagination
      v-model="page"
      :length="pagination.total"
      class="mt-4"
      @input="onPageChange"
      circle
      color="var(--primary)"      
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

          (this.searchBoxPaginationActive) 
              ? url = `/api/products?word=${this.$store.getters['search/getSearch']}&page=${this.$store.getters['pagination/getPagination'].page}`
              : url = `/api/products?page=${this.$store.getters['pagination/getPagination'].page}`;

        }

        // stores

        if (this.paginationSection == 'stores') {

            (this.searchBoxPaginationActive) 
                ? url = `/api/stores?word=${this.$store.getters['search/getSearch']}&page=${this.$store.getters['pagination/getPagination'].page}`
                : url = `/api/stores?page=${this.$store.getters['pagination/getPagination'].page}`;
   
        }

        // sales

        if (this.paginationSection == 'sales') {

            (this.searchBoxPaginationActive) 
                ? url = `/api/sales?word=${this.$store.getters['search/getSearch']}&page=${this.$store.getters['pagination/getPagination'].page}`
                : url = `/api/sales?page=${this.$store.getters['pagination/getPagination'].page}`;
   
        }

        // users

        if (this.paginationSection == 'users') {

            (this.searchBoxPaginationActive) 
                ? url = `/api/users?word=${this.$store.getters['search/getSearch']}&page=${this.$store.getters['pagination/getPagination'].page}`
                : url = `/api/users?page=${this.$store.getters['pagination/getPagination'].page}`;
   
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
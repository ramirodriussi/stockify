<template>

	<div>
		
        <v-text-field
        v-model="search"
        prepend-inner-icon="mdi-magnify"
        label="Buscar"
        hide-details
        @change="getItems"
        class="mb-4"
        outlined
        dense
        ></v-text-field>

	</div>

</template>

<script>

	import { mapState } from 'vuex';

	export default {

		data(){

			return {

                //

			}

		},

		props: {

			section: String,

		},

		mounted() {

			this.$store.commit('search/setSearch', null);
			this.$store.commit('search/setSearchBoxPaginationActive', false);

		},

		computed: {

            ...mapState('pagination', {
                pagination: state => state.pagination,
            }),

            ...mapState('search', {
                status: state => state.status
            }),

			search : {

				get(){
					return this.$store.getters['search/getSearch'];
				},
				set(value){
					this.$store.commit('search/setSearch', value);
				}

			},


		},

		methods: {

			getItems(){

                let url = `/api/${this.section}?word=${this.$store.getters['search/getSearch']}`;

				this.$store.commit('search/setSearchBoxPaginationActive', true);
				this.$store.commit('pagination/changePage', 1);
				this.$store.dispatch('pagination/setItemsPagination', url);

			},

		},

	}


</script>

<style scoped>

	>>>.v-icon {
	font-size: 14px;
	}

</style>
<template>

	<div>
		
		<v-select 
		v-if="showStoreInput"
		:items="stores" 
		item-value="id" 
		item-text="store"
		dense
		outlined
		prepend-inner-icon="mdi-magnify"
		label="Buscar por local"
		hide-details
		>

			<template v-slot:prepend-item>
				<v-list-item @click="changeStore('Todos los locales', 'all')">
					<v-list-item-content>
						<v-list-item-title>Todos los locales</v-list-item-title>
					</v-list-item-content>
				</v-list-item>
			</template>

			<template v-slot:item="data">
				<v-list-item @click="changeStore(data.item.store, data.item.id)">
					<v-list-item-content>
						<v-list-item-title>{{ data.item.store }}</v-list-item-title>
					</v-list-item-content>
				</v-list-item>
			</template>

		</v-select>

		<v-chip class="ml-0 mb-3" close v-if="chip" @click:close="removeStatus">{{ chip }}</v-chip>

        <v-text-field
		v-if="!showStoreInput"
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

                store: '',
				chip: '',
				showStoreInput: false,

			}

		},

		props: {

			section: String,

		},

		beforeMount(){

			(this.section == 'products') ? this.showStoreInput = !this.showStoreInput : this.showStoreInput;

		},

		mounted() {

			this.$store.commit('search/setSearch', '');
			this.$store.commit('search/setSearchBoxPaginationActive', false);

		},

		computed: {

             ...mapState('pagination', {
                pagination: state => state.pagination,
            }),

            ...mapState('search', {
                status: state => state.status
            }),

            ...mapState('stores', {
                stores: state => state.stores
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

                let url;

				(this.section == 'products')
					? url = `/api/${this.section}?word=${this.$store.getters['search/getSearch']}&store=${this.store}`
					: url = `/api/${this.section}?word=${this.$store.getters['search/getSearch']}`;
			
				this.$store.commit('search/setSearchBoxPaginationActive', true);
				this.$store.commit('pagination/changePage', 1);
				this.$store.dispatch('pagination/setItemsPagination', url);

			},

			changeStore(store, id){

				this.chip = store;
				this.store = id;
				this.showStoreInput = !this.showStoreInput;
				this.getItems();
				

			},
			
			removeStatus(){

				this.store = '';
				this.chip = '';
				this.$store.commit('search/setSearch', '');
				this.showStoreInput = !this.showStoreInput;
				this.getItems();

			}

		},

	}


</script>

<style scoped>

	>>>.v-icon {
	font-size: 14px;
	}

</style>
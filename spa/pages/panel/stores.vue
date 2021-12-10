<template>

    <div>

        <v-row v-if="skeleton">

            <v-col cols="12">

                <v-skeleton-loader
                    type="card-heading"
                    class="mb-4"
                ></v-skeleton-loader>

                <v-skeleton-loader
                    type="table-thead, table-tbody"
                ></v-skeleton-loader>

            </v-col>

        </v-row>

        <v-row v-else>

            <v-col cols="12">

                <v-row>

                    <v-col>

                        <v-card class="v-card-panel pa-4 elevation-4 mb-4">

                            <div class="d-flex justify-space-between flex-column flex-sm-row">

                                <h2 class="panel-v-card-title"><i class="far fa-file-alt"></i> Locales</h2>

                                <v-btn 
                                small 
                                class="mt-4 mt-md-0 pa-3" 
                                rounded
                                color="var(--primary)"
                                dark
                                @click="addDialog"
                                >
                                <v-icon small>mdi-plus</v-icon>
                                Agregar local
                                </v-btn>

                            </div>

                        </v-card>
                        
                    </v-col>

                </v-row>

                <v-card class="elevation-4 pa-3">
            
                    <v-col cols="12" sm="4">
                        <SearchBox section="stores" />
                    </v-col>

                    <v-data-table
                    :headers="headers"
                    :items="stores"
                    :loading="loading"
                    hide-default-footer
                    no-data-text="¡Aún no tenés locales agregados!"
                    >

                        <template v-slot:item.actions="{ item }">						

                            <v-btn class="mr-1" depressed fab x-small text color="var(--primary)" @click="editDialog(item.id)">
                                <v-icon dark small>mdi-pencil</v-icon>
                            </v-btn>

                            <v-btn depressed fab x-small text color="error" @click="deleteBook(item.id)">
                                <v-icon dark small>mdi-delete-outline</v-icon>
                            </v-btn>

                        </template>

                    </v-data-table>

                    <Pagination :pagination="pagination" />

                </v-card>

            </v-col>

        </v-row>

        <StoreDialog/>

    </div>

</template>

<script>

	import { mapState, mapGetters } from 'vuex';
    import StoreDialog from '@/components/StoreDialog';
    import Pagination from '@/components/Pagination';
    import SearchBox from '@/components/SearchBox';

	export default {

        components: {
            StoreDialog,
            Pagination,
            SearchBox,
        },
		
		data () {
			return {

				dialog: false,
				headers: [
					{
					text: 'Local',
					align: 'left',
					sortable: false,
					value: 'store'
					},
					{ text: 'Acciones', value: 'actions', sortable: false, align:'right' },
				],

			}
		},

        computed: {

            ...mapState(['loading','skeleton']),

            ...mapState('pagination', {
                pagination: state => state.pagination
            }),

            ...mapState('stores', {
                stores: state => state.stores
            }),

            ...mapGetters('pagination', ['getPagination'])


        },

        beforeCreate(){

            this.$store.commit('showSkeleton', true);

        },

        mounted() {

            this.$store.commit('pagination/changePage', 1);

            this.getItems();

        },

        methods: {

                addDialog(){
                    this.$store.commit('stores/showDialog', {add:true,id:null});
                },

                editDialog(id){
                    this.$store.commit('stores/showDialog', {add: false,id});
                },

                getItems(){

                    this.$store.commit('pagination/changePaginationSection', {section:'stores'});

                    console.log('pagin', this.pagination.page);

                    let page;

                    if ((this.$store.getters['stores/getStores'].length - 1) === 0) {
                        page = this.pagination.page - 1;
                    } else {
                        page = this.pagination.page;
                    }

                    let url = `/api/stores?page=${page}`;

                    this.$store.dispatch('pagination/setItemsPagination', url);

                },

                deleteBook(id){

                        this.$dialog.error({
                            title: 'Eliminar local',
                            text: '¿Estás seguro que querés eliminar este local?',
                            actions: {
                                false: 'No, cancelar',
                                true: {
                                    text: 'Sí, eliminar',
                                    color: 'error',
                                }

                            },
                        })
                        .then(resp=>{

                            if (resp) {

								this.$axios.delete(`/api/stores/${id}`)
								.then(() => {

                        			this.$store.commit('showSnackbar', {color:'success',text:'Local eliminado correctamente'});

                                	this.getItems();

								})
								.catch(() => {

                        			this.$store.commit('showSnackbar', {color:'error',text:'No se pudo eliminar el local'});

								})

                            }

                        });

                },


        },

	}
</script>

<style scoped>

.v-card__title {
	background-color: #2BDB87;
	color: white;
}

.v-card {
	border-radius: 7px;
}

>>>.v-input__slot {
	margin-bottom: 0;
}

>>>.v-input--selection-controls:not(.v-input--hide-details) .v-input__slot {
	margin-bottom: 0;
}

</style>
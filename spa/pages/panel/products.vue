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

                            <div class="d-flex justify-space-between align-center flex-column flex-sm-row">

                                <h2 class="panel-v-card-title"><i class="far fa-file-alt"></i> Productos</h2>

                                <v-btn 
                                small 
                                class="mt-md-0 pa-3" 
                                rounded
                                color="var(--primary)"
                                dark
                                @click="addDialog"
                                >
                                <v-icon small>mdi-plus</v-icon>
                                Agregar producto
                                </v-btn>

                            </div>

                        </v-card>
                        
                    </v-col>

                </v-row>

                <v-card class="elevation-4 pa-3">
            
                    <v-row class="d-flex justify-space-between flex-column flex-sm-row">
                        
                        <v-col cols="12" sm="4">

                            <SearchBox section="products" />

                        </v-col>

                        <div v-if="$auth.hasScope('Administrador')" class="ma-4">

                            <v-btn outlined class="mr-1" :href="`${url}/api/products/export`" tag="a" download rounded small color="var(--primary)">
                                <v-icon small>mdi-download</v-icon> Exportar
                            </v-btn>

                            <v-btn outlined rounded small color="var(--primary)" @click="showImportDialog">
                                <v-icon small>mdi-upload</v-icon> Importar
                            </v-btn>

                        </div>

                    </v-row>

                    <v-data-table
                    :headers="headers"
                    :items="products"
                    :loading="loading"
                    hide-default-footer
                    no-data-text="¡Aún no tenés productos agregados!"
                    >

                        <template v-slot:item.store.store="{item}">

                            <v-chip 
                            small 
                            color="red accent-1"
                            text-color="white"
                            >
                                {{ item.store.store }}
                            </v-chip>
                   
                        </template>

                        <template v-slot:item.price="{ item }">						

                            <td>
                                $ {{item.price}}
                            </td>

                        </template>

                        <template v-slot:item.actions="{ item }">						

                            <v-btn class="mr-1" depressed fab x-small text color="grey darken-1" @click="editDialog(item.id)">
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

        <ProductDialog/>
        <ImportProductDialog/>

    </div>

</template>

<script>

	import { mapState, mapGetters } from 'vuex';
    import ProductDialog from '@/components/ProductDialog';
    import ImportProductDialog from '@/components/ImportProductDialog';
    import Pagination from '@/components/Pagination';
    import SearchBox from '@/components/SearchBox';

	export default {

        components: {
            ProductDialog,
            Pagination,
            SearchBox,
            ImportProductDialog,
        },
		
		data () {
			return {

				dialog: false,
				headers: [
					{
					text: 'Producto',
					align: 'left',
					sortable: false,
					value: 'product'
					},
                    { text: 'Código', value: 'code', sortable: false, align:'left' },
                    { text: 'Precio', value: 'price', sortable: false, align:'left' },
                    { text: 'Stock', value: 'stock', sortable: false, align:'left' },
                    { text: 'Notificar (debajo de)', value: 'stock_notification_below', sortable: false, align:'left' },
                    { text: 'Local', value: 'store.store', sortable: false, align:'left' },
					{ text: 'Acciones', value: 'actions', sortable: false, align:'center' },
				],
                selectedFile: '',
                url: 'http://localhost:8000',

			}
		},

        computed: {

            ...mapState(['loading','skeleton']),

            ...mapState('pagination', {
                pagination: state => state.pagination
            }),

            ...mapState('products', {
                products: state => state.products
            }),

            ...mapGetters('pagination', ['getPagination']),


        },

        beforeCreate(){

            this.$store.commit('showSkeleton', true);

        },

        mounted() {

            this.$store.commit('pagination/changePage', 1);

            this.getItems();

            this.$store.dispatch('stores/getStores');

        },

        methods: {

                addDialog(){
                    this.$store.commit('products/showDialog', {add:true,id:null});
                },

                editDialog(id){
                    this.$store.commit('products/showDialog', {add: false,id});
                },

                showImportDialog(){

                    this.$store.commit('setDialog');

                },

                getItems(){

                    this.$store.commit('pagination/setSection', {section:'products', mutation:'setProducts'});

                    let page;

                    if ((this.$store.getters['products/getProducts'].length - 1) === 0) {
                        page = this.pagination.page - 1;
                    } else {
                        page = this.pagination.page;
                    }

                    let url = `/api/products?page=${page}`;

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

								this.$axios.delete(`/api/products/${id}`)
								.then(() => {

                        			this.$store.commit('showSnackbar', {color:'success',text:'Producto eliminado correctamente'});

                                	this.getItems();

								})
								.catch(() => {

                        			this.$store.commit('showSnackbar', {color:'error',text:'No se pudo eliminar el producto'});

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
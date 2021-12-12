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

                                <h2 class="panel-v-card-title"><i class="far fa-file-alt"></i> Ventas</h2>

                                <v-btn 
                                small 
                                class="mt-4 mt-md-0 pa-3" 
                                rounded
                                color="var(--primary)"
                                dark
                                @click="addDialog"
                                >
                                <v-icon small>mdi-plus</v-icon>
                                Nueva venta
                                </v-btn>

                            </div>

                        </v-card>
                        
                    </v-col>

                </v-row>

                <v-card class="elevation-4 pa-3">
            
                    <v-col cols="12" sm="4">
                        <SearchBox section="sales" />
                    </v-col>

                    <v-data-table
                    :headers="headers"
                    :items="sales"
                    :loading="loading"
                    hide-default-footer
                    no-data-text="¡Aún no tenés ventas!"
                    >

                        <template v-slot:item.total="{ item }">						

                            <td>
                                $ {{item.total}}
                            </td>

                        </template>

                        <template v-slot:item.actions="{ item }">						

                            <v-btn class="mr-1" depressed fab x-small text color="grey darken-1" @click="editDialog(item.id)">
                                <v-icon dark small>mdi-pencil</v-icon>
                            </v-btn>

                            <v-btn depressed fab x-small text color="error" @click="deleteSale(item.id)">
                                <v-icon dark small>mdi-delete-outline</v-icon>
                            </v-btn>

                        </template>

                    </v-data-table>

                    <Pagination :pagination="pagination" />

                </v-card>

            </v-col>

        </v-row>

        <SaleDialog/>

    </div>

</template>

<script>

	import { mapState, mapGetters } from 'vuex';
    import SaleDialog from '@/components/SaleDialog';
    import Pagination from '@/components/Pagination';
    import SearchBox from '@/components/SearchBox';

	export default {

        components: {
            SaleDialog,
            Pagination,
            SearchBox,
        },
		
		data () {
			return {

				dialog: false,
				headers: [
					{
					text: 'Venta',
					align: 'left',
					sortable: false,
					value: 'sale_id'
					},
					{ text: 'Total', value: 'total', sortable: false, align:'left' },
					{ text: 'Forma de pago', value: 'payment_type', sortable: false, align:'left' },
					{ text: 'Fecha', value: 'created_at', sortable: false, align:'left' },
					{ text: 'Acciones', value: 'actions', sortable: false, align:'right' },
				],

			}
		},

        computed: {

            ...mapState(['loading','skeleton']),

            ...mapState('pagination', {
                pagination: state => state.pagination
            }),

            ...mapState('sales', {
                sales: state => state.sales
            }),

            ...mapGetters('pagination', ['getPagination']),

            sales(){
                return this.$store.getters['sales/formatedSales'];
            },

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
                    this.$store.commit('sales/showDialog', {add:true,id:null});
                },

                editDialog(id){
                    this.$store.commit('sales/showDialog', {add: false,id});
                },

                getItems(){

                    this.$store.commit('pagination/changePaginationSection', {section:'sales'});

                    let page;

                    if ((this.$store.getters['sales/getSales'].length - 1) === 0) {
                        page = this.pagination.page - 1;
                    } else {
                        page = this.pagination.page;
                    }

                    let url = `/api/sales?page=${page}`;

                    this.$store.dispatch('pagination/setItemsPagination', url);

                },

                deleteSale(id){

                        this.$dialog.error({
                            title: 'Eliminar venta',
                            text: '¿Estás seguro que querés eliminar esta venta?',
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

                                this.$dialog.warning({
                                    title: 'Reintegrar stock',
                                    text: '¿Deseas reintegrar el stock a los productos eliminados de esta venta?',
                                    actions: {
                                        false: 'No, no reintegrar',
                                        true: {
                                            text: 'Sí, reintegrar',
                                            color: 'warning',
                                        }

                                    },
                                })
                                .then(resp=>{

                                    this.$axios.delete(`/api/sales/${id}`, {params: {reintegrate: resp}})
                                    .then(() => {

                                    	this.$store.commit('showSnackbar', {color:'success',text:'Venta eliminada correctamente'});

                                    	this.getItems();

                                    })
                                    .catch(() => {

                                    	this.$store.commit('showSnackbar', {color:'error',text:'No se pudo eliminar la venta'});

                                    })

                                    // if (resp) {

                                    //     console.log('reintegrar');

                                    // } else {

                                    //     console.log('no reintegrar');

                                    // }

                                });

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
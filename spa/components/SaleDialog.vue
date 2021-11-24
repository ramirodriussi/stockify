<template>

		<v-dialog
			:value="dialog.show"
			width="800"
			@click:outside="closeDialog"
		>
		
			<v-card>
				
			<v-card-title
				class="headline headline-white blue darken-3"
				primary-title
				v-text="dialog.add ? 'Nueva venta' : `Detalle de venta - ${saleId}`"
			>

			</v-card-title>

			<v-progress-linear :indeterminate="loading"></v-progress-linear>

			<v-card-text class="mt-4">

                <v-row>

					<v-col cols="12">

						<v-form ref="form">

							<v-row>

								<v-col cols="12" md="6">

									<v-text-field
										label="Código de producto"
										outlined
										v-model="code"
										dense
										@change="getItemByCode"
									></v-text-field>

								</v-col>

								<v-col cols="12" md="6">

									<v-select
									:items="payments"
									item-text="payment"
									item-value="id"
									label="Medio de pago"
									outlined
									dense
									v-model="paymentType"
									:rules="[rules.required]"
									></v-select>

								</v-col>

							</v-row>

						</v-form>

					</v-col>

					<v-col cols="12">

						<v-simple-table>
							<template v-slot:default>
							<thead>
								<tr>
									<th>
										
									</th>                            
									<th class="text-left">
										Producto
									</th>
									<th class="text-left">
										Código
									</th>
									<th class="text-left">
										Precio
									</th>
									<th class="text-left">
										Cantidad
									</th>
									<th class="text-left">
										Total
									</th>
								</tr>
							</thead>
							<tbody>
								<tr
								v-for="(item, i) in formatedItems"
								:key="i"
								>
								<td class="text-center ma-0 pa-0">
									<v-btn
										class="ma-2"
										text
										icon
										color="red lighten-2"
										@click="deleteItem(item.id)"
									>
										<v-icon>mdi-delete</v-icon>
									</v-btn>
								</td>
								<td>
									{{ item.product }}
								</td>
								<td>
									{{ item.code }}
								</td>
								<td>
									{{ item.price }}
								</td>
								<td>

									<v-text-field
										outlined
										class="quantity-input rounded-lg mr-2 flex-shrink-0 flex-grow-1"
										height="43"
										hide-details
										dense
										type="number"
										min="1"
										max="99"
										:value="item.quantity"
										@change="updateQuantity($event, item.id)"
									></v-text-field>

								</td>
								<td>$ {{ item.total }}</td>
								</tr>
							</tbody>
							</template>
						</v-simple-table>

						<v-alert
						color="grey lighten-4"
						class="mt-3"
						>

							<v-row>
								<v-col cols="12">
									<p class="mr-5 text-right ma-0"><strong>Total de la venta: $ {{ totalCart }}</strong></p>
								</v-col>
							</v-row>
										
						</v-alert>

					</v-col>

				</v-row>


			</v-card-text>

			<v-divider></v-divider>

			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn
				color="blue darken-3"
				rounded
				outlined
				dark	
				@click="closeDialog"
				>
				Cerrar
				</v-btn>
				<v-btn
				color="green darken-1"
				rounded
				dark	
				@click="saveSale"
				v-if="dialog.add"
				>
				Guardar
				</v-btn>				
			</v-card-actions>
			</v-card>
		</v-dialog>


		<!-- </div>  -->

</template>

<script>

	import { mapState, mapGetters } from 'vuex';

	export default {

		data(){

			return {

				loading: false,
				code: '',
				paymentType: '',
				totalCart: 0,
				saleId: '',
				payments: [
					{id: 'Efectivo', payment: 'Efectivo'},
					{id: 'Tarjeta de crédito', payment: 'Tarjeta de crédito'},
					{id: 'Tarjeta de débito', payment: 'Tarjeta de débito'},
					{id: 'Transferencia Bancaria', payment: 'Transferencia Bancaria'},
				],
				rules: {
					required : value => !!value || 'Debés completar este campo',
				}
				
			}

		},

		computed: {

			dialog: {

				get() {
					return this.$store.getters['sales/getDialog'];
				},

				set() {
					
					console.log('abc');
					// this.$store.commit('panel/bookshop/books/showDialog', {add: false});

					// this.$store.commit('clearObject', 'appointment');
				}

			},

            formatedItems(){

                let arr = [];
                let obj;

                this.totalCart = 0;

                this.cart.map(item => {

                    console.log('item', item);

                    obj = {};

					obj.id = item.id;
                    obj.product = item.product;
                    obj.code = item.code;
                    obj.price = item.price;
                    obj.quantity = item.quantity;
                    obj.total = Number(item.quantity * item.price).toFixed(2);

                    this.totalCart += Number(obj.total);

                    arr.push(obj);

                });

                this.totalCart = Number(this.totalCart).toFixed(2);

                // if(this.dialog && !arr.length){
                //     this.dialog = false;
                // }

                return arr;

            },

			...mapGetters('pagination', ['getPagination']),

			...mapState('sales', {
				cart: state => state.cart
			}),

		},

		watch: {

			'dialog.id'() {

				if(this.dialog.id){
					this.getData();
				}

			}

		},

		methods: {

			async getItemByCode(){

				this.$store.dispatch('sales/setItemCart', {code:this.code});

			},

			async getData(){

				this.loading = !this.loading;

				let resp = await this.$axios.get(`/api/sales/${this.dialog.id}`);

				this.paymentType = resp.data.payment_type;
				this.saleId = resp.data.sale_id;

				this.$store.commit('sales/fillCart', resp.data.products.data);

				this.loading = !this.loading;

			},

			clearDialog(){

				this.code = '';
				this.paymentType = '';
				this.saleId = '';
				this.loading = false;

				// Object.keys(this.form).map(key => {

				// 	this.form[key] = '';

				// });

			},

			async saveSale(){

				if (this.$refs.form.validate()) {

					try {
						
						await this.$axios.post('/api/sales', {cart:this.cart,payment:this.paymentType});

						this.$store.commit('pagination/changePaginationSection', {section:'sales'});

						let url = '/api/sales?page='+this.getPagination.page;

						this.$store.dispatch('pagination/setItemsPagination', url);

						this.$store.commit('showSnackbar', {color:'success', text: 'Agregado correctamente.'});

						this.closeDialog();
						
					} catch {
						
						this.$store.commit('showSnackbar', {color:'error', text: 'No se pudo agregar. Intentá nuevamente.'});

					} finally {

						this.loading = !this.loading;

					}

				}

			},

			closeDialog(){

				this.$store.commit('sales/showDialog', {add: false});
				this.clearDialog();
				this.$store.commit('sales/clearCart');
				this.$refs.form.resetValidation();

			},

            async updateQuantity(quantity, id){

				if(this.dialog.id){

					try {
						await this.$axios.put(`/api/sales/${this.dialog.id}`, {input:'quantity', value:quantity, id})
						
					} catch {
						
						this.$store.commit('showSnackbar', {color:'error',text:'No se pudo actualizar. Intentá nuevamente'});

						return;

					}

				}

                this.$store.commit('sales/updateItemCart', {quantity,id});

                this.$store.commit('showSnackbar', {color:'success',text:'Cantidad actualizada correctamente'});
                
            },

            deleteItem(id){

				this.$dialog.error({
					title: 'Eliminar producto',
					text: '¿Estás seguro que querés eliminar el producto de la venta?',
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

						if(this.dialog.id){

							this.$axios.delete(`/api/sales/${this.dialog.id}`, {params: {id}})
							.catch(() => {

								console.log('a');

								// this.$store.commit('showSnackbar', {color:'error',text:'No se pudo eliminar la venta'});

								return;

							})

						}

						console.log('b');

						// this.$store.commit('sales/deleteItemCart', {item});

						// this.$store.commit('showSnackbar', {color:'success',text:'Producto eliminado correctamente'});

					}

				});


                
            },

		}

	}


</script>

<style scoped>

	.fade-enter-active, .fade-leave-active {
		transition: opacity .5s;
	}
	.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
		opacity: 0;
	}

	.search-box {
		/* padding: 12px; */
		height: 180px;
		/* border:1px solid grey; */
		overflow-y:scroll;
		overflow-x:hidden;
	}

	/* width */
	::-webkit-scrollbar {
	width: 5px;
	}

	/* Track */
	::-webkit-scrollbar-track {
	background: #f1f1f1;
	}

	/* Handle */
	::-webkit-scrollbar-thumb {
	background: #d5d5d5;
	}

	/* Handle on hover */
	::-webkit-scrollbar-thumb:hover {
	background: #b6b6b6;
	}

    .v-expansion-panels {
        border-radius: 10px;
        border: 1px solid #dfdfdf;
    }

    .v-expansion-panel::before {
        box-shadow: none;
    }

	>>>.v-expansion-panel-content__wrap {
		padding: 0 10px 0 10px;
	}

</style>
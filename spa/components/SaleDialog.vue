<template>

		<v-dialog
			:value="dialog.show"
			width="800"
			@click:outside="closeDialog"
		>
		
			<v-card>
				
			<v-card-title
				class="headline headline-white toolbar-color"
				primary-title
				v-text="dialog.add ? 'Nueva venta' : `Detalle de venta - ${saleId}`"
			>

			</v-card-title>

			<v-progress-linear :indeterminate="loading"></v-progress-linear>

			<v-card-text class="mt-4">

				<v-form ref="form">

					<v-row>

						<v-col cols="12">

								<v-row v-if="dialog.add">

									<v-col cols="12" md="9">
										<h4>Colocá el cursor sobre "código de producto" y utilizá un lector de barras o iniciá el lector con la cámara web.</h4>
									</v-col>

									<v-col cols="12" md="3">

										<v-btn small outlined rounded color="var(--primary)" v-if="!camera" @click="camera = !camera">
											Iniciar cámara web
										</v-btn>
										<v-btn small outlined rounded color="var(--primary)" v-else @click="camera = !camera">
											Detener cámara web
										</v-btn>

									</v-col>

									<v-col cols="12">
										<hr>
									</v-col>

									<v-col cols="12" v-if="camera">

										<div class="reader-box">
											<v-quagga :onDetected="logIt" :readerSize="readerSize" :readerTypes="['ean_reader']"></v-quagga>
										</div>

									</v-col>

								</v-row>

								<v-row>

									<v-col v-if="dialog.add" cols="12" md="4">

										<v-text-field
											label="Producto"
											outlined
											dense
											:value="word"
											@change="getItemByName($event)"
											color="var(--primary)"
										></v-text-field>

									</v-col>

									<v-col v-if="dialog.add" cols="12" md="4">

										<v-text-field
											label="Código de producto"
											outlined
											:value="code"
											dense
											@change="getItemByCode($event)"
											color="var(--primary)"
										></v-text-field>

									</v-col>

									<v-col cols="12" md="4">

										<v-select
										:items="payments"
										item-text="payment"
										item-value="id"
										label="Medio de pago"
										outlined
										dense
										v-model="paymentType"
										:rules="[rules.required]"
										@change="updatePaymentType"
										color="var(--primary)"
										></v-select>

									</v-col>

									<v-col cols="12" v-if="products.length">

										<h4>Elegí el producto que querés agregar al carrito</h4>

										<v-simple-table>
											<template v-slot:default>
											<thead>
												<tr>
												<th class="text-left">
													Producto
												</th>
												<th class="text-left">
													Código
												</th>
												<th class="text-left">
													Stock
												</th>
												<th class="text-left">
													Local
												</th>
												<th class="text-left">
													Agregar
												</th>
												</tr>
											</thead>
											<tbody>
												<tr
												v-for="(item, i) in products"
												:key="i"
												>
												<td>{{ item.product }}</td>
												<td>{{ item.code }}</td>
												<td>{{ item.stock }}</td>
												<td>{{ item.store.store }}</td>
												<td>
													<v-btn class="mr-1" depressed fab x-small text color="success" @click="setItemCart(item.id)">
														<v-icon dark small>mdi-check</v-icon>
													</v-btn>
												</td>
												</tr>
											</tbody>
											</template>
										</v-simple-table>

									</v-col>

								</v-row>

						</v-col>

						<v-col cols="12" v-if="cartItems.length">

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
											Stock
										</th>
										<th class="text-left">
											Total
										</th>
									</tr>
								</thead>
								<tbody>
									<tr
									v-for="(item, i) in cartItems"
									:key="i"
									>
									<td class="text-center ma-0 pa-0">
										<v-btn
											class="ma-2"
											text
											icon
											color="red lighten-2"
											@click="deleteItem(item)"
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
											max="100"
											:value="item.quantity"
											@change="updateQuantity($event, item)"
										></v-text-field>

									</td>
									<td>{{ item.stock }}</td>
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

				</v-form>

			</v-card-text>

			<v-divider></v-divider>

			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn
				color="var(--primary)"
				rounded
				outlined
				dark	
				@click="closeDialog"
				>
				Cerrar
				</v-btn>
				<v-btn
				color="var(--primary)"
				rounded
				:dark="!loading"
				@click="saveSale"
				v-if="dialog.add"
				:disabled="loading"
				:loading="loading"
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
				paymentType: '',
				saleId: '',
				payments: [
					{id: 'Efectivo', payment: 'Efectivo'},
					{id: 'Tarjeta de crédito', payment: 'Tarjeta de crédito'},
					{id: 'Tarjeta de débito', payment: 'Tarjeta de débito'},
					{id: 'Transferencia Bancaria', payment: 'Transferencia Bancaria'},
				],
				rules: {
					required : value => !!value || 'Debés completar este campo',
					// max(item){
					// 	return value => value <= item.stock || 'No tenés stock suficiente';
					// },
				},
				
				camera: false,
				readerSize: {
					width: 640,
					height: 480
				},
				detecteds: [],
			
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

			cartItems(){
				return this.$store.getters['sales/cartItems'];
			},

			totalCart(){
				return this.$store.getters['sales/totalCart'];
			},

			...mapGetters('pagination', ['getPagination']),

			...mapState('sales', {
				cart: state => state.cart,
				total: state => state.total,
				products: state => state.products,
				word: state => state.word,
				code: state => state.code,
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

			logIt (data) {
				console.log('detected', data.codeResult.code)
				this.$store.commit('sales/setCode', data.codeResult.code);
				this.camera = false;
				this.getItemByCode();
			},

			async getItemByCode(code){

				this.$store.commit('sales/setCode', code);
				this.$store.dispatch('sales/getItemByCode');

			},

			async getItemByName(word){

				this.$store.commit('sales/setWord', word);
				this.$store.dispatch('sales/getItems');

			},

			setItemCart(id){

				this.$store.commit('sales/setItemCart', {id, byCode: false});

			},

			async getData(){

				this.loading = !this.loading;

				let resp = await this.$axios.get(`/api/sales/${this.dialog.id}`);

				this.paymentType = resp.data.payment_type;
				this.saleId = resp.data.sale_id;

				this.$store.commit('sales/setCart', resp.data.products.data);

				this.loading = !this.loading;

			},

			clearDialog(){

				this.$store.commit('sales/setCode', '');
				this.paymentType = '';
				this.saleId = '';
				// this.loading = false;

			},

			async saveSale(){

				if (this.$refs.form.validate()) {

					this.loading = !this.loading;

					try {
						
						await this.$axios.post('/api/sales', {cart:this.cart,payment:this.paymentType});

						this.$store.commit('pagination/setSection', {section:'sales', mutation:'setSales'});

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
				this.$store.commit('sales/setCart', []);
				this.$store.commit('sales/setProducts', []);
				this.$store.commit('sales/setWord', '');
				this.$refs.form.resetValidation();

			},

			updatePaymentType(){

				if(this.dialog.id){

					this.$dialog.warning({
						title: 'Actualizar medio de pago',
						text: 'Se actualizará el medio de pago de la venta. ¿Estas seguro?',
						actions: {
							false: 'No, cancelar',
							true: {
								text: 'Sí, actualizar',
								color: 'warning',
							}

						},
					})
					.then(resp=>{

						if (resp) {

							if(this.dialog.id){

								this.$store.dispatch('sales/updatePaymentType', {saleId: this.dialog.id, paymentType:this.paymentType});

							}

						}

					});

				}


			},

            updateQuantity(quantity, item){

				if(this.dialog.add){

					// validation rule

					if(quantity > item.stock){
						this.$store.commit('showSnackbar', {color:'error',text:'No tenés stock suficiente'});
						return;
					}

					this.$store.commit('sales/updateItemCart', {quantity,id:item.id});

				} else {

					// validation rule

					// stock: 5
					// item.quantity: 7
					// quantity: 8

					// console.log('quantity', quantity,'item quantity',item.quantity,'item stock', item.stock);

					if(quantity > item.quantity && (quantity - item.quantity) > item.stock){
						this.$store.commit('showSnackbar', {color:'error',text:'No tenés stock suficiente'});
						return;
					}

					this.$dialog.warning({
						title: 'Actualizar cantidad',
						text: 'Al actualizar la cantidad, se modificará el valor final de la venta y se actualizará el stock del producto. ¿Estas seguro?',
						actions: {
							false: 'No, cancelar',
							true: {
								text: 'Sí, actualizar',
								color: 'warning',
							}

						},
					})
					.then(resp=>{

						if (resp) {

							if(!this.dialog.add){

								let operator = quantity > item.quantity ? '-' : '+';
								let diff = quantity > item.quantity ? (quantity - item.quantity) : (item.quantity - quantity);

								this.$store.dispatch('sales/updateItemSaleQuantity', {saleId: this.dialog.id, quantity, id:item.id, operator, diff});

							}

						}

					});

				}
             
            },

            deleteItem(item){

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

							this.$axios.delete(`/api/sales/${this.dialog.id}`, {params: {id:item.id, quantity:item.quantity}})
							.catch(() => {

								this.$store.commit('showSnackbar', {color:'error',text:'No se pudo eliminar la venta'});
								return;

							})

						}

						this.$store.commit('sales/deleteItemCart', {item});

						this.$store.commit('showSnackbar', {color:'success',text:'Producto eliminado correctamente'});

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

	.reader-box {
		height: 480px;
	}

	h4 {
		color: grey;
		font-weight: 400;
	}

</style>
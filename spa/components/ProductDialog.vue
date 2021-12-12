<template>

		<v-dialog
			:value="dialog.show"
			@click:outside="closeDialog"
			fullscreen
			hide-overlay
			transition="dialog-bottom-transition"
		>
		
			<v-card>
				
				<v-toolbar
				dark
				color="toolbar-color"
				>
				<v-btn
					icon
					dark
					@click="closeDialog"
				>
					<v-icon>mdi-close</v-icon>
				</v-btn>
				<v-toolbar-title v-text="dialog.add ? 'Agregar producto' : 'Editar producto'"></v-toolbar-title>
				<v-spacer></v-spacer>
				<v-toolbar-items>
					<v-btn
					dark
					text
					@click="saveProduct"
					v-if="dialog.add"
					>
					Guardar
					</v-btn>
				</v-toolbar-items>
				</v-toolbar>

				<!-- <v-card-title
					class="headline headline-white blue darken-3"
					primary-title
					v-text="dialog.add ? 'Agregar producto' : 'Editar producto'"
				>
					

				</v-card-title> -->

				<v-progress-linear :indeterminate="loading"></v-progress-linear>

				<v-card-text class="mt-4">

					<v-row>

						<v-col cols="12" md="6">

							<v-form ref="form">

									<v-row>

										<v-col cols="12">

											<v-text-field
												label="Producto"
												outlined
												v-model="form.product"
												dense
												:rules="[rules.required]"
												@change="updateInput('product', form.product)"
												color="var(--primary)"
											></v-text-field>

										</v-col>

										<v-col cols="12">

											<v-text-field
												label="Código"
												outlined
												v-model="form.code"
												dense
												:rules="[rules.required]"
												@change="updateInput('code', form.code)"
												color="var(--primary)"
											></v-text-field>

										</v-col>

										<v-col cols="12">

											<v-select
											:items="stores"
											item-text="store"
											item-value="id"
											label="Local"
											outlined
											dense
											v-model="form.store_id"
											:rules="[rules.required]"
											@change="updateInput('store_id', form.store_id)"
											color="var(--primary)"
											></v-select>

										</v-col>

										<v-col cols="12">

											<v-text-field
												label="Precio"
												outlined
												v-model="form.price"
												dense
												:rules="[rules.required]"
												@change="updateInput('price', form.price)"
												color="var(--primary)"
											></v-text-field>

										</v-col>

										<v-col cols="12">

											<v-text-field
												label="Stock"
												outlined
												v-model="form.stock"
												dense
												:rules="[rules.required]"
												@change="updateInput('stock', form.stock)"
												color="var(--primary)"
											></v-text-field>

										</v-col>

										<v-col cols="12">

											<v-text-field
												label="Notificar stock debajo de"
												outlined
												v-model="form.stock_notification_below"
												dense
												:rules="[rules.required]"
												@change="updateInput('stock_notification_below', form.stock_notification_below)"
												color="var(--primary)"
											></v-text-field>

										</v-col>

									</v-row>

							</v-form>

						</v-col>

						<v-col cols="12" md="6" class="text-center">

							<div id="code">
								<barcode :value="form.code" tag="img">
								Código de barras
								</barcode>
							</div>
						
							<v-btn color="var(--primary)" dark small rounded @click="print">
								Imprimir
							</v-btn>

						</v-col>

					</v-row>


				</v-card-text>

			</v-card>
		</v-dialog>


		<!-- </div>  -->

</template>

<script>

	import { mapState, mapGetters } from 'vuex';
	import VueBarcode from 'vue-barcode';

	export default {

		components: {
			'barcode': VueBarcode,
		},

		data(){

			return {

				loading: false,
				form: {
                    product: '',
					code: '',
                    stock: '',
                    stock_notification_below: '',
                    price: '',
                    store_id: '',
                },
				rules: {
					required : value => !!value || 'Debés completar este campo',
				},
			}

		},

		computed: {

			dialog: {

				get() {
					return this.$store.getters['products/getDialog'];
				},

				set() {
					
					console.log('abc');
					// this.$store.commit('bookshop/books/showDialog', {add: false});

					// this.$store.commit('clearObject', 'appointment');
				}

			},

            ...mapState('stores', {
                stores: state => state.stores
            }),

			...mapGetters('pagination', ['getPagination'])

		},

		watch: {

			'dialog.id'() {

				if(this.dialog.id){

					this.getData();

				}

			}

		},

		methods: {

			async getData(){

				let resp = await this.$axios.get(`/api/products/${this.dialog.id}`);

				this.form.product = resp.data.product;
				this.form.code = resp.data.code;
				this.form.price = resp.data.price;
				this.form.stock = resp.data.stock;
				this.form.stock_notification_below = resp.data.stock_notification_below;
				this.form.store_id = resp.data.store.id;
                console.log(resp);

			},

			clearDialog(){

				Object.keys(this.form).map(key => {

					this.form[key] = '';

				});

			},

			async saveProduct(){

				if (this.$refs.form.validate()) {

					this.loading = !this.loading;

					try {
						
						await this.$axios.post('/api/products', {form:this.form});

						this.$store.commit('pagination/changePaginationSection', {section:'products'});

						let url = '/api/products?page='+this.getPagination.page;

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

			updateInput(input, value){

				if(!this.dialog.add){

					if (this.$refs.form.validate()) {

						let url = `/api/products/${this.dialog.id}`;
						
						this.$axios.put(url,{input,value})
						.then( resp => {
							console.log(resp)

							this.$store.commit('products/updateArray', {id:this.dialog.id,input,value});
							this.$store.commit('showSnackbar', {color:'success',text:'Actualizado correctamente'});

						})
						.catch( resp => {
							console.log(resp)
							this.$store.commit('showSnackbar', {color:'error',text:'No se pudo actualizar. Intentá nuevamente'});       

						})

					}

				}

			},

			closeDialog(){

				this.$store.commit('products/showDialog', {add: false});
				this.clearDialog();
				this.$refs.form.resetValidation();

			},

			async print(){

				await this.$htmlToPaper('code');

			}

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
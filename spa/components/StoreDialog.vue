<template>

		<v-dialog
			:value="dialog.show"
			width="500"
			@click:outside="closeDialog"
		>
		
			<v-card>
				
			<v-card-title
				class="headline headline-white teal lighten-1"
				primary-title
				v-text="dialog.add ? 'Agregar local' : 'Editar local'"
			>
				

			</v-card-title>

			<v-progress-linear :indeterminate="loading"></v-progress-linear>

			<v-card-text class="mt-4">

                <v-row>

					<v-col cols="12">

						<v-form ref="form">

								<v-row>

									<v-col cols="12">

										<v-text-field
											label="Local"
											outlined
											v-model="form.store"
											dense
											:rules="[rules.required]"
											@change="updateInput('store', form.store)"
											color="var(--primary)"
										></v-text-field>

									</v-col>

								</v-row>

						</v-form>

					</v-col>

				</v-row>


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
				@click="saveStore"
				v-if="dialog.add"
				:loading="loading"
				:disabled="loading"
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
				form: {
                    store: '',
                },
				rules: {
					required : value => !!value || 'Debés completar este campo',
				}
				
			}

		},

		computed: {

			dialog: {

				get() {
					return this.$store.getters['stores/getDialog'];
				},

				set() {
					
					console.log('abc');
					// this.$store.commit('bookshop/books/showDialog', {add: false});

					// this.$store.commit('clearObject', 'appointment');
				}

			},

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

				let resp = await this.$axios.get(`/api/stores/${this.dialog.id}`);

				this.form.store = resp.data.store;
                console.log(resp);

			},

			clearDialog(){

				Object.keys(this.form).map(key => {

					this.form[key] = '';

				});

			},

			async saveStore(){

				if (this.$refs.form.validate()) {

					this.loading = !this.loading;

					try {
						
						await this.$axios.post('/api/store', {form:this.form});

						this.$store.commit('pagination/changePaginationSection', {section:'stores'});

						let url = '/api/stores?page='+this.getPagination.page;

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

						let url = `/api/stores/${this.dialog.id}`;
						
						this.$axios.put(url,{input,value})
						.then( resp => {
							console.log(resp)

							this.$store.commit('stores/updateArray', {id:this.dialog.id,input,value});
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

				this.$store.commit('stores/showDialog', {add: false});
				this.clearDialog();
				this.$refs.form.resetValidation();

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
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
				v-text="dialog.add ? 'Agregar usuario' : 'Editar usuario'"
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
											label="Nombre"
											outlined
											v-model="form.name"
											dense
											:rules="[rules.required]"
											@change="updateInput('name', form.name)"
											color="var(--primary)"
										></v-text-field>

									</v-col>

									<v-col cols="12">

										<v-text-field
											label="Correo Electrónico"
											outlined
											v-model="form.email"
											dense
											:rules="[rules.required, rules.email]"
											@change="updateInput('email', form.email)"
											color="var(--primary)"
										></v-text-field>

									</v-col>

									<v-col cols="12">

										<v-text-field
											label="Contraseña"
											outlined
											v-model="form.password"
											dense
											:rules="[rules.requiredPassword(dialog.add), rules.password(dialog.add)]"
											@change="updateInput('password', form.password)"
											color="var(--primary)"
										></v-text-field>

									</v-col>

									<v-col cols="12">

											<v-select
											:items="roles"
											item-text="role"
											item-value="id"
											label="Rol"
											outlined
											dense
											v-model="form.role_id"
											:rules="[rules.required]"
											@change="updateInput('role_id', form.role_id)"
											color="var(--primary)"
											></v-select>

									</v-col>

									<v-col cols="12">

											<v-select
											:items="access"
											item-text="access"
											item-value="value"
											label="Acceso al panel"
											outlined
											dense
											v-model="form.access"
											@change="updateInput('access', form.access)"
											color="var(--primary)"
											></v-select>

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
				@click="saveUser"
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
                    name: '',
                    email: '',
                    password: '',
                    role_id: '',
					access: 0,
                },
				rules: {
					required: value => !!value || 'Debés completar este campo',
                    email : value => {
                        return this.validEmail(value) || 'Debés ingresar un email válido'
                    },
					requiredPassword(add){

						if(add){
							return value => !!value || 'Debés completar este campo'
						}

						return true;

					},
                    password(add){

						if(add){
							return value => (value && value.length >= 8) || 'La contraseña debe tener mínimo 8 caracteres'
						}

						return true;

					}

				},
				access: [
					{access: 'Habilitado', value: 1},
					{access: 'Deshabilitado', value: 0},
				]
				
			}

		},

		computed: {

			dialog: {

				get() {
					return this.$store.getters['users/getDialog'];
				},

				set() {
					
					console.log('abc');
					// this.$store.commit('bookshop/books/showDialog', {add: false});

					// this.$store.commit('clearObject', 'appointment');
				}

			},

			...mapGetters('pagination', ['getPagination']),

			...mapState('users', {
				roles: state => state.roles,
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

            validEmail(email) {
                var re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
                return re.test(email);
            },

			async getData(){

				let resp = await this.$axios.get(`/api/users/${this.dialog.id}`);

				this.form.name = resp.data.name;
				this.form.email = resp.data.email;
				this.form.role_id = resp.data.role.id;
				this.form.access = resp.data.access;
                console.log(resp);

			},

			clearDialog(){

				Object.keys(this.form).map(key => {

					this.form[key] = '';

				});

			},

			async saveUser(){

				if (this.$refs.form.validate()) {

					this.loading = !this.loading;

					try {
						
						await this.$axios.post('/api/users', {form:this.form});

						this.$store.commit('pagination/changePaginationSection', {section:'users'});

						let url = '/api/users?page='+this.getPagination.page;

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

						let url = `/api/users/${this.dialog.id}`;
						
						this.$axios.put(url,{input,value})
						.then( resp => {
							console.log(resp)

							this.$store.commit('users/updateArray', {id:this.dialog.id,input,value});
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

				this.$store.commit('users/showDialog', {add: false});
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
<template>

		<v-dialog
			:value="dialog"
			width="500"
			@click:outside="closeDialog"
		>
		
			<v-card>
				
			<v-card-title
				class="headline headline-white blue darken-3"
				primary-title
			>
                Importar producto
			</v-card-title>

			<v-progress-linear :indeterminate="loading"></v-progress-linear>

			<v-card-text class="mt-4">

                <v-form ref="form">

                    <v-file-input
                        label="Archivo"
                        prepend-icon="mdi-camera"
                        :rules="[rules.required]"
                        v-model="file"
						outlined
						dense
                    ></v-file-input>

                </v-form>

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
				:dark="!loading"
				@click="importItem"
				:disabled="loading"
				:loading="loading"
				>
				Importar
				</v-btn>				
			</v-card-actions>
			</v-card>
		</v-dialog>

</template>

<script>

	export default {

		data(){

			return {

				loading: false,
                file: null,
				rules: {
					required : value => !!value || 'Debés seleccionar un archivo',
				},
				uploaded: false,
			
			}

		},

		computed: {

			dialog: {

				get() {
					return this.$store.getters['getDialog'];
				},

				set() {					
					this.$store.commit('setDialog');
				}

			},

		},

		// mounted(){

		// 	this.checkIfUploaded();

		// },

		methods: {

			closeDialog(){

				this.$store.commit('setDialog');

			},

            importItem(){

                if(this.$refs.form.validate()){

                    this.loading = !this.loading;

                    let url = '/api/products/import';
                    let formData = new FormData();

                    // this.selectedFile = event.target.files[0];

                    formData.append('file', this.file, this.file.name);

                    this.$axios.post(url, formData, {headers: {
                        'Content-Type': 'multipart/form-data'
                    }})
                    .then(()=>{

                        // this.$store.commit('showSnackbar', {color:'success',text:'Subido correctamente'});
                        // this.$store.commit('setDialog');
						this.checkIfUploaded();

                    })
                    .catch(()=>{

                        // console.log(e);
                        this.$store.commit('showSnackbar', {color:'error',text:'No se pudo subir el archivo. Intentá nuevamente'});

                    })

                }

            },

			checkIfUploaded(){

				this.$axios.get('/api/products/import/check')
				.then((resp)=>{

					this.uploaded = resp.data.uploaded;
					console.log(resp);

					if(!this.uploaded){

						setTimeout(()=>{

							this.checkIfUploaded();
							console.log('checking again');

						}, 10000);

					} else {

                        this.file = null;
                        this.loading = !this.loading;
                        this.$store.commit('showSnackbar', {color:'success',text:'Subido correctamente'});
                        this.$store.commit('setDialog');

						this.$store.commit('pagination/changePaginationSection', {section:'products'});
						let url = `/api/products?page=1`;
						this.$store.dispatch('pagination/setItemsPagination', url);

					}

				})
				.catch(()=>{

                    this.$store.commit('showSnackbar', {color:'error',text:'No se pudo subir el archivo. Intentá nuevamente'});

				})

			}


		}

	}


</script>


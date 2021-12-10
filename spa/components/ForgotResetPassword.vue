<template>
    
    <v-form v-model="valid" ref="form" @submit.prevent>        
        <v-container>            
            <v-row class="text-center">

                <v-col cols="12">
                    <h2 class="section-title">Restablecer contraseña</h2>
                    <p v-text="forgot ? 'Te llegará un email con las instrucciones para restablecer tu contraseña. Recordá chequear también en la carpeta de correo no deseado' : 'Ingresá tus datos para restablecer la contraseña'"></p>
                </v-col>

                <v-col cols="12">
                    <v-text-field 
                    outlined 
                    @keyup.enter="keyEnter" 
                    label="Correo Electrónico" 
                    type="email" 
                    required 
                    v-model="email" 
                    :rules="[rules.required,rules.email]"
                    dense
                    color="var(--primary)"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" v-if="!forgot">
                    <v-text-field 
                    outlined 
                    label="Nueva Contraseña" 
                    type="password" 
                    required 
                    v-model="password" 
                    :rules="[rules.required,rules.password]"
                    dense
                    color="var(--primary)"
                    ></v-text-field>
                </v-col>				              

                <v-col cols="12" v-if="!forgot">
                    <v-text-field 
                    @keyup.enter="keyEnter" 
                    outlined 
                    label="Confirmar Nueva contraseña" 
                    type="password" 
                    required 
                    v-model="password_confirmation" 
                    :error-messages="passwordConfirmation()" 
                    dense
                    :rules="[rules.required]"
                    color="var(--primary)"
                    ></v-text-field>
                </v-col>

                <v-col cols="12">
                    <v-btn 
                    color="var(--primary)" 
                    rounded
                    :dark="!loading" 
                    block 
                    :loading="loading" 
                    :disabled="loading" 
                    @click="send" 
                    v-text="forgot ? 'Enviar instrucciones' : 'Restablecer contraseña'"/>
                </v-col>  

            </v-row>	
        </v-container>
    </v-form>

</template>

<script>

	export default {
		data(){
			return {

				valid : false,
				email : '',
                password: '',
                password_confirmation: '',
				error : '',
				success : '',
				rules : {

					required : value => !!value || 'Debés completar este campo',
					email: value => {
						return this.validEmail(value) || 'Debés ingresar un email válido'
					},
					password : value => (value && value.length >= 8) || 'La contraseña debe tener mínimo 8 caracteres'


				},
				loading: false,
			}
		},

        props: {

            forgot: {
                type: Boolean,
                default: true,
            }

        },

		methods:{

			keyEnter(){

				this.error = [];
				this.success = '';
				this.sendEmailPassword();

			},

			passwordConfirmation(){
				return (this.password === this.password_confirmation ? '' : 'Las contraseñas deben ser iguales');
			},

			validEmail(email) {
				var re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
				return re.test(email);
			},

			async send(){

				if (this.$refs.form.validate()) {

                    this.error = '';
                    this.success = '';
					this.loading = !this.loading;

                    await this.$axios.get('/sanctum/csrf-cookie');

                    if(this.forgot){

                        let url = '/forgot-password';

                        try {

                            let resp = await this.$axios.post(url, {email: this.email});
                            this.$store.commit('showSnackbar', {color:'success', text: resp.data.message});
                            
                        } catch (error) {

                            this.$store.commit('showSnackbar', {color:'error', text: error.response.data.errors.email[0]});
                            
                        } finally {

                            this.loading = !this.loading;

                        }

                    } else {

					    let url = '/reset-password';

                        try {

                            let resp = await this.$axios.post(url, {
                                email: this.email,
                                password: this.password,
                                password_confirmation : this.password_confirmation,
                                token : this.$route.query.token
                            });

                            this.$store.commit('showSnackbar', {color:'success', text: resp.data.message});

                            this.$router.push('/');
                            
                        } catch (error) {

                            this.$store.commit('showSnackbar', {color:'error', text: error.response.data.errors.email[0]});
                            
                        } finally {

                            this.loading = !this.loading;

                        }

                    }

				}

			},

		}

	}

</script>
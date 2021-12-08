<template>

        <v-card class="rounded-lg">
            <v-card-title>
                <h3>Ingesar al panel</h3>
            </v-card-title>
            <v-card-text>
                <v-form @submit.prevent v-model="valid" ref="form">
                    <v-row>
                        <v-col class="pb-0" cols="12">
                            <v-text-field
                                v-model="userInfo.email"
                                label="Correo Electrónico"
                                required
                                :rules="[rules.required, rules.email]"
                                outlined
                                type="email"
                                @keyup.enter="sendForm(userInfo)"
                            ></v-text-field>
                        </v-col>
                        <v-col class="pb-0" cols="12">
                            <v-text-field
                                v-model="userInfo.password"
                                label="Contraseña"
                                required
                                :rules="[rules.required, rules.password]"
                                outlined
                                type="password"
                                @keyup.enter="sendForm(userInfo)"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" class="text-center">
                            <v-btn color="primary" :dark="!loading" block @click="sendForm(userInfo)" :loading="loading" :disabled="loading">Ingresar</v-btn>
                            <p class="mt-5 mb-0">¿Olvidaste tu contraseña? <NuxtLink to="forgot">Hacé click acá</NuxtLink></p>
                        </v-col>
                    </v-row>
                </v-form>
            </v-card-text>
        </v-card>

</template>

<script>

    import { mapState } from 'vuex';

    export default {

        data(){

            return {

                userInfo: {
                    email: '',
                    password: '',
                },
                loading: false,
                valid: false,
                rules: {

                    required : value => !!value || 'Debés completar este campo',
                    email : value => {

                        return this.validEmail(value) || 'Debés ingresar un email válido'

                    },
                    password : value => (value && value.length >= 8) || 'La contraseña debe tener mínimo 8 caracteres'

                },
                success: '',
                error: ''


            }

        },

        methods: {

            validEmail(email) {
                var re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
                return re.test(email);
            },

            async sendForm(userInfo){

                if(this.$refs.form.validate()){

                    this.loading = !this.loading;

                    try {            
                        
                        let resp = await this.$auth.loginWith('laravelSanctum', {
                            data: {
                                email: userInfo.email,
                                password: userInfo.password
                            }
                        });

                        this.$store.commit('showSnackbar', {color:'success', text: resp.data.message});

                        localStorage.setItem('admin', (resp.data.role == 'Administrador') ? true : false);
                        
                        this.$store.dispatch('setUser');
                        this.$router.push('panel');

                    } catch (error) {

                        this.$store.commit('showSnackbar', {color:'error', text: error.response.data.errors.email[0]});
                        
                    } finally {

                        this.loading = !this.loading;

                    }

                }

            }

        }

    }
</script>
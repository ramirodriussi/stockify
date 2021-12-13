<template>

<div>

      <v-dialog v-model="dialog" max-width="400">
        <v-card>
          <v-card-title class="headline-white headline toolbar-color">Mi Perfil</v-card-title>

              <v-form v-model="validForm" ref="form" lazy-validation>

              <v-card-text>

                <!-- <upload-avatar class="mb-4" v-if="userLogged" :isCompanyAvatar="false"></upload-avatar> -->
                <Avatar class="mb-4" />
                
                <v-text-field
                  label="Nombre"
                  required
                  @change="updateInput('name', $event)"
                  :value="info.name"
                  :rules="[rules.required]"
                  outlined
                  :loading="loading"
                  :disabled="loading"
                  color="var(--primary)"
                  dense
                ></v-text-field>

                <v-text-field
                  label="Correo Electrónico"
                  required
                  @change="updateInput('email', $event)"
                  :value="info.email"
                  :rules="[rules.required,rules.email]"
                  outlined
                  :loading="loading"
                  :disabled="loading"
                  color="var(--primary)"
                  dense
                ></v-text-field>

                <span @click="showChangePassBox = !showChangePassBox">¿Necesitás cambiar tu contraseña?</span>  
             
                  <div v-show="showChangePassBox" class="mt-3">
                    
                      <v-text-field
                        label="Contraseña actual"
                        required
                        :value="info.currentPass"
                        @input="currentPass = $event"
                        :rules="[rules.required, rules.password]"
                        :error-messages="errors"
                        outlined
                        :loading="loading"
                        :disabled="loading"
                        color="var(--primary)"
                        dense
                      ></v-text-field>

                      <v-text-field
                        label="Nueva contraseña"
                        required
                        :value="info.newPass"
                        @input="newPass = $event"
                        :rules="[rules.required, rules.password, rules.equal]"
                        :error-messages="errors"
                        outlined
                        :loading="loading"
                        :disabled="loading"
                        color="var(--primary)"
                        dense
                      ></v-text-field>

                      <!-- <p class="error-message" v-if="error">{{ error }}</p> -->


                      <v-btn 
                        small 
                        class="mt-0 mb-2 p-3 ml-0" 
                        rounded
                        color="var(--primary)"
                        dark
                        @click="updatePass"
                      >
                      Cambiar contraseña
                      </v-btn>            

                  </div> 
                  
              </v-card-text>

              </v-form>  

          <v-card-actions>
        
            <v-spacer></v-spacer>
            <v-btn
              color="var(--primary)"
              outlined
              rounded
              @click="closeDialog"
            >
              Cerrar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

</div>
      

</template>

<script>

import { mapState } from 'vuex';
import Avatar from '@/components/Avatar';

export default {

  components: {
    Avatar,
  },

  data(){

    return {

          validForm: false,
          rules : {

            required : value => !!value || 'Debés completar este campo',
            password : value => (value && value.length >= 8) || 'La contraseña debe tener mínimo 8 caracteres',
            email: value => {
                        return this.validEmail(value) || 'Debés ingresar un email válido'
                      },
            equal : value => {
                        return this.checkEqual(value) || 'Las contraseñas no deben ser iguales'
                        }

          },
          errors: [],
          currentPass: '',
          newPass: '',
          info: {},
          showChangePassBox: false,

    }

  },

  computed: {

    ...mapState(['loading']),

			dialog: {

				get() {
					return this.$store.getters['getProfileDialog'];
				},

				set() {
					this.$store.commit('setProfileDialog');
				}

			},

  },

  mounted(){

      this.getInfo();

  },

  methods: {

      async getInfo(){

          let resp = await this.$axios.get('/api/user/profile');

          this.info = resp.data;

      },

      checkEqual(value){

        if (this.currentPass != value) {
          return true;
        }
        
      },

      validEmail(email) {
          var re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
          return re.test(email);
      },

      async updateInput(input, value){

        if (this.validForm) {

            this.$store.commit('setLoading');

            try {

              await this.$axios.post('/api/profile/update', {input,value});

              if(input == 'name'){
                this.$store.commit('updateUser', {name: value});
              }

			        this.$store.commit('showSnackbar', {color:'success',text:'Actualizado exitosamente'});
              
            } catch (error) {
              
              this.$store.commit('showSnackbar', {color:'error',text:'No se pudo actualizar'});

            } finally {

              this.$store.commit('setLoading');

            }

        }

      },

      async updatePass(){

        if (this.$refs.form.validate()) {

            this.$store.commit('setLoading');

            try {
                
                await this.$axios.post('/api/profile/password/update', {currentPass: this.currentPass, newPass: this.newPass});

                this.$store.commit('showSnackbar', {color:'success',text:'Contraseña actualizada exitosamente'});

            } catch (error) {
                
				        this.$store.commit('showSnackbar', {color:'error',text:error.response.data.error});

            } finally {

                this.$store.commit('setLoading');

            }

        }

      },
      
      closeDialog(){
        this.$store.commit('setProfileDialog');
      }


  },

}

</script>

<style scoped>

    span {
    cursor: pointer;
    }

</style>
<template>

	<div class="text-center d-flex flex-column align-center">

		<div class="avatar-box">

			<v-avatar>				
				<img :src="logoUrl != null ? logoUrl : require('~/assets/images/user.png')" alt="logo">
			</v-avatar>
			
		</div>

		<p class="mt-3" style="color:#FF8A80" v-if="error">{{ error }}</p>

		<v-btn color="var(--primary)" small dark rounded class="mt-4" @click="$refs.fileInput.click()">Cambiar foto</v-btn>

		<input class="file" type="file" @change="onFileChange" ref="fileInput">
		
	</div>

</template>

<script>

	import { mapState } from 'vuex';

	export default {

		data(){

			return {

					selectedFile: '',
					logoUrl: '',
					maxSize: 3000000,
					error: '',

			}

		},

		computed: {

			// ...mapState(['companyInfo','userInfo','logo']),

            ...mapState(['user', 'logo']),

		},

		watch: {

			'user.logo'(){
				this.logoUrl = this.user.logo;
			},

			'logo.url'(){
				this.logoUrl = this.logo.url;
			}

		},

		mounted(){

            this.logoUrl = this.user.logo;

		},

		methods: {

			onFileChange(event){

				this.selectedFile = event.target.files[0];

				this.error = '';

				const acceptedImageTypes = ['image/bmp', 'image/jpeg', 'image/png'];

				if (acceptedImageTypes.includes(this.selectedFile.type) && this.selectedFile.size <= this.maxSize) {

					let fileUrl = URL.createObjectURL(this.selectedFile);

					let formData = new FormData();

					formData.append('file', this.selectedFile, this.selectedFile.name);

					let url;

					if (this.isBookshop) {
						url = '/api/bookshop/image/store';
					} else {
						url = '/api/user/image/store';
					}	    		

					// console.log([...formData])

					this.$axios.post(url, formData, { headers: {
						'Content-Type': 'multipart/form-data'
					}})
					.then(resp=>{
						console.log(resp)
						this.$store.commit('setLogo', {avatar:fileUrl, isWelcomePage: false});

						this.$store.commit('showSnackbar', {color:'success',text:'Modificado exitosamente'});

					})
					.catch(resp=>{
						// console.log(resp.response.data.error)
						this.$store.commit('showSnackbar', {color:'error',text:resp.response.data.error});
					})

				} else {
					this.error = `El archivo debe ser una imagen, y debe pesar menos de ${this.maxSize/1000/1000} mb`; 
				}


			},

		}

	}


</script>

<style scoped>

	.v-avatar {
		height: 100% !important;
		width: 100% !important;
	}

	.avatar-box {
		border: 3px solid var(--primary);
		padding: 10px !important;
		border-radius: 50%;
		height: 225px;
		width: 225px;
	}

	.file {
		display: none;
	}

</style>
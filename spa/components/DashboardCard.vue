<template>

    <div>

        <v-skeleton-loader
            type="card"
            v-if="skeleton"
            height="150"
        ></v-skeleton-loader>

        <v-card
        v-else
        :color="color"
        dark
        shaped
        outlined
        elevation="2"
        >
        <v-card-title class="text-h5">
            {{ title }}
        </v-card-title>

        <v-card-subtitle class="mt-0">50</v-card-subtitle>

        <div class="box-icon">		
            <v-icon size="100">mdi-calendar</v-icon>					
        </div>	

        </v-card>

    </div>

			
</template>

<script>

	import { mapState } from 'vuex';

	export default {

	
		props: {

            title: {
                type: String,
            },

            color: {
                type: String
            }

        },

		computed: {

			...mapState(['skeleton']),

		},


	}

</script>

<style scoped>

    .text-h5 {
        font-size: 18px;
    }

	.box-icon {
		position: absolute;
		top: 5px;
		right: 7px;
		opacity: 0.2;
	}

	.v-card {
		border-radius: 7px;
		position: relative;
		overflow: hidden;
	}

    .v-card__subtitle {
        font-size: 30px;
    }

</style>
<template>
    <NuxtChild/>
</template>

<script>

    export default {
        
        middleware: ['access-panel'],
        layout: 'default'

    }

</script>
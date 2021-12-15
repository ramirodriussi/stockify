<template>
    <NuxtChild/>
</template>

<script>

    export default {
        
        middleware: 'auth',

    }

</script>
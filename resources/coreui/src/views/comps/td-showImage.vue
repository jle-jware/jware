<template>
    <img v-bind:src="'/img/' + value" width="100" />
</template>
<script>
    export default {
        props: ['value']
    }
</script>
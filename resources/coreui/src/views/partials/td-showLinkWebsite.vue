<template>
    <a v-bind:href="value">
        {{ value }}
    </a>
</template>
<script>
    export default {
        props: ['value']
    }
</script>

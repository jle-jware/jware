<template>
    <a v-bind:href="'/company/' + row.id">
        {{ value }}
    </a>
</template>
<script>
    export default {
        props: ['value', 'row']
    }
</script>

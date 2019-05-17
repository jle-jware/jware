<template>
    <span class="boolean-value">{{ value == 1 ? 'Yes' : 'No' }}</span>
</template>
<script>
    export default {
        props: ['value']
    }
</script>

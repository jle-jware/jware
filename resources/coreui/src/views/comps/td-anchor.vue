<template>
    <div>
        <button class="btn btn-success" v-show="value=='enabled'">{{ value }}</button>
        <button class="btn btn-danger" v-show="value=='disabled'">{{ value }}</button>
    </div>
</template>

<script>
export default { props: ['value'] }
</script>
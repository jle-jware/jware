<template>
  <a v-bind:href="row.id">
    {{ value }}
  </a>
</template>
<script>
export default {
  props: ['value', 'row']
}
</script>

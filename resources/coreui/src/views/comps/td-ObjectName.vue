<template>
  <div>
      <span v-for="(company, index) in value" v-bind:key="company.id">{{ company.name }}{{ (index < value.length -1) ? ', ' : '' }}</span>
  </div>
</template>

<script>
export default {
  props: ['value']
}
</script>
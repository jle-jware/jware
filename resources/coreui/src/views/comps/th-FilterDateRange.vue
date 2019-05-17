<template>
  <div class="btn-group">
    {{ title }}
    <a href="javascript:;" data-toggle="dropdown">
      <i class="fa fa-filter" :class="{ 'text-muted': !keyword }"></i>
    </a>
    <ul class="dropdown-menu" style="padding: 3px">
      <div class="input-group input-group-sm">
        <input type="date" class="form-control" ref="input"  v-model="date1" @keydown.enter="search" :placeholder="``">-
        <input type="date" class="form-control" ref="input"  v-model="date2" @keydown.enter="search" :placeholder="``">

        <span class="input-group-btn"><button class="btn btn-default fa fa-search" @click="search"></button></span>
      </div>
    </ul>
  </div>
</template>
<script>
export default {
  props: ['field', 'title', 'query'],
  data: () => ({
      date1: '',
      date2: '',
      keyword: ''
  }),
  mounted () {
    $(this.$el).on('shown.bs.dropdown', e => this.$refs.input.focus())
  },
  methods: {
    search () {
      const { query } = this
      // `$props.query` would be initialized to `{ limit: 10, offset: 0, sort: '', order: '' }` by default
      // custom query conditions must be set to observable by using `Vue.set / $vm.$set`
      this.$set(query, this.field, this.date1 +"||"+this.date2)
      query.offset = 0 // reset pagination
    }
  }
}
</script>

<style>
input[type=search]::-webkit-search-cancel-button {
  -webkit-appearance: searchfield-cancel-button;
  cursor: pointer;
}
</style>

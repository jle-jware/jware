// <template>
//     <div class="btn-group">
//         <div class="text-center" style="padding: 0 10px">
//             <div class="col-25">
//                 <span style="line-height: 35px;">{{ title }}</span>
//             </div>
//             <div class="col-75">
//                 <input type="search" 
//                     class="form-control col-75" 
//                     ref="input" v-model="keyword" 
//                     @keydown.enter="search" 
//                     :placeholder="`Search ${field}...`">
//                 </div>      
//             </div> 
//     </div>
// </template>


<template>
  <div class="btn-group">
    {{ title }}
    <a href="javascript:;" data-toggle="dropdown">
      <i class="fa fa-filter" :class="{ 'text-muted': !keyword }"></i>
    </a>
    <ul class="dropdown-menu" style="padding: 3px; min-width: 250px;">
      <div class="input-group input-group-sm">
        <input type="search" class="form-control" ref="input"
          v-model="keyword" @keydown.enter="search" :placeholder="`Search ${field}...`">
          <span class="input-group-btn" style="margin-left: 1px">
            <button class="btn btn-default fa fa-search" @click="search"></button>
          </span>
      </div>
    </ul>
  </div>
</template>

<script>
export default {
  props: ['field', 'title', 'query'],
  data: () => ({
    keyword: ''
  }),
  mounted () {
    $(this.$el).on('shown.bs.dropdown', e => this.$refs.input.focus())
  },
  watch: {
    keyword (kw) {
      // reset immediately if empty
      if (kw === '') 
      {
        this.search()
      }
      else{
        this.search()
      }
    }
  },
  methods: {
    search () {
      const { query } = this
      this.$set(query, this.field, this.keyword)
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
/* Floating column for labels: 25% width */
.col-25 {
  float: left;
  width: 25%;
}

/* Floating column for inputs: 75% width */
.col-75 {
  float: left;
  width: 75%;
}

@media screen and (max-width: 600px) {
  .col-25, .col-75, input[type=submit] {
    width: 100%;
    margin-top: 0;
  }
}

</style>

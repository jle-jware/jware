<template>
    <div>
        <progress-indicator class="v-fixed-top"></progress-indicator>
        <router-view></router-view>
        <notifications group="foo" position="bottom right"/>
    </div>
</template>

<script>
    import Vue from 'vue';
    import ProgressIndicator from 'vue-progress-indicator'
    Vue.use(ProgressIndicator)
    export default {
        data: function () {
            return {}
        },
        computed: {
            authenticated: function () {
                return this.$route.meta.requireAuth
            }
        },

        mounted() {
            this.checkAuth()
        },
        watch: {
            '$route': function(from, to) {
                this.checkAuth()
            }
        },
        methods :{
            
            checkAuth(){
                if(this.$route.meta.requireAuth && !this.$store.state.user && !this.$store.state.token){
                    this.$router.push(`/login`)
                }
            }
        }
    }
</script>

<style>
    /* Import Font Awesome Icons Set */

    $fa-font-path: '~font-awesome/fonts/';
    @import '~font-awesome/css/font-awesome.min.css';
    /* Import Simple Line Icons Set */

    $simple-line-font-path: '~simple-line-icons/fonts/';
    @import '~simple-line-icons/css/simple-line-icons.css';
    /* Import Bootstrap Vue Styles */

    @import '~bootstrap-vue/dist/bootstrap-vue.css';
</style>
<style lang="scss">
    // Import Main styles for this application
    @import '../scss/style';
</style>
<template>
    <header class="app-header navbar">
        <button class="navbar-toggler mobile-sidebar-toggler d-lg-none" type="button" @click="mobileSidebarToggle">&#9776;</button>
         <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" @click="sidebarMinimize">&#9776;</button>
        <b-link class="navbar-brand" to="#"></b-link>
       
        <b-nav is-nav-bar class="d-md-down-none">
            <b-nav-item class="px-3">
                <img :src="user.image" alt="" v-if="user.image" style="width: 39px;height:39px;object-fit:cover;border: 1px solid #687f89;border-radius: 50%;">
                <!-- <img src="/img/user.png" style="width: 39px;height:39px;object-fit:cover;border: 1px solid #687f89;border-radius: 50%;" v-else> {{ user.first_name }} {{ user.last_name}} -->
                <img src="/img/user.png" style="width: 39px;height:39px;object-fit:cover;border: 1px solid #687f89;border-radius: 50%;" v-else> {{ user.first_name }} {{ user.last_name}}
            </b-nav-item>
            <b-nav-item class="px-5">
                <div style="display: flex; justify-content: space-around; min-width: 400px;">
                    <img :src="company.image" alt="" v-if="company.image" style="height:39px;">
                    <img src="/img/company.png" style="height:39px;" v-else>
                    <!-- <img :src="'/storage/' + user.companies[0].image" alt="" v-if="user.companies[0].image" style="height:39px;"> -->
                    <!-- <img src="/img/company.png" style="height:39px;"> -->
                    <!-- <select class="form-control" style="margin-left: 10px;">
                        <option> {{company.name}}</option>
                    </select> -->
                </div>
                
            </b-nav-item>
        </b-nav>
        <b-nav is-nav-bar class="ml-auto">
            
            <b-nav-item-dropdown right>
                <template slot="button-content">
                    <i class="fa fa-bell fa-lg mt-2"></i>
                    <span class="badge badge-danger">{{notifications.length}}</span>
                </template>
                
                <b-dropdown-header tag="div" class="text-center">
                    <strong>Notification Center</strong>
                </b-dropdown-header>
                <b-dropdown-item>
                    <i class="fas fa-exclamation-triangle" style="font-size:10px;  color: red;" aria-hidden="true">
                        <font color="black" face="Segoe UI"> Job 4786 Overdue.</font>
                    </i>
                </b-dropdown-item>
                <b-dropdown-item>
                    <i class="fas fa-exclamation-triangle" style="font-size:10px;  color:orange;" aria-hidden="true">
                        <font face="Segoe UI" color="black"> Job 5871 Overdue.</font>
                    </i>
                </b-dropdown-item>
                <b-dropdown-item>
                    <i class="fas fa-exclamation-triangle" style="font-size:10px;  color: red;" aria-hidden="true">
                        <font color="black" face="Segoe UI"> Asset 4562 Quality Inspection.</font>
                    </i>
                </b-dropdown-item>
            </b-nav-item-dropdown>
            <b-nav-item-dropdown right>
                <template slot="button-content">
                    <i class="fa fa-user-o fa-lg mt-2"></i>
                    <!-- <img src="static/img/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com"> -->
                    <span class="d-md-down-none"></span>
                </template>
                <b-dropdown-header tag="div" class="text-center">
                    <strong>Account</strong>
                </b-dropdown-header>
                <b-dropdown-item>
                    <i class="fa fa-bell-o"></i> Updates
                    <span class="badge badge-info">42</span>
                </b-dropdown-item>
                <b-dropdown-item>
                    <i class="fa fa-envelope-o"></i> Messages
                    <span class="badge badge-success">42</span>
                </b-dropdown-item>
                <b-dropdown-item>
                    <i class="fa fa-tasks"></i> Tasks
                    <span class="badge badge-danger">42</span>
                </b-dropdown-item>
                <b-dropdown-item>
                    <i class="fa fa-comments"></i> Comments
                    <span class="badge badge-warning">42</span>
                </b-dropdown-item>
                <b-dropdown-header tag="div" class="text-center">
                    <strong>Settings</strong>
                </b-dropdown-header>
                <b-dropdown-item>
                    <i class="fa fa-user"></i> Profile</b-dropdown-item>
                <b-dropdown-item>
                    <i class="fa fa-wrench"></i> Settings</b-dropdown-item>
                <b-dropdown-item>
                    <i class="fa fa-usd"></i> Payments
                    <span class="badge badge-default">42</span>
                </b-dropdown-item>
                <b-dropdown-item>
                    <i class="fa fa-file"></i> Projects
                    <span class="badge badge-primary">42</span>
                </b-dropdown-item>
                <b-dropdown-divider></b-dropdown-divider>
                <b-dropdown-item>
                    <i class="fa fa-shield"></i> Lock Account</b-dropdown-item>
                <b-dropdown-item @click.prevent="logout">
                    <i class="fa fa-lock"></i> Logout</b-dropdown-item>
            </b-nav-item-dropdown>
        </b-nav>
        <!-- <button class="navbar-toggler aside-menu-toggler d-md-down-none" type="button" @click="asideToggle">&#9776;</button> -->
    </header>
</template>
<script>
    export default {
        name: 'header',
        data() {
            return {
                //companies: [],
                user: '',
                company: {},
                fullscreen: false,
                notifications: []
            }
        },
        created() {
            console.log('From header', this.$store.state.user);
            if (!this.$store.state.user) this.$router.push("/login");
            this.first_name = this.$store.state.user.first_name;
            this.user = this.$store.state.user;
            this.fetchUserWiseCompany()
            //this.fetch()
            console.log("get all user info", this.user);
        },
        methods: {
            fetch() {
                this.$http.get(`/api/companies`,
                {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                .then(response => {
                    this.companies = response.data.data
                    console.log("companies", this.companies)
                })
                .catch(err => console.log(err));
            },
            fetchUserWiseCompany() {
                this.$http.get(`/api/company/${this.user.id}`,
                {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                .then(response => {
                    this.company = response.data.data
                    console.log("company", this.company)
                })
                .catch(error => {
                    if (error.response) {
                        console.log(error.response);
                        if (error.response.status === 401) {
                            this.$router.push('/login')
                        }
                    }
                });
            },
            sidebarToggle(e) {
                e.preventDefault()
                document.body.classList.toggle('sidebar-hidden')
            },
            sidebarMinimize(e) {
                e.preventDefault()
                document.body.classList.toggle('sidebar-minimized')
            },
            mobileSidebarToggle(e) {
                e.preventDefault()
                document.body.classList.toggle('sidebar-mobile-show')
            },
            asideToggle(e) {
                e.preventDefault()
                document.body.classList.toggle('aside-menu-hidden')
            },
            logout() {
                this.$store.commit('changeUser', null)
                this.$router.push('/login')
            },
            
        },
        mounted() {
            // Echo.channel('comment')
            // .listen('NotifyComment', (e) => {
            //     console.log(e)
            // });

            // this.$http.post('/notification/get').then(response => {
            //     console.log(response.data)
            //     this.notifications = response.data;

            // //this.updateNumber()
            // })

            let userId = this.$store.state.user.id
            console.log('Here user id', userId)
            Echo.private('App.User.' + userId).notification((notification) => {
                console.log('Notification from pusher', notification)
                this.notifications.unshift(notification)
                //this.updateNumber()
            })
        }
    }
</script>
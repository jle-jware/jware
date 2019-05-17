<template>
    <div class="app flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="card mx-4">
                        <div class="card-body p-4">
                            <h1>Register</h1>
                            <p class="text-muted">User Information</p>
                            <div class="input-group mb-3">
                                <span class="input-group-addon">
                                    <i class="icon-user"></i>
                                </span>
                                <input type="text" class="form-control" v-model="user.first_name" :class="errors.has('first_name') ? 'form-control is-invalid':'form-control'" v-validate="'required'" name="first_name" placeholder="First Name">
                            </div>

                            <div class="input-group mb-3">
                                 <span class="input-group-addon">
                                    <i class="icon-user"></i>
                                </span>
                               <input type="text" class="form-control" :class="errors.has('last_name') ? 'form-control is-invalid':'form-control'" v-validate="'required'" name="last_name" v-model="user.last_name" placeholder="Last Name">
                            </div>

                             <div class="input-group mb-3">
                                 <span class="input-group-addon">
                                    <i class="icon-user"></i>
                                </span>
                               <input type="text" class="form-control" :class="errors.has('username') ? 'form-control is-invalid':'form-control'" v-validate="'required'" name="username" v-model="user.username" placeholder="User Name">
                            </div>

                             <div class="input-group mb-3">
                                <span class="input-group-addon">
                                    <i class="icon-phone"></i>
                                </span>
                               <input type="text" class="form-control" v-model="user.phone_number" placeholder="Phone Number">
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-addon">@</span>
                                <input type="text" class="form-control" :class="errors.has('email') ? 'form-control is-invalid':'form-control'" v-validate="'required'" name="email" v-model="user.email" placeholder="Email">
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-addon">
                                    <i class="icon-lock"></i>
                                </span>
                                <input type="password" class="form-control" :class="errors.has('password') ? 'form-control is-invalid':'form-control'" v-validate="'required'" name="password" v-model="user.password" placeholder="Password">
                            </div>

                            <div class="input-group mb-4">
                                <span class="input-group-addon">
                                    <i class="icon-lock"></i>
                                </span>
                                <input type="password" class="form-control"  :class="errors.has('repeatPassword') ? 'form-control is-invalid':'form-control'" v-validate="'required'" name="repeatPassword" v-model="user.repeatPassword" placeholder="Repeat password">
                            </div>

                            <button type="button" @click.prevent="submit" class="btn btn-block btn-success">Create User</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "user",
        data() {
            return {
                user: {
                    username: "",
                    first_name:"",
                    last_name:"",
                    phone_number:"",
                    email: "",
                    account_id:1,
                    company_id:1,
                    is_user:1,
                    key_contact:1,
                    password: "",
                    repeatPassword: ""
                }
               
            }
        },

        created() {
        },
         computed: {
            counter: function() {
                return this.$store.getters.counter
            }
        },

        methods: {
            submit() {
                this.$validator.validate().then(result => {
                    if (result)
                        this.register()
                })
            },
            register() {
                if(this.user.password !== this.user.repeatPassword){
                    console.log('Password is not same')
                    return
                }
                this.$http.post('/api/register', this.user)
                    .then(
                        (response) => {
                            console.log(response)
                            this.$router.push(`/login`)
                        }
                    )
                    .catch(
                        (error) => {
                            console.log(error)
                            this.$swal({
                                title: "",
                                text: "IDかパスワードが違います。",
                                type: "warning",
                                confirmButtonText: "OK"
                            });
                        }
                    );
            },
        }
    }
</script>
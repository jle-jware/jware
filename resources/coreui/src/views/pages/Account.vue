<template>
    <div class="app flex-row align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="card mx-4">
                        <div class="card-body p-4">
                            <h1>Create</h1>
                            <!-- <p class="text-muted">Account</p>
                            <div class="input-group mb-3">
                                <span class="input-group-addon">
                                    <i class="icon-user"></i>
                                </span>
                                <input type="text" :class="errors.has('name') ? 'form-control is-invalid':'form-control'" v-validate="'required'" name="name" v-model="account.name" placeholder="Account Name">
                                <br>
                                <span class="invalid-feedback">
                                      {{ errors.first('name') }}
                                </span>
                            </div> -->

                            <b-form-group>
								<label class="col-form-label" for="inputIsInvalid">Account</label>
								 <input type="text" :class="errors.has('name') ? 'form-control is-invalid':'form-control'" v-validate="'required'" data-vv-as="account name" name="name" v-model="account.name" placeholder="Account Name">
								<b-form-invalid-feedback>
									{{ errors.first('name') }}
								</b-form-invalid-feedback>
							</b-form-group>

                            <button type="button" @click.prevent="submit" class="btn btn-block btn-success">Create Account</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        name: "account",
        data() {
            return {
                account: {
                    name: "",
                    enable:1,     
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
                        this.create()
                })
            },
            create(){
                this.$http.post('/api/account', this.account)
                .then(
                    (response) => {
                        console.log(response)
                        this.$router.push("/company/");
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
                        })
                    }
                )
            }
        }
    }
</script>
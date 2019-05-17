<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm()">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                            <router-link :to="{ path: `/assetModel/${this.machineOption.asset_model_id}`}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button type="button" @click.prevent="submit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                            <!-- <button type="button" @click.prevent="submit" class="btn btn-block btn-success">Create Account</button> -->
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Option Type">
                                        <b-form-input type="text" :class="errors.has('option_name') ? 'form-control is-invalid':'form-control'"
                                            v-validate="'required'" name="option_name" v-model="machineOption.option_name"
                                            placeholder="Enter Option Type"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Option Description">
                                        <b-form-input type="text" :class="errors.has('value') ? 'form-control is-invalid':'form-control'"
                                            v-validate="'required'" name="value" v-model="machineOption.value"
                                            placeholder="Enter Description"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: "forms",
        components: { },
        data() {
            return {
                machineOption: {

                },
                edit: false,
            }
        },
        mounted() {
          
        },
        created() {
            if (this.$route.path.indexOf("create") > -1) {
                console.log("Create Location", this.$route.params.id)
                this.machineOption.asset_model_id = this.$route.params.id
                this.machineOption.account_id = this.$store.state.user.account_id
                this.edit = false
            }
            else {
                this.edit = true
                this.fetchById(this.$route.params.id)
            }

        },
        methods: {
            submit() {
                this.$validator.validate().then(result => {
                    if (result) this.submitForm()
                });
            },
            submitForm() {
                self = this
                if (this.edit) {
                    this.machineOption.updated_by = this.$store.state.user.id
                    this.$http
                        .put(`/api/machineOption`, this.machineOption, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            //alert("Machine Option Information updated")
                            this.$router.push({ path: `/assetModel/${self.machineOption.asset_model_id}`, query: { flag: 'machineOption' } })
                        })
                        .catch(error => {
                            if (error.response) {
                                console.log(error.response);
                                if (error.response.status === 401) {
                                    this.$router.push('/login')
                                }
                            }
                        });
                } else {
                    this.machineOption.created_by = this.$store.state.user.id
                    this.$http
                        .post(`/api/machineOption`, this.machineOption, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            //alert("Machine Option Optiontion Information Added");
                            this.$router.push({ path: `/assetModel/${self.machineOption.asset_model_id}`, query: { flag: 'machineOption' } });
                        })
                        .catch(error => {
                            if (error.response) {
                                console.log(error.response);
                                if (error.response.status === 401) {
                                    this.$router.push('/login')
                                }
                            }
                        });
                }
            },
            fetchById(id) {
                this.$http.get(`/api/machineOption/${id}`, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                    .then(response => {
                        this.machineOption = response.data.data
                        console.log("machineOption", this.machineOption)
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push('/login')
                            }
                        }
                    });
            },
        }
    };
</script>
<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                            <router-link :to="{ path: `/job/${this.assetUtilization.job_id}`}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button type="button" @click.prevent="submit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                            <!-- <button type="button" @click.prevent="submit" class="btn btn-block btn-success">Create Account</button> -->
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Date">
                                        <b-form-input type="date" v-model="assetUtilization.date" placeholder=""></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Value">
                                        <b-form-input type="text" :class="errors.has('value') ? 'form-control is-invalid':'form-control'" v-validate="'numeric'"
                                            name="allocated" v-model="assetUtilization.value" placeholder="Enter value"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <b-form-textarea id="textarea1" v-model="assetUtilization.comment" placeholder="comment" :rows="3" :max-rows="6">
                                    </b-form-textarea>
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
    import Multiselect from "vue-multiselect";
    export default {
        name: "forms",
        components: { Multiselect},
        data() {
            return {
                assetUtilization: {
                    
                },
                edit: false,
            };
        },
        created() {
            if (this.$route.path.indexOf("create") > -1) {
                console.log("Create assetUtilization", this.$route.params.id)
                this.assetUtilization.account_id = this.$store.state.user.account_id
                this.edit = false
                this.assetUtilization.job_id = this.$route.params.id
                this.fetchJobById(this.$route.params.id)
            }
            else {
                this.edit = true;
                this.fetchById(this.$route.params.id);
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
                    this.assetUtilization.updated_by = this.$store.state.user.id
                    this.$http
                        .put(`/api/assetUtilization`, this.assetUtilization, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                                //alert("Asset Utilization Information updated");
                                this.$router.push({ path: `/job/${self.job.id}/new` });
                        })
                        .catch(error => {
                            if (error.response) {
                                console.log(error.response);
                                if (error.response.status === 401) {
                                    window.assetUtilization.href = '/login'
                                }
                            }
                        });
                } else {
                    this.assetUtilization.created_by = this.$store.state.user.id;
                    this.$http
                        .post(`/api/assetUtilization`, this.assetUtilization, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                                //alert("Asset Utilization Information Added");
                                this.$router.push({ path: `/job/${self.job.id}/new` });
                        })
                        .catch(error => {
                            if (error.response) {
                                console.log(error.response);
                                if (error.response.status === 401) {
                                    window.assetUtilization.href = '/login'
                                }
                            }
                        });
                }
            },
            fetchById(id) {
                NProgress.start()
                this.$http.get(`/api/assetUtilization/${id}`, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                    .then(response => {
                        this.assetUtilization = response.data.data
                        console.log("assetUtilization", this.assetUtilization)
                        this.fetchJobById(this.assetUtilization.job_id)
                        NProgress.done()
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response);
                            if (error.response.status === 401) {
                                window.assetUtilization.href = '/login'
                            }
                        }
                    });
            },
            fetchJobById(id) {
                NProgress.start()
                this.$http.get(`/api/job/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.job = response.data.data;
                        this.fetchSpareByAsset(this.job.asset_id)
                        console.log("job", this.job);
                        NProgress.done()
                    }).catch(error => {
                        if (error.response) {
                            console.log(error.response);
                            if (error.response.status === 401) {
                                this.$router.push('/login')
                            }
                        }
                    });
            },
        },
    };
</script>
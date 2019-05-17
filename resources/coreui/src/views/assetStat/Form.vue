<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                                <router-link :to="{ name: 'Asset Status List'}">
                                    <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                                </router-link>
                                <button type="button" @click.prevent="submit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <b-form-fieldset label="Asset Status">
                                        <b-form-input type="text" :class="errors.has('type') ? 'form-control is-invalid':'form-control'" v-validate="'required'"
                                            name="type" v-model="assetStat.type" placeholder="Please enter"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <b-form-textarea id="textarea1" v-model="assetStat.comment" placeholder="Comments" :rows="3" :max-rows="6">
                                    </b-form-textarea>
                                </div>
                            </div>
                            <!-- {{assetStat}} {{edit}} -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'forms',
        components: {
        },
        data() {
            return {
                assetStat: {
                    
                },
                edit: false
            }
        },
        created() {
            console.log(this.$store.state.user);
            if (!this.$store.state.user) this.$router.push("/login");
            this.assetStat.account_id = this.$store.state.user.account_id;
           
            if (this.$route.path.indexOf("create") > -1) {
                console.log("show result", this.$route.path.indexOf("create"));
                this.edit = false;
            } else {
                this.edit = true;
                this.fetchById(this.$route.params.id);
            }
        },
        methods: {
            submit() {
                this.$validator.validate().then(result => {
                    if (result)
                        this.submitForm()
                })
            },
            submitForm() {
                self = this;
                if (this.edit) {
                    this.assetStat.updated_by = this.$store.state.user.id;
                    this.$http
                        .put(`/api/assetStat`, this.assetStat,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        .then(response => {
                            //alert("Asset Status Updated");
                            this.$router.push("/assetStats/");
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
                    this.assetStat.created_by = this.$store.state.user.id;
                    this.$http
                        .post(`/api/assetStat`, this.assetStat,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        .then(response => {
                            //alert("Asset Status Added");
                            this.$router.push("/assetStats/");
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
                this.$http.get(`/api/assetStat/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.assetStat = response.data.data;
                        console.log("assetStat", this.assetStat);
                    }).catch(error => {
                    if (error.response) {
                        console.log(error.response);
                        if (error.response.status === 401) {
                            this.$router.push('/login')
                        }

                    }
                });
            },
        }
    }
</script>
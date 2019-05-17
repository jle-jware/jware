<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                                <router-link :to="{ name: 'Result List'}">
                                    <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                                </router-link>
                                <button type="button" @click.prevent="submit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Result For">
                                        <b-form-input type="text" :class="errors.has('type') ? 'form-control is-invalid':'form-control'" v-validate="'required'"
                                            name="type" v-model="inspectionResultType.type" placeholder="Please enter type"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Result">
                                        <b-form-input type="text" :class="errors.has('name') ? 'form-control is-invalid':'form-control'" v-validate="'required'"
                                            name="type" v-model="inspectionResultType.name" placeholder="Please enter inspectionResultType"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <b-form-textarea id="textarea1" v-model="inspectionResultType.comment" placeholder="Comments" :rows="3" :max-rows="6">
                                    </b-form-textarea>
                                </div>
                            </div>
                            <!-- {{inspectionResultType}} {{edit}} -->
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
                inspectionResultType: {
                    
                },
                edit: false
            }
        },
        created() {
            console.log(this.$store.state.user)
            if (!this.$store.state.user) this.$router.push("/login")
            this.inspectionResultType.account_id = this.$store.state.user.account_id;
           
            if (this.$route.path.indexOf("create") > -1) {
                console.log("show inspectionResultType", this.$route.path.indexOf("create"))
                this.edit = false
            } else {
                this.edit = true
                this.fetchById(this.$route.params.id)
            }
        },
        methods: {
            submit() {
                this.$validator.validate().then(inspectionResultType => {
                    if (inspectionResultType)
                        this.submitForm()
                })
            },
            submitForm() {
                self = this
                if (this.edit) {
                    this.inspectionResultType.updated_by = this.$store.state.user.id
                    this.$http
                        .put(`/api/inspectionResultType`, this.inspectionResultType,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        .then(response => {
                            //alert("Result Updated")
                            this.$router.push("/inspectionResultTypes/");
                        })
                        .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push('/login')
                            }

                        }
                    });
                } else {
                    this.inspectionResultType.created_by = this.$store.state.user.id
                    this.$http
                        .post(`/api/inspectionResultType`, this.inspectionResultType,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        .then(response => {
                            //alert("Result Added")
                            this.$router.push("/inspectionResultTypes/")
                        })
                        .catch(error => {
                        if (error.response) {
                            console.log(error.response);
                            if (error.response.status === 401) {
                                this.$router.push('/login')
                            }
                        }
                    })
                }
            },
            fetchById(id) {
                this.$http.get(`/api/inspectionResultType/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.inspectionResultType = response.data.data
                        console.log("inspectionResultType", this.inspectionResultType)
                    }).catch(error => {
                    if (error.response) {
                        console.log(error.response)
                        if (error.response.status === 401) {
                            this.$router.push('/login')
                        }

                    }
                })
            },
        }
    }
</script>
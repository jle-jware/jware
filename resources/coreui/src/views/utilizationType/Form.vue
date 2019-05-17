<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm()">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                                <router-link :to="{ name: 'Utilization Type List'}">
                                    <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                                </router-link>
                               <button type="button" @click.prevent="submit" ref="btnSubmit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Utilization Type">
                                        <b-form-input type="text" :class="errors.has('utilization_type') ? 'form-control is-invalid':'form-control'" v-validate="'required'"
                                            name="utilization_type" v-model="utilizationType.utilization_type" placeholder="Please enter"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Utilization Unit">
                                        <b-form-input type="text" :class="errors.has('utilization_unit') ? 'form-control is-invalid':'form-control'" v-validate="'required'"
                                            name="utilization_unit" v-model="utilizationType.utilization_unit" placeholder="Please enter"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Rate Unit">
                                        <b-form-input type="text" :class="errors.has('rate_unit') ? 'form-control is-invalid':'form-control'" v-validate="'required'"
                                            name="rate_unit" v-model="utilizationType.rate_unit" placeholder="Please enter"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Unit Duration Days">
                                        <b-form-input type="text" v-model="utilizationType.unit_duration_days" placeholder="Please enter"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <b-form-textarea id="textarea1" v-model="utilizationType.comment" placeholder="Comments" :rows="3" :max-rows="6">
                                    </b-form-textarea>
                                </div>
                            </div>
                            <!-- {{utilizationType}} {{edit}} -->
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
                utilizationType: {
                    
                },
                edit: false
            }
        },
        created() {
            console.log(this.$store.state.user)
            if (!this.$store.state.user) this.$router.push("/login")
            this.utilizationType.account_id = this.$store.state.user.account_id
           
            if (this.$route.path.indexOf("create") > -1) {
                console.log("show result", this.$route.path.indexOf("create"))
                this.edit = false
            } else {
                this.edit = true
                this.fetchById(this.$route.params.id)
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
                this.$refs.btnSubmit.disabled = true
                self = this
                if (this.edit) {
                    this.utilizationType.updated_by = this.$store.state.user.id
                    this.$http
                        .put(`/api/utilizationType`, this.utilizationType,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })                      
                        .then(response => {
                            //alert("Utilization Type Updated");
                            this.$router.push("/utilizationTypes/");
                            this.$refs.btnSubmit.disabled = false
                        })
                        .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push('/login')
                            }
                            this.$refs.btnSubmit.disabled = false
                        }
                    })
                } else {
                    this.utilizationType.created_by = this.$store.state.user.id
                    this.$http
                        .post(`/api/utilizationType`, this.utilizationType,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        .then(response => {
                            alert("Utilization Type Added")
                            this.$router.push("/utilizationTypes/")
                            this.$refs.btnSubmit.disabled = false
                        })
                        .catch(error => {
                        if (error.response) {
                            console.log(error.response);
                            if (error.response.status === 401) {
                                this.$router.push('/login')
                            }
                            this.$refs.btnSubmit.disabled = false
                        }
                    })
                }
            },
            fetchById(id) {
                this.$http.get(`/api/utilizationType/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.utilizationType = response.data.data;
                        console.log("utilizationType", this.utilizationType)
                    }).catch(error => {
                    if (error.response) {
                        console.log(error.response);
                        if (error.response.status === 401) {
                            this.$router.push('/login')
                        }

                    }
                })
            },
        }
    }
</script>
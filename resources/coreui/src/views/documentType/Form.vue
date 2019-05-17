<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                                <router-link :to="{ name: 'Document Type List'}">
                                    <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                                </router-link>
                                <button type="button" @click.prevent="submit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <b-form-fieldset label="Document Type">
                                        <b-form-input type="text" :class="errors.has('type') ? 'form-control is-invalid':'form-control'" v-validate="'required'"
                                            name="type" v-model="documentType.type" placeholder="Please enter"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <b-form-textarea id="textarea1" v-model="documentType.comment" placeholder="Comments" :rows="3" :max-rows="6">
                                    </b-form-textarea>
                                </div>
                            </div>
                            <!-- {{documentType}} {{edit}} -->
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
                documentType: {
                    
                },
                edit: false
            }
        },
        created() {
            console.log(this.$store.state.user);
            if (!this.$store.state.user) this.$router.push("/login");
            this.documentType.account_id = this.$store.state.user.account_id;
           
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
                    this.documentType.updated_by = this.$store.state.user.id;
                    this.$http
                        .put(`/api/documentType`, this.documentType,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        // .then(response => {
                        //     //this.$router.push("/companies/");
                        //     this.$swal({
                        //             title: "Do You Want To Update!",
                        //             text: "Please Click 'OK' To Save ",
                        //             type: "success",
                        //             confirmButtonText: "OK"
                        //         })
                        //         .then(function () {
                        //             self.$router.push("/documentTypes/");
                        //         });
                        // })
                        .then(response => {
                            //alert("Document Type Updated");
                            this.$router.push("/documentTypes/");
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
                    this.documentType.created_by = this.$store.state.user.id;
                    this.$http
                        .post(`/api/documentType`, this.documentType,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        // .then(response => {
                        //     this.$swal({
                        //         title: "Do You Want To Save!",
                        //         text: "Please Click 'OK' To Save ",
                        //         type: "success",
                        //         confirmButtonText: "OK"
                        //     }).then(function () {
                        //         self.$router.push("/documentTypes/");
                        //     });
                        // })
                        .then(response => {
                            //alert("Document Type Added");
                            this.$router.push("/documentTypes/");
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
                this.$http.get(`/api/documentType/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.documentType = response.data.data;
                        console.log("documentType", this.documentType);
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
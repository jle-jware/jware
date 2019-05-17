<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                            <router-link :to="{ path: `/job/${this.spareJob.job_id}`}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button type="button" @click.prevent="submit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                            <!-- <button type="button" @click.prevent="submit" class="btn btn-block btn-success">Create Account</button> -->
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label for="">Select Spare</label>
                                    <multiselect v-model="selectedSpare"  :options="spares" @select="onSelectSpare" track-by="id" label="full_spare_info" placeholder="Select Spare"
                                        selectLabel="" deselectLabel="" selectedLabel="Remove">
                                    </multiselect>
                                    <div class="invalid-feedback">Please Select Spare</div>
                                </div>
                                <div class="form-group col-sm-3">
                                    <b-form-fieldset label="Available Parts">
                                        <b-form-input type="text" disabled v-model="availableStock" placeholder="Enter spareJob Description"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-3">
                                    <b-form-fieldset label="Allocated Parts">
                                        <b-form-input type="text" :class="errors.has('allocated') ? 'form-control is-invalid':'form-control'" v-validate="'numeric'"
                                            name="allocated" @change="checkStock" v-model="spareJob.allocated" placeholder="Enter spareJob Description"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-3">
                                    <b-form-fieldset label="Used Parts">
                                        <b-form-input type="text" v-model="spareJob.used" placeholder="Enter used parts"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <b-form-textarea id="textarea1" v-model="spareJob.comment" placeholder="comment" :rows="3" :max-rows="6">
                                    </b-form-textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- {{job}} -->
            {{this.availableStock}}
            {{this.spareJob.allocated}}
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
                spares: [],
                availableStock:0,
                spareJob: {
                    
                },
                edit: false,
                selectedSpare: { id: null, full_spare_info: 'Select Spare',current_stock:0 },
                job:{},
                address: ''
            };
        },
        created() {
            if (this.$route.path.indexOf("create") > -1) {
                console.log("Create spareJob", this.$route.params.id)
                this.spareJob.account_id = this.$store.state.user.account_id
                this.edit = false
                this.spareJob.job_id = this.$route.params.id
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
                    this.spareJob.updated_by = this.$store.state.user.id
                    this.$http
                        .put(`/api/spareJob`, this.spareJob, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                                //alert("spareJob Information updated");
                                this.$router.push({ path: `/job/${self.job.id}` });
                        })
                        .catch(error => {
                            if (error.response) {
                                console.log(error.response);
                                if (error.response.status === 401) {
                                    window.spareJob.href = '/login'
                                }
                            }
                        });
                } else {
                    this.spareJob.created_by = this.$store.state.user.id;
                    this.$http
                        .post(`/api/spareJob`, this.spareJob, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                                //alert("spareJob Information Added");
                                this.$router.push({ path: `/job/${self.job.id}` });
                        })
                        .catch(error => {
                            if (error.response) {
                                console.log(error.response);
                                if (error.response.status === 401) {
                                    window.spareJob.href = '/login'
                                }
                            }
                        });
                }
            },
            fetchById(id) {
                NProgress.start()
                this.$http.get(`/api/spareJob/${id}`, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                    .then(response => {
                        this.spareJob = response.data.data
                        console.log("spareJob", this.spareJob)
                        this.fetchJobById(this.spareJob.job_id)
                        this.fetchSpareByAssetModel(this.job.asset.asset_model_id)
                        NProgress.done()
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response);
                            if (error.response.status === 401) {
                                window.spareJob.href = '/login'
                            }
                        }
                    });
            },
            onSelectSpare(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.spareJob.spare_id = selectedOption.id
                    this.availableStock = this.spares.find(x => parseInt(x.id) === parseInt(selectedOption.id)).current_stock
                    // this.selectedSpare = this.spares.find(x => x.id === this.selectedOption.id).current_stock
                }
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
                        this.job = response.data.data
                        this.fetchSpareByAssetModel(this.job.asset.asset_model_id)
                        console.log("job", this.job)
                        NProgress.done()
                    }).catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push('/login')
                            }
                        }
                    });
            },
            fetchSpareByAssetModel(id) {
                NProgress.start()
                this.$http.get(`/api/asset-model/${id}/spares`, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                    .then(response => {
                        this.spares = response.data.data
                        console.log("spares", this.spares);
                        this.selectedSpare = this.spares.find(x => parseInt(x.id) === parseInt(this.spareJob.spare_id))
                        this.availableStock = this.spares.find(x => parseInt(x.id) === parseInt(this.spareJob.spare_id)).current_stock
                        NProgress.done()
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
            checkStock(){
                console.log(this.spareJob.allocated)
                if(parseInt(this.availableStock) < parseInt(this.spareJob.allocated))
                {
                    this.$swal("Allocated Quantity Can't Be Greater Than Available Stock")
                    this.spareJob.allocated = 0
                }   
            }
        },
    };
</script>
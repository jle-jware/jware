<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                            <router-link :to="{ path: `/machineSpecs`}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button type="button" @click.prevent="submit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="">Asset Model *</label>
                                    <multiselect v-model="selectedAssetType" :class="{ 'is-danger': !machineSpec.asset_model_id }"
                                        :options="assetModels" @select="onSelectAssetType" track-by="id" label="name"
                                        placeholder="Select Asset Type" selectLabel="" deselectLabel="" selectedLabel="Remove"
                                        v-validate="'required'">
                                    </multiselect>
                                    <!-- <a @click.prevent="addAssetType()" href="#">Add New Asset Type</a> -->
                                </div>
                                <div class="form-group col-sm-4">
                                     <b-form-fieldset label="Serial">
                                        <b-form-input type="text" v-model="machineSpec.serial" placeholder="Enter Serial Number"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-4">
                                     <b-form-fieldset label="BMB">
                                        <b-form-input type="text" v-model="machineSpec.bmb" placeholder="Enter BMB"></b-form-input>
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
    import Multiselect from "vue-multiselect"
    export default {
        name: 'forms',
        components: { Multiselect },
        data() {
            return {
                assetModels: [],
                machineSpec:{
                    bmb:''
                },
                selectedAssetType: { id: null, name: 'Select Asset Type' },
                edit: false,
            }
        },
        created() {
            console.log(this.$store.state.user);
            if (!this.$store.state.user) this.$router.push("/login");

            this.machineSpec.account_id = this.$store.state.user.account_id
            console.log("show asset id", this.machineSpec.asset_id)

            if (this.$route.path.indexOf("create") > -1) {
                console.log("show result", this.$route.path.indexOf("create"));
                this.edit = false;
                this.fetchAssetModels()
                this.getBmbNumber()
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
            fetchAssetModels() {
                NProgress.start()
                this.$http
                    .get(`/api/assetModels`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.assetModels = response.data.data;
                        this.selectedAssetType = this.assetModels.find(x => parseInt(x.id) === parseInt(this.machineSpec.asset_model_id))
                        console.log("assetModels", this.assetModels);
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
            onSelectAssetType(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.machineSpec.asset_model_id = selectedOption.id
                }
            },
            submitForm() {
                self = this
                if (this.edit) {
                    this.machineSpec.updated_by = this.$store.state.user.id;
                    this.$http
                        .put(`/api/machineSpec`, this.machineSpec,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        .then(response => {
                            alert("Machine Spec Updated");
                            this.$router.push("/machineSpecs/")
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
                    this.machineSpec.created_by = this.$store.state.user.id;
                    this.$http
                        .post(`/api/machineSpec`, this.machineSpec,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        .then(response => {
                            alert("Machine Spec Saved");
                            this.$router.push("/machineSpecs/")
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
                this.$http.get(`/api/machineSpec/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.machineSpec = response.data.data
                        console.log("machineSpec", this.machineSpec)
                        this.fetchAssetModels()
                    }).catch(error => {
                        if (error.response) {
                            console.log(error.response);
                            if (error.response.status === 401) {
                                this.$router.push('/login')
                            }
                        }
                    });
            },
            getBmbNumber() {
                this.$http.get(`/api/bmb-number`, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                    .then(response => {
                        this.machineSpec.bmb = parseInt(response.data.bmbNumber) + 1
                        console.log(response)
                    })
                    .catch(error => {
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
<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                            <router-link :to="{ name: 'Spare List'}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button type="button" @click.prevent="submit" style="margin-right: 10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Spare Name *">
                                        <b-form-input type="text" :class="errors.has('name') ? 'form-control is-invalid':'form-control'" v-validate="'required'"
                                            name="name" v-model="spare.name" placeholder="Please enter Spare Name"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Spare Serial *">
                                        <b-form-input type="text" :class="errors.has('serial') ? 'form-control is-invalid':'form-control'" v-validate="'required'"
                                            name="serial" v-model="spare.serial" placeholder="Please enter Spare serial"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="">Asset Model List *</label>
                                    <multiselect v-model="selectedAssetModel"  :options="assetModels"  track-by="id" label="name" placeholder="Select Asset Model"
                                        selectLabel="" deselectLabel="" selectedLabel="Remove" v-validate="'required'" :multiple="true"  :taggable="true">
                                    </multiselect>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="">Manufacturer List *</label>
                                     <multiselect v-model="selectedmanufacturer"  :options="manufacturers" @select="onSelectmanufacturer" track-by="id" label="name" placeholder="Select Asset Model"
                                        selectLabel="" deselectLabel="" selectedLabel="Remove" v-validate="'required'" >
                                    </multiselect>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="">Spare Type *</label>
                                    <multiselect v-model="selectedSpareType"  :options="spareTypes" @select="onSelectSpareType" track-by="id" label="type" placeholder="Select Spare Types"
                                        selectLabel="" deselectLabel="" selectedLabel="Remove" v-validate="'required'" >
                                    </multiselect>
                                    <a @click.prevent="addNewSpareType()" href="#">Add New Spare Type</a>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Cost *">
                                        <money v-model="spare.cost" class="form-control" v-bind="money"></money>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Minimum Stock *">
                                        <b-form-input type="text" :class="errors.has('minimum_stock') ? 'form-control is-invalid':'form-control'" v-validate="'required'"
                                            name="minimum_stock" v-model="spare.minimum_stock" placeholder="Please enter minimum stock"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-6">
                                   <b-form-fieldset label="Current Stock *">
                                        <b-form-input type="text" :class="errors.has('current_stock') ? 'form-control is-invalid':'form-control'" v-validate="'required'"
                                            name="current_stock" v-model="spare.current_stock" placeholder="Please enter current stock"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-textarea id="textarea1" v-model="spare.description" placeholder="Descriptions" :rows="3" :max-rows="6">
                                    </b-form-textarea>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-textarea id="textarea1" v-model="spare.comment" placeholder="Comments" :rows="3" :max-rows="6">
                                    </b-form-textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <!-- <img :src="userImage" class="img-fluid img-thumbnail" style="height: 200px" v-show="image!=null">
                                    <img src="/img/company.png" class="img-fluid img-thumbnail" style="height: 200px" v-show="image==null"> -->
                                    <div class="col-md-12 mb-3">
                                        <!-- <label for="">image</label> -->
                                        <!-- <input type="file" ref="files" placeholder="Spare image" :class="{'form-control': true,'is-invalid': errors.first('image')}"
                                            v-on:change="handleFilesUpload" name="image" v-validate="!edit ?'': ''"> -->
                                        <!-- <div class="invalid-feedback" v-show="errors.first('image')">{{ errors.first('image')  }}</div> -->
                                    </div>
                                </div>
                            </div>
                            <!-- {{spare}}{{edit}} -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <b-modal ref="spareTypeModal" size="lg" id="modal-center" @ok="submitSpareType()" centered title="Add New Spare Type">
            <div class="row">
              <div class="col-sm-12">
                <form>
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <b-form-fieldset label="Spare Type">
                                        <b-form-input type="text" :class="errors.has('type') ? 'form-control is-invalid':'form-control'"
                                            name="type" v-model="spareType.type" placeholder="Please enter Type"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <b-form-textarea id="textarea1" v-model="spareType.comment" placeholder="Comments" :rows="3" :max-rows="6">
                                    </b-form-textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
              </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import Multiselect from "vue-multiselect";
    import {Money} from 'v-money'
    export default {
        name: 'forms',
        components: { Multiselect,Money },
        data() {
            return {
                assetModels: [],
                customers: [],
                manufacturers: [],
                spareTypes: [],
                spare: {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
                },
                spareType: {},
                money: {
                    decimal: '',
                    thousands: ',',
                    prefix: 'NZD$ ',
                    suffix: ' ',
                    precision: 0,
                    masked: false
                },
                edit: false,
                selectedAssetModel: [],
                selectedCustomer: { id: null, name: 'Select Customer' },
                selectedmanufacturer: { id: null, name: 'Select manufacturer' },
                selectedSpareType: { id: null, type: 'Select Spare Types' },
                value: [],
            }
        },
        created() {
            console.log(this.$store.state.user);
            if (!this.$store.state.user) this.$router.push("/login");
            this.spare.account_id = this.$store.state.user.account_id;
            this.fetchAssetModels()
            this.fetchManufacturers()
            this.fetchSpareTypes()
            if (this.$route.path.indexOf("create") > -1) {
                console.log("show result", this.$route.path.indexOf("create"));
                this.edit = false;
            } else {
                this.edit = true;
                this.fetchById(this.$route.params.id)
                this.fetchAssetModels(this.spare.asset_model_id)
                this.fetchManufacturers(this.spare.company_id)
                this.fetchSpareTypes(this.spare.spare_type_id)
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
                    this.spare.updated_by = this.$store.state.user.id;
                    this.$http
                        .put(`/api/spare`, this.spare,
                        {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            //alert("Spare Updated");
                            this.$router.push("/spares/");
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
                    this.spare.created_by = this.$store.state.user.id;
                    this.$http
                        .post(`/api/spare`, this.spare,
                        {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            //alert("Spare Added");
                            this.$router.push("/spares/");
                        })
                        .catch(error => {
                            console.log(error);
                            this.$setErrorsFromResponse(err.response.data);
                            this.$emit("error", err.response.data);
                        });
                }
            },
            fetchById(id) {
                NProgress.start()
                this.$http.get(`/api/spare/${id}`,
                {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                .then(response => {
                    this.spare = response.data.data;
                    console.log("spare", this.spare);
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
            fetchManufacturers() {
                NProgress.start()
                this.$http.get(`/api/manufacturers`,
                {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                .then(response => {
                    this.manufacturers = response.data.data;
                    this.selectedmanufacturer = this.manufacturers.find(x => parseInt(x.id) === parseInt(this.spare.company_id))
                    console.log("manufacturers", this.manufacturers);
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
            fetchAssetModels() {
                NProgress.start()
                this.$http.get(`/api/assetModels`,
                {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                .then(response => {
                    this.assetModels = response.data.data;
                    this.selectedAssetModel = this.assetModels.find(x => parseInt(x.id) === parseInt(this.spare.asset_model_id))
                    console.log("assetModels", this.assetModels);
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
            fetchSpareTypes() {
                NProgress.start()
                this.$http.get(`/api/spareTypes`,
                {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                .then(response => {
                    this.spareTypes = response.data.data;
                    this.selectedSpareType = this.spareTypes.find(x => parseInt(x.id) === parseInt(this.spare.spare_type_id))
                    console.log("spareTypes", this.spareTypes);
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
            onSelectmanufacturer(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.spare.company_id = selectedOption.id
                }
            },
            onSelectSpareType(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.spare.spare_type_id = selectedOption.id
                }
            },
            addNewSpareType(){
                this.$refs.spareTypeModal.show()
            },
            submitSpareType(){
                this.spareType.created_by = this.$store.state.user.id
                this.spareType.account_id = this.$store.state.user.account_id

                this.$http.post(`/api/spareType`, this.spareType, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                .then(response => {
                    //alert("Spare Type Added")
                    this.fetchSpareTypes()
                })
                .catch(error => {
                    if (error.response) {
                        console.log(error.response)
                        if (error.response.status === 401) {
                            this.$router.push('/login')
                        }
                    }
                })
            }
        },
        watch:{
            selectedAssetModel(value){
                if(Array.isArray(this.selectedAssetModel)){
                    this.spare.asset_model_id = JSON.stringify(this.selectedAssetModel.map(a => a.id))
                    console.log(this.spare.asset_model_id)
                }
            }
        }
    };
</script>
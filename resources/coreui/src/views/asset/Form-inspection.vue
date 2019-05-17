<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitClicked()">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                           <router-link :to="{ path: `/asset/${this.assetInspection.asset_id}`}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button type="submit" style="margin-right: 10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="">Inspection Criteria</label>
                                    <multiselect v-model="selectedInspectionCriteria" :class="{ 'is-danger': !assetInspection.inspection_criteria_id }" :options="inspectionCriterias" @select="onSelectInspectionCriteria" track-by="id" label="name" placeholder="Select Inspection Criteria"
                                        selectLabel="" deselectLabel="" selectedLabel="Remove" v-validate="'required'" >
                                    </multiselect>
                                </div>
                                <div class="form-group col-sm-4">
                                    <b-form-fieldset label="Frequency">
                                        <b-form-input type="text" v-model="assetInspection.frequency" placeholder="Enter Frequency" 
                                        name="frequency"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-4">
                                    <b-form-fieldset label="Group">
                                        <b-form-input type="text" v-model="assetInspection.group" placeholder="Enter Group Name" 
                                        name="group"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-textarea id="textarea1" v-model="assetInspection.description" placeholder="Comments" :rows="3" :max-rows="6">
                                    </b-form-textarea>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-textarea id="textarea1" v-model="assetInspection.comment" placeholder="Comments" :rows="3" :max-rows="6">
                                    </b-form-textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">     
                                    <div id="preview">
                                        <img v-if="url" :src="url" style="height:100px;"  />
                                        <img v-else src="/img/default.png" style="height:120px;" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="form-inline">
                                        <div class="form-group mt-4">
                                            <input aria-describedby="fileHelp"  class=" btn form-control-file" id="file" ref="file" v-on:change="handleFileUpload()" type="file">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<style>
    body {
        background-color: #e2e2e2;
    }

    #app {
        padding: 20px;
    }

    #preview {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #preview img {
        max-width: 100%;
        max-height: 500px;
    }
</style>
<script>
    import Multiselect from "vue-multiselect"
    export default {
        name: 'forms',
        components: { Multiselect },
        data() {
            return {
                inspectionCriterias: [],
                assetModels:[],
                assetInspection: {},
                selectedAssetModel: { id: null, name: 'Select Asset Model' },
                selectedInspectionCriteria: { id: null, name: 'Select Inspection Criteria' },
                edit: false,
                url: null,
                uploadedFile : null,
            }
        },
        created() {
            console.log(this.$store.state.user)
            if (!this.$store.state.user) this.$router.push("/login")
            this.assetInspection.account_id = this.$store.state.user.account_id
            this.assetInspection.asset_id = this.$route.params.id
            this.fetchInspectionCriterias()
            this.fetchAssetModels()
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
            fetchInspectionCriterias() {
                this.$http.get(`/api/inspectionCriterias`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.inspectionCriterias = response.data.data
                        console.log("inspectionCriterias", this.inspectionCriterias)
                        if (this.edit) {
                            this.selectedInspectionCriteria = this.inspectionCriterias.find(x => parseInt(x.id) === parseInt(this.assetInspection.inspection_criteria_id))
                        }
                    }).catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push('/login')
                            }
                        }
                    })
            },
            fetchAssetModels() {
                this.$http.get(`/api/assetModels`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.assetModels = response.data.data
                        console.log("assetModels", this.assetModels)
                        if (this.edit) {
                            this.selectedAssetModel = this.assetModels.find(x => parseInt(x.id) === parseInt(this.assetInspection.asset_model_id))
                        }
                    }).catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push('/login')
                            }

                        }
                    })
            },
            submitForm() {
                self = this
                if (this.edit) {
                    this.assetInspection.updated_by = this.$store.state.user.id
                    
                    this.$http
                        .put(`/api/assetInspection`, this.assetInspection,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        .then(response => {
                            this.$router.push({ path: `/asset/${self.assetInspection.asset_id}` })
                        })
                        .catch(error => {
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push('/login')
                                }
                            }
                        })
                } else {
                    console.log(this.formData)
                    this.assetInspection.created_by = this.$store.state.user.id
                    this.$http
                        .post(`/api/assetInspection`, this.assetInspection,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        .then(response => {
                            this.$router.push({ path: `/asset/${self.assetInspection.asset_id}` })
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
            fetchById(id) {
                this.$http.get(`/api/assetInspection/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.assetInspection = response.data.data
                        this.url = this.assetInspection.image
                        console.log("assetInspection", this.assetInspection)
                        this.fetchAssetModels()
                        this.fetchInspectionCriterias()
                    }).catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push('/login')
                            }
                        }
                    })
            },
            onFileChange(e) {
                let ref = this
                const file = e.target.files[0]
                this.url = URL.createObjectURL(file)
                
                var reader = new FileReader()
                reader.readAsDataURL(file);
                reader.onloadend = function () {
                    let base64data = reader.result
                    console.log(base64data)
                    ref.assetInspection.image = base64data
                }

                //console.log(this.url)
            },
            onSelectAssetModel(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.assetInspection.asset_model_id = selectedOption.id
                }
            },
            onSelectInspectionCriteria(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.assetInspection.inspection_criteria_id = selectedOption.id
                }
            },
            handleFileUpload() {
                this.uploadedFile = this.$refs.file.files[0]
                console.log(this.uploadedFile)
            },
            submitFile() {
                let formData = new FormData()
                formData.append('file', this.uploadedFile)
                console.log(formData)

                NProgress.start()
                this.$http.post('/api/upload-file',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                             Authorization: `Bearer ${this.$store.state.token}`
                        }
                    }
                )
                .then(response => {
                        NProgress.done()
                        console.log(response.data)
                        this.uploadedFile = null
                        const input = this.$refs.file
                        input.type = 'file'
                        this.assetInspection.file = response.data.file
                        this.submitForm()

                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            NProgress.done()
                            ErrorHandler.handle(error.response.status, this)
                        }
                    })
            },
            submitClicked(){
                if(this.uploadedFile){
                    this.submitFile()
                }
                else{
                    this.submitForm()
                }
            }

        }
    }
</script>
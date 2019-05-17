<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitClicked()">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                            <router-link :to="{ name: 'Document List'}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button type="submit" style="margin-right: 10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="">Select Asset Model</label>
                                    <!-- <b-form-select v-model="document.asset_model_id" class="mb-3">
                                        <option :value="null">Please select an option</option>
                                        <option v-for="assetModel in assetModels" v-bind:key="assetModel.id" v-bind:value="assetModel.id">{{assetModel.name}}</option>
                                    </b-form-select> -->
                                    <multiselect v-model="selectedAssetModel" :class="{ 'is-danger': !document.asset_model_id }" :options="assetModels" @select="onSelectAssetModel" track-by="id" label="name" placeholder="Select Asset Model"
                                        selectLabel="" deselectLabel="" selectedLabel="Remove" v-validate="'required'" >
                                    </multiselect>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="">Document Type</label>
                                    <!-- <b-form-select v-model="document.document_type_id" class="mb-3">
                                        <option :value="null">Please select an option</option>
                                        <option v-for="documentType in documentTypes" v-bind:key="documentType.id" v-bind:value="documentType.id">{{documentType.type}}</option>
                                    </b-form-select> -->
                                    <multiselect v-model="selectedDocumentType" :class="{ 'is-danger': !document.document_type_id }" :options="documentTypes" @select="onSelectDocumentType" track-by="id" label="type" placeholder="Select Document Type"
                                        selectLabel="" deselectLabel="" selectedLabel="Remove" v-validate="'required'" >
                                    </multiselect>
                                </div>
                                <div class="form-group col-sm-4">
                                    <b-form-fieldset label="Website">
                                        <b-form-input type="text" :class="errors.has('url') ? 'form-control is-invalid':'form-control'" v-model="document.url" placeholder="http://www.website.com" 
                                        name="url" v-validate="'url:{require_protocol?}'"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-textarea id="textarea1" v-model="document.description" placeholder="Description" :rows="3" :max-rows="6">
                                    </b-form-textarea>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-textarea id="textarea1" v-model="document.comment" placeholder="Comments" :rows="3" :max-rows="6">
                                    </b-form-textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">     
                                    <div id="preview">
                                        <img v-if="url" :src="url" style="height:100px;"  />
                                        <img v-else src="/img/document.png" style="height:120px;" >
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="form-group col-sm-4">
                                    <b-form-file  ref="file" v-on:change="handleFileUpload()" placeholder="Choose a file..."></b-form-file>
                                </div>
                            </div> -->
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
                        <!-- {{document}} {{edit}} -->
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
    import Multiselect from "vue-multiselect";
    export default {
        name: 'forms',
        components: { Multiselect },
        data() {
            return {
                documentTypes: [],
                assetModels:[],
                document: {
                    document_type_id: null,
                    asset_model_id:null
                },
                selectedAssetModel: { id: null, name: 'Select Asset Model' },
                selectedDocumentType: { id: null, type: 'Select Document Type' },
                edit: false,
                url: null,
                uploadedFile : null,
            }
        },
        created() {
            console.log(this.$store.state.user);
            if (!this.$store.state.user) this.$router.push("/login");
            this.document.account_id = this.$store.state.user.account_id;
            this.fetchDocumentTypes()
            this.fetchAssetModels()
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
            fetchDocumentTypes() {
                this.$http.get(`/api/documentTypes`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.documentTypes = response.data.data;
                        console.log("documentTypes", this.documentTypes);
                        if (this.edit) {
                            this.selectedDocumentType = this.documentTypes.find(x => parseInt(x.id) === parseInt(this.document.document_type_id))
                        }
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
                this.$http.get(`/api/assetModels`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.assetModels = response.data.data;
                        console.log("assetModels", this.assetModels);
                        if (this.edit) {
                            this.selectedAssetModel = this.assetModels.find(x => parseInt(x.id) === parseInt(this.document.asset_model_id))
                        }
                    }).catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push('/login')
                            }

                        }
                    });
            },
            submitForm() {
                self = this
                NProgress.start()
                if (this.edit) {
                    this.document.updated_by = this.$store.state.user.id
                    
                    this.$http
                        .put(`/api/document`, this.document,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        .then(response => {
                            NProgress.done()
                            this.$notify({
                                group: 'foo',
                                type: 'info',
                                title: 'Notification',
                                text: 'Document Information Updated'
                            })
                            this.$router.push("/documents/")
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
                    this.document.created_by = this.$store.state.user.id
                    this.$http
                        .post(`/api/document`, this.document,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        .then(response => {
                            NProgress.done()
                            this.$notify({
                                group: 'foo',
                                type: 'success',
                                title: 'Notification',
                                text: 'Document Information Saved'
                            })
                            this.$router.push("/documents/")
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
                this.$http.get(`/api/document/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.document = response.data.data;
                        this.url = this.document.image
                        console.log("document", this.document);
                        this.fetchAssetModels()
                        this.fetchDocumentTypes()
                    }).catch(error => {
                        if (error.response) {
                            console.log(error.response);
                            if (error.response.status === 401) {
                                this.$router.push('/login')
                            }
                        }
                    });
            },
            onFileChange(e) {
                let ref = this
                const file = e.target.files[0]
                this.url = URL.createObjectURL(file)
                
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = function () {
                    let base64data = reader.result
                    console.log(base64data)
                    ref.document.image = base64data
                }

                //console.log(this.url)
            },
            onSelectAssetModel(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.document.asset_model_id = selectedOption.id
                }
            },
            onSelectDocumentType(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.document.document_type_id = selectedOption.id
                }
            },
            handleFileUpload() {
                this.uploadedFile = this.$refs.file.files[0];
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
                        const input = this.$refs.file;
                        input.type = 'file';
                        this.document.file = response.data.file
                        this.submitForm()

                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response);
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
<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitClicked()">
                    <div class="card border-0">
                        <div class="form-result form-actions">
                            <router-link :to="{  path: `/assetModel/${this.inspection.asset_model_id}`}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button type="submit" style="margin-right: 10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="">Inspection Criteria</label>
                                    <multiselect v-model="selectedInspectionCriteria" :class="{ 'is-danger': !inspection.inspection_criteria_id }"
                                        :options="inspectionCriterias" @select="onSelectInspectionCriteria" track-by="id"
                                        label="name" placeholder="Select Inspection Criteria" selectLabel=""
                                        deselectLabel="" selectedLabel="Remove" v-validate="'required'">
                                    </multiselect>
                                    <a @click.prevent="addNewCompany()" href="#">Add New Manufacturer</a>
                                </div>
                                <div class="form-result col-sm-4">
                                    <b-form-fieldset label="Group">
                                        <b-form-input type="text" v-model="inspection.group" placeholder="Please enter result"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-result col-sm-4">
                                    <b-form-fieldset label="Frequency">
                                        <b-form-input type="text" v-model="inspection.frequency" placeholder="Days"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-result col-sm-6">
                                    <b-form-textarea id="textarea1" v-model="inspection.description" placeholder="Description"
                                        :rows="3" :max-rows="6">
                                    </b-form-textarea>
                                </div>
                                <div class="form-result col-sm-6">
                                    <b-form-textarea id="textarea1" v-model="inspection.comment" placeholder="Comments"
                                        :rows="3" :max-rows="6">
                                    </b-form-textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-result col-sm-6">
                                    <div id="preview">
                                        <img v-if="url" :src="url" style="height:100px;" />
                                        <img v-else src="/img/document.png" style="height:120px;">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-result">
                                    <div class="form-inline">
                                        <div class="form-result mt-4">
                                            <input aria-describedby="fileHelp" class=" btn form-control-file" id="file"
                                                ref="file" v-on:change="handleFileUpload()" type="file">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- {{inspection}} {{edit}} -->
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
                inspectionCriterias: [],
                inspection: {},
                selectedInspectionCriteria: { id: null, name: "Select Inspection Criteria" },
                edit: false,
                url: null,
                uploadedFile: null,
            }
        },
        created() {
            console.log(this.$store.state.user)
            if (!this.$store.state.user) this.$router.push("/login")
            this.inspection.account_id = this.$store.state.user.account_id
            this.inspection.asset_model_id = this.$route.params.id
            if (this.$route.path.indexOf("create") > -1) {
                console.log("show result", this.$route.path.indexOf("create"))
                this.edit = false
                this.fetchInspectionCriteria()
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
                self = this
                if (this.edit) {
                    this.inspection.updated_by = this.$store.state.user.id

                    this.$http
                        .put(`/api/inspection`, this.inspection,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        .then(response => {
                            //alert("Inspection Updated")
                            self.$router.push({ path: `/assetModel/${self.inspection.asset_model_id}` });
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
                    console.log(this.formData)
                    this.inspection.created_by = this.$store.state.user.id;
                    this.$http
                        .post(`/api/inspection`, this.inspection,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        .then(response => {
                            //alert("Inspection Added")
                            self.$router.push({ path: `/assetModel/${self.inspection.asset_model_id}` });
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
                this.$http.get(`/api/inspection/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.inspection = response.data.data
                        this.url = this.inspection.image
                        this.fetchInspectionCriteria()
                        console.log("inspection", this.inspection)
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
                reader.readAsDataURL(file)
                reader.onloadend = function () {
                    let base64data = reader.result
                    console.log(base64data)
                    ref.inspection.image = base64data
                }

                //console.log(this.url)
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
                        this.inspection.file = response.data.file
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
            submitClicked() {
                if (this.uploadedFile) {
                    this.submitFile()
                }
                else {
                    this.submitForm()
                }
            },
            fetchInspectionCriteria() {
                this.$http
                    .get(`/api/inspectionCriterias`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.inspectionCriterias = response.data.data
                        console.log("inspectionCriterias", this.inspectionCriterias)
                        if (this.edit) {
                            this.selectedInspectionCriteria = this.inspectionCriterias.find(
                                x => parseInt(x.id) === parseInt(this.inspection.inspection_criteria_id)
                            )
                        }
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push("/login")
                            }
                        }
                    })
            },
            onSelectInspectionCriteria(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.inspection.inspection_criteria_id = selectedOption.id
                }
            },

        }
    }
</script>
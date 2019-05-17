<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitFile()">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                            <router-link :to="{ path: '/assets'}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button type="submit" style="margin-right: 10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                        </div>
                        <div class="card-body">
                            <!-- <div class="row">
                                <div class="form-group col-sm-6">     
                                    <div id="preview">
                                        <img v-if="url" :src="url" style="height:100px;"  />
                                        <img v-else src="/img/document.png" style="height:120px;" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <b-form-file @change="onFileChange" placeholder="Choose a file..."></b-form-file>
                                </div>
                            </div> -->
                            <div class="row">
                                <div class="form-group">
                                    <div class="form-inline">
                                        <div class="form-group mt-4">
                                            <input aria-describedby="fileHelp"  class=" btn form-control-file" id="file" ref="file" v-on:change="handleFileUpload()" type="file" accept=".xls, .xlsx">
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
                uploadedFile : null,
                asset : {}
            }
        },
        created() {},
        methods: {
            handleFileUpload() {
                this.uploadedFile = this.$refs.file.files[0];
                console.log(this.uploadedFile)
            },
            submitFile() {
                if(!this.uploadedFile){
                    alert('Please select a file')
                    return
                }
                    
                let formData = new FormData()
                formData.append('file', this.uploadedFile)
                console.log(formData)

                NProgress.start()
                this.$http.post('/api/company-upload-file',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                             Authorization: `Bearer ${this.$store.state.token}`
                        }
                    }
                )
                .then(response => {
                        alert("File Uploaded")
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
                            if (error.response.status === 401) {
                                this.$router.push('/login')
                            }
                        }
                    })
            },
        }
    }
</script>
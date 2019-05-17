<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off'>
                    <div class="card border-0">
                        <div class="form-group form-actions">
                            <router-link :to="{ path: `/job/${this.gallery.job_id}`}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button type="button" @click.prevent="submit()" style="margin-right:10px"
                                class="btn btn-sm btn-primary pull-right">Submit</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Image Description">
                                        <b-form-input type="text" v-model="gallery.description"
                                            :class="errors.has('name') ? 'form-control is-invalid':'form-control'"
                                            v-validate="'required'" name="name"
                                            placeholder="Please Enter Image Description">
                                        </b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="form-group col-sm-6">
                                    <div id="preview">
                                        <img v-if="url" :src="url" style="height:100px;" />
                                        <img v-else src="/img/gallery.png" style="height:120px;">
                                    </div>
                                </div>
                            </div> -->
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <!-- <b-form-file @change="onFileChange" placeholder="Choose a file..."></b-form-file> -->
                                    <vue-upload-multiple-image @upload-success="uploadImageSuccess" 
                                        @before-remove="beforeRemove" @edit-image="editImage" @data-change="dataChange"
                                        :data-images="images" :dragText=BROWSESRTEXT :browseText=FILEUPLOAD
                                        :maxImage=MAX_UPLOAD :showPrimary=false ref='multiImage'>
                                    </vue-upload-multiple-image>
                                </div>
                            </div>
                        </div>
                        <!-- {{gallery}} {{edit}} -->
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

    #my-strictly-unique-vue-upload-multiple-image {
        font-family: 'Avenir', Helvetica, Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
        margin-top: 60px;
    }

    h1,
    h2 {
        font-weight: normal;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    li {
        display: inline-block;
        margin: 0 10px;
    }

    a {
        color: #42b983;
    }
</style>
<script>
    import { Datetime } from "vue-datetime"
    import VueUploadMultipleImage from 'vue-upload-multiple-image'
    export default {
        name: "forms",
        components: {
            VueUploadMultipleImage,
        },
        data() {
            return {
                gallery: {
                    image: [],
                    date: new Date().toISOString()
                },
                edit: false,
                url: null,
                images: [],
                imageUploaded: null,
                MAX_UPLOAD: 5,
                FILEUPLOAD: 'Click Here To Upload',
                BROWSESRTEXT: 'Drag & Drop Files'
            };
        },
        created() {
            console.log(this.$store.state.user)

            if (!this.$store.state.user) this.$router.push("/login")
            this.gallery.account_id = this.$store.state.user.account_id
            this.gallery.job_id = this.$route.params.id

            if (this.$route.path.indexOf("create") > -1) {
                console.log("show description", this.$route.path.indexOf("create"))
                this.edit = false
            } else {
                this.edit = true
                this.fetchById(this.$route.params.id)
            }
        },
        methods: {
            submit() {
                this.$validator.validate().then(description => {
                    if (description) this.submitForm()
                });
            },

            submitForm() {
                self = this;
                if (Array.isArray(this.gallery.image)) {
                    if (this.gallery.image.length > this.MAX_UPLOAD) {
                        alert('Max image limit  exceeded!')
                        return;
                    }
                }
                if (this.edit) {
                    this.gallery.updated_by = this.$store.state.user.id;
                    this.$http
                        .put(`/api/gallery`, this.gallery, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            this.$router.push({ path: `/job/${this.gallery.job_id}` })
                        })
                        .catch(error => {
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push("/login");
                                }
                            }
                        });
                } else {
                    this.gallery.created_by = this.$store.state.user.id
                    this.$http
                        .post(`/api/gallery`, this.gallery, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            this.$router.push({ path: `/job/${this.gallery.job_id}` })
                        })
                        .catch(error => {
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push("/login")
                                }
                            }
                        });
                }
            },

            fetchById(id) {
                this.$http
                    .get(`/api/gallery/${id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.gallery = response.data.data
                        let images = this.gallery.image.split(',')

                        images.forEach(element => {
                            this.images.push({
                                path: element,
                                default: 1,
                                highlight: 1,
                                caption: 'caption to display. receive',
                            })
                        })

                        console.log(this.images)
                        console.log("gallery", this.gallery)
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push("/login");
                            }
                        }
                    });
            },

            onFileChange(e) {
                let ref = this;
                const file = e.target.files[0]
                this.url = URL.createObjectURL(file)
                var reader = new FileReader()
                reader.readAsDataURL(file)
                reader.onloadend = function () {
                    let base64data = reader.result
                    console.log(base64data)
                    ref.gallery.image = base64data
                }

            },

            uploadImageSuccess(formData, index, fileList) {
                //console.log(fileList)
                // this.gallery.image = fileList
                // this.gallery.image = fileList.map(file => {
                //     var temp = Object.assign({}, file)
                //     temp.path = this.resizeImage(file.path)
                //     return temp
                // })
                console.log(this.$refs.multiImage)
                this.getBase64FromImageUrl(fileList)

                if (fileList.length > this.MAX_UPLOAD) {
                    alert('Max image limit  exceeded!')
                }
            },

            beforeRemove(index, done, fileList) {
                console.log('index', index, fileList)
                var r = confirm("remove image")
                if (r == true) {
                    done()
                    let images = []
                    // fileList.forEach(element => {
                    //     images.push(element.path)
                    // })
                    // this.gallery.image = images.join()
                    this.getBase64FromImageUrl(fileList)
                }
                else {
                }
            },

            editImage(formData, index, fileList) {
                console.log('edit data', formData, index, fileList)
            },

            dataChange(data) {
                console.log(data)
            },
            resizeImage(base64) {
                var maxW = 1280
                var maxH = 720
                var canvas = document.createElement("canvas")
                var ctx = canvas.getContext("2d")
                var cw = canvas.width
                var ch = canvas.height

                var img = new Image
                img.src = base64
                var iw = img.width
                var ih = img.height
                var scale = Math.min((maxW / iw), (maxH / ih))
                var iwScaled = iw * scale
                var ihScaled = ih * scale
                canvas.width = iwScaled
                canvas.height = ihScaled
                ctx.drawImage(img, 0, 0, iwScaled, ihScaled)

                return canvas.toDataURL()
            },
            getBase64FromImageUrl(fileList) {
                let ref = this
                this.gallery.image = []

                var maxW = 600
                var maxH = 300
                
                fileList.forEach(element => {
                    
                    var img = new Image()
                    console.log(element.name)

                    img.onload = function () {
                        var iw = this.width
                        var ih = this.height    
                        var scale = Math.min((maxW / iw), (maxH / ih))
                        var iwScaled = iw * scale
                        var ihScaled = ih * scale

                        var canvas = document.createElement("canvas")
                        canvas.width = iwScaled
                        canvas.height = ihScaled
                        
                        var ctx = canvas.getContext("2d")
                        ctx.drawImage(this, 0, 0, iwScaled, ihScaled)
                        var dataURL = canvas.toDataURL("image/png")
                        ref.gallery.image.push(dataURL)
                        console.log(ref.gallery.image.length)
                    }

                    img.src = element.path
                })
                //img.setAttribute('crossOrigin', 'anonymous')
                

               
            }
        },
    };
</script>
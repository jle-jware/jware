<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm()">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                            <router-link :to="{ path: `/company/${this.location.company_id}`}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button type="button" @click.prevent="submit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                            <!-- <button type="button" @click.prevent="submit" class="btn btn-block btn-success">Create Account</button> -->
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label style="font-size: 20px">{{company.name}} - Location Details</label>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <b-form-fieldset label="Location Description">
                                        <b-form-input type="text" :class="errors.has('description') ? 'form-control is-invalid':'form-control'"
                                            v-validate="'required'" name="description" v-model="location.description"
                                            placeholder="Enter Location Description"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-3">
                                    <b-form-fieldset label="Location Address">
                                        <!-- <b-form-input type="text" v-model="company.address" placeholder="Enter Your address"></b-form-input> -->
                                        <vue-google-autocomplete ref="address" id="map" classname="form-control"
                                            :placeholder="location.address" v-on:placechanged="getAddressData" country="nz">
                                        </vue-google-autocomplete>
                                    </b-form-fieldset>
                                </div>
                                <!-- <div class="form-group col-sm-3">
                                    <label for="">Distributor *</label>
                                    <multiselect :disabled="checkCompanyType" v-model="selectedDistributor" v-validate="'required'" 
                                        :class="{ 'is-danger': !location.agent_id && !checkCompanyType }" :options="distributors" @select="onSelectDistributor"
                                        track-by="id" label="name" placeholder="Select Distributor" selectLabel=""
                                        deselectLabel="" selectedLabel="Remove">
                                    </multiselect>
                                </div> -->
                                <!-- <div class="form-group col-sm-3">
                                    <label for="">Region</label>
                                    <multiselect v-model="selectedRegions" :options="regions"  v-validate="'required'" :class="{ 'is-danger': !location.region_id }" @select="onSelectRegion"
                                        track-by="id" label="name" placeholder="Select Region" selectLabel=""
                                        deselectLabel="" selectedLabel="Remove">
                                    </multiselect>
                                </div> -->
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <!-- <input type="checkbox" placeholder="Is User" class="form-control" v-model="user.is_user" value="1" name="is_user"> -->
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" v-model="location.is_primary" value="Yes" name="is_primary"
                                            class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Primary Location For Company</span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <!-- <b-form-fieldset label="Location Phone Number">
                                        <vue-tel-input v-model="location.phone_number" @onInput="onInput"
                                            :preferredCountries="['us', 'gb', 'ua']"></vue-tel-input>
                                    </b-form-fieldset> -->
                                    <b-form-fieldset label="Phone Number">
                                        <b-form-input type="text" name="phone_number" v-model="location.phone_number" placeholder="Enter Phone Number"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="">Key Contact</label>
                                    <multiselect v-model="selectedUser" :options="users" @select="onSelectUser"
                                        track-by="id" label="full_name" placeholder="Select Key Contact" selectLabel=""
                                        deselectLabel="" selectedLabel="Remove">
                                    </multiselect>
                                    <a @click.prevent="addNewUser()" href="#">Add Contact</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <b-modal ref="userModal" size="lg" id="modal-center" @ok="submitUser()" centered title="Add New Contact">
            <div class="row">
                <div class="col-sm-12">
                   <form>
                    <div class="card border-0">
                        <div class="card-body">
                            <div class="form-group">
                                <!-- <label style="font-size: 20px" >{{company.name}} - Contact</label> -->
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="First Name *">
                                        <b-form-input type="text" :class="errors.has('first_name') ? 'form-control is-invalid':'form-control'"
                                            name="first_name" v-model="user.first_name" placeholder="Enter first name"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Last Name *">
                                        <b-form-input type="text" :class="errors.has('last_name') ? 'form-control is-invalid':'form-control'"
                                            name="last_name" v-model="user.last_name" placeholder="Enter last name"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <b-form-fieldset label="Email *">
                                        <b-form-input type="text" :class="errors.has('email') ? 'form-control is-invalid':'form-control'" 
                                            name="email" v-model="user.email" placeholder="Enter Email Address"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-4">
                                    <!-- <b-form-fieldset label="Phone Number">
                                    <vue-tel-input v-model="user.phone_number" @onInput="onInput"  :preferredCountries="['us', 'gb', 'ua']"></vue-tel-input>
                                    </b-form-fieldset> -->
                                    <b-form-fieldset label="Phone Number">
                                        <b-form-input type="text" name="phone_number" v-model="user.phone_number" placeholder="Enter Phone Number"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="">Contact Type *</label>
                                    <multiselect v-model="selectedContactType" :class="{ 'is-danger': !user.contact_type }" :options="roleTypes" @select="onSelectContactType" track-by="id" label="name" placeholder="Select Contact Type"
                                        selectLabel="" deselectLabel="" selectedLabel="Remove" >
                                    </multiselect>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <!-- <input type="checkbox" placeholder="Is User" class="form-control" v-model="user.is_user" value="1" name="is_user"> -->
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" v-model="user.is_user" value="1" name="is_user" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Is user?</span>
                                    </label>
                                </div>
                                <div class="form-group col-sm-3">
                                    <!-- <input type="checkbox" placeholder="Key Contact?" class="form-control" v-model="user.key_contact" value="1" name="key_contact"> -->
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" v-model="user.key_contact" value="1" name="key_contact" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">key Contact?</span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="User Name *" v-if="user.is_user==true">
                                        <b-form-input type="text" :class="errors.has('username') ? 'form-control is-invalid':'form-control'"
                                            name="username" v-model="user.username" placeholder="Enter user name"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                 <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Password *" v-if="user.is_user==true">
                                        <b-form-input type="password" :class="errors.has('password') ? 'form-control is-invalid':'form-control'"
                                            name="password" v-model="user.password" placeholder="Enter new password"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <!-- <div class="form-group col-sm-6">
                                    <a @click.prevent="addLocation()" href="#">Add Location</a>
                                </div> -->
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">     
                                    <div id="preview">
                                        <img v-if="url" :src="url" style="height:100px;"  />
                                        <img v-else src="/img/user.png" style="height:120px;" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <b-form-file @change="onFileChange" placeholder="Choose a file..."></b-form-file>
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
    import Multiselect from "vue-multiselect"
    import VueGoogleAutocomplete from 'vue-google-autocomplete'
    export default {
        name: "forms",
        components: { Multiselect, VueGoogleAutocomplete },
        data() {
            return {
                distributors:[],
                regions:[],
                users: [],
                roleTypes:[],
                location: {},
                user:{
                    key_contact: 0,
                    is_user: 0,
                    contact_type:null
                },
                edit: false,
                selectedContactType: { id: null, name: 'Select Contact Type' },
                selectedDistributor: { id: null, name: "Select Distributor" },
                selectedRegions:{ id: null, name: "Select Region" },
                selectedUser: { id: null, user_name: 'Select Key Contact' },
                company: {},
                address: '',
                url: null,
            };
        },
        mounted() {
            // To demonstrate functionality of exposed component functions
            // Here we make focus on the user input
            this.$refs.address.focus();
        },
        created() {
            if (this.$route.path.indexOf("create") > -1) {
                console.log("Create Location", this.$route.params.id)
                this.location.company_id = this.$route.params.id
                this.user.company_id = this.$route.params.id
                this.location.account_id = this.$store.state.user.account_id
                this.edit = false
                this.fetchDistributors()
                this.fetchRoleTypes()
                this.fetchRegions()
                this.fetchCompanyWiseUsers(this.location.company_id)
                this.fetchCompanyById(this.location.company_id)
            }
            else {
                this.edit = true
                this.fetchById(this.$route.params.id)
                this.placeChanged()
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
                    this.location.updated_by = this.$store.state.user.id
                    this.$http
                        .put(`/api/location`, this.location, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            //alert("Location Information updated")
                            this.$router.push({ path: `/company/${self.location.company_id}`, query: { flag: 'location' } })
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
                    this.location.created_by = this.$store.state.user.id
                    this.$http
                        .post(`/api/location`, this.location, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            //alert("Location Information Added");
                            this.$router.push({ path: `/company/${self.location.company_id}`, query: { flag: 'location' } })
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
                this.$http.get(`/api/location/${id}`, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                    .then(response => {
                        this.location = response.data.data
                        console.log("location", this.location)
                        this.fetchRegions(this.location.region_id)
                        this.fetchDistributors(this.location.agent_id);
                        this.fetchCompanyWiseUsers(this.location.company_id)
                        this.fetchCompanyById(this.location.company_id)
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
            fetchCompanyWiseUsers(id) {
                this.$http.get(`/api/company/${this.location.company_id}/users`, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                    .then(response => {
                        this.users = response.data.data;
                        console.log("users", this.users);
                        this.selectedUser = this.users.find(x => parseInt(x.id) === parseInt(this.location.user_id))
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
            fetchCompanyById(id) {
                this.$http.get(`/api/company/${id}`, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                    .then(response => {
                        this.company = response.data.data;
                        console.log("company", this.company);
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
            onSelectUser(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.location.user_id = selectedOption.id
                }
            },
            /**
            * When the location found
            * @param {Object} addressData Data of the found location
            * @param {Object} placeResultData PlaceResult object
            * @param {String} id Input container ID
            */
            // getAddressData: function (addressData, placeResultData, id) {
            //     this.address = addressData;
            // },
            placeChanged() {
                // this.$refs.address.update()
            },
            getAddressData(addressData, placeResultData, id) {
                this.location.address = `${addressData.street_number}, ${addressData.route},${addressData.locality},${addressData.administrative_area_level_1}, ${addressData.postal_code}, ${addressData.country}`
            },
            onInput({ number, isValid, country }) {
                console.log(number, isValid, country);
            },
            addNewUser() {
                this.$refs.userModal.show()
            },
            submitUser(){
                this.user.created_by = this.$store.state.user.id
                this.user.account_id = this.$store.state.user.account_id

                this.$http.post(`/api/user`, this.user, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                .then(response => {
                    //alert("New Contact Added")
                    this.fetchCompanyWiseUsers(this.location.company_id)
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
            onSelectContactType(selectedOption, name) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.user.contact_type = selectedOption.name
                }
            },
            onSelectDistributor(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.name)
                    this.location.agent_id = selectedOption.name
                }
            },
            onSelectRegion(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.location.region_id = selectedOption.id
                }
            },
            fetchDistributors() {
                NProgress.start();
                this.$http
                    .get(`/api/distributors`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.distributors = response.data.data;
                        // this.selectedDistributor = this.distributors.find(
                        //     x => parseInt(x.name) === parseInt(this.location.agent_id)
                        // );
                         this.selectedDistributor = this.distributors.find(x => (x.name) === (this.location.agent_id))
                        console.log("distributors", this.distributors);
                        NProgress.done();
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response);
                            if (error.response.status === 401) {
                                this.$router.push("/login");
                            }
                        }
                    });
            },
            fetchRegions() {
                NProgress.start();
                this.$http
                    .get(`/api/regions`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.regions = response.data.data
                        // this.selectedDistributor = this.distributors.find(
                        //     x => parseInt(x.name) === parseInt(this.location.agent_id)
                        // );
                         this.selectedRegions = this.regions.find(x => parseInt(x.id) === parseInt(this.location.region_id))
                        console.log("regions", this.regions)
                        NProgress.done()
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
            onFileChange(e) {
                let ref = this
                const file = e.target.files[0]
                this.url = URL.createObjectURL(file)
                
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = function () {
                    let base64data = reader.result
                    console.log(base64data)
                    ref.user.image = base64data
                }

                //console.log(this.url)
            },
            fetchRoleTypes() {
                NProgress.start()
                this.$http
                    .get(`/api/roleTypes`,
                        {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                    .then(response => {
                        this.roleTypes = response.data.data;
                        console.log("roleTypes", this.roleTypes)
                        if(this.edit){
                            this.selectedContactType =  this.roleTypes.find(x => (x.name) === (this.user.contact_type))
                        }
                        NProgress.done()
                    })
            },
        },
        computed:{
            checkCompanyType(){
                if(this.company.type == 'Contractor'){
                    this.selectedDistributor
                    return true
                }
            }
        }
    };
</script>
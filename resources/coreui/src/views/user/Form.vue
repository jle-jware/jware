<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form>
                    <div class="card border-0">
                        <div class="form-group form-actions">
                            <router-link :to="{ name: 'Contact List'}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button type="button" @click.prevent="submit()" ref="btnSubmit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="First Name *">
                                        <b-form-input type="text" :class="errors.has('first_name') ? 'form-control is-invalid':'form-control'"
                                            v-validate="'required'" name="first_name" v-model="user.first_name"
                                            placeholder="Enter first name">
                                        </b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Last Name *">
                                        <b-form-input type="text" :class="errors.has('last_name') ? 'form-control is-invalid':'form-control'"
                                            v-validate="'required'" name="last_name" v-model="user.last_name"
                                            placeholder="Enter last name">
                                        </b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <b-form-fieldset label="Email">
                                        <b-form-input type="email" name="email" v-model="user.email" placeholder="Enter Email Address"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-4">
                                    <!-- <b-form-fieldset label="Phone Number">
                                        <vue-tel-input v-model="user.phone_number" @onInput="onInput"
                                            :preferredCountries="['us', 'gb', 'ua']"></vue-tel-input>
                                    </b-form-fieldset> -->
                                    <b-form-fieldset label="Phone Number">
                                        <b-form-input type="text" name="phone_number" v-model="user.phone_number" placeholder="Enter Phone Number"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="">Contact Type *</label>
                                    <multiselect v-model="selectedContactType" :class="{ 'is-danger': !user.contact_type }"
                                        :options="roleTypes" @select="onSelectContactType" track-by="id" label="name"
                                        placeholder="Select Contact Type" selectLabel="" deselectLabel="" selectedLabel="Remove"
                                        v-validate="'required'">
                                    </multiselect>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="">Select Company *</label>
                                    <multiselect v-model="selectedCompany" :class="{ 'is-danger': !user.company_id }"
                                        :options="companies" @select="onSelectCompany" track-by="id" label="name"
                                        placeholder="Select Company" selectLabel="" deselectLabel="" selectedLabel="Remove"
                                        v-validate="'required'">
                                    </multiselect>
                                    <a @click.prevent="addCompany()" href="#">Add New Company</a>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="">Select Location *</label>
                                    <multiselect v-model="selectedLocation" :options="locations" @select="onSelectLocation"
                                        track-by="id" label="description" placeholder="Select Location" selectLabel=""
                                        deselectLabel="" selectedLabel="Remove" v-validate="''">
                                    </multiselect>
                                    <a @click.prevent="addLocation()" href="#">Add New Location</a>
                                </div>

                            </div>
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <!-- <input type="checkbox" placeholder="Is User" class="form-control" v-model="user.is_user" value="1" name="is_user"> -->
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" v-model="user.is_user" value="1" name="is_user" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Jware user?</span>
                                    </label>
                                </div>
                                <div class="form-group col-sm-3">
                                    <!-- <input type="checkbox" placeholder="Key Contact?" class="form-control" v-model="user.key_contact" value="1" name="key_contact"> -->
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" v-model="user.key_contact" value="1" name="key_contact"
                                            class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">key Contact</span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <!-- <div class="form-group col-sm-6">
                                    <b-form-fieldset label="User Name *" v-if="user.is_user==true">
                                        <b-form-input type="text" :class="errors.has('username') ? 'form-control is-invalid':'form-control'"
                                            v-validate="'required'" name="username" v-model="user.username" placeholder="Enter user name">
                                        </b-form-input>
                                    </b-form-fieldset>
                                </div> -->
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Password *" v-if="user.is_user==true">
                                        <b-form-input type="password" :class="errors.has('password') ? 'form-control is-invalid':'form-control'"
                                            v-validate="'required'" name="password" v-model="user.password" placeholder="Enter new password"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <!-- <div class="form-group col-sm-6">
                                    <a @click.prevent="addLocation()" href="#">Add Location</a>
                                </div> -->
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <div id="preview">
                                        <img v-if="url" :src="url" style="height:100px;" />
                                        <img v-else src="/img/user.png" style="height:120px;">
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
        <b-modal ref="locationModal" size="lg" id="modal-center" @ok="submitLocation()" centered title="Add Location Information">
            <div class="row">
                <div class="form-group col-sm-3">
                    <b-form-fieldset label="Location Description">
                        <b-form-input type="text" :class="errors.has('description') ? 'form-control is-invalid':'form-control'"
                            name="description" v-model="location.description" placeholder="Enter Location Description"></b-form-input>
                    </b-form-fieldset>
                </div>
                <div class="form-group col-sm-3">
                    <b-form-fieldset label="Location Address">
                        <!-- <b-form-input type="text" v-model="company.address" placeholder="Enter Your address"></b-form-input> -->
                        <vue-google-autocomplete ref="address" id="map" classname="form-control" :placeholder="location.address"
                            v-on:placechanged="getAddressData" country="nz">
                        </vue-google-autocomplete>
                    </b-form-fieldset>
                </div>
                <div class="form-group col-sm-3">
                    <label for="">Distributor *</label>
                    <multiselect v-model="selectedDistributor" :options="distributors" @select="onSelectDistributor"
                        track-by="id" label="name" placeholder="Select Distributor" selectLabel="" deselectLabel=""
                        selectedLabel="Remove">
                    </multiselect>
                </div>
                <div class="form-group col-sm-3">
                    <label for="">Region</label>
                    <multiselect v-model="selectedRegions" :options="regions" @select="onSelectRegion" track-by="id"
                        label="name" placeholder="Select Region" selectLabel="" deselectLabel="" selectedLabel="Remove">
                    </multiselect>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-3">
                    <!-- <input type="checkbox" placeholder="Is User" class="form-control" v-model="user.is_user" value="1" name="is_user"> -->
                    <label class="custom-control custom-checkbox">
                        <input type="checkbox" v-model="location.is_primary" value="Yes" name="is_primary" class="custom-control-input">
                        <span class="custom-control-indicator"></span>
                        <span class="custom-control-description">Is Primary Location For This Company?</span>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-sm-6">
                    <!-- <b-form-fieldset label="Location Phone Number">
                        <vue-tel-input v-model="location.phone_number" @onInput="onInput" :preferredCountries="['us', 'gb', 'ua']"></vue-tel-input>
                    </b-form-fieldset> -->
                    <b-form-fieldset label="Phone Number">
                        <b-form-input type="text" name="phone_number" v-model="location.phone_number" placeholder="Enter Phone Number"></b-form-input>
                    </b-form-fieldset>
                </div>
            </div>
        </b-modal>
        <b-modal ref="companyModal" size="lg" id="modal-center" @ok="submitCompany()" centered title="Add New Company">
            <div class="row">
                <div class="col-sm-12">
                    <form>
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <b-form-fieldset label="Company Name *">
                                            <b-form-input type="text" :class="errors.has('name') ? 'form-control is-invalid':'form-control'"
                                                name="name" v-model="company.name" placeholder="Please enter company name"></b-form-input>
                                        </b-form-fieldset>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <b-form-fieldset label="Client No *">
                                            <b-form-input type="text" v-model="company.client_no" placeholder="Please enter client number"></b-form-input>
                                        </b-form-fieldset>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label for="">Company Type *</label>
                                        <multiselect v-model="selectedCompanyType" :class="{ 'is-danger': !company.type }"
                                            :options="companyTypes" @select="onSelectCompanyType" track-by="id" label="type"
                                            placeholder="Company Type" selectLabel="" deselectLabel="" selectedLabel="Remove">
                                        </multiselect>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <b-form-fieldset label="Website">
                                            <b-form-input type="text" :class="errors.has('website') ? 'form-control is-invalid':'form-control'"
                                                v-model="company.website" placeholder="http://www.website.com" name="website"
                                                v-validate="'url:{require_protocol?}'"></b-form-input>
                                        </b-form-fieldset>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <b-form-fieldset label="Phone Number">
                                            <vue-tel-input v-model="company.phone_number" @onInput="onInput"
                                                :preferredCountries="['us', 'gb', 'ua']"></vue-tel-input>
                                        </b-form-fieldset>
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
    import VueGoogleAutocomplete from 'vue-google-autocomplete'
    export default {
        name: "forms",
        components: {
            Multiselect, VueGoogleAutocomplete
        },
        data() {
            return {
                roleTypes: [],
                companies: [],
                distributors:[],
                regions:[],
                locations: [],
                user: {
                    key_contact: 0,
                    is_user: 0,
                    contact_type: null
                },
                companyTypes: [],
                company: {
                    is_enable: 1,
                    type: null
                },
                edit: false,
                url: null,
                phone: '',
                selectedCompanyType: { id: null, type: 'Company Type' },
                selectedCompany: { id: null, name: 'Select Company' },
                selectedLocation: { id: null, address: 'Select Location' },
                selectedContactType: { id: null, name: 'Select Contact Type' },
                selectedDistributor: { id: null, name: "Select Distributor" },
                selectedRegions:{ id: null, name: "Select Region" },
                location: {}
            };
        },
        created() {
            console.log(this.$store.state.user)
            if (!this.$store.state.user) this.$router.push("/login")
            this.user.account_id = this.$store.state.user.account_id


            if (this.$route.path.indexOf('create') > -1) {
                this.fetchCompany()
                this.fetchRoleTypes()
                this.fetchCompanyType()
                this.fetchRegions()
                this.fetchDistributors()
            } else {
                this.edit = true;
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
            fetchCompany() {
                NProgress.start()
                this.$http
                    .get(`/api/companies`,
                        {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                    .then(response => {
                        this.companies = response.data.data;
                        console.log("companies", this.companies)
                        if (this.edit) {
                            this.selectedCompany = this.companies.find(x => parseInt(x.id) === parseInt(this.user.company_id))
                            this.fetchLocationByCompany(this.user.company_id)
                        }
                        NProgress.done()
                    })
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
                         this.selectedRegions = this.regions.find(x => parseInt(x.id) === parseInt(this.location.agent_id))
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
            fetchDistributors() {
                NProgress.start();
                this.$http
                    .get(`/api/distributors`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.distributors = response.data.data
                        // this.selectedDistributor = this.distributors.find(
                        //     x => parseInt(x.name) === parseInt(this.location.agent_id)
                        // );
                         this.selectedDistributor = this.distributors.find(x => (x.name) === (this.location.agent_id))
                        console.log("distributors", this.distributors)
                        NProgress.done();
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push("/login");
                            }
                        }
                    })
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
                        if (this.edit) {
                            this.selectedContactType = this.roleTypes.find(x => (x.name) === (this.user.contact_type))
                        }
                        NProgress.done()
                    })
            },
            submitForm() {
                this.$refs.btnSubmit.disabled = true
                self = this
                NProgress.start()
                if (this.edit) {
                    this.user.updated_by = this.$store.state.user.id
                    this.user.account_id = this.$store.state.user.account.id
                    this.$http
                        .put(`/api/user`, this.user,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        .then(response => {
                             this.$refs.btnSubmit.disabled = false
                                NProgress.done()
                                this.$notify({
                                group: 'foo',
                                type: 'info',
                                title: 'Notification',
                                text: 'User Information Updated'
                            })
                            this.$router.push("/users/")
                        })
                        .catch(error => {
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push('/login')
                                }
                                this.$refs.btnSubmit.disabled = false
                            }
                        })
                } else {
                    this.user.created_by = this.$store.state.user.id
                    this.user.account_id = this.$store.state.user.account.id
                    this.$http
                        .post(`/api/user`, this.user,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        .then(response => {
                                this.$refs.btnSubmit.disabled = false
                                NProgress.done()
                                this.$notify({
                                group: 'foo',
                                type: 'success',
                                title: 'Notification',
                                text: 'User Information Saved'
                            })
                            this.$router.push("/users/")
                        })
                        .catch(error => {
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push('/login')
                                }
                                this.$refs.btnSubmit.disabled = false
                            }
                        })
                }
            },
            fetchById(id) {
                NProgress.start()
                this.$http.get(`/api/user/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.user = response.data.data;
                        console.log('User', this.user)
                        console.log("company id", this.user.company_id)
                        this.url = this.user.image
                        this.fetchCompany()
                        this.fetchRoleTypes()
                        this.fetchRegions()
                        this.fetchDistributors()
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
            onInput({ number, isValid, country }) {
                console.log(number, isValid, country);
            },
            fetchLocationByCompany(id) {
                NProgress.start()
                this.$http
                    .get(`/api/company/${id}/locations`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.locations = response.data.data
                        console.log("locations", this.locations)

                        if (this.edit) {
                            this.selectedLocation = this.locations.find(x => parseInt(x.id) === parseInt(this.user.location_id))
                        }
                        NProgress.done()
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response);
                            if (error.response.status === 401) {
                                window.location.href = "/login"
                            }
                        }
                    })
            },
            onSelectCompany(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.user.company_id = selectedOption.id
                    this.fetchLocationByCompany(selectedOption.id)
                    this.location.company_id = selectedOption.id
                }
            },
            onSelectLocation(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.user.location_id = selectedOption.id
                }
            },
            onSelectRegion(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.location.region_id = selectedOption.id
                }
            },
            onSelectDistributor(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.location.agent_id = selectedOption.name
                }
            },
            onSelectContactType(selectedOption, name) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.user.contact_type = selectedOption.name
                }
            },
            addLocation() {
                this.$refs.locationModal.show()
            },
            addCompany() {
                this.$refs.companyModal.show()
            },
            getAddressData(addressData, placeResultData, id) {
                this.location.address = `${addressData.street_number}, ${addressData.route}, ${addressData.country}, ${addressData.postal_code}`
            },
            submitLocation() {
                this.location.created_by = this.$store.state.user.id
                this.location.account_id = this.$store.state.user.account_id
                this.location.user_id = this.user.id
                this.location.company_id = this.user.company_id

                this.$http.post(`/api/location`, this.location, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                    .then(response => {
                        //alert("Location Information Added")
                        this.fetchLocationByCompany(this.location.company_id)
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
            submitCompany() {
                this.company.created_by = this.$store.state.user.id
                this.company.account_id = this.$store.state.user.account_id

                this.$http.post(`/api/company`, this.company, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                    .then(response => {
                        //alert("Company Added")
                        this.fetchCompany()
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
            onSelectCompanyType(selectedOption, type) {
                if (selectedOption) {
                    console.log(selectedOption.type)
                    this.company.type = selectedOption.type
                }
            },
            fetchCompanyType() {
                this.$http.get(`/api/companyTypes`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.companyTypes = response.data.data
                        console.log("company Types", this.companyTypes)
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
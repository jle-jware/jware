<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm()">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                            <div class="form-group form-actions">
                                <router-link :to="{ name: 'Company List'}">
                                    <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                                </router-link>
                                <button type="button" ref="btnSubmit" @click.prevent="submit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <b-form-fieldset label="Company Name *">
                                        <b-form-input type="text" :class="errors.has('name') ? 'form-control is-invalid':'form-control'" v-validate="'required'"
                                            name="name" v-model="company.name" placeholder="Please enter company name"></b-form-input>
                                            <span class="is-danger">{{ errors.first('name') }}</span>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-4">
                                    <b-form-fieldset label="Client No">
                                        <b-form-input type="text" v-model="company.client_no" placeholder="Please enter client number"></b-form-input>
                                    </b-form-fieldset>
                                </div>

                                <div class="form-group col-sm-4">
                                    <label for="">Company Type *</label>
                                    <multiselect v-model="selectedCompanyType" :class="{ 'is-danger': !company.type }"  :options="companyTypes" @select="onSelectCompanyType" track-by="id" label="type" placeholder="Company Type"
                                        selectLabel="" deselectLabel="" selectedLabel="Remove" v-validate="'required'" >
                                    </multiselect>
                                     <b-form-fieldset label="Company Type" v-if="company.new_company==true">
                                        <b-form-input type="text"  v-model="company.type" placeholder="Enter Company Type"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Website">
                                        <!-- <b-form-input type="text" :class="errors.has('website') ? 'form-control is-invalid':'form-control'" v-model="company.website"
                                            placeholder="http://www.website.com" name="website" v-validate="'url:{require_protocol?}'"></b-form-input> -->
                                            <b-form-input type="text"  v-model="company.website" placeholder="http://www.website.com" name="website">
                                            </b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-6">
                                    <!-- <b-form-fieldset label="Phone Number">
                                        <vue-tel-input v-model="company.phone_number" @onInput="onInput"></vue-tel-input>
                                    </b-form-fieldset> -->
                                    <b-form-fieldset label="Phone Number">
                                        <b-form-input type="text" name="phone_number" v-model="company.phone_number" placeholder="Enter Phone Number"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <div id="preview">
                                        <img v-if="url" :src="url" style="height:100px;" />
                                        <img v-else src="/img/company.png" style="height:120px;">
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
        <div :hidden="!edit" class="col-sm-12">
            <div role="tablist" class="company-collapse">
                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-btn block href="#" v-b-toggle.accordion1 variant="success ">Contact List</b-btn>
                    </b-card-header>
                    <b-collapse id="accordion1" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <input type="text" class="form-control" v-model="user.search.global" v-on:keyup.enter="userLoad(1)" placeholder="Type and press enter to search"
                                        title="Search Item">
                                </div>
                                <div class="col-md-9 col-sm-12 text-right header-menu-nav">
                                    <slot name="navigations"></slot>

                                    <router-link :to="{ path: `/company/${$route.params.id}/user/create` }" class="btn btn-link">
                                        <i class="fa fa-plus-square icon" title="Add Item"></i>
                                    </router-link>
                                    <button class="btn btn-link" @click.prevent="userClearSearch" title="Clear Search">
                                        <i class="fas fa-eraser icon"></i>
                                    </button>

                                    <button :disabled="!user.selectedIds.length" class="btn btn-link" @click.prevent="userErase" title="Delete Item">
                                        <i class="fa fa-trash icon"></i>
                                    </button>
                                    <b-dropdown variant="link" no-caret>
                                        <template slot="button-content">
                                            <i class="fas fa-columns icon"></i>
                                        </template>
                                        <div class="selected-columns">
                                            <li v-for="(column, index) in user.columns" :key="index">
                                                <b-form-checkbox v-model="user.selectedColumns" @change="userColumnSelect" :value="column.name">{{column.label}}</b-form-checkbox>
                                            </li>
                                        </div>
                                    </b-dropdown>
                                    <download-excel :data="sortedUser" class="btn">
                                        <i class="fa fa-download icon"></i>
                                    </download-excel>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-striped table-responsive">
                                        <thead>
                                            <!-- Column Sort -->
                                            <tr>
                                                <th v-if="userCheckColumnVisibility('all')">
                                                    All
                                                </th>
                                                <th v-if="userCheckColumnVisibility('full_name')" @click="userSortByColumn('full_name')" class="text-center sortable">
                                                    Name
                                                    <i :class="userSetSelectedColumnSortIcon('full_name')"></i>
                                                </th>
                                                <th v-if="userCheckColumnVisibility('username')" @click="userSortByColumn('username')" class="text-center sortable">
                                                    Username
                                                    <i :class="userSetSelectedColumnSortIcon('username')"></i>
                                                </th>
                                                <th v-if="userCheckColumnVisibility('image')" class="text-center sortable">
                                                    Image
                                                </th>
                                                <th v-if="userCheckColumnVisibility('contact_type')" @click="userSortByColumn('contact_type')" class="text-center sortable">
                                                    Contact Type
                                                    <i :class="userSetSelectedColumnSortIcon('contact_type')"></i>
                                                </th>
                                                <th v-if="userCheckColumnVisibility('email')" @click="userSortByColumn('email')" class="text-center sortable">
                                                    Email
                                                    <i :class="userSetSelectedColumnSortIcon('email')"></i>
                                                </th>
                                                <th v-if="userCheckColumnVisibility('phone_number')" @click="userSortByColumn('phone_number')" class="text-center sortable">
                                                    Phone
                                                    <i :class="userSetSelectedColumnSortIcon('phone_number')"></i>
                                                </th>
                                                <th v-if="userCheckColumnVisibility('location_address')" @click="userSortByColumn('location_address')" class="text-center sortable">
                                                    Associated Location
                                                    <i :class="userSetSelectedColumnSortIcon('location_address')"></i>
                                                </th>
                                            </tr>
                                            <!-- Column Search -->
                                            <tr>
                                                <th v-if="userCheckColumnVisibility('all')">
                                                    <b-form-checkbox name="checkAll" title="Check all" @change="userAlluserRowSelect" v-model="user.all"></b-form-checkbox>
                                                </th>
                                                <th v-if="userCheckColumnVisibility('full_name')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="user.search.full_name" />
                                                </th>
                                                <th v-if="userCheckColumnVisibility('username')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="user.search.username" />
                                                </th>
                                                <th v-if="userCheckColumnVisibility('image')">
                                                </th>
                                                <th v-if="userCheckColumnVisibility('contact_type')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="user.search.contact_type" />
                                                </th>
                                                <th v-if="userCheckColumnVisibility('email')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="user.search.email" />
                                                </th>
                                                <th v-if="userCheckColumnVisibility('phone_number')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="user.search.phone_number" />
                                                </th>
                                                <th v-if="userCheckColumnVisibility('location_address')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="user.search.location_address" />
                                                </th>
                                            </tr>
                                        </thead>
                                        <!-- Table Body -->
                                        <tbody>
                                            <tr v-for="(contact, index) in sortedUser" :key="index">
                                                <td v-if="userCheckColumnVisibility('all')">
                                                    <b-form-checkbox v-model="user.selectedIds" @change="userRowSelect" :value="contact.id">
                                                    </b-form-checkbox>
                                                </td>
                                                <td v-if="userCheckColumnVisibility('full_name')">
                                                    <router-link :to="{ path: `/user/${contact.id}/edit`}">
                                                        {{ contact.full_name }}
                                                    </router-link>
                                                </td>
                                                <td v-if="userCheckColumnVisibility('username')">
                                                    {{ contact.username }}
                                                </td>

                                                <td v-if="userCheckColumnVisibility('image')">
                                                    <router-link :to="{ path: `/user/${contact.id}/edit`}">
                                                        <div class="image-container">
                                                            <img class="object-fit-cover" :src="contact.image || '/img/user.png'" />
                                                        </div>
                                                    </router-link>
                                                </td>
                                                <td v-if="userCheckColumnVisibility('contact_type')">
                                                    {{ contact.contact_type }}
                                                </td>
                                                <td v-if="userCheckColumnVisibility('email')">
                                                    <a :href="`mailto: ${contact.email}`">{{ contact.email }}</a>
                                                </td>
                                                <td v-if="userCheckColumnVisibility('phone_number')">
                                                    <a :href="`tel: ${contact.phone_number}`">{{ contact.phone_number }}</a>
                                                </td>
                                                <td v-if="userCheckColumnVisibility('location_address')">
                                                    {{ contact.location_address }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <!-- Table Body End -->
                                    </table>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-1 justify-content-center align-self-center">
                                    <multiselect v-model="user.pagination.per_page" @select="userOnSelectPageCount" :options="user.pagination.perPageItems" :searchable="false"
                                        :show-labels="false" placeholder="Items / Page"></multiselect>
                                </div>

                                <div class="col-lg-3 justify-content-center align-self-center text-center">

                                </div>

                                <div class="col-lg-4 justify-content-center align-self-center text-center">
                                    <div>Total {{user.pagination.total}} items, Per page {{user.pagination.per_page}}, Showing
                                        {{user.pagination.from}} 〜 {{user.pagination.to}}</div>
                                </div>

                                <div class="col-lg-4 justify-content-center align-self-center">
                                    <ul class="pagination pagination-custom pull-right">
                                        <li :class="{disabled: user.pagination.current_page <= 1}"><a href="#" @click.prevent="userPageChange(1)">&laquo;</a></li>
                                        <li :class="{disabled: user.pagination.current_page <= 1}"><a href="#" @click.prevent="userPageChange(user.pagination.current_page - 1)">&lt;</a></li>
                                        <li v-for="page in userPages" :key="page" :class="{active: page === user.pagination.current_page}">
                                            <a href="#" @click.prevent="userPageChange(page)">{{page}}</a>
                                        </li>
                                        <li :class="{disabled: user.pagination.current_page >= user.pagination.last_page}"><a
                                                href="#" @click.prevent="userPageChange(user.pagination.current_page + 1)">&gt;</a></li>
                                        <li :class="{disabled: user.pagination.current_page >= user.pagination.last_page}"><a
                                                href="#" @click.prevent="userPageChange(user.pagination.last_page)">&raquo;</a></li>
                                    </ul>
                                </div>
                            </div>
                        </b-card-body>
                    </b-collapse>
                </b-card>
                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-btn block href="#" v-b-toggle.accordion2 variant="success ">Location List</b-btn>
                    </b-card-header>
                    <b-collapse id="accordion2" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <input type="text" class="form-control" v-model="location.search.global" v-on:keyup.enter="locationLoad(1)" placeholder="Type and press enter to search"
                                        title="Search Item">
                                </div>
                                <div class="col-md-9 col-sm-12 text-right header-menu-nav">
                                    <slot name="navigations"></slot>

                                    <router-link :to="{ path: `/location/${$route.params.id}/create` }" class="btn btn-link">
                                        <i class="fa fa-plus-square icon" title="Add Item"></i>
                                    </router-link>
                                    <button class="btn btn-link" @click.prevent="locationClearSearch" title="Clear Search">
                                        <i class="fas fa-eraser icon"></i>
                                    </button>

                                    <button :disabled="!location.selectedIds.length" class="btn btn-link" @click.prevent="locationErase" title="Delete Item">
                                        <i class="fa fa-trash icon"></i>
                                    </button>
                                    <b-dropdown variant="link" no-caret>
                                        <template slot="button-content">
                                            <i class="fas fa-columns icon"></i>
                                        </template>
                                        <div class="selected-columns">
                                            <li v-for="(column, index) in location.columns" :key="index">
                                                <b-form-checkbox v-model="location.selectedColumns" @change="locationColumnSelect" :value="column.name">{{column.label}}</b-form-checkbox>
                                            </li>
                                        </div>
                                    </b-dropdown>
                                    <download-excel :data="sortedLocation" class="btn">
                                        <i class="fa fa-download icon"></i>
                                    </download-excel>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-striped table-responsive">
                                        <thead>
                                            <!-- Column Sort -->
                                            <tr>
                                                <th v-if="locationCheckColumnVisibility('all')">
                                                    All
                                                </th>
                                                <th v-if="locationCheckColumnVisibility('description')" @click="locationSortByColumn('description')" class="text-center sortable">
                                                    Description
                                                    <i :class="locationSetSelectedColumnSortIcon('description')"></i>
                                                </th>
                                                <th v-if="locationCheckColumnVisibility('address')" @click="locationSortByColumn('address')" class="text-center sortable">
                                                    Address
                                                    <i :class="locationSetSelectedColumnSortIcon('address')"></i>
                                                </th>
                                                <th v-if="locationCheckColumnVisibility('is_primary')" @click="locationSortByColumn('is_primary')" class="text-center sortable">
                                                    Primary Address?
                                                    <i :class="locationSetSelectedColumnSortIcon('is_primary')"></i>
                                                </th>
                                                <th v-if="locationCheckColumnVisibility('full_name')" @click="locationSortByColumn('full_name')" class="text-center sortable">
                                                    Key Contact
                                                    <i :class="locationSetSelectedColumnSortIcon('full_name')"></i>
                                                </th>
                                                <th v-if="locationCheckColumnVisibility('phone_number')" @click="locationSortByColumn('phone_number')" class="text-center sortable">
                                                    Phone
                                                    <i :class="locationSetSelectedColumnSortIcon('phone_number')"></i>
                                                </th>
                                            </tr>
                                            <!-- Column Search -->
                                            <tr>
                                                <th v-if="locationCheckColumnVisibility('all')">
                                                    <b-form-checkbox name="checkAll" title="Check all" @change="locationAlllocationRowSelect" v-model="location.all"></b-form-checkbox>
                                                </th>
                                                <th v-if="locationCheckColumnVisibility('description')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="location.search.description" />
                                                </th>
                                                <th v-if="locationCheckColumnVisibility('address')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="location.search.address" />
                                                </th>
                                                <th v-if="locationCheckColumnVisibility('is_primary')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="location.search.is_primary" />
                                                </th>
                                                <th v-if="locationCheckColumnVisibility('full_name')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="location.search.full_name" />
                                                </th>
                                                <th v-if="locationCheckColumnVisibility('phone_number')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="location.search.phone_number" />
                                                </th>
                                            </tr>
                                        </thead>
                                        <!-- Table Body -->
                                        <tbody>
                                            <tr v-for="(loc, index) in sortedLocation" :key="index">
                                                <td v-if="locationCheckColumnVisibility('all')">
                                                    <b-form-checkbox v-model="location.selectedIds" @change="locationRowSelect" :value="loc.id">
                                                    </b-form-checkbox>
                                                </td>
                                                <td v-if="locationCheckColumnVisibility('description')">
                                                    <router-link :to="{ path: `/location/${loc.id}`}">
                                                        {{ loc.description }}
                                                    </router-link>
                                                </td>
                                                <td v-if="locationCheckColumnVisibility('address')">
                                                     <router-link :to="{ path: `/location/${loc.id}`}">
                                                        {{ loc.address }}
                                                    </router-link>
                                                </td>
                                                 <td v-if="locationCheckColumnVisibility('is_primary')">
                                                    {{ loc.is_primary }}
                                                </td>
                                                <td v-if="locationCheckColumnVisibility('full_name')">
                                                    {{ loc.full_name }}
                                                </td>
                                                <td v-if="locationCheckColumnVisibility('phone_number')">
                                                    <a :href="`tel: ${loc.phone_number}`">{{ loc.phone_number }}</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <!-- Table Body End -->
                                    </table>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-1 justify-content-center align-self-center">
                                    <multiselect v-model="location.pagination.per_page" @select="locationOnSelectPageCount" :options="location.pagination.perPageItems"
                                        :searchable="false" :show-labels="false" placeholder="Items / Page"></multiselect>
                                </div>

                                <div class="col-lg-3 justify-content-center align-self-center text-center">

                                </div>

                                <div class="col-lg-4 justify-content-center align-self-center text-center">
                                    <div>Total {{location.pagination.total}} items, Per page {{location.pagination.per_page}},
                                        Showing {{location.pagination.from}} 〜 {{location.pagination.to}}</div>
                                </div>

                                <div class="col-lg-4 justify-content-center align-self-center">
                                    <ul class="pagination pagination-custom pull-right">
                                        <li :class="{disabled: location.pagination.current_page <= 1}"><a href="#" @click.prevent="locationPageChange(1)">&laquo;</a></li>
                                        <li :class="{disabled: location.pagination.current_page <= 1}"><a href="#" @click.prevent="locationPageChange(location.pagination.current_page - 1)">&lt;</a></li>
                                        <li v-for="page in locationPages" :key="page" :class="{active: page === location.pagination.current_page}">
                                            <a href="#" @click.prevent="locationPageChange(page)">{{page}}</a>
                                        </li>
                                        <li :class="{disabled: location.pagination.current_page >= location.pagination.last_page}"><a
                                                href="#" @click.prevent="locationPageChange(location.pagination.current_page + 1)">&gt;</a></li>
                                        <li :class="{disabled: location.pagination.current_page >= location.pagination.last_page}"><a
                                                href="#" @click.prevent="locationPageChange(location.pagination.last_page)">&raquo;</a></li>
                                    </ul>
                                </div>
                            </div>
                        </b-card-body>
                    </b-collapse>
                </b-card>
            </div>
        </div>
        <!-- {{users}} -->
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
    import VueGoogleAutocomplete from 'vue-google-autocomplete'
    import Multiselect from 'vue-multiselect'
    export default {
        name: "forms",
        components: {
            VueGoogleAutocomplete, Multiselect
        },
        data() {
            return {
                assets:[],
                users: [],
                locations: [],
                companyTypes:[],
                company: {
                    is_enable: 1,
                    type: null
                },
                edit: false,
                url: null,
                selectedCompanyType: { id: null, type: 'Company Type' },
                address: '',
                user: {
                    currentSortColumn: 'name',
                    currentSortDirection: 'asc',
                    search: {
                        global: '',
                        full_name: '',
                        username: '',
                        contact_type:'',
                        email: '',
                        is_primary:'',
                        phone_number: '',
                        location_address:''
                    },
                    pagination: {
                        per_page: 5,
                        current_page: 1,
                        last_page: 1,
                        total: 1,
                        from: 0,
                        to: 0,
                        perPageItems: [5, 10, 15]
                    },
                    all: false,
                    selectedIds: [],
                    selectedColumns: [],
                    columns: [
                        { name: 'all', label: 'All' },
                        { name: 'full_name', label: 'Name' },
                        { name: 'username', label: 'Username' },
                        { name: 'image', label: 'Image' },
                        { name: 'contact_type', label: 'Contact Type' },
                        { name: 'email', label: 'Email' },
                        { name: 'phone_number', label: 'Phone' },
                        { name: 'location_address', label: 'Location' },
                    ]
                },
                location: {
                    currentSortColumn: 'name',
                    currentSortDirection: 'asc',
                    search: {
                        global: '',
                        description: '',
                        address: '',
                        full_name: '',
                        phone_number: '',
                        is_primary: ''
                    },
                    pagination: {
                        per_page: 5,
                        current_page: 1,
                        last_page: 1,
                        total: 1,
                        from: 0,
                        to: 0,
                        perPageItems: [5, 10, 15]
                    },
                    all: false,
                    selectedIds: [],
                    selectedColumns: [],
                    columns: [
                        { name: 'all', label: 'All' },
                        { name: 'description', label: 'Description' },
                        { name: 'address', label: 'Address' },
                        { name: 'full_name', label: 'Key Contact' },
                        { name: 'phone_number', label: 'Phone' },
                        { name: 'is_primary', label: 'is_primary' },
                    ]
                },
                showCollapse: false,
                defaultCountry:''
            }
        },
        mounted() {
            // To demonstrate functionality of exposed component functions
            // Here we make focus on the user input
            //this.$refs.address.focus();
        },
        created() {

            console.log(this.$store.state.user);
            if (!this.$store.state.user) this.$router.push("/login");
            this.company.account_id = this.$store.state.user.account_id;
            this.fetchCompanyWiseLocations()
            this.fetchCompanyType()
            if (this.$route.params.id) {
                this.edit = true;
                //for edit by id
                this.fetchById(this.$route.params.id)
                console.log("look for id", this.$route.params.id)
                this.placeChanged()
            }

            this.user.columns.forEach(column => {
                this.user.selectedColumns.push(column.name)
            })
            this.location.columns.forEach(column => {
                this.location.selectedColumns.push(column.name)
            })

            if(this.$route.query.flag){
                this.showCollapse = false
            }

           
        },
        methods: {
            onChange(image) {
                console.log('New picture selected!')
                if (image) {
                    console.log('Picture loaded.')
                    this.company.image = image
                    console.log("image preview", this.company.image)
                } else {
                    console.log('FileReader API not supported: use the <form>, Luke!')
                }
            },
            submit() {
                this.$validator.validate().then(result => {
                    if (result)
                        this.submitForm()
                })
            },
            fetchContactByCompany() {
                NProgress.start()
                this.$http.get(`/api/users/${this.$route.params.id}/company`, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                    .then(response => {
                        this.users = response.data.users;
                        console.log("users", this.users)
                        NProgress.done()
                    })
            },
            //sweet alert
            showAlert() {
                this.$swal('Company Added Successfully');
            },
            submitForm() {
                NProgress.start()
                this.$refs.btnSubmit.disabled = true
                self = this
                if (this.edit) {
                    this.company.updated_by = this.$store.state.user.id
                    this.company.account_id = this.$store.state.user.account_id
                    this.$http
                        .put(`/api/company`, this.company,
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
                                text: 'Company Information Updated'
                            })
                            this.$router.push("/companies/")
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
                    self = this
                    // this.checkCompanyName()
                    this.company.created_by = this.$store.state.user.id
                    this.$http
                        .post(`/api/company`, this.company,
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
                                text: 'Company Information Saved'
                            })
                            this.$router.push("/companies/")
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
                this.$http.get(`/api/company/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.company = response.data.data;
                        //this.users = response.data.data.user;
                        this.url = this.company.image
                        console.log("company", this.company);
                        console.log("user", this.users);
                        this.userLoad(1)
                        this.locationLoad(1)
                        this.fetchAssetByCompany(id)
                        this.fetchCompanyType()
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
            fetchCompanyWiseLocations() {
                NProgress.start()
                this.$http.get(`/api/company/${this.$route.params.id}/locations`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.locations = response.data.data
                        console.log("locations", this.users)
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
            fetchAssetByCompany(id) {
                NProgress.start()
                this.$http.get(`/api/asset-by-company/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.assets = response.data.data
                        console.log("assets", this.assets)
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
            eraseContact(id) {
                self = this
                self.$swal({
                    title: 'Do You Really Want To Delete？',
                    text: "You Can Not Revive This Information!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        this.$http.delete(`/api/user/${id}`, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                            .then(response => {
                                this.$swal(
                                    'Deleted!',
                                    'Information Is Deleted From Database',
                                    'success'
                                )
                                this.fetchById(this.$route.params.id);
                            })
                            .catch(error => {
                                if (error.response) {
                                    console.log(error.response);
                                    if (error.response.status === 401) {
                                        this.$router.push('/login')
                                    }

                                }
                            })
                    }
                    else {
                        this.$swal(
                            'Your Information Is Safe',
                            '',
                            'error'
                        )
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
                    ref.company.image = base64data
                }

                //console.log(this.url)
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
                //this.$refs.address.update()
            },
            getAddressData(addressData, placeResultData, id) {
                this.company.address = `${addressData.street_number}, ${addressData.route}, ${addressData.country}, ${addressData.postal_code}`
            },
            onInput({ number, isValid, country }) {
                console.log(number, isValid, country);
            },

            // User Datatable
            userErase() {
                self = this
                self.$swal({
                    title: 'Do You Really Want To Delete？',
                    text: "You Can Not Revive This Information!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        this.$http.delete(`/api/user/${this.user.selectedIds.join(',')}`, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                            .then(response => {
                                this.$swal(
                                    'Deleted!',
                                    'Information Is Deleted From Database',
                                    'success'
                                )
                                this.userLoad(1)
                                this.user.selectedIds = []
                            })
                            .catch(error => {
                                if (error.response) {
                                    console.log(error.response);
                                    if (error.response.status === 401) {
                                        this.$router.push('/login')
                                    }
                                }
                            })
                    }
                    else {
                        this.$swal(
                            'Your Information Is Safe',
                            '',
                            'error'
                        )
                    }
                })
            },
            userSortByColumn: function (s) {
                if (s === this.user.currentSortColumn) {
                    this.user.currentSortDirection = this.user.currentSortDirection === 'asc' ? 'desc' : 'asc';
                }
                this.user.currentSortColumn = s;
            },
            userSetSelectedColumnSortIcon(type) {
                if (this.user.currentSortDirection == 'asc' && this.user.currentSortColumn == type)
                    return 'fa fa-arrow-down'
                else if (this.user.currentSortDirection == 'desc' && this.user.currentSortColumn == type)
                    return 'fa fa-arrow-down open'
                else
                    return 'fa fa-arrow-down open fa-disabled'
            },
            // Paginating the table data
            userMakePagination(meta) {
                let pagination = {
                    per_page: this.user.pagination.per_page,
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    total: meta.total,
                    from: meta.from,
                    to: meta.to,
                    perPageItems: this.user.pagination.perPageItems
                }
                this.user.pagination = pagination;
            },
            userAlluserRowSelect() {
                var self = this;
                Vue.nextTick(function () {
                    self.user.selectedIds = []

                    if (self.user.all) {
                        self.sortedUser.forEach(user => {
                            self.user.selectedIds.push(user.id.toString())
                        })
                    }
                    else {
                        if (self.sortedUser.length == self.user.selectedIds.length) {
                            self.user.selectedIds = []
                        }
                    }
                })
            },
            userRowSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    if (self.sortedUser.length == self.user.selectedIds.length) {
                        self.user.all = true
                    }
                    else {
                        self.user.all = false
                    }
                })
            },
            userColumnSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    console.log(self.user.selectedColumns)
                })
            },
            userLoad(page) {
                NProgress.start()
                let url = `/api/users-datatable/${this.$route.params.id}`
                let params = `?page=${page}&per_page=${this.user.pagination.per_page}&search=${this.user.search.global}`

                this.$http.get(url + params,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(({ data }) => {
                        console.log('Users', data)
                        this.users = data.data
                        NProgress.done()
                        this.userMakePagination(data.meta)
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
            userPageChange(page) {
                if (page >= 1 && page <= this.user.pagination.last_page) this.userLoad(page)
            },
            userPerPageChanged(count) {
                this.user.pagination.per_page = count
                this.userLoad(1)
            },
            userClearSearch() {
                if (this.user.search.global.length) {
                    this.user.search.global = ''
                    this.userLoad(1)
                }

                this.user.search.full_name = ''
                this.user.search.username = ''
                this.user.search.contact_type = ''
                this.user.search.email = ''
                this.user.search.phone_number = '',
                this.user.search.location_address = ''
            },
            userOnSelectPageCount(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption, id)
                    this.user.pagination.per_page = selectedOption
                    this.userLoad(1)
                }
            },
            userCheckColumnVisibility(column) {
                return this.user.selectedColumns.indexOf(column) !== -1 ? true : false
            },
            // location Datatable
            locationErase() {
                self = this
                self.$swal({
                    title: 'Do You Really Want To Delete？',
                    text: "You Can Not Revive This Information!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.value) {
                        this.$http.delete(`/api/location/${this.location.selectedIds.join(',')}`, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                            .then(response => {
                                this.$swal(
                                    'Deleted!',
                                    'Information Is Deleted From Database',
                                    'success'
                                )
                                this.locationLoad(1)
                                this.location.selectedIds = []
                            })
                            .catch(error => {
                                if (error.response) {
                                    console.log(error.response);
                                    if (error.response.status === 401) {
                                        this.$router.push('/login')
                                    }
                                }
                            })
                    }
                    else {
                        this.$swal(
                            'Your Information Is Safe',
                            '',
                            'error'
                        )
                    }
                })
            },
            locationSortByColumn: function (s) {
                if (s === this.location.currentSortColumn) {
                    this.location.currentSortDirection = this.location.currentSortDirection === 'asc' ? 'desc' : 'asc';
                }
                this.location.currentSortColumn = s;
            },
            locationSetSelectedColumnSortIcon(type) {
                if (this.location.currentSortDirection == 'asc' && this.location.currentSortColumn == type)
                    return 'fa fa-arrow-down'
                else if (this.location.currentSortDirection == 'desc' && this.location.currentSortColumn == type)
                    return 'fa fa-arrow-down open'
                else
                    return 'fa fa-arrow-down open fa-disabled'
            },
            // Paginating the table data
            locationMakePagination(meta) {
                let pagination = {
                    per_page: this.location.pagination.per_page,
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    total: meta.total,
                    from: meta.from,
                    to: meta.to,
                    perPageItems: this.location.pagination.perPageItems
                }
                this.location.pagination = pagination;
            },
            locationAlllocationRowSelect() {
                var self = this;
                Vue.nextTick(function () {
                    self.location.selectedIds = []

                    if (self.location.all) {
                        self.sortedLocation.forEach(location => {
                            self.location.selectedIds.push(location.id.toString())
                        })
                    }
                    else {
                        if (self.sortedLocation.length == self.location.selectedIds.length) {
                            self.location.selectedIds = []
                        }
                    }
                })
            },
            locationRowSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    if (self.sortedLocation.length == self.location.selectedIds.length) {
                        self.location.all = true
                    }
                    else {
                        self.location.all = false
                    }
                })
            },
            locationColumnSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    console.log(self.location.selectedColumns)
                })
            },
            locationLoad(page) {
                NProgress.start()
                let url = `/api/locations-datatable/${this.$route.params.id}`
                let params = `?page=${page}&per_page=${this.location.pagination.per_page}&search=${this.location.search.global}`

                this.$http.get(url + params,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(({ data }) => {
                        console.log('locations', data)
                        this.locations = data.data
                        this.locationMakePagination(data.meta)
                        NProgress.done()
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
            locationPageChange(page) {
                if (page >= 1 && page <= this.location.pagination.last_page) this.locationLoad(page)
            },
            locationPerPageChanged(count) {
                this.location.pagination.per_page = count
                this.locationLoad(1)
            },
            locationClearSearch() {
                if (this.location.search.global.length) {
                    this.location.search.global = ''
                    this.locationLoad(1)
                }

                this.location.search.full_name = ''
                this.location.search.locationname = ''
                this.location.search.email = ''
                this.location.search.phone_number = ''
                this.location.search.is_primary = ''
            },
            locationOnSelectPageCount(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption, id)
                    this.location.pagination.per_page = selectedOption
                    this.locationLoad(1)
                }
            },
            locationCheckColumnVisibility(column) {
                return this.location.selectedColumns.indexOf(column) !== -1 ? true : false
            },
            //location datatable end
            confirm() {
                this.$refs.myModalRef.show()
            },
            hideModal() {
                this.$refs.myModalRef.hide()
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
                        if(this.edit){
                            this.selectedCompanyType =  this.companyTypes.find(x => (x.type) === (this.company.type))
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
            checkCompanyName() {
                this.$http
                    .get(`/api/check-company-name/${this.company.name}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        if (response.data.name) {
                            this.$swal("Company name already in use");
                        }
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
        },
        computed: {
            filteredUser: function () {
                var self = this
                return this.users.filter(function (user) {
                    return user.full_name ? user.full_name.toLowerCase().indexOf(self.user.search.full_name.toLowerCase()) >= 0 : true
                })
                    .filter(function (user) {
                        return user.username ? user.username.toLowerCase().indexOf(self.user.search.username.toLowerCase()) >= 0 : true
                    })
                    .filter(function (user) {
                        return user.contact_type ? user.contact_type.toLowerCase().indexOf(self.user.search.contact_type.toLowerCase()) >= 0 : true
                    })
                    .filter(function (user) {
                        return user.email ? user.email.toLowerCase().indexOf(self.user.search.email.toLowerCase()) >= 0 : true
                    })
                    .filter(function (user) {
                        return user.phone_number ? user.phone_number.toString().indexOf(self.user.search.phone_number.toString()) >= 0 : true
                    })
                    .filter(function (user) {
                        return user.location_address ? user.location_address.toString().indexOf(self.user.search.location_address.toString()) >= 0 : true
                    })
            },
            sortedUser: function () {
                return this.filteredUser.sort((a, b) => {
                    let modifier = 1
                    if (this.user.currentSortDirection === 'desc') modifier = -1

                    let temp = `.${this.user.currentSortColumn}`
                    if (eval(`a${temp}`) < eval(`b${temp}`)) return -1 * modifier
                    if (eval(`a${temp}`) > eval(`b${temp}`)) return 1 * modifier
                    return 0
                })
            },
            userPages() {
                let start = _.max([this.user.pagination.current_page - 2, 1])
                let end = _.min([start + 5, this.user.pagination.last_page + 1])
                start = _.max([end - 5, 1])
                return _.range(start, end)
            },
            filteredLocation: function () {
                var self = this
                return this.locations.filter(function (location) {
                    return location.description ? location.description.toLowerCase().indexOf(self.location.search.description.toLowerCase()) >= 0 : true
                })
                    .filter(function (location) {
                        return location.address ? location.address.toLowerCase().indexOf(self.location.search.address.toLowerCase()) >= 0 : true
                    })
                    .filter(function (location) {
                        return location.full_name ? location.full_name.toLowerCase().indexOf(self.location.search.full_name.toLowerCase()) >= 0 : true
                    })
                    .filter(function (location) {
                        return location.phone_number ? location.phone_number.toString().indexOf(self.location.search.phone_number.toString()) >= 0 : true
                    })
                    .filter(function (location) {
                        return location.is_primary ? location.is_primary.toString().indexOf(self.location.search.is_primary.toString()) >= 0 : true
                    })
            },
            sortedLocation: function () {
                return this.filteredLocation.sort((a, b) => {
                    let modifier = 1
                    if (this.location.currentSortDirection === 'desc') modifier = -1

                    let temp = `.${this.location.currentSortColumn}`
                    if (eval(`a${temp}`) < eval(`b${temp}`)) return -1 * modifier
                    if (eval(`a${temp}`) > eval(`b${temp}`)) return 1 * modifier
                    return 0
                })
            },
            locationPages() {
                let start = _.max([this.location.pagination.current_page - 2, 1])
                let end = _.min([start + 5, this.location.pagination.last_page + 1])
                start = _.max([end - 5, 1])
                return _.range(start, end)
            },
        },
    };
</script>
<template>
    <div class="animated fadeIn">
        <div id="forms" class="row">
            <div id="printDiv" class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm">
                    <div id="canvas" class="card border-0">
                        <div class="card-body">
                            <div class="form-group">

                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                </div>
                                <div class="form-group col-sm-4">
                                </div>
                                <div class="form-group col-sm-4">
                                    <p>
                                        <center><img :src="company.image" alt="" v-if="company.image" style="height:80px;">
                                            <img src="/img/company.png" style="height:39px;" v-else></center>
                                    </p>
                                    <p>
                                        <center><b>Website:</b> {{company.website}}</center>
                                    </p>
                                    <p>
                                        <center><b>Phone:</b> {{company.phone_number}}</center>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-3"></div>
                                <div class="form-group col-sm-6">
                                    <label>
                                        <center>
                                            <font size="6"><b>{{job.jobType.name}} Order Number -{{job.job_number}}</b></font>
                                        </center>
                                    </label>
                                </div>
                                <div class="form-group col-sm-6">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <p style="background-color:black;">
                                        <font size="5" color="white">Site Information</font>
                                    </p>
                                    <hr>
                                    <p><label for="Distributor"><b> Distributor:</b>
                                            <font size="3"> {{job.asset.agent_id}}</font>
                                        </label></p>
                                    <p><label for="Customer"><b> Customer:</b>
                                            <font size="6"><b> {{asset.company.name}}</b></font>
                                        </label></p>
                                    <p><label for="Location"><b> Location name:</b>
                                            <font size="3"> {{asset.location.description}}</font>
                                        </label></p>
                                    <p><label for="Address"><b> Address:</b>
                                            <font size="3"> {{asset.location.address}}</font>
                                        </label></p>
                                    <p><label for="Address"><b> Specific Location:</b>
                                            <font size="3"> {{asset.specific_location}}</font>
                                        </label></p>
                                    <!-- <p><label for="Contact"><font size="3"> Contact: </font></label></p>
                                    <p><label for="Position"><font size="3"> Position: </font></label></p>
                                    <p><label for="Phone"><font size="3"> Phone: </font></label></p> -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <p style="background-color:black;">
                                        <font size="5" color="white">Unit To Be Repaired</font>
                                    </p>
                                    <hr>
                                    <p><label for="Distributor"><b> Make:</b>
                                            <font size="3"> {{asset.assetModel.company_id}}</font>
                                        </label></p>
                                    <p><label for="Model"><b> Model:</b>
                                            <font size="4" id="asset"> {{asset.name}}</font>
                                        </label></p>
                                    <p><label for="Model"><b> Serial:</b>
                                            <font size="3"> {{asset.serial}}</font>
                                        </label></p>
                                    <p><label for="Model"><b> BMB:</b>
                                            <font size="3"> {{asset.bmb}}</font>
                                        </label></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <p style="background-color:black;">
                                        <font size="5" color="white">Additional Information</font>
                                    </p>
                                    <hr>
                                    <p><label for="Required Date"><b> Job Raised:</b>
                                            <font size="3"> {{job.job_raised_date}}</font>
                                        </label></p>
                                    <p><label for="Required Date"><b> Required Date:</b>
                                            <font size="3"> {{job.required_date}}</font>
                                        </label></p>
                                    <p><label for="Technician"><b> Allocated Technician:</b>
                                            <font size="3"> {{job.user.first_name+' '+job.user.last_name}}</font>
                                        </label></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <!-- <b-form-textarea id="textarea1" disabled v-model="job.description" placeholder="Description" :rows="3" :max-rows="6">
                                    </b-form-textarea> -->
                                    <label><b>Description:</b>
                                        <font size="4"><b> {{job.description}}</b></font>
                                    </label>
                                </div>
                                <div class="form-group col-sm-6">
                                    <!-- <b-form-textarea id="textarea1" disabled v-model="job.comment" placeholder="Comments" :rows="3" :max-rows="6">
                                    </b-form-textarea> -->
                                    <label>
                                        <font size="3"><b>Comments:</b> {{job.comment}}</font>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <p style="background-color:black;">
                                        <font size="5" color="white">Repair Items</font>
                                    </p>
                                    <hr>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Spare Parts</th>
                                                <th>Quantity Allocated</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="spareJob in spareJobs" v-bind:key="spareJob.id">
                                                <td>{{ spareJob.spare.name }}</td>
                                                <td>{{ spareJob.allocated }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <p>
                                        <font size="5"><b>Returning machine for repair or return?</b></font>
                                    </p>
                                    <p>If job involves extraction of machine and return to Nestle either for repair or
                                        return; Please
                                        ensure machine is empty of all product and water before packaging for
                                        transport. Phone 0800 372 800 to notify
                                        once ready for pickup.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="form-group col-sm-12">
                    <button class="btn btn-primary btn-sml" @click.prevent="printDiv">Download PDF</button>
                </div>
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
                companies: [],
                contractors: [],
                customers: [],
                jobs: [],
                priorities: [],
                jobStats: [],
                jobTypes: [],
                assets: [],
                distributors: [],
                technicians: [],
                spareJobs: [],
                job: {
                    asset_id: null,
                    job_type_id: null,
                    job_stat_id: null,
                    priority_id: null,
                    company_id: null,
                    customer_id: null,
                    user_id: null
                },
                spareJob: {},
                asset: {},
                edit: false,
                selectedCompany: { id: null, name: 'Select Contractor' },
                asset: {
                    company: {}
                },
                user: '',
                company: {},
            }
        },
        created() {
            console.log(this.$store.state.user);
            if (!this.$store.state.user) this.$router.push("/login");
            this.first_name = this.$store.state.user.first_name;
            this.user = this.$store.state.user;
            this.fetchUserWiseCompany()
            this.job.account_id = this.$store.state.user.account_id;
            this.job.asset_id = this.$route.params.id
            console.log("show asset id", this.job.asset_id)
            this.fetchAssets()
            this.fetchJobTypes()
            this.fetchJobStats()
            this.fetchPriorities()
            this.fetchContrctors()
            this.fetchCustomers()


            if (this.$route.path.indexOf("create") > -1) {
                console.log("show result", this.$route.path.indexOf("create"));
                this.edit = false;
                this.fetchCompanyWiseTechnicians(this.job.company_id)
                this.fetchAssetWiseCompany(this.$route.params.id)
            } else {
                this.edit = true;
                this.fetchById(this.$route.params.id);
                this.fetchSpareJobByJobID(this.$route.params.id)
            }
        },
        methods: {
            submit() {
                this.$validator.validate().then(result => {
                    if (result)
                        this.submitForm()
                })
            },
            fetchCompanyWiseTechnicians(id) {
                NProgress.start()
                this.$http.get(`/api/company/${id}/technicians`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.technicians = response.data.data
                        console.log("technicians", this.technicians)
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
            fetchCustomers() {
                NProgress.start()
                this.$http
                    .get(`/api/customers`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.customers = response.data.data;
                        console.log("customers", this.customers);
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
            companySelected: function (e) {
                this.fetchCompanyWiseTechnicians(event.srcElement.value)
                console.log('test result', event.srcElement.value)
            },
            fetchContrctors() {
                NProgress.start()
                this.$http.get(`/api/contractors`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.contractors = response.data.data
                        console.log("contractors", this.contractors)
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
            fetchAssets() {
                NProgress.start()
                this.$http.get(`/api/assets`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.assets = response.data.data;
                        console.log("assets", this.assets);
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
            fetchSpareJobByJobID(id) {
                NProgress.start()
                this.$http.get(`/api/job/${id}/spareJobs`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.spareJobs = response.data.data;
                        console.log("spareJobs", this.spareJobs);
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
            fetchPriorities() {
                NProgress.start()
                this.$http.get(`/api/priorities`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.priorities = response.data.data;
                        console.log("priorities", this.priorities);
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
            fetchJobStats() {
                NProgress.start()
                this.$http.get(`/api/jobStats`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.jobStats = response.data.data;
                        console.log("jobStats", this.jobStats);
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
            fetchJobTypes() {
                NProgress.start()
                this.$http.get(`/api/jobTypes`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.jobTypes = response.data.data;
                        console.log("jobTypes", this.jobTypes);
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
            submitForm() {
                self = this
                if (this.edit) {
                    this.job.updated_by = this.$store.state.user.id;
                    this.$http
                        .put(`/api/job`, this.job,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        //     .then(response => {
                        //     //this.$router.push("/companies/");
                        //     this.$swal({
                        //         title: "Do You Want To Update!",
                        //         text: "Please Click 'OK' To Save ",
                        //         type: "success",
                        //         confirmButtonText: 'OK'
                        //     })
                        //         .then(function () {
                        //             self.$router.push({ path: `/asset/${self.job.asset_id}` });
                        //         });
                        // })
                        .then(response => {
                            //alert("Job Updated");
                            this.$router.push({ path: `/asset/${this.job.asset_id}` });
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
                    this.job.created_by = this.$store.state.user.id;
                    this.$http
                        .post(`/api/job`, this.job,
                            {
                                headers: {
                                    Authorization: `Bearer ${this.$store.state.token}`
                                }
                            })
                        // .then(response => {
                        //     //this.$router.push("/companies/");
                        //     this.$swal({
                        //         title: "Do You Want To Save!",
                        //         text: "Please Click 'OK' To Save ",
                        //         type: "success",
                        //         confirmButtonText: 'OK'
                        //     })
                        //         .then(function () {
                        //             self.$router.push({ path: `/asset/${self.job.asset_id}` });
                        //         });
                        // })
                        .then(response => {
                            //alert("Job Updated");
                            this.$router.push({ path: `/asset/${this.job.asset_id}` });
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
                NProgress.start()
                this.$http.get(`/api/job/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.job = response.data.data;
                        this.fetchCompanyWiseTechnicians(this.job.company_id)
                        this.fetchAssetWiseCompany(this.job.asset_id)
                        console.log("job", this.job);

                        if (this.edit) {
                            console.log('check contractor', this.getContractor(this.job.company_id))
                            this.selectedCompany = this.getContractor(this.job.company_id)
                        }
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
            onSelectCompany(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.job.company_id = selectedOption.id
                    this.fetchCompanyWiseTechnicians(selectedOption.id)
                }
            },
            getContractor(id) {
                return this.contractors.find(x => x.id === id) ? this.contractors.find(x => x.id === id) : ''
            },
            fetchAssetWiseCompany(id) {
                this.$http.get(`/api/asset/${id}/company`, {
                    headers: {
                        Authorization: `Bearer ${this.$store.state.token}`
                    }
                })
                    .then(response => {
                        this.asset = response.data.data;
                        this.job.company_id = this.asset.company.id
                        console.log("asset", this.asset);
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
            fetchUserWiseCompany() {
                this.$http.get(`/api/company/${this.user.id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.company = response.data.data
                        console.log("company", this.company)
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
            printDiv() {
                var printContents = document.getElementById('printDiv').innerHTML
                var originalContents = document.body.innerHTML

                document.body.innerHTML = printContents
                window.print()
                document.body.innerHTML = originalContents
            }
        }
    }
</script>
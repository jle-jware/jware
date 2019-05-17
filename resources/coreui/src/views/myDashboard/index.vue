<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h3>Nestle Asset Management Home</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 col-md-2">
                <h4>Tasks</h4>
                <div v-if="$store.state.user.contact_type != 'Technician' && $store.state.user.contact_type != 'Manager'" class="card text-white bg-info">
                    <router-link :to="{ name: 'Add Job'}">
                        <div class="card-body">
                            <div class="h1 text-muted text-right mb-0">
                                <i class="fas fa-briefcase" style="color:white;">
                                </i>
                            </div>
                            <div class="h4 mb-0">
                                <font color="white">Create A Job</font>
                            </div>
                            <!-- <small class="text-muted text-uppercase font-weight-bold">Create A Job</small> -->
                            <div class="progress-white progress-xs mt-3 progress">
                                <div role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25" class="progress-bar" style="width: 25%;">
                                    <!---->
                                </div>
                            </div>
                        </div>
                    </router-link>
                </div>
                <div class="card text-white bg-danger">
                    <router-link :to="{ name: 'Job List'}">
                        <div class="card-body">
                            <div class="h1 text-muted text-right mb-0">
                                <i class="fab fa-searchengin"></i>
                            </div>
                            <div class="h4 mb-0"> <font color="white">Find A Job</font></div>
                            <!-- <small class="text-muted text-uppercase font-weight-bold">Find A Job</small> -->
                            <div class="progress-white progress-xs mt-3 progress">
                                <div role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25" class="progress-bar" style="width: 25%;">
                                    <!---->
                                </div>
                            </div>
                        </div>
                    </router-link>
                </div>
                <div v-if="$store.state.user.contact_type != 'Technician' && $store.state.user.contact_type != 'Manager'" class="card text-white bg-success">
                    <router-link :to="{path: '/asset/create'}">
                    <div class="card-body">
                        <div class="h1 text-muted text-right mb-0">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="h4 mb-0"><font color="white">Add Asset</font></div>
                        <!-- <small class="text-muted text-uppercase font-weight-bold">Create Asset</small> -->
                        <div class="progress-white progress-xs mt-3 progress">
                            <div role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25" class="progress-bar" style="width: 25%;">
                                <!---->
                            </div>
                        </div>
                    </div>
                </router-link>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <h4>
                    <font color="white">Tasks</font>
                </h4>
                <div v-if="$store.state.user.contact_type != 'Technician' && $store.state.user.contact_type != 'Manager'" class="card text-white bg-warning">
                    <router-link :to="{ name: 'Add Company'}">
                    <div class="card-body">
                        <div class="h1 text-muted text-right mb-0">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="h4 mb-0"><font color="white">New Customer</font></div>
                        <!-- <small class="text-muted text-uppercase font-weight-bold">Create A Job</small> -->
                        <div class="progress-white progress-xs mt-3 progress">
                            <div role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25" class="progress-bar" style="width: 25%;">
                                <!---->
                            </div>
                        </div>
                    </div>
                </router-link>
                </div>
                <!-- <div class="card text-white bg-danger">
                    <router-link :to="{ name: 'Add new Job'}">
                    <div class="card-body">
                        <div class="h1 text-muted text-right mb-0">
                            <i class="fas fa-people-carry"></i>
                        </div>
                        <div class="h4 mb-0"><font color="white">New Placement</font></div>
                        <div class="progress-white progress-xs mt-3 progress">
                            <div role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25" class="progress-bar" style="width: 25%;">
                            </div>
                        </div>
                    </div>
                </router-link>
                </div> -->
                <div  v-if="$store.state.user.contact_type != 'Technician' && $store.state.user.contact_type != 'Manager'" class="card text-white bg-success">
                    <router-link :to="{ name: 'Asset List'}">
                    <div class="card-body">
                        <div class="h1 text-muted text-right mb-0">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div class="h4 mb-0"><font color="white">Find Asset</font></div>
                        <!-- <small class="text-muted text-uppercase font-weight-bold">Create Asset</small> -->
                        <div class="progress-white progress-xs mt-3 progress">
                            <div role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25" class="progress-bar" style="width: 25%;">
                                <!---->
                            </div>
                        </div>
                    </div>
                </router-link>
                </div>
                <div class="card text-white bg-info">
                    <router-link :to="{ name: 'Inspection Result List'}">
                    <div class="card-body">
                        <div class="h1 text-muted text-right mb-0">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div class="h4 mb-0"><font color="white">QA Job</font></div>
                        <!-- <small class="text-muted text-uppercase font-weight-bold">Create Asset</small> -->
                        <div class="progress-white progress-xs mt-3 progress">
                            <div role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25" class="progress-bar" style="width: 25%;">
                                <!---->
                            </div>
                        </div>
                    </div>
                </router-link>
                </div>
            </div>
            <div v-if="$store.state.user.contact_type != 'Technician' && $store.state.user.contact_type != 'Manager'" class="col-sm-6 col-md-3">
                <h4>KPI's</h4>
                <div class="card text-black">
                    <div class="card-body">
                        <div class="h1 text-muted text-right mb-1">
                            <!-- <i class="icon-people"></i> -->
                            {{assetByPlacedStatus}}
                        </div>
                        <div class="progress-blue progress-xs mt-3 progress">
                            <div role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25" class="progress-bar" style="width: 25%;">
                                <!---->
                            </div>
                        </div>
                        <!-- <small class="text-muted text-uppercase font-weight-bold">Create A Job</small> -->
                        <div class="h4 mb-0">Asset Placed</div>
                    </div>
                </div>
                <div class="card text-black">
                    <div class="card-body">
                        <div class="h1 text-muted text-right mb-1">
                            <!-- <i class="icon-people"></i> -->
                            {{assetBystatus}}
                        </div>
                        <div class="progress-danger progress-xs mt-3 progress">
                            <div role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25" class="progress-bar" style="width: 25%;">
                                <!---->
                            </div>
                        </div>
                        <!-- <small class="text-muted text-uppercase font-weight-bold">Create A Job</small> -->
                        <div class="h4 mb-0">Available Assets</div>
                    </div>
                </div>
                <div class="card text-black">
                    <div class="card-body">
                        <div class="h1 text-muted text-right mb-1">
                            <!-- <i class="icon-people"></i> -->
                            {{jobCount}}
                        </div>
                        <div class="progress-danger progress-xs mt-3 progress">
                            <div role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25" class="progress-bar" style="width: 25%;">
                                <!---->
                            </div>
                        </div>
                        <!-- <small class="text-muted text-uppercase font-weight-bold">Create A Job</small> -->
                        <div class="h4 mb-0">Open Jobs</div>
                    </div>
                </div>
                <div class="card text-black">
                    <div class="card-body">
                        <div class="h1 text-muted text-right mb-1">
                            <!-- <i class="icon-people"></i> -->
                            {{jobCountByType}}
                        </div>
                        <div class="progress-danger progress-xs mt-3 progress">
                            <div role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25" class="progress-bar" style="width: 25%;">
                                <!---->
                            </div>
                        </div>
                        <!-- <small class="text-muted text-uppercase font-weight-bold">Create A Job</small> -->
                        <div class="h4 mb-0">Placement Requests</div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-sm-6 col-md-4">
                <h4>Diagnostics</h4>
                <b-card header="<i class='fa fa-align-justify'></i>">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>List</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Job 3456 Over Due.</td>
                            </tr>
                            <tr>
                                <td>Asset 234 Cup Count Excceded</td>
                            </tr>
                            <tr>
                                <td>Job 897 Not Allocated To Technician</td>
                            </tr>
                            <tr>
                                <td>Job 3456 Over Due.</td>
                            </tr>
                            <tr>
                                <td>Asset 234 Cup Count Excceded</td>
                            </tr>
                            <tr>
                                <td>Asset 234 Cup Count Excceded</td>
                            </tr>

                        </tbody>
                    </table>
                    <ul class="pagination">
                        <li class="page-item">
                            <a class="page-link" href="#">Prev</a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">3</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">4</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </b-card>
            </div> -->
            <!--/.col-->
        </div>
    </div>
</template>

<script>
    export default {
        name: "forms",
        components: {},
        data() {
            return {
                jobCount: 0,
                jobCountByType:0,
                assetBystatus:0,
                assetByPlacedStatus:0

            };
        },
        created() {
            console.log(`Heeeeeee`, this.$store.state.user)
            if(!this.$store.state.user)
                this.$router.push('/login')

            this.fetchJobCount()
            this.fetchJobCountByType()
            this.fetchAssetsByStatus()
            this.fetchAssetsByStatus()
            this.fetchAssetsByPlacedStatus()
            
        },
        methods: {
			fetchJobCount() {
				this.$http.get(`/api/job-count`,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.jobCount = response.data.jobCount
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
            fetchJobCountByType() {
				this.$http.get(`/api/job-count-by-type`,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.jobCountByType = response.data.jobCountByType
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
            fetchAssetsByStatus() {
				this.$http.get(`/api/asset-by-status`,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.assetBystatus = response.data.assetBystatus
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
            fetchAssetsByPlacedStatus() {
				this.$http.get(`/api/asset-by-placed`,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.assetByPlacedStatus = response.data.assetByPlacedStatus
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
        }
    }
</script>
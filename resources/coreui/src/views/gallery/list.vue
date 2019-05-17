<template>
	<div class="animated fadeIn">
		<div class="row">
			<!--/.col-->
			<div class="col-lg-12">

				<b-card header="" border-variant="light">
					<div class="row">
						<!-- <div class="col-md-3 col-sm-12">
							<input type="text" class="form-control" placeholder="Search" title="Search Item" >
						</div> -->
						<div class="col-md-12 col-sm-12 text-right header-menu-nav">
							<slot name="navigations"></slot>
							<!-- <router-link :to="{ name: createRoute }" class="btn btn-link">
								<i class="fa fa-plus-square" title="Add Item" style="font-size:20px;  color: black;"></i>
							</router-link> -->
							<!-- <a class="btn btn-link" :href="createLink"><i class="far fa-plus-square" style="font-size:20px;  color: black;"></i></a>
							<button class="btn btn-link" title="Edit Item" ><i class="fa fa-edit" style="font-size:20px; color: black;"></i></button>
							<button class="btn btn-link" title="Delete Item" ><i class="fa fa-trash" style="font-size:20px; color:black;"></i></button> -->
							<!-- <div class="dropdown export" title="Download List" >
								<button class="btn btn-link  dropdown-toggle" data-toggle="dropdown" 
									aria-haspopup="true" aria-expanded="false" style="font-size: 20px;color: black;" ref="dropdownBtn">
									<i class="fa fa-download"></i> 
								</button>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
									<a class="dropdown-item" href="#" >Excel</a>
									<a class="dropdown-item" href="#" >PDF</a>
								</div>
							</div> -->
							<!-- <router-link :to="{ name: 'Add Company'}">
								<button class="btn btn-warning pull-right">Add Company</button>
							</router-link> -->
							<router-link :to="{ name: 'Add Company' }" class="btn btn-link">
								<i class="fa fa-plus-square" title="Add New Company" style="font-size:20px;  color: black;"></i>
							</router-link>
						</div>
					</div>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Actions</th>
								<th>Company Name</th>
								<th>Company Logo</th>
								<th>Website</th>
								<th>Address</th>
								<th>Type</th>
								<th>Phone Number</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="company in companies" v-bind:key="company.id">
								<td>
									<router-link :to="{ path:`/companies`}">
										<span v-on:click="erase(company.id)">
											<i class="fa fa-trash fa-lg" title="Delete Company" style="font-size:20px;  color: black;"></i>
										</span>
									</router-link>
									<router-link :to="{ path: `/company/${company.id}`}">
										<i class="fa fa-edit fa-lg" title="Edit Company" style="font-size:20px;  color: black;"></i>
									</router-link>
								</td>
								<td>
									<router-link :to="{ path: `/company/${company.id}`}">
										{{ company.name }}
									</router-link>
								</td>
								<td>
									<div class="image-container">
										<router-link :to="{ path: `/company/${company.id}`}">
											<img class="object-fit-cover" :src="company.image || '/img/company.png'" />
										</router-link>
									</div>
								</td>
								<td>
									 <a target="_blank" :href="company.website">{{ company.website }}</a>
								</td>
								<td>{{ company.address }}</td>
								<td>{{ company.type }}</td>
								<td>{{ company.phone_number }}</td>
							</tr>
						</tbody>
					</table>
				</b-card>
			</div>
			<!--/.col-->
		</div>
		<!--/.row-->
	</div>
</template>

<script>
	export default {
		name: 'list',
		data() {
			return {
				companies: []
			}
		},
		created() {
			console.log(this.$store.state.user)
			if (!this.$store.state.user)
				this.$router.push('/login')
			this.fetch();
		},
		methods: {
			showAlert() {
				// Use sweetalret2
				this.$swal('Company Deleted Successfully');
			},
			fetch() {
				this.$http.get(`/api/companies`,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.companies = response.data.data
						this.users = response.data.data[0].user
						console.log("companies", this.companies)
						console.log("users", this.users)
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
			erase(id) {
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
						this.$http.delete(`/api/company/${id}`, {
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
								this.fetch()
							})
							.catch(error => {
								if (error.response) {
									console.log(error.response);
									if (error.response.status === 401) {
										this.$router.push('/login')
									}

								}
							})	.then(response => {
								this.$swal(
									'Deleted!',
									'Information Is Deleted From Database',
									'success'
								)
								this.fetch()
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
			}
		}
	}
</script>
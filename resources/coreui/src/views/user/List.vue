<template>
	<div class="animated fadeIn">
		<div class="row">
			<!--/.col-->
			<div class="col-lg-12">
				<!-- <router-link :to="{ name: 'Add Contact'}">
					<button class="btn btn-warning">Add</button>
				</router-link> -->
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
							<router-link :to="{ name: 'Add Contact' }" class="btn btn-link">
								<i class="fa fa-plus-square" title="Add New User" style="font-size:20px;  color: black;"></i>
							</router-link>
						</div>
					</div>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Actions</th>
								<th>Contact Name</th>
								<!-- <th>Location</th> -->
								<th>Contact Image</th>
								<th>Company Name</th>
								<th>Email</th>
								<th>Phone Number</th>
								<th>Contact Type</th>
								<!-- <th>Created by</th>
								<th>Updated By</th> -->
								
							</tr>
						</thead>
						<tbody>
							<tr v-for="user in users" v-bind:key="user.id">
								<td>
									<!-- <router-link :to="{ path: `/user/${user.id}`}">
										<b-button type="reset" size="sm" variant="danger">
											<i class="icon-trash"></i> Edit</b-button>
									</router-link> -->
									<router-link :to="{ path: `/user/${user.id}`}">
										<i class="fa fa-edit fa-lg" title="Edit User" style="font-size:20px;  color: black;"></i>
									</router-link>
									<!-- <b-button @click.prevent="erase(user.id)" type="reset" size="sm" variant="danger">
										<i class="icon-trash"></i> Delete</b-button> -->
									<router-link :to="{ path:`/users`}">
										<span v-on:click="erase(user.id)">
											<i class="fa fa-trash fa-lg" title="Delete User" style="font-size:20px;  color: black;"></i>
										</span>
									</router-link>
								</td>
								<td>
									<router-link :to="{ path:  `/user/${user.id}`}">
										{{ user.last_name +' , '+ user.first_name }}
									</router-link>
								</td>
								<!-- <td>{{(user.location.address)? user.location.address: "not set"}}</td> -->
								<td>
									<div class="image-container">
										<img class="object-fit-cover" :src="user.image || '/img/user.png'" />
									</div>
								</td>
								<td>{{ user.company.name }}</td>
								<td>
									 <a :href="'mailto:'+ user.email">{{ user.email }}</a>
								</td>
								<td>{{ user.phone_number }}</td>
								<td>{{ user.contact_type }}</td>
							</tr>
						</tbody>
					</table>
					<!-- <ul class="pagination">
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
					</ul> -->
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
				users: []
			}
		},
		created() {
			console.log(this.$store.state.user)
			if (!this.$store.state.user)
				this.$router.push('/login')
			this.fetch();
		},
		methods: {
			fetch() {
				this.$http.get(`/api/users`,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.users = response.data.data
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
				this.$swal({
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
						this.$http.delete(`api/user/${id}`, {
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
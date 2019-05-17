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
							<!-- <router-link :to="{ name: 'Add assetModel'}">
								<button class="btn btn-warning pull-right">Add assetModel</button>
							</router-link> -->
							<router-link :to="{ name: 'Add Asset Model' }" class="btn btn-link">
								<i class="fa fa-plus-square" title="Add New assetModel" style="font-size:20px;  color: black;"></i>
							</router-link>
						</div>
					</div>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Actions</th>
								<th>Model Name</th>
								<th>Type</th>
								<th>Rent</th>
								<th>Frequency</th>
								<th>Minimum Stock</th>
								<th>Current Stock</th>
								<th>Image</th>
								<th>Description</th>
								<th>Comment</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="assetModel in assetModels" v-bind:key="assetModel.id">
								<td>
									<router-link :to="{ path: `/assetModel/${assetModel.id}`}">
										<i class="fa fa-edit fa-lg" title="Edit assetModel" style="font-size:20px;  color: black;"></i>
									</router-link>
									<router-link :to="{ path:`/assetModels`}">
										<span v-on:click="erase(assetModel.id)">
											<i class="fa fa-trash fa-lg" title="Delete assetModel" style="font-size:20px;  color: black;"></i>
										</span>
									</router-link>
								</td>
								<td>
									<router-link :to="{ path: `/assetModel/${assetModel.id}`}">
										{{ assetModel.name }}
									</router-link>
								</td>
								<td>{{ assetModel.modelType.type }}</td>
								<td>{{ assetModel.rent }}</td>
								<td>{{ assetModel.frequency }}</td>
								<td>{{ assetModel.minimum_stock }}</td>
								<td>{{ assetModel.current_stock }}</td>
								<td>
									<div id="preview" style="height:60px;width:100px;">
										<img :src="assetModel.image" />
									</div>
								</td>
								<td>{{ assetModel.description }}</td>
								<td>{{ assetModel.comment }}</td>
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
				assetModels: []
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
				this.$swal('assetModel Deleted Successfully');
			},
			fetch() {
				this.$http.get(`/api/assetModels`,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.assetModels = response.data.data
						this.users = response.data.data[0].user
						console.log("assetModels", this.assetModels)
						console.log("users", this.users)
					})
					.catch(err => console.log(err));
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
						this.$http.delete(`/api/assetModel/${id}`, {
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
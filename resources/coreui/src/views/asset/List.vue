<template>
	<div class="animated fadeIn">
		<div class="row">
			<!--/.col-->
			<div class="col-lg-12">
				<!-- <router-link :to="{ name: 'Add Asset'}">
					<button class="btn btn-warning">Add</button>
				</router-link> -->
				<b-card header="" border-variant="light">
					<div class="row">
						<div class="col-md-12 col-sm-12 text-right header-menu-nav">
							<slot name="navigations"></slot>
							<router-link :to="{ name: 'Add Asset' }" class="btn btn-link">
								<i class="fa fa-plus-square" title="Add New asset" style="font-size:20px;  color: black;"></i>
							</router-link>
						</div>
					</div>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Actions</th>
								<th>Asset Name</th>
								<th>Asset Type</th>
								<th>Company</th>
								<th>Location</th>
								<th>Specific Location</th>
								<th>Distributor</th>
								<th>Serial</th>
								<th>BMB</th>
								<th>Status</th>
								<th>Asset Value</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="asset in assets" v-bind:key="asset.id">
								<td>
									<router-link :to="{ path:`/assets`}">
										<span v-on:click="erase(asset.id)">
											<i class="fa fa-trash fa-lg" title="Delete asset" style="font-size:20px;  color: black;"></i>
										</span>
									</router-link>
									<router-link :to="{ path: `/asset/${asset.id}`}">
										<i class="fa fa-edit fa-lg" title="Edit asset" style="font-size:20px;  color: black;"></i>
									</router-link>
								</td>
								<td>
									<router-link :to="{ path: `/asset/${asset.id}`}">
										{{ asset.name }}
									</router-link>
								</td>
								<td>{{ asset.assetModel.name }}</td>
								<td>{{ asset.company.name }}</td>
								<td>{{ asset.location.address }}</td>
								<td>{{ asset.specific_location }}</td>
								<td>{{ asset.agent_id }}</td>
								<td>{{ asset.serial }}</td>
								<td>{{ asset.bmb }}</td>
								<td>{{ asset.status }}</td>
								<td>{{ asset.cost }}</td>
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
				assets: []
			}
		},
		created() {
			console.log(this.$store.state.user)
			if (!this.$store.state.user)
				this.$router.push('/login')
			this.fetch();
		},
		methods: {
			showAlert(){
            // Use sweetalret2
            this.$swal('asset Deleted Successfully');
            },
			fetch() {
				this.$http.get(`/api/assets`,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.assets = response.data.data
						console.log("assets", this.assets)
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
						this.$http.delete(`/api/asset/${id}`, {
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
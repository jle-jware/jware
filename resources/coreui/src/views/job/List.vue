<template>
	<div class="animated fadeIn">
		<div class="row">
			<!--/.col-->
			<div class="col-lg-12">
				<b-card header="" border-variant="light">
					<div class="row">
						<div class="col-md-12 col-sm-12 text-right header-menu-nav">
							<router-link :to="{ name: 'Add new Job' }" class="btn btn-link">
								<i class="fa fa-plus-square" title="Add New Job" style="font-size:20px;  color: black;"></i>
							</router-link>
						</div>
					</div>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Asset Name</th>
								<th>job Type</th>
								<th>Allocated Company</th>
								<th>Technician Name</th>
								<th>Job Priority</th>
								
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="job in jobs" v-bind:key="job.id">
								<td>{{ job.asset.name }}</td>
								<td>{{ job.jobType.name }}</td>
								<td>{{ job.company.name }}</td>
								<td>{{ job.user.first_name +' '+ job.user.last_name }}</td>
								<td>{{ job.priority.name }}</td>
								
								
								<td>
									<router-link :to="{ path: `/job/${job.id}/new`}">
										<i class="fa fa-edit fa-lg" title="Edit Job" style="font-size:20px;  color: black;"></i>
									</router-link>
										<router-link :to="{ path:`/jobs`}">
										<span v-on:click="erase(job.id)">
											<i class="fa fa-trash fa-lg" title="Delete Job" style="font-size:20px;  color: black;"></i>
										</span>
									</router-link>
								</td>
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
				jobs: []
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
            this.$swal('job Deleted Successfully');
            },
			fetch() {
				this.$http.get(`/api/jobs`,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.jobs = response.data.data
						console.log("jobs", this.jobs)
					})
					.catch(err => console.log(err));
			},
			erase(id) {
				// if (confirm("Are You Sure?")) {
				// 	this.$http.delete(`/api/job/${id}`)
				// 	.then(response => {
				// 		alert("Removed");
				// 		this.fetch();
				// 	})
				// 	.catch(err => console.log(err));
				// }
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
						this.$http.delete(`/api/job/${id}`, {
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
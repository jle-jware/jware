<template>
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-sm-12">
				<form enctype="multipart/form-data" autocomplete="off" @submit.prevent="submitForm()">
					<div class="card border-0">
						<div class="form-group form-actions">
							<button type="button" @click.prevent="goToPreviousPage()" class="btn btn-sm btn-warning pull-right">Back</button>
							<button type="button" @click.prevent="submit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="form-group col-sm-4">
									<label for>Select Utilization Type *</label>
									<multiselect v-model="selectedUtilizationType" :class="{ 'is-danger': !assetUtilization.utilization_type_id }" :options="utilizationTypes"
									 @select="onSelectUtilizationType" track-by="id" label="utilization_type" placeholder="Select Utilization Type" selectLabel
									 deselectLabel selectedLabel="Remove" v-validate="'required'"></multiselect>
								</div>
								<div class="form-group col-sm-4">
									<b-form-fieldset label="Date *">
										<datetime v-model="assetUtilization.date" type="date" input-format="DD-MM-YYYY" wrapper-class="my-wrapper-class"
										 input-class="form-control" placeholder="Select date" moment-locale="es" :i18n="{ok:'De acuerdo', cancel:'Cancelar'}"
										 :disabled-dates="['2017-09-07', ['2017-09-25', '2017-10-05']]" zone="Pacific/Auckland" value-zone="Pacific/Auckland" max-date="2017-12-10" min-date="2017-07-10"
										 monday-first auto-continue auto-close required></datetime>
									</b-form-fieldset>
								</div>
                                <div class="form-group col-sm-4">
									<b-form-fieldset label="Utilization Value">
										<b-form-input type="text" v-model="assetUtilization.value" placeholder="Enter Cup Count"></b-form-input>
									</b-form-fieldset>
								</div>
							</div>
							<div class="row">
                                <div class="form-group col-sm-3">
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" v-model="assetUtilization.clear_calculation"  name="is_user" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Mother Board Change</span>
                                    </label>
                                </div>
                                <div class="form-group col-sm-3">
                                    <!-- <input type="checkbox" placeholder="Key Contact?" class="form-control" v-model="user.key_contact" value="1" name="key_contact"> -->
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" v-model="assetUtilization.parts_changed"  name="key_contact" class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Filter Changed</span>
                                    </label>
                                </div>
                            </div>
							<!-- <div class="row">
								<div class="form-group col-sm-6">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>Recent Cup Counts</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="assetUtilization in assetUtilizations" v-bind:key="assetUtilization.id">
												<td>{{ assetUtilization.value }}</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div> -->
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</template>

<script>
	import Multiselect from "vue-multiselect"
	import { Datetime } from "vue-datetime"
	export default {
		name: "forms",
		components: { Multiselect, Datetime },
		data() {
			return {
				utilizationTypes: [],
				assetUtilization: {
					date: new Date().toISOString(),
					
				},
				job: {
					id: ''
				},
				edit: false,
				selectedUtilizationType: { id: null, utilization_type: "Select Utilization Type" },
				assetId: null
			};
		},
		created() {
			if (!this.$store.state.user) this.$router.push("/login")
			this.assetUtilization.account_id = this.$store.state.user.account_id


			if(this.$route.query.asset){
				this.assetUtilization.asset_id = this.$route.query.asset
				//this.fetchUtilizationsByAsset(this.assetUtilization.asset_id)
			}
			else if(this.$route.query.job){
				this.assetUtilization.job_id = this.$route.query.job
				this.fetchJobById(this.assetUtilization.job_id)
			}

			if (this.$route.path.indexOf("create") > -1) {
				this.fetchUtilizationTypes()
			} else {
				this.edit = true
				this.fetchById(this.$route.params.id)
			}
		},
		methods: {
			submit() {
				this.$validator.validate().then(result => {
					if (result) this.submitForm()
				});
			},
			addMilestoneType() {
				this.$refs.MilestoneTypesModal.show()
			},
			submitMilestoneType() {
				this.assetUtilizationType.created_by = this.$store.state.user.id
				this.assetUtilizationType.account_id = this.$store.state.user.account_id

				this.$http
					.post(`/api/assetUtilizationType`, this.assetUtilizationType, {
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						alert("Milestone Type Added")
						this.fetchUtilizationTypes()
					})
					.catch(error => {
						if (error.response) {
							console.log(error.response)
							if (error.response.status === 401) {
								this.$router.push("/login")
							}
						}
					});
			},
			// submitForm() {
			// 	self = this;
			// 	if (this.edit) {
			// 		this.assetUtilization.updated_by = this.$store.state.user.id
			// 		this.$http
			// 			.put(`/api/assetUtilization`, this.assetUtilization, {
			// 				headers: {
			// 					Authorization: `Bearer ${this.$store.state.token}`
			// 				}
			// 			})
			// 			.then(response => {
			// 				console.log(response)
			// 				if(response.status == 204){
			// 					alert("The already exists, please choose another date")
			// 				}
			// 				else{
			// 					window.history.back()
			// 				}
			// 				alert("Updated");
			// 				this.$router.push({ path: `/job/${this.assetUtilization.job_id}` })
			// 				NProgress.done();
			// 			})
			// 			.catch(error => {
			// 				if (error.response) {
			// 					console.log(error.response)
			// 					if (error.response.status === 401) {
			// 						this.$router.push("/login")
			// 					}
			// 				}
			// 			})
			// 	} else {
			// 		this.assetUtilization.created_by = this.$store.state.user.id
			// 		this.$http
			// 			.post(`/api/assetUtilization`, this.assetUtilization, {
			// 				headers: {
			// 					Authorization: `Bearer ${this.$store.state.token}`
			// 				}
			// 			})
			// 			.then(response => {
			// 				console.log(response.status)
			// 				if(response.status == 204){
			// 					alert("The already exists, please choose another date")
			// 				}
			// 				else{
			// 					window.history.back()
			// 				}	
			// 			})
			// 			.catch(error => {
			// 				if (error.response) {
			// 					console.log(error.response);
			// 					if (error.response.status === 401) {
			// 						this.$router.push("/login");
			// 					}
			// 				}
			// 			})
			// 	}
			// },
			submitForm() {
				self = this;
				if (this.edit) {
					this.assetUtilization.updated_by = this.$store.state.user.id
					this.$http
						.put(`/api/assetUtilization`, this.assetUtilization, {
							headers: {
								Authorization: `Bearer ${this.$store.state.token}`
							}
						})
						.then(response => {
							alert("Updated")
							NProgress.done()
							window.history.back()
						})
						.catch(error => {
							if (error.response) {
								console.log(error.response)
								if (error.response.status === 401) {
									this.$router.push("/login")
								}
							}
						})
				} else {
					this.assetUtilization.updated_by = this.$store.state.user.id
					this.$http
						.post(`/api/assetUtilization`, this.assetUtilization, {
							headers: {
								Authorization: `Bearer ${this.$store.state.token}`
							}
						})
						.then(response => {
							// console.log(response.status)
							// if(response.status == 204){
							// 	alert("The already exists, please choose another date")
							// }
							// else{
							window.history.back()
							// }
							//alert("Asset Utilization Added")
						})
						.catch(error => {
							if (error.response) {
								console.log(error.response);
								if (error.response.status === 401) {
									this.$router.push("/login");
								}
							}
						})
				}
			},
			fetchById(id) {
				this.$http
					.get(`/api/assetUtilization/${id}`, {
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.assetUtilization = response.data.data
						console.log("assetUtilization", this.assetUtilization)
						this.fetchUtilizationTypes()
						this.fetchJobById(this.assetUtilization.job_id)
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
			fetchUtilizationTypes() {
				NProgress.start();
				this.$http
					.get(`/api/utilizationTypes`, {
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.utilizationTypes = response.data.data
						console.log("utilizationTypes", this.utilizationTypes)
						if (this.edit) {
							this.selectedUtilizationType = this.utilizationTypes.find(
								x => parseInt(x.id) === parseInt(this.assetUtilization.utilization_type_id)
							)
						}
						NProgress.done()
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
			onSelectUtilizationType(selectedOption, id) {
				if (selectedOption) {
					console.log(selectedOption.id)
					this.assetUtilization.utilization_type_id = selectedOption.id
				}
			},
			fetchJobById(id) {
				this.$http.get(`/api/job/${id}`,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.job = response.data.data
						this.assetUtilization.asset_id = this.job.asset_id
						//this.fetchUtilizationsByAsset(this.job.asset_id)
						//console.log('asset id',this.assetUtilization.asset_id)
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
			fetchUtilizationsByAsset(id) {
				NProgress.start()
				this.$http
					.get(`/api/asset/${id}/assetUtilizations`, {
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.assetUtilizations = response.data.data
						console.log("all assetUtilization", this.assetUtilizations)
						NProgress.done()
					})
					.catch(error => {
						if (error.response) {
							console.log(error.response)
							if (error.response.status === 401) {
								this.$router.push("/login")
							}
						}
					});
			},
			goToPreviousPage(){
				if(this.$route.query.asset){
					this.$router.push(this.$router.push(`/asset/${this.$route.query.asset}`))
				}
				else if(this.$route.query.job){
					this.$router.push(this.$router.push(`/job/${this.$route.query.job}`))
				}
			}
		},
	};
</script>
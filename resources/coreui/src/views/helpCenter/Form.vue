<template>
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-sm-12">
				<form enctype="multipart/form-data" autocomplete="off" @submit.prevent="submitForm()">
					<div class="card border-0">
						<div class="form-group form-actions">
							 <router-link :to="{ path: '/helpCenters/'}">
                                    <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                                </router-link>
							<button v-if="$store.state.user.contact_type != 'Technician' && 'Manager'" type="button" @click.prevent="submit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="form-group col-sm-12">
									<b-form-fieldset label="Type *">
                                        <b-form-input type="text" :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit" :class="errors.has('type') ? 'form-control is-invalid':'form-control'" v-validate="'required'"
                                            name="type" v-model="helpCenter.type" placeholder="Please enter type"></b-form-input>
                                            <span class="is-danger">{{ errors.first('type') }}</span>
									</b-form-fieldset>	
								</div>
							</div>
							<div class="row">
								<div class="form-group col-sm-6">
									 <label for>URL *</label>
                                	<wysiwyg  v-model="helpCenter.url" v-validate="'required'" name="url" data-vv-as="url" type="text">
									</wysiwyg>
                                <span class="is-danger">{{ errors.first('url') }}</span>
								</div>
								<div class="form-group col-sm-6">
									 <label for>Comment</label>
                                	<wysiwyg  v-model="helpCenter.comment"  name="comment" data-vv-as="comment" type="text">
									</wysiwyg>
								</div>
							</div>
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
				helpCenter: {
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
			this.helpCenter.account_id = this.$store.state.user.account_id


			if(this.$route.query.asset){
				this.helpCenter.asset_id = this.$route.query.asset
				//this.fetchUtilizationsByAsset(this.helpCenter.asset_id)
			}
			else if(this.$route.query.job){
				this.helpCenter.job_id = this.$route.query.job
				//this.fetchJobById(this.helpCenter.job_id)
			}

			if (this.$route.path.indexOf("create") > -1) {
				//fetchUtilizationTypes()
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
				this.helpCenterType.created_by = this.$store.state.user.id
				this.helpCenterType.account_id = this.$store.state.user.account_id

				this.$http
					.post(`/api/helpCenterType`, this.helpCenterType, {
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						//alert("Milestone Type Added")
						//this.fetchUtilizationTypes()
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
			submitForm() {
				self = this;
				if (this.edit) {
					this.helpCenter.updated_by = this.$store.state.user.id
					// this.helpCenter.value_per_day = cupCountPerDay;
					this.$http
						.put(`/api/helpCenter`, this.helpCenter, {
							headers: {
								Authorization: `Bearer ${this.$store.state.token}`
							}
						})
						.then(response => {
							//alert("Updated");
							// this.$router.push({ path: `/job/${this.helpCenter.job_id}` })
							// NProgress.done();
                            window.history.back()
						})
						.catch(error => {
							if (error.response) {
								console.log(error.response)
								if (error.response.status === 401) {
									this.$router.push("/login")
								}
							}
						});
				} else {
					this.helpCenter.created_by = this.$store.state.user.id
					this.$http
						.post(`/api/helpCenter`, this.helpCenter, {
							headers: {
								Authorization: `Bearer ${this.$store.state.token}`
							}
						})
						.then(response => {
							//alert("Saved");
							//this.$router.push({ path: `/job/${this.helpCenter.job_id}` })
							window.history.back()
						})
						.catch(error => {
							if (error.response) {
								console.log(error.response);
								if (error.response.status === 401) {
									this.$router.push("/login");
								}
							}
						});
				}
			},
			fetchById(id) {
				this.$http
					.get(`/api/helpCenter/${id}`, {
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.helpCenter = response.data.data
						console.log("helpCenter", this.helpCenter)
						//this.fetchUtilizationTypes()
						//this.fetchJobById(this.helpCenter.job_id)
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
								x => parseInt(x.id) === parseInt(this.helpCenter.utilization_type_id)
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
					this.helpCenter.utilization_type_id = selectedOption.id
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
						this.helpCenter.asset_id = this.job.asset_id
						//this.fetchUtilizationsByAsset(this.job.asset_id)
						//console.log('asset id',this.helpCenter.asset_id)
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
					.get(`/api/asset/${id}/helpCenters`, {
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.helpCenters = response.data.data
						console.log("all helpCenter", this.helpCenters)
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
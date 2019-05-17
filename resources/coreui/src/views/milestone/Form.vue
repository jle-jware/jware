<template>
	<div class="animated fadeIn">
		<div class="row">
			<div class="col-sm-12">
				<form enctype="multipart/form-data" autocomplete="off" @submit.prevent="submitForm">
					<div class="card border-0">
						<div class="form-group form-actions">
							<button type="button" @click.prevent="goToPreviousPage()" class="btn btn-sm btn-warning pull-right">Back</button>
							<button type="button" @click.prevent="submit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="form-group col-sm-6">
									<label for>Select Milestone Type *</label>
									<multiselect v-model="selectedMilestoneType" :class="{ 'is-danger': !milestone.milestone_type_id }" :options="milestoneTypes"
									 @select="onSelectMilestoneType" track-by="id" label="name" placeholder="Select Milestone Type" selectLabel
									 deselectLabel selectedLabel="Remove" v-validate="'required'"></multiselect>
									<a @click.prevent="addMilestoneType()" href="#">Add New Milestone Type</a>
								</div>
								<div class="form-group col-sm-6">
									<b-form-fieldset label="Date *">
										<datetime v-model="milestone.date" type="date" input-format="DD-MM-YYYY" wrapper-class="my-wrapper-class"
										 input-class="form-control" placeholder="Select date" moment-locale="es" :i18n="{ok:'De acuerdo', cancel:'Cancelar'}"
										 :disabled-dates="['2017-09-07', ['2017-09-25', '2017-10-05']]" zone="Pacific/Auckland" value-zone="Pacific/Auckland" max-date="2017-12-10" min-date="2017-07-10"
										 monday-first auto-continue auto-close required></datetime>
									</b-form-fieldset>
								</div>
							</div>
							<!-- <div class="row">
								<div class="form-group col-sm-6">
									<b-form-fieldset label="Cup Counts">
										<b-form-input type="text" v-model="milestone.cup_count" placeholder="Enter Cup Count"></b-form-input>
									</b-form-fieldset>
								</div>
								<div class="form-group col-sm-6">
									<br>
									<label class="custom-control custom-checkbox">
										<input type="checkbox" v-model="milestone.test_tag" class="custom-control-input">
										<span class="custom-control-indicator"></span>
										<span class="custom-control-description">Completed Test & Tag</span>
									</label>
								</div>
							</div> -->
							<div class="row">
								<!-- <div class="form-group col-sm-6">
									<b-form-textarea name="description" id="textarea1" v-model="milestone.description" placeholder="Description"
									 :rows="3" :max-rows="6"></b-form-textarea>
								</div> -->
								<div class="form-group col-sm-12">
									<b-form-textarea id="textarea1" placeholder="Comments" v-model="milestone.comment" :rows="3" :max-rows="6"></b-form-textarea>
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
											<tr v-for="milestone in milestones" v-bind:key="milestone.id">
												<td>{{ milestone.cup_count }}</td>
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
		<b-modal ref="MilestoneTypesModal" size="lg" id="modal-center" @ok="submitMilestoneType()" centered title="Add Asset Model Information">
			<div class="row">
				<div class="col-sm-12">
					<div class="row">
						<div class="form-group col-sm-12">
							<b-form-fieldset label="Milestone Type">
								<b-form-input type="text" name="name" v-model="milestoneType.name" placeholder="Please enter"></b-form-input>
							</b-form-fieldset>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-12">
							<b-form-textarea id="textarea1" v-model="milestoneType.comment" placeholder="Comments" :rows="3" :max-rows="6"></b-form-textarea>
						</div>
					</div>
				</div>
			</div>
		</b-modal>
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
				milestoneTypes: [],
				milestones: [],
				milestone: {
					date: new Date().toISOString(),
					cup_count: 0,
					days: 0,
					cupCountPerDay: "",
				},
				milestoneType: {
					name: ""
				},
				job: {
					id: ''
				},
				edit: false,
				selectedMilestoneType: { id: null, name: "Select Milestone Type" },
				assetId: null
			};
		},
		created() {
			if (!this.$store.state.user) this.$router.push("/login")
			this.milestone.account_id = this.$store.state.user.account_id


			if(this.$route.query.asset){
				this.milestone.asset_id = this.$route.query.asset
				this.fetchMilestonesByAsset(this.milestone.asset_id)
			}
			else if(this.$route.query.job){
				this.milestone.job_id = this.$route.query.job
				this.fetchJobById(this.milestone.job_id)
			}

			if (this.$route.path.indexOf("create") > -1) {
				this.fetchMilestoneTypes()
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
				this.milestoneType.created_by = this.$store.state.user.id
				this.milestoneType.account_id = this.$store.state.user.account_id

				this.$http
					.post(`/api/milestoneType`, this.milestoneType, {
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						//alert("Milestone Type Added")
						this.fetchMilestoneTypes()
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
					this.milestone.updated_by = this.$store.state.user.id
					// this.milestone.cup_count_per_day = cupCountPerDay;
					this.$http
						.put(`/api/milestone`, this.milestone, {
							headers: {
								Authorization: `Bearer ${this.$store.state.token}`
							}
						})
						.then(response => {
							//alert("Milestone Updated");
							// this.$router.push({ path: `/job/${this.milestone.job_id}` })
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
					this.milestone.created_by = this.$store.state.user.id
					this.$http
						.post(`/api/milestone`, this.milestone, {
							headers: {
								Authorization: `Bearer ${this.$store.state.token}`
							}
						})
						.then(response => {
							//alert("Milestone Saved");
							//this.$router.push({ path: `/job/${this.milestone.job_id}` })
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
					.get(`/api/milestone/${id}`, {
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.milestone = response.data.data
						console.log("milestone", this.milestone)
						this.fetchMilestoneTypes()
						this.fetchJobById(this.milestone.job_id)
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
			fetchMilestoneTypes() {
				NProgress.start();
				this.$http
					.get(`/api/milestoneTypes`, {
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.milestoneTypes = response.data.data
						console.log("milestoneTypes", this.milestoneTypes)
						if (this.edit) {
							this.selectedMilestoneType = this.milestoneTypes.find(
								x => parseInt(x.id) === parseInt(this.milestone.milestone_type_id)
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
			onSelectMilestoneType(selectedOption, id) {
				if (selectedOption) {
					console.log(selectedOption.id);
					this.milestone.milestone_type_id = selectedOption.id;
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
						this.milestone.asset_id = this.job.asset_id
						this.fetchMilestonesByAsset(this.job.asset_id)
						//console.log('asset id',this.milestone.asset_id)
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
			fetchMilestonesByAsset(id) {
				NProgress.start()
				this.$http
					.get(`/api/asset/${id}/milestones`, {
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(response => {
						this.milestones = response.data.data
						console.log("all milestone", this.milestones)
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
		computed: {
			// cupCountPerDay() {
			//   return this.milestone.cup_count / this.milestone.days;
			// },
			cupCountPerDay: {
				get() {
					//this function will determine what is displayed in the input
					return this.milestone.cup_count / this.milestone.days
				},
				set(newVal) {
					//this function will run whenever the input changes
					this.milestone.cup_count_per_day = newVal
				}
			}
		}
	};
</script>
<template>
	<div class="animated fadeIn">
		<div class="row">
			<!--/.col-->
			<div class="col-lg-12">
				<b-card header="" border-variant="light">
					<div class="row">
						<div class="col-lg-4"></div>
						<div class="col-lg-8">
							<div >Short Listed {{sortedjobSheet.length}} Total {{pagination.total}} items, Per page {{pagination.per_page}}, Showing {{pagination.from}} 〜 {{pagination.to}}</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3 col-sm-12">
							<input type="text" class="form-control" v-model="search.global" v-on:keydown="load(1)" v-on:keyup.enter="load(1)" placeholder="Search For All Records" title="Type and press enter to search">
						</div>
						<div class="col-md-2 col-sm-12">
							<button class="btn btn-link" @click.prevent="clearSearch" title="Clear Search">
								<i class="fa fa-window-close icon"></i>
							</button>
						</div>
						<div class="col-md-7 col-sm-12 text-right header-menu-nav">
							<slot name="navigations"></slot>

							<router-link v-if="$store.state.user.contact_type != 'Technician'" :to="{ path: '/jobSheet/new/create' }" class="btn btn-link">
								<i class="fa fa-plus-square icon" title="Add New Job"></i>
							</router-link>

							<button v-if="$store.state.user.contact_type != 'Technician'" :disabled="!selectedIds.length" class="btn btn-link" @click.prevent="erase" title="Delete Jobs">
								<i class="fa fa-trash icon"></i>
							</button>
							<router-link :to="{ path: '/close-jobSheets' }" class="btn btn-link">
								<i class="fa fa-archive icon" title="See Close Jobs"></i>
							</router-link>
							<b-dropdown variant="link" no-caret>
								<template slot="button-content">
									<i class="fas fa-columns icon" title="Customize Columns"></i>
								</template>
								<div class="selected-columns">
									<li v-for="(column, index) in columns" :key="index">
										<b-form-checkbox v-model="selectedColumns" @change="columnSelect" :value="column.name">{{column.label}}</b-form-checkbox>
									</li>
								</div>
							</b-dropdown>
							<download-excel :data="sortedjobSheet" class="btn">
								<i class="fa fa-download icon" title="Export Jobs"></i>
							</download-excel>
						</div>
						<div class="col-md-12 scrollme">
							<table class="table table-striped table-responsive">
								<thead>
									<!-- Column Sort -->
									<tr>
										<th v-if="checkColumnVisibilityStatus('all')">
											All
										</th>
										<th v-if="checkColumnVisibilityStatus('job_number')" @click="sortByColumn('job_number')" class="text-center sortable">
											Job Number
											<i :class="setSelectedColumnSortIcon('job_number')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('job_type')" @click="sortByColumn('job_type')" class="text-center sortable">
											Job Type
											<i :class="setSelectedColumnSortIcon('job_type')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('asset')" @click="sortByColumn('asset')" class="text-center sortable">
											Asset Model
											<i :class="setSelectedColumnSortIcon('asset')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('serial')" @click="sortByColumn('serial')" class="text-center sortable">
											Serial
											<i :class="setSelectedColumnSortIcon('serial')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('date')" @click="sortByColumn('date')" class="text-center sortable">
											Date
											<i :class="setSelectedColumnSortIcon('date')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('onstie')" @click="sortByColumn('onstie')" class="text-center sortable">
											On Site Time (hr:mins)
											<i :class="setSelectedColumnSortIcon('onstie')"></i>
										</th>
										<!-- <th v-if="checkColumnVisibilityStatus('asset')" @click="sortByColumn('asset')" class="text-center sortable">
											Asset Model
											<i :class="setSelectedColumnSortIcon('asset')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('placement_asset')" @click="sortByColumn('placement_asset')" class="text-center sortable">
											Placement Asset Model
											<i :class="setSelectedColumnSortIcon('placement_asset')"></i>
										</th>
										
										<th v-if="checkColumnVisibilityStatus('date')" @click="sortByColumn('date')" class="text-center sortable">
											 Serial
											<i :class="setSelectedColumnSortIcon('date')"></i>
										</th>
										
										<th v-if="checkColumnVisibilityStatus('asset_bmb')" @click="sortByColumn('asset_bmb')" class="text-center sortable">
											BMB
											<i :class="setSelectedColumnSortIcon('asset_bmb')"></i>
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('asset_name')" @click="sortByColumn('asset_name')" class="text-center sortable">
											Asset
											<i :class="setSelectedColumnSortIcon('asset_name')"></i>
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('contractor')" @click="sortByColumn('contractor')" class="text-center sortable">
											Contractor
											<i :class="setSelectedColumnSortIcon('contractor')"></i>
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('swmc')" @click="sortByColumn('swmc')" class="text-center sortable">
											Customer
											<i :class="setSelectedColumnSortIcon('swmc')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('location_address')" @click="sortByColumn('location_address')" class="text-center sortable">
											Address
											<i :class="setSelectedColumnSortIcon('location_address')"></i>
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('swmc')" @click="sortByColumn('swmc')" class="text-center sortable">
											Contractor
											<i :class="setSelectedColumnSortIcon('swmc')"></i>
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('jobSheet_status')" @click="sortByColumn('jobSheet_status')" class="text-center sortable">
											Status
											<i :class="setSelectedColumnSortIcon('jobSheet_status')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('jobSheet_priority')" @click="sortByColumn('jobSheet_priority')" class="text-center sortable">
											Priority
											<i :class="setSelectedColumnSortIcon('jobSheet_priority')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('job_number')" @click="sortByColumn('job_number')" class="text-center sortable">
											Description
											<i :class="setSelectedColumnSortIcon('job_number')"></i>
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('required_date')" @click="sortByColumn('required_date')" class="text-center sortable">
											Required Date
											<i :class="setSelectedColumnSortIcon('required_date')"></i>
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('jobSheet_raised_date')" @click="sortByColumn('jobSheet_raised_date')" class="text-center sortable">
											Raised Date
											<i :class="setSelectedColumnSortIcon('jobSheet_raised_date')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('tech_name')" @click="sortByColumn('tech_name')" class="text-center sortable">
											Technician
											<i :class="setSelectedColumnSortIcon('tech_name')"></i>
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('created_at')" @click="sortByColumn('created_at')" class="text-center sortable">
											Created Date
											<i :class="setSelectedColumnSortIcon('created_at')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('updated_at')" @click="sortByColumn('updated_at')" class="text-center sortable">
											Updated Date
											<i :class="setSelectedColumnSortIcon('updated_at')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('created_name')" @click="sortByColumn('created_name')" class="text-center sortable">
											Created by
											<i :class="setSelectedColumnSortIcon('created_name')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('updated_name')" @click="sortByColumn('updated_name')" class="text-center sortable">
											Updated by
											<i :class="setSelectedColumnSortIcon('updated_name')"></i>
										</th> -->
									</tr>
									<!-- Column Search -->
									<tr>
										<th v-if="checkColumnVisibilityStatus('all')">
											<b-form-checkbox name="checkAll" title="Check all" @change="allRowSelect" v-model="all"></b-form-checkbox>
										</th>
										<th v-if="checkColumnVisibilityStatus('job_number')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.job_number" />
										</th>
										<th v-if="checkColumnVisibilityStatus('job_type')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.job_type" />
										</th>
										<th v-if="checkColumnVisibilityStatus('asset')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.asset" />
										</th>
										<th v-if="checkColumnVisibilityStatus('serial')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.serial" />
										</th>
										<th v-if="checkColumnVisibilityStatus('date')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.date" />
										</th>
										<th v-if="checkColumnVisibilityStatus('onstie')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.onstie" />
										</th>
										<!-- <th v-if="checkColumnVisibilityStatus('onstie')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.onstie" />
										</th>
										<th v-if="checkColumnVisibilityStatus('asset')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.asset" />
										</th>
										<th v-if="checkColumnVisibilityStatus('placement_asset')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.placement_asset" />
										</th>
										<th v-if="checkColumnVisibilityStatus('date')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.date" />
										</th>
										<th v-if="checkColumnVisibilityStatus('asset_bmb')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.asset_bmb" />
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('asset_name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.asset_name" />
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('contractor')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.contractor" />
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('swmc')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.swmc" />
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('location_address')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.location_address" />
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('swmc')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.swmc" />
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('jobSheet_status')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.jobSheet_status" />
										</th>
										<th v-if="checkColumnVisibilityStatus('jobSheet_priority')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.jobSheet_priority" />
										</th>
										<th v-if="checkColumnVisibilityStatus('job_number')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.job_number" />
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('required_date')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.required_date" />
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('jobSheet_raised_date')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.jobSheet_raised_date" />
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('tech_name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.tech_name" />
										</th> -->
										<!-- <th v-if="checkColumnVisibilityStatus('created_at')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.created_at" />
										</th>
										<th v-if="checkColumnVisibilityStatus('updated_at')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.updated_at" />
										</th>
										<th v-if="checkColumnVisibilityStatus('created_name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.created_name" />
										</th>
										<th v-if="checkColumnVisibilityStatus('updated_name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.updated_name" />
										</th> -->
									</tr>
								</thead>
								<!-- Table Body -->
								<tbody>
									<tr v-for="(jobSheet, index) in sortedjobSheet" :key="index">
										<td v-if="checkColumnVisibilityStatus('all')">
											<b-form-checkbox v-model="selectedIds" @change="rowSelect" :value="jobSheet.id">
											</b-form-checkbox>
										</td>
										<!-- <td v-if="checkColumnVisibilityStatus('job_number')">
											<router-link :to="{ path: `/jobSheet/${jobSheet.id}/new`}" v-html="jobSheet.job_number"></router-link>
										</td> -->
										<td v-if="checkColumnVisibilityStatus('job_number')">{{ jobSheet.job_number ? jobSheet.job_number:'' }}</td>
										<td v-if="checkColumnVisibilityStatus('job_type')">{{ jobSheet.job_type ? jobSheet.job_type:'' }}</td>
										<td v-if="checkColumnVisibilityStatus('asset')">{{ jobSheet.job_type ? jobSheet.asset:'' }}</td>
										<td v-if="checkColumnVisibilityStatus('serial')">{{ jobSheet.job_type ? jobSheet.serial:'' }}</td>
										<td v-if="checkColumnVisibilityStatus('date')">{{ jobSheet.job_type ? jobSheet.date:'' }}</td>
										<td v-if="checkColumnVisibilityStatus('onstie')">{{ jobSheet.job_type ? jobSheet.onstie:'' }}</td>
										
										<!-- <td v-if="checkColumnVisibilityStatus('onstie')">
											<div>
												<router-link :to="{ path: `/jobSheet/${jobSheet.id}/new`}" v-html="jobSheet.onstie"></router-link>
											</div>
										</td>
										<td v-if="checkColumnVisibilityStatus('asset')">
											{{ jobSheet.asset ? jobSheet.asset:'' }}
										</td>
										<td v-if="checkColumnVisibilityStatus('placement_asset')">
											{{ jobSheet.placement_asset ? jobSheet.placement_asset:'' }}
										</td>
										<td v-if="checkColumnVisibilityStatus('date')">
											<div>
												<router-link :to="{ path: `/jobSheet/${jobSheet.id}/new`}" v-html="jobSheet.date"></router-link>
											</div>
										</td>
										<td v-if="checkColumnVisibilityStatus('asset_bmb')">
											<div>
												<router-link :to="{ path: `/jobSheet/${jobSheet.id}/new`}" v-html="jobSheet.asset_bmb"></router-link>
											</div>
										</td> -->
										<!-- <td v-if="checkColumnVisibilityStatus('asset_bmb')">{{ jobSheet.asset_bmb ? jobSheet.asset_bmb:'' }}</td> -->
										<!-- <td v-if="checkColumnVisibilityStatus('onstie')">{{ jobSheet.onstie ? jobSheet.onstie:'' }}</td> -->
										<!-- <td v-if="checkColumnVisibilityStatus('job_number')">{{ jobSheet.job_number }}</td> -->
										<!-- <td v-if="checkColumnVisibilityStatus('asset_name')">
											{{ jobSheet.asset_name ? jobSheet.asset_name:'' }}
										</td> -->
										<!-- <td v-if="checkColumnVisibilityStatus('contractor')">{{ jobSheet.contractor }}</td> -->
										<!-- <td v-if="checkColumnVisibilityStatus('swmc')">{{ jobSheet.swmc ? jobSheet.swmc:'' }}</td> -->
										<!-- <td v-if="checkColumnVisibilityStatus('location_address')">
											<div class="fit-text">{{ jobSheet.location_address }}</div>
										</td> -->
										<!-- <td v-if="checkColumnVisibilityStatus('swmc')">{{ jobSheet.swmc ? jobSheet.swmc:'' }}</td> -->
										
										<!-- <td :class="getJobStatusType(jobSheet.jobSheet_status)" v-if="checkColumnVisibilityStatus('jobSheet_status')">
											{{ jobSheet.jobSheet_status ? jobSheet.jobSheet_status:'' }}
										</td>
										<td v-if="checkColumnVisibilityStatus('jobSheet_priority')">{{ jobSheet.jobSheet_priority ? jobSheet.jobSheet_priority:'' }}</td>
										<td v-if="checkColumnVisibilityStatus('job_number')">
											<div class="fit-text">
												<router-link :to="{ path: `/jobSheet/${jobSheet.id}/new`}" v-html="jobSheet.job_number"></router-link>
											</div>
										</td> -->
										<!-- <td v-if="checkColumnVisibilityStatus('required_date')">{{ jobSheet.required_date }}</td> -->
										<!-- <td v-if="checkColumnVisibilityStatus('jobSheet_raised_date')">{{ jobSheet.jobSheet_raised_date }}</td>
										<td v-if="checkColumnVisibilityStatus('tech_name')">{{ jobSheet.tech_name ? jobSheet.tech_name:'' }}</td> -->
										<!-- <td v-if="checkColumnVisibilityStatus('created_at')">{{ jobSheet.created_at ? jobSheet.created_at:'' }}</td>
										<td v-if="checkColumnVisibilityStatus('updated_at')">{{ jobSheet.updated_at ? jobSheet.updated_at:'' }}</td>
										<td v-if="checkColumnVisibilityStatus('created_name')">{{ jobSheet.created_name ? jobSheet.created_name:'' }}</td>
										<td v-if="checkColumnVisibilityStatus('updated_name')">{{ jobSheet.updated_name ? jobSheet.updated_name:'' }}</td> -->
									</tr>
								</tbody>
								<!-- Table Body End -->
							</table>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-1 justify-content-center align-self-center">
							<multiselect v-model="pagination.per_page" @select="onSelectPageCount" :options="pagination.perPageItems" :searchable="false" :show-labels="false" placeholder="Items / Page"></multiselect>
						</div>

						<div class="col-lg-3 justify-content-center align-self-center text-center">
							
						</div>

						<div class="col-lg-4 justify-content-center align-self-center text-center">
							<div>Short Listed {{sortedjobSheet.length}} Total {{pagination.total}} items, Per page {{pagination.per_page}}, Showing {{pagination.from}} 〜 {{pagination.to}}</div>
						</div>

						<div class="col-lg-4 justify-content-center align-self-center">
							<ul class="pagination pagination-custom pull-right">
								<li :class="{disabled: pagination.current_page <= 1}"><a href="#" @click.prevent="change(1)">&laquo;</a></li>
								<li :class="{disabled: pagination.current_page <= 1}"><a href="#" @click.prevent="change(pagination.current_page - 1)">&lt;</a></li>
								<li v-for="page in pages" :key="page" :class="{active: page === pagination.current_page}">
									<a href="#" @click.prevent="change(page)">{{page}}</a>
								</li>
								<li :class="{disabled: pagination.current_page >= pagination.last_page}"><a href="#" @click.prevent="change(pagination.current_page + 1)">&gt;</a></li>
								<li :class="{disabled: pagination.current_page >= pagination.last_page}"><a href="#" @click.prevent="change(pagination.last_page)">&raquo;</a></li>
							</ul>
						</div>
					</div>
				</b-card>
			</div>
			<!--/.col-->
		</div>
		<!--/.row-->
	</div>
</template>
<script>
	import Multiselect from 'vue-multiselect'
	export default {
		name: 'list',
		components: {
			Multiselect
		},
		data() {
			return {
				jobSheets: [],
				currentSortColumn: 'name',
				currentSortDirection: 'asc',
				search: {
					global: '',
					job_number: '',
					asset: '',
					date: '',
					// jobSheet_raised_date: '',
					// location_address: '',
					serial:'',
					// date:'',
					// asset_bmb:'',
					// jobSheet_status:'',
					// onstie:'',
					// jobSheet_priority:'',
					//swmc:'',
					job_type:'',
					onstie:'',
					// asset:'',
					// placement_asset:'',
					// // created_at:'',
					// updated_at:'',
					// created_name:'',
					// updated_name:'',
					//contractor:'',
				},
				pagination: {
					per_page: 20,
					current_page: 1,
					last_page: 1,
					total: 1,
					from: 0,
					to: 0,
					perPageItems: [20,50,100,1500]
				},
				all: false,
				selectedIds: [],
				selectedColumns: [],
				columns: [
					{name : 'all', label : 'All'},
					{name : 'job_number', label : 'Job Number'},
					{name : 'onstie', label : 'On Site Time'},
					{name : 'job_type', label : 'Job Type'},
					{name : 'date', label : 'Date'},
					{name : 'asset', label : 'Asset Model'},
					{name : 'serial', label : 'Serial'},
					// {name : 'asset_name', label : 'Asset Name'},
					// {name : 'asset', label : 'Asset Model'},
					// {name : 'asset_bmb', label : 'BMB'},
					// {name : 'jobSheet_status', label : 'Status'},
					// {name : 'onstie', label : 'Type'},
					// {name : 'jobSheet_priority', label : 'Priority'},
					// {name : 'swmc', label : 'Customer'},
					// {name : 'tech_name', label : 'Technician Name'},
					// {name : 'placement_asset', label : 'Placement Asset Model'},
					// {name : 'created_at', label : 'Created Date'},
					// {name : 'updated_at', label : 'Updated Date'},
					// {name : 'created_name', label : 'Created By'},
					// {name : 'updated_name', label : 'Updated By'},
					// {name : 'contractor', label : 'Contractor'},
				]
			}
		},
		created() {
			console.log(this.$store.state.user)
			if (!this.$store.state.user)
				this.$router.push('/login')

			this.columns.forEach(column => {
				this.selectedColumns.push(column.name)
			})
		},
		mounted() {
			this.load(1)
		},
		methods: {
			showAlert() {
				this.$swal('jobSheet Deleted Successfully')
			},
			erase() {
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
						this.$http.delete(`/api/jobSheet/${this.selectedIds.join(',')}`, {
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
								this.load(1)
								this.selectedIds = []
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
			},
			sortByColumn: function (s) {
				if (s === this.currentSortColumn) {
					this.currentSortDirection = this.currentSortDirection === 'asc' ? 'desc' : 'asc';
				}
				this.currentSortColumn = s;
			},
			setSelectedColumnSortIcon(type) {
				if (this.currentSortDirection == 'asc' && this.currentSortColumn == type)
					return 'fa fa-arrow-down'
				else if (this.currentSortDirection == 'desc' && this.currentSortColumn == type)
					return 'fa fa-arrow-down open'
				else
					return 'fa fa-arrow-down open fa-disabled'
			},
			// Paginating the table data
			makePagination(meta) {
				let pagination = {
					per_page : this.pagination.per_page,
					current_page : meta.current_page,
					last_page : meta.last_page,
					total : meta.total,
					from : meta.from,
					to : meta.to,
					perPageItems : this.pagination.perPageItems
				}
				this.pagination = pagination;
			},
			allRowSelect() {
				var self = this;
				Vue.nextTick(function () {
					self.selectedIds = []

					if (self.all) {
						self.sortedjobSheet.forEach(jobSheet => {
							self.selectedIds.push(jobSheet.id.toString())
						})
					}
					else {
						if (self.sortedjobSheet.length == self.selectedIds.length) {
							self.selectedIds = []
						}
					}
				})
			},
			rowSelect: function () {
				var self = this;
				Vue.nextTick(function () {
					if (self.sortedjobSheet.length == self.selectedIds.length) {
						self.all = true
					}
					else {
						self.all = false
					}
				})
			},
			columnSelect: function () {
				var self = this;
				Vue.nextTick(function () {
					console.log(self.selectedColumns)
				})
			},
			load(page) {
				NProgress.start()
				let url = `/api/job-sheets-datatable`
				// let params = `?company=${this.$store.state.user.company.type == 'Owner' ?  '' : this.$store.state.user.company_id}&page=${page}&per_page=${this.pagination.per_page}&search=${this.search.global}`
				let params = `?page=${page}&per_page=${this.pagination.per_page}&search=${this.search.global}`
				this.$http.get(url + params,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(({ data }) => {
						console.log('jobSheets', data)
						this.jobSheets = data.data
						NProgress.done()
						this.makePagination(data.meta)
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
			change(page) {
				if (page >= 1 && page <= this.pagination.last_page) this.load(page)
			},
			perPageChanged(count){
				this.pagination.per_page = count
				this.load(1)
			},
			clearSearch(){
				if(this.search.global.length){
					this.search.global = ''
					this.load(1)
				}
				
				this.search.job_number = '',
				this.search.swmc = '',
				this.search.job_type = '',
				this.search.onstie = '',
				this.search.date = '',
				this.search.asset = '',
				this.search.serial = ''
				
			},
			onSelectPageCount(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption, id)
                    this.pagination.per_page = selectedOption
                    this.load(1)
                }
			},
			checkColumnVisibilityStatus(column){
				return this.selectedColumns.indexOf(column) !== -1 ? true : false
			},
			getJobStatusType(type){
				let response = null
				switch (type) {
					case 'Complete':
						response = 'jobSheet-status-warning'
						break
					case 'Allocated':
						response = 'jobSheet-status-danger'
						break
					case 'Open':
						response = 'jobSheet-status-danger'
						break
					case 'Received':
						response = 'jobSheet-status-danger'
						break
					case 'Close':
						response = 'jobSheet-status-success'
						break
					case 'Need Admin Approval':
						response = 'jobSheet-status-warning'
						break
				}
				return response
			}
		},
		computed: {
			filteredJobSheet: function () {
				var self = this
				return this.jobSheets.filter(function (jobSheet) {
					return jobSheet.job_number ? jobSheet.job_number.toString().indexOf(self.search.job_number.toString()) >= 0 : true
					})
					// .filter(function (jobSheet) {
					// 	return jobSheet.swmc ? jobSheet.swmc.toLowerCase().indexOf(self.search.swmc.toLowerCase()) >= 0 : true
					// })
					.filter(function (jobSheet) {
						return jobSheet.job_type ? jobSheet.job_type.toLowerCase().indexOf(self.search.job_type.toLowerCase()) >= 0 : true
					})
					.filter(function (jobSheet) {
						return jobSheet.onstie ? jobSheet.onstie.toString().indexOf(self.search.onstie.toString()) >= 0 : true
					})
					.filter(function (jobSheet) {
						return jobSheet.asset ? jobSheet.asset.toLowerCase().indexOf(self.search.asset.toLowerCase()) >= 0 : true
					})
					.filter(function (jobSheet) {
						return jobSheet.date ? jobSheet.date.toString().indexOf(self.search.date.toString()) >= 0 : true
					})
					.filter(function (jobSheet) {
						return jobSheet.serial ? jobSheet.serial.toString().indexOf(self.search.serial.toString()) >= 0 : true
					})
					// .filter(function (jobSheet) {
					// 	return jobSheet.asset_bmb ? jobSheet.asset_bmb.toString().indexOf(self.search.asset_bmb.toString()) >= 0 : true
					// })
					// .filter(function (jobSheet) {
					// 	return jobSheet.jobSheet_status ? jobSheet.jobSheet_status.toLowerCase().indexOf(self.search.jobSheet_status.toLowerCase()) >= 0 : true
					// })
					// .filter(function (jobSheet) {
					// 	return jobSheet.onstie ? jobSheet.onstie.toLowerCase().indexOf(self.search.onstie.toLowerCase()) >= 0 : true
					// })
					// .filter(function (jobSheet) {
					// 	return jobSheet.jobSheet_priority ? jobSheet.jobSheet_priority.toLowerCase().indexOf(self.search.jobSheet_priority.toLowerCase()) >= 0 : true
					// })
					// // .filter(function (jobSheet) {
					// // 	return jobSheet.required_date ? jobSheet.required_date.toString().indexOf(self.search.required_date.toString()) >= 0 : true
					// // })
					// .filter(function (jobSheet) {
					// 	return jobSheet.jobSheet_raised_date ? jobSheet.jobSheet_raised_date.toString().indexOf(self.search.jobSheet_raised_date.toString()) >= 0 : true
					// })
					// .filter(function (jobSheet) {
					// 	return jobSheet.location_address ? jobSheet.location_address.toLowerCase().indexOf(self.search.location_address.toLowerCase()) >= 0 : true
					// })
					// .filter(function (jobSheet) {
					// 	return jobSheet.tech_name ? jobSheet.tech_name.toLowerCase().indexOf(self.search.tech_name.toLowerCase()) >= 0 : true
					// })
					// .filter(function (jobSheet) {
					// 	return jobSheet.placement_asset ? jobSheet.placement_asset.toLowerCase().indexOf(self.search.placement_asset.toLowerCase()) >= 0 : true
					// })
					// .filter(function (jobSheet) {
					// 	return jobSheet.created_at ? jobSheet.created_at.toLowerCase().indexOf(self.search.created_at.toLowerCase()) >= 0 : true
					// })
					// .filter(function (jobSheet) {
					// 	return jobSheet.updated_at ? jobSheet.updated_at.toLowerCase().indexOf(self.search.updated_at.toLowerCase()) >= 0 : true
					// })
					// .filter(function (jobSheet) {
					// 	return jobSheet.created_name ? jobSheet.created_name.toLowerCase().indexOf(self.search.created_name.toLowerCase()) >= 0 : true
					// })
					// .filter(function (jobSheet) {
					// 	return jobSheet.contractor ? jobSheet.contractor.toLowerCase().indexOf(self.search.contractor.toLowerCase()) >= 0 : true
					// })
					// .filter(function (jobSheet) {
					// 	return jobSheet.updated_name ? jobSheet.updated_name.toLowerCase().indexOf(self.search.updated_name.toLowerCase()) >= 0 : true
					// })
			},
			sortedjobSheet: function () {
				return this.filteredJobSheet.sort((a, b) => {
					let modifier = 1
					if (this.currentSortDirection === 'desc') modifier = -1

					let temp = `.${this.currentSortColumn}`
					if (eval(`a${temp}`) < eval(`b${temp}`)) return -1 * modifier
					if (eval(`a${temp}`) > eval(`b${temp}`)) return 1 * modifier
					return 0
				})
			},
			pages() {
				let start = _.max([this.pagination.current_page - 2, 1])
				let end = _.min([start + 5, this.pagination.last_page + 1])
				start = _.max([end - 5, 1])
				return _.range(start, end)
			},
		},

	}
</script>
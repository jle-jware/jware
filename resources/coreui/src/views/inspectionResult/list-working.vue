<template>
	<div class="animated fadeIn">
		<div class="row">
			<!--/.col-->
			<div class="col-lg-12">
				<b-card header="" border-variant="light">
					<div class="row">
						<div class="col-md-3 col-sm-12">
							<input type="text" class="form-control" v-model="search.global" v-on:keyup.enter="load(1)" placeholder="Type and press enter to search" title="Search Item">
						</div>
						<div class="col-md-9 col-sm-12 text-right header-menu-nav">
							<slot name="navigations"></slot>

							<button class="btn btn-link" @click.prevent="createInspectionJob()" title="Clear Search">
								<i class="fa fa-plus-square icon" title="Add Item"></i>
							</button>
							<button class="btn btn-link" @click.prevent="clearSearch" title="Clear Search">
								<i class="fas fa-eraser icon"></i>
							</button>

							<button :disabled="!selectedIds.length" class="btn btn-link" @click.prevent="erase" title="Delete Item">
								<i class="fa fa-trash icon"></i>
							</button>
							<b-dropdown variant="link" no-caret>
								<template slot="button-content">
									<i class="fas fa-columns icon"></i>
								</template>
								<div class="selected-columns">
									<li v-for="(column, index) in columns" :key="index">
										<b-form-checkbox v-model="selectedColumns" @change="columnSelect" :value="column.name">{{column.label}}</b-form-checkbox>
									</li>
								</div>
							</b-dropdown>
							<download-excel :data="sortedInspectionResult" class="btn">
								<i class="fa fa-download icon"></i>
							</download-excel>
						</div>
						<div class="col-md-12">
							<table class="table table-striped table-responsive">
								<thead>
									<!-- Column Sort -->
									<tr>
										<th v-if="checkColumnVisibilityStatus('all')">
											All
										</th>
										<th v-if="checkColumnVisibilityStatus('company_name')" @click="sortByColumn('company_name')" class="text-center sortable">
											Company Name
											<i :class="setSelectedColumnSortIcon('company_name')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('asset_name')" @click="sortByColumn('asset_name')" class="text-center sortable">
											Asset Name
											<i :class="setSelectedColumnSortIcon('asset_name')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('serial')" @click="sortByColumn('serial')" class="text-center sortable">
											Serial
											<i :class="setSelectedColumnSortIcon('serial')"></i>
										</th>
										<!-- <th v-if="checkColumnVisibilityStatus('bmb')" @click="sortByColumn('bmb')" class="text-center sortable">
											BMB
											<i :class="setSelectedColumnSortIcon('bmb')"></i>
										</th> -->
										<th v-if="checkColumnVisibilityStatus('asset_name')" @click="sortByColumn('asset_name')" class="text-center sortable">
											Location
											<i :class="setSelectedColumnSortIcon('asset_name')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('band')" @click="sortByColumn('band')" class="text-center sortable">
											Earth Bond
											<i :class="setSelectedColumnSortIcon('band')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('insulation')" @click="sortByColumn('insulation')" class="text-center sortable">
											Insulation
											<i :class="setSelectedColumnSortIcon('insulation')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('leakage')" @click="sortByColumn('leakage')" class="text-center sortable">
											Leakage
											<i :class="setSelectedColumnSortIcon('leakage')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('date')" @click="sortByColumn('date')" class="text-center sortable">
											Test & Tag Date
											<i :class="setSelectedColumnSortIcon('date')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('next_job_date')" @click="sortByColumn('next_job_date')" class="text-center sortable">
											Test & Tag Date 
											<i :class="setSelectedColumnSortIcon('next_job_date')"></i>
										</th>
										<!-- <th v-if="checkColumnVisibilityStatus('result_name')" @click="sortByColumn('result_name')" class="text-center sortable">
											QA Result
											<i :class="setSelectedColumnSortIcon('result_name')"></i>
										</th> -->
										<th v-if="checkColumnVisibilityStatus('comment')" @click="sortByColumn('comment')" class="text-center sortable">
											Comment
											<i :class="setSelectedColumnSortIcon('comment')"></i>
										</th>
									</tr>
									<!-- Column Search -->
									<tr>
										<th v-if="checkColumnVisibilityStatus('all')">
											<b-form-checkbox name="checkAll" title="Check all" @change="allRowSelect" v-model="all"></b-form-checkbox>
										</th>
										<th v-if="checkColumnVisibilityStatus('company_name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.company_name" />
										</th>
										<th v-if="checkColumnVisibilityStatus('asset_name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.asset_name" />
										</th>
										<th v-if="checkColumnVisibilityStatus('serial')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.serial" />
										</th>
										<!-- <th v-if="checkColumnVisibilityStatus('bmb')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.bmb" />
										</th> -->
										<th v-if="checkColumnVisibilityStatus('location')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.location" />
										</th>
										<th v-if="checkColumnVisibilityStatus('band')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.band" />
										</th>
										<th v-if="checkColumnVisibilityStatus('insulation')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.insulation" />
										</th>
										<th v-if="checkColumnVisibilityStatus('leakage')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.leakage" />
										</th>
										<th v-if="checkColumnVisibilityStatus('date')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.date" />
										</th>
										<th v-if="checkColumnVisibilityStatus('next_job_date')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.next_job_date" />
										</th>
										<!-- <th v-if="checkColumnVisibilityStatus('result_name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.result_name" />
										</th> -->
										<th v-if="checkColumnVisibilityStatus('comment')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.comment" />
										</th>
									</tr>
								</thead>
								<!-- Table Body -->
								<tbody>
									<tr v-for="(inspectionResult, index) in sortedInspectionResult" :key="index">
										<td v-if="checkColumnVisibilityStatus('all')">
											<b-form-checkbox v-model="selectedIds" @change="rowSelect" :value="inspectionResult.id">
											</b-form-checkbox>
										</td>
										<td v-if="checkColumnVisibilityStatus('company_name')">{{ inspectionResult.company_name }}</td>
										<td v-if="checkColumnVisibilityStatus('asset_name')">{{ inspectionResult.asset_name }}</td>
										<td v-if="checkColumnVisibilityStatus('serial')">{{ inspectionResult.serial }}</td>
										<!-- <td v-if="checkColumnVisibilityStatus('bmb')">{{ inspectionResult.bmb }}</td> -->
										<td v-if="checkColumnVisibilityStatus('location')">{{ inspectionResult.location }}</td>
										<td v-if="checkColumnVisibilityStatus('band')">{{ inspectionResult.band }}</td>
										<td v-if="checkColumnVisibilityStatus('insulation')">{{ inspectionResult.insulation }}</td>
										<td v-if="checkColumnVisibilityStatus('leakage')">{{ inspectionResult.leakage }}</td>
										<td v-if="checkColumnVisibilityStatus('date')">{{ inspectionResult.date }}</td>
										<td :class="checkDueJobs(inspectionResult.next_job_date)" v-if="checkColumnVisibilityStatus('next_job_date')">{{ inspectionResult.next_job_date }}</td>
										<!-- <td v-if="checkColumnVisibilityStatus('result_name')">{{ inspectionResult.result_name }}</td> -->
										<td v-if="checkColumnVisibilityStatus('comment')">{{ inspectionResult.comment }}</td>
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
							<div >Total {{pagination.total}} items, Per page {{pagination.per_page}}, Showing {{pagination.from}} 〜 {{pagination.to}}</div>
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
					<br>
					<div class="form-group form-actions">
					<!-- <router-link :to="{ name: 'Asset List'}">
						<button type="button" class="btn btn-sm btn-warning pull-left">Cancel</button>
					</router-link> -->
					<button type="button" @click.prevent="createInspectionJob()" style="margin-right:10px" class="btn btn-sm btn-primary pull-left">Create Jobs</button>
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
	import moment from 'moment'
	export default {
		name: 'list',
		components: {
			Multiselect
		},
		data() {
			return {
				inspectionResults: [],
				currentSortColumn: 'name',
				currentSortDirection: 'asc',
				search: {
					global: '',
					date: '',
					comment:'',
					result_name:'',
					next_job_date:'',
					company_name:'',
					asset_name:'',
					location:'',
					serial:'',
					bmb:'',
					band:'',
					insulation:'',
					leakage:''
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
					{name : 'date', label : 'date'},
					{name : 'comment', label : 'comment'},
					{name : 'result_name', label : 'QA Result'},
					{name : 'next_job_date', label : 'Next QA Date'},
					{name : 'company_name', label : 'Company Name'},
					{name : 'asset_name', label : 'Asset Name'},
					{name : 'location', label : 'Location Name'},
					{name : 'serial', label : 'Serial'},
					{name : 'band', label : 'Earth Bond'},
					{name : 'insulation', label : 'Insulation'},
					{name : 'leakage', label : 'Leakage Less Than Value'},
					{name : 'bmb', label : 'BMB'},
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
				this.$swal('Company Deleted Successfully')
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
						this.$http.delete(`/api/inspectionResult/${this.selectedIds.join(',')}`, {
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
						self.sortedInspectionResult.forEach(company => {
							self.selectedIds.push(company.id.toString())
						})
					}
					else {
						if (self.sortedInspectionResult.length == self.selectedIds.length) {
							self.selectedIds = []
						}
					}
				})
			},
			rowSelect: function () {
				var self = this;
				
				Vue.nextTick(function () {
					if (self.sortedInspectionResult.length == self.selectedIds.length) {
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
				let date = `/api/inspectionResult-datatable`
				let params = `?page=${page}&per_page=${this.pagination.per_page}&search=${this.search.global}`
				
				this.$http.get(date + params,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(({ data }) => {
						console.log('inspectionResults', data)
						this.inspectionResults = data.data

						this.makePagination(data.meta)
						NProgress.done()
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
				
				this.search.date = ''
				this.search.comment = ''
				this.search.result_name = ''
				this.search.next_job_date = ''
				this.search.company_name = ''
				this.search.asset_name = ''
				this.search.location =''
				this.search.serial =''
				this.search.bmb =''
				this.search.band =''
				this.search.insulation =''
				this.search.leakage =''
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
			openFile(file){
				console.log(file)
				var win = window.open(`/api/download-file/${file}`, '_blank')
  				win.focus()
			},
			createInspectionJob(){
				var jobIds = []
				this.selectedIds.forEach(element => {
					jobIds.push(this.inspectionResults.find(x => x.id === element).job_id)
				})
				this.$http
                        .post(`/api/create-inspection-job`, {'jobIds' :jobIds}, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            alert("Inspection Job created");
                        })
                        .catch(error => {
                            if (error.response) {
                                console.log(error.response);
                                if (error.response.status === 401) {
                                    this.$router.push("/login");
                                }
                            }
                        });
			},
			checkDueJobs(jobDate){
				let date = moment(jobDate, 'DD-MM-YYYY')
				let now = moment()
				
				if (date.isBefore(now)) {
					return 'job-status-danger'
				} 
			}
		},
		computed: {
			filteredInspectionResult: function () {
				var self = this
				return this.inspectionResults.filter(function (inspectionResult) {
					return inspectionResult.date ? inspectionResult.date.indexOf(self.search.date) >= 0 : true
				})
				.filter(function (inspectionResult) {
						return inspectionResult.company_name ? inspectionResult.company_name.toString().indexOf(self.search.company_name.toString()) >= 0 : true
				})
				.filter(function (inspectionResult) {
						return inspectionResult.asset_name ? inspectionResult.asset_name.toString().indexOf(self.search.asset_name.toString()) >= 0 : true
				})
				.filter(function (inspectionResult) {
						return inspectionResult.location ? inspectionResult.location.toString().indexOf(self.search.location.toString()) >= 0 : true
				})
				.filter(function (inspectionResult) {
					return inspectionResult.comment ? inspectionResult.comment.toLowerCase().indexOf(self.search.comment.toLowerCase()) >= 0 : true
				})
				.filter(function (inspectionResult) {
					return inspectionResult.band ? inspectionResult.band.toLowerCase().indexOf(self.search.band.toLowerCase()) >= 0 : true
				})
				.filter(function (inspectionResult) {
					return inspectionResult.insulation ? inspectionResult.insulation.toLowerCase().indexOf(self.search.insulation.toLowerCase()) >= 0 : true
				})
				.filter(function (inspectionResult) {
					return inspectionResult.leakage ? inspectionResult.leakage.toLowerCase().indexOf(self.search.leakage.toLowerCase()) >= 0 : true
				})
				.filter(function (inspectionResult) {
					return inspectionResult.result_name ? inspectionResult.result_name.indexOf(self.search.result_name) >= 0 : true
				})
				.filter(function (inspectionResult) {
					return inspectionResult.next_job_date ? inspectionResult.next_job_date.indexOf(self.search.next_job_date) >= 0 : true
				})
				.filter(function (inspectionResult) {
					return inspectionResult.serial ? inspectionResult.serial.indexOf(self.search.serial) >= 0 : true
				})
				.filter(function (inspectionResult) {
					return inspectionResult.bmb ? inspectionResult.bmb.indexOf(self.search.bmb) >= 0 : true
				})
			},
			sortedInspectionResult: function () {
				return this.filteredInspectionResult.sort((a, b) => {
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
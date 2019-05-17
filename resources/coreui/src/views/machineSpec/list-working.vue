<template>
	<div class="animated fadeIn">
		<div class="row">
			<!--/.col-->
			<div class="col-lg-12">
				<b-card header="" border-variant="light">
					<div class="row">
						<div class="col-md-3 col-sm-12">
							<input asset_model_name="text" class="form-control" v-model="search.global" v-on:keyup.enter="load(1)" placeholder="Type and press enter to search" title="Search Item">
						</div>
						<div class="col-md-9 col-sm-12 text-right header-menu-nav">
							<slot name="navigations"></slot>

							<router-link :to="{ path: '/machineSpec/create' }" class="btn btn-link">
								<i class="fa fa-plus-square icon" title="Add Item"></i>
							</router-link>
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
							<download-excel :data="sortedModelType" class="btn">
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
										<th v-if="checkColumnVisibilityStatus('asset_model_name')" @click="sortByColumn('asset_model_name')" class="text-center sortable">
											Asset Model Name
											<i :class="setSelectedColumnSortIcon('asset_model_name')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('serial')" @click="sortByColumn('serial')" class="text-center sortable">
											Serial
											<i :class="setSelectedColumnSortIcon('serial')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('bmb')" @click="sortByColumn('bmb')" class="text-center sortable">
											BMB
											<i :class="setSelectedColumnSortIcon('bmb')"></i>
										</th>
									</tr>
									<!-- Column Search -->
									<tr>
										<th v-if="checkColumnVisibilityStatus('all')">
											<b-form-checkbox name="checkAll" title="Check all" @change="allRowSelect" v-model="all"></b-form-checkbox>
										</th>
										<th v-if="checkColumnVisibilityStatus('asset_model_name')">
											<input asset_model_name="text" placeholder="Search" class="form-control" v-model="search.asset_model_name" />
										</th>
										<th v-if="checkColumnVisibilityStatus('serial')">
											<input asset_model_name="text" placeholder="Search" class="form-control" v-model="search.serial" />
										</th>
										<th v-if="checkColumnVisibilityStatus('bmb')">
											<input asset_model_name="text" placeholder="Search" class="form-control" v-model="search.bmb" />
										</th>
									</tr>
								</thead>
								<!-- Table Body -->
								<tbody>
									<tr v-for="(machineSpec, index) in sortedModelType" :key="index">
										<td v-if="checkColumnVisibilityStatus('all')">
											<b-form-checkbox v-model="selectedIds" @change="rowSelect" :value="machineSpec.id">
											</b-form-checkbox>
										</td>
										<td v-if="checkColumnVisibilityStatus('asset_model_name')">
											<router-link :to="{ path: `/machineSpec/${machineSpec.id}`}">
												{{ machineSpec.asset_model_name }}
											</router-link>
										</td>
										<td v-if="checkColumnVisibilityStatus('serial')">
											{{ machineSpec.serial }}
										</td>
										<td v-if="checkColumnVisibilityStatus('bmb')">
											{{ machineSpec.bmb }}
										</td>
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
				machineSpecs: [],
				currentSortColumn: 'name',
				currentSortDirection: 'asc',
				search: {
					global: '',
					asset_model_name: '',
				},
				pagination: {
					per_page: 5,
					current_page: 1,
					last_page: 1,
					total: 1,
					from: 0,
					to: 0,
					perPageItems: [5, 10, 15]
				},
				all: false,
				selectedIds: [],
				selectedColumns: [],
				columns: [
					{name : 'all', label : 'All'},
					{name : 'asset_model_name', label : 'Asset Model'},
					{name : 'serial', label : 'Serial'},
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
				this.$swal('machineSpec Deleted Successfully')
			},
			erase() {
				self = this
				self.$swal({
					title: 'Do You Really Want To Delete？',
					text: "You Can Not Revive This Information!",
					asset_model_name: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'OK',
					cancelButtonText: 'Cancel'
				}).then((result) => {
					if (result.value) {
						this.$http.delete(`/api/machineSpec/${this.selectedIds.join(',')}`, {
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
			setSelectedColumnSortIcon(asset_model_name) {
				if (this.currentSortDirection == 'asc' && this.currentSortColumn == asset_model_name)
					return 'fa fa-arrow-down'
				else if (this.currentSortDirection == 'desc' && this.currentSortColumn == asset_model_name)
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
						self.sortedModelType.forEach(machineSpec => {
							self.selectedIds.push(machineSpec.id.toString())
						})
					}
					else {
						if (self.sortedModelType.length == self.selectedIds.length) {
							self.selectedIds = []
						}
					}
				})
			},
			rowSelect: function () {
				var self = this;
				Vue.nextTick(function () {
					if (self.sortedModelType.length == self.selectedIds.length) {
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
				let url = `/api/machineSpecs-datatable`
				let params = `?page=${page}&per_page=${this.pagination.per_page}&search=${this.search.global}`
				
				this.$http.get(url + params,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(({ data }) => {
						console.log('machineSpecs', data)
						this.machineSpecs = data.data

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
				this.search.asset_model_name = ''
				this.search.serial = ''
				this.search.bmb = ''
				
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
			}
		},
		computed: {
			filteredModelType: function () {
				var self = this
				return this.machineSpecs.filter(function (machineSpec) {
					return machineSpec.asset_model_name ? machineSpec.asset_model_name.toLowerCase().indexOf(self.search.asset_model_name.toLowerCase()) >= 0 : true
					return machineSpec.serial ? machineSpec.serial.toLowerCase().indexOf(self.search.serial.toLowerCase()) >= 0 : true
					return machineSpec.bmb ? machineSpec.bmb.toLowerCase().indexOf(self.search.bmb.toLowerCase()) >= 0 : true
				})	
			},
			sortedModelType: function () {
				return this.filteredModelType.sort((a, b) => {
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
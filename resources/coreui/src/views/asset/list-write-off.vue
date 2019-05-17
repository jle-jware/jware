<template>
	<div class="animated fadeIn">
		<div class="row">
			<!--/.col-->
			<div class="col-lg-12">
				<b-card header="" border-variant="light">
					<div class="row">
						<div class="col-lg-4"></div>
						<div class="col-lg-8">
							<div >Short Listed {{sortedAsset.length}} Total {{pagination.total}} items, Per page {{pagination.per_page}}, Showing {{pagination.from}} 〜 {{pagination.to}}</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3 col-sm-12">
							<input type="text" class="form-control" v-model="search.global" v-on:keydown="load(1)" v-on:keyup.enter="load(1)" placeholder="Search For All Records" title="Type and press enter to search">
						</div>
						<div class="col-md-2 col-sm-12">
							<button class="btn btn-link" @click.prevent="clearSearch" title="Clear Search">
								<i class="fa fa-window-close icon" title="Clear All Search"></i>
							</button>
						</div>
						<div class="col-md-7 col-sm-12 text-right header-menu-nav">
							<slot name="navigations"></slot>
							<router-link :to="{ path: '/assets' }" class="btn btn-link">
								<i class="fas fa-box-open icon" title="See Available Assets"></i>
							</router-link>
							<button :disabled="!selectedIds.length" class="btn btn-link" @click.prevent="erase" title="Delete Item">
								<i class="fa fa-trash icon"></i>
							</button>
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
							<download-excel :data="sortedAsset" class="btn">
								<i class="fa fa-download icon" title="Export Assets"></i>
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
										<!-- <th v-if="checkColumnVisibilityStatus('name')" @click="sortByColumn('name')" class="text-center sortable">
											Name
											<i :class="setSelectedColumnSortIcon('name')"></i>
										</th> -->
										<th v-if="checkColumnVisibilityStatus('asset_model_name')" @click="sortByColumn('asset_model_name')" class="text-center sortable">
											Type
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
										<th v-if="checkColumnVisibilityStatus('company_name')" @click="sortByColumn('company_name')" class="text-center sortable">
											Customer
											<i :class="setSelectedColumnSortIcon('company_name')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('location_description')" @click="sortByColumn('location_description')" class="text-center sortable">
											Location Description
											<i :class="setSelectedColumnSortIcon('location_description')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('location_address')" @click="sortByColumn('location_address')" class="text-center sortable">
											Address
											<i :class="setSelectedColumnSortIcon('location_address')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('distributor')" @click="sortByColumn('asset_model_name')" class="text-center sortable">
											Distributor
											<i :class="setSelectedColumnSortIcon('distributor')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('specific_location')" @click="sortByColumn('specific_location')" class="text-center sortable">
											Specific Location
											<i :class="setSelectedColumnSortIcon('specific_location')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('asset_status_name')" @click="sortByColumn('asset_status_name')" class="text-center sortable">
											Asset Status
											<i :class="setSelectedColumnSortIcon('asset_status_name')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('updated_at')" @click="sortByColumn('updated_at')" class="text-center sortable">
											Update Date
											<i :class="setSelectedColumnSortIcon('updated_at')"></i>
										</th>
									</tr>
									<!-- Column Search -->
									<tr>
										<th v-if="checkColumnVisibilityStatus('all')">
											<b-form-checkbox name="checkAll" title="Check all" @change="allRowSelect" v-model="all"></b-form-checkbox>
										</th>
										<!-- <th v-if="checkColumnVisibilityStatus('name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.name" />
										</th> -->
										<th v-if="checkColumnVisibilityStatus('asset_model_name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.asset_model_name" />
										</th>
										<th v-if="checkColumnVisibilityStatus('serial')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.serial" />
										</th>
										<th v-if="checkColumnVisibilityStatus('bmb')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.bmb" />
										</th>
										<th v-if="checkColumnVisibilityStatus('company_name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.company_name" />
										</th>
										<th v-if="checkColumnVisibilityStatus('location_description')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.location_description" />
										</th>
										<th v-if="checkColumnVisibilityStatus('location_address')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.location_address" />
										</th>
										<th v-if="checkColumnVisibilityStatus('distributor')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.distributor" />
										</th>
										<th v-if="checkColumnVisibilityStatus('specific_location')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.specific_location" />
										</th>
										<th v-if="checkColumnVisibilityStatus('asset_status_name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.asset_status_name" />
										</th>
										<th v-if="checkColumnVisibilityStatus('updated_at')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.updated_at" />
										</th>
									</tr>
								</thead>
								<!-- Table Body -->
								<tbody>
									<tr v-for="(asset, index) in sortedAsset" :key="index">
										<td v-if="checkColumnVisibilityStatus('all')">
											<b-form-checkbox v-model="selectedIds" @change="rowSelect" :value="asset.id">
											</b-form-checkbox>
										</td>
										<!-- <td v-if="checkColumnVisibilityStatus('name')">
											<router-link :to="{ path: `/asset/${asset.id}`}">
												{{ asset.name }}
											</router-link>
										</td> -->
										<td v-if="checkColumnVisibilityStatus('asset_model_name')">
											<router-link :to="{ path: `/asset/${asset.id}`}">
												{{ asset.asset_model_name ? asset.asset_model_name:''}}
											</router-link>
										</td>
										<td v-if="checkColumnVisibilityStatus('serial')">
											{{ asset.serial }}
										</td>
										<td v-if="checkColumnVisibilityStatus('bmb')">
											{{ asset.bmb }}
										</td>
										<td v-if="checkColumnVisibilityStatus('company_name')">
											<router-link :to="{ path: `/asset/${asset.id}`}">
												{{ asset.company_name }}
											</router-link>
										</td>
										<td v-if="checkColumnVisibilityStatus('location_description')">
											<router-link :to="{ path: `/asset/${asset.id}`}">
												{{ asset.location_description }}
											</router-link>
										</td>
										<td v-if="checkColumnVisibilityStatus('location_address')">
											{{ asset.location_address }}
										</td>
										<td v-if="checkColumnVisibilityStatus('distributor')">
											{{ asset.distributor }}
										</td>
										<td v-if="checkColumnVisibilityStatus('specific_location')">
											{{ asset.specific_location }}
										</td>
										<!-- <td v-if="checkColumnVisibilityStatus('asset_status_name')">
											{{ asset.asset_status_name }}
										</td> -->
										<td :class="getAssetStatusType(asset.asset_status_name)" v-if="checkColumnVisibilityStatus('asset_status_name')">
											{{ asset.asset_status_name ? asset.asset_status_name:'' }}
										</td>
										<td v-if="checkColumnVisibilityStatus('updated_at')">
											{{ asset.updated_at }}
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
				assets: [],
				currentSortColumn: 'name',
				currentSortDirection: 'asc',
				search: {
					global: '',
					name: '',
					specific_location: '',
					agent_id: '',
					serial: '',
					bmb: '',
					location_address:'',
					location_description:'',
					asset_model_name:'',
					company_name:'',
					asset_status_name:'',
					distributor: '',
					updated_at:''
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
					{name : 'name', label : 'Name'},
					{name : 'specific_location', label : 'Specific Location'},
					{name : 'agent_id', label : 'Distributor'},
					{name : 'serial', label : 'Serial'},
					{name : 'bmb', label : 'BMB'},
					{name : 'location_address', label : 'location_address'},
					{name : 'location_description', label : 'Location Description'},
					{name : 'asset_model_name', label : 'Asset Model Name'},
					{name : 'company_name', label : 'Company Name'},
					{name : 'asset_status_name', label : 'Asse Status'},
					{name : 'distributor', label : 'Distributor'},
					{name : 'updated_at', label : 'Update Date'},
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
				this.$swal('asset Deleted Successfully')
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
						this.$http.delete(`/api/asset/${this.selectedIds.join(',')}`, {
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
						self.sortedAsset.forEach(asset => {
							self.selectedIds.push(asset.id.toString())
						})
					}
					else {
						if (self.sortedAsset.length == self.selectedIds.length) {
							self.selectedIds = []
						}
					}
				})
			},
			rowSelect: function () {
				var self = this;
				Vue.nextTick(function () {
					if (self.sortedAsset.length == self.selectedIds.length) {
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
				let url = `/api/assets-by-write-off-datatable`
				let params = `?page=${page}&per_page=${this.pagination.per_page}&search=${this.search.global}`
				
				this.$http.get(url + params,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(({ data }) => {
						console.log('assets', data)
						this.assets = data.data
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
				
				this.search.name = ''
				this.search.specific_location = ''
				this.search.agent_id = ''
				this.search.serial = ''
				this.search.bmb = ''
				this.search.location_address = '',
				this.search.company_name = '',
				this.search.asset_model_name = ''
				this.search.location_description = ''
				this.search.asset_status_name = ''
				this.search.distributor = '',
				this.search.updated_at = ''
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
			getAssetStatusType(type){
				let response = null
				switch (type) {
					case 'NEW MACHINE':
						response = 'asset-status-warning'
						break
					case 'PLACED':
						response = 'asset-status-success'
						break
					case 'In Transit':
						response = 'asset-status-danger'
						break
					case 'TO BE ASSESSED BY PAUL':
						response = 'asset-status-blue'
						break
					case 'REFURBISH':
						response = 'asset-status-success'
						break
					case 'Return':
						response = 'asset-status-danger'
						break
					case 'WRITE OFF':
						response = 'asset-status-danger'
						break
				}
				return response
			}
		},
		computed: {
			filteredAsset: function () {
				var self = this
				return this.assets.filter(function (asset) {
					return asset.name ? asset.name.toLowerCase().indexOf(self.search.name.toLowerCase()) >= 0 : true
					})
					.filter(function (asset) {
						return asset.asset_model_name ? asset.asset_model_name.toLowerCase().indexOf(self.search.asset_model_name.toLowerCase()) >= 0 : true
					})
					.filter(function (asset) {
						return asset.company_name ? asset.company_name.toLowerCase().indexOf(self.search.company_name.toLowerCase()) >= 0 : true
					})
					.filter(function (asset) {
						return asset.location_description ? asset.location_description.toLowerCase().indexOf(self.search.location_description.toLowerCase()) >= 0 : true
					})
					.filter(function (asset) {
						return asset.location_address ? asset.location_address.toLowerCase().indexOf(self.search.location_address.toLowerCase()) >= 0 : true
					})
					.filter(function (asset) {
						return asset.specific_location ? asset.specific_location.toLowerCase().indexOf(self.search.specific_location.toLowerCase()) >= 0 : true
					})
					// .filter(function (asset) {
					// 	return asset.agent_id ? asset.agent_id.toLowerCase().indexOf(self.search.agent_id.toLowerCase()) >= 0 : true
					// })
					.filter(function (asset) {
						return asset.serial ? asset.serial.toString().indexOf(self.search.serial.toString()) >= 0 : true
					})
					.filter(function (asset) {
						return asset.bmb ? asset.bmb.toString().indexOf(self.search.bmb.toString()) >= 0 : true
					})
					.filter(function (asset) {
						return asset.asset_status_name ? asset.asset_status_name.toLowerCase().indexOf(self.search.asset_status_name.toLowerCase()) >= 0 : true
					})
					.filter(function (asset) {
						return asset.distributor ? asset.distributor.toLowerCase().indexOf(self.search.distributor.toLowerCase()) >= 0 : true
					})
					.filter(function (asset) {
						return asset.updated_at ? asset.updated_at.toString().indexOf(self.search.updated_at.toString()) >= 0 : true
					})
			},
			sortedAsset: function () {
				return this.filteredAsset.sort((a, b) => {
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
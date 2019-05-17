<template>
	<div class="animated fadeIn">
		<div class="row">
			<!--/.col-->
			<div class="col-lg-12 scrollme">
				<b-card header="" border-variant="light">
					<div class="row">
						<div class="col-md-3 col-sm-12">
							<input type="text" class="form-control" v-model="search.global" v-on:keyup.enter="load(1)" placeholder="Type and press enter to search" title="Search Item">
						</div>
						<div class="col-md-9 col-sm-12 text-right header-menu-nav">
							<slot name="navigations"></slot>

							<router-link :to="{ path: '/spare/create' }" class="btn btn-link">
								<i class="fa fa-plus-square icon" title="Add Item"></i>
							</router-link>
							<button class="btn btn-link" @click.prevent="clearSearch" title="Clear Search">
								<i class="fas fa-eraser icon"></i>
							</button>
							<router-link :to="{ name: 'Upload Spare Excel Dump' }" class="btn btn-link">
								<i class="fa fa-upload icon" title="Add Item"></i>
							</router-link>
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
							<download-excel :data="sortedSpare" class="btn">
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
										<th v-if="checkColumnVisibilityStatus('name')" @click="sortByColumn('name')" class="text-center sortable">
											Name
											<i :class="setSelectedColumnSortIcon('name')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('spare_type_name')" @click="sortByColumn('spare_type_name')" class="text-center sortable">
											Spare Type
											<i :class="setSelectedColumnSortIcon('spare_type_name')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('company_name')" @click="sortByColumn('company_name')" class="text-center sortable">
											Manufacturer
											<i :class="setSelectedColumnSortIcon('company_name')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('asset_model_name')" @click="sortByColumn('asset_model_name')" class="text-center sortable">
											Asset Model
											<i :class="setSelectedColumnSortIcon('asset_model_name')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('serial')" @click="sortByColumn('serial')" class="text-center sortable">
											Serial
											<i :class="setSelectedColumnSortIcon('serial')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('cost')" @click="sortByColumn('cost')" class="text-center sortable">
											Cost
											<i :class="setSelectedColumnSortIcon('cost')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('minimum_stock')" @click="sortByColumn('minimum_stock')" class="text-center sortable">
											Minimum Stock
											<i :class="setSelectedColumnSortIcon('minimum_stock')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('current_stock')" @click="sortByColumn('current_stock')" class="text-center sortable">
											Current  Stock
											<i :class="setSelectedColumnSortIcon('current_stock')"></i>
										</th>
									</tr>
									<!-- Column Search -->
									<tr>
										<th v-if="checkColumnVisibilityStatus('all')">
											<b-form-checkbox name="checkAll" title="Check all" @change="allRowSelect" v-model="all"></b-form-checkbox>
										</th>
										<th v-if="checkColumnVisibilityStatus('name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.name" />
										</th>
										<th v-if="checkColumnVisibilityStatus('spare_type_name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.spare_type_name" />
										</th>
										<th v-if="checkColumnVisibilityStatus('company_name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.company_name" />
										</th>
										<th v-if="checkColumnVisibilityStatus('asset_model_name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.asset_model_name" />
										</th>
										<th v-if="checkColumnVisibilityStatus('serial')">
											<input type="text" placeholder="serial" class="form-control" v-model="search.serial" />
										</th>
										<th v-if="checkColumnVisibilityStatus('cost')">
											<input type="text" placeholder="cost" class="form-control" v-model="search.cost" />
										</th>
										<th v-if="checkColumnVisibilityStatus('minimum_stock')">
											<input type="text" placeholder="minimum_stock" class="form-control" v-model="search.minimum_stock" />
										</th>
										<th v-if="checkColumnVisibilityStatus('current_stock')">
											<input type="text" placeholder="current_stock" class="form-control" v-model="search.current_stock" />
										</th>
									</tr>
								</thead>
								<!-- Table Body -->
								<tbody>
									<tr v-for="(spare, index) in sortedSpare" :key="index">
										<td v-if="checkColumnVisibilityStatus('all')">
											<b-form-checkbox v-model="selectedIds" @change="rowSelect" :value="spare.id">
											</b-form-checkbox>
										</td>
										<td v-if="checkColumnVisibilityStatus('name')">
											<router-link :to="{ path: `/spare/${spare.id}`}">
												{{ spare.name }}
											</router-link>
										</td>
										<td v-if="checkColumnVisibilityStatus('spare_type_name')">
											{{ spare.spare_type_name }}
										</td>
										<td v-if="checkColumnVisibilityStatus('company_name')">
											{{ spare.company_name }}
										</td>
										<td v-if="checkColumnVisibilityStatus('asset_model_name')">
											{{ spare.asset_model_name }}
										</td>
										<td v-if="checkColumnVisibilityStatus('serial')">
											{{ spare.serial }}
										</td>
										<td v-if="checkColumnVisibilityStatus('cost')">
											NZD ${{ spare.cost }}
										</td>
										<td v-if="checkColumnVisibilityStatus('minimum_stock')">
											{{ spare.minimum_stock }}
										</td>
										<td v-if="checkColumnVisibilityStatus('current_stock')">
											{{ spare.current_stock }}
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
				spares: [],
				currentSortColumn: 'name',
				currentSortDirection: 'asc',
				search: {
					global: '',
					name: '',
					company_name:'',
					spare_type_name:'',
					asset_model_name:'',
					serial:'',
					cost:'',
					minimum_stock:'',
					current_stock:''
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
					{name : 'spare_type_name', label : 'Spare Type Name'},
					{name : 'company_name', label : 'Company Name'},
					{name : 'asset_model_name', label : 'Asset Model Name'},
					{name : 'serial', label : 'serial'},
					{name : 'cost', label : 'cost'},
					{name : 'minimum_stock', label : 'Minimum Stock'},
					{name : 'current_stock', label : 'Current Stock'},
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
				this.$swal('spare Deleted Successfully')
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
						this.$http.delete(`/api/spare/${this.selectedIds.join(',')}`, {
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
						self.sortedSpare.forEach(spare => {
							self.selectedIds.push(spare.id.toString())
						})
					}
					else {
						if (self.sortedSpare.length == self.selectedIds.length) {
							self.selectedIds = []
						}
					}
				})
			},
			rowSelect: function () {
				var self = this;
				Vue.nextTick(function () {
					if (self.sortedSpare.length == self.selectedIds.length) {
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
				let url = `/api/spares-datatable`
				let params = `?page=${page}&per_page=${this.pagination.per_page}&search=${this.search.global}`
				
				this.$http.get(url + params,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(({ data }) => {
						console.log('spares', data)
						this.spares = data.data
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
				this.search.company_name = ''
				this.search.asset_model_name = ''
				this.search.serial = ''
				this.search.cost = ''
				this.search.minimum_stock = ''
				this.search.current_stock = ''
				this.search.spare_type_name =''
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
			filteredSpare: function () {
				var self = this
				return this.spares.filter(function (spare) {
					return spare.name ? spare.name.toLowerCase().indexOf(self.search.name.toLowerCase()) >= 0 : true
					})
					.filter(function (spare) {
						return spare.spare_type_name ? spare.spare_type_name.toLowerCase().indexOf(self.search.spare_type_name.toLowerCase()) >= 0 : true
					})
					.filter(function (spare) {
						return spare.company_name ? spare.company_name.toLowerCase().indexOf(self.search.company_name.toLowerCase()) >= 0 : true
					})
					.filter(function (spare) {
						return spare.serial ? spare.serial.toString().indexOf(self.search.serial.toString()) >= 0 : true
					})
					.filter(function (spare) {
						return spare.cost ? spare.cost.toString().indexOf(self.search.cost.toString()) >= 0 : true
					})
					.filter(function (spare) {
						return spare.minimum_stock ? spare.minimum_stock.toString().indexOf(self.search.minimum_stock.toString()) >= 0 : true
					})
					.filter(function (spare) {
						return spare.current_stock ? spare.minimum_stock.toString().indexOf(self.search.current_stock.toString()) >= 0 : true
					})
					.filter(function (spare) {
						return spare.asset_model_name ? spare.asset_model_name.toString().indexOf(self.search.asset_model_name.toString()) >= 0 : true
					})
			},
			sortedSpare: function () {
				return this.filteredSpare.sort((a, b) => {
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
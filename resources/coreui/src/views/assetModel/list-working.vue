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

							<router-link :to="{ path: '/assetModel/create' }" class="btn btn-link">
								<i class="fa fa-plus-square icon" title="Add Item"></i>
							</router-link>
							<button class="btn btn-link" @click.prevent="clearSearch" title="Clear Search">
								<i class="fas fa-eraser icon"></i>
							</button>
							<router-link :to="{ name: 'Upload Asset Model Excel Dump' }" class="btn btn-link">
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
							<download-excel :data="sortedAssetModel" class="btn">
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
											Model Name
											<i :class="setSelectedColumnSortIcon('name')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('model_type')" @click="sortByColumn('model_type')" class="text-center sortable">
											Model Type
											<i :class="setSelectedColumnSortIcon('model_type')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('company_name')" @click="sortByColumn('company_name')" class="text-center sortable">
											Model Make
											<i :class="setSelectedColumnSortIcon('company_name')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('cannister_no')" @click="sortByColumn('cannister_no')" class="text-center sortable">
											No of Cannisters
											<i :class="setSelectedColumnSortIcon('cannister_no')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('drink_no')" @click="sortByColumn('drink_no')" class="text-center sortable">
											No of Drinks
											<i :class="setSelectedColumnSortIcon('drink_no')"></i>
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
										<th v-if="checkColumnVisibilityStatus('model_type')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.model_type" />
										</th>
										<th v-if="checkColumnVisibilityStatus('company_name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.company_name" />
										</th>
										<th v-if="checkColumnVisibilityStatus('cannister_no')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.cannister_no" />
										</th>
										<th v-if="checkColumnVisibilityStatus('drink_no')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.drink_no" />
										</th>
									</tr>
								</thead>
								<!-- Table Body -->
								<tbody>
									<tr v-for="(assetModel, index) in sortedAssetModel" :key="index">
										<td v-if="checkColumnVisibilityStatus('all')">
											<b-form-checkbox v-model="selectedIds" @change="rowSelect" :value="assetModel.id">
											</b-form-checkbox>
										</td>
										<td v-if="checkColumnVisibilityStatus('name')">
											<router-link :to="{ path: `/assetModel/${assetModel.id}`}">
												{{ assetModel.name }}
											</router-link>
										</td>
										<!-- <td v-if="checkColumnVisibilityStatus('assetModel_number')">{{ assetModel.assetModel_number }}</td> -->
										<td v-if="checkColumnVisibilityStatus('model_type')">{{ assetModel.model_type ? assetModel.model_type:'' }}</td>
										<!-- <td v-if="checkColumnVisibilityStatus('rent')">NZD ${{ assetModel.rent }}</td> -->
										<td v-if="checkColumnVisibilityStatus('company_name')">{{ assetModel.company_name }}</td>
										<td v-if="checkColumnVisibilityStatus('cannister_no')">{{ assetModel.cannister_no }}</td>
										<td v-if="checkColumnVisibilityStatus('drink_no')">{{ assetModel.drink_no }}</td>
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
				assetModels: [],
				currentSortColumn: 'name',
				currentSortDirection: 'asc',
				search: {
					global: '',
					name: '',
					model_type: '',
					rent: '',
					frequency: '',
					company_name: '',
					current_stock:'',
					drink_no:'',
					cannister_no:''
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
					{name : 'name', label : 'name'},
					{name : 'model_type', label : 'model_type'},
					{name : 'company_name', label : 'Model Make'},
					{name : 'cannister_no', label : 'No of Cannister'},
					{name : 'drink_no', label : 'No of Drinks'},
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
				this.$swal('assetModel Deleted Successfully')
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
						this.$http.delete(`/api/assetModel/${this.selectedIds.join(',')}`, {
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
						self.sortedAssetModel.forEach(assetModel => {
							self.selectedIds.push(assetModel.id.toString())
						})
					}
					else {
						if (self.sortedAssetModel.length == self.selectedIds.length) {
							self.selectedIds = []
						}
					}
				})
			},
			rowSelect: function () {
				var self = this;
				Vue.nextTick(function () {
					if (self.sortedAssetModel.length == self.selectedIds.length) {
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
				let url = `/api/assetModels-datatable`
				let params = `?page=${page}&per_page=${this.pagination.per_page}&search=${this.search.global}`
				
				this.$http.get(url + params,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(({ data }) => {
						console.log('assetModels', data)
						this.assetModels = data.data
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
				this.search.model_type = ''
				this.search.rent = ''
				this.search.frequency = ''
				this.search.current_stock = ''
				this.search.company_name = ''
				this.search.cannister_no = ''
				this.search.drink_no = ''
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
			filteredAssetModel: function () {
				var self = this
				return this.assetModels.filter(function (assetModel) {
					return assetModel.name ? assetModel.name.toLowerCase().indexOf(self.search.name.toLowerCase()) >= 0 : true
					})
					.filter(function (assetModel) {
						return assetModel.model_type ? assetModel.model_type.toLowerCase().indexOf(self.search.model_type.toLowerCase()) >= 0 : true
					})
					.filter(function (assetModel) {
						return assetModel.rent ? assetModel.rent.toLowerCase().indexOf(self.search.rent.toLowerCase()) >= 0 : true
					})
					.filter(function (assetModel) {
						return assetModel.frequency ? assetModel.frequency.toLowerCase().indexOf(self.search.frequency.toLowerCase()) >= 0 : true
					})
					.filter(function (assetModel) {
						return assetModel.current_stock ? assetModel.current_stock.toString().indexOf(self.search.current_stock.toString()) >= 0 : true
					})
					.filter(function (assetModel) {
						return assetModel.company_name ? assetModel.company_name.toLowerCase().indexOf(self.search.company_name.toLowerCase()) >= 0 : true
					})
					.filter(function (assetModel) {
						return assetModel.cannister_no ? assetModel.cannister_no.toString().indexOf(self.search.cannister_no.toString()) >= 0 : true
					})
					.filter(function (assetModel) {
						return assetModel.drink_no ? assetModel.drink_no.toString().indexOf(self.search.drink_no.toString()) >= 0 : true
					})
			},
			sortedAssetModel: function () {
				return this.filteredAssetModel.sort((a, b) => {
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
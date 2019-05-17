<template>
	<div class="animated fadeIn">
		<div class="row">
			<!--/.col-->
			<div class="col-lg-12">
				<b-card header="" border-variant="light">
					<div class="row">
						<div class="col-md-3 col-sm-12">
							<input type="text" class="form-control" v-model="search.global" v-on:keydown="load(1)" v-on:keyup.enter="load(1)" placeholder="Type and press enter to search" title="Search Item">
						</div>
						<div class="col-md-9 col-sm-12 text-right header-menu-nav">
							<slot name="navigations"></slot>

							<router-link :to="{ path: '/user/create' }" class="btn btn-link">
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
							<download-excel :data="sortedUser" class="btn">
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
										<th v-if="checkColumnVisibilityStatus('full_name')" @click="sortByColumn('full_name')" class="text-center sortable">
											Name
											<i :class="setSelectedColumnSortIcon('full_name')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('image')" class="text-center sortable">
											Image
										</th>
										<th v-if="checkColumnVisibilityStatus('username')" @click="sortByColumn('username')" class="text-center sortable">
											User Name
											<i :class="setSelectedColumnSortIcon('username')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('email')" @click="sortByColumn('email')" class="text-center sortable">
											Email
											<i :class="setSelectedColumnSortIcon('email')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('contact_type')" @click="sortByColumn('contact_type')" class="text-center sortable">
											Contact Type
											<i :class="setSelectedColumnSortIcon('contact_type')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('phone_number')" @click="sortByColumn('phone_number')" class="text-center sortable">
											Phone
											<i :class="setSelectedColumnSortIcon('phone_number')"></i>
										</th>
										<th v-if="checkColumnVisibilityStatus('location_address')" @click="sortByColumn('location_address')" class="text-center sortable">
											Location
											<i :class="setSelectedColumnSortIcon('location_address')"></i>
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
										<th v-if="checkColumnVisibilityStatus('full_name')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.full_name" />
										</th>
										<th v-if="checkColumnVisibilityStatus('image')">
										</th>
										<th v-if="checkColumnVisibilityStatus('username')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.username" />
										</th>
										<th v-if="checkColumnVisibilityStatus('email')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.email" />
										</th>
										<th v-if="checkColumnVisibilityStatus('contact_type')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.contact_type" />
										</th>
										<th v-if="checkColumnVisibilityStatus('phone_number')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.phone_number" />
										</th>
										<th v-if="checkColumnVisibilityStatus('location_address')">
											<input type="text" placeholder="Search" class="form-control" v-model="search.location_address" />
										</th>
									</tr>
								</thead>
								<!-- Table Body -->
								<tbody>
									<tr v-for="(user, index) in sortedUser" :key="index">
										<td v-if="checkColumnVisibilityStatus('all')">
											<b-form-checkbox v-model="selectedIds" @change="rowSelect" :value="user.id">
											</b-form-checkbox>
										</td>
										<td v-if="checkColumnVisibilityStatus('company_name')">
											{{ user.company_name }}
										</td>
										<td v-if="checkColumnVisibilityStatus('full_name')">
											<router-link :to="{ path: `/user/${user.id}`}">
												{{ user.full_name }}
											</router-link>
										</td>

										<td v-if="checkColumnVisibilityStatus('image')">
											<router-link :to="{ path: `/user/${user.id}`}">
												<div class="image-container">
													<img class="object-fit-cover" :src="user.image || '/img/user.png'" />
												</div>
											</router-link>
										</td>
										<td v-if="checkColumnVisibilityStatus('username')">
											<a target="_blank" :href="user.username">{{ user.username }}</a>
										</td>
										<td v-if="checkColumnVisibilityStatus('email')">
											<a :href="`mailto: ${user.email}`">{{user.email}}</a>
										</td>
										<td v-if="checkColumnVisibilityStatus('contact_type')">
											{{ user.contact_type }}
										</td>
										<td v-if="checkColumnVisibilityStatus('phone_number')">
											<a :href="`tel: ${user.phone_number}`">{{ user.phone_number }}</a>
										</td>
										<td v-if="checkColumnVisibilityStatus('location_address')">
											{{ user.location_address }}
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
				users: [],
				currentSortColumn: 'name',
				currentSortDirection: 'asc',
				search: {
					global: '',
					full_name:'',
					company_name:'',
					image: '',
					username: '',
					email: '',
					contact_type: '',
					phone_number: '',
					location_address:'',
					company_name:''
				},
				pagination: {
					per_page: 15,
					current_page: 1,
					last_page: 1,
					total: 1,
					from: 0,
					to: 0,
					perPageItems: [20, 50, 100,1500]
				},
				all: false,
				selectedIds: [],
				selectedColumns: [],
				columns: [
					{name : 'all', label : 'All'},
					{name : 'full_name', label : 'Name'},
					{name : 'image', label : 'Image'},
					{name : 'username', label : 'User Name'},
					{name : 'email', label : 'Email'},
					{name : 'contact_type', label : 'Contact type'},
					{name : 'phone_number', label : 'Phone Number'},
					{name : 'location_address', label : 'Location Address'},
					{name : 'company_name', label : 'Company Name'},
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
				this.$swal('user Deleted Successfully')
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
						this.$http.delete(`/api/user/${this.selectedIds.join(',')}`, {
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
										window.location.href = '/login'
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
						self.sortedUser.forEach(user => {
							self.selectedIds.push(user.id.toString())
						})
					}
					else {
						if (self.sortedUser.length == self.selectedIds.length) {
							self.selectedIds = []
						}
					}
				})
			},
			rowSelect: function () {
				var self = this;
				Vue.nextTick(function () {
					if (self.sortedUser.length == self.selectedIds.length) {
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
				let url = `/api/users-datatable`
				let params = `?page=${page}&per_page=${this.pagination.per_page}&search=${this.search.global}`
				
				this.$http.get(url + params,
					{
						headers: {
							Authorization: `Bearer ${this.$store.state.token}`
						}
					})
					.then(({ data }) => {
						console.log('users', data)
						this.users = data.data

						this.makePagination(data.meta)
						NProgress.done()
					})
					.catch(error => {
						if (error.response) {
							console.log(error.response);
							if (error.response.status === 401) {
								window.location.href = '/login'
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
				
				this.search.full_name = ''
				this.search.username = ''
				this.search.contact_type = ''
				this.search.phone_number = ''
				this.search.email = ''
				this.search.location_address = '',
				this.search.company_name = ''
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
			filteredUser: function () {
				var self = this
				return this.users.filter(function (user) {
					return user.full_name ? user.full_name.toLowerCase().indexOf(self.search.full_name.toLowerCase()) >= 0 : true
				})
					.filter(function (user) {
						return user.username ? user.username.toLowerCase().indexOf(self.search.username.toLowerCase()) >= 0 : true
					})
					.filter(function (user) {
						return user.contact_type ? user.contact_type.toLowerCase().indexOf(self.search.contact_type.toLowerCase()) >= 0 : true
					})
					.filter(function (user) {
						return user.phone_number ? user.phone_number.toString().indexOf(self.search.phone_number.toString()) >= 0 : true
					})
					.filter(function (user) {
						return user.email ? user.email.toLowerCase().indexOf(self.search.email.toLowerCase()) >= 0 : true
					})
					.filter(function (user) {
						return user.location_address ? user.location_address.toLowerCase().indexOf(self.search.location_address.toLowerCase()) >= 0 : true
					})
					.filter(function (user) {
						return user.company_name ? user.company_name.toLowerCase().indexOf(self.search.company_name.toLowerCase()) >= 0 : true
					})
			},
			sortedUser: function () {
				return this.filteredUser.sort((a, b) => {
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
<template>
    <div class="animated fadeIn">
        <div :hidden="!edit" class="col-sm-12">
            <div role="tablist" class="company-collapse">
                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-btn block href="#" v-b-toggle.accordion2 variant="success ">Asset Inspection List</b-btn>
                    </b-card-header>
                    <b-collapse id="accordion2" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <input type="text" class="form-control" v-model="assetInspection.search.global" v-on:keyup.enter="assetInspectionLoad(1)" placeholder="Type and press enter to search"
                                        title="Search Item">
                                </div>
                                <div class="col-md-9 col-sm-12 text-right header-menu-nav">
                                    <slot name="navigations"></slot>

                                    <router-link :to="{ path: `/assetInspection/${$route.params.id}/create`, params: $route.params.id }" class="btn btn-link">
                                        <i class="fa fa-plus-square icon" title="Add Item"></i>
                                    </router-link>
                                    <button class="btn btn-link" @click.prevent="assetInspectionClearSearch" title="Clear Search">
                                        <i class="fas fa-eraser icon"></i>
                                    </button>

                                    <button :disabled="!assetInspection.selectedIds.length" class="btn btn-link" @click.prevent="assetInspectionErase" title="Delete Item">
                                        <i class="fa fa-trash icon"></i>
                                    </button>
                                    <b-dropdown variant="link" no-caret>
                                        <template slot="button-content">
                                            <i class="fas fa-columns icon"></i>
                                        </template>
                                        <div class="selected-columns">
                                            <li v-for="(column, index) in assetInspection.columns" :key="index">
                                                <b-form-checkbox v-model="assetInspection.selectedColumns" @change="assetInspectionColumnSelect" :value="column.name">{{column.label}}</b-form-checkbox>
                                            </li>
                                        </div>
                                    </b-dropdown>
                                    <download-excel :data="sortedAssetInspection" class="btn">
                                        <i class="fa fa-download icon"></i>
                                    </download-excel>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-striped table-responsive">
                                        <thead>
                                            <!-- Column Sort -->
                                            <tr>
                                                <th v-if="assetInspectionCheckColumnVisibility('all')">
                                                    All
                                                </th>
                                                <th v-if="assetInspectionCheckColumnVisibility('description')" @click="assetInspectionSortByColumn('description')" class="text-center sortable">
                                                    Description
                                                    <i :class="assetInspectionSetSelectedColumnSortIcon('description')"></i>
                                                </th>
                                                <th v-if="assetInspectionCheckColumnVisibility('frequency')" @click="assetInspectionSortByColumn('frequency')" class="text-center sortable">
                                                    Date
                                                    <i :class="assetInspectionSetSelectedColumnSortIcon('frequency')"></i>
                                                </th>
                                                <th v-if="assetInspectionCheckColumnVisibility('group')" @click="assetInspectionSortByColumn('group')" class="text-center sortable">
                                                   Asset Utilization
                                                    <i :class="assetInspectionSetSelectedColumnSortIcon('group')"></i>
                                                </th>
                                                <th v-if="assetInspectionCheckColumnVisibility('inspection_criteria')" @click="assetInspectionSortByColumn('inspection_criteria')" class="text-center sortable">
                                                    Asset Utilization Per Day
                                                    <i :class="assetInspectionSetSelectedColumnSortIcon('inspection_criteria')"></i>
                                                </th>
                                                 <th v-if="assetInspectionCheckColumnVisibility('file')" @click="assetInspectionSortByColumn('file')" class="text-center sortable">
                                                    Comment
                                                    <i :class="assetInspectionSetSelectedColumnSortIcon('file')"></i>
                                                </th>
                                            </tr>
                                            <!-- Column Search -->
                                            <tr>
                                                <th v-if="assetInspectionCheckColumnVisibility('all')">
                                                    <b-form-checkbox name="checkAll" title="Check all" @change="assetInspectionAllassetInspectionRowSelect" v-model="assetInspection.all"></b-form-checkbox>
                                                </th>
                                                <th v-if="assetInspectionCheckColumnVisibility('description')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="assetInspection.search.description" />
                                                </th>
                                                <th v-if="assetInspectionCheckColumnVisibility('frequency')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="assetInspection.search.frequency" />
                                                </th>
                                                <th v-if="assetInspectionCheckColumnVisibility('group')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="assetInspection.search.group" />
                                                </th>
                                                <th v-if="assetInspectionCheckColumnVisibility('inspection_criteria')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="assetInspection.search.inspection_criteria" />
                                                </th>
                                                <th v-if="assetInspectionCheckColumnVisibility('file')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="assetInspection.search.file" />
                                                </th>
                                            </tr>
                                        </thead>
                                        <!-- Table Body -->
                                        <tbody>
                                            <tr v-for="(asstInspection, index) in sortedAssetInspection" :key="index">
                                                <td v-if="assetInspectionCheckColumnVisibility('all')">
                                                    <b-form-checkbox v-model="assetInspection.selectedIds" @change="assetInspectionRowSelect" :value="asstInspection.id">
                                                    </b-form-checkbox>
                                                </td>
                                                <td v-if="assetInspectionCheckColumnVisibility('description')">
                                                    <router-link :to="{ path: `/assetInspection/${asstInspection.id}`}">
                                                        {{ asstInspection.description }}
                                                    </router-link>
                                                </td>
                                                 <td v-if="assetInspectionCheckColumnVisibility('frequency')">
                                                    {{ asstInspection.frequency }}
                                                </td>
                                                <td v-if="assetInspectionCheckColumnVisibility('group')">
                                                    {{ asstInspection.group }}
                                                </td>
                                                <td v-if="assetInspectionCheckColumnVisibility('inspection_criteria')">
                                                    {{ asstInspection.inspection_criteria }}
                                                </td>
                                                <td v-if="assetInspectionCheckColumnVisibility('file')">
                                                    {{ asstInspection.file }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <!-- Table Body End -->
                                    </table>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-1 justify-content-center align-self-center">
                                    <multiselect v-model="assetInspection.pagination.per_page" @select="assetInspectionOnSelectPageCount" :options="assetInspection.pagination.perPageItems"
                                        :searchable="false" :show-labels="false" placeholder="Items / Page"></multiselect>
                                </div>

                                <div class="col-lg-3 justify-content-center align-self-center text-center">

                                </div>

                                <div class="col-lg-4 justify-content-center align-self-center text-center">
                                    <div>Total {{assetInspection.pagination.total}} items, Per page {{assetInspection.pagination.per_page}},
                                        Showing {{assetInspection.pagination.from}} 〜 {{assetInspection.pagination.to}}</div>
                                </div>

                                <div class="col-lg-4 justify-content-center align-self-center">
                                    <ul class="pagination pagination-custom pull-right">
                                        <li :class="{disabled: assetInspection.pagination.current_page <= 1}"><a href="#" @click.prevent="assetInspectionPageChange(1)">&laquo;</a></li>
                                        <li :class="{disabled: assetInspection.pagination.current_page <= 1}"><a href="#" @click.prevent="assetInspectionPageChange(assetInspection.pagination.current_page - 1)">&lt;</a></li>
                                        <li v-for="page in AssetInspectionPages" :key="page" :class="{active: page === assetInspection.pagination.current_page}">
                                            <a href="#" @click.prevent="assetInspectionPageChange(page)">{{page}}</a>
                                        </li>
                                        <li :class="{disabled: assetInspection.pagination.current_page >= assetInspection.pagination.last_page}"><a
                                                href="#" @click.prevent="assetInspectionPageChange(assetInspection.pagination.current_page + 1)">&gt;</a></li>
                                        <li :class="{disabled: assetInspection.pagination.current_page >= assetInspection.pagination.last_page}"><a
                                                href="#" @click.prevent="assetInspectionPageChange(assetInspection.pagination.last_page)">&raquo;</a></li>
                                    </ul>
                                </div>
                            </div>
                        </b-card-body>
                    </b-collapse>
                </b-card>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: "forms",
        data() {
            return {
                assetInspection: {
                    currentSortColumn: 'name',
                    currentSortDirection: 'asc',
                    search: {
                        global: '',
                        description: '',
                        frequency: '',
                        group: '',
                        inspection_criteria: '',
                        file: '',
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
                        { name: 'all', label: 'All' },
                        { name: 'description', label: 'Description' },
                        { name: 'frequency', label: 'Frequency' },
                        { name: 'group', label: 'Group' },
                        { name: 'inspection_criteria', label: 'Criteria' },
                        { name: 'file', label: 'Comment' },
                    ]
                }
            }
        },

        created() {
            this.assetInspection.columns.forEach(column => {
                this.assetInspection.selectedColumns.push(column.name)
            })
        },
        methods: {
            // assetInspection Datatable
            assetInspectionErase() {
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
                        this.$http.delete(`/api/assetInspection/${this.assetInspection.selectedIds.join(',')}`, {
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
                                this.assetInspectionLoad(1)
                                this.assetInspection.selectedIds = []
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
            assetInspectionSortByColumn: function (s) {
                if (s === this.assetInspection.currentSortColumn) {
                    this.assetInspection.currentSortDirection = this.assetInspection.currentSortDirection === 'asc' ? 'desc' : 'asc';
                }
                this.assetInspection.currentSortColumn = s;
            },
            assetInspectionSetSelectedColumnSortIcon(type) {
                if (this.assetInspection.currentSortDirection == 'asc' && this.assetInspection.currentSortColumn == type)
                    return 'fa fa-arrow-down'
                else if (this.assetInspection.currentSortDirection == 'desc' && this.assetInspection.currentSortColumn == type)
                    return 'fa fa-arrow-down open'
                else
                    return 'fa fa-arrow-down open fa-disabled'
            },
            // Paginating the table data
            assetInspectionMakePagination(meta) {
                let pagination = {
                    per_page: this.assetInspection.pagination.per_page,
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    total: meta.total,
                    from: meta.from,
                    to: meta.to,
                    perPageItems: this.assetInspection.pagination.perPageItems
                }
                this.assetInspection.pagination = pagination;
            },
            assetInspectionAllassetInspectionRowSelect() {
                var self = this;
                Vue.nextTick(function () {
                    self.assetInspection.selectedIds = []

                    if (self.assetInspection.all) {
                        self.sortedAssetInspection.forEach(assetInspection => {
                            self.assetInspection.selectedIds.push(assetInspection.id.toString())
                        })
                    }
                    else {
                        if (self.sortedAssetInspection.length == self.assetInspection.selectedIds.length) {
                            self.assetInspection.selectedIds = []
                        }
                    }
                })
            },
            assetInspectionRowSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    if (self.sortedAssetInspection.length == self.assetInspection.selectedIds.length) {
                        self.assetInspection.all = true
                    }
                    else {
                        self.assetInspection.all = false
                    }
                })
            },
            assetInspectionColumnSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    console.log(self.assetInspection.selectedColumns)
                })
            },
            assetInspectionLoad(page) {
                NProgress.start()
                let url = `/api/assetInspections-datatable/${this.$route.params.id}`
                let params = `?page=${page}&per_page=${this.assetInspection.pagination.per_page}&search=${this.assetInspection.search.global}`

                this.$http.get(url + params,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(({ data }) => {
                        console.log('assetInspections', data)
                        this.assetInspections = data.data
                        NProgress.done()
                        this.assetInspectionMakePagination(data.meta)
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
            assetInspectionPageChange(page) {
                if (page >= 1 && page <= this.assetInspection.pagination.last_page) this.assetInspectionLoad(page)
            },
            assetInspectionPerPageChanged(count) {
                this.assetInspection.pagination.per_page = count
                this.assetInspectionLoad(1)
            },
            assetInspectionClearSearch() {
                if (this.assetInspection.search.global.length) {
                    this.assetInspection.search.global = ''
                    this.assetInspectionLoad(1)
                }

                this.assetInspection.search.description = ''
                this.assetInspection.search.frequency = ''
                this.assetInspection.search.group = ''
                this.assetInspection.search.inspection_criteria = ''
                this.assetInspection.search.file = ''
            },
            assetInspectionOnSelectPageCount(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption, id)
                    this.assetInspection.pagination.per_page = selectedOption
                    this.assetInspectionLoad(1)
                }
            },
            assetInspectionCheckColumnVisibility(column) {
                return this.assetInspection.selectedColumns.indexOf(column) !== -1 ? true : false
            },
            //end of assetInspection datatable
        },
        computed: {
            filteredAssetInspection: function () {
                var self = this
                return this.assetInspections.filter(function (assetInspection) {
                    return assetInspection.description ? assetInspection.description.toLowerCase().indexOf(self.assetInspection.search.description.toLowerCase()) >= 0 : true
                })
                    .filter(function (assetInspection) {
                        return assetInspection.frequency ? assetInspection.frequency.toLowerCase().indexOf(self.assetInspection.search.frequency.toLowerCase()) >= 0 : true
                    })
                    .filter(function (assetInspection) {
                        return assetInspection.group ? assetInspection.group.toString().indexOf(self.assetInspection.search.group.toString()) >= 0 : true
                    })
                    .filter(function (assetInspection) {
                        return assetInspection.inspection_criteria ? assetInspection.inspection_criteria.toString().indexOf(self.assetInspection.search.inspection_criteria.toString()) >= 0 : true
                    })
                    .filter(function (assetInspection) {
                        return assetInspection.file ? assetInspection.file.toLowerCase().indexOf(self.assetInspection.search.file.toLowerCase()) >= 0 : true
                    })
            },
            sortedAssetInspection: function () {
                return this.filteredAssetInspection.sort((a, b) => {
                    let modifier = 1
                    if (this.assetInspection.currentSortDirection === 'desc') modifier = -1

                    let temp = `.${this.assetInspection.currentSortColumn}`
                    if (eval(`a${temp}`) < eval(`b${temp}`)) return -1 * modifier
                    if (eval(`a${temp}`) > eval(`b${temp}`)) return 1 * modifier
                    return 0
                })
            },
            AssetInspectionPages() {
                let start = _.max([this.assetInspection.pagination.current_page - 2, 1])
                let end = _.min([start + 5, this.assetInspection.pagination.last_page + 1])
                start = _.max([end - 5, 1])
                return _.range(start, end)
            },
        },
    };
</script>
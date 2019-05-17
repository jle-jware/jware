<template>
    <div class="animated fadeIn">
        <div :hidden="!edit" class="col-sm-12">
            <div role="tablist" class="company-collapse">
                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-btn bjbk href="#" v-b-toggle.accordion2 variant="success ">job List</b-btn>
                    </b-card-header>
                    <b-collapse id="accordion2" accordion="my-accordion" role="tabpanel">
                        <b-card-body>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <input type="text" class="form-control" v-model="job.search.global" v-on:keyup.enter="jobLoad(1)" placeholder="Type and press enter to search"
                                        title="Search Item">
                                </div>
                                <div class="col-md-9 col-sm-12 text-right header-menu-nav">
                                    <slot name="navigations"></slot>

                                    <router-link :to="{ path: `/job/${$route.params.id}/create`, params: $route.params.id }" class="btn btn-link">
                                        <i class="fa fa-plus-square icon" title="Add Item"></i>
                                    </router-link>
                                    <button class="btn btn-link" @click.prevent="jobClearSearch" title="Clear Search">
                                        <i class="fas fa-eraser icon"></i>
                                    </button>

                                    <button :disabled="!job.selectedIds.length" class="btn btn-link" @click.prevent="jobErase" title="Delete Item">
                                        <i class="fa fa-trash icon"></i>
                                    </button>
                                    <b-dropdown variant="link" no-caret>
                                        <template slot="button-content">
                                            <i class="fas fa-columns icon"></i>
                                        </template>
                                        <div class="selected-columns">
                                            <li v-for="(column, index) in job.columns" :key="index">
                                                <b-form-checkbox v-model="job.selectedColumns" @change="jobColumnSelect" :value="column.name">{{column.label}}</b-form-checkbox>
                                            </li>
                                        </div>
                                    </b-dropdown>
                                    <download-excel :data="sortedJob" class="btn">
                                        <i class="fa fa-download icon"></i>
                                    </download-excel>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-striped table-responsive">
                                        <thead>
                                            <!-- Column Sort -->
                                            <tr>
                                                <th v-if="jobCheckColumnVisibility('all')">
                                                    All
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('description')" @click="jobSortByColumn('description')" class="text-center sortable">
                                                    Job Description
                                                    <i :class="jobSetSelectedColumnSortIcon('description')"></i>
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('job_number')" @click="jobSortByColumn('job_number')" class="text-center sortable">
                                                    Job Number
                                                    <i :class="jobSetSelectedColumnSortIcon('job_number')"></i>
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('job_type_name')" @click="jobSortByColumn('job_type_name')" class="text-center sortable">
                                                    Job Type
                                                    <i :class="jobSetSelectedColumnSortIcon('job_type_name')"></i>
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('job_priority')" @click="jobSortByColumn('job_priority')" class="text-center sortable">
                                                    Job Priority
                                                    <i :class="jobSetSelectedColumnSortIcon('job_priority')"></i>
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('job_status')" @click="jobSortByColumn('job_status')" class="text-center sortable">
                                                    Job Status
                                                    <i :class="jobSetSelectedColumnSortIcon('job_status')"></i>
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('tech_name')" @click="jobSortByColumn('tech_name')" class="text-center sortable">
                                                    Technician Name
                                                    <i :class="jobSetSelectedColumnSortIcon('tech_name')"></i>
                                                </th>
                                                 <th v-if="jobCheckColumnVisibility('comment')" @click="jobSortByColumn('comment')" class="text-center sortable">
                                                    Comment
                                                    <i :class="jobSetSelectedColumnSortIcon('comment')"></i>
                                                </th>
                                            </tr>
                                            <!-- Column Search -->
                                            <tr>
                                                <th v-if="jobCheckColumnVisibility('all')">
                                                    <b-form-checkbox name="checkAll" title="Check all" @change="jobAlljobRowSelect" v-model="job.all"></b-form-checkbox>
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('description')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="job.search.description" />
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('job_number')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="job.search.job_number" />
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('job_type_name')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="job.search.job_type_name" />
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('job_priority')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="job.search.job_priority" />
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('job_status')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="job.search.job_status" />
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('tech_name')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="job.search.tech_name" />
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('comment')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="job.search.comment" />
                                                </th>
                                            </tr>
                                        </thead>
                                        <!-- Table Body -->
                                        <tbody>
                                            <tr v-for="(jb, index) in sortedJob" :key="index">
                                                <td v-if="jobCheckColumnVisibility('all')">
                                                    <b-form-checkbox v-model="job.selectedIds" @change="jobRowSelect" :value="jb.id">
                                                    </b-form-checkbox>
                                                </td>
                                                <td v-if="jobCheckColumnVisibility('description')">
                                                    <router-link :to="{ path: `/job/${jb.id}`}">
                                                        {{ jb.description }}
                                                    </router-link>
                                                </td>
                                                <td v-if="jobCheckColumnVisibility('job_number')">
                                                    <router-link :to="{ path: `/job/${jb.id}/view`}">
                                                        {{ jb.job_number }}
                                                    </router-link>
                                                </td>
                                                 <td v-if="jobCheckColumnVisibility('job_type_name')">
                                                    {{ jb.job_type_name }}
                                                </td>
                                                <td v-if="jobCheckColumnVisibility('job_priority')">
                                                    {{ jb.job_priority }}
                                                </td>
                                                <td v-if="jobCheckColumnVisibility('job_status')">
                                                    {{ jb.job_status }}
                                                </td>
                                                <td v-if="jobCheckColumnVisibility('tech_name')">
                                                    {{ jb.tech_name }}
                                                </td>
                                                <td v-if="jobCheckColumnVisibility('comment')">
                                                    {{ jb.comment }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <!-- Table Body End -->
                                    </table>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-1 justify-content-center align-self-center">
                                    <multiselect v-model="job.pagination.per_page" @select="jobOnSelectPageCount" :options="job.pagination.perPageItems"
                                        :searchable="false" :show-labels="false" placeholder="Items / Page"></multiselect>
                                </div>

                                <div class="col-lg-3 justify-content-center align-self-center text-center">

                                </div>

                                <div class="col-lg-4 justify-content-center align-self-center text-center">
                                    <div>Total {{job.pagination.total}} items, Per page {{job.pagination.per_page}},
                                        Showing {{job.pagination.from}} 〜 {{job.pagination.to}}</div>
                                </div>

                                <div class="col-lg-4 justify-content-center align-self-center">
                                    <ul class="pagination pagination-custom pull-right">
                                        <li :class="{disabled: job.pagination.current_page <= 1}"><a href="#" @click.prevent="jobPageChange(1)">&laquo;</a></li>
                                        <li :class="{disabled: job.pagination.current_page <= 1}"><a href="#" @click.prevent="jobPageChange(job.pagination.current_page - 1)">&lt;</a></li>
                                        <li v-for="page in JobPages" :key="page" :class="{active: page === job.pagination.current_page}">
                                            <a href="#" @click.prevent="jobPageChange(page)">{{page}}</a>
                                        </li>
                                        <li :class="{disabled: job.pagination.current_page >= job.pagination.last_page}"><a
                                                href="#" @click.prevent="jobPageChange(job.pagination.current_page + 1)">&gt;</a></li>
                                        <li :class="{disabled: job.pagination.current_page >= job.pagination.last_page}"><a
                                                href="#" @click.prevent="jobPageChange(job.pagination.last_page)">&raquo;</a></li>
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
                job: {
                    currentSortColumn: 'name',
                    currentSortDirection: 'asc',
                    search: {
                        global: '',
                        description: '',
                        job_number: '',
                        job_type_name: '',
                        job_priority: '',
                        job_status: '',
                        tech_name: '',
                        comment: '',
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
                        { name: 'job_number', label: 'Job Number' },
                        { name: 'job_type_name', label: 'Job Type' },
                        { name: 'job_priority', label: 'Job Priority' },
                        { name: 'job_status', label: 'Job Status' },
                        { name: 'tech_name', label: 'Technician Name' },
                        { name: 'comment', label: 'Comment' },
                    ]
                }
            }
        },

        created() {
            this.job.columns.forEach(column => {
                this.job.selectedColumns.push(column.name)
            })
        },
        methods: {
            // job Datatable
            jobErase() {
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
                        this.$http.delete(`/api/job/${this.job.selectedIds.join(',')}`, {
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
                                this.jobLoad(1)
                                this.job.selectedIds = []
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
            jobSortByColumn: function (s) {
                if (s === this.job.currentSortColumn) {
                    this.job.currentSortDirection = this.job.currentSortDirection === 'asc' ? 'desc' : 'asc';
                }
                this.job.currentSortColumn = s;
            },
            jobSetSelectedColumnSortIcon(type) {
                if (this.job.currentSortDirection == 'asc' && this.job.currentSortColumn == type)
                    return 'fa fa-arrow-down'
                else if (this.job.currentSortDirection == 'desc' && this.job.currentSortColumn == type)
                    return 'fa fa-arrow-down open'
                else
                    return 'fa fa-arrow-down open fa-disabled'
            },
            // Paginating the table data
            jobMakePagination(meta) {
                let pagination = {
                    per_page: this.job.pagination.per_page,
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    total: meta.total,
                    from: meta.from,
                    to: meta.to,
                    perPageItems: this.job.pagination.perPageItems
                }
                this.job.pagination = pagination;
            },
            jobAlljobRowSelect() {
                var self = this;
                Vue.nextTick(function () {
                    self.job.selectedIds = []

                    if (self.job.all) {
                        self.sortedJob.forEach(job => {
                            self.job.selectedIds.push(job.id.toString())
                        })
                    }
                    else {
                        if (self.sortedJob.length == self.job.selectedIds.length) {
                            self.job.selectedIds = []
                        }
                    }
                })
            },
            jobRowSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    if (self.sortedJob.length == self.job.selectedIds.length) {
                        self.job.all = true
                    }
                    else {
                        self.job.all = false
                    }
                })
            },
            jobColumnSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    console.log(self.job.selectedColumns)
                })
            },
            jobLoad(page) {
                NProgress.start()
                let url = `/api/job-list-by-assetid-datatable/${this.$route.params.id}`
                let params = `?page=${page}&per_page=${this.job.pagination.per_page}&search=${this.job.search.global}`

                this.$http.get(url + params,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(({ data }) => {
                        console.log('jobs', data)
                        this.jobs = data.data
                        NProgress.done()
                        this.jobMakePagination(data.meta)
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
            jobPageChange(page) {
                if (page >= 1 && page <= this.job.pagination.last_page) this.jobLoad(page)
            },
            jobPerPageChanged(count) {
                this.job.pagination.per_page = count
                this.jobLoad(1)
            },
            jobClearSearch() {
                if (this.job.search.global.length) {
                    this.job.search.global = ''
                    this.jobLoad(1)
                }

                this.job.search.description = ''
                this.job.search.job_number = ''
                this.job.search.job_type_name = ''
                this.job.search.job_priority = ''
                this.job.search.job_status = ''
                this.job.search.tech_name = ''
                this.job.search.comment = ''
            },
            jobOnSelectPageCount(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption, id)
                    this.job.pagination.per_page = selectedOption
                    this.jobLoad(1)
                }
            },
            jobCheckColumnVisibility(column) {
                return this.job.selectedColumns.indexOf(column) !== -1 ? true : false
            },
            //end of job datatable
        },
        computed: {
            filteredJob: function () {
                var self = this
                return this.jobs.filter(function (job) {
                    return job.description ? job.description.toLowerCase().indexOf(self.job.search.description.toLowerCase()) >= 0 : true
                })
                    .filter(function (job) {
                        return job.job_number ? job.job_number.toLowerCase().indexOf(self.job.search.job_number.toLowerCase()) >= 0 : true
                    })
                    .filter(function (job) {
                        return job.job_type_name ? job.job_type_name.toLowerCase().indexOf(self.job.search.job_type_name.toLowerCase()) >= 0 : true
                    })
                    .filter(function (job) {
                        return job.job_priority ? job.job_priority.toString().indexOf(self.job.search.job_priority.toString()) >= 0 : true
                    })
                    .filter(function (job) {
                        return job.job_status ? job.job_status.toString().indexOf(self.job.search.job_status.toString()) >= 0 : true
                    })
                    .filter(function (job) {
                        return job.tech_name ? job.tech_name.toString().indexOf(self.job.search.tech_name.toString()) >= 0 : true
                    })
                    .filter(function (job) {
                        return job.comment ? job.comment.toString().indexOf(self.job.search.comment.toString()) >= 0 : true
                    })
            },
            sortedJob: function () {
                return this.filteredJob.sort((a, b) => {
                    let modifier = 1
                    if (this.job.currentSortDirection === 'desc') modifier = -1

                    let temp = `.${this.job.currentSortColumn}`
                    if (eval(`a${temp}`) < eval(`b${temp}`)) return -1 * modifier
                    if (eval(`a${temp}`) > eval(`b${temp}`)) return 1 * modifier
                    return 0
                })
            },
            JobPages() {
                let start = _.max([this.job.pagination.current_page - 2, 1])
                let end = _.min([start + 5, this.job.pagination.last_page + 1])
                start = _.max([end - 5, 1])
                return _.range(start, end)
            },
        },
    };
</script>
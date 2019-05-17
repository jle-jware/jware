<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <div v-if="$store.state.user.contact_type != 'Technician' && $store.state.user.contact_type != 'Manager'" class="form-group form-actions">

                    <router-link :to="{  }" class="btn btn-link pull-right">
                        <i class="fas fa-sign-out-alt fa-3x action" @click.prevent="goToPreviousPage()"
                            title="Exit From This Page"></i>
                    </router-link>
                    <router-link :to="{  }" tag="button" :disabled="isSubmitDisabled" @click.native="submit(null)" class="btn btn-link pull-right">
                        <i class="far fa-save fa-3x action"  title="Save"></i>
                    </router-link>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="row">
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <div class="form-group col-sm-3">
                                <label for>Job Type *</label>
                                <multiselect :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit"
                                    v-model="selectedJobType" :class="{ 'is-danger': !job.job_type_id }"
                                    :options="jobTypes" @select="onSelectJobType" track-by="id" label="name"
                                    placeholder="Select Job Type" selectLabel deselectLabel selectedLabel="Remove"
                                    v-validate="'required'"></multiselect>
                            </div>
                            <div class="form-group col-sm-3">
                                <b-form-fieldset label="Job Number">
                                    <b-form-input type="text" disabled v-model="job.job_number" placeholder="Please Enter Job Number"></b-form-input>
                                </b-form-fieldset>
                            </div>
                            <div class="form-group col-sm-3">
                                <label for>Job Priority *</label>
                                <multiselect :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit"
                                    v-model="selectedPriority" :class="{ 'is-danger': !job.priority_id }"
                                    :options="priorities" @select="onSelectPriority" track-by="id" label="name"
                                    placeholder="Select Priority" selectLabel deselectLabel selectedLabel="Remove"
                                    v-validate="'required'"></multiselect>
                            </div>
                            <div class="form-group col-sm-3">
                                <label for>Job Status *</label>
                                <multiselect :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit"
                                    v-model="selectedjobStat" :class="{ 'is-danger': !job.job_stat_id }"
                                    :options="jobStats" @select="onSelectjobStat" track-by="id" label="type"
                                    placeholder="Select Job Status" selectLabel deselectLabel selectedLabel="Remove"
                                    v-validate="'required'"></multiselect>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for>Select Asset *</label>
                                <multiselect
                                    :disabled="isFromAsset || placementMode || $store.state.user.contact_type == 'Technician' && edit"
                                    ref="assetQuery" v-model="selectedAsset" label="custom" track-by="id"
                                    placeholder="Type & press enter to search" open-direction="bottom" :options="assets"
                                    :searchable="true" :internal-search="false" :loading="isLoading"
                                    :show-no-results="false" :hide-selected="true" @select="onSelectAsset"
                                    @keyup.enter.native="fetchAssetsBySearch()" :block-keys="['Tab', 'Enter']">
                                </multiselect>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for>Select Customer *</label>
                                <!-- <multiselect v-model="selectedCustomer" :options="customers" :class="{ 'is-danger': !job.company_id }"
                                    @select="onSelectCustomer" track-by="id" label="name" placeholder="Select Customer"
                selectLabel deselectLabel selectedLabel="Remove" v-validate="'required'"></multiselect>-->
                                <multiselect
                                    :disabled="isFromAsset || $store.state.user.contact_type == 'Technician' && edit"
                                    ref="companyQuery" v-model="selectedCustomer" label="name" track-by="id"
                                    placeholder="Type & Enter " open-direction="bottom" v-validate="'required'"
                                    :class="{ 'is-danger': !job.company_id }" :options="customers" :searchable="true"
                                    :internal-search="false" :loading="isLoading" :show-no-results="false"
                                    :hide-selected="true" @select="onSelectCustomer"
                                    @keyup.enter.native="fetchCompanyBySearch()" :block-keys="['Tab', 'Enter']">
                                </multiselect>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for>Location List *</label>
                                <multiselect
                                    :disabled="isFromAsset || $store.state.user.contact_type == 'Technician' && edit"
                                    v-model="selectedLocation" :options="locations"
                                    :class="{ 'is-danger': !job.location_id }" @select="onSelectLocation" track-by="id"
                                    label="full_address" placeholder="Select Location" selectLabel deselectLabel
                                    selectedLabel="Remove" v-validate="'required'"></multiselect>
                            </div>
                        </div>
                        <div v-if="qualityMode || testMode" class="row">
                            <div class="form-group col-sm-4">
                                <label for>Inspection Description</label>
                                <multiselect :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit"
                                    v-model="selectedInspectionDescription" :options="assetInspections"
                                    @select="onSelectInspectionDescription" track-by="id" label="description"
                                    placeholder="Select Inspection Description" selectLabel deselectLabel
                                    selectedLabel="Remove"></multiselect>
                            </div>
                            <div class="form-group col-sm-4">
                                <br>
                                <label>Frequency:</label>
                                <label>{{selectedInspectionDescription.frequency}} Days</label>
                            </div>
                            <div class="form-group col-sm-4">
                                <br>
                                <label>Group:</label>
                                <label>{{selectedInspectionDescription.group}}</label>
                            </div>
                        </div>
                        <div v-if="qualityMode || testMode" class="row">
                            <div class="form-group col-sm-4">
                                <br>
                                <label>Criteria:</label>
                                <label>{{selectedInspectionDescription.inspection_criteria}}</label>
                            </div>
                            <div class="form-group col-sm-4">
                                <br>
                                <label>Comment:</label>
                                <label>{{selectedInspectionDescription.comment}}</label>
                            </div>
                            <div class="form-group col-sm-4">
                                <br>
                                <label>Test Sheet:</label>
                                <label @click.prevent="openFile(selectedInspectionDescription.file)">
                                    <a href="#">{{selectedInspectionDescription.file}}</a>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for>Description *</label>
                                <wysiwyg v-model="job.description" v-validate="'required'" name="description"
                                    data-vv-as="description" type="text"></wysiwyg>
                                <span class="is-danger">{{ errors.first('description') }}</span>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for>Comment</label>
                                <wysiwyg v-model="job.comment" name="comment" data-vv-as="comment" type="text">
                                </wysiwyg>
                            </div>
                        </div>
                    </div>
                </div>
                <b-tabs>
                    <b-tab @click="selectedTab('allocation')" title="Job Allocation" active>
                        <form>
                            <div class="card border-0">
                                <div class="card-body">
                                    <div class="form-group"></div>
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <div class="row">
                                                <div class="form-group col-sm-4">
                                                    <label for>Job Raised By *</label>
                                                    <multiselect
                                                        :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit"
                                                        v-model="selectedUser" :options="users" @select="onSelectUser"
                                                        track-by="id" label="full_name" placeholder="Select User"
                                                        selectLabel deselectLabel selectedLabel="Remove"></multiselect>
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for>Allocated Contractor *</label>
                                                    <multiselect
                                                        :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit"
                                                        v-model="selectedContractor"
                                                        :class="{ 'is-danger': !job.contractor_id }"
                                                        :options="contractors" @select="onSelectContract" track-by="id"
                                                        label="name" placeholder="Select Contractor" selectLabel
                                                        deselectLabel selectedLabel="Remove" v-validate="'required'">
                                                    </multiselect>
                                                </div>
                                                <div class="form-group col-sm-4">
                                                    <label for>Allocated Technician *</label>
                                                    <multiselect
                                                        :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit"
                                                        v-model="selectedTechnician" :options="technicians"
                                                        @select="onSelectTechnician" track-by="id" label="full_name"
                                                        placeholder="Select Technician" selectLabel deselectLabel
                                                        selectedLabel="Remove"></multiselect>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <b-form-fieldset label="Job Raised">
                                                <datetime
                                                    :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit"
                                                    v-model="job.job_raised_date" type="datetime"
                                                    input-format="YYYY-MM-DD HH:mm" wrapper-class="my-wrapper-class"
                                                    input-class="form-control" placeholder="Select date"
                                                    moment-locale="es" :i18n="{ok:'De acuerdo', cancel:'Cancelar'}"
                                                    :disabled-dates="['2017-09-07', ['2017-09-25', '2017-10-05']]"
                                                    max-date="2017-12-10" min-date="2017-07-10" zone="Pacific/Auckland" value-zone="Pacific/Auckland" monday-first
                                                    auto-continue auto-close required></datetime>
                                            </b-form-fieldset>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <b-form-fieldset label="Required Finishing Date">
                                                <datetime
                                                    :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit"
                                                    v-model="job.required_date" type="datetime"
                                                    input-format="YYYY-MM-DD HH:mm" wrapper-class="my-wrapper-class"
                                                    input-class="form-control" placeholder="Select date"
                                                    moment-locale="es" :i18n="{ok:'De acuerdo', cancel:'Cancelar'}"
                                                    :disabled-dates="['2017-09-07', ['2017-09-25', '2017-10-05']]"
                                                    max-date="2017-12-10" min-date="2017-07-10" zone="Pacific/Auckland" value-zone="Pacific/Auckland" monday-first
                                                    auto-continue auto-close required></datetime>
                                            </b-form-fieldset>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <b-form-fieldset label="Contact Person">
                                                <b-form-input
                                                    :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit"
                                                    type="text" v-model="job.contact_person"
                                                    placeholder="Enter Contact Name"></b-form-input>
                                            </b-form-fieldset>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <!-- <b-form-fieldset label="Phone Number">
                                                <vue-tel-input
                                                    :disabled="$store.state.user.contact_type == 'Technician' && edit"
                                                    v-model="job.contact_number" @onInput="onInput"
                                                    :preferredCountries="['us', 'gb', 'ua']">
                                                </vue-tel-input>
                                            </b-form-fieldset> -->
                                            <b-form-fieldset label="Phone Number">
                                                <b-form-input type="text" :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit" name="phone_number" v-model="job.contact_number" placeholder="Enter Phone Number"></b-form-input>
                                            </b-form-fieldset>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <div class="card">
                                                <div class="card-header">Allocate Spare Parts</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-12">
                                                            <input type="text" class="form-control"
                                                                v-model="spareJob.search.global"
                                                                v-on:keyup.enter="spareJobLoad(1)"
                                                                placeholder="Type and press enter to search"
                                                                title="Search Item">
                                                        </div>
                                                        <div class="col-md-9 col-sm-12 text-right header-menu-nav">
                                                            <slot name="navigations"></slot>

                                                            <router-link
                                                                :to="{ path: `/SpareJob/${$route.params.id}/create`, params: $route.params.id}"
                                                                class="btn btn-link">
                                                                <i class="fa fa-plus-square icon" title="Add Item"></i>
                                                            </router-link>
                                                            <button class="btn btn-link"
                                                                @click.prevent="spareJobClearSearch"
                                                                title="Clear Search">
                                                                <i class="fas fa-eraser icon"></i>
                                                            </button>

                                                            <button :disabled="!spareJob.selectedIds.length"
                                                                class="btn btn-link" @click.prevent="spareJobErase"
                                                                title="Delete Item">
                                                                <i class="fa fa-trash icon"></i>
                                                            </button>
                                                            <b-dropdown variant="link" no-caret>
                                                                <template slot="button-content">
                                                                    <i class="fas fa-columns icon"></i>
                                                                </template>
                                                                <div class="selected-columns">
                                                                    <li v-for="(column, index) in spareJob.columns"
                                                                        :key="index">
                                                                        <b-form-checkbox
                                                                            v-model="spareJob.selectedColumns"
                                                                            @change="spareJobColumnSelect"
                                                                            :value="column.name">{{column.label}}
                                                                        </b-form-checkbox>
                                                                    </li>
                                                                </div>
                                                            </b-dropdown>
                                                            <download-excel :data="sortedSpareJob" class="btn">
                                                                <i class="fa fa-download icon"></i>
                                                            </download-excel>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <table class="table table-striped table-responsive">
                                                                <thead>
                                                                    <!-- Column Sort -->
                                                                    <tr>
                                                                        <th v-if="spareJobCheckColumnVisibility('all')">
                                                                            All</th>
                                                                        <th v-if="spareJobCheckColumnVisibility('spare_name')"
                                                                            @click="spareJobSortByColumn('spare_name')"
                                                                            class="text-center sortable">
                                                                            Spare Name
                                                                            <i
                                                                                :class="spareJobSetSelectedColumnSortIcon('spare_name')"></i>
                                                                        </th>
                                                                        <th v-if="spareJobCheckColumnVisibility('allocated')"
                                                                            @click="spareJobSortByColumn('allocated')"
                                                                            class="text-center sortable">
                                                                            Allocated
                                                                            <i
                                                                                :class="spareJobSetSelectedColumnSortIcon('allocated')"></i>
                                                                        </th>
                                                                        <th v-if="spareJobCheckColumnVisibility('used')"
                                                                            @click="spareJobSortByColumn('used')"
                                                                            class="text-center sortable">
                                                                            Used
                                                                            <i
                                                                                :class="spareJobSetSelectedColumnSortIcon('used')"></i>
                                                                        </th>
                                                                        <th v-if="spareJobCheckColumnVisibility('comment')"
                                                                            @click="spareJobSortByColumn('comment')"
                                                                            class="text-center sortable">
                                                                            Comment
                                                                            <i
                                                                                :class="spareJobSetSelectedColumnSortIcon('comment')"></i>
                                                                        </th>
                                                                    </tr>
                                                                    <!-- Column Search -->
                                                                    <tr>
                                                                        <th v-if="spareJobCheckColumnVisibility('all')">
                                                                            <b-form-checkbox name="checkAll"
                                                                                title="Check all"
                                                                                @change="spareJobAllspareJobRowSelect"
                                                                                v-model="spareJob.all">
                                                                            </b-form-checkbox>
                                                                        </th>
                                                                        <th
                                                                            v-if="spareJobCheckColumnVisibility('spare_name')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="spareJob.search.spare_name">
                                                                        </th>
                                                                        <th
                                                                            v-if="spareJobCheckColumnVisibility('allocated')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="spareJob.search.allocated">
                                                                        </th>
                                                                        <th
                                                                            v-if="spareJobCheckColumnVisibility('used')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="spareJob.search.used">
                                                                        </th>
                                                                        <th
                                                                            v-if="spareJobCheckColumnVisibility('comment')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="spareJob.search.comment">
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <!-- Table Body -->
                                                                <tbody>
                                                                    <tr v-for="(aspareJob, index) in sortedSpareJob"
                                                                        :key="index">
                                                                        <td v-if="spareJobCheckColumnVisibility('all')">
                                                                            <b-form-checkbox
                                                                                v-model="spareJob.selectedIds"
                                                                                @change="spareJobRowSelect"
                                                                                :value="aspareJob.id"></b-form-checkbox>
                                                                        </td>
                                                                        <td
                                                                            v-if="spareJobCheckColumnVisibility('spare_name')">
                                                                            <router-link
                                                                                :to="{ path: `/spareJob/${aspareJob.id}`}">
                                                                                {{aspareJob.spare_name }}
                                                                            </router-link>
                                                                        </td>
                                                                        <td
                                                                            v-if="spareJobCheckColumnVisibility('allocated')">
                                                                            {{aspareJob.allocated }}
                                                                        </td>
                                                                        <td
                                                                            v-if="spareJobCheckColumnVisibility('used')">
                                                                            {{ aspareJob.used }}
                                                                        </td>
                                                                        <td
                                                                            v-if="spareJobCheckColumnVisibility('comment')">
                                                                            {{
                                      aspareJob.comment }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <!-- Table Body End -->
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-1 justify-content-center align-self-center">
                                                            <multiselect v-model="spareJob.pagination.per_page"
                                                                @select="spareJobOnSelectPageCount"
                                                                :options="spareJob.pagination.perPageItems"
                                                                :searchable="false" :show-labels="false"
                                                                placeholder="Items / Page"></multiselect>
                                                        </div>

                                                        <div
                                                            class="col-lg-3 justify-content-center align-self-center text-center">
                                                        </div>

                                                        <div
                                                            class="col-lg-4 justify-content-center align-self-center text-center">
                                                            <div>
                                                                Total {{spareJob.pagination.total}} items, Per page
                                                                {{spareJob.pagination.per_page}},
                                                                Showing {{spareJob.pagination.from}} 〜
                                                                {{spareJob.pagination.to}}
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 justify-content-center align-self-center">
                                                            <ul class="pagination pagination-custom pull-right">
                                                                <li
                                                                    :class="{disabled: spareJob.pagination.current_page <= 1}">
                                                                    <a href="#"
                                                                        @click.prevent="spareJobPageChange(1)">&laquo;</a>
                                                                </li>
                                                                <li
                                                                    :class="{disabled: spareJob.pagination.current_page <= 1}">
                                                                    <a href="#"
                                                                        @click.prevent="spareJobPageChange(spareJob.pagination.current_page - 1)">&lt;</a>
                                                                </li>
                                                                <li v-for="page in spareJobPages" :key="page"
                                                                    :class="{active: page === spareJob.pagination.current_page}">
                                                                    <a href="#"
                                                                        @click.prevent="spareJobPageChange(page)">{{page}}</a>
                                                                </li>
                                                                <li
                                                                    :class="{disabled: spareJob.pagination.current_page >= spareJob.pagination.last_page}">
                                                                    <a href="#"
                                                                        @click.prevent="spareJobPageChange(spareJob.pagination.current_page + 1)">&gt;</a>
                                                                </li>
                                                                <li
                                                                    :class="{disabled: spareJob.pagination.current_page >= spareJob.pagination.last_page}">
                                                                    <a href="#"
                                                                        @click.prevent="spareJobPageChange(spareJob.pagination.last_page)">&raquo;</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <a href="#" class="btn btn-link">
                                                <i class="fa fa-envelope fa-3x action"
                                                    @click.prevent="emailBoxJobAllocation()"
                                                    title="Click Here To Print RPO">
                                                    <font size="3" class="text-style">Send Email</font>
                                                </i>
                                            </a>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <a href="#" class="btn btn-link">
                                                <i class="fa fa-print fa-3x action" @click.prevent="createPDF()"
                                                    title="Click Here To Print RPO">
                                                    <font size="3" class="text-style">Print RPO</font>
                                                </i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </b-tab>
                    <b-tab @click="selectedTab('completion')" v-if="edit" title="Job Completion">
                        <br>
                        <div class="card-body">
                            <div class="row">
                                <div :hidden="!edit" class="col-sm-12">
                                    <div role="tablist" class="company-collapse">
                                        <b-card no-body class="mb-1">
                                            <b-card-header header-tag="header" class="p-1" role="tab">
                                                <b-btn block href="#" v-b-toggle.accordion1 variant="danger">
                                                    Asset
                                                    Utilization List
                                                </b-btn>
                                            </b-card-header>
                                            <b-collapse id="accordion1" accordion="my-accordion" role="tabpanel">
                                                <b-card-body>
                                                    <div class="row">
                                                        <div class="col-lg-4"></div>
                                                        <div class="col-lg-8">
                                                            <div>
                                                                Short Listed {{sortedAssetUtilization.length}}Total {{assetUtilization.pagination.total}} items, Per
                                                                page {{assetUtilization.pagination.per_page}},
                                                                Showing {{assetUtilization.pagination.from}} 〜
                                                                {{assetUtilization.pagination.to}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-12">
                                                            <input type="text" class="form-control"
                                                                v-model="assetUtilization.search.global"
                                                                v-on:keyup.enter="assetUtilizationLoad(1)"
                                                                placeholder="Search For All Records"
                                                                title="Type and press enter to search">
                                                        </div>
                                                        <div class="col-md-2 col-sm-12">
                                                             <button class="btn btn-link"
                                                                @click.prevent="assetUtilizationClearSearch"
                                                                title="Clear Search">
                                                                <i class="fa fa-window-close icon"></i>
                                                            </button>
                                                        </div>
                                                        <div class="col-md-7 col-sm-12 text-right header-menu-nav">
                                                            <slot name="navigations"></slot>

                                                            <router-link
                                                                :to="{ path: `/asset-utilization/create`, query: { job:$route.params.id}}"
                                                                class="btn btn-link">
                                                                <i class="fa fa-plus-square icon" title="Add Asset utilization"></i>
                                                            </router-link>

                                                            <button :disabled="!assetUtilization.selectedIds.length"
                                                                class="btn btn-link"
                                                                @click.prevent="assetUtilizationErase"
                                                                title="Delete Item">
                                                                <i class="fa fa-trash icon"></i>
                                                            </button>
                                                            <b-dropdown variant="link" no-caret>
                                                                <template slot="button-content">
                                                                    <i class="fas fa-columns icon" title="Customize Columns"></i>
                                                                </template>
                                                                <div class="selected-columns">
                                                                    <li v-for="(column, index) in assetUtilization.columns"
                                                                        :key="index">
                                                                        <b-form-checkbox
                                                                            v-model="assetUtilization.selectedColumns"
                                                                            @change="assetUtilizationColumnSelect"
                                                                            :value="column.name">{{column.label}}
                                                                        </b-form-checkbox>
                                                                    </li>
                                                                </div>
                                                            </b-dropdown>
                                                            <download-excel :data="sortedAssetUtilization" class="btn">
                                                                <i class="fa fa-download icon" title="Export"></i>
                                                            </download-excel>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <table class="table table-striped table-responsive">
                                                                <thead>
                                                                    <!-- Column Sort -->
                                                                    <tr>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('all')">
                                                                            All</th>
                                                                        <th v-if="assetUtilizationCheckColumnVisibility('full_date')"
                                                                            @click="assetUtilizationSortByColumn('full_date')"
                                                                            class="text-center sortable">
                                                                            Date
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('full_date')"></i>
                                                                        </th>
                                                                        <th v-if="assetUtilizationCheckColumnVisibility('utilization_type')"
                                                                            @click="assetUtilizationSortByColumn('utilization_type')"
                                                                            class="text-center sortable">
                                                                            Type
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('utilization_type')"></i>
                                                                        </th>
                                                                        <th v-if="assetUtilizationCheckColumnVisibility('value')"
                                                                            @click="assetUtilizationSortByColumn('value')"
                                                                            class="text-center sortable">
                                                                            Utilization value
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('value')"></i>
                                                                        </th>
                                                                        <th v-if="assetUtilizationCheckColumnVisibility('utilization_unit')"
                                                                            @click="assetUtilizationSortByColumn('utilization_unit')"
                                                                            class="text-center sortable">
                                                                            Measure Unit
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('utilization_unit')"></i>
                                                                        </th>
                                                                        <th v-if="assetUtilizationCheckColumnVisibility('rate')"
                                                                            @click="assetUtilizationSortByColumn('rate')"
                                                                            class="text-center sortable">
                                                                            Rate
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('rate')"></i>
                                                                        </th>
                                                                        <th v-if="assetUtilizationCheckColumnVisibility('rate_unit')"
                                                                            @click="assetUtilizationSortByColumn('rate_unit')"
                                                                            class="text-center sortable">
                                                                           No of Days
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('rate_unit')"></i>
                                                                        </th>
                                                                        <th v-if="assetUtilizationCheckColumnVisibility('clear_calculation_description')"
                                                                            @click="assetUtilizationSortByColumn('clear_calculation_description')"
                                                                            class="text-center sortable">
                                                                           Mother Board Changed
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('clear_calculation_description')"></i>
                                                                        </th>
                                                                         <th v-if="assetUtilizationCheckColumnVisibility('parts_changed')"
                                                                            @click="assetUtilizationSortByColumn('parts_changed')"
                                                                            class="text-center sortable">
                                                                          Filter Changed
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('parts_changed')"></i>
                                                                        </th>
                                                                        
                                                                    </tr>
                                                                    <!-- Column Search -->
                                                                    <tr>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('all')">
                                                                            <b-form-checkbox name="checkAll"
                                                                                title="Check all"
                                                                                @change="assetUtilizationAllassetUtilizationRowSelect"
                                                                                v-model="assetUtilization.all">
                                                                            </b-form-checkbox>
                                                                        </th>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('full_date')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.full_date">
                                                                        </th>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('utilization_type')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.utilization_type">
                                                                        </th>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('value')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.value">
                                                                        </th>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('utilization_unit')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.utilization_unit">
                                                                        </th>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('rate')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.rate">
                                                                        </th>
                                                                         <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('rate_unit')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.rate_unit">
                                                                        </th>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('clear_calculation_decription')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.rate_unit">
                                                                        </th>
                                                                         <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('clear_calculation_description')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.clear_calculation_description">
                                                                        </th>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('parts_changed')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.parts_changed">
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <!-- Table Body -->
                                                                <tbody>
                                                                    <tr v-for="(assetUtilization, index) in sortedAssetUtilization"
                                                                        :key="index">
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('all')">
                                                                            <b-form-checkbox
                                                                                v-model="assetUtilization.selectedIds"
                                                                                @change="assetUtilizationRowSelect"
                                                                                :value="assetUtilization.id">
                                                                            </b-form-checkbox>
                                                                        </td>
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('full_date')">
                                                                            <router-link
                                                                                :to="{ path: `/assetUtilization/${assetUtilization.id}`, query: { job:$route.params.id}}">
                                                                                {{ assetUtilization.full_date }}
                                                                            </router-link>
                                                                        </td>
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('utilization_type')">
                                                                            {{ assetUtilization.utilization_type }}</td>
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('value')">
                                                                            {{ assetUtilization.value }}</td>
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('utilization_unit')">
                                                                            {{ assetUtilization.utilization_unit }}</td>
                                                                        <td class="text-sm-right is-danger"
                                                                            v-if="assetUtilizationCheckColumnVisibility('rate')">
                                                                            {{ Number(utilizationCalculation(sortedAssetUtilization, assetUtilization)).toFixed(2) }}
                                                                        </td>
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('rate_unit')">
                                                                            {{ sortedAssetUtilization.length == (index + 1 ) ? '' :
                                                                                (sortedAssetUtilization[index + 1].utilization_type_id == assetUtilization.utilization_type_id ?
                                                                                calcBusinessDays(sortedAssetUtilization[index + 1].date, assetUtilization.date) : 0) }}
                                                                        </td>
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('clear_calculation_description')">
                                                                            {{ assetUtilization.clear_calculation_description }}
                                                                        </td>
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('parts_changed')">
                                                                            {{ assetUtilization.parts_changed }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <!-- Table Body End -->
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-1 justify-content-center align-self-center">
                                                            <multiselect v-model="assetUtilization.pagination.per_page"
                                                                @select="assetUtilizationOnSelectPageCount"
                                                                :options="assetUtilization.pagination.perPageItems"
                                                                :searchable="false" :show-labels="false"
                                                                placeholder="Items / Page"></multiselect>
                                                        </div>

                                                        <div
                                                            class="col-lg-3 justify-content-center align-self-center text-center">
                                                        </div>

                                                        <div
                                                            class="col-lg-4 justify-content-center align-self-center text-center">
                                                            <div>
                                                                Short Listed {{sortedAssetUtilization.length}}Total {{assetUtilization.pagination.total}} items, Per
                                                                page {{assetUtilization.pagination.per_page}},
                                                                Showing {{assetUtilization.pagination.from}} 〜
                                                                {{assetUtilization.pagination.to}}
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 justify-content-center align-self-center">
                                                            <ul class="pagination pagination-custom pull-right">
                                                                <li
                                                                    :class="{disabled: assetUtilization.pagination.currate_page <= 1}">
                                                                    <a href="#"
                                                                        @click.prevent="assetUtilizationPageChange(1)">&laquo;</a>
                                                                </li>
                                                                <li
                                                                    :class="{disabled: assetUtilization.pagination.currate_page <= 1}">
                                                                    <a href="#"
                                                                        @click.prevent="assetUtilizationPageChange(assetUtilization.pagination.currate_page - 1)">&lt;</a>
                                                                </li>
                                                                <li v-for="page in AssetUtilizationPages" :key="page"
                                                                    :class="{active: page === assetUtilization.pagination.currate_page}">
                                                                    <a href="#"
                                                                        @click.prevent="assetUtilizationPageChange(page)">{{page}}</a>
                                                                </li>
                                                                <li
                                                                    :class="{disabled: assetUtilization.pagination.currate_page >= assetUtilization.pagination.last_page}">
                                                                    <a href="#"
                                                                        @click.prevent="assetUtilizationPageChange(assetUtilization.pagination.currate_page + 1)">&gt;</a>
                                                                </li>
                                                                <li
                                                                    :class="{disabled: assetUtilization.pagination.currate_page >= assetUtilization.pagination.last_page}">
                                                                    <a href="#"
                                                                        @click.prevent="assetUtilizationPageChange(assetUtilization.pagination.last_page)">&raquo;</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </b-card-body>
                                            </b-collapse>
                                        </b-card>
                                    </div>
                                </div>
                                <div :hidden="!edit" class="col-sm-12">
                                    <div role="tablist" class="company-collapse">
                                        <b-card no-body class="mb-1">
                                            <b-card-header header-tag="header" class="p-1" role="tab">
                                                <b-btn block href="#" v-b-toggle.accordion2 variant="danger">
                                                    Spare Parts
                                                    List
                                                </b-btn>
                                            </b-card-header>
                                            <b-collapse id="accordion2" accordion="my-accordion" role="tabpane2">
                                                <b-card-body>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-3 col-sm-12">
                                                                <input type="text" class="form-control"
                                                                    v-model="spareJob.search.global"
                                                                    v-on:keyup.enter="spareJobLoad(1)"
                                                                    placeholder="Search For All Records"
                                                                    title="Type and press enter to search">
                                                            </div>
                                                            <div class="col-md-2 col-sm-12">
                                                                <button class="btn btn-link"
                                                                    @click.prevent="spareJobClearSearch"
                                                                    title="Clear Search">
                                                                    <i class="fa fa-window-close icon"></i>
                                                                </button>
                                                            </div>
                                                            <div class="col-md-7 col-sm-12 text-right header-menu-nav">
                                                                <slot name="navigations"></slot>

                                                                <router-link
                                                                    :to="{ path: `/SpareJob/${$route.params.id}/create`, params: $route.params.id}"
                                                                    class="btn btn-link">
                                                                    <i class="fa fa-plus-square icon"
                                                                        title="Add New Spare Job"></i>
                                                                </router-link>

                                                                <button :disabled="!spareJob.selectedIds.length"
                                                                    class="btn btn-link" @click.prevent="spareJobErase"
                                                                    title="Delete Item">
                                                                    <i class="fa fa-trash icon"></i>
                                                                </button>
                                                                <b-dropdown variant="link" no-caret>
                                                                    <template slot="button-content">
                                                                        <i class="fas fa-columns icon" title="Customize Columns"></i>
                                                                    </template>
                                                                    <div class="selected-columns">
                                                                        <li v-for="(column, index) in spareJob.columns"
                                                                            :key="index">
                                                                            <b-form-checkbox
                                                                                v-model="spareJob.selectedColumns"
                                                                                @change="spareJobColumnSelect"
                                                                                :value="column.name">{{column.label}}
                                                                            </b-form-checkbox>
                                                                        </li>
                                                                    </div>
                                                                </b-dropdown>
                                                                <download-excel :data="sortedSpareJob" class="btn">
                                                                    <i class="fa fa-download icon" title="Export"></i>
                                                                </download-excel>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <table class="table table-striped table-responsive">
                                                                    <thead>
                                                                        <!-- Column Sort -->
                                                                        <tr>
                                                                            <th
                                                                                v-if="spareJobCheckColumnVisibility('all')">
                                                                                All</th>
                                                                            <th v-if="spareJobCheckColumnVisibility('spare_name')"
                                                                                @click="spareJobSortByColumn('spare_name')"
                                                                                class="text-center sortable">
                                                                                Spare Name
                                                                                <i
                                                                                    :class="spareJobSetSelectedColumnSortIcon('spare_name')"></i>
                                                                            </th>
                                                                            <th v-if="spareJobCheckColumnVisibility('allocated')"
                                                                                @click="spareJobSortByColumn('allocated')"
                                                                                class="text-center sortable">
                                                                                Allocated
                                                                                <i
                                                                                    :class="spareJobSetSelectedColumnSortIcon('allocated')"></i>
                                                                            </th>
                                                                            <th v-if="spareJobCheckColumnVisibility('used')"
                                                                                @click="spareJobSortByColumn('used')"
                                                                                class="text-center sortable">
                                                                                Used
                                                                                <i
                                                                                    :class="spareJobSetSelectedColumnSortIcon('used')"></i>
                                                                            </th>
                                                                            <th v-if="spareJobCheckColumnVisibility('comment')"
                                                                                @click="spareJobSortByColumn('comment')"
                                                                                class="text-center sortable">
                                                                                Comment
                                                                                <i
                                                                                    :class="spareJobSetSelectedColumnSortIcon('comment')"></i>
                                                                            </th>
                                                                        </tr>
                                                                        <!-- Column Search -->
                                                                        <tr>
                                                                            <th
                                                                                v-if="spareJobCheckColumnVisibility('all')">
                                                                                <b-form-checkbox name="checkAll"
                                                                                    title="Check all"
                                                                                    @change="spareJobAllspareJobRowSelect"
                                                                                    v-model="spareJob.all">
                                                                                </b-form-checkbox>
                                                                            </th>
                                                                            <th
                                                                                v-if="spareJobCheckColumnVisibility('spare_name')">
                                                                                <input type="text" placeholder="Search"
                                                                                    class="form-control"
                                                                                    v-model="spareJob.search.spare_name">
                                                                            </th>
                                                                            <th
                                                                                v-if="spareJobCheckColumnVisibility('allocated')">
                                                                                <input type="text" placeholder="Search"
                                                                                    class="form-control"
                                                                                    v-model="spareJob.search.allocated">
                                                                            </th>
                                                                            <th
                                                                                v-if="spareJobCheckColumnVisibility('used')">
                                                                                <input type="text" placeholder="Search"
                                                                                    class="form-control"
                                                                                    v-model="spareJob.search.used">
                                                                            </th>
                                                                            <th
                                                                                v-if="spareJobCheckColumnVisibility('comment')">
                                                                                <input type="text" placeholder="Search"
                                                                                    class="form-control"
                                                                                    v-model="spareJob.search.comment">
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <!-- Table Body -->
                                                                    <tbody>
                                                                        <tr v-for="(aspareJob, index) in sortedSpareJob"
                                                                            :key="index">
                                                                            <td
                                                                                v-if="spareJobCheckColumnVisibility('all')">
                                                                                <b-form-checkbox
                                                                                    v-model="spareJob.selectedIds"
                                                                                    @change="spareJobRowSelect"
                                                                                    :value="aspareJob.id">
                                                                                </b-form-checkbox>
                                                                            </td>
                                                                            <td
                                                                                v-if="spareJobCheckColumnVisibility('spare_name')">
                                                                                <router-link
                                                                                    :to="{ path: `/spareJob/${aspareJob.id}`}">
                                                                                    {{
                                          aspareJob.spare_name }}
                                                                                </router-link>
                                                                            </td>
                                                                            <td
                                                                                v-if="spareJobCheckColumnVisibility('allocated')">
                                                                                {{
                                        aspareJob.allocated }}
                                                                            </td>
                                                                            <td
                                                                                v-if="spareJobCheckColumnVisibility('used')">
                                                                                {{
                                        aspareJob.used }}
                                                                            </td>
                                                                            <td
                                                                                v-if="spareJobCheckColumnVisibility('comment')">
                                                                                {{
                                        aspareJob.comment }}
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <!-- Table Body End -->
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div
                                                                class="col-lg-1 justify-content-center align-self-center">
                                                                <multiselect v-model="spareJob.pagination.per_page"
                                                                    @select="spareJobOnSelectPageCount"
                                                                    :options="spareJob.pagination.perPageItems"
                                                                    :searchable="false" :show-labels="false"
                                                                    placeholder="Items / Page"></multiselect>
                                                            </div>

                                                            <div
                                                                class="col-lg-3 justify-content-center align-self-center text-center">
                                                            </div>

                                                            <div
                                                                class="col-lg-4 justify-content-center align-self-center text-center">
                                                                <div>
                                                                    Total {{spareJob.pagination.total}} items, Per page
                                                                    {{spareJob.pagination.per_page}},
                                                                    Showing {{spareJob.pagination.from}} 〜
                                                                    {{spareJob.pagination.to}}
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="col-lg-4 justify-content-center align-self-center">
                                                                <ul class="pagination pagination-custom pull-right">
                                                                    <li
                                                                        :class="{disabled: spareJob.pagination.current_page <= 1}">
                                                                        <a href="#"
                                                                            @click.prevent="spareJobPageChange(1)">&laquo;</a>
                                                                    </li>
                                                                    <li
                                                                        :class="{disabled: spareJob.pagination.current_page <= 1}">
                                                                        <a href="#"
                                                                            @click.prevent="spareJobPageChange(spareJob.pagination.current_page - 1)">&lt;</a>
                                                                    </li>
                                                                    <li v-for="page in spareJobPages" :key="page"
                                                                        :class="{active: page === spareJob.pagination.current_page}">
                                                                        <a href="#"
                                                                            @click.prevent="spareJobPageChange(page)">{{page}}</a>
                                                                    </li>
                                                                    <li
                                                                        :class="{disabled: spareJob.pagination.current_page >= spareJob.pagination.last_page}">
                                                                        <a href="#"
                                                                            @click.prevent="spareJobPageChange(spareJob.pagination.current_page + 1)">&gt;</a>
                                                                    </li>
                                                                    <li
                                                                        :class="{disabled: spareJob.pagination.current_page >= spareJob.pagination.last_page}">
                                                                        <a href="#"
                                                                            @click.prevent="spareJobPageChange(spareJob.pagination.last_page)">&raquo;</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </b-card-body>
                                            </b-collapse>
                                        </b-card>
                                    </div>
                                </div>
                                <div :hidden="!edit" class="col-sm-12">
                                    <div role="tablist" class="company-collapse">
                                        <b-card no-body class="mb-1">
                                            <b-card-header header-tag="header" class="p-1" role="tab">
                                                <b-btn block href="#" v-b-toggle.accordion3 variant="danger">
                                                    Image
                                                    Gallery
                                                </b-btn>
                                            </b-card-header>
                                            <b-collapse id="accordion3" accordion="my-accordion" role="tabpane3">
                                                <b-card-body>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-3 col-sm-12">
                                                                <input type="text" class="form-control"
                                                                    v-model="gallery.search.global"
                                                                    v-on:keyup.enter="galleryLoad(1)"
                                                                    placeholder="Type and press enter to search"
                                                                    title="Search Item">
                                                            </div>
                                                            <div class="col-md-2 col-sm-12">
                                                               <button class="btn btn-link"
                                                                    @click.prevent="galleryClearSearch"
                                                                    title="Clear Search">
                                                                    <i class="fa fa-window-close icon"></i>
                                                                </button>
                                                            </div>
                                                            <div class="col-md-7 col-sm-12 text-right header-menu-nav">
                                                                <slot name="navigations"></slot>

                                                                <router-link
                                                                    :to="{ path: `/gallery/${$route.params.id}/create`, params: $route.params.id }"
                                                                    class="btn btn-link">
                                                                    <i class="fa fa-plus-square icon"
                                                                        title="Add New Image"></i>
                                                                </router-link>

                                                                <button :disabled="!gallery.selectedIds.length"
                                                                    class="btn btn-link" @click.prevent="galleryErase"
                                                                    title="Delete Images">
                                                                    <i class="fa fa-trash icon"></i>
                                                                </button>
                                                                <b-dropdown variant="link" no-caret>
                                                                    <template slot="button-content">
                                                                        <i class="fas fa-columns icon" title="Customize Columns"></i>
                                                                    </template>
                                                                    <div class="selected-columns">
                                                                        <li v-for="(column, index) in gallery.columns"
                                                                            :key="index">
                                                                            <b-form-checkbox
                                                                                v-model="gallery.selectedColumns"
                                                                                @change="galleryColumnSelect"
                                                                                :value="column.name">{{column.label}}
                                                                            </b-form-checkbox>
                                                                        </li>
                                                                    </div>
                                                                </b-dropdown>
                                                                <download-excel :data="sortedGallery" class="btn">
                                                                    <i class="fa fa-download icon"></i>
                                                                </download-excel>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <table class="table table-striped table-responsive">
                                                                    <thead>
                                                                        <!-- Column Sort -->
                                                                        <tr>
                                                                            <th
                                                                                v-if="galleryCheckColumnVisibility('all')">
                                                                                All</th>
                                                                            <th v-if="galleryCheckColumnVisibility('description')"
                                                                                @click="gallerySortByColumn('description')"
                                                                                class="text-center sortable">
                                                                                Description
                                                                                <i
                                                                                    :class="gallerySetSelectedColumnSortIcon('description')"></i>
                                                                            </th>
                                                                            <!-- <th v-if="galleryCheckColumnVisibility('image')"
                                                                                    @click="gallerySortByColumn('image')"
                                                                                    class="text-center sortable">
                                                                                    Image
                                                                                    <i :class="gallerySetSelectedColumnSortIcon('image')"></i>
                                      </th>-->
                                                                        </tr>
                                                                        <!-- Column Search -->
                                                                        <tr>
                                                                            <th
                                                                                v-if="galleryCheckColumnVisibility('all')">
                                                                                <b-form-checkbox name="checkAll"
                                                                                    title="Check all"
                                                                                    @change="galleryAllgalleryRowSelect"
                                                                                    v-model="gallery.all">
                                                                                </b-form-checkbox>
                                                                            </th>
                                                                            <th
                                                                                v-if="galleryCheckColumnVisibility('description')">
                                                                                <input type="text" placeholder="Search"
                                                                                    class="form-control"
                                                                                    v-model="gallery.search.description">
                                                                            </th>
                                                                            <!-- <th v-if="galleryCheckColumnVisibility('image')">
                                                                                    <input type="text" placeholder="Search"
                                                                                        class="form-control" v-model="gallery.search.image" />
                                      </th>-->
                                                                        </tr>
                                                                    </thead>
                                                                    <!-- Table Body -->
                                                                    <tbody>
                                                                        <tr v-for="(glry, index) in sortedGallery"
                                                                            :key="index">
                                                                            <td
                                                                                v-if="galleryCheckColumnVisibility('all')">
                                                                                <b-form-checkbox
                                                                                    v-model="gallery.selectedIds"
                                                                                    @change="galleryRowSelect"
                                                                                    :value="glry.id"></b-form-checkbox>
                                                                            </td>
                                                                            <td
                                                                                v-if="galleryCheckColumnVisibility('description')">
                                                                                <router-link
                                                                                    :to="{ path: `/gallery/${glry.id}`}">
                                                                                    {{
                                          glry.description }}
                                                                                </router-link>
                                                                            </td>
                                                                            <!-- <td v-if="galleryCheckColumnVisibility('image')">
                                                                                    <router-link :to="{ path: `/gallery/${glry.id}`}">
                                                                                        <div class="image-container">
                                                                                            <img class="object-fit-cover" :src="glry.image || '/img/company.png'" />
                                                                                        </div>
                                                                                    </router-link>
                                      </td>-->
                                                                        </tr>
                                                                    </tbody>
                                                                    <!-- Table Body End -->
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div
                                                                class="col-lg-1 justify-content-center align-self-center">
                                                                <multiselect v-model="gallery.pagination.per_page"
                                                                    @select="galleryOnSelectPageCount"
                                                                    :options="gallery.pagination.perPageItems"
                                                                    :searchable="false" :show-labels="false"
                                                                    placeholder="Items / Page"></multiselect>
                                                            </div>

                                                            <div
                                                                class="col-lg-3 justify-content-center align-self-center text-center">
                                                            </div>

                                                            <div
                                                                class="col-lg-4 justify-content-center align-self-center text-center">
                                                                <div>
                                                                    Total {{gallery.pagination.total}} items, Per page
                                                                    {{gallery.pagination.per_page}},
                                                                    Showing {{gallery.pagination.from}} 〜
                                                                    {{gallery.pagination.to}}
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="col-lg-4 justify-content-center align-self-center">
                                                                <ul class="pagination pagination-custom pull-right">
                                                                    <li
                                                                        :class="{disabled: gallery.pagination.current_page <= 1}">
                                                                        <a href="#"
                                                                            @click.prevent="galleryPageChange(1)">&laquo;</a>
                                                                    </li>
                                                                    <li
                                                                        :class="{disabled: gallery.pagination.current_page <= 1}">
                                                                        <a href="#"
                                                                            @click.prevent="galleryPageChange(gallery.pagination.current_page - 1)">&lt;</a>
                                                                    </li>
                                                                    <li v-for="page in GalleryPages" :key="page"
                                                                        :class="{active: page === gallery.pagination.current_page}">
                                                                        <a href="#"
                                                                            @click.prevent="galleryPageChange(page)">{{page}}</a>
                                                                    </li>
                                                                    <li
                                                                        :class="{disabled: gallery.pagination.current_page >= gallery.pagination.last_page}">
                                                                        <a href="#"
                                                                            @click.prevent="galleryPageChange(gallery.pagination.current_page + 1)">&gt;</a>
                                                                    </li>
                                                                    <li
                                                                        :class="{disabled: gallery.pagination.current_page >= gallery.pagination.last_page}">
                                                                        <a href="#"
                                                                            @click.prevent="galleryPageChange(gallery.pagination.last_page)">&raquo;</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </b-card-body>
                                            </b-collapse>
                                        </b-card>
                                    </div>
                                </div>
                                <div :hidden="!edit" class="col-sm-12">
                                    <div role="tablist" class="company-collapse">
                                        <b-card no-body class="mb-1">
                                            <b-card-header header-tag="header" class="p-1" role="tab">
                                                <b-btn block href="#" v-b-toggle.accordion4 variant="danger">Job Sheet</b-btn>
                                            </b-card-header>
                                            <b-collapse id="accordion4" accordion="my-accordion" role="tabpane4">
                                                <b-card-body>
                                                    <form @submit.prevent="submitJobSheet()">
                                                        <div class="card border-0">
                                                            <div class="form-group form-actions">
                                                                <!-- <router-link :to="{ path: `/asset/${job.asset_id}` }"
                                                                    class="btn btn-link pull-right">
                                                                    <i class="fas fa-sign-out-alt fa-3x action"
                                                                        title="Exit From This Page"></i>
                                                                </router-link> -->
                                                                <router-link :to="{  }" class="btn btn-link pull-right">
                                                                    <i class="fas fa-sign-out-alt fa-3x action" @click.prevent="goToPreviousPage()"
                                                                        title="Exit From This Page"></i>
                                                                </router-link>
                                                                <router-link :to="{}" class="btn btn-link pull-right">
                                                                    <i class="fa fa-envelope fa-3x action"
                                                                        @click.prevent="emailBox(jobSheet.id)"
                                                                        title="Send Email"></i>
                                                                </router-link>
                                                                <router-link :to="{}" class="btn btn-link pull-right">
                                                                    <i class="fa fa-print fa-3x action"
                                                                        @click.prevent="createJobSheetPDF(jobSheet.id)"
                                                                        title="Download PDF"></i>
                                                                </router-link>
                                                                <router-link :to="{ path: '/assets' }" :disabled="isSubmitDisabledJobSheet"
                                                                    @click.native="submitJobSheet()" class="btn btn-link pull-right">
                                                                    <i class="far fa-save fa-3x action" title="Save"></i>
                                                                </router-link>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="form-group col-sm-3">
                                                                        <b-form-fieldset label="Date">
                                                                            <datetime v-model="jobSheet.job_date"
                                                                                type="datetime" input-format="YYYY-MM-DD HH:mm"
                                                                                wrapper-class="my-wrapper-class"
                                                                                input-class="form-control"
                                                                                placeholder="Select date" moment-locale="es"
                                                                                :i18n="{ok:'De acuerdo', cancel:'Cancelar'}"
                                                                                :disabled-dates="['2017-09-07', ['2017-09-25', '2017-10-05']]"
                                                                                max-date="2017-12-10" min-date="2017-07-10" zone="Pacific/Auckland" value-zone="Pacific/Auckland"
                                                                                monday-first auto-continue auto-close required>
                                                                            </datetime>
                                                                        </b-form-fieldset>
                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <b-form-fieldset label="On Site Time In">
                                                                            <vue-clock-picker class="form-control"
                                                                                v-model="jobSheet.timein"
                                                                                @beforeClose="onCloseTimeIn" ref="timeIn">
                                                                            </vue-clock-picker>
                                                                        </b-form-fieldset>
                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <b-form-fieldset label="Off Site Time Out">
                                                                            <vue-clock-picker class="form-control"
                                                                                v-model="jobSheet.timeout"
                                                                                @beforeClose="onCloseTimeOut" ref="timeOut">
                                                                            </vue-clock-picker>
                                                                        </b-form-fieldset>
                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <b-form-fieldset label="On Site">
                                                                            <b-form-input type="text" v-model="jobSheet.onstie"
                                                                                placeholder="hrs:mins"></b-form-input>
                                                                        </b-form-fieldset>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-sm-3">
                                                                        <b-form-fieldset label="Travel Hours">
                                                                            <b-form-input type="text"
                                                                                v-model="jobSheet.travel_hours"
                                                                                placeholder="Travel Hours(Mins)"></b-form-input>
                                                                        </b-form-fieldset>
                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <b-form-fieldset label="Total Kms">
                                                                            <b-form-input type="text"
                                                                                v-model="jobSheet.total_kms"
                                                                                placeholder="Total KMs"></b-form-input>
                                                                        </b-form-fieldset>
                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <b-form-fieldset label="Waiting Time">
                                                                            <b-form-input type="text"
                                                                                v-model="jobSheet.waiting_time"
                                                                                placeholder="hrs:mins"></b-form-input>
                                                                        </b-form-fieldset>
                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <b-form-fieldset label="Reason For Wait">
                                                                            <b-form-input type="text"
                                                                                v-model="jobSheet.waiting_reason"
                                                                                placeholder="contact, parts"></b-form-input>
                                                                        </b-form-fieldset>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-sm-8">
                                                                        <label for>Technician Comments</label>
                                                                        <wysiwyg v-model="jobSheet.comment" name="comment"
                                                                            data-vv-as="comment" type="text"></wysiwyg>
                                                                    </div>
                                                                    <div class="form-group col-sm-4">
                                                                        <b-form-fieldset label="Safety Document">
                                                                            <b-form-input type="text" v-model="jobSheet.swmc"
                                                                                placeholder="Enter SWMC DOC No"></b-form-input>
                                                                        </b-form-fieldset>
                                                                        <b-form-fieldset label="Customer Name">
                                                                            <b-form-input type="text" v-model="jobSheet.customer_signature"
                                                                                placeholder=""></b-form-input>
                                                                        </b-form-fieldset>
                                                                        <b-form-fieldset label="Technician Name">
                                                                            <b-form-input type="text" disabled :placeholder ="this.$store.state.user.full_name"></b-form-input>
                                                                        </b-form-fieldset>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <label class="col-md-3 form-control-label">
                                                                        Job
                                                                        Checklist:
                                                                    </label>
                                                                    <div class="form-group col-sm-2">
                                                                        <!-- <input type="checkbox" placeholder="Is User" class="form-control" v-model="user.is_user" value="1" name="is_user"> -->
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox" v-model="jobSheet.run_flush"
                                                                                value="1" name="is_user"
                                                                                class="custom-control-input">
                                                                            <span class="custom-control-indicator"></span>
                                                                            <span class="custom-control-description">
                                                                                Run &
                                                                                Flush
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-group col-sm-2">
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox" v-model="jobSheet.work_area"
                                                                                value="1" name="key_contact"
                                                                                class="custom-control-input">
                                                                            <span class="custom-control-indicator"></span>
                                                                            <span class="custom-control-description">
                                                                                Work Area
                                                                                Tied
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-group col-sm-2">
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox"
                                                                                v-model="jobSheet.product_checked" value="1"
                                                                                name="key_contact" class="custom-control-input">
                                                                            <span class="custom-control-indicator"></span>
                                                                            <span class="custom-control-description">
                                                                                Product
                                                                                Checked
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-sm-6">
                                                                        <div id="preview">
                                                                            <img v-if="url" :src="url" style="height:100px;">
                                                                            <img v-else src="/img/coffee.png"
                                                                                style="height:120px;">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row">
                                                                    <div class="form-group col-sm-3">
                                                                        <b-form-file @change="onFileChange"
                                                                            placeholder="Upload Machine Image"></b-form-file>
                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <a @click.prevent="ShowDocument()" href="#">
                                                                            Click Here
                                                                            For Machine Document
                                                                        </a>
                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox" @change="changeJobStatus()"
                                                                                v-model="jobSheet.is_complete"
                                                                                name="is_complete" class="custom-control-input">
                                                                            <span class="custom-control-indicator"></span>
                                                                            <span class="custom-control-description">
                                                                                Send Job
                                                                                Completetion Request
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-group col-sm-3">
                                                                        <label class="custom-control custom-checkbox">
                                                                            <input type="checkbox" v-model="secondTrip"
                                                                                value="1" name="key_contact"
                                                                                class="custom-control-input">
                                                                            <span class="custom-control-indicator"></span>
                                                                            <span class="custom-control-description">
                                                                                Second
                                                                                Trip
                                                                            </span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </b-card-body>
                                            </b-collapse>
                                        </b-card>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-tab>
                    <b-tab v-if="placementMode" @click="selectedTab('asset_option')" title="Asset Option">
                        <br>
                        <form @submit.prevent="submitAssetOption()">
                            <div class="card border-0">
                                <div class="form-group form-actions">
                                    <div class="col-sm-12">
                                        <div v-if="$store.state.user.contact_type != 'Manager'"
                                            class="form-group form-actions">
                                            <router-link :to="{ path: `/jobs` }" class="btn btn-link pull-right">
                                                <i class="fas fa-sign-out-alt fa-3x action"
                                                    title="Exit From This Page"></i>
                                            </router-link>
                                            <router-link :to="{  }" class="btn btn-link pull-right">
                                                <i class="far fa-save fa-3x action" @click.prevent="submit(null)"
                                                    title="Save"></i>
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label for>Asset Type *</label>
                                            <multiselect v-model="selectedJobAssetType" :options="assetModels"
                                                @select="onSelectJobAssetType" track-by="id" label="name"
                                                placeholder="Select Asset Type" selectLabel deselectLabel
                                                selectedLabel="Remove"></multiselect>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Cup Options:</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(machineOption, index) in machineOptions" :key="index">
                                                        <td v-if="machineOption.option_name == 'Cup'">
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" :value="machineOption.id"
                                                                    v-model="job.machine_option"
                                                                    class="custom-control-input">
                                                                <span class="custom-control-indicator"></span>
                                                                <span
                                                                    class="custom-control-description">{{machineOption.value}}</span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Product Options:</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(machineOption, index) in machineOptions" :key="index">
                                                        <td v-if="machineOption.option_name == 'Product'">
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" :value="machineOption.id"
                                                                    v-model="job.machine_option"
                                                                    class="custom-control-input">
                                                                <span class="custom-control-indicator"></span>
                                                                <span
                                                                    class="custom-control-description">{{machineOption.value}}</span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Drink Options:</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(machineOption, index) in machineOptions" :key="index">
                                                        <td v-if="machineOption.option_name == 'Drink'">
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" :value="machineOption.id"
                                                                    v-model="job.machine_option"
                                                                    class="custom-control-input">
                                                                <span class="custom-control-indicator"></span>
                                                                <span
                                                                    class="custom-control-description">{{machineOption.value}}</span>
                                                            </label>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label for>Available Asset *</label>
                                            <multiselect v-model="selectedAssetSerial" :options="serials"
                                                @select="onSelectAssetSerial" track-by="id" label="asset_serial"
                                                placeholder="Select Asset Serial" selectLabel deselectLabel
                                                selectedLabel="Remove"></multiselect>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </b-tab>
                    <b-tab v-if="qualityMode && edit" @click="selectedTab('asset_option')" title="Quality Tab">
                        <br>
                        <div class="card-body">
                            <div class="row">
                                 <div :hidden="!edit" class="col-sm-12">
                                    <div role="tablist" class="company-collapse">
                                        <b-card no-body class="mb-1">
                                            <b-card-header header-tag="header" class="p-1" role="tab">
                                                <b-btn block href="#" v-b-toggle.accordion1 variant="danger">
                                                    Asset
                                                    Utilization List
                                                </b-btn>
                                            </b-card-header>
                                            <b-collapse id="accordion1" accordion="my-accordion" role="tabpanel">
                                                <b-card-body>
                                                    <div class="row">
                                                        <div class="col-lg-4"></div>
                                                        <div class="col-lg-8">
                                                            <div>
                                                                Short Listed {{sortedAssetUtilization.length}}Total {{assetUtilization.pagination.total}} items, Per
                                                                page {{assetUtilization.pagination.per_page}},
                                                                Showing {{assetUtilization.pagination.from}} 〜
                                                                {{assetUtilization.pagination.to}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-12">
                                                            <input type="text" class="form-control"
                                                                v-model="assetUtilization.search.global"
                                                                v-on:keyup.enter="assetUtilizationLoad(1)"
                                                                placeholder="Search For All Records"
                                                                title="Type and press enter to search">
                                                        </div>
                                                        <div class="col-md-2 col-sm-12">
                                                             <button class="btn btn-link"
                                                                @click.prevent="assetUtilizationClearSearch"
                                                                title="Clear Search">
                                                                <i class="fa fa-window-close icon"></i>
                                                            </button>
                                                        </div>
                                                        <div class="col-md-7 col-sm-12 text-right header-menu-nav">
                                                            <slot name="navigations"></slot>

                                                            <router-link
                                                                :to="{ path: `/asset-utilization/create`, query: { job:$route.params.id}}"
                                                                class="btn btn-link">
                                                                <i class="fa fa-plus-square icon" title="Add Asset utilization"></i>
                                                            </router-link>

                                                            <button :disabled="!assetUtilization.selectedIds.length"
                                                                class="btn btn-link"
                                                                @click.prevent="assetUtilizationErase"
                                                                title="Delete Item">
                                                                <i class="fa fa-trash icon"></i>
                                                            </button>
                                                            <b-dropdown variant="link" no-caret>
                                                                <template slot="button-content">
                                                                    <i class="fas fa-columns icon" title="Customize Columns"></i>
                                                                </template>
                                                                <div class="selected-columns">
                                                                    <li v-for="(column, index) in assetUtilization.columns"
                                                                        :key="index">
                                                                        <b-form-checkbox
                                                                            v-model="assetUtilization.selectedColumns"
                                                                            @change="assetUtilizationColumnSelect"
                                                                            :value="column.name">{{column.label}}
                                                                        </b-form-checkbox>
                                                                    </li>
                                                                </div>
                                                            </b-dropdown>
                                                            <download-excel :data="sortedAssetUtilization" class="btn">
                                                                <i class="fa fa-download icon" title="Export"></i>
                                                            </download-excel>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <table class="table table-striped table-responsive">
                                                                <thead>
                                                                    <!-- Column Sort -->
                                                                    <tr>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('all')">
                                                                            All</th>
                                                                        <th v-if="assetUtilizationCheckColumnVisibility('full_date')"
                                                                            @click="assetUtilizationSortByColumn('full_date')"
                                                                            class="text-center sortable">
                                                                            Date
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('full_date')"></i>
                                                                        </th>
                                                                        <th v-if="assetUtilizationCheckColumnVisibility('utilization_type')"
                                                                            @click="assetUtilizationSortByColumn('utilization_type')"
                                                                            class="text-center sortable">
                                                                            Type
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('utilization_type')"></i>
                                                                        </th>
                                                                        <th v-if="assetUtilizationCheckColumnVisibility('value')"
                                                                            @click="assetUtilizationSortByColumn('value')"
                                                                            class="text-center sortable">
                                                                            Utilization value
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('value')"></i>
                                                                        </th>
                                                                        <th v-if="assetUtilizationCheckColumnVisibility('utilization_unit')"
                                                                            @click="assetUtilizationSortByColumn('utilization_unit')"
                                                                            class="text-center sortable">
                                                                            Measure Unit
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('utilization_unit')"></i>
                                                                        </th>
                                                                        <th v-if="assetUtilizationCheckColumnVisibility('rate')"
                                                                            @click="assetUtilizationSortByColumn('rate')"
                                                                            class="text-center sortable">
                                                                            Rate
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('rate')"></i>
                                                                        </th>
                                                                        <th v-if="assetUtilizationCheckColumnVisibility('rate_unit')"
                                                                            @click="assetUtilizationSortByColumn('rate_unit')"
                                                                            class="text-center sortable">
                                                                           No of Days
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('rate_unit')"></i>
                                                                        </th>
                                                                        <th v-if="assetUtilizationCheckColumnVisibility('clear_calculation_description')"
                                                                            @click="assetUtilizationSortByColumn('clear_calculation_description')"
                                                                            class="text-center sortable">
                                                                           Mother Board Changed
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('clear_calculation_description')"></i>
                                                                        </th>
                                                                         <th v-if="assetUtilizationCheckColumnVisibility('parts_changed')"
                                                                            @click="assetUtilizationSortByColumn('parts_changed')"
                                                                            class="text-center sortable">
                                                                          Filter Changed
                                                                            <i
                                                                                :class="assetUtilizationSetSelectedColumnSortIcon('parts_changed')"></i>
                                                                        </th>
                                                                        
                                                                    </tr>
                                                                    <!-- Column Search -->
                                                                    <tr>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('all')">
                                                                            <b-form-checkbox name="checkAll"
                                                                                title="Check all"
                                                                                @change="assetUtilizationAllassetUtilizationRowSelect"
                                                                                v-model="assetUtilization.all">
                                                                            </b-form-checkbox>
                                                                        </th>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('full_date')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.full_date">
                                                                        </th>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('utilization_type')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.utilization_type">
                                                                        </th>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('value')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.value">
                                                                        </th>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('utilization_unit')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.utilization_unit">
                                                                        </th>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('rate')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.rate">
                                                                        </th>
                                                                         <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('rate_unit')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.rate_unit">
                                                                        </th>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('clear_calculation_decription')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.rate_unit">
                                                                        </th>
                                                                         <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('clear_calculation_description')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.clear_calculation_description">
                                                                        </th>
                                                                        <th
                                                                            v-if="assetUtilizationCheckColumnVisibility('parts_changed')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="assetUtilization.search.parts_changed">
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <!-- Table Body -->
                                                                <tbody>
                                                                    <tr v-for="(assetUtilization, index) in sortedAssetUtilization"
                                                                        :key="index">
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('all')">
                                                                            <b-form-checkbox
                                                                                v-model="assetUtilization.selectedIds"
                                                                                @change="assetUtilizationRowSelect"
                                                                                :value="assetUtilization.id">
                                                                            </b-form-checkbox>
                                                                        </td>
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('full_date')">
                                                                            <router-link
                                                                                :to="{ path: `/assetUtilization/${assetUtilization.id}`, query: { job:$route.params.id}}">
                                                                                {{ assetUtilization.full_date }}
                                                                            </router-link>
                                                                        </td>
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('utilization_type')">
                                                                            {{ assetUtilization.utilization_type }}</td>
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('value')">
                                                                            {{ assetUtilization.value }}</td>
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('utilization_unit')">
                                                                            {{ assetUtilization.utilization_unit }}</td>
                                                                        <td class="text-sm-right is-danger"
                                                                            v-if="assetUtilizationCheckColumnVisibility('rate')">
                                                                            {{ Number(utilizationCalculation(sortedAssetUtilization, assetUtilization)).toFixed(2) }}
                                                                        </td>
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('rate_unit')">
                                                                            {{ sortedAssetUtilization.length == (index + 1 ) ? '' :
                                                                                (sortedAssetUtilization[index + 1].utilization_type_id == assetUtilization.utilization_type_id ?
                                                                                calcBusinessDays(sortedAssetUtilization[index + 1].date, assetUtilization.date) : 0) }}
                                                                        </td>
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('clear_calculation_description')">
                                                                            {{ assetUtilization.clear_calculation_description }}
                                                                        </td>
                                                                        <td
                                                                            v-if="assetUtilizationCheckColumnVisibility('parts_changed')">
                                                                            {{ assetUtilization.parts_changed }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <!-- Table Body End -->
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-1 justify-content-center align-self-center">
                                                            <multiselect v-model="assetUtilization.pagination.per_page"
                                                                @select="assetUtilizationOnSelectPageCount"
                                                                :options="assetUtilization.pagination.perPageItems"
                                                                :searchable="false" :show-labels="false"
                                                                placeholder="Items / Page"></multiselect>
                                                        </div>

                                                        <div
                                                            class="col-lg-3 justify-content-center align-self-center text-center">
                                                        </div>

                                                        <div
                                                            class="col-lg-4 justify-content-center align-self-center text-center">
                                                            <div>
                                                                Short Listed {{sortedAssetUtilization.length}}Total {{assetUtilization.pagination.total}} items, Per
                                                                page {{assetUtilization.pagination.per_page}},
                                                                Showing {{assetUtilization.pagination.from}} 〜
                                                                {{assetUtilization.pagination.to}}
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 justify-content-center align-self-center">
                                                            <ul class="pagination pagination-custom pull-right">
                                                                <li
                                                                    :class="{disabled: assetUtilization.pagination.currate_page <= 1}">
                                                                    <a href="#"
                                                                        @click.prevent="assetUtilizationPageChange(1)">&laquo;</a>
                                                                </li>
                                                                <li
                                                                    :class="{disabled: assetUtilization.pagination.currate_page <= 1}">
                                                                    <a href="#"
                                                                        @click.prevent="assetUtilizationPageChange(assetUtilization.pagination.currate_page - 1)">&lt;</a>
                                                                </li>
                                                                <li v-for="page in AssetUtilizationPages" :key="page"
                                                                    :class="{active: page === assetUtilization.pagination.currate_page}">
                                                                    <a href="#"
                                                                        @click.prevent="assetUtilizationPageChange(page)">{{page}}</a>
                                                                </li>
                                                                <li
                                                                    :class="{disabled: assetUtilization.pagination.currate_page >= assetUtilization.pagination.last_page}">
                                                                    <a href="#"
                                                                        @click.prevent="assetUtilizationPageChange(assetUtilization.pagination.currate_page + 1)">&gt;</a>
                                                                </li>
                                                                <li
                                                                    :class="{disabled: assetUtilization.pagination.currate_page >= assetUtilization.pagination.last_page}">
                                                                    <a href="#"
                                                                        @click.prevent="assetUtilizationPageChange(assetUtilization.pagination.last_page)">&raquo;</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </b-card-body>
                                            </b-collapse>
                                        </b-card>
                                    </div>
                                </div>
                                <div :hidden="!edit" class="col-sm-12">
                                    <div role="tablist" class="company-collapse">
                                        <b-card no-body class="mb-1">
                                            <b-card-header header-tag="header" class="p-1" role="tab">
                                                <b-btn block href="#" v-b-toggle.accordion2 variant="danger">
                                                    Spare Parts
                                                    List
                                                </b-btn>
                                            </b-card-header>
                                            <b-collapse id="accordion2" accordion="my-accordion" role="tabpane2">
                                                <b-card-body>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-3 col-sm-12">
                                                                <input type="text" class="form-control"
                                                                    v-model="spareJob.search.global"
                                                                    v-on:keyup.enter="spareJobLoad(1)"
                                                                    placeholder="Search For All Records"
                                                                    title="Type and press enter to search">
                                                            </div>
                                                            <div class="col-md-2 col-sm-12">
                                                                <button class="btn btn-link"
                                                                    @click.prevent="spareJobClearSearch"
                                                                    title="Clear Search">
                                                                    <i class="fa fa-window-close icon"></i>
                                                                </button>
                                                            </div>
                                                            <div class="col-md-7 col-sm-12 text-right header-menu-nav">
                                                                <slot name="navigations"></slot>

                                                                <router-link
                                                                    :to="{ path: `/SpareJob/${$route.params.id}/create`, params: $route.params.id}"
                                                                    class="btn btn-link">
                                                                    <i class="fa fa-plus-square icon"
                                                                        title="Add New Spare Job"></i>
                                                                </router-link>

                                                                <button :disabled="!spareJob.selectedIds.length"
                                                                    class="btn btn-link" @click.prevent="spareJobErase"
                                                                    title="Delete Item">
                                                                    <i class="fa fa-trash icon"></i>
                                                                </button>
                                                                <b-dropdown variant="link" no-caret>
                                                                    <template slot="button-content">
                                                                        <i class="fas fa-columns icon" title="Customize Columns"></i>
                                                                    </template>
                                                                    <div class="selected-columns">
                                                                        <li v-for="(column, index) in spareJob.columns"
                                                                            :key="index">
                                                                            <b-form-checkbox
                                                                                v-model="spareJob.selectedColumns"
                                                                                @change="spareJobColumnSelect"
                                                                                :value="column.name">{{column.label}}
                                                                            </b-form-checkbox>
                                                                        </li>
                                                                    </div>
                                                                </b-dropdown>
                                                                <download-excel :data="sortedSpareJob" class="btn">
                                                                    <i class="fa fa-download icon" title="Export"></i>
                                                                </download-excel>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <table class="table table-striped table-responsive">
                                                                    <thead>
                                                                        <!-- Column Sort -->
                                                                        <tr>
                                                                            <th
                                                                                v-if="spareJobCheckColumnVisibility('all')">
                                                                                All</th>
                                                                            <th v-if="spareJobCheckColumnVisibility('spare_name')"
                                                                                @click="spareJobSortByColumn('spare_name')"
                                                                                class="text-center sortable">
                                                                                Spare Name
                                                                                <i
                                                                                    :class="spareJobSetSelectedColumnSortIcon('spare_name')"></i>
                                                                            </th>
                                                                            <th v-if="spareJobCheckColumnVisibility('allocated')"
                                                                                @click="spareJobSortByColumn('allocated')"
                                                                                class="text-center sortable">
                                                                                Allocated
                                                                                <i
                                                                                    :class="spareJobSetSelectedColumnSortIcon('allocated')"></i>
                                                                            </th>
                                                                            <th v-if="spareJobCheckColumnVisibility('used')"
                                                                                @click="spareJobSortByColumn('used')"
                                                                                class="text-center sortable">
                                                                                Used
                                                                                <i
                                                                                    :class="spareJobSetSelectedColumnSortIcon('used')"></i>
                                                                            </th>
                                                                            <th v-if="spareJobCheckColumnVisibility('comment')"
                                                                                @click="spareJobSortByColumn('comment')"
                                                                                class="text-center sortable">
                                                                                Comment
                                                                                <i
                                                                                    :class="spareJobSetSelectedColumnSortIcon('comment')"></i>
                                                                            </th>
                                                                        </tr>
                                                                        <!-- Column Search -->
                                                                        <tr>
                                                                            <th
                                                                                v-if="spareJobCheckColumnVisibility('all')">
                                                                                <b-form-checkbox name="checkAll"
                                                                                    title="Check all"
                                                                                    @change="spareJobAllspareJobRowSelect"
                                                                                    v-model="spareJob.all">
                                                                                </b-form-checkbox>
                                                                            </th>
                                                                            <th
                                                                                v-if="spareJobCheckColumnVisibility('spare_name')">
                                                                                <input type="text" placeholder="Search"
                                                                                    class="form-control"
                                                                                    v-model="spareJob.search.spare_name">
                                                                            </th>
                                                                            <th
                                                                                v-if="spareJobCheckColumnVisibility('allocated')">
                                                                                <input type="text" placeholder="Search"
                                                                                    class="form-control"
                                                                                    v-model="spareJob.search.allocated">
                                                                            </th>
                                                                            <th
                                                                                v-if="spareJobCheckColumnVisibility('used')">
                                                                                <input type="text" placeholder="Search"
                                                                                    class="form-control"
                                                                                    v-model="spareJob.search.used">
                                                                            </th>
                                                                            <th
                                                                                v-if="spareJobCheckColumnVisibility('comment')">
                                                                                <input type="text" placeholder="Search"
                                                                                    class="form-control"
                                                                                    v-model="spareJob.search.comment">
                                                                            </th>
                                                                        </tr>
                                                                    </thead>
                                                                    <!-- Table Body -->
                                                                    <tbody>
                                                                        <tr v-for="(aspareJob, index) in sortedSpareJob"
                                                                            :key="index">
                                                                            <td
                                                                                v-if="spareJobCheckColumnVisibility('all')">
                                                                                <b-form-checkbox
                                                                                    v-model="spareJob.selectedIds"
                                                                                    @change="spareJobRowSelect"
                                                                                    :value="aspareJob.id">
                                                                                </b-form-checkbox>
                                                                            </td>
                                                                            <td
                                                                                v-if="spareJobCheckColumnVisibility('spare_name')">
                                                                                <router-link
                                                                                    :to="{ path: `/spareJob/${aspareJob.id}`}">
                                                                                    {{
                                          aspareJob.spare_name }}
                                                                                </router-link>
                                                                            </td>
                                                                            <td
                                                                                v-if="spareJobCheckColumnVisibility('allocated')">
                                                                                {{
                                        aspareJob.allocated }}
                                                                            </td>
                                                                            <td
                                                                                v-if="spareJobCheckColumnVisibility('used')">
                                                                                {{
                                        aspareJob.used }}
                                                                            </td>
                                                                            <td
                                                                                v-if="spareJobCheckColumnVisibility('comment')">
                                                                                {{
                                        aspareJob.comment }}
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                    <!-- Table Body End -->
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div
                                                                class="col-lg-1 justify-content-center align-self-center">
                                                                <multiselect v-model="spareJob.pagination.per_page"
                                                                    @select="spareJobOnSelectPageCount"
                                                                    :options="spareJob.pagination.perPageItems"
                                                                    :searchable="false" :show-labels="false"
                                                                    placeholder="Items / Page"></multiselect>
                                                            </div>

                                                            <div
                                                                class="col-lg-3 justify-content-center align-self-center text-center">
                                                            </div>

                                                            <div
                                                                class="col-lg-4 justify-content-center align-self-center text-center">
                                                                <div>
                                                                    Total {{spareJob.pagination.total}} items, Per page
                                                                    {{spareJob.pagination.per_page}},
                                                                    Showing {{spareJob.pagination.from}} 〜
                                                                    {{spareJob.pagination.to}}
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="col-lg-4 justify-content-center align-self-center">
                                                                <ul class="pagination pagination-custom pull-right">
                                                                    <li
                                                                        :class="{disabled: spareJob.pagination.current_page <= 1}">
                                                                        <a href="#"
                                                                            @click.prevent="spareJobPageChange(1)">&laquo;</a>
                                                                    </li>
                                                                    <li
                                                                        :class="{disabled: spareJob.pagination.current_page <= 1}">
                                                                        <a href="#"
                                                                            @click.prevent="spareJobPageChange(spareJob.pagination.current_page - 1)">&lt;</a>
                                                                    </li>
                                                                    <li v-for="page in spareJobPages" :key="page"
                                                                        :class="{active: page === spareJob.pagination.current_page}">
                                                                        <a href="#"
                                                                            @click.prevent="spareJobPageChange(page)">{{page}}</a>
                                                                    </li>
                                                                    <li
                                                                        :class="{disabled: spareJob.pagination.current_page >= spareJob.pagination.last_page}">
                                                                        <a href="#"
                                                                            @click.prevent="spareJobPageChange(spareJob.pagination.current_page + 1)">&gt;</a>
                                                                    </li>
                                                                    <li
                                                                        :class="{disabled: spareJob.pagination.current_page >= spareJob.pagination.last_page}">
                                                                        <a href="#"
                                                                            @click.prevent="spareJobPageChange(spareJob.pagination.last_page)">&raquo;</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </b-card-body>
                                            </b-collapse>
                                        </b-card>
                                    </div>
                                </div>
                                <div :hidden="!edit" class="col-sm-12">
                                    <div role="tablist" class="company-collapse">
                                        <b-card no-body class="mb-1">
                                            <b-card-header header-tag="header" class="p-1" role="tab">
                                                <b-btn block href="#" v-b-toggle.accordion3 variant="danger">
                                                    Image
                                                    Gallery
                                                </b-btn>
                                            </b-card-header>
                                            <b-collapse id="accordion3" accordion="my-accordion" role="tabpane3">
                                                <b-card-body>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-3 col-sm-12">
                                                                <input type="text" class="form-control"
                                                                    v-model="gallery.search.global"
                                                                    v-on:keyup.enter="galleryLoad(1)"
                                                                    placeholder="Type and press enter to search"
                                                                    title="Search Item">
                                                            </div>
                                                            <div class="col-md-2 col-sm-12">
                                                               <button class="btn btn-link"
                                                                    @click.prevent="galleryClearSearch"
                                                                    title="Clear Search">
                                                                    <i class="fa fa-window-close icon"></i>
                                                                </button>
                                                            </div>
                                                            <div class="col-md-7 col-sm-12 text-right header-menu-nav">
                                                                <slot name="navigations"></slot>

                                                                <router-link
                                                                    :to="{ path: `/gallery/${$route.params.id}/create`, params: $route.params.id }"
                                                                    class="btn btn-link">
                                                                    <i class="fa fa-plus-square icon"
                                                                        title="Add New Image"></i>
                                                                </router-link>

                                                                <button :disabled="!gallery.selectedIds.length"
                                                                    class="btn btn-link" @click.prevent="galleryErase"
                                                                    title="Delete Images">
                                                                    <i class="fa fa-trash icon"></i>
                                                                </button>
                                                                <b-dropdown variant="link" no-caret>
                                                                    <template slot="button-content">
                                                                        <i class="fas fa-columns icon" title="Customize Columns"></i>
                                                                    </template>
                                                                    <div class="selected-columns">
                                                                        <li v-for="(column, index) in gallery.columns"
                                                                            :key="index">
                                                                            <b-form-checkbox
                                                                                v-model="gallery.selectedColumns"
                                                                                @change="galleryColumnSelect"
                                                                                :value="column.name">{{column.label}}
                                                                            </b-form-checkbox>
                                                                        </li>
                                                                    </div>
                                                                </b-dropdown>
                                                                <download-excel :data="sortedGallery" class="btn">
                                                                    <i class="fa fa-download icon"></i>
                                                                </download-excel>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <table class="table table-striped table-responsive">
                                                                    <thead>
                                                                        <!-- Column Sort -->
                                                                        <tr>
                                                                            <th
                                                                                v-if="galleryCheckColumnVisibility('all')">
                                                                                All</th>
                                                                            <th v-if="galleryCheckColumnVisibility('description')"
                                                                                @click="gallerySortByColumn('description')"
                                                                                class="text-center sortable">
                                                                                Description
                                                                                <i
                                                                                    :class="gallerySetSelectedColumnSortIcon('description')"></i>
                                                                            </th>
                                                                            <!-- <th v-if="galleryCheckColumnVisibility('image')"
                                                                                    @click="gallerySortByColumn('image')"
                                                                                    class="text-center sortable">
                                                                                    Image
                                                                                    <i :class="gallerySetSelectedColumnSortIcon('image')"></i>
                                      </th>-->
                                                                        </tr>
                                                                        <!-- Column Search -->
                                                                        <tr>
                                                                            <th
                                                                                v-if="galleryCheckColumnVisibility('all')">
                                                                                <b-form-checkbox name="checkAll"
                                                                                    title="Check all"
                                                                                    @change="galleryAllgalleryRowSelect"
                                                                                    v-model="gallery.all">
                                                                                </b-form-checkbox>
                                                                            </th>
                                                                            <th
                                                                                v-if="galleryCheckColumnVisibility('description')">
                                                                                <input type="text" placeholder="Search"
                                                                                    class="form-control"
                                                                                    v-model="gallery.search.description">
                                                                            </th>
                                                                            <!-- <th v-if="galleryCheckColumnVisibility('image')">
                                                                                    <input type="text" placeholder="Search"
                                                                                        class="form-control" v-model="gallery.search.image" />
                                      </th>-->
                                                                        </tr>
                                                                    </thead>
                                                                    <!-- Table Body -->
                                                                    <tbody>
                                                                        <tr v-for="(glry, index) in sortedGallery"
                                                                            :key="index">
                                                                            <td
                                                                                v-if="galleryCheckColumnVisibility('all')">
                                                                                <b-form-checkbox
                                                                                    v-model="gallery.selectedIds"
                                                                                    @change="galleryRowSelect"
                                                                                    :value="glry.id"></b-form-checkbox>
                                                                            </td>
                                                                            <td
                                                                                v-if="galleryCheckColumnVisibility('description')">
                                                                                <router-link
                                                                                    :to="{ path: `/gallery/${glry.id}`}">
                                                                                    {{
                                          glry.description }}
                                                                                </router-link>
                                                                            </td>
                                                                            <!-- <td v-if="galleryCheckColumnVisibility('image')">
                                                                                    <router-link :to="{ path: `/gallery/${glry.id}`}">
                                                                                        <div class="image-container">
                                                                                            <img class="object-fit-cover" :src="glry.image || '/img/company.png'" />
                                                                                        </div>
                                                                                    </router-link>
                                      </td>-->
                                                                        </tr>
                                                                    </tbody>
                                                                    <!-- Table Body End -->
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div
                                                                class="col-lg-1 justify-content-center align-self-center">
                                                                <multiselect v-model="gallery.pagination.per_page"
                                                                    @select="galleryOnSelectPageCount"
                                                                    :options="gallery.pagination.perPageItems"
                                                                    :searchable="false" :show-labels="false"
                                                                    placeholder="Items / Page"></multiselect>
                                                            </div>

                                                            <div
                                                                class="col-lg-3 justify-content-center align-self-center text-center">
                                                            </div>

                                                            <div
                                                                class="col-lg-4 justify-content-center align-self-center text-center">
                                                                <div>
                                                                    Total {{gallery.pagination.total}} items, Per page
                                                                    {{gallery.pagination.per_page}},
                                                                    Showing {{gallery.pagination.from}} 〜
                                                                    {{gallery.pagination.to}}
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="col-lg-4 justify-content-center align-self-center">
                                                                <ul class="pagination pagination-custom pull-right">
                                                                    <li
                                                                        :class="{disabled: gallery.pagination.current_page <= 1}">
                                                                        <a href="#"
                                                                            @click.prevent="galleryPageChange(1)">&laquo;</a>
                                                                    </li>
                                                                    <li
                                                                        :class="{disabled: gallery.pagination.current_page <= 1}">
                                                                        <a href="#"
                                                                            @click.prevent="galleryPageChange(gallery.pagination.current_page - 1)">&lt;</a>
                                                                    </li>
                                                                    <li v-for="page in GalleryPages" :key="page"
                                                                        :class="{active: page === gallery.pagination.current_page}">
                                                                        <a href="#"
                                                                            @click.prevent="galleryPageChange(page)">{{page}}</a>
                                                                    </li>
                                                                    <li
                                                                        :class="{disabled: gallery.pagination.current_page >= gallery.pagination.last_page}">
                                                                        <a href="#"
                                                                            @click.prevent="galleryPageChange(gallery.pagination.current_page + 1)">&gt;</a>
                                                                    </li>
                                                                    <li
                                                                        :class="{disabled: gallery.pagination.current_page >= gallery.pagination.last_page}">
                                                                        <a href="#"
                                                                            @click.prevent="galleryPageChange(gallery.pagination.last_page)">&raquo;</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </b-card-body>
                                            </b-collapse>
                                        </b-card>
                                    </div>
                                </div>
                                <div :hidden="!edit" class="col-sm-12">
                                    <div role="tablist" class="company-collapse">
                                        <b-card no-body class="mb-1">
                                            <b-card-header header-tag="header" class="p-1" role="tab">
                                                <b-btn block href="#" v-b-toggle.accordion4 variant="danger">QA Sheet</b-btn>
                                            </b-card-header>
                                            <b-collapse id="accordion4" accordion="my-accordion" role="tabpane4">
                                                <b-card-body>
                                                  <form @submit.prevent="submitAssetOption()">
                                                    <div class="card border-0">
                                                        <div class="form-group form-actions">
                                                            <router-link :to="{ path: `/asset/${job.asset_id}` }"
                                                                class="btn btn-link pull-right">
                                                                <i class="fas fa-sign-out-alt fa-3x action" title="Exit From This Page"></i>
                                                            </router-link>
                                                            <router-link :to="{  }" :disabled="isSubmitDisabledQa" @click.native="submitInspectionResult()" class="btn btn-link pull-right">
                                                                <i class="far fa-save fa-3x action" 
                                                                    title="Save"></i>
                                                            </router-link>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="form-group col-sm-6">
                                                                    <b-form-fieldset label="Inspection Date">
                                                                        <datetime v-model="inspectionResult.date" type="datetime"
                                                                            input-format="YYYY-MM-DD HH:mm" wrapper-class="my-wrapper-class"
                                                                            input-class="form-control" placeholder="Select date"
                                                                            moment-locale="es" :i18n="{ok:'De acuerdo', cancel:'Cancelar'}"
                                                                            :disabled-dates="['2017-09-07', ['2017-09-25', '2017-10-05']]"
                                                                            max-date="2017-12-10" min-date="2017-07-10" monday-first
                                                                            auto-continue auto-close required></datetime>
                                                                    </b-form-fieldset>
                                                                </div>
                                                                <div class="form-group col-sm-6">
                                                                    <label for>Result</label>
                                                                    <multiselect v-model="selectedInspectionResult"
                                                                        :options="inspectionResultTypes" @select="onSelectInspectionResult"
                                                                        track-by="id" label="name" placeholder="Select Inspection Result"
                                                                        selectLabel deselectLabel selectedLabel="Remove"></multiselect>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-sm-12">
                                                                    <wysiwyg v-model="inspectionResult.comment" name="comment"
                                                                        data-vv-as="comment" type="text"></wysiwyg>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                </b-card-body>
                                            </b-collapse>
                                        </b-card>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </b-tab>
                    <b-tab v-if="testMode && edit" @click="selectedTab('asset_option')" title="Test & Tag">
                        <br>
                         <div :hidden="!edit" class="col-sm-12">
                                <div role="tablist" class="company-collapse">
                                    <b-card no-body class="mb-1">
                                        <b-card-header header-tag="header" class="p-1" role="tab">
                                            <b-btn block href="#" v-b-toggle.accordion4 variant="danger">Test & Tag Sheet</b-btn>
                                        </b-card-header>
                                        <b-collapse id="accordion4" accordion="my-accordion" role="tabpane4">
                                            <b-card-body>
                                             <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitClicked()">
                                                <div class="card border-0">
                                                    <div class="form-group form-actions">
                                                        <router-link :to="{ path: `/asset/${job.asset_id}`}">
                                                            <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                                                        </router-link>
                                                        <button type="submit" style="margin-right: 10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                                                    </div>
                                                    <div class="card-body">
                                                         <div class="row">
                                                            <div class="form-group col-sm-6">
                                                                <b-form-fieldset label="Date">
                                                                    <datetime v-model="inspectionResult.date" type="datetime"
                                                                        input-format="YYYY-MM-DD HH:mm" wrapper-class="my-wrapper-class"
                                                                        input-class="form-control" placeholder="Select date"
                                                                        moment-locale="es" :i18n="{ok:'De acuerdo', cancel:'Cancelar'}"
                                                                        :disabled-dates="['2017-09-07', ['2017-09-25', '2017-10-05']]"
                                                                        max-date="2017-12-10" min-date="2017-07-10" monday-first
                                                                        auto-continue auto-close required></datetime>
                                                                </b-form-fieldset>
                                                            </div>
                                                            <div class="form-group col-sm-6">
                                                                <label for>Result</label>
                                                                <multiselect v-model="selectedInspectionResult"
                                                                    :options="inspectionResultTypes" @select="onSelectInspectionResult"
                                                                    track-by="id" label="name" placeholder="Select Inspection Result"
                                                                    selectLabel deselectLabel selectedLabel="Remove"></multiselect>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-sm-4">
                                                                <b-form-fieldset label="Earth Band">
                                                                    <b-form-input type="text" :class="errors.has('band') ? 'form-control is-invalid':'form-control'"
                                                                    title="Please Enter Earth Band"    v-validate="'required'" name="band" v-model="inspectionResult.band" placeholder="Please enter Earth Band"></b-form-input>
                                                                    <span class="is-danger">{{ errors.first('serial') }}</span>
                                                                </b-form-fieldset>
                                                            </div>
                                                            <div class="form-group col-sm-4">
                                                                <b-form-fieldset label="Insulation">
                                                                    <b-form-input type="text" :class="errors.has('insulation') ? 'form-control is-invalid':'form-control'"
                                                                        v-validate="'required'" name="insulation" v-model="inspectionResult.insulation" title="Enter Insulation" placeholder="Please enter Insulation"></b-form-input>
                                                                    <span class="is-danger">{{ errors.first('insulation') }}</span>
                                                                </b-form-fieldset>
                                                            </div>
                                                             <div class="form-group col-sm-4">
                                                                <b-form-fieldset label="Leakage Value">
                                                                    <b-form-input type="text" :class="errors.has('leakage') ? 'form-control is-invalid':'form-control'"
                                                                        v-validate="'required'" name="leakage" v-model="inspectionResult.leakage" title="Enter Leakage Value" placeholder="Please enter Leakage Value"></b-form-input>
                                                                    <span class="is-danger">{{ errors.first('leakage') }}</span>
                                                                </b-form-fieldset>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                             <div class="form-group col-sm-4">
                                                                <br><br>
                                                                <label class="custom-control custom-checkbox">
                                                                    <input type="checkbox" v-model="inspectionResult.in_location" name="in_location"
                                                                        class="custom-control-input">
                                                                    <span class="custom-control-indicator"></span>
                                                                    <span class="custom-control-description">Not In Location</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-sm-12">
                                                                <label>Enter Your Commnet</label>
                                                                <wysiwyg v-model="inspectionResult.comment" name="comment" data-vv-as="comment" type="text"></wysiwyg>
                                                            </div>
                                                        </div>
                                                        <!-- <div class="row">
                                                            <div class="form-group col-sm-6">     
                                                                <div id="preview">
                                                                    <img v-if="url" :src="url" style="height:100px;"  />
                                                                    <img v-else src="/img/document.png" style="height:120px;" >
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                        <!-- <div class="row">
                                                            <div class="form-group">
                                                                <div class="form-inline">
                                                                    <div class="form-group mt-4">
                                                                        <input aria-describedby="fileHelp"  class=" btn form-control-file" id="file" ref="file" v-on:change="handleFileUpload()" type="file">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </form>
                                            </b-card-body>
                                        </b-collapse>
                                    </b-card>
                                </div>
                            </div>
                     </b-tab>
                    
                    <b-tab @click="selectedTab('secondTrip')" v-if="secondTrip && edit" title="Second Trip">
                        <br>
                        <form @submit.prevent="submitJobSecondTrip()">
                            <div class="card border-0">
                                <div class="form-group form-actions">
                                    <router-link :to="{ path: `/asset/${job.asset_id}` }"
                                        class="btn btn-link pull-right">
                                        <i class="fas fa-sign-out-alt fa-3x action" title="Exit From This Page"></i>
                                    </router-link>
                                    <router-link :to="{ }" class="btn btn-link pull-right">
                                        <i class="fa fa-print fa-3x action"
                                            @click.prevent="createJobSheetPDF(jobSecondTrip.id)"
                                            title="Click Here To Print Job Sheet">
                                            <font size="3" class="text-style"></font>
                                        </i>
                                    </router-link>
                                    <router-link :to="{}" class="btn btn-link pull-right">
                                        <i class="fa fa-envelope fa-3x action"
                                            @click.prevent="emailBox(jobSecondTrip.id)" title="Send Email"></i>
                                    </router-link>
                                    <router-link :to="{  }" :disabled="isSubmitDisabledSecondJobSheet" @click.native="submitJobSecondTrip()" class="btn btn-link pull-right">
                                        <i class="far fa-save fa-3x action" 
                                            title="Save"></i>
                                    </router-link>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <!-- <label style="font-size: 20px">Job Completetion Form For -
                    {{asset.company.name}} , Asset -{{asset.name}}</label>-->
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-3">
                                            <b-form-fieldset label="Date">
                                                <datetime v-model="jobSecondTrip.job_date" type="datetime"
                                                    input-format="YYYY-MM-DD HH:mm" wrapper-class="my-wrapper-class"
                                                    input-class="form-control" placeholder="Select date"
                                                    moment-locale="es" :i18n="{ok:'De acuerdo', cancel:'Cancelar'}"
                                                    :disabled-dates="['2017-09-07', ['2017-09-25', '2017-10-05']]"
                                                    max-date="2017-12-10" min-date="2017-07-10" monday-first zone="Pacific/Auckland" value-zone="Pacific/Auckland"
                                                    auto-continue auto-close required></datetime>
                                            </b-form-fieldset>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <b-form-fieldset label="On Site Time In">
                                                <vue-clock-picker class="form-control" v-model="jobSecondTrip.timein"
                                                    @beforeClose="onCloseTimeInSecondTrip" ref="timeInSecondTrip">
                                                </vue-clock-picker>
                                            </b-form-fieldset>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <b-form-fieldset label="Off Site Time Out">
                                                <vue-clock-picker class="form-control" v-model="jobSecondTrip.timeout"
                                                    @beforeClose="onCloseTimeOutSecondTrip" ref="timeOutSecondTrip">
                                                </vue-clock-picker>
                                            </b-form-fieldset>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <b-form-fieldset label="On Site">
                                                <b-form-input type="text" v-model="jobSecondTrip.onstie"
                                                    placeholder="hrs:mins"></b-form-input>
                                            </b-form-fieldset>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-3">
                                            <b-form-fieldset label="Travel Hours">
                                                <b-form-input type="text" v-model="jobSecondTrip.travel_hours"
                                                    placeholder="Travel Hours(Mins)"></b-form-input>
                                            </b-form-fieldset>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <b-form-fieldset label="Total Kms">
                                                <b-form-input type="text" v-model="jobSecondTrip.total_kms"
                                                    placeholder="Total KMs"></b-form-input>
                                            </b-form-fieldset>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <b-form-fieldset label="Waiting Time">
                                                <b-form-input type="text" v-model="jobSecondTrip.waiting_time"
                                                    placeholder="hrs:mins"></b-form-input>
                                            </b-form-fieldset>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <b-form-fieldset label="Reason For Wait">
                                                <b-form-input type="text" v-model="jobSecondTrip.waiting_reason"
                                                    placeholder="contact, parts"></b-form-input>
                                            </b-form-fieldset>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-8">
                                            <label for>Technician Comments</label>
                                            <wysiwyg v-model="jobSecondTrip.comment" name="comment" data-vv-as="comment"
                                                type="text"></wysiwyg>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <b-form-fieldset label="Safety Document">
                                                <b-form-input type="text" v-model="jobSecondTrip.swmc"
                                                    placeholder="Enter SWMC DOC No"></b-form-input>
                                            </b-form-fieldset>
                                              <b-form-fieldset label="Customer Name">
                                            <b-form-input type="text" v-model="jobSecondTrip.customer_signature"
                                                    placeholder=""></b-form-input>
                                            </b-form-fieldset>
                                            <b-form-fieldset label="Technician Name">
                                                <b-form-input type="text" disabled :placeholder ="this.$store.state.user.full_name"></b-form-input>
                                            </b-form-fieldset>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-md-3 form-control-label">Job Checklist:</label>
                                        <div class="form-group col-sm-2">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" v-model="jobSecondTrip.run_flush" value="0"
                                                    name="is_user" class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Run & Flush</span>
                                            </label>
                                        </div>
                                        
                                        <div class="form-group col-sm-2">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" v-model="jobSecondTrip.work_area" value="0"
                                                    name="key_contact" class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Work Area Tied</span>
                                            </label>
                                        </div>
                                        <div class="form-group col-sm-2">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" v-model="jobSecondTrip.product_checked" value="0"
                                                    name="key_contact" class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Product Checked</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <div id="preview">
                                                <img v-if="url" :src="url" style="height:100px;">
                                                <img v-else src="/img/coffee.png" style="height:120px;">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-3">
                                            <b-form-file @change="onFileChanges" placeholder="Upload Machine Image">
                                            </b-form-file>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <a @click.prevent="ShowDocument()" href="#">
                                                Click Here For Machine
                                                Document
                                            </a>
                                        </div>
                                        <div class="form-group col-sm-3">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" @change="changeJobSecondTripStatus()"
                                                    v-model="jobSecondTrip.is_complete" value="0" name="is_complete"
                                                    class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">
                                                    Send Job Completetion
                                                    Request
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-8"></div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <div class="card">
                                                <div class="card-header">Spare Parts</div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-12">
                                                            <input type="text" class="form-control"
                                                                v-model="spareJob.search.global"
                                                                v-on:keyup.enter="spareJobLoad(1)"
                                                                placeholder="Type and press enter to search"
                                                                title="Search Item">
                                                        </div>
                                                        <div class="col-md-9 col-sm-12 text-right header-menu-nav">
                                                            <slot name="navigations"></slot>

                                                            <router-link
                                                                :to="{ path: `/SpareJob/${$route.params.id}/create`, params: $route.params.id}"
                                                                class="btn btn-link">
                                                                <i class="fa fa-plus-square icon" title="Add Item"></i>
                                                            </router-link>
                                                            <button class="btn btn-link"
                                                                @click.prevent="spareJobClearSearch"
                                                                title="Clear Search">
                                                                <i class="fas fa-eraser icon"></i>
                                                            </button>

                                                            <button :disabled="!spareJob.selectedIds.length"
                                                                class="btn btn-link" @click.prevent="spareJobErase"
                                                                title="Delete Item">
                                                                <i class="fa fa-trash icon"></i>
                                                            </button>
                                                            <b-dropdown variant="link" no-caret>
                                                                <template slot="button-content">
                                                                    <i class="fas fa-columns icon"></i>
                                                                </template>
                                                                <div class="selected-columns">
                                                                    <li v-for="(column, index) in spareJob.columns"
                                                                        :key="index">
                                                                        <b-form-checkbox
                                                                            v-model="spareJob.selectedColumns"
                                                                            @change="spareJobColumnSelect"
                                                                            :value="column.name">{{column.label}}
                                                                        </b-form-checkbox>
                                                                    </li>
                                                                </div>
                                                            </b-dropdown>
                                                            <download-excel :data="sortedSpareJob" class="btn">
                                                                <i class="fa fa-download icon"></i>
                                                            </download-excel>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <table class="table table-striped table-responsive">
                                                                <thead>
                                                                    <!-- Column Sort -->
                                                                    <tr>
                                                                        <th v-if="spareJobCheckColumnVisibility('all')">
                                                                            All</th>
                                                                        <th v-if="spareJobCheckColumnVisibility('spare_name')"
                                                                            @click="spareJobSortByColumn('spare_name')"
                                                                            class="text-center sortable">
                                                                            Spare Name
                                                                            <i
                                                                                :class="spareJobSetSelectedColumnSortIcon('spare_name')"></i>
                                                                        </th>
                                                                        <th v-if="spareJobCheckColumnVisibility('allocated')"
                                                                            @click="spareJobSortByColumn('allocated')"
                                                                            class="text-center sortable">
                                                                            Allocated
                                                                            <i
                                                                                :class="spareJobSetSelectedColumnSortIcon('allocated')"></i>
                                                                        </th>
                                                                        <th v-if="spareJobCheckColumnVisibility('used')"
                                                                            @click="spareJobSortByColumn('used')"
                                                                            class="text-center sortable">
                                                                            Used
                                                                            <i
                                                                                :class="spareJobSetSelectedColumnSortIcon('used')"></i>
                                                                        </th>
                                                                        <th v-if="spareJobCheckColumnVisibility('comment')"
                                                                            @click="spareJobSortByColumn('comment')"
                                                                            class="text-center sortable">
                                                                            Comment
                                                                            <i
                                                                                :class="spareJobSetSelectedColumnSortIcon('comment')"></i>
                                                                        </th>
                                                                    </tr>
                                                                    <!-- Column Search -->
                                                                    <tr>
                                                                        <th v-if="spareJobCheckColumnVisibility('all')">
                                                                            <b-form-checkbox name="checkAll"
                                                                                title="Check all"
                                                                                @change="spareJobAllspareJobRowSelect"
                                                                                v-model="spareJob.all">
                                                                            </b-form-checkbox>
                                                                        </th>
                                                                        <th
                                                                            v-if="spareJobCheckColumnVisibility('spare_name')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="spareJob.search.spare_name">
                                                                        </th>
                                                                        <th
                                                                            v-if="spareJobCheckColumnVisibility('allocated')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="spareJob.search.allocated">
                                                                        </th>
                                                                        <th
                                                                            v-if="spareJobCheckColumnVisibility('used')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="spareJob.search.used">
                                                                        </th>
                                                                        <th
                                                                            v-if="spareJobCheckColumnVisibility('comment')">
                                                                            <input type="text" placeholder="Search"
                                                                                class="form-control"
                                                                                v-model="spareJob.search.comment">
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <!-- Table Body -->
                                                                <tbody>
                                                                    <tr v-for="(aspareJob, index) in sortedSpareJob"
                                                                        :key="index">
                                                                        <td v-if="spareJobCheckColumnVisibility('all')">
                                                                            <b-form-checkbox
                                                                                v-model="spareJob.selectedIds"
                                                                                @change="spareJobRowSelect"
                                                                                :value="aspareJob.id"></b-form-checkbox>
                                                                        </td>
                                                                        <td
                                                                            v-if="spareJobCheckColumnVisibility('spare_name')">
                                                                            <router-link
                                                                                :to="{ path: `/spareJob/${aspareJob.id}`}">
                                                                                {{
                                        aspareJob.spare_name }}
                                                                            </router-link>
                                                                        </td>
                                                                        <td
                                                                            v-if="spareJobCheckColumnVisibility('allocated')">
                                                                            {{
                                      aspareJob.allocated }}
                                                                        </td>
                                                                        <td
                                                                            v-if="spareJobCheckColumnVisibility('used')">
                                                                            {{
                                      aspareJob.used }}
                                                                        </td>
                                                                        <td
                                                                            v-if="spareJobCheckColumnVisibility('comment')">
                                                                            {{
                                      aspareJob.comment }}
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                                <!-- Table Body End -->
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-1 justify-content-center align-self-center">
                                                            <multiselect v-model="spareJob.pagination.per_page"
                                                                @select="spareJobOnSelectPageCount"
                                                                :options="spareJob.pagination.perPageItems"
                                                                :searchable="false" :show-labels="false"
                                                                placeholder="Items / Page"></multiselect>
                                                        </div>

                                                        <div
                                                            class="col-lg-3 justify-content-center align-self-center text-center">
                                                        </div>

                                                        <div
                                                            class="col-lg-4 justify-content-center align-self-center text-center">
                                                            <div>
                                                                Total {{spareJob.pagination.total}} items, Per page
                                                                {{spareJob.pagination.per_page}},
                                                                Showing {{spareJob.pagination.from}} 〜
                                                                {{spareJob.pagination.to}}
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4 justify-content-center align-self-center">
                                                            <ul class="pagination pagination-custom pull-right">
                                                                <li
                                                                    :class="{disabled: spareJob.pagination.current_page <= 1}">
                                                                    <a href="#"
                                                                        @click.prevent="spareJobPageChange(1)">&laquo;</a>
                                                                </li>
                                                                <li
                                                                    :class="{disabled: spareJob.pagination.current_page <= 1}">
                                                                    <a href="#"
                                                                        @click.prevent="spareJobPageChange(spareJob.pagination.current_page - 1)">&lt;</a>
                                                                </li>
                                                                <li v-for="page in spareJobPages" :key="page"
                                                                    :class="{active: page === spareJob.pagination.current_page}">
                                                                    <a href="#"
                                                                        @click.prevent="spareJobPageChange(page)">{{page}}</a>
                                                                </li>
                                                                <li
                                                                    :class="{disabled: spareJob.pagination.current_page >= spareJob.pagination.last_page}">
                                                                    <a href="#"
                                                                        @click.prevent="spareJobPageChange(spareJob.pagination.current_page + 1)">&gt;</a>
                                                                </li>
                                                                <li
                                                                    :class="{disabled: spareJob.pagination.current_page >= spareJob.pagination.last_page}">
                                                                    <a href="#"
                                                                        @click.prevent="spareJobPageChange(spareJob.pagination.last_page)">&raquo;</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </b-tab>
                    <b-tab @click="selectedTab('allocation')"
                        v-if="$store.state.user.contact_type != 'Technician' && edit" title="Close Out">
                        <br>
                        <form>
                            <div class="card border-0">
                                <div class="form-group form-actions"></div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <!-- <label style="font-size: 20px">Job Close Out Form For - {{asset.company.name}}
                    , Asset -{{asset.name}}</label>-->
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for>Administration Close Out By *</label>
                                            <multiselect v-model="selectedAdmin" :options="users"
                                                @select="onSelectAdmin" track-by="id" label="full_name"
                                                placeholder="Select User" selectLabel deselectLabel
                                                selectedLabel="Remove"></multiselect>
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for>Charge To</label>
                                            <multiselect v-model="selectedChargeTo" :options="chargeCompanies"
                                                @select="onSelectChargeTo" track-by="id" label="name"
                                                placeholder="Select Charge To" selectLabel deselectLabel
                                                selectedLabel="Remove"></multiselect>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" @change="adminApproval()"
                                                    v-model="job.need_approval" value="1" name="need_approval"
                                                    class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Need Approval</span>
                                            </label>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" v-model="job.is_nuiscance" value="Yes"
                                                    name="is_nuiscance" class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Nuisance Call</span>
                                            </label>
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label class="custom-control custom-checkbox">
                                                <input type="checkbox" @change="changeAdminCloseOut()"
                                                    v-model="job.close_by_admin" value="1" name="close_by_admin"
                                                    class="custom-control-input">
                                                <span class="custom-control-indicator"></span>
                                                <span class="custom-control-description">Admin Close Out</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-12">
                                            <b-form-fieldset label="Review for Technician">
                                                <b-form-textarea id="textarea1" v-model="job.review"
                                                    placeholder="Please Enter Review for Technician" :rows="3"
                                                    :max-rows="6"></b-form-textarea>
                                            </b-form-fieldset>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </b-tab>
                </b-tabs>
            </div>
        </div>
        <b-modal ref="asset" size="lg" id="modal-center" @ok="submitassetType()" centered
            title="Update Asset Information">
            <div class="row">
                <div class="col-sm-12"></div>
            </div>
        </b-modal>
        <b-modal ref="emailForm" size="lg" id="modal-center" @ok="sendEmail()" centered title="Send Email">
            <div class="row">
                <div class="col-sm-12">
                    <form enctype="multipart/form-data" autocomplete="off">
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <b-form-fieldset label>
                                            <b-form-input type="text" name="name" v-model="sendEmailModal.email"
                                                placeholder="Please enter receiver email address"></b-form-input>
                                            <span class="is-danger">{{ errors.first('name') }}</span>
                                        </b-form-fieldset>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <b-form-textarea id="textarea1" v-model="sendEmailModal.description"
                                            placeholder="Description" :rows="3" :max-rows="6"></b-form-textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </b-modal>
        <b-modal ref="emailFormFirstJobSheet" size="lg" id="modal-center"
            @ok="sendEmailFirstJobSheet(jobSheetIdForEmail)" centered title="Send Email">
            <div class="row">
                <div class="col-sm-12">
                    <form enctype="multipart/form-data" autocomplete="off">
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-sm-12">
                                        <b-form-fieldset label>
                                            <b-form-input type="text" name="name" v-model="sendEmailModalJobSheet.email"
                                                placeholder="Please enter receiver email address"></b-form-input>
                                            <span class="is-danger">{{ errors.first('name') }}</span>
                                        </b-form-fieldset>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <b-form-textarea id="textarea1" v-model="sendEmailModalJobSheet.description"
                                            placeholder="Description" :rows="3" :max-rows="6"></b-form-textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </b-modal>
        <b-modal ref="documentModal" size="lg" id="modal-center" centered title="Machine Documents">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Document Description</th>
                                <th>Document</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="document in documents" v-bind:key="document.id">
                                <td>{{ document.description }}</td>
                                <td @click.prevent="openFile(document.file)">
                                    <a href="#">{{ document.file }}</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </b-modal>
    </div>
</template>
<style>
    /* Style the header */
    .header {
        padding: 0px;
        text-align: center;
        font-size: 13px;
    }

    /* Style the break */
    .break {
        background-color: #f1f1f1;
        padding: 0px;
        text-align: left;
        font-size: 10px;
    }

    /* Create three equal columns that floats next to each other */
    .column {
        float: left;
        width: 100%;
    }

    .columnsecond {
        float: left;
        width: 100%;
    }

    .column-three {
        float: left;
        width: 100%;
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }

    /* Style the footer */
    .footer {
        padding: 10px;
        text-align: center;
    }

    /* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
    @media (max-width: 600px) {
        .column {
            width: 100%;
        }
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
    }

    th,
    td {
        text-align: left;
        padding: 0px;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>
<script>
    import Multiselect from "vue-multiselect";
    import "@pencilpix/vue2-clock-picker/dist/vue2-clock-picker.min.css";
    import VueClockPicker from "@pencilpix/vue2-clock-picker";
    import moment from "moment";

    export default {
        name: "forms",
        components: { Multiselect, VueClockPicker },
        data() {
            return {
                assetUtilizations: [],
                inspectionResultTypes: [],
                serials: [],
                milestones: [],
                users: [],
                companies: [],
                contractors: [],
                customers: [],
                jobs: [],
                galleries: [],
                priorities: [],
                jobStats: [],
                jobTypes: [],
                assets: [],
                distributors: [],
                technicians: [],
                spareJobs: [],
                assetStats: [],
                locations: [],
                assetInspections: [],
                job: {
                    type: null,
                    job_raised_date: new Date().toISOString(),
                    job_number: null,
                    machine_option: [],
                    drink_no: "",
                    asset_model_id: null
                    // asset: {
                    //     machine_option: [],
                    //     asset_model_id: null,
                    //     drink_no: '',
                    // }
                },
                inspectionResult: {
                    date: new Date().toISOString(),
                    frequency: "",
                    group: "",
                    file: "",
                    comment: ""
                },
                email: {},
                assetModel: {},
                jobSheet: {
                    is_complete: false,
                    timein: "",
                    timeout: "",
                    job: {
                        job_stat_id: null
                    }
                },
                jobClose: {},
                url: null,
                edit: false,
                selectedContractor: { id: null, name: "Select Contractor" },
                selectedCloseBy: { id: null, full_name: "Select User" },
                selectedAdmin: { id: null, full_name: "Select User" },
                selectedUser: { id: null, full_name: "Select User" },
                selectedAsset: { id: null, custom: "Select Asset" },
                selectedAssetSerial: { id: null, asset_serial: "Select Asset Serial" },
                selectedjobStat: { id: null, type: "Select Job Status" },
                selectedPriority: { id: null, name: "Select Priority" },
                selectedJobType: { id: null, name: "Select Job Type" },
                selectedCustomer: { id: null, name: "Select Customer" },
                selectedTechnician: { id: null, full_name: "Select Technician" },
                selectedChargeTo: { id: null, name: "Nestle Professional" },
                selectedAssetType: { id: null, name: "Select Asset Type" },
                selectedJobAssetType: { id: null, name: "Select Asset Type" },
                selectedInspectionDescription: {id: null, description: "Select Inspection Description" },
                selectedDistributor: { id: null, name: "Select Distributor" },
                selectedAssetStat: { id: null, type: "Select Asset Status" },
                selectedLocation: { id: null, full_address: "Select Location" },
                selectedInspectionResult: { id: null, name: "Select Inspection Result" },
                asset: {
                    company: {},
                    email: "",
                    machine_option: []
                },
                jobSheetEdit: false,
                jobCloseEdit: false,
                secondTrip: false,
                jobSecondTrip: {
                    timein: "",
                    timeout: ""
                },
                spareJob: {
                    currentSortColumn: "name",
                    currentSortDirection: "asc",
                    search: {
                        global: "",
                        spare_name: "",
                        allocated: "",
                        used: "",
                        comment: ""
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
                        { name: "all", label: "All" },
                        { name: "spare_name", label: "Spare Name" },
                        { name: "allocated", label: "Allocated" },
                        { name: "used", label: "Used" },
                        { name: "comment", label: "Comment" }
                    ]
                },
                milestone: {
                    currentSortColumn: "name",
                    currentSortDirection: "asc",
                    search: {
                        global: "",
                        description: "",
                        date: "",
                        cup_count: "",
                        cup_count_per_day: "",
                        comment: ""
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
                        { name: "all", label: "All" },
                        { name: "description", label: "Description" },
                        { name: "date", label: "Date" },
                        { name: "cup_count", label: "Asset Utilization" },
                        { name: "cup_count_per_day", label: "Utilization Per Day" },
                        { name: "comment", label: "Comment" }
                    ]
                },
                gallery: {
                    currentSortColumn: "name",
                    currentSortDirection: "asc",
                    search: {
                        global: "",
                        description: "",
                        image: ""
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
                        { name: "all", label: "All" },
                        { name: "description", label: "Description" },
                        { name: "image", label: "Image" }
                    ]
                },
                documents: [],
                company: {},
                contractEmails: [],
                technicianEmail: null,
                chargeCompanies: [
                    { id: null, name: "NO COST" },
                    { id: null, name: "Nestle Professional" },
                    { id: null, name: "Distributor" },
                    { id: null, name: "Nestle/Distributor" }
                ],
                checkJobComplete: false,
                selectedTabFlag: null,
                machineOptions: [],
                assetModels: [],
                placementMode: false,
                qualityMode: false,
                testMode:false,
                defaultHour: new Date().getHours(),
                defaultMinute: new Date().getMinutes(),
                assetId: null,
                sendEmailModal: {},
                sendEmailModalJobSheet: {},
                jobSheetIdForEmail: null,
                isLoading: false,
                assetUtilization: {
                    currateSortColumn: "name",
                    currateSortDirection: "asc",
                    search: {
                        global: "",
                        full_date: "",
                        utilization_type: "",
                        value: "",
                        utilization_unit: "",
                        rate: "",
                        rate_unit: "",
                        clear_calculation_description: "",
                        parts_changed: ""
                    },
                    pagination: {
                        per_page: 5,
                        currate_page: 1,
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
                        { name: "all", label: "All" },
                        { name: "full_date", label: "Date" },
                        { name: "utilization_type", label: "Utilization Type" },
                        { name: "value", label: "value" },
                        { name: "utilization_unit", label: "Utilization Unit" },
                        { name: "rate", label: "Rate" },
                        { name: "rate_unit", label: "Rate Unit" },
                        { name: "clear_calculation_description", label: "Mother Board Changed" },
                        { name: "parts_changed", label: "Filter Changed" }
                    ]
                },
                isSubmitDisabled: false,
                isSubmitDisabledJobSheet: false,
                isSubmitDisabledSecondJobSheet: false,
                isSubmitDisabledQa: false,
                isSubmitDisabledTestTag: false,
                uploadedFile : null,     
            }
        },
        created() {
            console.log(this.$store.state.user.contact_type);


            if (this.asset.id) {
                this.fetchAssetWiseInspection(this.asset.id);
            }

            if (this.$route.query.asset) {
                this.job.asset_id = this.$route.query.asset
                this.fetchByAsset()
            }

            if (!this.$store.state.user) this.$router.push("/login");

            this.job.account_id = this.$store.state.user.account_id;
            if (this.$route.path.indexOf("create") > -1) {
                this.edit = false;
                this.fetchCompanyWiseTechnicians(this.job.company_id);
                this.fetchUsers();
                //this.fetchAssets();
                this.fetchContrctors();
                this.fetchJobStats();
                this.fetchPriorities();
                this.fetchJobTypes();
                // this.fetchCustomers();
                //this.getJobNumber();
                this.fetchAssetModels();
                this.fetchDistributors();
                this.fetchAssetStats();
                this.fetchAssetsByJobType();
                this.fetchInspectionResults();
            } else {
                this.edit = true;
                this.fetchById(this.$route.params.id);
                this.fetchSpareJobByJob(this.$route.params.id);
            }
            //get job id from routes
            this.jobSheet.job_id = this.$route.params.id;
            this.jobClose.job_id = this.$route.params.id;
            this.inspectionResult.job_id = this.$route.params.id;
            this.jobSecondTrip.job_id = this.$route.params.id;
            this.checkSecondTrip(this.$route.params.id);
            this.spareJob.columns.forEach(column => {
                this.spareJob.selectedColumns.push(column.name);
            });
            this.gallery.columns.forEach(column => {
                this.gallery.selectedColumns.push(column.name);
            });
            this.milestone.columns.forEach(column => {
                this.milestone.selectedColumns.push(column.name);
            });
            this.assetUtilization.columns.forEach(column => {
                this.assetUtilization.selectedColumns.push(column.name);
            });
        },
        methods: {
            //common functions starts here
            submit() {
                this.$validator.validate().then(result => {
                    if (result)
                        this.submitTestTagFile()
                })
            },
            submit(emailFlag) {
                this.$validator.validate().then(result => {
                    if (result) {
                        console.log("result", result);
                        this.submitForm(emailFlag);
                    } else {
                        console.log("result", result);
                    }
                });
            },
            fetchAssetModels() {
                NProgress.start();
                this.$http
                    .get(`/api/assetModels`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.assetModels = response.data.data;
                        console.log("assetModels", this.assetModels);
                        this.selectedJobAssetType = this.assetModels.find(
                            x => parseInt(x.id) === parseInt(this.job.asset_model_id)
                        );
                        NProgress.done();
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
            onSelectJobAssetType(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    this.job.asset_model_id = selectedOption.id;
                    this.fetchMachineOption(selectedOption.id);
                }
            },
            onSelectInspectionDescription(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    this.job.inspection_description_id = selectedOption.id;
                }
            },
            onSelectAssetType(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    this.asset.asset_model_id = selectedOption.id;
                    this.fetchMachineOption(selectedOption.id);
                }
            },
            onSelectAssetSerial(selectedOption, id) {
                if (selectedOption) {
                    console.log("asset serial", selectedOption.id);
                    this.job.asset_id = selectedOption.id;
                }
            },
            onSelectInspectionResult(selectedOption, id) {
                if (selectedOption) {
                    this.inspectionResult.result_id = selectedOption.id
                    this.inspectionResult.result_id = selectedOption.id
                }
            },
            fetchDistributors() {
                NProgress.start();
                this.$http
                    .get(`/api/distributors`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.distributors = response.data.data;
                        this.selectedDistributor = this.distributors.find(
                            x => parseInt(x.id) === parseInt(this.asset.agent_id)
                        );
                        console.log("distributors", this.distributors);
                        NProgress.done();
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
            fetchInspectionResults() {
                NProgress.start();
                this.$http
                    .get(`/api/inspectionResultTypes`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.inspectionResultTypes = response.data.data;
                        this.selectedInspectionResult = this.inspectionResultTypes.find(
                            x => parseInt(x.id) === parseInt(this.inspectionResult.result_id)
                        );
                        console.log("inspectionResultTypes", this.inspectionResultTypes);
                        NProgress.done();
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
            onSelectDistributor(selectedOption, name) {
                if (selectedOption) {
                    console.log(selectedOption.name);
                    this.job.asset.agent_id = selectedOption.name;
                    this.job.agent_id = selectedOption.name;
                }
            },
            fetchMachineOption(id) {
                NProgress.start();
                this.$http
                    .get(`/api/asset-model-wise-machine-options/${id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.machineOptions = response.data.data;
                        console.log("machineOptions", this.machineOptions);
                        NProgress.done();
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
            fetchAssetWiseInspection(id) {
                NProgress.start();
                this.$http
                    .get(`/api/asset-wise-inspection/${id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.assetInspections = response.data.data
                        console.log("Asset Inspections", this.assetInspections);
                        if (this.edit) {
                            this.selectedInspectionDescription = this.assetInspections.find(
                                x =>
                                    parseInt(x.id) === parseInt(this.job.inspection_description_id)
                            )
                        }
                        NProgress.done();
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
            fetchAssetStats() {
                NProgress.start();
                this.$http
                    .get(`/api/assetStats`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.assetStats = response.data.data;
                        this.selectedAssetStat = this.assetStats.find(
                            x => parseInt(x.id) === parseInt(this.asset.asset_stat_id)
                        );
                        NProgress.done();
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
            onSelectAssetStat(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.type);
                    let finishingDate = new Date();
                    switch (selectedOption.type) {
                        case " Placement Request":
                            finishingDate.setHours(finishingDate.getHours() + 120);
                            break;
                    }
                    this.asset.finishing_date = finishingDate.toISOString();
                    console.log(finishingDate);
                    this.asset.asset_stat_id = selectedOption.id;
                    this.asset.asset_stat_flag = selectedOption.flag;
                }
            },
            fetchCompanyWiseLocations(id) {
                NProgress.start();
                this.$http
                    .get(`/api/company/${id}/locations`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.locations = response.data.data;
                        this.selectedLocation = this.locations.find(
                            x => parseInt(x.id) === parseInt(this.job.location_id)
                        );
                        console.log("locations", this.locations);
                        NProgress.done();
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
            onSelectLocation(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    // this.job.asset.location_id = selectedOption.id
                    this.job.location_id = selectedOption.id;
                    this.job.contact_person = this.locations.find(
                        x => parseInt(x.id) === parseInt(this.job.location_id)
                    ).user_name;
                    this.job.contact_number = this.locations.find(
                        x => parseInt(x.id) === parseInt(this.job.location_id)
                    ).phone_number;
                }
            },
            updateAssetForm() {
                this.$refs.asset.show();
            },
            emailBoxJobAllocation() {
                this.$refs.emailForm.show();
            },
            emailBox(id) {
                this.jobSheetIdForEmail = id;
                this.$refs.emailFormFirstJobSheet.show();
            },
            fetchCompanyWiseTechnicians(id) {
                NProgress.start();
                this.$http
                    .get(`/api/company/${id}/technicians`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.technicians = response.data.data;
                        console.log("technicians", this.technicians);
                        if (this.edit) {
                            this.selectedTechnician = this.technicians.find(
                                x => parseInt(x.id) === parseInt(this.job.user_id)
                            );
                        }
                        NProgress.done();
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
            fetchCustomers() {
                NProgress.start();
                this.$http
                    .get(`/api/customers`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.customers = response.data.data;
                        console.log("customers", this.customers);
                        if (this.edit) {
                            this.selectedCustomer = this.customers.find(
                                x => parseInt(x.id) === parseInt(this.job.company_id)
                            );
                            this.fetchCompanyWiseLocations(this.job.company_id);
                        }
                        if (this.job.asset_id) {
                            this.selectedCustomer = this.customers.find(
                                x => parseInt(x.id) === parseInt(this.selectedAsset.company_id)
                            );
                            this.fetchCompanyWiseLocations(this.selectedAsset.company_id);
                        }
                        NProgress.done();
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response);
                            if (error.response.status === 401) {
                                window.location.href = "/login";
                            }
                        }
                    });
            },
            companySelected: function (e) {
                this.fetchCompanyWiseTechnicians(event.srcElement.value);
                console.log("test result", event.srcElement.value);
            },
            fetchContrctors() {
                NProgress.start();
                this.$http
                    .get(`/api/contractors`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.contractors = response.data.data;
                        console.log("contractors", this.contractors);
                        //debugger
                        if (this.edit) {
                            this.selectedContractor = this.contractors.find(
                                x => parseInt(x.id) === parseInt(this.job.contractor_id)
                            );
                        }
                        NProgress.done();
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
            fetchUsers() {
                NProgress.start();
                this.$http
                    .get(`/api/owner-users`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.users = response.data.data;
                        console.log("users", this.users);
                        //debugger
                        if (this.edit) {
                            this.selectedUser = this.users.find(
                                x => parseInt(x.id) === parseInt(this.job.raised_by)
                            );
                            this.selectedAdmin = this.users.find(
                                x => parseInt(x.id) === parseInt(this.job.admin_close_out)
                            );
                            this.selectedCloseBy = this.users.find(
                                x =>
                                    parseInt(x.id) ===
                                    parseInt(this.job.technical_manager_close_out)
                            );
                        } else {
                            this.selectedUser = this.users.find(
                                x => parseInt(x.id) === parseInt(this.$store.state.user.id)
                            );
                            this.job.raised_by = this.selectedUser.id;
                        }
                        NProgress.done();
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
            fetchAssets() {
                NProgress.start();
                this.$http
                    .get(`/api/assets`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.assets = response.data.data;
                        console.log("assets", this.assets);
                        if (this.edit) {
                            this.selectedAsset = this.assets.find(
                                x => parseInt(x.id) === parseInt(this.job.asset_id)
                            );
                            this.fetchAssetWiseInspection(this.job.asset_id);
                            this.selectedAssetSerial = this.assets.find(
                                x => parseInt(x.id) === parseInt(this.job.asset_id)
                            );
                        }
                        if (this.job.asset_id) {
                            this.selectedAsset = this.assets.find(
                                x => parseInt(x.id) === parseInt(this.job.asset_id)
                            );
                            this.fetchAssetWiseInspection(this.job.asset_id);
                            this.selectedCustomer = this.customers.find(
                                x => parseInt(x.id) === parseInt(selectedAsset.company_id)
                            );
                        }
                        NProgress.done();
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
            fetchAssetsByJobType() {
                NProgress.start();
                this.$http
                    .get(`/api/asset-by-status-for-job-form`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.serials = response.data.data;
                        console.log("available serials", this.serials);
                        // if (this.edit) {
                        //     this.selectedAsset = this.assets.find(x => parseInt(x.id) === parseInt(this.job.asset_id))
                        // }
                        NProgress.done();
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
            fetchSpareJobByJob(id) {
                NProgress.start();
                this.$http
                    .get(`/api/job/${id}/spareJobs`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.spareJobs = response.data.data;
                        console.log("spareJobs", this.spareJobs);
                        NProgress.done();
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
            fetchPriorities() {
                NProgress.start();
                this.$http
                    .get(`/api/priorities`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.priorities = response.data.data;
                        console.log("priorities", this.priorities);
                        if (this.edit) {
                            this.selectedPriority = this.priorities.find(
                                x => parseInt(x.id) === parseInt(this.job.priority_id)
                            );
                        } else {
                            this.selectedPriority = this.priorities.find(
                                x => x.name === "Medium"
                            );
                            this.job.priority_id = this.selectedPriority.id;

                            let finishingDate = new Date();
                            finishingDate.setHours(finishingDate.getHours() + 24);
                            this.job.required_date = finishingDate.toISOString();
                        }
                        NProgress.done();
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
            fetchJobStats() {
                NProgress.start();
                this.$http
                    .get(`/api/jobStats`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.jobStats = response.data.data;
                        console.log("jobStats", this.jobStats);
                        if (this.edit) {
                            this.selectedjobStat = this.jobStats.find(
                                x => parseInt(x.id) === parseInt(this.job.job_stat_id)
                            );
                        } else {
                            this.selectedjobStat = this.jobStats.find(
                                x => x.type === "Allocated"
                            );
                            this.job.job_stat_id = this.selectedjobStat.id;
                        }
                        NProgress.done();
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
            fetchJobTypes() {
                NProgress.start();
                this.$http
                    .get(`/api/jobTypes`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.jobTypes = response.data.data;
                        console.log("jobTypes", this.jobTypes);
                        if (this.edit) {
                            this.selectedJobType = this.jobTypes.find(
                                x => parseInt(x.id) === parseInt(this.job.job_type_id)
                            );
                            if (this.selectedJobType.name == "Placement Request")
                                this.placementMode = true;
                            if (this.selectedJobType.name == "QA") this.qualityMode = true
                            if (this.selectedJobType.name == "Test & Tag") this.testMode = true
                        }
                        NProgress.done();
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
            submitForm(userEmail) {
                this.isSubmitDisabled = true
                self = this
                this.job.emailFlag = {
                    userEmail: userEmail,
                    technicianEmail: this.technicianEmail,
                    contractEmails: this.contractEmails,
                    loggedInUser: this.$store.state.user.email
                }
                if (userEmail) {
                    console.log("userEmail", userEmail)
                    this.selectedjobStat = this.jobStats.find(x => x.type === "Allocated")
                    this.job.job_stat_id = this.selectedjobStat.id
                }
                if (this.job.machine_option)
                    this.job.machine_option = this.job.machine_option.join()

                if (this.edit) {
                    this.job.updated_by = this.$store.state.user.id
                    this.$http
                        .put(`/api/job`, this.job, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            this.isSubmitDisabled = false
                            this.$notify({
                                group: 'foo',
                                type: 'info',
                                title: 'Notification',
                                text: 'Job Information Updated'
                            })
                            window.history.back()
                        })
                        .catch(error => {
                            this.isSubmitDisabled = false
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push("/login")
                                }
                            }
                        })
                } else {
                    NProgress.start()
                    this.job.created_by = this.$store.state.user.id
                    this.$http
                        .post(`/api/job`, this.job, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            this.isSubmitDisabled = false
                            NProgress.done()
                            // alert("Job Updated")
                            //this.$router.push({ path: `/jobs` })
                            this.$notify({
                                group: 'foo',
                                type: 'success',
                                title: 'Notification',
                                text: 'Job Information Saved'
                            })
                            window.history.back()
                        })
                        .catch(error => {
                            this.isSubmitDisabled = false
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push("/login")
                                }
                                
                            }
                        })
                }
            },
            fetchById(id) {
                this.$http
                    .get(`/api/job/${id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.job = response.data.data;

                        this.selectedLocation = {
                            id: this.job.location.id,
                            full_address: `${this.job.location.description} - ${
                                this.job.location.address
                                } - ${this.job.location.agent_id}`
                        };
                        this.job.location_id = this.job.location.id;

                        this.selectedCustomer = this.job.company;
                        this.job.company_id = this.job.company.id;
                        // this.fetchCustomers()
                        //this.fetchCompanyWiseLocations(this.job.location_id)
                        this.fetchCompanyWiseTechnicians(this.job.contractor_id);
                        //this.fetchAssetWiseCompany(this.job.asset_id)
                        //console.log("job", this.job)
                        //get job sheet by job id

                        this.fetchJobSheetByJob(id);
                        //get inspection result by job
                        this.fetchInspectionResultByJob(id);
                        //get job close by job id
                        this.fetchJobCloseByJob(id);
                        this.fetchUsers();
                        //this.fetchAssets();
                        if (this.job.asset) this.fillAssetByJob(this.job);
                        this.fetchContrctors();
                        this.fetchJobStats();
                        this.fetchPriorities();

                        this.fetchJobTypes();
                        this.fetchAssetsByJobType();
                        this.fetchAssetModels();
                        this.fetchDistributors();
                        this.fetchAssetStats();
                        this.fetchInspectionResults();
                        this.fetchAssetWiseInspection(this.job.asset_id);

                        if (this.job.asset_model_id) {
                            this.fetchMachineOption(this.job.asset_model_id);
                            this.fetchDocumentByAssetModel(this.job.asset_model_id);
                        }

                        // this.fetchCompanyById(this.job.company_id)
                        if (this.job.asset_id) this.fetchAssetById(this.job.asset_id);
                        this.galleryLoad(1);
                        this.milestoneLoad(1);
                         if (this.job.asset_id) {
                             this.assetUtilizationLoad(1)
                        }
                        
                        this.selectedChargeTo = { id: null, name: this.job.charge_to };
                        this.job.machine_option = this.job.machine_option.split(",");
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
            onSelectContract(selectedOption, id) {
                if (selectedOption) {
                    this.contractEmails = [];
                    console.log(selectedOption.id);
                    this.job.contractor_id = selectedOption.id;
                    this.fetchCompanyWiseTechnicians(selectedOption.id);
                    let contractor = this.contractors.find(x => x.id === selectedOption.id);
                    contractor.user.forEach(user => {
                        if (user.contact_type == "Manager") {
                            this.contractEmails.push(user.email);
                        }
                    });
                    //console.log("contractEmails", this.contractEmails)
                    alert(this.contractEmails);
                }
            },
            onSelectUser(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    this.job.raised_by = selectedOption.id;
                }
            },
            onSelectAdmin(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    this.job.admin_close_out = selectedOption.id;
                }
            },
            onSelectCloseBy(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    this.job.technical_manager_close_out = selectedOption.id;
                }
            },
            onSelectPriority(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.name);
                    let finishingDate = new Date();
                    switch (selectedOption.name) {
                        case "High":
                            finishingDate.setHours(finishingDate.getHours() + 4);
                            break;
                        case "Medium":
                            finishingDate.setHours(finishingDate.getHours() + 24);
                            break;
                        case "Low":
                            finishingDate.setHours(finishingDate.getHours() + 336);
                            break;
                    }
                    this.job.required_date = finishingDate.toISOString();
                    console.log(finishingDate);
                    this.job.priority_id = selectedOption.id;
                }
            },
            onSelectJobType(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    this.job.job_type_id = selectedOption.id;
                    if (selectedOption.name == "Placement Request") {
                        this.placementMode = true
                    } else {
                        this.placementMode = false
                    }
                    if (selectedOption.name == "QA") {
                        this.qualityMode = true
                    } else {
                        this.qualityMode = false
                    }
                    if (selectedOption.name == "Test & Tag") {
                        this.testMode = true
                    } else {
                        this.testMode = false
                    }
                }
            },
            onSelectCustomer(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    this.job.company_id = selectedOption.id;
                    this.fetchCompanyWiseLocations(selectedOption.id);
                }
            },
            onSelectTechnician(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    this.job.user_id = selectedOption.id;
                    this.technicianEmail = this.technicians.find(
                        x => x.id === selectedOption.id
                    ).email;
                    //console.log("technicianEmail", this.technicianEmail);
                    alert(this.technicianEmail);
                }
            },
            onSelectAsset(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption);
                    this.job.asset_id = selectedOption.id;
                    this.job.contact_person = this.assets.find(
                        x => parseInt(x.id) === parseInt(selectedOption.id)
                    ).full_name;
                    this.fetchAssetWiseInspection(selectedOption.id);

                    this.selectedLocation = {
                        id: selectedOption.location.id,
                        full_address: `${selectedOption.location.description} - ${
                            selectedOption.location.address
                            } - ${selectedOption.location.agent_id}`
                    };
                    this.job.location_id = selectedOption.location.id;
                    this.selectedCustomer = selectedOption.company;
                    this.job.company_id = selectedOption.company.id;
                }
            },
            getContractor(id) {
                return this.contractors.find(x => parseInt(x.id) === parseInt(id))
                    ? this.contractors.find(x => parseInt(x.id) === parseInt(id))
                    : "";
            },
            onSelectjobStat(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    this.job.job_stat_id = selectedOption.id;
                }
            },
            fetchAssetWiseCompany(id) {
                this.$http
                    .get(`/api/asset/${id}/company`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.asset = response.data.data;
                        console.log("asset", this.asset);
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
            //job sheet module starts here
            fetchJobSheetByJob(id) {
                this.$http
                    .get(`/api/job-wise-job-sheet/${id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.jobSheet = response.data.data.length
                            ? response.data.data[0]
                            : this.jobSheet;
                        this.url = this.jobSheet.image;
                        this.url = this.jobSecondTrip.image;
                        console.log("job sheet", this.jobSheet);
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
            //submit job sheet
            submitJobSheet() {
                NProgress.start()
                this.isSubmitDisabledJobSheet = true
                self = this
                this.jobSheet.user_id = this.$store.state.user.id
                if (this.jobSheet.id) {
                    this.jobSheet.updated_by = this.$store.state.user.id
                    this.jobSheet.job_stat_id = this.selectedjobStat.id
                    this.$http
                        .put(`/api/jobSheet`, this.jobSheet, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            NProgress.done()
                            this.isSubmitDisabledJobSheet = false
                            this.$router.push(`/jobs`)
                             this.$notify({
                                group: 'foo',
                                type: 'info',
                                title: 'Notification',
                                text: 'Job Sheet Information Updated'
                            })
                        })
                        .catch(error => {
                            this.isSubmitDisabledJobSheet = false
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push("/login")
                                }
                            }
                        })
                } else {
                    this.jobSheet.created_by = this.$store.state.user.id
                    this.jobSheet.job_stat_id = this.selectedjobStat.id
                    this.$http
                        .post(`/api/jobSheet`, this.jobSheet, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            this.isSubmitDisabledJobSheet = false
                            this.$router.push(`/jobs`)
                            this.$notify({
                                group: 'foo',
                                type: 'success',
                                title: 'Notification',
                                text: 'Job Sheet Information Saved'
                            })
                        })
                        .catch(error => {
                            this.isSubmitDisabledJobSheet = false
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push("/login")
                                }
                            }
                        })
                }
            },
            //job close module starts here
            fetchJobCloseByJob(id) {
                this.$http
                    .get(`/api/job-wise-job-close/${id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.jobClose = response.data.data.length
                            ? response.data.data[0]
                            : this.jobClose;
                        console.log("job close", this.jobClose)
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
            //submit job Close Out Form
            submitJobClose() {
                self = this;
                this.jobClose.user_id = this.$store.state.user.id
                if (this.jobClose.id) {
                    this.jobClose.updated_by = this.$store.state.user.id
                    this.$http
                        .put(`/api/jobClose`, this.jobClose, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            alert("Job Close Out Information Updated");
                            //this.$router.push(`/jobs`);
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
                    this.jobClose.created_by = this.$store.state.user.id
                    this.$http
                        .post(`/api/jobClose`, this.jobClose, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            //alert("Job Close Out Information Updated");
                            //this.$router.push(`/jobs`);
                        })
                        .catch(error => {
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push("/login")
                                }
                            }
                        });
                }
            },
            //file upload
            onFileChange(e) {
                let ref = this;
                const file = e.target.files[0]
                this.url = URL.createObjectURL(file)
                var reader = new FileReader()
                reader.readAsDataURL(file)
                reader.onloadend = function () {
                    let base64data = reader.result
                    console.log(base64data)
                    ref.jobSheet.image = base64data
                };
                //console.log(this.url)
            },
            //file upload
            onFileChanges(e) {
                let ref = this;
                const file = e.target.files[0];
                this.url = URL.createObjectURL(file);
                var reader = new FileReader()
                reader.readAsDataURL(file)
                reader.onloadend = function () {
                    let base64data = reader.result
                    console.log(base64data)
                    ref.jobSecondTrip.image = base64data
                };
                //console.log(this.url)
            },
            //file upload
            onFileChange(e) {
                let ref = this;
                const file = e.target.files[0];
                this.url = URL.createObjectURL(file);
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = function () {
                    let base64data = reader.result;
                    console.log(base64data);
                    ref.jobSecondTrip.image = base64data;
                };
                //console.log(this.url)
            },
            //job sheet module ends here
            onInput({ number, isValid, country }) {
                console.log(number, isValid, country);
            },
            //send email
            sendEmail() {
                NProgress.start();
                this.$http
                    .get(
                        `/api/job-send-email/${this.job.id}/${this.sendEmailModal.email}/${
                        this.sendEmailModal.description
                        }`,
                        {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        }
                    )
                    .then(response => {
                        alert("Email Sent");
                        console.log("email sent", response.data);
                        // this.selectedjobStat = this.jobStats.find(x => x.type === 'Allocated')
                        // this.job.job_stat_id = this.selectedjobStat.id
                        NProgress.done();
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
            //send email From First Job Sheet
            sendEmailFirstJobSheet(jobSheetId) {
                NProgress.start();
                this.$http
                    .get(
                        `/api/first-job-sheet-send-email/${jobSheetId}/${
                        this.sendEmailModalJobSheet.email
                        }/${this.sendEmailModalJobSheet.description}`,
                        {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        }
                    )
                    .then(response => {
                        alert("Email Sent");
                        console.log("email sent", response.data);
                        // this.selectedjobStat = this.jobStats.find(x => x.type === 'Allocated')
                        // this.job.job_stat_id = this.selectedjobStat.id
                        NProgress.done();
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
            eraseSpareJob(id) {
                self = this;
                self
                    .$swal({
                        title: "Do You Really Want To Delete？",
                        text: "You Can Not Revive This Information!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "OK",
                        cancelButtonText: "Cancel"
                    })
                    .then(result => {
                        if (result.value) {
                            this.$http
                                .delete(`/api/spareJob/${id}`, {
                                    headers: {
                                        Authorization: `Bearer ${this.$store.state.token}`
                                    }
                                })
                                .then(response => {
                                    this.$swal(
                                        "Deleted!",
                                        "Information Is Deleted From Database",
                                        "success"
                                    );
                                    this.fetchSpareJobByJob(this.$route.params.id);
                                })
                                .catch(error => {
                                    if (error.response) {
                                        console.log(error.response);
                                        if (error.response.status === 401) {
                                            this.$router.push("/login");
                                        }
                                    }
                                });
                        } else {
                            this.$swal("Your Information Is Safe", "", "error");
                        }
                    });
            },
            changeJobStatus() {
                let self = this;
                Vue.nextTick(function () {
                    if (self.jobSheet.is_complete) {
                        self.selectedjobStat = self.jobStats.find(x => x.type === "Complete");
                        self.checkJobComplete = true;
                    } else {
                        self.selectedjobStat = self.jobStats.find(x => x.type === "Received");
                        self.checkJobComplete = false;
                    }
                });
            },
            changeJobSecondTripStatus() {
                let self = this;
                Vue.nextTick(function () {
                    if (self.jobSecondTrip.is_complete) {
                        self.selectedjobStat = self.jobStats.find(x => x.type === "Complete");
                        self.checkJobComplete = true;
                    } else {
                        self.selectedjobStat = self.jobStats.find(x => x.type === "Received");
                        self.checkJobComplete = false;
                    }
                });
            },
            changeAdminCloseOut() {
                let self = this;
                Vue.nextTick(function () {
                    console.log(self.job.close_by_admin);
                    if (self.job.close_by_admin) {
                        self.selectedjobStat = self.jobStats.find(x => x.type === "Close");
                    } else {
                        self.selectedjobStat = self.jobStats.find(x => x.type === "Complete");
                    }
                    self.job.job_stat_id = self.selectedjobStat.id;
                });
            },
            adminApproval() {
                let self = this;
                Vue.nextTick(function () {
                    console.log(self.job.need_approval);
                    if (self.job.need_approval) {
                        self.selectedjobStat = self.jobStats.find(
                            x => x.type === "Need Admin Approval"
                        );
                    } else {
                        self.selectedjobStat = self.jobStats.find(x => x.type === "Complete");
                    }
                    self.job.job_stat_id = self.selectedjobStat.id;
                });
            },
            //Open Second Trip Tab
            openSecondTrip() {
                this.secondTrip = true;
            },
            submitJobSecondTrip() {
                NProgress.start()
                this.isSubmitDisabledSecondJobSheet = true
                self = this
                this.jobSecondTrip.user_id = this.$store.state.user.id
                this.jobSecondTrip.job_stat_id = this.selectedjobStat.id
                if (this.jobSecondTrip.id) {
                    this.jobSecondTrip.updated_by = this.$store.state.user.id
                    this.$http
                        .put(`/api/jobSheet`, this.jobSecondTrip, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            NProgress.done()
                            this.isSubmitDisabledSecondJobSheet = false
                            this.$router.push(`/jobs`)
                            this.$notify({
                                group: 'foo',
                                type: 'info',
                                title: 'Notification',
                                text: 'Job Sheet Information Updated'
                            })
                        })
                        .catch(error => {
                            this.isSubmitDisabledSecondJobSheet = false
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push("/login")
                                }
                            }
                        })
                } else {
                    this.jobSecondTrip.created_by = this.$store.state.user.id
                    this.jobSecondTrip.job_stat_id = this.selectedjobStat.id
                    this.jobSecondTrip.second_trip = true
                    this.$http
                        .post(`/api/jobSheet`, this.jobSecondTrip, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            this.isSubmitDisabledSecondJobSheet = false
                            this.$router.push(`/jobs`)
                            this.$notify({
                                group: 'foo',
                                type: 'success',
                                title: 'Notification',
                                text: 'Job Sheet Information Saved'
                            })
                        })
                        .catch(error => {
                            this.isSubmitDisabledSecondJobSheet = false
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push("/login")
                                }
                            }
                        })
                }
            },
            submitInspectionResult() {
                NProgress.start()
                this.isSubmitDisabledQa = true
                self = this
                if (this.inspectionResult.id) {
                    this.inspectionResult.updated_by = this.$store.state.user.id
                    this.$http
                        .put(`/api/inspectionResult`, this.inspectionResult, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            NProgress.done()
                            this.isSubmitDisabledQa = false
                            this.$notify({
                                group: 'foo',
                                type: 'info',
                                title: 'Notification',
                                text: 'QA Result Updated'
                            })
                        })
                        .catch(error => {
                            this.isSubmitDisabledQa = false
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push("/login")
                                }
                            }
                        })
                } else {
                    this.inspectionResult.created_by = this.$store.state.user.id
                    this.$http
                        .post(`/api/inspectionResult`, this.inspectionResult, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            this.isSubmitDisabledQa = false
                            this.$notify({
                                group: 'foo',
                                type: 'Success',
                                title: 'Notification',
                                text: 'QA Result Saved'
                            })
                        })
                        .catch(error => {
                            this.isSubmitDisabledQa = false
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push("/login")
                                }
                            }
                        })
                }
            },
            submitTestTag() {
                NProgress.start()
                this.isSubmitDisabledTestTag = true
                self = this
                if (this.inspectionResult.id) {
                    this.inspectionResult.updated_by = this.$store.state.user.id
                    this.$http
                        .put(`/api/inspectionResult`, this.inspectionResult, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            NProgress.done()
                            this.isSubmitDisabledTestTag = false
                            this.$notify({
                                group: 'foo',
                                type: 'info',
                                title: 'Notification',
                                text: 'Test & Tag Result Updated'
                            })
                        })
                        .catch(error => {
                            this.isSubmitDisabledTestTag = false
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push("/login")
                                }
                            }
                        })
                } else {
                    this.inspectionResult.created_by = this.$store.state.user.id
                    this.$http
                        .post(`/api/inspectionResult`, this.inspectionResult, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            this.isSubmitDisabledTestTag = false
                            this.$notify({
                                group: 'foo',
                                type: 'Success',
                                title: 'Notification',
                                text: 'Test & Tag Result Saved'
                            })
                        })
                        .catch(error => {
                            this.isSubmitDisabledTestTag = false
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push("/login")
                                }
                            }
                        })
                }
            },
            handleFileUpload() {
                this.uploadedFile = this.$refs.file.files[0];
                console.log(this.uploadedFile)
            },
            submitTestTagFile() {
                let formData = new FormData()
                formData.append('file', this.uploadedFile)
                console.log(formData)
                NProgress.start()
                this.$http.post('/api/upload-file',
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                             Authorization: `Bearer ${this.$store.state.token}`
                        }
                    }
                )
                .then(response => {
                        NProgress.done()
                        console.log(response.data)
                        this.uploadedFile = null
                        const input = this.$refs.file
                        input.type = 'file'
                        this.inspectionResult.file = response.data.file
                        this.submitTestTagFile()

                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            NProgress.done()
                            ErrorHandler.handle(error.response.status, this)
                        }
                    })
            },
            submitClicked(){
                if(this.uploadedFile){
                    this.submitTestTagFile()
                }
                else{
                    this.submitTestTag()
                }
            },
            fetchInspectionResultByJob(id) {
                this.$http
                    .get(`/api/job-wise-inspection-result/${id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.inspectionResult = response.data.data.length
                            ? response.data.data[0]
                            : this.inspectionResult;
                        //console.log("Inspection Result By job", this.inspectionResult);
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
            checkSecondTrip(id) {
                this.$http
                    .get(`/api/check-second-trip/${id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.jobSecondTripData = response.data.data;
                        console.log("checkSecondTrip", this.jobSecondTripData);
                        if (this.jobSecondTripData.length) {
                            this.secondTrip = true;
                            this.jobSecondTrip = response.data.data[0];
                        }
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
            fetchAssetById(id) {
                NProgress.start();
                this.$http
                    .get(`/api/asset/${id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.job.asset = response.data.data;
                        this.job.machine_option = this.job.machine_option.split(",");
                        console.log("asset", this.asset);
                        if (this.edit) {
                            this.selectedAsset = this.assets.find(
                                x => parseInt(x.id) === parseInt(this.job.asset_id)
                            );
                            this.selectedAssetSerial = this.job.asset_id;
                        }
                        NProgress.done();
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
            // Spare Job Datatable
            spareJobErase() {
                self = this;
                self
                    .$swal({
                        title: "Do You Really Want To Delete？",
                        text: "You Can Not Revive This Information!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "OK",
                        cancelButtonText: "Cancel"
                    })
                    .then(result => {
                        if (result.value) {
                            this.$http
                                .delete(`/api/spareJob/${this.spareJob.selectedIds.join(",")}`, {
                                    headers: {
                                        Authorization: `Bearer ${this.$store.state.token}`
                                    }
                                })
                                .then(response => {
                                    this.$swal(
                                        "Deleted!",
                                        "Information Is Deleted From Database",
                                        "success"
                                    );
                                    this.spareJobLoad(1);
                                    this.spareJob.selectedIds = [];
                                })
                                .catch(error => {
                                    if (error.response) {
                                        console.log(error.response);
                                        if (error.response.status === 401) {
                                            this.$router.push("/login");
                                        }
                                    }
                                });
                        } else {
                            this.$swal("Your Information Is Safe", "", "error");
                        }
                    });
            },
            spareJobSortByColumn: function (s) {
                if (s === this.spareJob.currentSortColumn) {
                    this.spareJob.currentSortDirection =
                        this.spareJob.currentSortDirection === "asc" ? "desc" : "asc";
                }
                this.spareJob.currentSortColumn = s;
            },
            spareJobSetSelectedColumnSortIcon(type) {
                if (
                    this.spareJob.currentSortDirection == "asc" &&
                    this.spareJob.currentSortColumn == type
                )
                    return "fa fa-arrow-down";
                else if (
                    this.spareJob.currentSortDirection == "desc" &&
                    this.spareJob.currentSortColumn == type
                )
                    return "fa fa-arrow-down open";
                else return "fa fa-arrow-down open fa-disabled";
            },
            // Paginating the table data
            spareJobMakePagination(meta) {
                let pagination = {
                    per_page: this.spareJob.pagination.per_page,
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    total: meta.total,
                    from: meta.from,
                    to: meta.to,
                    perPageItems: this.spareJob.pagination.perPageItems
                };
                this.spareJob.pagination = pagination;
            },
            spareJobAllspareJobRowSelect() {
                var self = this;
                Vue.nextTick(function () {
                    self.spareJob.selectedIds = [];
                    if (self.spareJob.all) {
                        self.sortedSpareJob.forEach(spareJob => {
                            self.spareJob.selectedIds.push(spareJob.id.toString());
                        });
                    } else {
                        if (self.sortedSpareJob.length == self.spareJob.selectedIds.length) {
                            self.spareJob.selectedIds = [];
                        }
                    }
                });
            },
            spareJobRowSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    if (self.sortedSpareJob.length == self.spareJob.selectedIds.length) {
                        self.spareJob.all = true;
                    } else {
                        self.spareJob.all = false;
                    }
                });
            },
            spareJobColumnSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    console.log(self.spareJob.selectedColumns);
                });
            },
            spareJobLoad(page) {
                NProgress.start();
                let url = `/api/spareJobs-datatable/${this.$route.params.id}`;
                let params = `?page=${page}&per_page=${
                    this.spareJob.pagination.per_page
                    }&search=${this.spareJob.search.global}`;
                this.$http
                    .get(url + params, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(({ data }) => {
                        console.log("spareJobs", data);
                        this.spareJobs = data.data;
                        this.spareJobMakePagination(data.meta);
                        NProgress.done();
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
            spareJobPageChange(page) {
                if (page >= 1 && page <= this.spareJob.pagination.last_page)
                    this.spareJobLoad(page);
            },
            spareJobPerPageChanged(count) {
                this.spareJob.pagination.per_page = count;
                this.spareJobLoad(1);
            },
            spareJobClearSearch() {
                if (this.spareJob.search.global.length) {
                    this.spareJob.search.global = "";
                    this.spareJobLoad(1);
                }
                this.spareJob.search.spare_name = "";
                this.spareJob.search.allocated = "";
                this.spareJob.search.used = "";
                this.spareJob.search.comment = "";
            },
            spareJobOnSelectPageCount(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption, id);
                    this.spareJob.pagination.per_page = selectedOption;
                    this.spareJobLoad(1);
                }
            },
            spareJobCheckColumnVisibility(column) {
                return this.spareJob.selectedColumns.indexOf(column) !== -1
                    ? true
                    : false;
            },
            // Spare Job Datatable Ends Here

            onCloseTimeIn() {
                this.jobSheet.timein = this.$refs.timeIn.getValue();
                this.calculateOnSiteTime(this.jobSheet.timein, this.jobSheet.timeout);
            },
            onCloseTimeOut() {
                this.jobSheet.timeout = this.$refs.timeOut.getValue();
                let newMinute = this.jobSheet.timeout.substring(3, 5);
                let newHour = this.jobSheet.timeout.substring(0, 2);
                let minute = parseInt(this.jobSheet.timeout.substring(3, 5));
                let hour = parseInt(this.jobSheet.timeout.substring(0, 2));
                if (minute > 0 && minute <= 15) {
                    newMinute = "15";
                } else if (minute > 15 && minute <= 25) {
                    newMinute = "30";
                } else if (minute > 25 && minute <= 30) {
                    newMinute = "30";
                } else if (minute > 30 && minute <= 45) {
                    newMinute = "45";
                // } else if (minute > 45 && minute <= 55) {
                //     newMinute = "50";
                } else if (minute > 45 && minute <= 59) {
                    newMinute = "00";
                    newHour = hour + 1;
                }
                this.jobSheet.timeout = `${newHour}:${newMinute}`;
                console.log(this.jobSheet.timeout);
                this.calculateOnSiteTime(this.jobSheet.timein, this.jobSheet.timeout);
            },
            calculateOnSiteTime(timeIn, timeOut) {
                if (timeIn.length && timeOut.length) {
                    let ms =
                        new Date("1970-01-01T" + timeOut + "Z").getTime() -
                        new Date("1970-01-01T" + timeIn + "Z").getTime();
                    var seconds = ms / 1000;
                    var hours = parseInt(seconds / 3600);
                    seconds = seconds % 3600;
                    var minutes = parseInt(seconds / 60);
                    seconds = seconds % 60;
                    this.jobSheet.onstie = `${hours}:${minutes}`;
                }
            },
            onCloseTimeInSecondTrip() {
                this.jobSecondTrip.timein = this.$refs.timeInSecondTrip.getValue();
                this.calculateOnSiteTimeSecondTrip(
                    this.jobSecondTrip.timein,
                    this.jobSecondTrip.timeout
                );
            },
            onCloseTimeOutSecondTrip() {
                this.jobSecondTrip.timeout = this.$refs.timeOutSecondTrip.getValue();
                let newMinute = this.jobSecondTrip.timeout.substring(3, 5);
                let newHour = this.jobSecondTrip.timeout.substring(0, 2);
                let minute = parseInt(this.jobSecondTrip.timeout.substring(3, 5));
                let hour = parseInt(this.jobSecondTrip.timeout.substring(0, 2));
                if (minute > 0 && minute <= 15) {
                    newMinute = "15";
                } else if (minute > 15 && minute <= 30) {
                    newMinute = "30";
                } else if (minute > 30 && minute <= 45) {
                    newMinute = "45";
                } else if (minute > 45 && minute <= 59) {
                    newMinute = "00";
                    newHour = hour + 1;
                }
                this.jobSecondTrip.timeout = `${newHour}:${newMinute}`;
                console.log(this.jobSecondTrip.timeout);
                this.calculateOnSiteTimeSecondTrip(
                    this.jobSecondTrip.timein,
                    this.jobSecondTrip.timeout
                );
            },
            calculateOnSiteTimeSecondTrip(timeInSecondTrip, timeOutSecondTrip) {
                if (timeInSecondTrip.length && timeOutSecondTrip.length) {
                    let ms =
                        new Date("1970-01-01T" + timeOutSecondTrip + "Z").getTime() -
                        new Date("1970-01-01T" + timeInSecondTrip + "Z").getTime();
                    var seconds = ms / 1000;
                    var hours = parseInt(seconds / 3600);
                    seconds = seconds % 3600;
                    var minutes = parseInt(seconds / 60);
                    seconds = seconds % 60;
                    this.jobSecondTrip.onstie = `${hours}:${minutes}`;
                }
            },
            onSelectChargeTo(selectedOption, id) {
                console.log(selectedOption);
                this.job.charge_to = selectedOption.name;
            },
            createPDF() {
                window.location.href = `/api/rpo-download/${this.$route.params.id}`
            },
            createJobSheetPDF(id) {
                window.location.href = `/api/job-sheet-download/${id}`
            },
            getJobNumber() {
                this.$http
                    .get(`/api/job-number`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.job.job_number = parseInt(response.data.jobNumber) + 1;
                        console.log(response);
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
            fetchDocumentByAssetModel(id) {
                NProgress.start();
                this.$http
                    .get(`/api/document-by-asset-model-id/${id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.documents = response.data.data;
                        console.log("documents", this.documents);
                        NProgress.done();
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
            openFile(file) {
                console.log(file);
                var win = window.open(`/api/download-file/${file}`, "_blank");
                win.focus();
            },
            ShowDocument() {
                this.$refs.documentModal.show();
            },
            //Tab Flag
            selectedTab(type) {
                this.selectedTabFlag = type;
            },
            // gallery Datatable
            galleryErase() {
                self = this;
                self
                    .$swal({
                        title: "Do You Really Want To Delete？",
                        text: "You Can Not Revive This Information!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "OK",
                        cancelButtonText: "Cancel"
                    })
                    .then(result => {
                        if (result.value) {
                            this.$http
                                .delete(`/api/gallery/${this.gallery.selectedIds.join(",")}`, {
                                    headers: {
                                        Authorization: `Bearer ${this.$store.state.token}`
                                    }
                                })
                                .then(response => {
                                    this.$swal(
                                        "Deleted!",
                                        "Information Is Deleted From Database",
                                        "success"
                                    );
                                    this.galleryLoad(1);
                                    this.gallery.selectedIds = [];
                                })
                                .catch(error => {
                                    if (error.response) {
                                        console.log(error.response);
                                        if (error.response.status === 401) {
                                            this.$router.push("/login");
                                        }
                                    }
                                });
                        } else {
                            this.$swal("Your Information Is Safe", "", "error");
                        }
                    });
            },
            gallerySortByColumn: function (s) {
                if (s === this.gallery.currentSortColumn) {
                    this.gallery.currentSortDirection =
                        this.gallery.currentSortDirection === "asc" ? "desc" : "asc";
                }
                this.gallery.currentSortColumn = s;
            },
            gallerySetSelectedColumnSortIcon(type) {
                if (
                    this.gallery.currentSortDirection == "asc" &&
                    this.gallery.currentSortColumn == type
                )
                    return "fa fa-arrow-down";
                else if (
                    this.gallery.currentSortDirection == "desc" &&
                    this.gallery.currentSortColumn == type
                )
                    return "fa fa-arrow-down open";
                else return "fa fa-arrow-down open fa-disabled";
            },
            // Paginating the table data
            galleryMakePagination(meta) {
                let pagination = {
                    per_page: this.gallery.pagination.per_page,
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    total: meta.total,
                    from: meta.from,
                    to: meta.to,
                    perPageItems: this.gallery.pagination.perPageItems
                };
                this.gallery.pagination = pagination;
            },
            galleryAllgalleryRowSelect() {
                var self = this;
                Vue.nextTick(function () {
                    self.gallery.selectedIds = [];
                    if (self.gallery.all) {
                        self.sortedGallery.forEach(gallery => {
                            self.gallery.selectedIds.push(gallery.id.toString());
                        });
                    } else {
                        if (self.sortedGallery.length == self.gallery.selectedIds.length) {
                            self.gallery.selectedIds = [];
                        }
                    }
                });
            },
            galleryRowSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    if (self.sortedGallery.length == self.gallery.selectedIds.length) {
                        self.gallery.all = true;
                    } else {
                        self.gallery.all = false;
                    }
                });
            },
            galleryColumnSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    console.log(self.gallery.selectedColumns);
                });
            },
            galleryLoad(page) {
                NProgress.start();
                let url = `/api/galleries-datatable/${this.$route.params.id}`;
                let params = `?page=${page}&per_page=${
                    this.gallery.pagination.per_page
                    }&search=${this.gallery.search.global}`;
                this.$http
                    .get(url + params, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(({ data }) => {
                        console.log("galleries", data);
                        this.galleries = data.data;
                        NProgress.done();
                        this.galleryMakePagination(data.meta);
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
            galleryPageChange(page) {
                if (page >= 1 && page <= this.gallery.pagination.last_page)
                    this.galleryLoad(page);
            },
            galleryPerPageChanged(count) {
                this.gallery.pagination.per_page = count;
                this.galleryLoad(1);
            },
            galleryClearSearch() {
                if (this.gallery.search.global.length) {
                    this.gallery.search.global = "";
                    this.galleryLoad(1);
                }
                this.gallery.search.description = "";
                this.gallery.search.image = "";
            },
            galleryOnSelectPageCount(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption, id);
                    this.gallery.pagination.per_page = selectedOption;
                    this.galleryLoad(1);
                }
            },
            galleryCheckColumnVisibility(column) {
                return this.gallery.selectedColumns.indexOf(column) !== -1 ? true : false;
            },
            //end of gallery datatable
            // milestone Datatable
            milestoneErase() {
                self = this;
                self
                    .$swal({
                        title: "Do You Really Want To Delete？",
                        text: "You Can Not Revive This Information!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "OK",
                        cancelButtonText: "Cancel"
                    })
                    .then(result => {
                        if (result.value) {
                            this.$http
                                .delete(
                                    `/api/milestone/${this.milestone.selectedIds.join(",")}`,
                                    {
                                        headers: {
                                            Authorization: `Bearer ${this.$store.state.token}`
                                        }
                                    }
                                )
                                .then(response => {
                                    this.$swal(
                                        "Deleted!",
                                        "Information Is Deleted From Database",
                                        "success"
                                    );
                                    this.milestoneLoad(1);
                                    this.milestone.selectedIds = [];
                                })
                                .catch(error => {
                                    if (error.response) {
                                        console.log(error.response);
                                        if (error.response.status === 401) {
                                            this.$router.push("/login");
                                        }
                                    }
                                });
                        } else {
                            this.$swal("Your Information Is Safe", "", "error");
                        }
                    });
            },
            milestoneSortByColumn: function (s) {
                if (s === this.milestone.currentSortColumn) {
                    this.milestone.currentSortDirection =
                        this.milestone.currentSortDirection === "asc" ? "desc" : "asc";
                }
                this.milestone.currentSortColumn = s;
            },
            milestoneSetSelectedColumnSortIcon(type) {
                if (
                    this.milestone.currentSortDirection == "asc" &&
                    this.milestone.currentSortColumn == type
                )
                    return "fa fa-arrow-down";
                else if (
                    this.milestone.currentSortDirection == "desc" &&
                    this.milestone.currentSortColumn == type
                )
                    return "fa fa-arrow-down open";
                else return "fa fa-arrow-down open fa-disabled";
            },
            // Paginating the table data
            milestoneMakePagination(meta) {
                let pagination = {
                    per_page: this.milestone.pagination.per_page,
                    current_page: meta.current_page,
                    last_page: meta.last_page,
                    total: meta.total,
                    from: meta.from,
                    to: meta.to,
                    perPageItems: this.milestone.pagination.perPageItems
                };
                this.milestone.pagination = pagination;
            },
            milestoneAllmilestoneRowSelect() {
                var self = this;
                Vue.nextTick(function () {
                    self.milestone.selectedIds = [];
                    if (self.milestone.all) {
                        self.sortedMilestone.forEach(milestone => {
                            self.milestone.selectedIds.push(milestone.id.toString());
                        });
                    } else {
                        if (
                            self.sortedMilestone.length == self.milestone.selectedIds.length
                        ) {
                            self.milestone.selectedIds = [];
                        }
                    }
                });
            },
            milestoneRowSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    if (self.sortedMilestone.length == self.milestone.selectedIds.length) {
                        self.milestone.all = true;
                    } else {
                        self.milestone.all = false;
                    }
                });
            },
            milestoneColumnSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    console.log(self.milestone.selectedColumns);
                });
            },
            milestoneLoad(page) {
                NProgress.start();
                let url = `/api/milestones-datatable/${this.$route.params.id}`;
                let params = `?page=${page}&per_page=${
                    this.milestone.pagination.per_page
                    }&search=${this.milestone.search.global}`;
                this.$http
                    .get(url + params, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(({ data }) => {
                        console.log("milestones", data);
                        this.milestones = data.data;
                        NProgress.done();
                        this.milestoneMakePagination(data.meta);
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
            milestonePageChange(page) {
                if (page >= 1 && page <= this.milestone.pagination.last_page)
                    this.milestoneLoad(page);
            },
            milestonePerPageChanged(count) {
                this.milestone.pagination.per_page = count;
                this.milestoneLoad(1);
            },
            milestoneClearSearch() {
                if (this.milestone.search.global.length) {
                    this.milestone.search.global = "";
                    this.milestoneLoad(1);
                }
                this.milestone.search.description = "";
                this.milestone.search.date = "";
                this.milestone.search.cup_count = "";
                this.milestone.search.cup_count_per_day = "";
                this.milestone.search.comment = "";
            },
            milestoneOnSelectPageCount(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption, id);
                    this.milestone.pagination.per_page = selectedOption;
                    this.milestoneLoad(1);
                }
            },
            milestoneCheckColumnVisibility(column) {
                return this.milestone.selectedColumns.indexOf(column) !== -1
                    ? true
                    : false;
            },
            //end of milestone datatable
            calculateCupCount(
                previousDate,
                previousCupCount,
                currentDate,
                currentCupCount
            ) {
                var previousDate = moment(previousDate, "YYYY-MM-DD");
                var currentDate = moment(currentDate, "YYYY-MM-DD");

                console.log(
                    `Weekdays ${previousDate} - ${currentDate}`,
                    this.calcBusinessDays(previousDate, currentDate)
                );

                //Difference in number of days
                let dayDifference = moment
                    .duration(currentDate.diff(previousDate))
                    .asDays();
                let cupDifference = currentCupCount - previousCupCount;
                return cupDifference / dayDifference;
            },
            fetchAssetsBySearch() {
                this.isLoading = true;
                this.$http
                    .get(`/api/assets-by-search/${this.$refs.assetQuery.search}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.assets = response.data.data;
                        this.isLoading = false;
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response);
                            this.isLoading = false;
                            if (error.response.status === 401) {
                                this.$router.push("/login");
                            }
                        }
                    });
            },
            fetchCompanyBySearch() {
                this.isLoading = true;
                this.$http
                    .get(`/api/company-by-search/${this.$refs.companyQuery.search}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.customers = response.data.data;
                        this.isLoading = false;
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response);
                            this.isLoading = false;
                            if (error.response.status === 401) {
                                this.$router.push("/login");
                            }
                        }
                    });
            },
            fillAssetByJob(job) {
                this.selectedAsset = {
                    id: job.asset.id,
                    custom: `${job.asset.name} ${job.asset.serial} ${job.asset.bmb}`
                };
                this.selectedAssetSerial = {
                    id: job.asset.id,
                    asset_serial: `${job.asset.serial}`
                };
            },
            fetchByAsset() {
                NProgress.start();
                this.$http
                    .get(`/api/asset/${this.job.asset_id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        let asset = response.data.data;

                        this.selectedAsset = {
                            id: asset.id,
                            custom: `${asset.name} ${asset.serial} ${asset.bmb}`
                        };
                        this.job.asset_id = asset.id;

                        this.selectedCustomer = asset.company;
                        this.job.company_id = asset.company.id;

                        this.selectedLocation = {
                            id: asset.location.id,
                            full_address: `${asset.location.description} - ${
                                asset.location.address
                                } - ${asset.location.agent_id}`
                        };
                        this.job.location_id = asset.location.id;

                        NProgress.done();
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
            // assetUtilization Datatable
            assetUtilizationErase() {
                self = this;
                self
                    .$swal({
                        title: "Do You Really Want To Delete？",
                        text: "You Can Not Revive This Information!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "OK",
                        cancelButtonText: "Cancel"
                    })
                    .then(result => {
                        if (result.value) {
                            this.$http
                                .delete(
                                    `/api/assetUtilization/${this.assetUtilization.selectedIds.join(
                                        ","
                                    )}`,
                                    {
                                        headers: {
                                            Authorization: `Bearer ${this.$store.state.token}`
                                        }
                                    }
                                )
                                .then(response => {
                                    this.$swal(
                                        "Deleted!",
                                        "Information Is Deleted From Database",
                                        "success"
                                    );
                                    this.assetUtilizationLoad(1);
                                    this.assetUtilization.selectedIds = [];
                                })
                                .catch(error => {
                                    if (error.response) {
                                        console.log(error.response);
                                        if (error.response.status === 401) {
                                            this.$router.push("/login");
                                        }
                                    }
                                });
                        } else {
                            this.$swal("Your Information Is Safe", "", "error");
                        }
                    });
            },
            assetUtilizationSortByColumn: function (s) {
                if (s === this.assetUtilization.currateSortColumn) {
                    this.assetUtilization.currateSortDirection =
                        this.assetUtilization.currateSortDirection === "asc" ? "desc" : "asc";
                }
                this.assetUtilization.currateSortColumn = s;
            },
            assetUtilizationSetSelectedColumnSortIcon(type) {
                if (
                    this.assetUtilization.currateSortDirection == "asc" &&
                    this.assetUtilization.currateSortColumn == type
                )
                    return "fa fa-arrow-down";
                else if (
                    this.assetUtilization.currateSortDirection == "desc" &&
                    this.assetUtilization.currateSortColumn == type
                )
                    return "fa fa-arrow-down open";
                else return "fa fa-arrow-down open fa-disabled";
            },
            // Paginating the table data
            assetUtilizationMakePagination(meta) {
                let pagination = {
                    per_page: this.assetUtilization.pagination.per_page,
                    currate_page: meta.currate_page,
                    last_page: meta.last_page,
                    total: meta.total,
                    from: meta.from,
                    to: meta.to,
                    perPageItems: this.assetUtilization.pagination.perPageItems
                };
                this.assetUtilization.pagination = pagination;
            },
            assetUtilizationAllassetUtilizationRowSelect() {
                var self = this;
                Vue.nextTick(function () {
                    self.assetUtilization.selectedIds = [];

                    if (self.assetUtilization.all) {
                        self.sortedAssetUtilization.forEach(assetUtilization => {
                            self.assetUtilization.selectedIds.push(
                                assetUtilization.id.toString()
                            );
                        });
                    } else {
                        if (
                            self.sortedAssetUtilization.length ==
                            self.assetUtilization.selectedIds.length
                        ) {
                            self.assetUtilization.selectedIds = [];
                        }
                    }
                });
            },
            assetUtilizationRowSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    if (
                        self.sortedAssetUtilization.length ==
                        self.assetUtilization.selectedIds.length
                    ) {
                        self.assetUtilization.all = true;
                    } else {
                        self.assetUtilization.all = false;
                    }
                });
            },
            assetUtilizationColumnSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    console.log(self.assetUtilization.selectedColumns);
                });
            },
            assetUtilizationLoad(page) {
                NProgress.start();
                let url = `/api/assetUtilizations-datatable/${this.job.asset_id}`;
                let params = `?page=${page}&per_page=${
                    this.assetUtilization.pagination.per_page
                    }&search=${this.assetUtilization.search.global}`;

                this.$http
                    .get(url + params, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(({ data }) => {
                        console.log("assetUtilizations", data);
                        this.assetUtilizations = data.data;
                        NProgress.done();
                        this.assetUtilizationMakePagination(data.meta);
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
            assetUtilizationPageChange(page) {
                if (page >= 1 && page <= this.assetUtilization.pagination.last_page)
                    this.assetUtilizationLoad(page);
            },
            assetUtilizationPerPageChanged(count) {
                this.assetUtilization.pagination.per_page = count;
                this.assetUtilizationLoad(1);
            },
            assetUtilizationClearSearch() {
                if (this.assetUtilization.search.global.length) {
                    this.assetUtilization.search.global = "";
                    this.assetUtilizationLoad(1);
                }

                this.assetUtilization.search.full_date = ""
                this.assetUtilization.search.utilization_type = ""
                this.assetUtilization.search.value = ""
                this.assetUtilization.search.utilization_unit = ""
                this.assetUtilization.search.rate = ""
                this.assetUtilization.search.rate_unit = ""
                this.assetUtilization.search.clear_calculation_description = ""
                this.assetUtilization.search.parts_changed = ""
            },
            assetUtilizationOnSelectPageCount(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption, id);
                    this.assetUtilization.pagination.per_page = selectedOption;
                    this.assetUtilizationLoad(1);
                }
            },
            assetUtilizationCheckColumnVisibility(column) {
                return this.assetUtilization.selectedColumns.indexOf(column) !== -1
                    ? true
                    : false;
            },
            calcBusinessDays(d1, d2) {
                d1 = moment(d1, "YYYY-MM-DD");
                d2 = moment(d2, "YYYY-MM-DD");
                // Calc all days used including last day ( the +1 )
                const days = d2.diff(d1, "days") + 1;

                console.log("Days:", days);

                // how many full weekends occured in this time span
                const weekends = Math.floor(days / 7);

                console.log("Full Weekends:", weekends);

                // Subtract all the weekend days
                let businessDays = days - weekends * 2;

                // Special case for weeks less than 7
                if (weekends === 0) {
                    const cur = d1.clone();
                    for (let i = 0; i < days; i++) {
                        if (cur.day() === 0 || cur.day() === 6) {
                            businessDays--;
                        }
                        cur.add(1, "days");
                    }
                } else {
                    // If the last day is a saturday we need to account for it
                    if (d2.day() === 6) {
                        console.log("Extra weekend day (Saturday)");
                        businessDays--;
                    }
                    // If the first day is a sunday we need to account for it
                    if (d1.day() === 0) {
                        console.log("Extra weekend day (Sunday)");
                        businessDays--;
                    }
                }

                console.log("Business days:", businessDays);
                return businessDays;
            },
            utilizationCalculation(sortedAssetUtilization, assetUtilization) {
                let response = null
                switch (assetUtilization.utilization_type_id) {
                    case 1:
                        if(assetUtilization.clear_calculation){
                            response = 0
                            break;
                        }

                        let cupUtilizations = sortedAssetUtilization.filter(item => {
                            return (item.utilization_type_id == 1 && moment(moment(item.date, "YYYY-MM-DD")).isBefore(moment(assetUtilization.date, "YYYY-MM-DD")) )
                        })

                        if (cupUtilizations.length) {
                            let previousAssetUtilization = cupUtilizations.reduce(function (a, b) {
                                return a.date > b.date ? a : b
                            })
                            response = (assetUtilization.value - previousAssetUtilization.value) / this.calcBusinessDays(previousAssetUtilization.date, assetUtilization.date)
                        }
                        break

                    case 3:
                        let mileageUtilizations = sortedAssetUtilization.filter(item => {
                            return (item.utilization_type_id == 3 && moment(moment(item.date, "YYYY-MM-DD")).isBefore(moment(assetUtilization.date, "YYYY-MM-DD")))
                        })

                        if (mileageUtilizations.length) {
                            let previousAssetUtilization = mileageUtilizations.reduce(function (a, b) {
                                return a.date > b.date ? a : b
                            })
                            response = (assetUtilization.value - previousAssetUtilization.value) / 365
                        }
                        break
                }

                return response
            },
            goToPreviousPage() {
                if (this.$route.query.asset) {
                    this.$router.push(this.$router.push(`/asset/${this.$route.query.asset}`))
                }
                else {
                    window.history.back();
                }
            }
        },
        computed: {
            //Spare Jobs
            filteredSpareJob: function () {
                var self = this;
                return this.spareJobs
                    .filter(function (spareJob) {
                        return spareJob.spare_name
                            ? spareJob.spare_name
                                .toLowerCase()
                                .indexOf(self.spareJob.search.spare_name.toLowerCase()) >= 0
                            : true;
                    })
                    .filter(function (spareJob) {
                        return spareJob.allocated
                            ? spareJob.allocated
                                .toLowerCase()
                                .indexOf(self.spareJob.search.allocated.toLowerCase()) >= 0
                            : true;
                    })
                    .filter(function (spareJob) {
                        return spareJob.used
                            ? spareJob.used
                                .toLowerCase()
                                .indexOf(self.spareJob.search.used.toLowerCase()) >= 0
                            : true;
                    })
                    .filter(function (spareJob) {
                        return spareJob.comment
                            ? spareJob.comment
                                .toLowerCase()
                                .indexOf(self.spareJob.search.comment.toLowerCase()) >= 0
                            : true;
                    });
            },
            sortedSpareJob: function () {
                return this.filteredSpareJob.sort((a, b) => {
                    let modifier = 1;
                    if (this.spareJob.currentSortDirection === "desc") modifier = -1;
                    let temp = `.${this.spareJob.currentSortColumn}`;
                    if (eval(`a${temp}`) < eval(`b${temp}`)) return -1 * modifier;
                    if (eval(`a${temp}`) > eval(`b${temp}`)) return 1 * modifier;
                    return 0;
                });
            },
            spareJobPages() {
                let start = _.max([this.spareJob.pagination.current_page - 2, 1]);
                let end = _.min([start + 5, this.spareJob.pagination.last_page + 1]);
                start = _.max([end - 5, 1]);
                return _.range(start, end);
            },
            filteredGallery: function () {
                var self = this;
                return this.galleries
                    .filter(function (gallery) {
                        return gallery.description
                            ? gallery.description
                                .toLowerCase()
                                .indexOf(self.gallery.search.description.toLowerCase()) >= 0
                            : true;
                    })
                    .filter(function (gallery) {
                        return gallery.image
                            ? gallery.image
                                .toLowerCase()
                                .indexOf(self.gallery.search.image.toLowerCase()) >= 0
                            : true;
                    });
            },
            sortedGallery: function () {
                return this.filteredGallery.sort((a, b) => {
                    let modifier = 1;
                    if (this.gallery.currentSortDirection === "desc") modifier = -1;
                    let temp = `.${this.gallery.currentSortColumn}`;
                    if (eval(`a${temp}`) < eval(`b${temp}`)) return -1 * modifier;
                    if (eval(`a${temp}`) > eval(`b${temp}`)) return 1 * modifier;
                    return 0;
                });
            },
            GalleryPages() {
                let start = _.max([this.gallery.pagination.current_page - 2, 1]);
                let end = _.min([start + 5, this.gallery.pagination.last_page + 1]);
                start = _.max([end - 5, 1]);
                return _.range(start, end);
            },
            filteredMilestone: function () {
                var self = this;
                return this.milestones
                    .filter(function (milestone) {
                        return milestone.description
                            ? milestone.description
                                .toLowerCase()
                                .indexOf(self.milestone.search.description.toLowerCase()) >= 0
                            : true;
                    })
                    .filter(function (milestone) {
                        return milestone.date
                            ? milestone.date
                                .toLowerCase()
                                .indexOf(self.milestone.search.date.toLowerCase()) >= 0
                            : true;
                    })
                    .filter(function (milestone) {
                        return milestone.cup_count
                            ? milestone.cup_count
                                .toString()
                                .indexOf(self.milestone.search.cup_count.toString()) >= 0
                            : true;
                    })
                    .filter(function (milestone) {
                        return milestone.cup_count_per_day
                            ? milestone.cup_count_per_day
                                .toString()
                                .indexOf(self.milestone.search.cup_count_per_day.toString()) >=
                            0
                            : true;
                    })
                    .filter(function (milestone) {
                        return milestone.comment
                            ? milestone.comment
                                .toLowerCase()
                                .indexOf(self.milestone.search.comment.toLowerCase()) >= 0
                            : true;
                    });
            },
            sortedMilestone: function () {
                return this.filteredMilestone.sort((a, b) => {
                    let modifier = 1;
                    if (this.milestone.currentSortDirection === "desc") modifier = -1;
                    let temp = `.${this.milestone.currentSortColumn}`;
                    if (eval(`a${temp}`) < eval(`b${temp}`)) return -1 * modifier;
                    if (eval(`a${temp}`) > eval(`b${temp}`)) return 1 * modifier;
                    return 0;
                });
            },
            MilestonePages() {
                let start = _.max([this.milestone.pagination.current_page - 2, 1]);
                let end = _.min([start + 5, this.milestone.pagination.last_page + 1]);
                start = _.max([end - 5, 1]);
                return _.range(start, end);
            },
            isFromAsset() {
                return this.job.asset_id ? true : false;
            },
            filteredAssetUtilization: function () {
                var self = this;
                return this.assetUtilizations
                    .filter(function (assetUtilization) {
                        return assetUtilization.full_date
                            ? assetUtilization.full_date
                                .toString()
                                .indexOf(self.assetUtilization.search.full_date.toString()) >= 0
                            : true;
                    })
                    .filter(function (assetUtilization) {
                        return assetUtilization.utilization_type
                            ? assetUtilization.utilization_type
                                .toLowerCase()
                                .indexOf(
                                    self.assetUtilization.search.utilization_type.toLowerCase()
                                ) >= 0
                            : true;
                    })
                    .filter(function (assetUtilization) {
                        return assetUtilization.value
                            ? assetUtilization.value
                                .toString()
                                .indexOf(self.assetUtilization.search.value.toString()) >= 0
                            : true;
                    })
                    .filter(function (assetUtilization) {
                        return assetUtilization.utilization_unit
                            ? assetUtilization.utilization_unit
                                .toLowerCase()
                                .indexOf(
                                    self.assetUtilization.search.utilization_unit.toLowerCase()
                                ) >= 0
                            : true;
                    })
                    .filter(function (assetUtilization) {
                        return assetUtilization.clear_calculation_description
                            ? assetUtilization.clear_calculation_description
                                .toLowerCase()
                                .indexOf(
                                    self.assetUtilization.search.clear_calculation_description.toLowerCase()
                                ) >= 0
                            : true;
                    })
                    .filter(function (assetUtilization) {
                        return assetUtilization.parts_changed
                            ? assetUtilization.parts_changed
                                .toLowerCase()
                                .indexOf(
                                    self.assetUtilization.search.parts_changed.toLowerCase()
                                ) >= 0
                            : true;
                    })
                    .filter(function (assetUtilization) {
                        return assetUtilization.rate
                            ? assetUtilization.rate
                                .toString()
                                .indexOf(self.assetUtilization.search.rate.toString()) >= 0
                            : true;
                    })
                    .filter(function (assetUtilization) {
                        return assetUtilization.rate_unit
                            ? assetUtilization.rate_unit
                                .toString()
                                .indexOf(self.assetUtilization.search.rate_unit.toString()) >= 0
                            : true;
                    });
            },
            sortedAssetUtilization: function () {
                return this.filteredAssetUtilization.sort((a, b) => {
                    let modifier = 1;
                    if (this.assetUtilization.currateSortDirection === "desc")
                        modifier = -1;

                    let temp = `.${this.assetUtilization.currateSortColumn}`;
                    if (eval(`a${temp}`) < eval(`b${temp}`)) return -1 * modifier;
                    if (eval(`a${temp}`) > eval(`b${temp}`)) return 1 * modifier;
                    return 0;
                });
            },
            AssetUtilizationPages() {
                let start = _.max([this.assetUtilization.pagination.currate_page - 2, 1]);
                let end = _.min([
                    start + 5,
                    this.assetUtilization.pagination.last_page + 1
                ]);
                start = _.max([end - 5, 1]);
                return _.range(start, end);
            }
        },
        watch: {
            "job.machine_option": function (machineOption, oldVal) {
                if (!machineOption) return;
                let ref = this;
                let count = 0;
                ref.machineOptions.forEach(element => {
                    if (element.option_name === "Drink") {
                        machineOption.forEach(x => {
                            if (element.id == parseInt(x)) {
                                count++;
                            }
                            if (count > ref.selectedJobAssetType.drink_no) {
                                ref.job.machine_option = ref.job.machine_option.filter(
                                    value => value != machineOption[machineOption.length - 1]
                                );
                                ref.$swal(
                                    `Can not choose Drink more than  ${
                                    ref.selectedJobAssetType.drink_no
                                    }`,
                                    "",
                                    "error"
                                );
                                return;
                            }
                        });
                    }
                });
            }
        }
    };
</script>
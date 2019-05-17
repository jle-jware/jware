<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm()">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                            <router-link :to="{ name: 'Asset List'}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button v-if="$store.state.user.contact_type != 'Technician' && $store.state.user.contact_type != 'Manager'" type="button" @click.prevent="submit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="">Asset Status *</label>
                                    <multiselect :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit" v-model="selectedAssetStat" :class="{ 'is-danger': !asset.asset_stat_id }"
                                        :options="assetStats" @select="onSelectAssetStat" track-by="id" label="type" title="Select Asset Status"
                                        placeholder="Select Asset Status" selectLabel="" deselectLabel="" selectedLabel="Remove"
                                        v-validate="'required'">
                                    </multiselect>
                                </div>
                                <div class="form-group col-sm-4">
                                    <b-form-fieldset label="Asset Name *">
                                        <b-form-input :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit" type="text" :class="errors.has('name') ? 'form-control is-invalid':'form-control'"
                                        title="Please Enter Asset Name. Ex. Alegria 8-60"    v-validate="'required'" name="name" v-model="asset.name" placeholder="Please enter Asset Name"></b-form-input>
                                        <span class="is-danger">{{ errors.first('name') }}</span>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="">Asset Type *</label>
                                    <multiselect :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit" v-model="selectedAssetType" :class="{ 'is-danger': !asset.asset_model_id }"
                                        :options="assetModels" @select="onSelectAssetType" track-by="id" label="name" title="Select Asset Type"
                                        placeholder="Select Asset Type" selectLabel="" deselectLabel="" selectedLabel="Remove"
                                        v-validate="'required'">
                                    </multiselect>
                                    <a @click.prevent="addAssetType()" href="#">Add New Asset Type</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Asset Serial *">
                                        <b-form-input :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit" type="text" :class="errors.has('serial') ? 'form-control is-invalid':'form-control'"
                                        title="Please Enter Asset Serial Number"    v-validate="'required'" name="serial" v-model="asset.serial" placeholder="Please enter Asset Serial"></b-form-input>
                                        <span class="is-danger">{{ errors.first('serial') }}</span>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Asset BMB *">
                                        <b-form-input :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit" type="text" :class="errors.has('bmb') ? 'form-control is-invalid':'form-control'"
                                            v-validate="'required'" name="bmb" v-model="asset.bmb" title="Select BMB" placeholder="Please enter Asset BMB"></b-form-input>
                                        <span class="is-danger">{{ errors.first('bmb') }}</span>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-4">
                                    <label for="">Customer List *</label>
                                    <multiselect :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit" v-model="selectedCustomer" :options="customers" @select="onSelectCustomer"
                                        track-by="id" label="name" placeholder="Select Customer" selectLabel="" title="Select Customer"
                                        deselectLabel="" selectedLabel="Remove">
                                    </multiselect>
                                    <a @click.prevent="addNewCompany()" href="#">Add New Customer</a>
                                </div>
                                <div class="form-group col-sm-4">
                                    <b-form-fieldset label="Date">
                                        <datetime
                                            :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit"
                                            v-model="asset.date" type="date"
                                            input-format="YYYY-MM-DD HH:mm" wrapper-class="my-wrapper-class"
                                            input-class="form-control" placeholder="Select date"
                                            moment-locale="es" :i18n="{ok:'De acuerdo', cancel:'Cancelar'}"
                                            :disabled-dates="['2017-09-07', ['2017-09-25', '2017-10-05']]"
                                            max-date="2017-12-10" min-date="2017-07-10" zone="Pacific/Auckland" value-zone="Pacific/Auckland" monday-first
                                            auto-continue auto-close required></datetime>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-4">
                                    <br><br>
                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" v-model="asset.is_charged" value="1" name="is_charged"
                                            class="custom-control-input">
                                        <span class="custom-control-indicator"></span>
                                        <span class="custom-control-description">Charge Rent</span>
                                    </label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="">Location List *</label>
                                    <multiselect :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit" v-model="selectedLocation" :options="locations" @select="onSelectLocation"
                                        track-by="id" label="full_address" placeholder="Select Location" selectLabel="" title="Select Location"
                                        deselectLabel="" selectedLabel="Remove">
                                    </multiselect>
                                    <a @click.prevent="addNewLocation()" href="#">Add New Location</a>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit" label="Asset Specific Location">
                                        <b-form-input type="text" v-model="asset.specific_location" title="Enter Specific Location" placeholder="Please enter specific location"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            <div class="row">
                                <div :disabled ="$store.state.user.contact_type == 'Technician'|| $store.state.user.contact_type == 'Manager' && edit" class="form-group col-sm-12">
                                    <b-form-textarea id="textarea1" v-model="asset.description" placeholder="Description" title="Enter Description"
                                        :rows="3" :max-rows="6">
                                    </b-form-textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
         <div :hidden="!edit"  v-if="$store.state.user.contact_type != 'Technician' && $store.state.user.contact_type != 'Manager'" class="col-sm-12">
            <div role="tablist" class="company-collapse">
                <b-card no-body class="mb-1">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-btn block href="#" v-b-toggle.accordion4 variant="success ">Milestone List</b-btn>
                    </b-card-header>
                    <b-collapse id="accordion4" accordion="my-accordion" role="tabpane4">
                <b-card-body>
                <div class="row">
                    <div class="col-lg-4"></div>
                    <div class="col-lg-8">
                        <div>Short Listed {{sortedMilestone.length}} Total {{milestone.pagination.total}} items, Per page {{milestone.pagination.per_page}}, Showing {{milestone.pagination.from}} 〜 {{milestone.pagination.to}}</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                        <input type="text" class="form-control" v-model="milestone.search.global" v-on:keyup.enter="milestoneLoad(1)" v-on:keydown="milestoneLoad(1)"
                            placeholder="Search For All Records" title="Type and press enter to search">
                    </div>
                    <div class="col-md-2 col-sm-12">
                       <button class="btn btn-link" @click.prevent="milestoneClearSearch" title="Clear Search">
                            <i class="fa fa-window-close icon" title="Clear All Search"></i>
                        </button>
                    </div>
                    <div class="col-md-7 col-sm-12 text-right header-menu-nav">
                        <slot name="navigations"></slot>
                        <router-link v-if="$store.state.user.contact_type != 'Technician'" :to="{ path: `/milestone/create`, query: { asset:$route.params.id}}" class="btn btn-link">
                            <i class="fa fa-plus-square icon" title="Add New Milestone"></i>
                        </router-link>
                        <button :disabled="!milestone.selectedIds.length" class="btn btn-link" @click.prevent="milestoneErase" title="Delete Item"><i class="fa fa-trash icon"></i></button>
                        <b-dropdown variant="link" no-caret>
                            <template slot="button-content">
                                <i class="fas fa-columns icon" title="Customize Columns"></i>
                            </template>
                            <div class="selected-columns">
                                <li v-for="(column, index) in milestone.columns" :key="index">
                                    <b-form-checkbox v-model="milestone.selectedColumns" @change="milestoneColumnSelect"
                                        :value="column.name">{{column.label}}</b-form-checkbox>
                                </li>
                            </div>
                        </b-dropdown>
                        <download-excel :data="sortedMilestone" class="btn">
                            <i class="fa fa-download icon" title="Export Milestones"></i>
                        </download-excel>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-striped table-responsive">
                            <thead>
                                <!-- Column Sort -->
                                <tr>
                                    <th v-if="milestoneCheckColumnVisibility('all')">
                                        All
                                    </th>
                                        <th v-if="milestoneCheckColumnVisibility('milestone_type_name')" @click="milestoneSortByColumn('milestone_type_name')"
                                            class="text-center sortable">
                                            Milestone Type
                                            <i :class="milestoneSetSelectedColumnSortIcon('milestone_type_name')"></i>
                                        </th>
                                        <th v-if="milestoneCheckColumnVisibility('description')" @click="milestoneSortByColumn('description')"
                                            class="text-center sortable">
                                            Description
                                            <i :class="milestoneSetSelectedColumnSortIcon('description')"></i>
                                        </th>
                                        <th v-if="milestoneCheckColumnVisibility('date')" @click="milestoneSortByColumn('date')"
                                            class="text-center sortable">
                                            Date
                                            <i :class="milestoneSetSelectedColumnSortIcon('date')"></i>
                                        </th>
                                    </tr>
                                    <!-- Column Search -->
                                    <tr>
                                        <th v-if="milestoneCheckColumnVisibility('all')">
                                            <b-form-checkbox name="checkAll" title="Check all" @change="milestoneAllmilestoneRowSelect"
                                                v-model="milestone.all"></b-form-checkbox>
                                        </th>
                                        <th v-if="milestoneCheckColumnVisibility('milestone_type_name')">
                                            <input type="text" placeholder="Search" class="form-control" v-model="milestone.search.milestone_type_name" />
                                        </th>
                                        <th v-if="milestoneCheckColumnVisibility('description')">
                                            <input type="text" placeholder="Search" class="form-control" v-model="milestone.search.description" />
                                        </th>
                                        <th v-if="milestoneCheckColumnVisibility('date')">
                                            <input type="text" placeholder="Search" class="form-control" v-model="milestone.search.date" />
                                        </th>
                                    </tr>
                                </thead>
                                <!-- Table Body -->
                                <tbody>
                                    <tr v-for="(mils, index) in sortedMilestone" :key="index">
                                        <td v-if="milestoneCheckColumnVisibility('all')">
                                            <b-form-checkbox v-model="milestone.selectedIds" @change="milestoneRowSelect"
                                                :value="mils.id">
                                            </b-form-checkbox>
                                        </td>
                                        <td v-if="milestoneCheckColumnVisibility('milestone_type_name')">
                                            <router-link :to="{ path: `/milestone/${mils.id}`, query: { asset:$route.params.id}}">
                                                    {{ mils.milestone_type_name }}
                                                </router-link>
                                        </td>
                                        <td v-if="milestoneCheckColumnVisibility('description')">
                                            {{ mils.description }}
                                        </td>
                                        <td v-if="milestoneCheckColumnVisibility('date')">
                                            {{ mils.date }}
                                        </td>
                                    </tr>
                                </tbody>
                                <!-- Table Body End -->
                            </table>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-1 justify-content-center align-self-center">
                            <multiselect v-model="milestone.pagination.per_page" @select="milestoneOnSelectPageCount"
                                :options="milestone.pagination.perPageItems" :searchable="false" :show-labels="false"
                                placeholder="Items / Page"></multiselect>
                        </div>

                        <div class="col-lg-3 justify-content-center align-self-center text-center">

                        </div>

                        <div class="col-lg-4 justify-content-center align-self-center text-center">
                            <div>Total {{milestone.pagination.total}} items, Per page
                                {{milestone.pagination.per_page}},
                                Showing {{milestone.pagination.from}} 〜 {{milestone.pagination.to}}</div>
                        </div>

                        <div class="col-lg-4 justify-content-center align-self-center">
                            <ul class="pagination pagination-custom pull-right">
                                <li :class="{disabled: milestone.pagination.current_page <= 1}"><a href="#" @click.prevent="milestonePageChange(1)">&laquo;</a></li>
                                <li :class="{disabled: milestone.pagination.current_page <= 1}"><a href="#" @click.prevent="milestonePageChange(milestone.pagination.current_page - 1)">&lt;</a></li>
                                <li v-for="page in MilestonePages" :key="page" :class="{active: page === milestone.pagination.current_page}">
                                    <a href="#" @click.prevent="milestonePageChange(page)">{{page}}</a>
                                </li>
                                <li :class="{disabled: milestone.pagination.current_page >= milestone.pagination.last_page}"><a
                                        href="#" @click.prevent="milestonePageChange(milestone.pagination.current_page + 1)">&gt;</a></li>
                                <li :class="{disabled: milestone.pagination.current_page >= milestone.pagination.last_page}"><a
                                        href="#" @click.prevent="milestonePageChange(milestone.pagination.last_page)">&raquo;</a></li>
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
                        <b-btn block href="#" v-b-toggle.accordion3 variant="success ">Asset Utilization List</b-btn>
                    </b-card-header>
                    <b-collapse id="accordion3" accordion="my-accordion" role="tabpane3">
                        <b-card-body>
                            <div class=row>
                                <div class="col-lg-4"></div>
                                <div class="col-lg-8">
							        <div>Short Listed {{sortedAssetUtilization.length}} Total {{assetUtilization.pagination.total}} items, Per page {{assetUtilization.pagination.per_page}},
                                        Showing {{assetUtilization.pagination.from}} 〜 {{assetUtilization.pagination.to}}</div>
						        </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <input type="text" class="form-control" v-model="assetUtilization.search.global" v-on:keydown="assetUtilizationLoad(1)" v-on:keyup.enter="assetUtilizationLoad(1)" placeholder="Search For All Records" title="Type and press enter to search">
                                </div>
                                <div class="col-md-2 col-sm-12">
                                <button class="btn btn-link" @click.prevent="assetUtilizationClearSearch" title="Clear Search">
                                        <i class="fa fa-window-close icon" title="Clear All Search"></i>
                                    </button>
                                </div>
                                <div class="col-md-7 col-sm-12 text-right header-menu-nav">
                                    <slot name="navigations"></slot>

                                    <router-link v-if="$store.state.user.contact_type != 'Technician'" :to="{ path: `/asset-utilization/create`, query: { asset:$route.params.id}}" class="btn btn-link">
                                        <i class="fa fa-plus-square icon" title="Add New Asset Utilization"></i>
                                    </router-link>
                                    <button class="btn btn-link" @click.prevent="assetUtilizationClearSearch" title="Clear Search">
                                        <i class="fas fa-eraser icon"></i>
                                    </button>

                                    <button v-if="$store.state.user.contact_type != 'Technician' && $store.state.user.contact_type != 'Manager'" :disabled="!assetUtilization.selectedIds.length" class="btn btn-link" @click.prevent="assetUtilizationErase" title="Delete Asset Utlizations">
                                        <i class="fa fa-trash icon"></i>
                                    </button>
                                    <b-dropdown variant="link" no-caret>
                                        <template slot="button-content">
                                            <i class="fas fa-columns icon" title="Customize Columns"></i>
                                        </template>
                                        <div class="selected-columns">
                                            <li v-for="(column, index) in assetUtilization.columns" :key="index">
                                                <b-form-checkbox v-model="assetUtilization.selectedColumns" @change="assetUtilizationColumnSelect" :value="column.name">{{column.label}}</b-form-checkbox>
                                            </li>
                                        </div>
                                    </b-dropdown>
                                    <download-excel :data="sortedAssetUtilization" class="btn">
                                        <i class="fa fa-download icon" title="Export Asset Utilizations"></i>
                                    </download-excel>
                                </div>
                                <div class="col-md-12">
                                    <table class="table table-striped table-responsive">
                                        <thead>
                                            <!-- Column Sort -->
                                            <tr>
                                                <th v-if="assetUtilizationCheckColumnVisibility('all')">
                                                    All
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('full_date')" @click="assetUtilizationSortByColumn('date')" class="text-center sortable">
                                                    Date
                                                    <i :class="assetUtilizationSetSelectedColumnSortIcon('full_date')"></i>
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('utilization_type')" @click="assetUtilizationSortByColumn('utilization_type')" class="text-center sortable">
                                                    Type
                                                    <i :class="assetUtilizationSetSelectedColumnSortIcon('utilization_type')"></i>
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('value')" @click="assetUtilizationSortByColumn('value')" class="text-center sortable">
                                                   Utilization Value
                                                    <i :class="assetUtilizationSetSelectedColumnSortIcon('value')"></i>
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('utilization_unit')" @click="assetUtilizationSortByColumn('utilization_unit')" class="text-center sortable">
                                                    Measure Unit
                                                    <i :class="assetUtilizationSetSelectedColumnSortIcon('utilization_unit')"></i>
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('rate')" @click="assetUtilizationSortByColumn('rate')" class="text-center sortable">
                                                    Rate
                                                    <i :class="assetUtilizationSetSelectedColumnSortIcon('rate')"></i>
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('rate_unit')" @click="assetUtilizationSortByColumn('rate_unit')" class="text-center sortable">
                                                    No of Days
                                                    <i :class="assetUtilizationSetSelectedColumnSortIcon('rate_unit')"></i>
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('clear_calculation_description')" @click="assetUtilizationSortByColumn('clear_calculation_description')" class="text-center sortable">
                                                    Mother Board Changed
                                                    <i :class="assetUtilizationSetSelectedColumnSortIcon('clear_calculation_description')"></i>
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('parts_changed')" @click="assetUtilizationSortByColumn('parts_changed')" class="text-center sortable">
                                                    Filter Changed
                                                    <i :class="assetUtilizationSetSelectedColumnSortIcon('parts_changed')"></i>
                                                </th>
                                            </tr>
                                            <!-- Column Search -->
                                            <tr>
                                                <th v-if="assetUtilizationCheckColumnVisibility('all')">
                                                    <b-form-checkbox name="checkAll" title="Check all" @change="assetUtilizationAllassetUtilizationRowSelect" v-model="assetUtilization.all"></b-form-checkbox>
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('full_date')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="assetUtilization.search.full_date" />
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('utilization_type')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="assetUtilization.search.utilization_type" />
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('value')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="assetUtilization.search.value" />
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('utilization_unit')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="assetUtilization.search.utilization_unit" />
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('rate')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="assetUtilization.search.rate" />
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('rate_unit')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="assetUtilization.search.rate_unit" />
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('clear_calculation_description')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="assetUtilization.search.clear_calculation_description" />
                                                </th>
                                                <th v-if="assetUtilizationCheckColumnVisibility('parts_changed')">
                                                    <input type="text" placeholder="Search" class="form-control" v-model="assetUtilization.search.parts_changed" />
                                                </th>
                                            </tr>
                                        </thead>
                                        <!-- Table Body -->
                                        <tbody>
                                            <tr v-for="(assetUtilization, index) in sortedAssetUtilization" :key="index">
                                                <td v-if="assetUtilizationCheckColumnVisibility('all')">
                                                    <b-form-checkbox v-model="assetUtilization.selectedIds" @change="assetUtilizationRowSelect" :value="assetUtilization.id">
                                                    </b-form-checkbox>
                                                </td>
                                                <td v-if="assetUtilizationCheckColumnVisibility('full_date')">
                                                    <router-link :to="{ path: `/assetUtilization/${assetUtilization.id}`, query: { asset:$route.params.id}}">
                                                        {{ assetUtilization.full_date }}
                                                    </router-link>
                                                </td>
                                                 <td v-if="assetUtilizationCheckColumnVisibility('utilization_type')">
                                                    {{ assetUtilization.utilization_type }}
                                                </td>
                                                <td v-if="assetUtilizationCheckColumnVisibility('value')">
                                                    {{ assetUtilization.value }}
                                                </td>
                                                <td v-if="assetUtilizationCheckColumnVisibility('utilization_unit')">
                                                    {{ assetUtilization.utilization_unit }}
                                                </td>
                                                <td class="text-sm-center is-danger"
                                                    v-if="assetUtilizationCheckColumnVisibility('rate')">
                                                    {{ Number(utilizationCalculation(sortedAssetUtilization, assetUtilization)).toFixed(2) }}
                                                </td>
                                                <td class="text-sm-center"
                                                    v-if="assetUtilizationCheckColumnVisibility('rate_unit')">
                                                    {{ sortedAssetUtilization.length == (index + 1 ) ? '' :
                                                        (sortedAssetUtilization[index + 1].utilization_type_id == assetUtilization.utilization_type_id ?
                                                        calcBusinessDays(sortedAssetUtilization[index + 1].date, assetUtilization.date) : 0) }}
                                                </td>
                                                <td v-if="assetUtilizationCheckColumnVisibility('clear_calculation_description')">
                                                    {{ assetUtilization.clear_calculation_description }}
                                                </td>
                                                <td v-if="assetUtilizationCheckColumnVisibility('parts_changed')">
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
                                    <multiselect v-model="assetUtilization.pagination.per_page" @select="assetUtilizationOnSelectPageCount" :options="assetUtilization.pagination.perPageItems"
                                        :searchable="false" :show-labels="false" placeholder="Items / Page"></multiselect>
                                </div>

                                <div class="col-lg-3 justify-content-center align-self-center text-center">

                                </div>

                                <div class="col-lg-4 justify-content-center align-self-center text-center">
                                    <div>Total {{assetUtilization.pagination.total}} items, Per page {{assetUtilization.pagination.per_page}},
                                        Showing {{assetUtilization.pagination.from}} 〜 {{assetUtilization.pagination.to}}</div>
                                </div>

                                <div class="col-lg-4 justify-content-center align-self-center">
                                    <ul class="pagination pagination-custom pull-right">
                                        <li :class="{disabled: assetUtilization.pagination.currate_page <= 1}"><a href="#" @click.prevent="assetUtilizationPageChange(1)">&laquo;</a></li>
                                        <li :class="{disabled: assetUtilization.pagination.currate_page <= 1}"><a href="#" @click.prevent="assetUtilizationPageChange(assetUtilization.pagination.currate_page - 1)">&lt;</a></li>
                                        <li v-for="page in AssetInspectionPages" :key="page" :class="{active: page === assetUtilization.pagination.currate_page}">
                                            <a href="#" @click.prevent="assetUtilizationPageChange(page)">{{page}}</a>
                                        </li>
                                        <li :class="{disabled: assetUtilization.pagination.currate_page >= assetUtilization.pagination.last_page}"><a
                                                href="#" @click.prevent="assetUtilizationPageChange(assetUtilization.pagination.currate_page + 1)">&gt;</a></li>
                                        <li :class="{disabled: assetUtilization.pagination.currate_page >= assetUtilization.pagination.last_page}"><a
                                                href="#" @click.prevent="assetUtilizationPageChange(assetUtilization.pagination.last_page)">&raquo;</a></li>
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
                        <b-btn block href="#" v-b-toggle.accordion2 variant="success">Job List</b-btn>
                    </b-card-header>
                    <b-collapse id="accordion2" accordion="my-accordion" role="tabpane2">
                        <b-card-body>
                            <div class="row">
                                <div class="col-lg-4"></div>
                                <div class="col-lg-8">
                                    <div>Short Listed {{sortedJob.length}} Total {{job.pagination.total}} items, Per page {{job.pagination.per_page}},
                                        Showing {{job.pagination.from}} 〜 {{job.pagination.to}}</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <input type="text" class="form-control" v-model="job.search.global"
                                        v-on:keyup.enter="jobLoad(1)" v-on:keydown="jobLoad(1)" placeholder="Search For All Records" title="Type and press enter to search">
                                </div>
                                <div class="col-md-2 col-sm-12">
                                    <button class="btn btn-link" @click.prevent="jobClearSearch" title="Clear Search">
                                        <i class="fa fa-window-close icon" title="Clear All Search"></i>
                                    </button>
                                </div>
                                <div class="col-md-7 col-sm-12 text-right header-menu-nav">
                                    <slot name="navigations"></slot>

                                    <router-link v-if="$store.state.user.contact_type != 'Technician' && $store.state.user.contact_type != 'Manager'" :to="{ path: `/job/${$route.params.id}/create`, query: { asset:$route.params.id}}"
                                        class="btn btn-link">
                                        <i class="fa fa-plus-square icon" title="Add New Job"></i>
                                    </router-link>
                                    <button class="btn btn-link" @click.prevent="jobClearSearch" title="Clear Search">
                                        <i class="fas fa-eraser icon"></i>
                                    </button>

                                    <button v-if="$store.state.user.contact_type != 'Technician' && $store.state.user.contact_type != 'Manager'" :disabled="!job.selectedIds.length" class="btn btn-link" @click.prevent="jobErase"
                                        title="Delete Item">
                                        <i class="fa fa-trash icon"></i>
                                    </button>
                                    <b-dropdown variant="link" no-caret>
                                        <template slot="button-content">
                                            <i class="fas fa-columns icon" title="Customize Columns"></i>
                                        </template>
                                        <div class="selected-columns">
                                            <li v-for="(column, index) in job.columns" :key="index">
                                                <b-form-checkbox v-model="job.selectedColumns" @change="jobColumnSelect"
                                                    :value="column.name">{{column.label}}</b-form-checkbox>
                                            </li>
                                        </div>
                                    </b-dropdown>
                                    <download-excel :data="sortedJob" class="btn">
                                        <i class="fa fa-download icon" title="Export Jobs"></i>
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
                                                <th v-if="jobCheckColumnVisibility('job_number')" @click="jobSortByColumn('job_number')"
                                                    class="text-center sortable">
                                                    Job Number
                                                    <i :class="jobSetSelectedColumnSortIcon('job_number')"></i>
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('job_type_name')" @click="jobSortByColumn('job_type_name')"
                                                    class="text-center sortable">
                                                    Job Type
                                                    <i :class="jobSetSelectedColumnSortIcon('job_type_name')"></i>
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('customer_name')" @click="jobSortByColumn('customer_name')"
                                                    class="text-center sortable">
                                                    Customer Name
                                                    <i :class="jobSetSelectedColumnSortIcon('customer_name')"></i>
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('location_address')" @click="jobSortByColumn('location_address')"
                                                    class="text-center sortable">
                                                   Location
                                                    <i :class="jobSetSelectedColumnSortIcon('location_address')"></i>
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('job_priority')" @click="jobSortByColumn('job_priority')"
                                                    class="text-center sortable">
                                                    Job Priority
                                                    <i :class="jobSetSelectedColumnSortIcon('job_priority')"></i>
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('job_status')" @click="jobSortByColumn('job_status')"
                                                    class="text-center sortable">
                                                    Job Status
                                                    <i :class="jobSetSelectedColumnSortIcon('job_status')"></i>
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('updated_at')" @click="jobSortByColumn('updated_at')"
                                                    class="text-center sortable">
                                                   Date
                                                    <i :class="jobSetSelectedColumnSortIcon('updated_at')"></i>
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('tech_name')" @click="jobSortByColumn('tech_name')"
                                                    class="text-center sortable">
                                                    Technician Name
                                                    <i :class="jobSetSelectedColumnSortIcon('tech_name')"></i>
                                                </th>
                                                 <th v-if="jobCheckColumnVisibility('description')" @click="jobSortByColumn('description')"
                                                    class="text-center sortable">
                                                    Job Description
                                                    <i :class="jobSetSelectedColumnSortIcon('description')"></i>
                                                </th>
                                            </tr>
                                            <!-- Column Search -->
                                            <tr>
                                                <th v-if="jobCheckColumnVisibility('all')">
                                                    <b-form-checkbox name="checkAll" title="Check all" @change="jobAlljobRowSelect"
                                                        v-model="job.all"></b-form-checkbox>
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('job_number')">
                                                    <input type="text" placeholder="Search" class="form-control"
                                                        v-model="job.search.job_number" />
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('job_type_name')">
                                                    <input type="text" placeholder="Search" class="form-control"
                                                        v-model="job.search.job_type_name" />
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('customer_name')">
                                                    <input type="text" placeholder="Search" class="form-control"
                                                        v-model="job.search.customer_name" />
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('location_address')">
                                                    <input type="text" placeholder="Search" class="form-control"
                                                        v-model="job.search.location_address" />
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('job_priority')">
                                                    <input type="text" placeholder="Search" class="form-control"
                                                        v-model="job.search.job_priority" />
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('job_status')">
                                                    <input type="text" placeholder="Search" class="form-control"
                                                        v-model="job.search.job_status" />
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('updated_at')">
                                                    <input type="text" placeholder="Search" class="form-control"
                                                        v-model="job.search.updated_at" />
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('tech_name')">
                                                    <input type="text" placeholder="Search" class="form-control"
                                                        v-model="job.search.tech_name" />
                                                </th>
                                                <th v-if="jobCheckColumnVisibility('description')">
                                                    <input type="text" placeholder="Search" class="form-control"
                                                        v-model="job.search.description" />
                                                </th>
                                            </tr>
                                        </thead>
                                        <!-- Table Body -->
                                        <tbody>
                                            <tr v-for="(jb, index) in sortedJob" :key="index">
                                                <td v-if="jobCheckColumnVisibility('all')">
                                                    <b-form-checkbox v-model="job.selectedIds" @change="jobRowSelect"
                                                        :value="jb.id">
                                                    </b-form-checkbox>
                                                </td>
                                                <td v-if="jobCheckColumnVisibility('job_number')">
                                                    <router-link :to="{ path: `/job/${jb.id}/new`, query: { asset:$route.params.id}}">
                                                        {{ jb.job_number }}
                                                    </router-link>
                                                </td>
                                                <td v-if="jobCheckColumnVisibility('job_type_name')">
                                                    <router-link :to="{ path: `/job/${jb.id}/new`, query: { asset:$route.params.id}}">
                                                        {{ jb.job_type_name }}
                                                    </router-link>
                                                </td>
                                                <td v-if="jobCheckColumnVisibility('customer_name')">
                                                    <router-link :to="{ path: `/job/${jb.id}/new`, query: { asset:$route.params.id}}">
                                                        {{ jb.customer_name }}
                                                    </router-link>
                                                </td>
                                                <td v-if="jobCheckColumnVisibility('location_address')">
                                                    <router-link :to="{ path: `/job/${jb.id}/new`, query: { asset:$route.params.id}}">
                                                       {{ jb.location_address }}
                                                    </router-link>
                                                </td>
                                                
                                                <td v-if="jobCheckColumnVisibility('job_priority')">
                                                    {{ jb.job_priority }}
                                                </td>
                                                <td :class="getJobStatusType(jb.job_status)" v-if="jobCheckColumnVisibility('job_status')">
                                                    {{ jb.job_status ? jb.job_status:'' }}
                                                </td>
                                                <td v-if="jobCheckColumnVisibility('updated_at')">
                                                    {{ jb.updated_at }}
                                                </td>
                                                <td v-if="jobCheckColumnVisibility('tech_name')">
                                                    {{ jb.tech_name }}
                                                </td>
                                                <td v-if="jobCheckColumnVisibility('description')">
                                                     <router-link :to="{ path: `/job/${jb.id}/new`, query: { asset:$route.params.id}}" v-html="jb.description"></router-link>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <!-- Table Body End -->
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-1 justify-content-center align-self-center">
                                    <multiselect v-model="job.pagination.per_page" @select="jobOnSelectPageCount"
                                        :options="job.pagination.perPageItems" :searchable="false" :show-labels="false"
                                        placeholder="Items / Page"></multiselect>
                                </div>

                                <div class="col-lg-3 justify-content-center align-self-center text-center">

                                </div>

                                <div class="col-lg-4 justify-content-center align-self-center text-center">
                                    <div>Total {{job.pagination.total}} items, Per page {{job.pagination.per_page}},
                                        Showing {{job.pagination.from}} 〜 {{job.pagination.to}}</div>
                                </div>

                                <div class="col-lg-4 justify-content-center align-self-center">
                                    <ul class="pagination pagination-custom pull-right">
                                        <li :class="{disabled: job.pagination.current_page <= 1}"><a href="#"
                                                @click.prevent="jobPageChange(1)">&laquo;</a></li>
                                        <li :class="{disabled: job.pagination.current_page <= 1}"><a href="#"
                                                @click.prevent="jobPageChange(job.pagination.current_page - 1)">&lt;</a></li>
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
        <div  v-if="$store.state.user.contact_type != 'Technician' && $store.state.user.contact_type != 'Manager'" class="animated fadeIn">
            <div :hidden="!edit" class="col-sm-12">
                <div role="tablist" class="company-collapse">
                    <b-card no-body class="mb-1">
                        <b-card-header header-tag="header" class="p-1" role="tab">
                            <b-btn block href="#" v-b-toggle.accordion1 variant="success ">Asset Inspection List</b-btn>
                        </b-card-header>
                        <b-collapse id="accordion1" accordion="my-accordion" role="tabpane1">
                            <b-card-body>
                                <div class="row">
                                    <div class="col-lg-4"></div>
                                    <div class="col-lg-8">
                                        <div>Short Listed {{sortedAssetInspection.length}} Total {{assetInspection.pagination.total}} items, Per page {{assetInspection.pagination.per_page}},Showing {{assetInspection.pagination.from}} 〜
                                            {{assetInspection.pagination.to}}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 col-sm-12">
                                        <input type="text" class="form-control" v-model="assetInspection.search.global"
                                            v-on:keyup.enter="assetInspectionLoad(1)" v-on:keydown="assetInspectionLoad(1)" placeholder="Type and press enter to search"
                                            title="Search Item">
                                    </div>
                                    <div class="col-md-2 col-sm-12">
                                        <button class="btn btn-link" @click.prevent="assetInspectionClearSearch" title="Clear Search">
                                            <i class="fa fa-window-close icon" title="Clear All Search"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-7 col-sm-12 text-right header-menu-nav">
                                        <slot name="navigations"></slot>

                                        <!-- <router-link :to="{ path: `/assetInspection/${$route.params.id}/create`, params: $route.params.id }" class="btn btn-link">
                                        <i class="fa fa-plus-square icon" title="Add Asset Inspection"></i>
                                    </router-link> -->
                                        <button class="btn btn-link" @click.prevent="assetInspectionClearSearch" title="Clear Search">
                                            <i class="fas fa-eraser icon"></i>
                                        </button>

                                        <button :disabled="!assetInspection.selectedIds.length" class="btn btn-link"
                                            @click.prevent="assetInspectionErase" title="Delete Asset Inspection">
                                            <i class="fa fa-trash icon"></i>
                                        </button>
                                        <b-dropdown variant="link" no-caret>
                                            <template slot="button-content">
                                                <i class="fas fa-columns icon" title="Customize Columns"></i>
                                            </template>
                                            <div class="selected-columns">
                                                <li v-for="(column, index) in assetInspection.columns" :key="index">
                                                    <b-form-checkbox v-model="assetInspection.selectedColumns" @change="assetInspectionColumnSelect"
                                                        :value="column.name">{{column.label}}</b-form-checkbox>
                                                </li>
                                            </div>
                                        </b-dropdown>
                                        <download-excel :data="sortedAssetInspection" class="btn">
                                            <i class="fa fa-download icon" title="Export Asset Inspection List"></i>
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
                                                    <th v-if="assetInspectionCheckColumnVisibility('description')"
                                                        @click="assetInspectionSortByColumn('description')" class="text-center sortable">
                                                        Description
                                                        <i :class="assetInspectionSetSelectedColumnSortIcon('description')"></i>
                                                    </th>
                                                    <th v-if="assetInspectionCheckColumnVisibility('frequency')" @click="assetInspectionSortByColumn('frequency')"
                                                        class="text-center sortable">
                                                        Frequency
                                                        <i :class="assetInspectionSetSelectedColumnSortIcon('frequency')"></i>
                                                    </th>
                                                    <th v-if="assetInspectionCheckColumnVisibility('group')" @click="assetInspectionSortByColumn('group')"
                                                        class="text-center sortable">
                                                        Group
                                                        <i :class="assetInspectionSetSelectedColumnSortIcon('group')"></i>
                                                    </th>
                                                    <th v-if="assetInspectionCheckColumnVisibility('inspection_criteria')"
                                                        @click="assetInspectionSortByColumn('inspection_criteria')"
                                                        class="text-center sortable">
                                                        Criteria
                                                        <i :class="assetInspectionSetSelectedColumnSortIcon('inspection_criteria')"></i>
                                                    </th>
                                                    <th v-if="assetInspectionCheckColumnVisibility('file')" @click="assetInspectionSortByColumn('file')"
                                                        class="text-center sortable">
                                                        File
                                                        <i :class="assetInspectionSetSelectedColumnSortIcon('file')"></i>
                                                    </th>
                                                </tr>
                                                <!-- Column Search -->
                                                <tr>
                                                    <th v-if="assetInspectionCheckColumnVisibility('all')">
                                                        <b-form-checkbox name="checkAll" title="Check all" @change="assetInspectionAllassetInspectionRowSelect"
                                                            v-model="assetInspection.all"></b-form-checkbox>
                                                    </th>
                                                    <th v-if="assetInspectionCheckColumnVisibility('description')">
                                                        <input type="text" placeholder="Search" class="form-control"
                                                            v-model="assetInspection.search.description" />
                                                    </th>
                                                    <th v-if="assetInspectionCheckColumnVisibility('frequency')">
                                                        <input type="text" placeholder="Search" class="form-control"
                                                            v-model="assetInspection.search.frequency" />
                                                    </th>
                                                    <th v-if="assetInspectionCheckColumnVisibility('group')">
                                                        <input type="text" placeholder="Search" class="form-control"
                                                            v-model="assetInspection.search.group" />
                                                    </th>
                                                    <th v-if="assetInspectionCheckColumnVisibility('inspection_criteria')">
                                                        <input type="text" placeholder="Search" class="form-control"
                                                            v-model="assetInspection.search.inspection_criteria" />
                                                    </th>
                                                    <th v-if="assetInspectionCheckColumnVisibility('file')">
                                                        <input type="text" placeholder="Search" class="form-control"
                                                            v-model="assetInspection.search.file" />
                                                    </th>
                                                </tr>
                                            </thead>
                                            <!-- Table Body -->
                                            <tbody>
                                                <tr v-for="(asstInspection, index) in sortedAssetInspection" :key="index">
                                                    <td v-if="assetInspectionCheckColumnVisibility('all')">
                                                        <b-form-checkbox v-model="assetInspection.selectedIds" @change="assetInspectionRowSelect"
                                                            :value="asstInspection.id">
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
                                                    <td @click.prevent="openFile(asstInspection.file)" v-if="assetInspectionCheckColumnVisibility('file')">
                                                        <a href="#">{{ asstInspection.file }}</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <!-- Table Body End -->
                                        </table>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-lg-1 justify-content-center align-self-center">
                                        <multiselect v-model="assetInspection.pagination.per_page" @select="assetInspectionOnSelectPageCount"
                                            :options="assetInspection.pagination.perPageItems" :searchable="false"
                                            :show-labels="false" placeholder="Items / Page"></multiselect>
                                    </div>

                                    <div class="col-lg-3 justify-content-center align-self-center text-center">

                                    </div>

                                    <div class="col-lg-4 justify-content-center align-self-center text-center">
                                        <div>Total {{assetInspection.pagination.total}} items, Per page
                                            {{assetInspection.pagination.per_page}},
                                            Showing {{assetInspection.pagination.from}} 〜
                                            {{assetInspection.pagination.to}}</div>
                                    </div>

                                    <div class="col-lg-4 justify-content-center align-self-center">
                                        <ul class="pagination pagination-custom pull-right">
                                            <li :class="{disabled: assetInspection.pagination.current_page <= 1}"><a
                                                    href="#" @click.prevent="assetInspectionPageChange(1)">&laquo;</a></li>
                                            <li :class="{disabled: assetInspection.pagination.current_page <= 1}"><a
                                                    href="#" @click.prevent="assetInspectionPageChange(assetInspection.pagination.current_page - 1)">&lt;</a></li>
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
        <b-modal ref="assetModalType" size="lg" id="modal-center" @ok="submitassetType()" centered title="Add Asset Model Information">
            <div class="row">
                <div class="col-sm-12">
                    <form enctype="multipart/form-data" autocomplete='off'>
                        <div class="card border-0">
                            <!-- <div class="form-group form-actions">
                            <router-link :to="{ name: 'Asset Model List'}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button type="submit" style="margin-right: 10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                        </div> -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <b-form-fieldset label="Asset Model Name">
                                            <b-form-input type="text" v-model="assetModel.name" placeholder="Please Enter Asset Model Name"></b-form-input>
                                        </b-form-fieldset>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="">Manufacturer</label>
                                        <multiselect v-model="selectedManufacturer" :options="manufacturers" @select="onSelectManufacturer"
                                            track-by="id" label="name" placeholder="Select Manufacturer" selectLabel=""
                                            deselectLabel="" selectedLabel="Remove">
                                        </multiselect>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <label for="">Model Type</label>
                                        <multiselect v-model="selectedModelType" :options="modelTypes" @select="onSelectModelType"
                                            track-by="id" label="type" placeholder="Select Model Type" selectLabel=""
                                            deselectLabel="" selectedLabel="Remove">
                                        </multiselect>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <label for="">Rent Frequency *</label>
                                        <b-form-select v-model="assetModel.frequency" class="mb-3">
                                            <option :value="null">Please select an option</option>
                                            <option value="Yearly">Yearly</option>
                                            <option value="Monthly">Monthly</option>
                                            <option value="Weekly">Weekly</option>
                                        </b-form-select>
                                    </div>
                                    <!-- <div class="form-group col-sm-4">
                                     <b-form-fieldset label="Unit">
                                        <b-form-input type="text" v-model="assetModel.unit" placeholder="Unit Number"></b-form-input>
                                    </b-form-fieldset>
                                </div> -->
                                    <div class="form-group col-sm-6">
                                        <b-form-fieldset label="Rent *">
                                            <money v-model="assetModel.rent" class="form-control" v-bind="money"></money>

                                        </b-form-fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <b-form-fieldset label="Minimum Stock">
                                            <b-form-input type="text" v-model="assetModel.minimum_stock" placeholder="Minimum Stock"></b-form-input>
                                        </b-form-fieldset>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <b-form-fieldset label="Current Stock">
                                            <b-form-input type="text" v-model="assetModel.current_stock" placeholder="Current Stock"></b-form-input>
                                        </b-form-fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <b-form-textarea id="textarea1" v-model="assetModel.description" placeholder="Description"
                                            :rows="3" :max-rows="6">
                                        </b-form-textarea>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <b-form-textarea id="textarea1" v-model="assetModel.comment" placeholder="Comments"
                                            :rows="3" :max-rows="6">
                                        </b-form-textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <b-form-fieldset label="Utilization Type">
                                            <b-form-input type="text" v-model="assetModel.utilization_type" placeholder="Enter Utilization Type"></b-form-input>
                                        </b-form-fieldset>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <b-form-fieldset label="Utilization Unit">
                                            <b-form-input type="text" v-model="assetModel.unit" placeholder="Utilization Unit"></b-form-input>
                                        </b-form-fieldset>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <div id="preview">
                                            <img v-if="url" :src="url" style="height:100px;" />
                                            <img v-else src="/img/coffee.png" style="height:120px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <b-form-file @change="onFileChange" placeholder="Choose a file..."></b-form-file>
                                    </div>
                                </div>
                            </div>
                            <!-- {{assetModel}} {{edit}} -->
                        </div>
                    </form>
                </div>
            </div>
        </b-modal>
        <b-modal ref="companyModal" size="lg" id="modal-center" @ok="submitCompany()" centered title="Add New Customer">
            <div class="row">
                <div class="col-sm-12">
                    <form>
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-sm-4">
                                        <b-form-fieldset label="Company Name *">
                                            <b-form-input type="text" name="name" v-model="company.name" placeholder="Please enter company name"></b-form-input>
                                        </b-form-fieldset>
                                    </div>
                                    <div class="form-group col-sm-4">
                                        <b-form-fieldset label="Client No *">
                                            <b-form-input type="text" v-model="company.client_no" placeholder="Please enter client number"></b-form-input>
                                        </b-form-fieldset>
                                    </div>

                                    <div class="form-group col-sm-4">
                                        <label for="">Company Type *</label>
                                        <multiselect v-model="selectedCompanyType" :options="companyTypes" @select="onSelectCompanyType"
                                            track-by="id" label="type" placeholder="Company Type" selectLabel=""
                                            deselectLabel="" selectedLabel="Remove">
                                        </multiselect>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <b-form-fieldset label="Website">
                                            <b-form-input type="text" v-model="company.website" placeholder="http://www.website.com"
                                                name="website" v-validate="'url:{require_protocol?}'"></b-form-input>
                                        </b-form-fieldset>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <!-- <b-form-fieldset label="Phone Number">
                                            <vue-tel-input v-model="company.phone_number" @onInput="onInput"
                                                :preferredCountries="['us', 'gb', 'ua']"></vue-tel-input>
                                        </b-form-fieldset> -->
                                        <b-form-fieldset label="Phone Number">
                                            <b-form-input type="text" name="phone_number" v-model="company.phone_number" placeholder="Enter Phone Number"></b-form-input>
                                        </b-form-fieldset>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </b-modal>
        <b-modal ref="locationModal" size="lg" id="modal-center" @ok="submitLocation()" centered title="Add New Location">
            <div class="row">
                <div class="col-sm-12">
                    <form>
                        <div class="card border-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-sm-3">
                                        <b-form-fieldset label="Location Description">
                                            <b-form-input type="text" name="description" v-model="location.description"
                                                placeholder="Enter Location Description"></b-form-input>
                                        </b-form-fieldset>
                                    </div>
                                    <div class="form-group col-sm-3">
                                        <b-form-fieldset label="Location Address">
                                            <vue-google-autocomplete ref="address" id="map" classname="form-control"
                                                :placeholder="location.address" v-on:placechanged="getAddressData"
                                                country="nz">
                                            </vue-google-autocomplete>
                                        </b-form-fieldset>
                                    </div>
                                    <div class="form-group col-sm-3">
                                    <label for="">Distributor *</label>
                                    <multiselect v-model="selectedDistributor" :options="distributors" @select="onSelectDistributor"
                                        track-by="id" label="name" placeholder="Select Distributor" selectLabel=""
                                        deselectLabel="" selectedLabel="Remove">
                                    </multiselect>
                                </div>
                                <div class="form-group col-sm-3">
                                    <label for="">Region</label>
                                    <multiselect v-model="selectedRegions" :options="regions" @select="onSelectRegion"
                                        track-by="id" label="name" placeholder="Select Region" selectLabel=""
                                        deselectLabel="" selectedLabel="Remove">
                                    </multiselect>
                                </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-3">
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" v-model="location.is_primary" value="Yes" name="is_primary"
                                                class="custom-control-input">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">Is Primary Location For This
                                                Company?</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <!-- <b-form-fieldset label="Location Phone Number">
                                            <vue-tel-input v-model="location.phone_number" @onInput="onInput"
                                                :preferredCountries="['us', 'gb', 'ua']"></vue-tel-input>
                                        </b-form-fieldset> -->
                                        <b-form-fieldset label="Phone Number">
                                            <b-form-input type="text" name="phone_number" v-model="location.phone_number" placeholder="Enter Phone Number"></b-form-input>
                                        </b-form-fieldset>
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <label for="">Select Key Contact</label>
                                        <multiselect v-model="selectedUser" :options="users" @select="onSelectUser"
                                            track-by="id" label="full_name" placeholder="Select Key Contact"
                                            selectLabel="" deselectLabel="" selectedLabel="Remove">
                                        </multiselect>
                                        <div class="invalid-feedback">Please Select Key Contact</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import Multiselect from "vue-multiselect"
    import { Money } from "v-money"
    import "@pencilpix/vue2-clock-picker/dist/vue2-clock-picker.min.css"
    import VueGoogleAutocomplete from "vue-google-autocomplete"
    import VueClockPicker from "@pencilpix/vue2-clock-picker"
    import moment from 'moment'
    export default {
        name: "forms",
        components: { Multiselect, Money, VueGoogleAutocomplete },
        data() {
            return {
                machineSpecs: [],
                regions:[],
                assetUtilizations:[],
                assetStats: [],
                assets: [],
                contractors: [],
                customers: [],
                milestones: [],
                technicians: [],
                distributors: [],
                locations: [],
                assetModels: [],
                users: [],
                jobs: [],
                machineOptions: [],
                assetInspections: [],
                asset: {
                    status: null,
                    agent_id: null,
                    company_id: null,
                    location_id: null,
                    date: new Date().toISOString(),
                    machine_option: []
                },
                money: {
                    decimal: "",
                    thousands: ",",
                    prefix: "NZD$ ",
                    suffix: " ",
                    precision: 0,
                    masked: false
                },
                companyTypes: [],
                company: {
                    is_enable: 1,
                    type: null
                },
                location: {},
                edit: false,
                selectedContractor: { id: null, name: "Select Contractor" },
                selectedTechnician: { id: null, full_name: "Select Technician" },
                selectedCompanyType: { id: null, type: "Company Type" },
                selectedCustomer: { id: null, name: "Select Customer" },
                selectedLocation: { id: null, full_address: "Select Location" },
                selectedAssetType: { id: null, name: "Select Asset Type" },
                selectedDistributor: { id: null, name: "Select Distributor" },
                selectedAssetStat: { id: null, type: "Select Asset Status" },
                selectedUser: { id: null, user_name: "Select Key Contact" },
                selectedSerial: { id: null, serial: "Select Serial" },
                selectedBMB: { id: null, bmb: "Select bmb" },
                selectedDistributor: { id: null, name: "Select Distributor" },
                selectedRegions:{ id: null, name: "Select Region" },
                milestone: {
                    currentSortColumn: "name",
                    currentSortDirection: "asc",
                    search: {
                        global: "",
                        milestone_type_name: "",
                        description: "",
                        date: "",
                        comment: "",
                        cup_count: "",
                        cup_count_per_day: ""
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
                        { name: "milestone_type_name", label: "Milestone Type" },
                        { name: "description", label: "Description" },
                        { name: "date", label: "Date" },
                        { name: "cup_count", label: "Cup Count" },
                        { name: "cup_count_per_day", label: "Cup Count Per Day" },
                        { name: "comment", label: "Comment" }
                    ]
                },
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
                },
                job: {
                    currentSortColumn: "name",
                    currentSortDirection: "asc",
                    search: {
                        global: "",
                        description: "",
                        job_number: "",
                        job_type_name: "",
                        job_priority: "",
                        job_status: "",
                        tech_name: "",
                        comment: "",
                        customer_name: "",
                        location_address:"",
                        updated_at: ""
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
                        { name: "job_number", label: "Job Number" },
                        { name: "milestone_type_name", label: "Job Type" },
                        { name: "job_priority", label: "Job Priority" },
                        { name: "job_status", label: "Job Status" },
                        { name: "tech_name", label: "Technician Name" },
                        { name: "comment", label: "Comment" },
                        { name: "customer_name", label: "Customer Name" },
                        { name: "location_address", label: "Location" },
                        { name: "job_type_name", label: "Job Type Name" },
                        { name: "updated_at", label: "Job Close Date" }
                    ]
                },
                    assetUtilization: {
                    currateSortColumn: 'name',
                    currateSortDirection: 'asc',
                    search: {
                        global: '',
                        full_date: '',
                        utilization_type: '',
                        value: '',
                        utilization_unit: '',
                        rate: '',
                        rate_unit:'',
                        clear_calculation_description: '',
                        parts_changed: ''
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
                        { name: 'all', label: 'All' },
                        { name: 'full_date', label: 'Date' },
                        { name: 'utilization_type', label: 'Utilization Type' },
                        { name: 'value', label: 'value' },
                        { name: 'utilization_unit', label: 'Utilization Unit' },
                        { name: 'rate', label: 'Rate' },
                        { name: 'rate_unit', label: 'Rate Unit' },
                        { name: 'clear_calculation_description', label: 'Mother Board Changed' },
                        { name: 'parts_changed', label: 'Filter Changed' },
                    ]
                },
                modelTypes: [],
                manufacturers: [],
                selectedManufacturer: { id: null, name: "Manufacturer" },
                selectedModelType: { id: null, type: "Model Type" },
                assetModel: {
                    frequency: null
                },
                url: null,
                address: "",
            };
        },
        created() {
            console.log(this.$store.state.user);
            if (!this.$store.state.user) this.$router.push("/login");
            this.asset.account_id = this.$store.state.user.account_id;
            this.fetchDistributors()
            this.fetchContrctors()
            // this.fetchCompanyWiseTechnicians(this.asset.contractor_id)
            this.fetchAssetModels()
            this.fetchManufacturers()
            this.fetchModelTypes()
            this.fetchCompanyType()
            ///this.fetchDistributors()
            this.fetchRegions()

            if (this.$route.path.indexOf("create") > -1) {
                console.log("show result", this.$route.path.indexOf("create"));
                this.edit = false;
                this.fetchCustomers();
                this.fetchAssetStats();
                this.fetchCompanyWiseLocations(this.asset.location_id);
                // this.fetchCompanyWiseUsers()
            } else {
                this.edit = true;
                this.fetchById(this.$route.params.id);
                this.fetchAssetModels(this.asset.asset_model_id);
                this.fetchDistributors(this.asset.agent_id);
                //this.fetchJobsBasedOnAssetID(this.$route.params.id)
                //this.fetchMilestonesBasedOnAssetID(this.$route.params.id)
                this.fetchCustomers(this.asset.company_id);

                this.milestone.columns.forEach(column => {
                    this.milestone.selectedColumns.push(column.name);
                });

                this.job.columns.forEach(column => {
                    this.job.selectedColumns.push(column.name);
                });

                this.assetInspection.columns.forEach(column => {
                    this.assetInspection.selectedColumns.push(column.name)
                })

                this.assetUtilization.columns.forEach(column => {
                this.assetUtilization.selectedColumns.push(column.name)
                })

                this.jobLoad(1)

                this.assetInspectionLoad(1)
            }
        },
        methods: {
            submit() {
                this.$validator.validate().then(result => {
                    if (result) this.submitForm()
                });
            },
            fetchMachineOptionByAsset(id) {
                NProgress.start()
                this.$http
                    .get(`/api/machine-options-by-asset/${id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.machineOptions = response.data.data
                        console.log("machineOptions", this.machineOptions)
                        NProgress.done();
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push("/login");
                            }
                        }
                    })
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
                        if (this.edit) {
                            this.selectedContractor = this.contractors.find(
                                x => parseInt(x.id) === parseInt(this.asset.contractor_id)
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
                                x => parseInt(x.id) === parseInt(this.asset.technician_id)
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
            onSelectTechnician(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    this.asset.technician_id = selectedOption.id;
                    // this.technicianEmail = this.technicians.find(x => x.id === selectedOption.id).email
                    // console.log('technicianEmail', this.technicianEmail)
                }
            },
            onSelectContract(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.asset.contractor_id = selectedOption.id
                    this.fetchCompanyWiseTechnicians(selectedOption.id)
                }
            },
            submitForm() {
                
                self = this
                if (this.edit) {
                    NProgress.start()
                    this.asset.updated_by = this.$store.state.user.id
                    this.$http
                        .put(`/api/asset`, this.asset, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            NProgress.done()
                            this.$notify({
                                group: 'foo',
                                type: 'info',
                                title: 'Notification',
                                text: 'Asset Information Updated'
                            })
                            this.$router.push("/assets/")
                        })
                        .catch(error => {
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push("/login")
                                }
                            }
                        })
                } else {
                    // if(!this.checkAssetSerial()){
                    //     return
                    // }
                    NProgress.start()
                    self = this
                    this.asset.created_by = this.$store.state.user.id
                    this.$http
                        .post(`/api/asset`, this.asset, {
                            headers: {
                                Authorization: `Bearer ${this.$store.state.token}`
                            }
                        })
                        .then(response => {
                            this.$notify({
                                group: 'foo',
                                type: 'success',
                                title: 'Notification',
                                text: 'Asset Information Saved'
                            })
                            this.$router.push("/assets/")
                        })
                        .catch(error => {
                            if (error.response) {
                                console.log(error.response)
                                if (error.response.status === 401) {
                                    this.$router.push("/login")
                                }
                            }
                        })
                }
            },
            getJobStatusType(type) {
                let response = null;
                switch (type) {
                    case "Complete":
                        response = "job-status-warning";
                        break;
                    case "Allocated":
                        response = "job-status-danger";
                        break;
                    case "Open":
                        response = "job-status-danger";
                        break;
                    case "Received":
                        response = "job-status-danger";
                        break;
                    case "Close":
                        response = "job-status-success";
                        break;
                }
                return response;
            },
            fetchById(id) {
                NProgress.start();
                this.$http
                    .get(`/api/asset/${id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.asset = response.data.data

                        this.fetchCustomers()
                        this.fetchCompanyWiseLocations(this.asset.location_id)
                        this.fetchManufacturers()
                        this.fetchModelTypes()
                        this.fetchAssetStats()
                        this.fetchCompanyType()
                        this.fetchContrctors()
                        this.fetchAssetWiseGallery(this.asset.id)
                        this.fetchMachineOptionByAsset(this.asset.id)
                        this.fetchCompanyWiseTechnicians(this.asset.contractor_id)
                        // this.fetchMachineSpecsAll(this.asset.asset_model_id)
                        this.milestoneLoad(1)
                        this.assetInspectionLoad(1)
                        this.assetUtilizationLoad(1)
                        // this.asset.machine_option = this.asset.machine_option.split(",")
                        console.log("asset", this.asset)
                        NProgress.done()
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
            fetchDistributors() {
                NProgress.start();
                this.$http
                    .get(`/api/distributors`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.distributors = response.data.data
                       
                        console.log("distributors", this.distributors)
                        NProgress.done()
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
            fetchRegions() {
                NProgress.start()
                this.$http
                    .get(`/api/regions`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.regions = response.data.data
                        NProgress.done()
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push("/login")
                            }
                        }
                    })
            },
            onSelectDistributor(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.name)
                    this.location.agent_id = selectedOption.name
                }
            },
            onSelectRegion(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.location.region_id = selectedOption.id
                }
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
                                x => parseInt(x.id) === parseInt(this.asset.company_id)
                            );
                            this.fetchCompanyWiseLocations(this.asset.company_id)
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
            fetchCompanyType() {
                this.$http
                    .get(`/api/companyTypes`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.companyTypes = response.data.data;
                        console.log("company Types", this.companyTypes);
                        // if(this.edit){
                        //     this.selectedCompanyType =  this.companyTypes.find(x => (x.type) === (this.company.type))
                        // }
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
                        this.selectedAssetType = this.assetModels.find(
                            x => parseInt(x.id) === parseInt(this.asset.asset_model_id)
                        );
                        console.log("assetModels", this.assetModels);
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
            fetchMachineSpecs(id) {
                NProgress.start();
                this.$http
                    .get(`/api/machine-specs-serial/${id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.machineSpecs = response.data.data;
                        if (this.edit) {
                            this.selectedSerial = this.machineSpecs.find(
                                x => parseInt(x.serial) === parseInt(this.asset.serial)
                            );
                            this.selectedBMB = this.machineSpecs.find(
                                x => parseInt(x.bmb) === parseInt(this.asset.bmb)
                            );
                        }
                        console.log("machineSpecs", this.machineSpecs);
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
            
            fetchMachineSpecsAll(id) {
                NProgress.start();
                this.$http
                    .get(`/api/machine-specs-serial-all/${id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.machineSpecs = response.data.data;
                        if (this.edit) {
                            this.selectedSerial = this.machineSpecs.find(
                                x => parseInt(x.serial) === parseInt(this.asset.serial)
                            );
                            this.selectedBMB = this.machineSpecs.find(
                                x => parseInt(x.bmb) === parseInt(this.asset.bmb)
                            );
                        }
                        console.log("machineSpecs", this.machineSpecs);
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
            onSelectSerial(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    this.asset.serial = selectedOption.serial;
                    this.selectedBMB = selectedOption;
                    this.asset.bmb = selectedOption.bmb;
                }
            },
            onSelectBmb(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    this.asset.bmb = selectedOption.id;
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
                        this.locations = response.data.data
                        this.selectedLocation = this.locations.find(
                            x => parseInt(x.id) === parseInt(this.asset.location_id)
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
            companySelected: function (e) {
                this.fetchCompanyWiseLocations(event.srcElement.value);
                console.log("test result", event.srcElement.value);
            },
            fetchJobsBasedOnAssetID(id) {
                NProgress.start();
                this.$http
                    .get(`/api/asset/${id}/jobs`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.jobs = response.data.data;
                        console.log("jobs", this.jobs);
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
            fetchMilestonesBasedOnAssetID(id) {
                NProgress.start();
                this.$http
                    .get(`/api/asset/${id}/milestones`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.milestones = response.data.data;
                        console.log("milestones", this.milestones);
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
            eraseJob(id) {
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
                                .delete(`/api/job/${id}`, {
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
                                    this.fetchJobsBasedOnAssetID(this.$route.params.id);
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
            onSelectAssetType(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.asset.asset_model_id = selectedOption.id
                    // this.fetchMachineSpecs(selectedOption.id)
                    // this.fetchMachineOption(selectedOption.id)
                }
            },
            onSelectDistributor(selectedOption, name) {
                if (selectedOption) {
                    console.log(selectedOption.name);
                    this.asset.agent_id = selectedOption.name;
                }
            },
            onSelectCustomer(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    this.asset.company_id = selectedOption.id
                    this.location.company_id = selectedOption.id
                    this.fetchCompanyWiseLocations(selectedOption.id)
                    this.fetchCompanyWiseUsers(selectedOption.id)
                }
            },
            onSelectLocation(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id);
                    this.asset.location_id = selectedOption.id;
                }
            },
            onSelectAssetStat(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.type);
                    let finishingDate = new Date();

                    switch (selectedOption.type) {
                        case "Placement":
                            finishingDate.setHours(finishingDate.getHours() + 120);
                            break;
                    }
                    this.asset.finishing_date = finishingDate.toISOString();
                    console.log(finishingDate);
                    this.asset.asset_stat_id = selectedOption.id;
                    this.asset.asset_stat_flag = selectedOption.flag;
                }
            },
            onSelectCompanyType(selectedOption, type) {
                if (selectedOption) {
                    console.log(selectedOption.type);
                    this.company.type = selectedOption.type;
                }
            },
            getJobStatusType(type) {
                let response = null;
                switch (type) {
                    case "Complete":
                        response = "job-status-warning";
                        break;
                    case "Allocated ":
                        response = "job-status-danger";
                        break;
                    case "Open":
                        response = "job-status-danger";
                        break;
                    case "Received":
                        response = "job-status-danger";
                        break;
                    case "Close":
                        response = "job-status-success";
                        break;
                }
                return response;
            },
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
                let url = `/api/milestones-datatable-by-asset/${this.$route.params.id}`;
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

                this.milestone.search.milestone_type_name = "";
                this.milestone.search.description = "";
                this.milestone.search.date = "";
                this.milestone.search.comment = "";
                this.milestone.search.cup_count = "";
                this.milestone.search.cup_count_per_day = "";
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
            // job Datatable
            jobErase() {
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
                                .delete(`/api/job/${this.job.selectedIds.join(",")}`, {
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
                                    this.jobLoad(1);
                                    this.job.selectedIds = [];
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
            jobSortByColumn: function (s) {
                if (s === this.job.currentSortColumn) {
                    this.job.currentSortDirection =
                        this.job.currentSortDirection === "asc" ? "desc" : "asc";
                }
                this.job.currentSortColumn = s;
            },
            jobSetSelectedColumnSortIcon(type) {
                if (
                    this.job.currentSortDirection == "asc" &&
                    this.job.currentSortColumn == type
                )
                    return "fa fa-arrow-down";
                else if (
                    this.job.currentSortDirection == "desc" &&
                    this.job.currentSortColumn == type
                )
                    return "fa fa-arrow-down open";
                else return "fa fa-arrow-down open fa-disabled";
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
                };
                this.job.pagination = pagination;
            },
            jobAlljobRowSelect() {
                var self = this;
                Vue.nextTick(function () {
                    self.job.selectedIds = [];

                    if (self.job.all) {
                        self.sortedJob.forEach(job => {
                            self.job.selectedIds.push(job.id.toString());
                        });
                    } else {
                        if (self.sortedJob.length == self.job.selectedIds.length) {
                            self.job.selectedIds = [];
                        }
                    }
                });
            },
            jobRowSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    if (self.sortedJob.length == self.job.selectedIds.length) {
                        self.job.all = true;
                    } else {
                        self.job.all = false;
                    }
                });
            },
            jobColumnSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    console.log(self.job.selectedColumns);
                });
            },
            jobLoad(page) {
                NProgress.start();
                let url = `/api/job-list-by-assetid-datatable/${this.$route.params.id}`;
                let params = `?page=${page}&per_page=${
                    this.job.pagination.per_page
                    }&search=${this.job.search.global}`;

                this.$http
                    .get(url + params, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(({ data }) => {
                        console.log("jobsByAssetID", data);
                        this.jobs = data.data;
                        NProgress.done();
                        this.jobMakePagination(data.meta);
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
            jobPageChange(page) {
                if (page >= 1 && page <= this.job.pagination.last_page)
                    this.jobLoad(page);
            },
            jobPerPageChanged(count) {
                this.job.pagination.per_page = count;
                this.jobLoad(1);
            },
            jobClearSearch() {
                if (this.job.search.global.length) {
                    this.job.search.global = "";
                    this.jobLoad(1);
                }

                this.job.search.description = "";
                this.job.search.job_number = "";
                this.job.search.job_type_name = "";
                this.job.search.job_priority = "";
                this.job.search.job_status = "";
                this.job.search.tech_name = "";
                this.job.search.comment = "";
                this.job.search.customer_name = "";
                this.job.search.location_address = "";
                 this.job.search.updated_at = "";
            },
            jobOnSelectPageCount(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption, id);
                    this.job.pagination.per_page = selectedOption;
                    this.jobLoad(1);
                }
            },
            jobCheckColumnVisibility(column) {
                return this.job.selectedColumns.indexOf(column) !== -1 ? true : false;
            },
            //end of job datatable
            onFileChange(e) {
                let ref = this;
                const file = e.target.files[0]
                this.url = URL.createObjectURL(file)

                var reader = new FileReader()
                reader.readAsDataURL(file)
                reader.onloadend = function () {
                    let base64data = reader.result
                    console.log(base64data)
                    ref.assetModel.image = base64data
                };
            },
            fetchManufacturers() {
                this.$http
                    .get(`/api/manufacturers`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.manufacturers = response.data.data;
                        console.log("manufacturers", this.manufacturers);
                        if (this.edit) {
                            this.selectedManufacturer = this.manufacturers.find(
                                x => parseInt(x.id) === parseInt(this.assetModel.company_id)
                            );
                        }
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push("/login")
                            }
                        }
                    });
            },
            fetchModelTypes() {
                this.$http
                    .get(`/api/modelTypes`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.modelTypes = response.data.data
                        console.log("modelTypes", this.modelTypes)
                        if (this.edit) {
                            this.selectedModelType = this.modelTypes.find(
                                x => parseInt(x.id) === parseInt(this.assetModel.model_type_id)
                            )
                        }
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push("/login")
                            }
                        }
                    });
            },
            onSelectManufacturer(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.assetModel.company_id = selectedOption.id
                }
            },
            onSelectModelType(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.assetModel.model_type_id = selectedOption.id
                    // this.fetchMachineSpecs(selectedOption.id);

                }
            },
            addAssetType() {
                this.$refs.assetModalType.show()
            },
            addNewCompany() {
                this.$refs.companyModal.show()
            },
            addNewLocation() {
                this.$refs.locationModal.show()
            },
            submitassetType() {
                this.assetModel.created_by = this.$store.state.user.id;
                this.assetModel.account_id = this.$store.state.user.account_id;

                this.$http
                    .post(`/api/assetModel`, this.assetModel, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        //alert("Asset Model Type Added");
                        this.fetchAssetModels();
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response);
                            if (error.response.status === 401) {
                                this.$router.push("/login");
                            }
                        }
                    })
            },
            submitCompany() {
                this.company.created_by = this.$store.state.user.id
                this.company.account_id = this.$store.state.user.account_id

                this.$http
                    .post(`/api/company`, this.company, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.$notify({
                                group: 'foo',
                                type: 'success',
                                title: 'Notification',
                                text: 'Company Information Saved'
                        })
                        this.fetchCustomers()
                        this.fetchDistributors()
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push("/login")
                            }
                        }
                    })
            },
            submitLocation() {
                this.location.created_by = this.$store.state.user.id
                this.location.account_id = this.$store.state.user.account.id

                this.$http
                    .post(`/api/location`, this.location, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        alert("Location Added. Please Select Customer Again For Your New Location")
                        this.fetchCompanyWiseLocations(this.asset.location_id)
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push("/login")
                            }
                        }
                    });
            },
            onInput({ number, isValid, country }) {
                console.log(number, isValid, country)
            },
            getAddressData(addressData, placeResultData, id) {
                this.location.address = `${addressData.street_number}, ${
                    addressData.route
                    }, ${addressData.country}, ${addressData.postal_code}`
            },
            onSelectUser(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption.id)
                    this.location.user_id = selectedOption.id
                }
            },
            fetchCompanyWiseUsers(id) {
                this.$http
                    .get(`/api/company/${id}/users`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.users = response.data.data
                        console.log("users", this.users)
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push("/login")
                            }
                        }
                    });
            },
            fetchAssetWiseGallery(id) {
                this.$http
                    .get(`/api/assetwise-gallery/${id}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        this.galleries = response.data.data
                        console.log("galleries", this.usgalleriesers)
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push("/login")
                            }
                        }
                    });
            },
            checkAssetSerial() {
                this.$http
                    .get(`/api/check-asset-serial/${this.asset.serial}`, {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(response => {
                        if (response.data.serial) {
                            this.$swal("Asset Already in use")
                            return false
                        }
                    })
                    .catch(error => {
                        if (error.response) {
                            console.log(error.response)
                            if (error.response.status === 401) {
                                this.$router.push("/login")
                            }
                            return false
                        }
                    })
            },
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
            openFile(file) {
                console.log(file)
                var win = window.open(`/api/download-file/${file}`, '_blank')
                win.focus()
            },
            //Asset Utilization Datatable
            // assetUtilization Datatable
            assetUtilizationErase() {
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
                        this.$http.delete(`/api/assetUtilization/${this.assetUtilization.selectedIds.join(',')}`, {
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
                                this.assetUtilizationLoad(1)
                                this.assetUtilization.selectedIds = []
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
            assetUtilizationSortByColumn: function (s) {
                if (s === this.assetUtilization.currateSortColumn) {
                    this.assetUtilization.currateSortDirection = this.assetUtilization.currateSortDirection === 'asc' ? 'desc' : 'asc';
                }
                this.assetUtilization.currateSortColumn = s;
            },
            assetUtilizationSetSelectedColumnSortIcon(type) {
                if (this.assetUtilization.currateSortDirection == 'asc' && this.assetUtilization.currateSortColumn == type)
                    return 'fa fa-arrow-down'
                else if (this.assetUtilization.currateSortDirection == 'desc' && this.assetUtilization.currateSortColumn == type)
                    return 'fa fa-arrow-down open'
                else
                    return 'fa fa-arrow-down open fa-disabled'
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
                }
                this.assetUtilization.pagination = pagination;
            },
            assetUtilizationAllassetUtilizationRowSelect() {
                var self = this;
                Vue.nextTick(function () {
                    self.assetUtilization.selectedIds = []

                    if (self.assetUtilization.all) {
                        self.sortedAssetUtilization.forEach(assetUtilization => {
                            self.assetUtilization.selectedIds.push(assetUtilization.id.toString())
                        })
                    }
                    else {
                        if (self.sortedAssetUtilization.length == self.assetUtilization.selectedIds.length) {
                            self.assetUtilization.selectedIds = []
                        }
                    }
                })
            },
            assetUtilizationRowSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    if (self.sortedAssetUtilization.length == self.assetUtilization.selectedIds.length) {
                        self.assetUtilization.all = true
                    }
                    else {
                        self.assetUtilization.all = false
                    }
                })
            },
            assetUtilizationColumnSelect: function () {
                var self = this;
                Vue.nextTick(function () {
                    console.log(self.assetUtilization.selectedColumns)
                })
            },
            assetUtilizationLoad(page) {
                NProgress.start()
                let url = `/api/assetUtilizations-datatable/${this.$route.params.id}`
                let params = `?page=${page}&per_page=${this.assetUtilization.pagination.per_page}&search=${this.assetUtilization.search.global}`

                this.$http.get(url + params,
                    {
                        headers: {
                            Authorization: `Bearer ${this.$store.state.token}`
                        }
                    })
                    .then(({ data }) => {
                        console.log('assetUtilizations', data)
                        this.assetUtilizations = data.data
                        NProgress.done()
                        this.assetUtilizationMakePagination(data.meta)
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
            assetUtilizationPageChange(page) {
                if (page >= 1 && page <= this.assetUtilization.pagination.last_page) this.assetUtilizationLoad(page)
            },
            assetUtilizationPerPageChanged(count) {
                this.assetUtilization.pagination.per_page = count
                this.assetUtilizationLoad(1)
            },
            assetUtilizationClearSearch() {
                if (this.assetUtilization.search.global.length) {
                    this.assetUtilization.search.global = ''
                    this.assetUtilizationLoad(1)
                }

                this.assetUtilization.search.full_date = ''
                this.assetUtilization.search.utilization_type = ''
                this.assetUtilization.search.value = ''
                this.assetUtilization.search.utilization_unit = ''
                this.assetUtilization.search.rate = ''
                this.assetUtilization.search.rate_unit = '',
                this.assetUtilization.search.clear_calculation_description = '',
                this.assetUtilization.search.parts_changed = ''
            },
            assetUtilizationOnSelectPageCount(selectedOption, id) {
                if (selectedOption) {
                    console.log(selectedOption, id)
                    this.assetUtilization.pagination.per_page = selectedOption
                    this.assetUtilizationLoad(1)
                }
            },
            assetUtilizationCheckColumnVisibility(column) {
                return this.assetUtilization.selectedColumns.indexOf(column) !== -1 ? true : false
            },
            //end of assetUtilization datatable
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
        },
        computed: {
            filteredMilestone: function () {
                var self = this;
                return this.milestones
                    .filter(function (milestone) {
                        return milestone.milestone_type_name
                            ? milestone.milestone_type_name
                                .toLowerCase()
                                .indexOf(
                                    self.milestone.search.milestone_type_name.toLowerCase()
                                ) >= 0
                            : true;
                    })
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
                                .indexOf(self.milestone.search.cup_count_per_day.toString()) >= 0
                            : true;
                    })
                    .filter(function (milestone) {
                        return milestone.comment
                            ? milestone.comment
                                .toString()
                                .indexOf(self.milestone.search.comment.toString()) >= 0
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
            filteredJob: function () {
                var self = this;
                return this.jobs
                    .filter(function (job) {
                        return job.description
                            ? job.description
                                .toLowerCase()
                                .indexOf(self.job.search.description.toLowerCase()) >= 0
                            : true;
                    })
                    .filter(function (job) {
                        return job.job_number
                            ? job.job_number
                                .toString()
                                .indexOf(self.job.search.job_number.toString()) >= 0
                            : true;
                    })
                    .filter(function (job) {
                        return job.job_type_name
                            ? job.job_type_name
                                .toLowerCase()
                                .indexOf(self.job.search.job_type_name.toLowerCase()) >= 0
                            : true;
                    })
                    .filter(function (job) {
                        return job.job_priority
                            ? job.job_priority
                                .toLowerCase()
                                .indexOf(self.job.search.job_priority.toLowerCase()) >= 0
                            : true;
                    })
                    .filter(function (job) {
                        return job.job_status
                            ? job.job_status
                                .toLowerCase()
                                .indexOf(self.job.search.job_status.toLowerCase()) >= 0
                            : true;
                    })
                    .filter(function (job) {
                        return job.tech_name
                            ? job.tech_name
                                .toLowerCase()
                                .indexOf(self.job.search.tech_name.toLowerCase()) >= 0
                            : true;
                    })
                    .filter(function (job) {
                        return job.customer_name
                            ? job.customer_name
                                .toLowerCase()
                                .indexOf(self.job.search.customer_name.toLowerCase()) >= 0
                            : true;
                    })
                    .filter(function (job) {
                        return job.location_address
                            ? job.location_address
                                .toLowerCase()
                                .indexOf(self.job.search.location_address.toLowerCase()) >= 0
                            : true;
                    })
                    .filter(function (job) {
                        return job.updated_at
                            ? job.updated_at
                                .toString()
                                .indexOf(self.job.search.updated_at.toString()) >= 0
                            : true;
                    })
                    .filter(function (job) {
                        return job.comment
                            ? job.comment
                                .toLowerCase()
                                .indexOf(self.job.search.comment.toLowerCase()) >= 0
                            : true;
                    });
            },
            sortedJob: function () {
                return this.filteredJob.sort((a, b) => {
                    let modifier = 1;
                    if (this.job.currentSortDirection === "desc") modifier = -1;

                    let temp = `.${this.job.currentSortColumn}`;
                    if (eval(`a${temp}`) < eval(`b${temp}`)) return -1 * modifier;
                    if (eval(`a${temp}`) > eval(`b${temp}`)) return 1 * modifier;
                    return 0;
                });
            },
            JobPages() {
                let start = _.max([this.job.pagination.current_page - 2, 1]);
                let end = _.min([start + 5, this.job.pagination.last_page + 1]);
                start = _.max([end - 5, 1]);
                return _.range(start, end);
            },
            filteredAssetInspection: function () {
                var self = this
                return this.assetInspections.filter(function (assetInspection) {
                    return assetInspection.description ? assetInspection.description.toLowerCase().indexOf(self.assetInspection.search.description.toLowerCase()) >= 0 : true
                })
                    .filter(function (assetInspection) {
                        return assetInspection.frequency ? assetInspection.frequency.toString().indexOf(self.assetInspection.search.frequency.toString()) >= 0 : true
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

            //Asset Utilization Datatable
            filteredAssetUtilization: function () {
                var self = this
                return this.assetUtilizations.filter(function (assetUtilization) {
                    return assetUtilization.full_date ? assetUtilization.full_date.toString().indexOf(self.assetUtilization.search.full_date.toString()) >= 0 : true
                })
                    .filter(function (assetUtilization) {
                        return assetUtilization.utilization_type ? assetUtilization.utilization_type.toLowerCase().indexOf(self.assetUtilization.search.utilization_type.toLowerCase()) >= 0 : true
                    })
                    .filter(function (assetUtilization) {
                        return assetUtilization.value ? assetUtilization.value.toString().indexOf(self.assetUtilization.search.value.toString()) >= 0 : true
                    })
                    .filter(function (assetUtilization) {
                        return assetUtilization.utilization_unit ? assetUtilization.utilization_unit.toLowerCase().indexOf(self.assetUtilization.search.utilization_unit.toLowerCase()) >= 0 : true
                    })
                    .filter(function (assetUtilization) {
                        return assetUtilization.clear_calculation_description ? assetUtilization.clear_calculation_description.toLowerCase().indexOf(self.assetUtilization.search.clear_calculation_description.toLowerCase()) >= 0 : true
                    })
                    .filter(function (assetUtilization) {
                        return assetUtilization.parts_changed ? assetUtilization.parts_changed.toLowerCase().indexOf(self.assetUtilization.search.parts_changed.toLowerCase()) >= 0 : true
                    })
                    .filter(function (assetUtilization) {
                        return assetUtilization.rate ? assetUtilization.rate.toString().indexOf(self.assetUtilization.search.rate.toString()) >= 0 : true
                    })
                    .filter(function (assetUtilization) {
                        return assetUtilization.rate_unit ? assetUtilization.rate_unit.toString().indexOf(self.assetUtilization.search.rate_unit.toString()) >= 0 : true
                    })
            },
            sortedAssetUtilization: function () {
                return this.filteredAssetUtilization.sort((a, b) => {
                    let modifier = 1
                    if (this.assetUtilization.currateSortDirection === 'desc') modifier = -1

                    let temp = `.${this.assetUtilization.currateSortColumn}`
                    if (eval(`a${temp}`) < eval(`b${temp}`)) return -1 * modifier
                    if (eval(`a${temp}`) > eval(`b${temp}`)) return 1 * modifier
                    return 0
                })
            },
            AssetUtilizationPages() {
                let start = _.max([this.assetUtilization.pagination.currate_page - 2, 1])
                let end = _.min([start + 5, this.assetUtilization.pagination.last_page + 1])
                start = _.max([end - 5, 1])
                return _.range(start, end)
            },

        },
    }
</script>
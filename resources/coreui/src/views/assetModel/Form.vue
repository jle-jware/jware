<template>
  <div class="animated fadeIn">
    <div class="row">
      <div class="col-sm-12">
        <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm">
          <div class="card border-0">
            <div class="form-group form-actions">
              <router-link :to="{ name: 'Asset Model List'}">
                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
              </router-link>
              <button type="submit" style="margin-right: 10px" class="btn btn-sm btn-primary pull-right">Submit</button>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="form-group col-sm-4">
                  <b-form-fieldset label="Asset Name *">
                    <b-form-input type="text" :class="errors.has('name') ? 'form-control is-invalid':'form-control'"
                      v-validate="'required'" name="name" v-model="assetModel.name" placeholder="Please enter Asset Name"></b-form-input>
                    <span class="is-danger">{{ errors.first('name') }}</span>
                  </b-form-fieldset>
                </div>
                <div class="form-group col-sm-4">
                  <label for="">Manufacturer</label>
                  <multiselect v-model="selectedManufacturer" :class="{ 'is-danger': !assetModel.company_id }" :options="manufacturers"
                    @select="onSelectManufacturer" track-by="id" label="name" placeholder="Select Manufacturer"
                    selectLabel="" deselectLabel="" selectedLabel="Remove" v-validate="'required'">
                  </multiselect>
                  <a @click.prevent="addNewCompany()" href="#">Add New Manufacturer</a>
                </div>
                <div class="form-group col-sm-4">
                  <label for="">Model Type</label>
                  <multiselect v-model="selectedModelType" :class="{ 'is-danger': !assetModel.model_type_id }" :options="modelTypes"
                    @select="onSelectModelType" track-by="id" label="type" placeholder="Select Model Type" selectLabel=""
                    deselectLabel="" selectedLabel="Remove" v-validate="'required'">
                  </multiselect>
                  <a @click.prevent="addModelType()" href="#">Add New Model</a>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="">Rent Frequency *</label>
                  <b-form-select v-model="assetModel.frequency" class="mb-3">
                    <option :value="null">Please select an option</option>
                    <option value="Yearly">Yearly</option>
                    <option value="Monthly">Monthly</option>
                    <option value="Weekly">Weekly</option>
                  </b-form-select>
                </div>
                <div class="form-group col-sm-4">
                  <b-form-fieldset label="Asset Value">
                    <money v-model="assetModel.value" class="form-control" v-bind="money"></money>
                  </b-form-fieldset>
                </div>
                <div class="form-group col-sm-4">
                  <b-form-fieldset label="Rent *">
                    <money v-model="assetModel.rent" class="form-control" v-bind="money"></money>
                  </b-form-fieldset>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-6">
                  <b-form-fieldset label="Cannister Number">
                    <b-form-input type="text" v-model="assetModel.cannister_no" placeholder="Please Enter Cannister Number"></b-form-input>
                  </b-form-fieldset>
                </div>
                <div class="form-group col-sm-6">
                  <b-form-fieldset label="Drink Number">
                    <b-form-input type="text" v-model="assetModel.drink_no" placeholder="Please Enter Drink Number"></b-form-input>
                  </b-form-fieldset>
                </div>
              </div>
              <!-- <div class="row">
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
                            </div> -->
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
    <div :hidden="!edit" class="col-sm-12">
      <div role="tablist" class="company-collapse">
        <b-card no-body class="mb-1">
          <b-card-header header-tag="header" class="p-1" role="tab">
            <b-btn block href="#" v-b-toggle.accordion1 variant="danger ">Model Inspection</b-btn>
          </b-card-header>
          <b-collapse id="accordion1" accordion="my-accordion" role="tabpanel">
            <b-card-body>
              <div class="row">
                <div class="col-md-3 col-sm-12">
                  <input type="text" class="form-control" v-model="inspection.search.global" v-on:keyup.enter="inspectionLoad(1)"
                    placeholder="Type and press enter to search" title="Search Item">
                </div>
                <div class="col-md-9 col-sm-12 text-right header-menu-nav">
                  <slot name="navigations"></slot>

                  <router-link :to="{  path: `/inspection/${$route.params.id}/create`, params: $route.params.id }"
                    class="btn btn-link">
                    <i class="fa fa-plus-square icon" title="Add Item"></i>
                  </router-link>
                  <button class="btn btn-link" @click.prevent="inspectionClearSearch" title="Clear Search">
                    <i class="fas fa-eraser icon"></i>
                  </button>

                  <button :disabled="!inspection.selectedIds.length" class="btn btn-link" @click.prevent="inspectionErase"
                    title="Delete Item">
                    <i class="fa fa-trash icon"></i>
                  </button>
                  <b-dropdown variant="link" no-caret>
                    <template slot="button-content">
                      <i class="fas fa-columns icon"></i>
                    </template>
                    <div class="selected-columns">
                      <li v-for="(column, index) in inspection.columns" :key="index">
                        <b-form-checkbox v-model="inspection.selectedColumns" @change="inspectionColumnSelect" :value="column.name">{{column.label}}</b-form-checkbox>
                      </li>
                    </div>
                  </b-dropdown>
                  <download-excel :data="sortedInspection" class="btn">
                    <i class="fa fa-download icon"></i>
                  </download-excel>
                </div>
                <div class="col-md-12">
                  <table class="table table-striped table-responsive">
                    <thead>
                      <!-- Column Sort -->
                      <tr>
                        <th v-if="inspectionCheckColumnVisibility('all')">
                          All
                        </th>
                        <th v-if="inspectionCheckColumnVisibility('description')" @click="inspectionSortByColumn('description')"
                          class="text-center sortable">
                          Description
                          <i :class="inspectionSetSelectedColumnSortIcon('description')"></i>
                        </th>
                        <th v-if="inspectionCheckColumnVisibility('frequency')" @click="inspectionSortByColumn('frequency')"
                          class="text-center sortable">
                          Frequency
                          <i :class="inspectionSetSelectedColumnSortIcon('frequency')"></i>
                        </th>
                        <th v-if="inspectionCheckColumnVisibility('group')" @click="inspectionSortByColumn('group')"
                          class="text-center sortable">
                          Group
                          <i :class="inspectionSetSelectedColumnSortIcon('group')"></i>
                        </th>
                         <th v-if="inspectionCheckColumnVisibility('inspection_criteria')" @click="inspectionSortByColumn('inspection_criteria')"
                          class="text-center sortable">
                          Criteria
                          <i :class="inspectionSetSelectedColumnSortIcon('inspection_criteria')"></i>
                        </th>
                        <th v-if="inspectionCheckColumnVisibility('comment')" @click="inspectionSortByColumn('comment')"
                          class="text-center sortable">
                          Comment
                          <i :class="inspectionSetSelectedColumnSortIcon('comment')"></i>
                        </th>
                        <th v-if="inspectionCheckColumnVisibility('file')" @click="inspectionSortByColumn('file')"
                          class="text-center sortable">
                          File
                          <i :class="inspectionSetSelectedColumnSortIcon('file')"></i>
                        </th>
                      </tr>
                      <!-- Column Search -->
                      <tr>
                        <th v-if="inspectionCheckColumnVisibility('all')">
                          <b-form-checkbox name="checkAll" title="Check all" @change="inspectionAllinspectionRowSelect"
                            v-model="inspection.all"></b-form-checkbox>
                        </th>
                        <th v-if="inspectionCheckColumnVisibility('description')">
                          <input type="text" placeholder="Search" class="form-control" v-model="inspection.search.description" />
                        </th>
                        <th v-if="inspectionCheckColumnVisibility('frequency')">
                          <input type="text" placeholder="Search" class="form-control" v-model="inspection.search.frequency" />
                        </th>
                        <th v-if="inspectionCheckColumnVisibility('group')">
                          <input type="text" placeholder="Search" class="form-control" v-model="inspection.search.group" />
                        </th>
                        <th v-if="inspectionCheckColumnVisibility('inspection_criteria')">
                          <input type="text" placeholder="Search" class="form-control" v-model="inspection.search.inspection_criteria" />
                        </th>
                        <th v-if="inspectionCheckColumnVisibility('comment')">
                          <input type="text" placeholder="Search" class="form-control" v-model="inspection.search.comment" />
                        </th>
                        <th v-if="inspectionCheckColumnVisibility('file')">
                          <input type="text" placeholder="Search" class="form-control" v-model="inspection.search.file" />
                        </th>
                      </tr>
                    </thead>
                    <!-- Table Body -->
                    <tbody>
                      <tr v-for="(inspec, index) in sortedInspection" :key="index">
                        <td v-if="inspectionCheckColumnVisibility('all')">
                          <b-form-checkbox v-model="inspection.selectedIds" @change="inspectionRowSelect" :value="inspec.id">
                          </b-form-checkbox>
                        </td>
                        <td v-if="inspectionCheckColumnVisibility('description')">
                          <router-link :to="{ path: `/inspection/${inspec.id}`}">
                            {{ inspec.description }}
                          </router-link>
                        </td>
                        <td v-if="inspectionCheckColumnVisibility('frequency')">
                          {{ inspec.frequency }}
                        </td>
                        <td v-if="inspectionCheckColumnVisibility('group')">
                          {{ inspec.group }}
                        </td>
                         <td v-if="inspectionCheckColumnVisibility('inspection_criteria')">
                          {{ inspec.inspection_criteria }}
                        </td>
                        <td v-if="inspectionCheckColumnVisibility('comment')">
                          {{ inspec.comment }}
                        </td>
                        <td  @click.prevent="openFile(inspec.file)" v-if="inspectionCheckColumnVisibility('file')">
											    <a href="#">{{ inspec.file }}</a>
										    </td>
                      </tr>
                    </tbody>
                    <!-- Table Body End -->
                  </table>
                </div>

              </div>
              <div class="row">
                <div class="col-lg-1 justify-content-center align-self-center">
                  <multiselect v-model="inspection.pagination.per_page" @select="inspectionOnSelectPageCount" :options="inspection.pagination.perPageItems"
                    :searchable="false" :show-labels="false" placeholder="Items / Page"></multiselect>
                </div>

                <div class="col-lg-3 justify-content-center align-self-center text-center">

                </div>

                <div class="col-lg-4 justify-content-center align-self-center text-center">
                  <div>Total {{inspection.pagination.total}} items, Per page
                    {{inspection.pagination.per_page}},
                    Showing {{inspection.pagination.from}} 〜 {{inspection.pagination.to}}</div>
                </div>

                <div class="col-lg-4 justify-content-center align-self-center">
                  <ul class="pagination pagination-custom pull-right">
                    <li :class="{disabled: inspection.pagination.current_page <= 1}"><a href="#" @click.prevent="inspectionPageChange(1)">&laquo;</a></li>
                    <li :class="{disabled: inspection.pagination.current_page <= 1}"><a href="#" @click.prevent="inspectionPageChange(inspection.pagination.current_page - 1)">&lt;</a></li>
                    <li v-for="page in InspectionPages" :key="page" :class="{active: page === inspection.pagination.current_page}">
                      <a href="#" @click.prevent="inspectionPageChange(page)">{{page}}</a>
                    </li>
                    <li :class="{disabled: inspection.pagination.current_page >= inspection.pagination.last_page}"><a
                        href="#" @click.prevent="inspectionPageChange(inspection.pagination.current_page + 1)">&gt;</a></li>
                    <li :class="{disabled: inspection.pagination.current_page >= inspection.pagination.last_page}"><a
                        href="#" @click.prevent="inspectionPageChange(inspection.pagination.last_page)">&raquo;</a></li>
                  </ul>
                </div>
              </div>
            </b-card-body>
          </b-collapse>
        </b-card>
      </div>
    </div>
    <div :hidden="!edit" class="col-sm-12">
      <div role="tablist" class="machine-collapse">
        <b-card no-body class="mb-1">
          <b-card-header header-tag="header" class="p-1" role="tab">
            <b-btn block href="#" v-b-toggle.accordion2 variant="success ">Machine Option</b-btn>
          </b-card-header>
          <b-collapse id="accordion2" accordion="my-accordion" role="tabpanel">
            <b-card-body>
              <div class="row">
                <div class="col-md-3 col-sm-12">
                  <input type="text" class="form-control" v-model="machineOption.search.global" v-on:keyup.enter="machineOptionLoad(1)"
                    placeholder="Type and press enter to search" title="Search Item">
                </div>
                <div class="col-md-9 col-sm-12 text-right header-menu-nav">
                  <slot name="navigations"></slot>

                  <router-link :to="{ path: `/machineOption/${$route.params.id}/create` }" class="btn btn-link">
                    <i class="fa fa-plus-square icon" title="Add Item"></i>
                  </router-link>
                  <button class="btn btn-link" @click.prevent="machineOptionClearSearch" title="Clear Search">
                    <i class="fas fa-eraser icon"></i>
                  </button>

                  <button :disabled="!machineOption.selectedIds.length" class="btn btn-link" @click.prevent="machineOptionErase"
                    title="Delete Item">
                    <i class="fa fa-trash icon"></i>
                  </button>
                  <b-dropdown variant="link" no-caret>
                    <template slot="button-content">
                      <i class="fas fa-columns icon"></i>
                    </template>
                    <div class="selected-columns">
                      <li v-for="(column, index) in machineOption.columns" :key="index">
                        <b-form-checkbox v-model="machineOption.selectedColumns" @change="machineOptionColumnSelect"
                          :value="column.name">{{column.label}}</b-form-checkbox>
                      </li>
                    </div>
                  </b-dropdown>
                  <download-excel :data="sortedMachineOption" class="btn">
                    <i class="fa fa-download icon"></i>
                  </download-excel>
                </div>
                <div class="col-md-12">
                  <table class="table table-striped table-responsive">
                    <thead>
                      <!-- Column Sort -->
                      <tr>
                        <th v-if="machineOptionCheckColumnVisibility('all')">
                          All
                        </th>
                        <th v-if="machineOptionCheckColumnVisibility('option_name')" @click="machineOptionSortByColumn('option_name')"
                          class="text-center sortable">
                          Options
                          <i :class="machineOptionSetSelectedColumnSortIcon('option_name')"></i>
                        </th>
                        <th v-if="machineOptionCheckColumnVisibility('value')" @click="machineOptionSortByColumn('value')"
                          class="text-center sortable">
                          Value
                          <i :class="machineOptionSetSelectedColumnSortIcon('value')"></i>
                        </th>
                      </tr>
                      <!-- Column Search -->
                      <tr>
                        <th v-if="machineOptionCheckColumnVisibility('all')">
                          <b-form-checkbox name="checkAll" title="Check all" @change="machineOptionAllmachineOptionRowSelect"
                            v-model="machineOption.all"></b-form-checkbox>
                        </th>
                        <th v-if="machineOptionCheckColumnVisibility('option_name')">
                          <input type="text" placeholder="Search" class="form-control" v-model="machineOption.search.option_name" />
                        </th>
                        <th v-if="machineOptionCheckColumnVisibility('value')">
                          <input type="text" placeholder="Search" class="form-control" v-model="machineOption.search.value" />
                        </th>
                      </tr>
                    </thead>
                    <!-- Table Body -->
                    <tbody>
                      <tr v-for="(machOpt, index) in sortedMachineOption" :key="index">
                        <td v-if="machineOptionCheckColumnVisibility('all')">
                          <b-form-checkbox v-model="machineOption.selectedIds" @change="machineOptionRowSelect" :value="machOpt.id">
                          </b-form-checkbox>
                        </td>
                        <td v-if="machineOptionCheckColumnVisibility('option_name')">
                          <router-link :to="{ path: `/machineOption/${machOpt.id}`}">
                            {{ machOpt.option_name }}
                          </router-link>
                        </td>
                        <td v-if="machineOptionCheckColumnVisibility('value')">
                          {{ machOpt.value }}
                        </td>
                      </tr>
                    </tbody>
                    <!-- Table Body End -->
                  </table>
                </div>

              </div>
              <div class="row">
                <div class="col-lg-1 justify-content-center align-self-center">
                  <multiselect v-model="machineOption.pagination.per_page" @select="machineOptionOnSelectPageCount"
                    :options="machineOption.pagination.perPageItems" :searchable="false" :show-labels="false"
                    placeholder="Items / Page"></multiselect>
                </div>

                <div class="col-lg-3 justify-content-center align-self-center text-center">

                </div>

                <div class="col-lg-4 justify-content-center align-self-center text-center">
                  <div>Total {{machineOption.pagination.total}} items, Per page
                    {{machineOption.pagination.per_page}},
                    Showing {{machineOption.pagination.from}} 〜 {{machineOption.pagination.to}}</div>
                </div>

                <div class="col-lg-4 justify-content-center align-self-center">
                  <ul class="pagination pagination-custom pull-right">
                    <li :class="{disabled: machineOption.pagination.current_page <= 1}"><a href="#" @click.prevent="machineOptionPageChange(1)">&laquo;</a></li>
                    <li :class="{disabled: machineOption.pagination.current_page <= 1}"><a href="#" @click.prevent="machineOptionPageChange(machineOption.pagination.current_page - 1)">&lt;</a></li>
                    <li v-for="page in machineOptionPages" :key="page" :class="{active: page === machineOption.pagination.current_page}">
                      <a href="#" @click.prevent="machineOptionPageChange(page)">{{page}}</a>
                    </li>
                    <li :class="{disabled: machineOption.pagination.current_page >= machineOption.pagination.last_page}"><a
                        href="#" @click.prevent="machineOptionPageChange(machineOption.pagination.current_page + 1)">&gt;</a></li>
                    <li :class="{disabled: machineOption.pagination.current_page >= machineOption.pagination.last_page}"><a
                        href="#" @click.prevent="machineOptionPageChange(machineOption.pagination.last_page)">&raquo;</a></li>
                  </ul>
                </div>
              </div>
            </b-card-body>
          </b-collapse>
        </b-card>
      </div>
    </div>
    <b-modal ref="assetModelTypeModal" size="lg" id="modal-center" @ok="submitNewModelType()" centered title="Add New Model">
      <div class="row">
        <div class="col-sm-12">
          <form>
            <div class="card border-0">
              <div class="card-body">
                <div class="row">
                  <div class="form-group col-sm-12">
                    <b-form-fieldset label="Model Type">
                      <b-form-input type="text" :class="errors.has('type') ? 'form-control is-invalid':'form-control'"
                        name="type" v-model="modelType.type" placeholder="Please enter Model Type"></b-form-input>
                    </b-form-fieldset>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-12">
                    <b-form-textarea id="textarea1" v-model="modelType.comment" placeholder="Comments" :rows="3"
                      :max-rows="6">
                    </b-form-textarea>
                  </div>
                </div>
                <!-- {{modelType}} {{edit}} -->
              </div>
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
                      <b-form-input type="text" :class="errors.has('name') ? 'form-control is-invalid':'form-control'"
                        name="name" v-model="company.name" placeholder="Please enter company name"></b-form-input>
                    </b-form-fieldset>
                  </div>
                  <div class="form-group col-sm-4">
                    <b-form-fieldset label="Client No *">
                      <b-form-input type="text" v-model="company.client_no" placeholder="Please enter client number"></b-form-input>
                    </b-form-fieldset>
                  </div>

                  <div class="form-group col-sm-4">
                    <label for="">Company Type *</label>
                    <multiselect v-model="selectedCompanyType" :class="{ 'is-danger': !company.type }" :options="companyTypes"
                      @select="onSelectCompanyType" track-by="id" label="type" placeholder="Company Type" selectLabel=""
                      deselectLabel="" selectedLabel="Remove">
                    </multiselect>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-sm-6">
                    <b-form-fieldset label="Website">
                      <b-form-input type="text" :class="errors.has('website') ? 'form-control is-invalid':'form-control'"
                        v-model="company.website" placeholder="http://www.website.com" name="website" v-validate="'url:{require_protocol?}'"></b-form-input>
                    </b-form-fieldset>
                  </div>
                  <div class="form-group col-sm-6">
                    <b-form-fieldset label="Phone Number">
                      <vue-tel-input v-model="company.phone_number" @onInput="onInput" :preferredCountries="['us', 'gb', 'ua']"></vue-tel-input>
                    </b-form-fieldset>
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
<style>
  body {
    background-color: #e2e2e2;
  }

  #app {
    padding: 20px;
  }

  #preview {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  #preview img {
    max-width: 100%;
    max-height: 500px;
  }
</style>
<script>
  import Multiselect from "vue-multiselect";
  import { Money } from "v-money";
  export default {
    name: "forms",
    components: { Multiselect, Money },
    data() {
      return {
        modelTypes: [],
        manufacturers: [],
        inspections: [],
        machineOptions: [],
        assetModel: {
          model_type_id: null,
          frequency: null,
          company_id: null
        },
        modelType: {},
        money: {
          decimal: "",
          thousands: ",",
          prefix: "NZD$ ",
          suffix: " ",
          precision: 0,
          masked: false
        },
        selectedManufacturer: { id: null, name: "Select Manufacturer" },
        selectedModelType: { id: null, type: "Select Model Type" },
        selectedCompanyType: { id: null, type: "Company Type" },
        edit: false,
        url: null,
        inspection: {
          currentSortColumn: "name",
          currentSortDirection: "asc",
          search: {
            global: "",
            description: "",
            frequency: "",
            group: "",
            comment: "",
            file: "",
            inspection_criteria:""
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
            { name: "description", label: "Result" },
            { name: "frequency", label: "Frequency" },
            { name: "group", label: "Group" },
            { name: "inspection_criteria", label: "Inspection Criteria" },
            { name: "comment", label: "Comment" },
            { name: "file", label: "File" }
          ]
        },
        companyTypes: [],
        company: {
          is_enable: 1,
          type: null
        },
        machineOption: {
          currentSortColumn: "name",
          currentSortDirection: "asc",
          search: {
            global: "",
            option_name: "",
            value: ""
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
            { name: "option_name", label: "Description" },
            { name: "value", label: "Address" }
          ]
        }
      };
    },
    created() {
      console.log(this.$store.state.user);
      if (!this.$store.state.user) this.$router.push("/login");
      this.assetModel.account_id = this.$store.state.user.account_id;
      this.fetchAssetModelWiseInspections();
      if (this.$route.path.indexOf("create") > -1) {
        console.log("show description", this.$route.path.indexOf("create"));
        this.edit = false;
        this.fetchManufacturers();
        this.fetchModelTypes();
        this.fetchCompanyType();
      } else {
        this.edit = true;
        this.fetchById(this.$route.params.id);
      }
      this.inspection.columns.forEach(column => {
        this.inspection.selectedColumns.push(column.name);
      });
      this.machineOption.columns.forEach(column => {
        this.machineOption.selectedColumns.push(column.name);
      });
    },
    methods: {
      submit() {
        this.$validator.validate().then(description => {
          if (description) this.submitForm();
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
            this.modelTypes = response.data.data;
            console.log("modelTypes", this.modelTypes);
            if (this.edit) {
              this.selectedModelType = this.modelTypes.find(
                x => parseInt(x.id) === parseInt(this.assetModel.model_type_id)
              );
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
              console.log(error.response);
              if (error.response.status === 401) {
                this.$router.push("/login");
              }
            }
          });
      },
      submitForm() {
        NProgress.start();
        self = this
        if (this.edit) {
          this.assetModel.updated_by = this.$store.state.user.id
          this.$http
            .put(`/api/assetModel`, this.assetModel, {
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
                  text: 'Asset Model Information Updated'
              })
              this.$router.push("/assetModels/")
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
          this.assetModel.created_by = this.$store.state.user.id
          this.$http
            .post(`/api/assetModel`, this.assetModel, {
              headers: {
                Authorization: `Bearer ${this.$store.state.token}`
              }
            })
            .then(response => {
              NProgress.done()
              this.$notify({
                  group: 'foo',
                  type: 'success',
                  title: 'Notification',
                  text: 'Job Information Saved'
              })
              this.$router.push("/assetModels/")
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
      fetchById(id) {
        this.$http
          .get(`/api/assetModel/${id}`, {
            headers: {
              Authorization: `Bearer ${this.$store.state.token}`
            }
          })
          .then(response => {
            this.assetModel = response.data.data;
            this.url = this.assetModel.image;
            console.log("assetModel", this.assetModel);
            this.fetchManufacturers();
            this.fetchModelTypes();
            this.inspectionLoad(1);
            this.machineOptionLoad(1);
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
      onFileChange(e) {
        let ref = this;
        const file = e.target.files[0];
        this.url = URL.createObjectURL(file);
        var reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onloadend = function () {
          let base64data = reader.description;
          console.log(base64data);
          ref.assetModel.image = base64data;
        };
      },
      fetchAssetModelWiseInspections() {
        this.$http
          .get(`/api/assetModel/${this.$route.params.id}/inspections`, {
            headers: {
              Authorization: `Bearer ${this.$store.state.token}`
            }
          })
          .then(response => {
            this.inspections = response.data.data;
            console.log("inspections", this.inspections);
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
      eraseInspection(id) {
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
          .then(description => {
            if (description.value) {
              this.$http
                .delete(`/api/inspection/${id}`, {
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
                  self.fetchAssetModelWiseInspections(this.$route.params.id);
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
      onSelectManufacturer(selectedOption, id) {
        if (selectedOption) {
          console.log(selectedOption.id);
          this.assetModel.company_id = selectedOption.id;
        }
      },
      onSelectModelType(selectedOption, id) {
        if (selectedOption) {
          console.log(selectedOption.id);
          this.assetModel.model_type_id = selectedOption.id;
        }
      },
      //inspection datatable
      inspectionErase() {
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
          .then(description => {
            if (description.value) {
              this.$http
                .delete(
                  `/api/inspection/${this.inspection.selectedIds.join(",")}`,
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
                  this.inspectionLoad(1);
                  this.inspection.selectedIds = [];
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
      inspectionSortByColumn: function (s) {
        if (s === this.inspection.currentSortColumn) {
          this.inspection.currentSortDirection =
            this.inspection.currentSortDirection === "asc" ? "desc" : "asc";
        }
        this.inspection.currentSortColumn = s;
      },
      inspectionSetSelectedColumnSortIcon(type) {
        if (
          this.inspection.currentSortDirection == "asc" &&
          this.inspection.currentSortColumn == type
        )
          return "fa fa-arrow-down";
        else if (
          this.inspection.currentSortDirection == "desc" &&
          this.inspection.currentSortColumn == type
        )
          return "fa fa-arrow-down open";
        else return "fa fa-arrow-down open fa-disabled";
      },
      // Paginating the table data
      inspectionMakePagination(meta) {
        let pagination = {
          per_page: this.inspection.pagination.per_page,
          current_page: meta.current_page,
          last_page: meta.last_page,
          total: meta.total,
          from: meta.from,
          to: meta.to,
          perPageItems: this.inspection.pagination.perPageItems
        };
        this.inspection.pagination = pagination;
      },
      inspectionAllinspectionRowSelect() {
        var self = this;
        Vue.nextTick(function () {
          self.inspection.selectedIds = [];
          if (self.inspection.all) {
            self.sortedInspection.forEach(inspection => {
              self.inspection.selectedIds.push(inspection.id.toString());
            });
          } else {
            if (
              self.sortedInspection.length == self.inspection.selectedIds.length
            ) {
              self.inspection.selectedIds = [];
            }
          }
        });
      },
      inspectionRowSelect: function () {
        var self = this;
        Vue.nextTick(function () {
          if (
            self.sortedInspection.length == self.inspection.selectedIds.length
          ) {
            self.inspection.all = true;
          } else {
            self.inspection.all = false;
          }
        });
      },
      inspectionColumnSelect: function () {
        var self = this;
        Vue.nextTick(function () {
          console.log(self.inspection.selectedColumns);
        });
      },
      inspectionLoad(page) {
        NProgress.start();
        let url = `/api/inspections-datatable/${this.$route.params.id}`;
        let params = `?page=${page}&per_page=${
          this.inspection.pagination.per_page
          }&search=${this.inspection.search.global}`;
        this.$http
          .get(url + params, {
            headers: {
              Authorization: `Bearer ${this.$store.state.token}`
            }
          })
          .then(({ data }) => {
            console.log("inspections", data);
            this.inspections = data.data;
            NProgress.done();
            this.inspectionMakePagination(data.meta);
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
      inspectionPageChange(page) {
        if (page >= 1 && page <= this.inspection.pagination.last_page)
          this.inspectionLoad(page);
      },
      inspectionPerPageChanged(count) {
        this.inspection.pagination.per_page = count;
        this.inspectionLoad(1);
      },
      inspectionClearSearch() {
        if (this.inspection.search.global.length) {
          this.inspection.search.global = "";
          this.inspectionLoad(1);
        }
        this.inspection.search.description = "";
        this.inspection.search.frequency = "";
        this.inspection.search.group = "";
        this.inspection.search.comment = "";
        this.inspection.search.file = "";
        this.inspection.search.inspection_criteria = "";
      },
      inspectionOnSelectPageCount(selectedOption, id) {
        if (selectedOption) {
          console.log(selectedOption, id);
          this.inspection.pagination.per_page = selectedOption;
          this.inspectionLoad(1);
        }
      },
      inspectionCheckColumnVisibility(column) {
        return this.inspection.selectedColumns.indexOf(column) !== -1
          ? true
          : false;
      },
      //end inspection datatable
      // machineOption Datatable
      machineOptionErase() {
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
          .then(description => {
            if (description.value) {
              this.$http
                .delete(
                  `/api/machineOption/${this.machineOption.selectedIds.join(
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
                  this.machineOptionLoad(1);
                  this.machineOption.selectedIds = [];
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
      machineOptionSortByColumn: function (s) {
        if (s === this.machineOption.currentSortColumn) {
          this.machineOption.currentSortDirection =
            this.machineOption.currentSortDirection === "asc" ? "desc" : "asc";
        }
        this.machineOption.currentSortColumn = s;
      },
      machineOptionSetSelectedColumnSortIcon(type) {
        if (
          this.machineOption.currentSortDirection == "asc" &&
          this.machineOption.currentSortColumn == type
        )
          return "fa fa-arrow-down";
        else if (
          this.machineOption.currentSortDirection == "desc" &&
          this.machineOption.currentSortColumn == type
        )
          return "fa fa-arrow-down open";
        else return "fa fa-arrow-down open fa-disabled";
      },
      // Paginating the table data
      machineOptionMakePagination(meta) {
        let pagination = {
          per_page: this.machineOption.pagination.per_page,
          current_page: meta.current_page,
          last_page: meta.last_page,
          total: meta.total,
          from: meta.from,
          to: meta.to,
          perPageItems: this.machineOption.pagination.perPageItems
        };
        this.machineOption.pagination = pagination;
      },
      machineOptionAllmachineOptionRowSelect() {
        var self = this;
        Vue.nextTick(function () {
          self.machineOption.selectedIds = [];
          if (self.machineOption.all) {
            self.sortedMachineOption.forEach(machineOption => {
              self.machineOption.selectedIds.push(machineOption.id.toString());
            });
          } else {
            if (
              self.sortedMachineOption.length ==
              self.machineOption.selectedIds.length
            ) {
              self.machineOption.selectedIds = [];
            }
          }
        });
      },
      machineOptionRowSelect: function () {
        var self = this;
        Vue.nextTick(function () {
          if (
            self.sortedMachineOption.length ==
            self.machineOption.selectedIds.length
          ) {
            self.machineOption.all = true;
          } else {
            self.machineOption.all = false;
          }
        });
      },
      machineOptionColumnSelect: function () {
        var self = this;
        Vue.nextTick(function () {
          console.log(self.machineOption.selectedColumns);
        });
      },
      machineOptionLoad(page) {
        NProgress.start();
        let url = `/api/machine-Options-datatable/${this.$route.params.id}`;
        let params = `?page=${page}&per_page=${
          this.machineOption.pagination.per_page
          }&search=${this.machineOption.search.global}`;
        this.$http
          .get(url + params, {
            headers: {
              Authorization: `Bearer ${this.$store.state.token}`
            }
          })
          .then(({ data }) => {
            console.log("machineOptions", data);
            this.machineOptions = data.data;
            this.machineOptionMakePagination(data.meta);
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
      machineOptionPageChange(page) {
        if (page >= 1 && page <= this.machineOption.pagination.last_page)
          this.machineOptionLoad(page);
      },
      machineOptionPerPageChanged(count) {
        this.machineOption.pagination.per_page = count;
        this.machineOptionLoad(1);
      },
      machineOptionClearSearch() {
        if (this.machineOption.search.global.length) {
          this.machineOption.search.global = "";
          this.machineOptionLoad(1);
        }
        this.machineOption.search.option_name = "";
        this.machineOption.search.value = "";
      },
      machineOptionOnSelectPageCount(selectedOption, id) {
        if (selectedOption) {
          console.log(selectedOption, id);
          this.machineOption.pagination.per_page = selectedOption;
          this.machineOptionLoad(1);
        }
      },
      machineOptionCheckColumnVisibility(column) {
        return this.machineOption.selectedColumns.indexOf(column) !== -1
          ? true
          : false;
      },
      //machineOption datatable end
      addModelType() {
        this.$refs.assetModelTypeModal.show();
      },
      submitNewModelType() {
        this.modelType.created_by = this.$store.state.user.id;
        this.modelType.account_id = this.$store.state.user.account_id;
        this.$http
          .post(`/api/modelType`, this.modelType, {
            headers: {
              Authorization: `Bearer ${this.$store.state.token}`
            }
          })
          .then(response => {
            //alert("Model Type Added");
            this.fetchModelTypes();
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
      addNewCompany() {
        this.$refs.companyModal.show();
      },
      submitCompany() {
        this.company.created_by = this.$store.state.user.id;
        this.company.account_id = this.$store.state.user.account_id;
        this.$http
          .post(`/api/company`, this.company, {
            headers: {
              Authorization: `Bearer ${this.$store.state.token}`
            }
          })
          .then(response => {
            //alert("Company Added");
            this.fetchManufacturers();
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
      onInput({ number, isValid, country }) {
        console.log(number, isValid, country);
      },
      onSelectCompanyType(selectedOption, type) {
        if (selectedOption) {
          console.log(selectedOption.type);
          this.company.type = selectedOption.type;
        }
      },
      openFile(file){
				console.log(file)
				var win = window.open(`/api/download-file/${file}`, '_blank')
  				win.focus()
			}
    },
    computed: {
      filteredInspection: function () {
        var self = this;
        return this.inspections
          .filter(function (inspection) {
            return inspection.description
              ? inspection.description
                .toLowerCase()
                .indexOf(self.inspection.search.description.toLowerCase()) >= 0
              : true;
          })
          .filter(function (inspection) {
            return inspection.frequency
              ? inspection.frequency
                .toString()
                .indexOf(self.inspection.search.frequency.toString()) >= 0
              : true;
          })
          .filter(function (inspection) {
            return inspection.group
              ? inspection.group
                .toLowerCase()
                .indexOf(self.inspection.search.group.toLowerCase()) >= 0
              : true;
          })
          .filter(function (inspection) {
            return inspection.comment
              ? inspection.comment
                .toLowerCase()
                .indexOf(self.inspection.search.comment.toLowerCase()) >= 0
              : true;
          })
          .filter(function (inspection) {
            return inspection.inspection_criteria
              ? inspection.inspection_criteria
                .toLowerCase()
                .indexOf(self.inspection.search.inspection_criteria.toLowerCase()) >= 0
              : true;
          })
          .filter(function (inspection) {
            return inspection.file
              ? inspection.file
                .toLowerCase()
                .indexOf(self.inspection.search.file.toLowerCase()) >= 0
              : true;
          });
      },
      sortedInspection: function () {
        return this.filteredInspection.sort((a, b) => {
          let modifier = 1;
          if (this.inspection.currentSortDirection === "desc") modifier = -1;
          let temp = `.${this.inspection.currentSortColumn}`;
          if (eval(`a${temp}`) < eval(`b${temp}`)) return -1 * modifier;
          if (eval(`a${temp}`) > eval(`b${temp}`)) return 1 * modifier;
          return 0;
        });
      },
      InspectionPages() {
        let start = _.max([this.inspection.pagination.current_page - 2, 1]);
        let end = _.min([start + 5, this.inspection.pagination.last_page + 1]);
        start = _.max([end - 5, 1]);
        return _.range(start, end);
      },
      filteredMachineOption: function () {
        var self = this;
        return this.machineOptions
          .filter(function (machineOption) {
            return machineOption.option_name
              ? machineOption.option_name
                .toLowerCase()
                .indexOf(self.machineOption.search.option_name.toLowerCase()) >=
              0
              : true;
          })
          .filter(function (machineOption) {
            return machineOption.value
              ? machineOption.value
                .toLowerCase()
                .indexOf(self.machineOption.search.value.toLowerCase()) >= 0
              : true;
          });
      },
      sortedMachineOption: function () {
        return this.filteredMachineOption.sort((a, b) => {
          let modifier = 1;
          if (this.machineOption.currentSortDirection === "desc") modifier = -1;
          let temp = `.${this.machineOption.currentSortColumn}`;
          if (eval(`a${temp}`) < eval(`b${temp}`)) return -1 * modifier;
          if (eval(`a${temp}`) > eval(`b${temp}`)) return 1 * modifier;
          return 0;
        });
      },
      machineOptionPages() {
        let start = _.max([this.machineOption.pagination.current_page - 2, 1]);
        let end = _.min([start + 5, this.machineOption.pagination.last_page + 1]);
        start = _.max([end - 5, 1]);
        return _.range(start, end);
      }
    }
  };
</script>
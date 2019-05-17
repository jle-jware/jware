import Vue from 'vue'
import Router from 'vue-router'

// Containers
import Full from '../containers/Full'

// Views
import Dashboard from '../views/Dashboard'
import Charts from '../views/Charts'
import Widgets from '../views/Widgets'

// Views - Components
import Buttons from '../views/components/Buttons'
import SocialButtons from '../views/components/SocialButtons'
import Cards from '../views/components/Cards'
import Forms from '../views/components/Forms'
import Modals from '../views/components/Modals'
import Switches from '../views/components/Switches'
import Tables from '../views/components/Tables'

// Views - Icons
import FontAwesome from '../views/icons/FontAwesome'
import SimpleLineIcons from '../views/icons/SimpleLineIcons'

// Views - Pages
import Page404 from '../views/pages/Page404'
import Page500 from '../views/pages/Page500'
import Login from '../views/pages/Login'
import Register from '../views/pages/Register'
import Account from '../views/pages/Account'
import Company from '../views/pages/Company'


// Views - Dashboard
import MyDashboard from '../views/myDashboard/index'

// Views - Company
import CompanyForm from '../views/company/Form'
import CompanyList from '../views/company/list'
import CompanyNewList from '../views/company/list-working'
import CompanyxclForm from '../views/company/xcl'

// Views - Company Type
import companyTypeForm from '../views/companyType/Form'
import companyTypeList from '../views/companyType/list-working'

// Views - User
import UserForm from '../views/user/Form'
import UserList from '../views/user/List'
import NewUserForm from '../views/user/Forms'
import UserNewList from '../views/user/list-working'

// Views - Location
import LocationForm from '../views/location/Form'
import LocationList from '../views/location/List'

// Views - Help Center
import HelpCenterForm from '../views/helpCenter/Form'
import HelpCenterList from '../views/helpCenter/list-working'

// Views - Job Stat
import RoleTypeForm from '../views/roleType/Form'
import RoleTypeList from '../views/roleType/list-working'

// Views - Job Stat
import jobStatForm from '../views/jobStat/Form'
import JobStatList from '../views/jobStat/list-working'

// Views - Job Type
import JobTypeForm from '../views/jobType/Form'
import JobTypeList from '../views/jobType/list-working'

// Views - Job Priority
import JobPriorityForm from '../views/priority/Form'
import JobPriorityList from '../views/priority/list-working'

// Views - Result
import ResultForm from '../views/result/Form'
import ResultList from '../views/result/list-working'

// Views - Region
import RegionForm from '../views/region/Form'
import RegionList from '../views/region/list-working'

// Views - Machine Spec
import MachineSpecForm from '../views/machineSpec/Form'
import MachineSpecList from '../views/machineSpec/list-working'

// Views - Job
//import JobForm from '../views/job/Form'
import JobNewForm from '../views/job/NewForm'
import JobList from '../views/job/list-working'
import CloseJobList from '../views/job/close-job-list'
import JobView from '../views/job/view'
import JobSheetView from '../views/job/job-sheet-list'
import GalleryForm from '../views/gallery/Form'

// Views - Spare Job
import SpareJobForm from '../views/spareJob/Form'
import SpareNewJobForm from '../views/spareJob/NewForm'

// Views - Utilization Type
import UtilizationTypeForm from '../views/utilizationType/Form'
import UtilizationTypeList from '../views/utilizationType/list-working'

// Views - Asset utilization
import AssetUtilizationForm from '../views/assetUtilization/Form'
import AssetUtilizationNewForm from '../views/assetUtilization/NewForm'

// Views - Assets
import AssetForm from '../views/asset/Form'
import AssetList from '../views/asset/list-working'
import AssetWriteOffList from '../views/asset/list-write-off'
import AssetInspectionForm from '../views/asset/Form-inspection'
import AssetxclForm from '../views/asset/xcl'

// Views - Asset Model
import AssetModelForm from '../views/assetModel/Form'
import AssetModelList from '../views/assetModel/list-working'
// import MachineOptionForm from '../views/assetModel/Form-machine-option'
import AssetModelxclForm from '../views/assetModel/xcl'

// Views - Machine Option
import MachineOptionForm from '../views/machineOption/Form'
import MachineOptionList from '../views/machineOption/list-working'
import MachineOptionxclForm from '../views/machineOption/xcl'

// Views - Spare
import SpareForm from '../views/spare/Form'
import SpareList from '../views/spare/list-working'
import SparexclForm from '../views/spare/xcl'

// Views - Spare Type
import SpareTypeForm from '../views/spareType/Form'
import SpareTypeList from '../views/spareType/list-working'

// Views - Model Type
import ModelTypeForm from '../views/modelType/Form'
import ModelTypeList from '../views/modelType/list-working'

// Views - Asset Status
import AssetStatForm from '../views/assetStat/Form'
import AssetStatList from '../views/assetStat/list-working'

// Views - Milestone Type
import MilestoneTypeForm from '../views/milestoneType/Form'
import MilestoneTypeList from '../views/milestoneType/list-working'

// Views - Document
import DocumentForm from '../views/document/Form'
import DocumentList from '../views/document/list-working'

// Views - Document Type
import DocumentTypeForm from '../views/documentType/Form'
import DocumentTypeList from '../views/documentType/list-working'

// Views - Inspection
import InspectionForm from '../views/inspection/Form'
import InspectionList from '../views/inspection/List'

// Views - Inspection Criteria
import InspectionCriteriaForm from '../views/inspectionCriteria/Form'
import InspectionCriteriaList from '../views/inspectionCriteria/list-working'

// Views - Inspection Result
import InspectionResultList from '../views/inspectionResult/list-working'
import InspectionNextJobList from '../views/inspectionResult/list'

// Views - Milestone
import MilestoneForm from '../views/milestone/Form'
// import MilestoneList from '../views/milestone/List'



Vue.use(Router)

const router = new Router({
	mode: 'history', // Demo is living in GitHub.io, so required!
	linkActiveClass: 'active',
	scrollBehavior: () => ({
		y: 0
	}),
	routes: [{
		path: '/',
		redirect: '/dashboard',
		name: 'Home',
		component: Full,
		children: [
			{
				path: 'dashboard',
				name: 'My-Dashboard',
				component: MyDashboard,
				meta: {
					requireAuth: true
				}
			},
			//Job
			{
				path: 'jobs',
				redirect: '/jobs',
				name: 'Job List',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [{
					path: '/jobs',
					name: 'Job List',
					component: JobList
				},
				{
					path: '/close-jobs',
					name: 'Close Jobs',
					component: CloseJobList
				},
				{
					path: '/job-sheets',
					name: 'Job Sheets',
					component: JobSheetView
				},
				{
					path: '/job/create',
					name: 'Add Job',
					component: JobNewForm
				},
				// {
				// 	path: '/job/new/create',
				// 	name: 'Add new Job',
				// 	component: JobNewForm
				// },
				{
					path: '/job/:id/new',
					name: 'Edit new Job',
					component: JobNewForm
				},

				{
					path: '/job/:id',
					name: 'Edit Job',
					component: JobNewForm
				},
				{
					path: '/job/:id/view',
					name: 'View Job',
					component: JobView
				},
				{
					path: '/job/:id/create',
					name: 'Add-Job',
					component: JobNewForm,
				},
				{
					path: '/SpareJob/:id/create',
					name: 'Add-spare-Job',
					component: SpareJobForm,
				},
				{
					path: '/SpareJob/:id',
					name: 'Edit Spare Job',
					component: SpareJobForm
				},
				{
					path: '/SpareJob/:id/create/new',
					name: 'Add-new-spare-Job',
					component: SpareNewJobForm,
				},
				{
					path: '/SpareJob/:id/edit',
					name: 'Edit New Spare Job',
					component: SpareNewJobForm
				},
				{
					path: '/asset-utilization/create',
					name: 'Add-Asset-Utilization',
					component: AssetUtilizationForm,
				},
				{
					path: '/AssetUtilization/:id',
					name: 'Edit Asset utilization',
					component: AssetUtilizationForm
				},
				{
					path: '/AssetUtilization/:id/create/new',
					name: 'Add-New-Asset-Utilization',
					component: AssetUtilizationNewForm,
				},
				{
					path: '/AssetUtilization/:id/edit',
					name: 'Edit-New-Asset-Utilization',
					component: AssetUtilizationNewForm
				},
				{
					path: '/Gallery/:id/create',
					name: 'Add-New-Asset-Gallery',
					component: GalleryForm,
				},
				{
					path: '/Gallery/:id',
					name: 'Edit Gallery',
					component: GalleryForm
				},
				]
			},
					
			//Role Type
			{
				path: '/roleTypes/',
				redirect: '/roleTypes',
				name: 'Role Type List',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [{
					path: '/roleTypes',
					name: 'Role Type List',
					component: RoleTypeList
				},

				{
					path: '/roleType/create',
					name: 'Add Role Type',
					component: RoleTypeForm
				},

				{
					path: '/roleType/:id',
					name: 'Edit Role Type',
					component: RoleTypeForm
				},
				]
			},
			//Utilization Type
			{
				path: '/utilizationTypes/',
				redirect: '/utilizationTypes',
				name: 'Utilization Type List',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [{
					path: '/utilizationTypes',
					name: 'Utilization Type List',
					component: UtilizationTypeList
				},

				{
					path: '/utilizationType/create',
					name: 'Add Utilization Type',
					component: UtilizationTypeForm
				},

				{
					path: '/utilizationType/:id',
					name: 'Edit Utilization Type',
					component: UtilizationTypeForm
				},
				]
			},
			//Help Center
			{
				path: '/helpCenters/',
				redirect: '/helpCenters',
				name: 'Help Centers List',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [{
					path: '/helpCenters',
					name: 'Help Centers List',
					component: HelpCenterList
				},

				{
					path: '/helpCenter/create',
					name: 'Add Help Center',
					component: HelpCenterForm
				},

				{
					path: '/helpCenter/:id',
					name: 'Edit Help Center',
					component: HelpCenterForm
				},
				]
			},
			//Result
			{
				path: '/inspectionResultTypes/',
				redirect: '/inspectionResultTypes',
				name: 'Result List',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [{
					path: '/inspectionResultTypes',
					name: 'Result List',
					component: ResultList
				},

				{
					path: '/inspectionResultType/create',
					name: 'Add Result',
					component: ResultForm
				},

				{
					path: '/inspectionResultType/:id',
					name: 'Edit Result',
					component: ResultForm
				},
				]
			},
			//Inspection Result
			{
				path: '/inspectionResults/',
				redirect: '/inspectionResults',
				name: 'Inspection Result List',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [{
					path: '/inspectionResults',
					name: 'Inspection Result List',
					component: InspectionResultList
				},
				{
					path: '/inspection-job-results',
					name: 'Inspection Job List',
					component: InspectionNextJobList
				},		
				]
			},
		]
	},
	//company
	{
		path: '/',
		redirect: '/companies',
		name: 'Foundation',
		component: Full,
		children: [
			//Company
			{
				path: 'companies',
				redirect: '/companies',
				name: 'Companies',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/companies',
						name: 'Company List',
						component: CompanyNewList,
						meta: {
							requireAuth: true
						}
					},
					{
						path: '/company/create',
						name: 'Add Company',
						component: CompanyForm,
						meta: {
							requireAuth: true
						}
					},
					{
						path: '/company/:id',
						name: 'Edit Company',
						component: CompanyForm,
						meta: {
							requireAuth: true
						}
					},
					{
						path: '/companynewlist',
						name: 'Show Company',
						component: CompanyNewList,
						meta: {
							requireAuth: true
						}
					},
					{
						path: '/company/upload',
						name: 'Upload Company Excel Dump',
						component: CompanyxclForm
					},
				]
			},
		]
	},
	//User
	{
		path: '/',
		redirect: '/users',
		name: 'Foundation',
		component: Full,
		children: [
			//USERS
			{
				path: 'users',
				redirect: '/users',
				name: 'Users',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/users',
						name: 'Contact List',
						component: UserNewList,
						meta: {
							requireAuth: true
						}
					},
	
					{
						path: '/user/create',
						name: 'Add Contact',
						component: UserForm
					},
	
					{
						path: '/user/:id',
						name: 'Edit User',
						component: UserForm
					},
					
					//add user from company form
					{
						path: '/company/:id/user/create',
						name: 'Add-Contact',
						component: NewUserForm,
					},
					//edit user from company form
					{
						path: '/user/:id/edit',
						name: 'Edit-contact',
						component: NewUserForm,
					},
				]
			},
		]
	},
	//locations
	{
		path: '/',
		redirect: '/locations',
		name: 'Foundation',
		component: Full,
		children: [
			{
				path: 'locations',
				redirect: '/locations',
				name: 'Locations',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/locations',
						name: 'Location List',
						component: LocationList
					},
					{
						path: '/location/:id/create',
						name: 'Add Location',
						component: LocationForm,
					},
	
					{
						path: '/location/:id',
						name: 'Edit Location',
						component: LocationForm
					},
				]
			},
		]
	},
	

	//Asset
	{
		path: '/',
		redirect: '/assets',
		name: 'Asset',
		component: Full,
		children: [
			{
				path: 'assets',
				redirect: '/assets',
				name: 'Assets',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/assets',
						name: 'Asset List',
						component: AssetList
					},
					{
						path: '/asset/create',
						name: 'Add Asset',
						component: AssetForm
					},
					{
						path: '/asset/:id',
						name: 'Edit Asset',
						component: AssetForm
					},
					
					{
						path: '/milestone/:id/create',
						name: 'Add Milestone',
						component: MilestoneForm,
					},
					{
						path: '/milestone/:id',
						name: 'Edit Milestone',
						component: MilestoneForm,
					},
					{
						path: '/assetInspection/:id/create',
						name: 'Add Asset Inspection',
						component: AssetInspectionForm,
					},
					{
						path: '/assetInspection/:id',
						name: 'Edit Asset Inspection',
						component: AssetInspectionForm,
					},
					{
						path: '/upload',
						name: 'Upload Bulk Asset',
						component: AssetxclForm
					},
					{
						path: '/write-off-assets',
						name: 'Asset Write Off List',
						component: AssetWriteOffList
					},
				]
			},
		]
	},
	
	//spares
	{
		path: '/',
		redirect: '/spares',
		name: 'Asset',
		component: Full,
		children: [
			{
				path: 'spares',
				redirect: '/spares',
				name: 'Spares',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/spares',
						name: 'Spare List',
						component: SpareList
					},
	
					{
						path: '/spare/create',
						name: 'Add Spare',
						component: SpareForm
					},
	
					{
						path: '/spare/:id',
						name: 'Edit Spare',
						component: SpareForm
					},
					{
						path: '/spare/upload',
						name: 'Upload Spare Excel Dump',
						component: SparexclForm
					},
				]
			},
		]
	},
	//Documents
	{
		path: '/',
		redirect: '/documents',
		name: 'Asset',
		component: Full,
		children: [
			{
				path: 'documents',
				redirect: '/documents',
				name: 'Documents',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/documents',
						name: 'Document List',
						component: DocumentList
					},
	
					{
						path: '/document/create',
						name: 'Add Document',
						component: DocumentForm
					},
	
					{
						path: '/document/:id',
						name: 'Edit Document',
						component: DocumentForm
					},
				]
			},
		]
	},
	//Spare Types 
	{
		path: '/',
		redirect: '/spareTypes',
		name: 'Asset Settings',
		component: Full,
		children: [
			{
				path: 'spareTypes',
				redirect: '/spareTypes',
				name: 'spareTypes',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/spareTypes',
						name: 'Spare Type List',
						component: SpareTypeList
					},
	
					{
						path: '/spareType/create',
						name: 'Add Spare Type',
						component: SpareTypeForm
					},
	
					{
						path: '/spareType/:id',
						name: 'Edit Spare',
						component: SpareTypeForm
					},
				]
			},
		]
	},
	//Machine Spec
	{
		path: '/',
		redirect: '/machineSpecs',
		name: 'Machine Spec Lists',
		component: Full,
		children: [
			{
				path: 'machineSpecs',
				redirect: '/machineSpecs',
				name: 'machineSpecs',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/machineSpecs',
						name: 'Machine Spec List',
						component: MachineSpecList
					},
	
					{
						path: '/machineSpec/create',
						name: 'Add Machine Spec',
						component: MachineSpecForm
					},
	
					{
						path: '/machineSpec/:id',
						name: 'Edit Machine Spec',
						component: MachineSpecForm
					},
				]
			},
		]
	},
	//Inspections
	{
		path: '/',
		redirect: '/inspections',
		name: 'Asset Settings',
		component: Full,
		children: [
			{
				path: 'inspections',
				redirect: '/inspections',
				name: 'Inspections',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/inspections',
						name: 'Inspection List',
						component: InspectionList
					},
	
					{
						path: '/inspection/create',
						name: 'Add Inspection',
						component: InspectionForm
					},
					{
						path: '/inspection/:id/create',
						name: 'Add-Inspection',
						component: InspectionForm,
					},
	
					{
						path: '/inspection/:id',
						name: 'Edit Inspection',
						component: InspectionForm
					},
				]
			},
		]
	},
	//Inspection Criteria
	{
		path: '/',
		redirect: '/inspectionCriterias',
		name: 'Asset Settings',
		component: Full,
		children: [
			{
				path: 'inspectionCriterias',
				redirect: '/inspectionCriterias',
				name: 'Inspection Criterias',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/inspectionCriterias',
						name: 'Inspection Criteria List',
						component: InspectionCriteriaList
					},
	
					{
						path: '/inspectionCriteria/create',
						name: 'Add Inspection Criteria',
						component: InspectionCriteriaForm
					},
	
					{
						path: '/inspectionCriteria/:id',
						name: 'Edit Inspection Criteria',
						component: InspectionCriteriaForm
					},
				]
			},
		]
	},
	//Document Types
	{
		path: '/',
		redirect: '/documentTypes',
		name: 'Asset Settings',
		component: Full,
		children: [
			{
				path: 'documentTypes',
				redirect: '/documentTypes',
				name: 'Document Types',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/documentTypes',
						name: 'Document Type List',
						component: DocumentTypeList
					},
	
					{
						path: '/documentType/create',
						name: 'Add Document Type',
						component: DocumentTypeForm
					},
	
					{
						path: '/documentType/:id',
						name: 'Edit Document Type',
						component: DocumentTypeForm
					},
				]
			},
		]
	},
	//Asset Status
	{
		path: '/',
		redirect: '/assetStats',
		name: 'Asset Settings',
		component: Full,
		children: [
			{
				path: 'assetStats',
				redirect: '/assetStats',
				name: 'Asset Status',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/assetStats',
						name: 'Asset Status List',
						component: AssetStatList
					},
	
					{
						path: '/assetStat/create',
						name: 'Add Asset Status',
						component: AssetStatForm
					},
	
					{
						path: '/assetStat/:id',
						name: 'Edit Asset Status',
						component: AssetStatForm
					},
				]
			},
		]
	},
	//Milestone Types
	{
		path: '/',
		redirect: '/milestoneTypes',
		name: 'Milestone Types',
		component: Full,
		children: [
			{
				path: 'milestoneTypes',
				redirect: '/milestoneTypes',
				name: 'Milestone Types',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/milestoneTypes',
						name: 'Milestone Type List',
						component: MilestoneTypeList
					},
	
					{
						path: '/milestoneType/create',
						name: 'Add Milestone Type',
						component: MilestoneTypeForm
					},
	
					{
						path: '/milestoneType/:id',
						name: 'Edit Milestone Type',
						component: MilestoneTypeForm
					},
				]
			},
		]
	},
	//Company Type
	{
		path: '/',
		redirect: '/companyTypes',
		name: 'Asset Settings',
		component: Full,
		children: [
			{
				path: 'companyTypes',
				redirect: '/companyTypes',
				name: 'Company Types',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/companyTypes',
						name: 'Company Type List',
						component: companyTypeList
					},
	
					{
						path: '/companyType/create',
						name: 'Add Company Type',
						component: companyTypeForm
					},
	
					{
						path: '/companyType/:id',
						name: 'Edit Company Type',
						component: companyTypeForm
					},
				]
			},
		]
	},
	//Job Types
	{
		path: '/',
		redirect: '/jobTypes',
		name: 'Asset Settings',
		component: Full,
		children: [
			{
				path: 'jobTypes',
				redirect: '/jobTypes',
				name: 'Job Types',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/jobTypes',
						name: 'Job Type List',
						component: JobTypeList
					},
	
					{
						path: '/jobType/create',
						name: 'Add Job Type',
						component: JobTypeForm
					},
	
					{
						path: '/jobType/:id',
						name: 'Edit Job Type',
						component: JobTypeForm
					},
				]
			},
		]
	},
	//Job Status
	{
		path: '/',
		redirect: '/jobStats',
		name: 'Asset Settings',
		component: Full,
		children: [
			{
				path: 'jobStats',
				redirect: '/jobStats',
				name: 'Job Status',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/jobStats',
						name: 'Job Status List',
						component: JobStatList
					},

					{
						path: '/jobStat/create',
						name: 'Add Job Status',
						component: jobStatForm
					},

					{
						path: '/jobStat/:id',
						name: 'Edit Job Status',
						component: jobStatForm
					},
				]
			},
		]
	},
	//Region
	{
		path: '/',
		redirect: '/regions',
		name: 'Regions',
		component: Full,
		children: [
			{
				path: 'regions',
				redirect: '/regions',
				name: 'Regions',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/regions',
						name: 'Region List',
						component: RegionList
					},
	
					{
						path: '/region/create',
						name: 'Add Region',
						component: RegionForm
					},
	
					{
						path: '/region/:id',
						name: 'Edit Region',
						component: RegionForm
					},
				]
			},
		]
	},
	//Job Priority
	{
		path: '/',
		redirect: '/priorities',
		name: 'Asset Settings',
		component: Full,
		children: [
			{
				path: 'priorities',
				redirect: '/priorities',
				name: 'Job Priority',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/priorities',
						name: 'Job Priority List',
						component: JobPriorityList
					},
	
					{
						path: '/priority/create',
						name: 'Add Job Priority',
						component: JobPriorityForm
					},
	
					{
						path: '/priority/:id',
						name: 'Edit Job Priority',
						component: JobPriorityForm
					},
				]
			},
		]
	},
	//Model Types
	{
		path: '/',
		redirect: '/modelTypes',
		name: 'Asset Settings',
		component: Full,
		children: [
			{
				path: 'modelTypes',
				redirect: '/modelTypes',
				name: 'Model Type list',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/modelTypes',
						name: 'Model Type list',
						component: ModelTypeList
					},
	
					{
						path: '/modelType/create',
						name: 'Add Model Type',
						component: ModelTypeForm
					},
	
					{
						path: '/modelType/:id',
						name: 'Edit Model Type',
						component: ModelTypeForm
					},
				]
			},
		]
	},
	//Asset Model
	{
		path: '/',
		redirect: '/assetModels',
		name: 'Asset Settings',
		component: Full,
		children: [
			{
				path: 'assetModels',
				redirect: '/assetModels',
				name: 'Asset Models',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/assetModels',
						name: 'Asset Model List',
						component: AssetModelList
					},
	
					{
						path: '/assetModel/create',
						name: 'Add Asset Model',
						component: AssetModelForm
					},
	
					{
						path: '/assetModel/:id',
						name: 'Edit Asset Model',
						component: AssetModelForm
					},
					{
						path: '/assetModel/upload',
						name: 'Upload Asset Model Excel Dump',
						component: AssetModelxclForm
					},
					{
						path: '/machineOption/:id/create',
						name: 'Add Machine Option',
						component: MachineOptionForm,
					},
	
					{
						path: '/machineOption/:id',
						name: 'Edit Machine Option',
						component: MachineOptionForm
					},
				]
			},
		]
	},
	//Machine Option
	{
		path: '/',
		redirect: '/machineOptions',
		name: 'Machine Option List',
		component: Full,
		children: [
			{
				path: 'machineOptions',
				redirect: '/machineOptions',
				name: 'Machine Option List',
				component: {
					render(c) {
						return c('router-view')
					}
				},
				children: [
					{
						path: '/machineOptions',
						name: 'Machine Option List',
						component: MachineOptionList
					},
	
					{
						path: '/machineOption/create',
						name: 'Add Machine Option',
						component: MachineOptionForm
					},
	
					{
						path: '/machineOption/:id',
						name: 'Edit Machine Option',
						component: MachineOptionForm
					},
					{
						path: '/machineOption/upload',
						name: 'Upload Machine Option Excel Dump',
						component: MachineOptionxclForm
					},
				]
			},
		]
	},
	//pulic registration
	{
		path: '/',
		redirect: '/pages/p404',
		name: 'Pages',
		component: {
			render(c) {
				return c('router-view')
			}
		},
		children: [{
			path: '404',
			name: 'Page404',
			component: Page404
		},
		{
			path: '500',
			name: 'Page500',
			component: Page500
		},
		{
			path: 'login',
			name: 'Login',
			component: Login,
			meta: {
				requireAuth: false
			}
		},
		{
			path: 'register',
			name: 'Register',
			component: Register,
			meta: {
				requireAuth: false
			}
		},
		{
			path: 'account',
			name: 'Account',
			component: Account,
			meta: {
				requireAuth: false
			}
		},
		{
			path: 'company',
			name: 'Company',
			component: Company,
			meta: {
				requireAuth: false
			}
		}
		]
	}
	]
})
router.beforeEach((to, from, next) => {
	setTimeout(function () {
		setClassForParentMenu();
	}, 100);
	setTimeout(function () {
		headerColorChanger();
	}, 200);
	return next();
});
function setClassForParentMenu() {
	// remove class
	var els = document.querySelectorAll('.nav-item.nav-dropdown');
	for (var i = 0; i < els.length; i++) {
		els[i].classList.remove('open');
		els[i].classList.remove('active');
	}
	var menuActive = document.querySelector(".nav-item .router-link-exact-active");
	if (menuActive != null) {
		menuActive.parentElement.parentElement.parentElement.className += " open active";
	}
}
function headerColorChanger() {
	let menuActive = document.querySelector(".nav-item.nav-dropdown.active.open");
	if (menuActive != null) {
		let menuActiveBackground = window.getComputedStyle(menuActive, null).getPropertyValue("background");
		let breadcrumb = document.querySelector(".main .breadcrumb");
		breadcrumb.style.background = menuActiveBackground;
	}
}

export default router
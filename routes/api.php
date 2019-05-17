<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group(['middleware' => 'jwt.auth'], function ($router) {

    # UserController Routes
    // List Users
    Route::get('users', 'UserController@index');
    // List Users
    Route::get('owner-users', 'UserController@getUsers');
    //  get one user
    Route::get('user/{id}', 'UserController@show');
    // Create new  user
    Route::post('user', 'UserController@store');
    // Update  user
    Route::put('user', 'UserController@store');
    // Delete  user
    Route::delete('user/{id}', 'UserController@destroy');
    // Get User By Company ID
    Route::get('company/{id}/users', 'UserController@companyWiseUser');
    // Get Technician By Company ID
    Route::get('company/{id}/technicians', 'UserController@companyWiseTechnician');
    // List companies
    Route::get('users-datatable/{companyId}', 'UserController@getDataForDataTable');
    // List users
    Route::get('users-datatable/', 'UserController@getDataForUserDataTable');

    # RoleTypeController Routes
    // List role types
    Route::get('roleTypes', 'RoleTypeController@index');
    //  get one role type
    Route::get('roleType/{id}', 'RoleTypeController@show');
    // Create new  role type
    Route::post('roleType', 'RoleTypeController@store');
    // Update  role type
    Route::put('roleType', 'RoleTypeController@store');
    // Delete  role type
    Route::delete('roleType/{id}', 'RoleTypeController@destroy');
    // List Role Types
    Route::get('roleTypes-datatable/', 'RoleTypeController@getDataForDataTable');

    # InspectionController Routes
    // List inspections
    Route::get('inspections', 'InspectionController@index');
    //  get one inspection
    Route::get('inspection/{id}', 'InspectionController@show');
    // Create new  inspection
    Route::post('inspection', 'InspectionController@store');
    // Update  inspection
    Route::put('inspection', 'InspectionController@store');
    // Delete
    Route::delete('inspection/{id}', 'InspectionController@destroy');

    // get assetmodel wise  inspection
    Route::get('assetModel/{id}/inspections', 'InspectionController@assetModelWiseInspection');

    // List Inspections
    Route::get('inspections-datatable/{assetModelId}', 'InspectionController@getDataForDataTable');

        # AssetInspectionController Routes
    // List inspections
    Route::get('assetInspections', 'AssetInspectionController@index');
    //  get one inspection
    Route::get('assetInspection/{id}', 'AssetInspectionController@show');
    // Create new  inspection
    Route::post('assetInspection', 'AssetInspectionController@store');
    // Update  inspection
    Route::put('assetInspection', 'AssetInspectionController@store');
    // Delete
    Route::delete('assetInspection/{id}', 'AssetInspectionController@destroy');
    // get asset wise  inspection
    Route::get('asset-wise-inspection/{id}', 'AssetInspectionController@assetlWiseAssetInspection');
    // List Inspections
    Route::get('assetInspections-datatable/{assetId}', 'AssetInspectionController@getDataForDataTable');

    # MachineOptionController Routes
    // List machine options
    Route::get('machineOptions', 'MachineOptionController@index');
    //  get one Machine Option
    Route::get('machineOption/{id}', 'MachineOptionController@show');
    // Create new  Machine Option
    Route::post('machineOption', 'MachineOptionController@store');
    // Update  Machine Option
    Route::put('machineOption', 'MachineOptionController@store');
    // Delete
    Route::delete('machineOption/{id}', 'MachineOptionController@destroy');
    // List Machine Options
    Route::get('machine-Options-datatable/{assetModelId}', 'MachineOptionController@getDataForDataTable');
    // List Machine Options with out ID
    Route::get('machine-Options-datatable-without-id/', 'MachineOptionController@getDataForMachineOptionDataTable');
    // List machine options
    Route::get('asset-model-wise-machine-options/{id}', 'MachineOptionController@getByAssetModel');
    

     # InspectionResultController Routes
    // List inspection results
    Route::get('inspectionResults', 'InspectionResultController@index');
    //  get one Machine Option
    Route::get('inspectionResult/{id}', 'InspectionResultController@show');
    // Create new  Machine Option
    Route::post('inspectionResult', 'InspectionResultController@store');
    // Update  Machine Option
    Route::put('inspectionResult', 'InspectionResultController@store');
    // Delete
    Route::delete('inspectionResult/{id}', 'InspectionResultController@destroy');
    //  get one inspction result
    Route::get('job-wise-inspection-result/{id}', 'InspectionResultController@getByJob');
    // List Inspection Results
    Route::get('inspectionResult-datatable', 'InspectionResultController@getDataForDataTable');
    // List Inspection Results where user will create job 
    Route::get('inspectionResult-job-datatable', 'InspectionResultController@getDataForDataTableInspectionJob');

    # CompanyController Routes
    // List companies
    Route::get('companies', 'CompanyController@index');
    //  get one company
    Route::get('company/{id}', 'CompanyController@show');
    // Create new  company
    Route::post('company', 'CompanyController@store');
    // Update  company
    Route::put('company', 'CompanyController@store');
    // Delete  company
    Route::delete('company/{id}', 'CompanyController@destroy');
    // user wise company
    Route::get('userCompany/{id}', 'UserController@userWiseCompany');
    // List  company by type = distributor
    Route::get('distributors', 'CompanyController@getDistributors');
    // List  company by type = customer
    Route::get('customers', 'CompanyController@getCustomers');
    // List companies
    Route::get('companies-datatable', 'CompanyController@getDataForDataTable');
    // List  company by type = contractor
    Route::get('contractors', 'CompanyController@getContractors');
    // List  company by type = Manufacturer
    Route::get('manufacturers', 'CompanyController@getManufacturers');
    // List Assets
    Route::get('company-by-search/{searchTerm}', 'CompanyController@getCompanyBySearch');

    # AccountController Routes
    // List accounts
    Route::get('accounts', 'AccountController@index');
    //  get one account
    Route::get('account/{id}', 'AccountController@show');
    // Create new account
    Route::post('account', 'AccountController@store');
    // Update account
    Route::put('account', 'AccountController@store');
    // Delete account
    Route::delete('account/{id}', 'AccountController@destroy');

    # AssetController Routes
    // List Assets
    Route::get('assets', 'AssetController@index');
    //  get one Asset
    Route::get('asset/{id}', 'AssetController@show');
    // Create new  Asset
    Route::post('asset', 'AssetController@store');
    // Update  Asset
    Route::put('asset', 'AssetController@store');
    // Delete  Asset
    Route::delete('asset/{id}', 'AssetController@destroy');
    //  get one company by asset id
    Route::get('asset/{id}/company', 'AssetController@getAssetWiseCompany');
    // List companies
    Route::get('assets-datatable', 'AssetController@getDataForDataTable');
    // List assets by write off
    Route::get('assets-by-write-off-datatable', 'AssetController@getWriteOffDataForDataTable');
    // List assets
    Route::get('asset-by-company/{id}', 'AssetController@getDataForDataTable');
    // Upload Excel
    Route::post('asset-upload-file', 'AssetController@storeUploadData');
    // Check unique asset or not
    Route::get('check-asset-serial/{serial}', 'AssetController@checkSerial');
    //  get asset count by status
    Route::get('asset-by-status', 'AssetController@getAssetBystatus');
    //  get asset by Placed
    Route::get('asset-by-placed', 'AssetController@getAssetByPlacedStatus');
    //  get asset by status for job form
    Route::get('asset-by-status-for-job-form', 'AssetController@getAssetBystatusForJobForm');
    // List Assets
    Route::get('assets-by-search/{searchTerm}', 'AssetController@getAssetBySearch');

    # MilestoneController Routes
    // List Milestones
    Route::get('milestones', 'MilestoneController@index');
    //  get one Milestone
    Route::get('milestone/{id}', 'MilestoneController@show');
    // Create new  Milestone
    Route::post('milestone', 'MilestoneController@store');
    // Update  Milestone
    Route::put('milestone', 'MilestoneController@store');
    // Delete  Milestone
    Route::delete('milestone/{id}', 'MilestoneController@destroy');
    // Get  asset wise milestone
    Route::get('asset/{id}/milestones', 'MilestoneController@assetWiseMilestoneists');
    // List jobs by asset id
    Route::get('milestones-datatable/{jobId}', 'MilestoneController@getDataForDataTable');
    // List milestone by asset id
    Route::get('milestones-datatable-by-asset/{assetId}', 'MilestoneController@getDataForDataTableByAsset');
    

    # Asset ModelController Routes
    // List Asset Models
    Route::get('assetModels', 'AssetModelController@index');
    //  get one Asset Model
    Route::get('assetModel/{id}', 'AssetModelController@show');
    // Create new  Asset Model
    Route::post('assetModel', 'AssetModelController@store');
    // Update  Asset Model
    Route::put('assetModel', 'AssetModelController@store');
    // Delete  Asset Model
    Route::delete('assetModel/{id}', 'AssetModelController@destroy');
    // List assetModels
    Route::get('assetModels-datatable', 'AssetModelController@getDataForDataTable');
    // Upload Excel
    Route::post('asset-model-upload-file', 'AssetModelController@storeUploadData');

    # AssetStatController Routes
    // List Asset Stat
    Route::get('assetStats', 'AssetStatController@index');
    //  get one job priority
    Route::get('assetStat/{id}', 'AssetStatController@show');
    // Create new Job priority
    Route::post('assetStat', 'AssetStatController@store');
    // Update job  priority
    Route::put('assetStat', 'AssetStatController@store');
    // Delete Job  priority
    Route::delete('assetStat/{id}', 'AssetStatController@destroy');
    // List Priority
    Route::get('assetStats-datatable', 'AssetStatController@getDataForDataTable');

      # Utilization Type Routes
    // List Utilization Type Lists
    Route::get('utilizationTypes', 'UtilizationTypeController@index');
    //  get one job priority
    Route::get('utilizationType/{id}', 'UtilizationTypeController@show');
    // Create new Job priority
    Route::post('utilizationType', 'UtilizationTypeController@store');
    // Update job  priority
    Route::put('utilizationType', 'UtilizationTypeController@store');
    // Delete Job  priority
    Route::delete('utilizationType/{id}', 'UtilizationTypeController@destroy');
    // List Priority
    Route::get('utilizationTypes-datatable', 'UtilizationTypeController@getDataForDataTable');

    # MilestoneTypeController Routes
    // List Milestone Types
    Route::get('milestoneTypes', 'MilestoneTypeController@index');
    //  get one Milestone Type
    Route::get('milestoneType/{id}', 'MilestoneTypeController@show');
    // Create new Milestone Type
    Route::post('milestoneType', 'MilestoneTypeController@store');
    // Update Milestone Type
    Route::put('milestoneType', 'MilestoneTypeController@store');
    // Delete Milestone Type
    Route::delete('milestoneType/{id}', 'MilestoneTypeController@destroy');
    // List Milestone Types
    Route::get('milestoneTypes-datatable', 'MilestoneTypeController@getDataForDataTable');

    # ModelTypeController Routes
    // List model types
    Route::get('modelTypes', 'ModelTypeController@index');
    //  get one model type
    Route::get('modelType/{id}', 'ModelTypeController@show');
    // Create new  model type
    Route::post('modelType', 'ModelTypeController@store');
    // Update  model type
    Route::put('modelType', 'ModelTypeController@store');
    // Delete  model type
    Route::delete('modelType/{id}', 'ModelTypeController@destroy');
    // List model types
    Route::get('modelTypes-datatable', 'ModelTypeController@getDataForDataTable');

    # GalleryController Routes
    // List model types
    Route::get('galleries', 'GalleryController@index');
    //  get one model type
    Route::get('gallery/{id}', 'GalleryController@show');
    // Create new  model type
    Route::post('gallery', 'GalleryController@store');
    // Update  model type
    Route::put('gallery', 'GalleryController@store');
    // Delete  model type
    Route::delete('gallery/{id}', 'GalleryController@destroy');
    // show asset wise gallery
    Route::get('assetwise-gallery/{id}', 'GalleryController@assetWiseGallery');
    // List galleries by job id for datatable
    Route::get('galleries-datatable/{jobId}', 'GalleryController@getDataForDataTable');

    # MachineSpecController Routes
    // List machine Specs
    Route::get('machineSpecs', 'MachineSpecController@index');
    //  get one machine Spec
    Route::get('machineSpec/{id}', 'MachineSpecController@show');
    // Create new  machine Spec
    Route::post('machineSpec', 'MachineSpecController@store');
    // Update  machine Spec
    Route::put('machineSpec', 'MachineSpecController@store');
    // Delete  machine Spec
    Route::delete('machineSpec/{id}', 'MachineSpecController@destroy');
    // List machine Specs
    Route::get('machineSpecs-datatable', 'MachineSpecController@getDataForDataTable');
    // List of serial number by asset model id
    Route::get('machine-specs-serial/{id}', 'MachineSpecController@assetModelWiseSerial');
    // List of serial number by asset model id
    Route::get('machine-specs-serial-all/{id}', 'MachineSpecController@assetModelWiseSerialAll');
    //  get bmb number
    Route::get('bmb-number', 'MachineSpecController@getBmbNumber');

    # JobStatController Routes
    // List Job Status
    Route::get('jobStats', 'JobStatController@index');
    //  get one Job status
    Route::get('jobStat/{id}', 'JobStatController@show');
    // Create new  Job status
    Route::post('jobStat', 'JobStatController@store');
    // Update  Job status
    Route::put('jobStat', 'JobStatController@store');
    // Delete  Job status
    Route::delete('jobStat/{id}', 'JobStatController@destroy');
    // List model types
    Route::get('jobStats-datatable', 'JobStatController@getDataForDataTable');

    # JobController Routes
    // List job
    Route::get('jobs', 'JobController@index');
    //  get one job
    Route::get('job/{id}', 'JobController@show');
    // Create new  job
    Route::post('job', 'JobController@store');
    // Update  job
    Route::put('job', 'JobController@store');
    // Delete  job
    Route::delete('job/{id}', 'JobController@destroy');
    // Get  asset wise job
    Route::get('asset/{id}/jobs', 'JobController@assetWiseJobLists');
    // List jobs
    Route::get('jobs-datatable', 'JobController@getDataForDataTable');
    // List jobs by asset id
    Route::get('job-list-by-assetid-datatable/{assetId}', 'JobController@getJobDataForDataTable');
    // List jobs by job status close
    Route::get('job-list-by-job-status', 'JobController@getClosedJobDataForDataTable');
    // send email
    Route::get('job-send-email/{id}/{email}/{description}', 'JobController@sendMail');
    // send email when finished
    Route::post('job-finished-send-email', 'JobController@sendFinishedJobMail');
    //  get one job
    Route::get('job-number', 'JobController@getJobNumber');
    //  get job count
    Route::get('job-count', 'JobController@getJobCount');
    //  get job count by job type
    Route::get('job-count-by-type', 'JobController@getJobCountByType');
    // List machine options
    Route::get('machine-options-by-asset/{id}', 'JobController@getMachineOptionsByAsset');
    //  on page load
    Route::get('job-page-load/{assetId}', 'JobController@onPageLoad');
    //  create inspection job 
    Route::post('create-inspection-job', 'JobController@createInspectionJob');
    // List jobs
    Route::get('job-sheets-datatable', 'JobSheetController@getDataForDataTable');

    # JobSheetController Routes
    // List job Sheet
    Route::get('jobSheet', 'JobSheetController@index');
    //  get one job Sheet
    Route::get('jobSheet/{id}', 'JobSheetController@show');
    // Create new  job Sheet
    Route::post('jobSheet', 'JobSheetController@store');
    // Update  job Sheet
    Route::put('jobSheet', 'JobSheetController@store');
    // Delete  job Sheet
    Route::delete('jobSheet/{id}', 'JobSheetController@destroy');
    // List jobSheets
    Route::get('job-sheet-datatable', 'JobSheetController@getDataForDataTable');
    //  get one job Sheet
    Route::get('job-wise-job-sheet/{id}', 'JobSheetController@getByJob');
    //  get one job Sheet
    Route::get('check-second-trip/{id}', 'JobSheetController@checkSecondTrip');
    // send email from first job sheet
    Route::get('first-job-sheet-send-email/{id}/{email}/{description}', 'JobSheetController@sendMail');

    # JobCloseController Routes
    // List job Sheet
    Route::get('jobCloses', 'JobCloseController@index');
    //  get one job Sheet
    Route::get('jobClose/{id}', 'JobCloseController@show');
    // Create new  job Sheet
    Route::post('jobClose', 'JobCloseController@store');
    // Update  job Sheet
    Route::put('jobClose', 'JobCloseController@store');
    // Delete  job Sheet
    Route::delete('jobClose/{id}', 'JobCloseController@destroy');
    //  get one job Sheet
    Route::get('job-wise-job-close/{id}', 'JobCloseController@getByJob');

    # AssetUtilizationController Routes
    // List Asset Utilization
    Route::get('assetUtilizations', 'AssetUtilizationController@index');
    //  get one Asset Utilization
    Route::get('assetUtilization/{id}', 'AssetUtilizationController@show');
    // Create new  Asset Utilization
    Route::post('assetUtilization', 'AssetUtilizationController@store');
    // Update  Asset Utilization
    Route::put('assetUtilization', 'AssetUtilizationController@store');
    // Delete  Asset Utilization
    Route::delete('assetUtilization/{id}', 'AssetUtilizationController@destroy');
    //  get one Asset Utilization
    Route::get('job-wise-asset-utilization/{id}', 'AssetUtilizationController@getByJob');
    // List asset Utilization by asset id for datatable
    Route::get('assetUtilizations-datatable/{assetID}', 'AssetUtilizationController@getDataForDataTable');
    // List asset Utilization by job id for datatable
    Route::get('assetUtilizations-job-datatable/{jobID}', 'AssetUtilizationController@getJobDataForDataTable');

    # JobTypeController Routes
    // List job types
    Route::get('jobTypes', 'JobTypeController@index');
    //  get one job type
    Route::get('jobType/{id}', 'JobTypeController@show');
    // Create new  job type
    Route::post('jobType', 'JobTypeController@store');
    // Update  job type
    Route::put('jobType', 'JobTypeController@store');
    // Delete  job type
    Route::delete('jobType/{id}', 'JobTypeController@destroy');
    // List spares
    Route::get('jobTypes-datatable', 'JobTypeController@getDataForDataTable');

     # HelpCenterController Routes
    // List job types
    Route::get('helpCenter', 'HelpCenterController@index');
    //  get one job type
    Route::get('helpCenter/{id}', 'HelpCenterController@show');
    // Create new  job type
    Route::post('helpCenter', 'HelpCenterController@store');
    // Update  job type
    Route::put('helpCenter', 'HelpCenterController@store');
    // Delete  job type
    Route::delete('helpCenter/{id}', 'HelpCenterController@destroy');
    // List spares
    Route::get('help-centers-datatable', 'HelpCenterController@getDataForDataTable');

    # PriorityController Routes
    // List Job priorities
    Route::get('priorities', 'PriorityController@index');
    //  get one job priority
    Route::get('priority/{id}', 'PriorityController@show');
    // Create new Job priority
    Route::post('priority', 'PriorityController@store');
    // Update job  priority
    Route::put('priority', 'PriorityController@store');
    // Delete Job  priority
    Route::delete('priority/{id}', 'PriorityController@destroy');
    // List Priority
    Route::get('priorities-datatable', 'PriorityController@getDataForDataTable');

    # RegionController Routes
    // List Regions
    Route::get('regions', 'RegionController@index');
    //  get one region
    Route::get('region/{id}', 'RegionController@show');
    // Create new region
    Route::post('region', 'RegionController@store');
    // Update region
    Route::put('region', 'RegionController@store');
    // Delete region
    Route::delete('region/{id}', 'RegionController@destroy');
    // List Regions
    Route::get('regions-datatable', 'RegionController@getDataForDataTable');

     # ResultController Routes
    // List Results
    Route::get('inspectionResultTypes', 'InspectionResultTypeController@index');
    //  get one Result
    Route::get('inspectionResultType/{id}', 'InspectionResultTypeController@show');
    // Create new Result
    Route::post('inspectionResultType', 'InspectionResultTypeController@store');
    // Update Result
    Route::put('inspectionResultType', 'InspectionResultTypeController@store');
    // Delete Result
    Route::delete('inspectionResultType/{id}', 'InspectionResultTypeController@destroy');
    // List Results By Type Inspection
    Route::get('inspectionResultTypes', 'InspectionResultTypeController@getInspectionResult');
    // List Result
    Route::get('inspectionResultTypes-datatable', 'InspectionResultTypeController@getDataForDataTable');

        # InspectionCriteriaController Routes
    // List Job inspection Criteria
    Route::get('inspectionCriterias', 'InspectionCriteriaController@index');
    //  get one inspection Criteria
    Route::get('inspectionCriteria/{id}', 'InspectionCriteriaController@show');
    // Create new inspection Criteria
    Route::post('inspectionCriteria', 'InspectionCriteriaController@store');
    // Update inspection Criteria
    Route::put('inspectionCriteria', 'InspectionCriteriaController@store');
    // Delete inspection Criteria
    Route::delete('inspectionCriteria/{id}', 'InspectionCriteriaController@destroy');
    // List Priority
    Route::get('inspectionCriterias-datatable', 'InspectionCriteriaController@getDataForDataTable');

    # SpareController Routes
    // List Spare
    Route::get('spares', 'SpareController@index');
    //  get one spare type
    Route::get('spare/{id}', 'SpareController@show');
    // Create new  spare type
    Route::post('spare', 'SpareController@store');
    // Update  spare type
    Route::put('spare', 'SpareController@store');
    // Delete  spare type
    Route::delete('spare/{id}', 'SpareController@destroy');
    // Get  spare by asset id
    Route::get('asset-model/{id}/spares', 'SpareController@getSpareByAssetModel');
    // List spares
    Route::get('spares-datatable', 'SpareController@getDataForDataTable');
    // Upload Excel
    Route::post('spare-upload-file', 'SpareController@storeUploadData');

    # SpareJobController Routes
    // List Spare Jobs
    Route::get('spareJobs', 'SpareJobController@index');
    //  get one Spare Job
    Route::get('spareJob/{id}', 'SpareJobController@show');
    // Create new  Spare Job
    Route::post('spareJob', 'SpareJobController@store');
    // Update  Spare Job
    Route::put('spareJob', 'SpareJobController@store');
    // Delete  Spare Job
    Route::delete('spareJob/{id}', 'SpareJobController@destroy');
    // Get  spare job by job id for table
    Route::get('job/{id}/spareJobs', 'SpareJobController@SpareJobListByJobID');
    // List spare jobs by job id for datatable
    Route::get('spareJobs-datatable/{jobId}', 'SpareJobController@getDataForDataTable');

    # SpareTypeController Routes
    // List Spare Types
    Route::get('spareTypes', 'SpareTypeController@index');
    //  get one spare type
    Route::get('spareType/{id}', 'SpareTypeController@show');
    // Create new  spare type
    Route::post('spareType', 'SpareTypeController@store');
    // Update  spare type
    Route::put('spareType', 'SpareTypeController@store');
    // Delete  spare type
    Route::delete('spareType/{id}', 'SpareTypeController@destroy');
    // List spare types
    Route::get('spareTypes-datatable', 'SpareTypeController@getDataForDataTable');

    # LocationController Routes
    // List Job Status
    Route::get('locations', 'LocationController@index');
    //  get one location
    Route::get('location/{id}', 'LocationController@show');
    // Create new  location
    Route::post('location', 'LocationController@store');
    // Update  location
    Route::put('location', 'LocationController@store');
    // Delete  location
    Route::delete('location/{id}', 'LocationController@destroy');
    // get company wise  location
    Route::get('company/{id}/locations', 'LocationController@companyWiseLocation');
    // List companies
    Route::get('locations-datatable/{companyId}', 'LocationController@getDataForDataTable');

    # DocumentController Routes
    // List Documents
    Route::get('documents', 'DocumentController@index');
    //  get one document
    Route::get('document/{id}', 'DocumentController@show');
    // Create new  document
    Route::post('document', 'DocumentController@store');
    // Update  document
    Route::put('document', 'DocumentController@store');
    // Delete  document
    Route::delete('document/{id}', 'DocumentController@destroy');
    // List jobSheets
    Route::get('documents-datatable', 'DocumentController@getDataForDataTable');

    Route::post('upload-file', 'DocumentController@uploadFile');

    // Get  document by asset model id
    Route::get('document-by-asset-model-id/{id}', 'DocumentController@getDocumentByAssetModel');

    # Company Type Controller Routes
    // List Company Type
    Route::get('companyTypes', 'CompanyTypeController@index');
    //  get one company type
    Route::get('companyType/{id}', 'CompanyTypeController@show');
    // Create new  company type
    Route::post('companyType', 'CompanyTypeController@store');
    // Update  company type
    Route::put('companyType', 'CompanyTypeController@store');
    // Delete  company type
    Route::delete('companyType/{id}', 'CompanyTypeController@destroy');
    // List company types
    Route::get('companyTypes-datatable', 'CompanyTypeController@getDataForDataTable');

    # DocumentTypeController Routes
    // List Document Types
    Route::get('documentTypes', 'DocumentTypeController@index');
    //  get one Document Type
    Route::get('documentType/{id}', 'DocumentTypeController@show');
    // Create new  Document Type
    Route::post('documentType', 'DocumentTypeController@store');
    // Update  Document Type
    Route::put('documentType', 'DocumentTypeController@store');
    // Delete  Document Type
    Route::delete('documentType/{id}', 'DocumentTypeController@destroy');
    // List Document Types
    Route::get('documentTypes-datatable', 'DocumentTypeController@getDataForDataTable');

    # Notification Route
    Route::post('notification/get', 'NotificationController@get');
    Route::post('notification/read', 'NotificationController@read');
});
Route::post('register', 'AuthController@register');
// Create new account
Route::post('account', 'AccountController@store');
// Create new  company
Route::post('company', 'CompanyController@store');
// Check unique company or not
Route::get('check-company-name/{name}', 'CompanyController@checkName');
// Update  company
Route::put('company', 'CompanyController@store');
// Upload Excel
Route::post('company-upload-file', 'CompanyController@storeUploadData');

Route::get('/download-file/{file}', function ($file) {
    return response()->download(public_path('uploads/' . $file));
});

Route::get('/rpo-download/{id}', 'JobController@generateRPO');
Route::get('/job-sheet-download/{id}', 'JobSheetController@generateJobSheetPDF');
 // Editor Image Upload
Route::post('editor-images-upload', 'JobController@postImageUpload');

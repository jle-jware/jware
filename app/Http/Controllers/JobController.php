<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\SpareJob;
use App\Asset;
use App\Http\Resources\JobResource;
use App\Http\Resources\JobDatatableResource;
use App\User;
use App\Helpers\Notify;
use Mail;
use DB;
use Carbon\Carbon;
use PDF;
use App\Transformers\JobTransformer;
use App\MachineOption;

class JobController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get jobs
        $jobs = Job::orderBy('created_at', 'desc')->get();

        // Return collection of jobs as a resource
        return JobResource::collection($jobs);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $job = $request->isMethod('put') ? Job::findOrFail($request->id) : new Job;
        $job->id = $request->input('id');
        $job->account_id = $request->input('account_id');
        $job->company_id = $request->input('company_id');
        $job->user_id = $request->input('user_id');
        $job->asset_id = $request->input('asset_id');
        $job->contractor_id = $request->input('contractor_id');
        $job->location_id = $request->input('location_id');
        $job->job_type_id = $request->input('job_type_id');
        $job->cost_labour = $request->input('cost_labour');
        $job->material_labour = $request->input('material_labour');
        $job->hours = $request->input('hours');
        $job->job_stat_id = $request->input('job_stat_id');
        $job->job_number = $request->isMethod('put') ? $request->input('job_number') : $this->getJobNumber();
        $job->inspection_description_id = $request->input('inspection_description_id');
        $job->job_raised_date = Carbon::parse($request->input('job_raised_date'));
        $job->required_date = Carbon::parse( $request->input('required_date'));
        $job->comment = $request->input('comment');
        $job->description = $request->input('description');
        $job->machine_option = $request->input('machine_option');
        $job->asset_model_id = $request->input('asset_model_id');
        $job->has_optional_contact = $request->input('has_optional_contact');
        $job->contact_person = $request->input('contact_person');
        $job->contact_number = $request->input('contact_number');
        $job->priority_id = $request->input('priority_id');
        $job->inspection_id = $request->input('inspection_id');
        $job->admin_close_out = $request->input('admin_close_out');
        $job->technical_manager_close_out = $request->input('technical_manager_close_out');
        $job->review = $request->input('review');
        $job->send_email = $request->input('send_email');
        $job->close_by_admin = $request->input('close_by_admin');
        $job->is_nuisance = $request->input('is_nuisance');
        $job->need_approval = $request->input('need_approval');
        $job->charge_to = $request->input('charge_to');
        $job->email_sent = $request->input('email_sent');
        $job->raised_by = $request->input('raised_by');
        $job->created_by = $request->input('created_by');
        $job->updated_by = $request->input('updated_by');



       

        if($job->save()) {

            if($request->isMethod('post') || $request->input('job_stat_id') == 7)
            {
                $email = $request->input('emailFlag');
                if(isset($email))
                {
                    if($email['userEmail'])
                    {
                        $this->sendMail($job->id, $email['userEmail']);
                    }
    
                    // if($email['contractEmails'])
                    // {
                    //     foreach (explode(',', $email['contractEmails']) as $key => $value) 
                    //     {
                    //         $this->sendMail($job->id, $value);
                    //     }
                    // }

                    if($email['contractEmails'])
                    {
                        $this->sendMail($job->id, $email['contractEmails']);
                    }
                    
                    if($email['technicianEmail'])
                    {
                        $this->sendMail($job->id, $email['technicianEmail']);
                    }
    
                    if($email['loggedInUser'])
                    {
                        $this->sendMail($job->id, $email['loggedInUser']);
                    }

                    //Additional Email Recipients
                    //$this->sendMail($job->id, ['technical.support@nz.nestle.com', 'paul.stevenson1@nz.nestle.com', 'lorita.teao@jle.co.nz']); 
                }
            }
            
            $assetId = $request->input('asset_id');
            if($assetId)
            {
                $asset = Asset::findOrFail($assetId);
                $asset->company_id = $request->input('company_id');
                // $asset->asset_model_id = $request->input('asset_model_id');
                $asset->location_id = $request->input('location_id');
                $asset->asset_stat_id = 2;

                $asset->save();
            }

            return new JobResource($job);
        }
    }
    /**
     * Display the specified resource.
     *$
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get jobs
        $job = Job::findOrFail($id);

        // Return single jobs as a resource
        return new JobResource($job);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendMail($id, $email, $description='')
    {
        $job = Job::with('jobType')->with('jobStat')
                                    ->with('priority')
                                    ->with('company')
                                    ->with('location')
                                    ->with('user')
                                    ->with('contractor')
                                    ->with('assetModel')
                                    ->with(['asset', 'asset.assetModel', 'asset.company', 'asset.location', 'asset.assetStat'])
                                    ->with('spareJob')
                                    ->findOrFail($id)
                                    ->toArray();
      
        $job['mail_description'] = $description;
        $subject = $job['job_type']['name']. 'Job Number:'.$job['job_number']. ', Customer: '. $job['company']['name']. ',Location: '. $job['location']['address']. ',  Asset: '. $job['asset']['name']. ', Job Status: '. 
                    $job['job_stat']['type']. ', Job Priority: '. $job['priority']['name'];

    //     dd($job); 
    //     die();
    //    json_encode(var_dump($job));

        Mail::send('mail', $job, function($message) use($subject, $email){
           $message->to($email, $email)->subject($subject);
           $message->from('xyz@gmail.com', 'Nestle Professional');
        });
    }
   
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assetWiseJobLists($id)
    {
       
        $jobs= Job::Where('asset_id',$id)->get();

        // Return single companies as a resource
        return JobResource::collection($jobs);
    }

   /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ids = explode(',', $id);
        Job::whereIn('id', $ids)->delete();
    }
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataForDataTable()
    {   
        $companyId = \Request::get('company');
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        $regExSearch = $this->buildRegEx($search);
        // Get jobs
        $jobs = Job::where(function ($query) use($search,$regExSearch) {
                                $query->where('job_number', 'like', '%' . $search . '%')
                                    ->orWhere('user_id', 'like', '%' . $search . '%')
                                    ->orWhereHas('company', function ($query) use ($search, $regExSearch){
                                        $query->where('name', 'REGEXP', $regExSearch);
                                    })
                                    ->orWhereHas('asset', function ($query) use ($search){
                                        $query->where('name', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('contractor', function ($query) use ($search){
                                        $query->where('name', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('asset', function ($query) use ($search){
                                        $query->where('serial', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('asset', function ($query) use ($search){
                                        $query->where('bmb', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('jobType', function ($query) use ($search){
                                        $query->where('name', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('jobStat', function ($query) use ($search){
                                        $query->where('type', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('priority', function ($query) use ($search){
                                        $query->where('name', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('location', function ($query) use ($search){
                                        $query->where('address', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('contractor', function ($query) use ($search, $regExSearch){
                                        $query->where('name', 'REGEXP', $regExSearch);
                                    })
                                    ->orWhere('asset_id', 'like', '%' . $search . '%')
                                    ->orWhere('job_type_id', 'like', '%' . $search . '%')
                                    ->orWhere('job_stat_id', 'like', '%' . $search . '%')
                                    ->orWhere('job_raised_date', 'like', '%' . $search . '%')
                                    ->orWhere('required_date', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%')
                                    ->orWhere('description', 'like', '%' . $search . '%')
                                    ->orWhere('priority_id', 'like', '%' . $search . '%');
                                })
                            ->whereHas('jobStat', function ($query){
                                $query->whereIn('type', ['Received', 'Allocated', 'Complete','Need Admin Approval']);
                            })
                            ->when($companyId, function($query) use ($companyId) {
                                $query->where('contractor_id', $companyId);
                            })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);
        return JobDatatableResource::collection($jobs);
    }

      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getClosedJobDataForDataTable()
    {   
        $companyId = \Request::get('company');
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        $regExSearch = $this->buildRegEx($search);
        // Get jobs
        $jobs = Job::where(function ($query) use($search,$regExSearch) {
                                $query->where('job_number', 'like', '%' . $search . '%')
                                ->orWhere('user_id', 'like', '%' . $search . '%')
                                ->orWhereHas('company', function ($query) use ($search, $regExSearch){
                                    $query->where('name', 'REGEXP', $regExSearch);
                                })
                                ->orWhereHas('asset', function ($query) use ($search){
                                    $query->where('name', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('asset', function ($query) use ($search){
                                    $query->where('serial', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('asset', function ($query) use ($search){
                                    $query->where('bmb', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('jobType', function ($query) use ($search){
                                    $query->where('name', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('jobStat', function ($query) use ($search){
                                    $query->where('type', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('priority', function ($query) use ($search){
                                    $query->where('name', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('location', function ($query) use ($search){
                                    $query->where('address', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('contractor', function ($query) use ($search, $regExSearch){
                                    $query->where('name', 'REGEXP', $regExSearch);
                                })
                                ->orWhere('asset_id', 'like', '%' . $search . '%')
                                ->orWhere('job_type_id', 'like', '%' . $search . '%')
                                ->orWhere('job_stat_id', 'like', '%' . $search . '%')
                                ->orWhere('job_raised_date', 'like', '%' . $search . '%')
                                ->orWhere('required_date', 'like', '%' . $search . '%')
                                ->orWhere('comment', 'like', '%' . $search . '%')
                                ->orWhere('description', 'like', '%' . $search . '%')
                                ->orWhere('priority_id', 'like', '%' . $search . '%');
                            })
                            ->whereHas('jobStat', function ($query){
                                $query->whereIn('type', ['Close']);
                            })
                            ->when($companyId, function($query) use ($companyId) {
                                $query->where('contractor_id', $companyId);
                            })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);
        return JobDatatableResource::collection($jobs);
    }
         /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getJobDataForDataTable($assetId)
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        $regExSearch = $this->buildRegEx($search);
        // Get companies
        $jobs = Job::where(function ($query) use($search,$regExSearch) {
                                    $query->where('job_number', 'like', '%' . $search . '%')
                                    ->orWhere('user_id', 'like', '%' . $search . '%')
                                    ->orWhereHas('company', function ($query) use ($search, $regExSearch){
                                        $query->where('name', 'REGEXP', $regExSearch);
                                    })
                                    ->orWhereHas('asset', function ($query) use ($search){
                                        $query->where('name', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('asset', function ($query) use ($search){
                                        $query->where('serial', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('asset', function ($query) use ($search){
                                        $query->where('bmb', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('jobType', function ($query) use ($search){
                                        $query->where('name', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('jobStat', function ($query) use ($search){
                                        $query->where('type', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('priority', function ($query) use ($search){
                                        $query->where('name', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('location', function ($query) use ($search){
                                        $query->where('address', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('contractor', function ($query) use ($search, $regExSearch){
                                        $query->where('name', 'REGEXP', $regExSearch);
                                    })
                                    ->orWhere('asset_id', 'like', '%' . $search . '%')
                                    ->orWhere('job_type_id', 'like', '%' . $search . '%')
                                    ->orWhere('job_stat_id', 'like', '%' . $search . '%')
                                    ->orWhere('job_raised_date', 'like', '%' . $search . '%')
                                    ->orWhere('required_date', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%')
                                    ->orWhere('description', 'like', '%' . $search . '%')
                                    ->orWhere('priority_id', 'like', '%' . $search . '%');
                                })
                                ->where('asset_id', $assetId)
                                ->orderBy('created_at', 'desc')
                                ->paginate($perPage);

        // Return collection of companies as a resource
        return JobDatatableResource::collection($jobs);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($id, $statusId)
    {
        $job = Job::findOrFail($id);
        $job->job_stat_id = $statusId;
        $job->save();
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getJobNumber()
    {
        $jobs = Job::orderBy('job_number', 'desc')->take(1)->get();
        
        if(count($jobs))
        {
            return ($jobs[0]->job_number) + 1;
        }
        else
        {
            return 1;
        }
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getMachineOptionsByAsset($id)
    {
        // Get machineOptions
        $jobs= Job::Where('asset_id',$id)->get();

        // Return collection of machineOptions as a resource
        return JobResource::collection($jobs);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getJobCount()
    {
        // Get companies
        $jobs = Job::whereHas('jobStat', function ($query){
                        $query->whereIn('type', ['Received', 'Allocated', 'Complete']);
                    })->count();

        // Return single companies as a resource
        return response()->json(['jobCount' => $jobs]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getJobCountByType()
    {
        // Get companies
        $jobs = Job::whereHas('jobType', function ($query){
                        $query->whereIn('name', ['Placement Request']);
                    })->whereHas('jobStat', function ($query){
                        $query->whereIn('type', ['Received','Allocated']);
                    })
                    ->count();

        // Return single companies as a resource
        return response()->json(['jobCountByType' => $jobs]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generateRPO($id)
    {
        if (!file_exists(public_path() . '/pdf' )) 
        {
            mkdir(public_path().'/pdf', 0777, true);
        }

        $job = Job::with('jobType')->with('jobStat')
                    ->with('priority')
                    ->with('company')
                    ->with('location')
                    ->with('contractor')
                    ->with('assetModel')
                    ->with(['asset', 'asset.assetModel', 'asset.company', 'asset.location', 'asset.assetStat'])
                    ->findOrFail($id)
                    ->toArray();

        $machineOptions = MachineOption::WhereIn('id', explode(',', $job['machine_option']))->get()->toArray();
        $groupedByOptionName = [];
        foreach ($machineOptions as $element) 
        {
            $groupedByOptionName[$element['option_name']][] = $element;
        }

        
        //dd($groupedByOptionName);
        $job['machineOptions'] = $groupedByOptionName;
        //dd($result); 

        $fileName = 'RPO' .round(microtime(true)) .'.pdf';

        $pdf = PDF::loadView('pdf', compact('job'));
        return $pdf->download('rpo.pdf');

        //return response()->download(public_path() . '/pdf/'.$fileName);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postImageUpload(Request $request)
    {
        $file = $request->file('file');
        $path = public_path().'/uploads/';
        if (!file_exists($path)) 
        {
            mkdir($path, 0777, true);
        }

        $fileName = str_random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($path, $fileName);
        return '/uploads/'.$fileName;
    }


     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function onPageLoad($assetId)
    {
        $jobTransformer = new JobTransformer();
        return $jobTransformer->onJobPageLoad($assetId);
    }

    /**
     * Display the specified resource.
     *$
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createInspectionJob(Request $request)
    {
        foreach ($request->input('jobIds') as $key => $value) 
        {
            // Get jobs
            $fetchedJob = Job::findOrFail($value);

            $job = new Job;
            $job->account_id = $fetchedJob->account_id;
            $job->company_id = $fetchedJob->company_id;
            $job->user_id = $fetchedJob->user_id;
            $job->asset_id = $fetchedJob->asset_id;
            $job->contractor_id = $fetchedJob->contractor_id;
            $job->location_id = $fetchedJob->location_id;
            $job->job_type_id = $fetchedJob->job_type_id;
            $job->job_stat_id = $fetchedJob->job_stat_id;
            $job->job_number = $this->getJobNumber();
            $job->inspection_description_id = $fetchedJob->inspection_description_id;
            $job->job_raised_date = Carbon::now();
            $job->required_date = Carbon::now()->addDays(14);
            $job->description = $fetchedJob->description;
            $job->contact_person = $fetchedJob->contact_person;
            $job->contact_number = $fetchedJob->contact_number;
            $job->priority_id = $fetchedJob->priority_id;
            $job->inspection_id = $fetchedJob->inspection_id;
            $job->raised_by = $fetchedJob->raised_by;
            $job->created_by = $fetchedJob->created_by;
            $job->updated_by = $fetchedJob->updated_by;
            $job->save();
        }

        // Return single jobs as a resource
        return response()->json(['message' => 'Saved Successfully']);
    }

     /**
     * Converting search term to Regular Expression 
     *
     * @param  string  $search
     * @return string 
     */
    public function buildRegEx(string $search): string
    {
        $searchTerms = explode(' ', $search);
        $regex = '[[:<:]]';
        foreach ($searchTerms as $key => $value) 
        {
            $regex .= $value . '([A-Za-z0-9_])*' . (count($searchTerms) - 1 > $key ? '[[:space:]]+' : '');
        }

        return $regex;
    }



}

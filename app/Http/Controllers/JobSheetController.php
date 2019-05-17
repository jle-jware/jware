<?php

namespace App\Http\Controllers;
use App\JobSheet;
use App\Http\Resources\JobSheetResource;
use App\Http\Resources\JobSheetDatatableResource;
use Illuminate\Http\Request;
use App\Job;
use Storage;
use App\Helpers\ImageProcessing;
use App\Http\Controllers\JobController;
use PDF;
use DB;
use Carbon\Carbon;
use Mail;

class JobSheetController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get jobSheets
        $jobSheets = JobSheet::orderBy('created_at', 'desc')->get();

        // Return collection of jobSheets as a resource
        return JobSheetResource::collection($jobSheets);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->input('job_stat_id') != NULL)
        {
            $jobController = new JobController();
            $jobController->changeStatus($request->input('job_id'), $request->input('job_stat_id'));
        }

        $jobSheet = $request->isMethod('put') ? JobSheet::findOrFail($request->id) : new JobSheet;
        $jobSheet->id = $request->input('id');
        $jobSheet->user_id = $request->input('user_id');
        $jobSheet->job_id = $request->input('job_id');
        $jobSheet->swmc = $request->input('swmc');
        $jobSheet->job_date = $request->input('job_date');
        $jobSheet->timein = $request->input('timein');
        $jobSheet->timeout = $request->input('timeout');
        $jobSheet->onstie = $request->input('onstie');
        $jobSheet->travel_hours = $request->input('travel_hours');
        $jobSheet->total_kms = $request->input('total_kms');
        $jobSheet->waiting_time = $request->input('waiting_time');
        $jobSheet->waiting_reason = $request->input('waiting_reason');
        $jobSheet->comment = $request->input('comment');
        $jobSheet->total_cup_count = $request->input('total_cup_count');
        $jobSheet->run_flush = $request->input('run_flush');
        $jobSheet->product_checked = $request->input('product_checked');
        $jobSheet->customer_signature = $request->input('customer_signature');
        $jobSheet->work_area = $request->input('work_area');
        $jobSheet->customer_signature = $request->input('customer_signature');
        $jobSheet->image = $request->input('image')? ImageProcessing::saveBase64ImagePng($request->input('image')) : NULL;
        $jobSheet->is_complete = $request->input('is_complete');
        $jobSheet->second_trip = $request->input('second_trip');
        $jobSheet->created_by = $request->input('created_by');
        $jobSheet->updated_by = $request->input('updated_by');
        if($jobSheet->save()) {
            return new JobSheetResource($jobSheet);
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
        // Get jobSheets
        $jobSheet = JobSheet::findOrFail($id);

        // Return single jobSheets as a resource
        return new JobSheetResource($jobSheet);
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
        JobSheet::whereIn('id', $ids)->delete();
    }
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataForDataTable()
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        // Get jobSheets
        $jobSheets = JobSheet::with(['job', 'job.priority', 'job.jobType', 'job.jobStat','job.asset','job.contractor','job.company','job.location'])
                                ->where(function ($query) use($search) {
                                $query->where('job_id', 'like', '%' . $search . '%')
                                    ->orWhereHas('job', function ($query) use ($search){
                                        $query->where('job_number', 'like', '%' . $search . '%');
                                    })
                                    ->orWhere('swmc', 'like', '%' . $search . '%')
                                    ->orWhere('job_date', 'like', '%' . $search . '%')
                                    ->orWhere('timein', 'like', '%' . $search . '%')
                                    ->orWhere('timeout', 'like', '%' . $search . '%')
                                    ->orWhere('onstie', 'like', '%' . $search . '%')
                                    ->orWhere('travel_hours', 'like', '%' . $search . '%')
                                    ->orWhere('total_kms', 'like', '%' . $search . '%')
                                    ->orWhere('waiting_reason', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%')
                                    ->orWhere('total_cup_count', 'like', '%' . $search . '%')
                                    ->orWhere('run_flush', 'like', '%' . $search . '%')
                                    ->orWhere('work_area', 'like', '%' . $search . '%')
                                    ->orWhere('product_checked', 'like', '%' . $search . '%')
                                    ->orWhere('customer_signature', 'like', '%' . $search . '%')
                                    ->orWhere('is_complete', 'like', '%' . $search . '%')
                                    ->orWhere('waiting_time', 'like', '%' . $search . '%');
                                })
                            ->orderBy('job_date', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return JobSheetDatatableResource::collection($jobSheets);
    }

    /**
     * Display the specified resource.
     *$
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getByJob($id)
    {
        // Get jobSheets
        $jobSheet = JobSheet::where('job_id', $id)->get();

        // Return single jobSheets as a resource
        return JobSheetResource::collection($jobSheet);
    }

    /**
     * Display the specified resource.
     *$
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkSecondTrip($id)
    {
        // Get jobSheets
        $jobSheet = JobSheet::where('job_id', $id)->where('second_trip', 1)->get();

        // Return single jobSheets as a resource
        return JobSheetResource::collection($jobSheet);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function generateJobSheetPDF($id)
    {
        if (!file_exists(public_path() . '/pdf' )) 
        {
            mkdir(public_path().'/pdf', 0777, true);
        }

        $jobSheet = JobSheet::with(['job','user', 'job.priority','job.contractor','job.jobType', 'job.jobStat','job.location','job.company','job.asset','createdUser', 'updatedUser'])->findOrFail($id)->toArray();
      
        //dd($jobSheet);
        //$fileName = 'jobSheet' .round(microtime(true)) .'.pdf';

        $pdf = PDF::loadView('jobSheet', compact('jobSheet'));
        //$pdf->save(public_path() . '/pdf/'.$fileName);
        return $pdf->download('jobSheet.pdf');

        //return response()->download(public_path() . '/pdf/'.$fileName);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendMail($id, $email, $description='')
    {
        $jobSheet = JobSheet::with('job')
                            ->with(['job', 'job.priority', 'job.jobType', 'job.jobStat','job.asset','job.contractor','job.company','job.location'])
                            ->with('createdUser')
                            ->with('updatedUser')
                            ->findOrFail($id)
                            ->toArray();
      
        $jobSheet['mail_description'] = $description;
        $subject = $jobSheet['job']['job_type']['name']. 'Job Number:'.$jobSheet['job']['job_number']. ',Customer: '. $jobSheet['job']['company']['name']. ',Location: '. $jobSheet ['job']['location']['address']. ', Asset: '. $jobSheet['job']['asset']['name']. ', Job Status: '. 
                    $jobSheet['job']['job_stat']['type']. ', Job Priority: '. $jobSheet['job']['priority']['name'];

        //dd($jobSheet); 
        //  die();
        // json_encode(var_dump($jobSheet));

        Mail::send('mailJobSheet', $jobSheet, function($message) use($subject, $email){
           $message->to($email, $email)->subject($subject);
           $message->from('xyz@gmail.com', 'Nestle Professional');
        });
    }
}

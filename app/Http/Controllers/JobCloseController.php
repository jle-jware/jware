<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobClose;
use App\Http\Resources\JobCloseResource;
class JobCloseController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get jobCloses
        $jobCloses = JobClose::orderBy('created_at', 'desc')->get();

        // Return collection of jobCloses as a resource
        return JobCloseResource::collection($jobCloses);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $jobClose = $request->isMethod('put') ? JobClose::findOrFail($request->id) : new JobClose;
        $jobClose->id = $request->input('id');
        $jobClose->user_id = $request->input('user_id');
        $jobClose->job_id = $request->input('job_id');
        $jobClose->is_complete = $request->input('is_complete');
        $jobClose->send_email = $request->input('send_email');
        $jobClose->review = $request->input('review');
        $jobClose->created_by = $request->input('created_by');
        $jobClose->updated_by = $request->input('updated_by');
        if($jobClose->save()) {
            return new JobCloseResource($jobClose);
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
        // Get jobCloses
        $jobClose = JobClose::findOrFail($id);

        // Return single jobCloses as a resource
        return new JobCloseResource($jobClose);
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
        JobClose::whereIn('id', $ids)->delete();
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
        $jobClose = JobClose::where('job_id', $id)->get();

        // Return single jobSheets as a resource
        return JobCloseResource::collection($jobClose);
    }
}

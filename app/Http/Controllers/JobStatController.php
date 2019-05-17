<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobStat;
use App\Http\Resources\JobStatResource;

class JobStatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get jobStats
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        // Get companies
        $jobStats = JobStat::where(function ($query) use($search) {
                                $query->where('type', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return JobStatResource::collection($jobStats);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $jobStat = $request->isMethod('put') ? JobStat::findOrFail($request->id) : new JobStat;

        $jobStat->id = $request->input('id');
        $jobStat->type = $request->input('type');
        $jobStat->account_id = $request->input('account_id');
        $jobStat->comment = $request->input('comment');
        $jobStat->created_by = $request->input('created_by');
        $jobStat->updated_by = $request->input('updated_by');
        
        if($jobStat->save()) {
            return new JobStatResource($jobStat);
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
        // Get jobTypes
        $jobStat = JobStat::findOrFail($id);

        // Return single jobTypes as a resource
        return new JobStatResource($jobStat);
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
        JobStat::whereIn('id', $ids)->delete();
    }
}

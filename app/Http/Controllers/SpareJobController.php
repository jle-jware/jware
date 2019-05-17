<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\SpareJobResource;

use App\SpareJob;
use App\Spare;
class SpareJobController extends Controller
{
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get spareJobs
        $spareJobs = spareJob::orderBy('created_at', 'desc')->paginate(15);

        // Return collection of spareJobs as a resource
        return SpareJobResource::collection($spareJobs);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $spareJob = $request->isMethod('put') ? SpareJob::findOrFail($request->id) : new SpareJob;
        $spareJob->id = $request->input('id');
        $spareJob->account_id = $request->input('account_id');
        $spareJob->spare_id = $request->input('spare_id');
        $spareJob->job_id = $request->input('job_id');
        $spareJob->allocated = $request->input('allocated');
        $spareJob->used = $request->input('used');
        $spareJob->comment = $request->input('comment');
        $spareJob->created_by = $request->input('created_by');
        $spareJob->updated_by = $request->input('updated_by');

        if($spareJob->save()) {
            $this->updateStock($spareJob->spare_id, $spareJob->used);
            return new SpareJobResource($spareJob);
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
        // Get spareJobs
        $spareJob = SpareJob::findOrFail($id);

        // Return single spareJobs as a resource
        return new SpareJobResource($spareJob);
    }
             /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function SpareJobListByJobID($id)
    {
        // // Get sparejobs
        $spareJobs= SpareJob::Where('job_id',$id)->get();

        // Return single companies as a resource
        return SpareJobResource::collection($spareJobs);
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
        SpareJob::whereIn('id', $ids)->delete();
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStock($id, $used)
    {
        $spare= Spare::findOrFail($id);
        $spare->current_stock = $spare->current_stock - $used;
        $spare->save();  
    }

      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataForDataTable($jobId)
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        // Get companies
        $spareJobs = SpareJob::where(function ($query) use($search) {
                                $query->where('spare_id', 'like', '%' . $search . '%')
                                    ->orWhere('job_id', 'like', '%' . $search . '%')
                                    ->orWhere('allocated', 'like', '%' . $search . '%')
                                    ->orWhere('used', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%');
                                })
                            ->where('job_id', $jobId)
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return SpareJobResource::collection($spareJobs);
    }
}

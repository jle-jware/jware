<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Milestone;
use App\Http\Resources\MilestoneResource;
use App\Http\Resources\MilestoneDatatableResource;
use Carbon\Carbon;
class MilestoneController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get milestones
        $milestones = Milestone::orderBy('created_at', 'desc')->get();

        // Return collection of milestones as a resource
        return MilestoneResource::collection($milestones);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $milestone = $request->isMethod('put') ? Milestone::findOrFail($request->id) : new Milestone;
        $milestone->id = $request->input('id');
        $milestone->account_id = $request->input('account_id');
        $milestone->company_id = $request->input('company_id');
        $milestone->asset_id = $request->input('asset_id');
        $milestone->job_id = $request->input('job_id');
        $milestone->date = Carbon::parse($request->input('date'));
        $milestone->milestone_type_id = $request->input('milestone_type_id');
        $milestone->description = $request->input('description');
        $milestone->cup_count = $request->input('cup_count');
        $milestone->cup_count_per_day = $request->input('cup_count_per_day');
        $milestone->test_tag = $request->input('test_tag');
        $milestone->comment = $request->input('comment');
        $milestone->created_by = $request->input('created_by');
        $milestone->updated_by = $request->input('updated_by');
        if($milestone->save()) {
            return new MilestoneResource($milestone);
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
        // Get milestones
        $milestone = Milestone::findOrFail($id);

        // Return single milestones as a resource
        return new MilestoneResource($milestone);
    }
          /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assetWiseMilestoneists($id)
    {
        // Get milestones
        $milestones= Milestone::Where('asset_id',$id)->get();

        // Return milestones as a resource
        return MilestoneResource::collection($milestones);
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
        Milestone::whereIn('id', $ids)->delete();
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
        $milestones = Milestone::where(function ($query) use($search) {
                                $query->where('job_id', 'like', '%' . $search . '%')
                                    ->orWhere('asset_id', 'like', '%' . $search . '%')
                                    ->orWhere('milestone_type_id', 'like', '%' . $search . '%')
                                    ->orWhere('date', 'like', '%' . $search . '%')
                                    ->orWhere('cup_count', 'like', '%' . $search . '%')
                                    ->orWhere('test_tag', 'like', '%' . $search . '%')
                                    ->orWhere('description', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%');
                                })
                            ->where('job_id', $jobId)
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of milestones as a resource
        return MilestoneDatatableResource::collection($milestones);
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataForDataTableByAsset($assetId)
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        // Get companies
        $milestones = Milestone::where(function ($query) use($search) {
                                $query->where('job_id', 'like', '%' . $search . '%')
                                    ->orWhere('asset_id', 'like', '%' . $search . '%')
                                    ->orWhere('milestone_type_id', 'like', '%' . $search . '%')
                                    ->orWhere('date', 'like', '%' . $search . '%')
                                    ->orWhere('cup_count', 'like', '%' . $search . '%')
                                    ->orWhere('test_tag', 'like', '%' . $search . '%')
                                    ->orWhere('description', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%');
                                })
                            ->where('asset_id', $assetId)
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of milestones as a resource
        return MilestoneDatatableResource::collection($milestones);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\AssetUtilization;
use App\Http\Resources\AssetUtilizationResource;
use App\Http\Resources\AssetUtilizationDatatableResource;
class AssetUtilizationController extends Controller
{
   
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get assetUtilizations
        $assetUtilizations = AssetUtilization::orderBy('created_at', 'desc')->get();

        // Return collection of assetUtilizations as a resource
        return AssetUtilizationResource::collection($assetUtilizations);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $assetUtilization = AssetUtilization::where('utilization_type_id', $request->input('utilization_type_id'))
        //                 ->where('date', Carbon::parse($request->input('date')))
        //                 ->when($request->isMethod('put'), function($query) use ($request) {
        //                     $query->where('id', '!=', $request->id);
        //                   })
        //                 ->get();
        // if(count($assetUtilization))
        // {
        //     return response()->json(['date' => 'Date already exists, please select another'], 204);
        // }

        $assetUtilization = $request->isMethod('put') ? AssetUtilization::findOrFail($request->id) : new AssetUtilization;
        $assetUtilization->id = $request->input('id');
        $assetUtilization->account_id = $request->input('account_id');
        $assetUtilization->asset_id = $request->input('asset_id');
        $assetUtilization->job_id = $request->input('job_id');
        $assetUtilization->utilization_type_id = $request->input('utilization_type_id');
        $assetUtilization->date = Carbon::parse($request->input('date'));
        $assetUtilization->value = $request->input('value');
        $assetUtilization->clear_calculation = $request->input('clear_calculation');
        $assetUtilization->parts_changed = $request->input('parts_changed');
        $assetUtilization->comment = $request->input('comment');
        $assetUtilization->created_by = $request->input('created_by');
        $assetUtilization->updated_by = $request->input('updated_by');
        if($assetUtilization->save()) 
        {
            return new AssetUtilizationResource($assetUtilization);
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
        // Get assetUtilizations
        $assetUtilization = AssetUtilization::findOrFail($id);

        // Return single assetUtilizations as a resource
        return new AssetUtilizationResource($assetUtilization);
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
        AssetUtilization::whereIn('id', $ids)->delete();
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
        $assetUtilization = AssetUtilization::where('job_id', $id)->get();

        // Return single jobSheets as a resource
        return AssetUtilizationResource::collection($assetUtilization);
    }
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataForDataTable($assetID)
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        // Get companies
        $assetUtilizations = AssetUtilization::where(function ($query) use($search) {
                                $query->where('job_id', 'like', '%' . $search . '%')
                                ->orWhereHas('utilizationType', function ($query) use ($search){
                                    $query->where('utilization_type', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('utilizationType', function ($query) use ($search){
                                    $query->where('utilization_unit', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('utilizationType', function ($query) use ($search){
                                    $query->where('rate_unit', 'like', '%' . $search . '%');
                                })
                                ->orWhere('value', 'like', '%' . $search . '%')
                                ->orWhere('date', 'like', '%' . $search . '%')
                                ->orWhere('comment', 'like', '%' . $search . '%');
                                })
                            ->where('asset_id', $assetID)
                            ->orderBy('date', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return AssetUtilizationDatatableResource::collection($assetUtilizations);
    }
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getJobDataForDataTable($jobId)
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        // Get companies
        $assetUtilizations = AssetUtilization::where(function ($query) use($search) {
                                $query->where('job_id', 'like', '%' . $search . '%')
                                ->orWhereHas('utilizationType', function ($query) use ($search){
                                    $query->where('utilization_type', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('utilizationType', function ($query) use ($search){
                                    $query->where('utilization_unit', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('utilizationType', function ($query) use ($search){
                                    $query->where('rate_unit', 'like', '%' . $search . '%');
                                })
                                ->orWhere('value', 'like', '%' . $search . '%')
                                ->orWhere('date', 'like', '%' . $search . '%')
                                ->orWhere('comment', 'like', '%' . $search . '%');
                                })
                            ->where('job_id', $jobId)
                            ->orderBy('date', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return AssetUtilizationDatatableResource::collection($assetUtilizations);
    }
}

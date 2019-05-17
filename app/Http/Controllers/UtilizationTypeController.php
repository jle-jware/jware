<?php

namespace App\Http\Controllers;

use App\UtilizationType;
use App\Http\Resources\UtilizationTypeResource;
use App\Http\Resources\UtilizationTypeResourceDatatable;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UtilizationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get utilizationTypes
        $utilizationTypes = UtilizationType::orderBy('created_at', 'desc')->get();

        // Return collection of utilizationTypes as a resource
        return UtilizationTypeResource::collection($utilizationTypes);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $utilizationType = $request->isMethod('put') ? UtilizationType::findOrFail($request->id) : new UtilizationType;
        $utilizationType->id = $request->input('id');
        $utilizationType->account_id = $request->input('account_id');
        $utilizationType->utilization_type = $request->input('utilization_type');
        $utilizationType->utilization_unit = $request->input('utilization_unit');
        $utilizationType->rate_unit = $request->input('rate_unit');
        $utilizationType->unit_duration_days = $request->input('unit_duration_days');
        $utilizationType->comment = $request->input('comment');
        $utilizationType->created_by = $request->input('created_by');
        $utilizationType->updated_by = $request->input('updated_by');
        if($utilizationType->save()) {
            return new UtilizationTypeResource($utilizationType);
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
        // Get utilizationTypes
        $utilizationType = UtilizationType::findOrFail($id);

        // Return single utilizationTypes as a resource
        return new UtilizationTypeResource($utilizationType);
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
        UtilizationType::whereIn('id', $ids)->delete();
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
        // Get companies
        $utilizationTypes = UtilizationType::where(function ($query) use($search) {
                                $query->where('utilization_type', 'like', '%' . $search . '%')
                                    ->orWhere('utilization_unit', 'like', '%' . $search . '%')
                                    ->orWhere('rate_unit', 'like', '%' . $search . '%')
                                    ->orWhere('unit_duration_days', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of utilizationTypes as a resource
        return UtilizationTypeDatatableResource::collection($utilizationTypes);
    }
}

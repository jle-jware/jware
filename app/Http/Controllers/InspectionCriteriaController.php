<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InspectionCriteria;
use App\Http\Resources\InspectionCriteriaResource;

class InspectionCriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Inspection Criterias
        $inspectionCriterias = InspectionCriteria::orderBy('created_at', 'desc')->get();

        // Return collection of priorities as a resource
        return InspectionCriteriaResource::collection($inspectionCriterias);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inspectionCriteria = $request->isMethod('put') ? InspectionCriteria::findOrFail($request->id) : new InspectionCriteria;
        $inspectionCriteria->id = $request->input('id');
        $inspectionCriteria->account_id = $request->input('account_id');
        $inspectionCriteria->name = $request->input('name');
        $inspectionCriteria->comment = $request->input('comment');
        $inspectionCriteria->created_by = $request->input('created_by');
        $inspectionCriteria->updated_by = $request->input('updated_by');
        if($inspectionCriteria->save()) {
            return new InspectionCriteriaResource($inspectionCriteria);
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
        // Get InspectionCriteria
        $inspectionCriteria = InspectionCriteria::findOrFail($id);

        // Return single priorities as a resource
        return new InspectionCriteriaResource($inspectionCriteria);
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
        InspectionCriteria::whereIn('id', $ids)->delete();
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
        // Get inspection criterias
        $inspectionCriterias = InspectionCriteria::where(function ($query) use($search) {
                                $query->where('account_id', 'like', '%' . $search . '%')
                                    ->orWhere('name', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return InspectionCriteriaResource::collection($inspectionCriterias);
    }
}

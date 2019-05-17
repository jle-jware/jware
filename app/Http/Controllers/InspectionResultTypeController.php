<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InspectionResultType;
use App\Http\Resources\InspectionResultTypeResource;
class InspectionResultTypeController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get results
        $results = InspectionResultType::orderBy('created_at', 'desc')->get();

        // Return collection of results as a resource
        return InspectionResultTypeResource::collection($results);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $request->isMethod('put') ? InspectionResultType::findOrFail($request->id) : new InspectionResultType;
        $result->id = $request->input('id');
        $result->account_id = $request->input('account_id');
        $result->type = $request->input('type');
        $result->name = $request->input('name');
        $result->comment = $request->input('comment');
        $result->created_by = $request->input('created_by');
        $result->updated_by = $request->input('updated_by');
        if($result->save()) {
            return new InspectionResultTypeResource($result);
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
        // Get results
        $result = InspectionResultType::findOrFail($id);

        // Return single results as a resource
        return new InspectionResultTypeResource($result);
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
        InspectionResultType::whereIn('id', $ids)->delete();
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
        // Get Asset
        $results = InspectionResultType::where(function ($query) use($search) {
                                $query->where('name', 'like', '%' . $search . '%')
                                    ->orWhere('type', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return InspectionResultTypeResource::collection($results);
    }
    public function getInspectionResult()
    {
        $results = InspectionResultType::where('type', 'Inspection')->get();
        return InspectionResultTypeResource::collection($results);
    }
}

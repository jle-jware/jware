<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Result;
use App\Http\Resources\ResultResource;
class ResultController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get results
        $results = Result::orderBy('created_at', 'desc')->get();

        // Return collection of results as a resource
        return ResultResource::collection($results);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $request->isMethod('put') ? Result::findOrFail($request->id) : new Result;
        $result->id = $request->input('id');
        $result->account_id = $request->input('account_id');
        $result->type = $request->input('type');
        $result->name = $request->input('name');
        $result->comment = $request->input('comment');
        $result->created_by = $request->input('created_by');
        $result->updated_by = $request->input('updated_by');
        if($result->save()) {
            return new ResultResource($result);
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
        $result = Result::findOrFail($id);

        // Return single results as a resource
        return new ResultResource($result);
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
        Result::whereIn('id', $ids)->delete();
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
        $results = Result::where(function ($query) use($search) {
                                $query->where('name', 'like', '%' . $search . '%')
                                    ->orWhere('type', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return ResultResource::collection($results);
    }
    public function getInspectionResult()
    {
        $results = Result::where('type', 'Inspection')->get();
        return ResultResource::collection($results);
    }
}

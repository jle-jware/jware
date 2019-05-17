<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MilestoneType;
use App\Http\Resources\MilestoneTypeResource;

class MilestoneTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get MilestoneTypes
        $milestoneTypes = MilestoneType::orderBy('created_at', 'desc')->paginate(15);

        // Return collection of MilestoneTypes as a resource
        return MilestoneTypeResource::collection($milestoneTypes);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $milestoneType = $request->isMethod('put') ? MilestoneType::findOrFail($request->id) : new MilestoneType;
        $milestoneType->id = $request->input('id');
        $milestoneType->account_id = $request->input('account_id');
        $milestoneType->name = $request->input('name');
        $milestoneType->comment = $request->input('comment');
        $milestoneType->created_by = $request->input('created_by');
        $milestoneType->updated_by = $request->input('updated_by');
        if($milestoneType->save()) {
            return new MilestoneTypeResource($milestoneType);
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
        // Get MilestoneTypes
        $milestoneType = MilestoneType::findOrFail($id);

        // Return single MilestoneTypes as a resource
        return new MilestoneTypeResource($milestoneType);
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
        MilestoneType::whereIn('id', $ids)->delete();
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
        // Get jobs
        $milestoneTypes = MilestoneType::where(function ($query) use($search) {
                                $query->where('account_id', 'like', '%' . $search . '%')
                                    ->orWhere('name', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return MilestoneTypeResource::collection($milestoneTypes);
    }
}

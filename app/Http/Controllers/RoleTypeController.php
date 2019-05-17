<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RoleType;
use App\Http\Resources\RoleTypeResource;

class RoleTypeController extends Controller
{
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get roleTypes
        $roleTypes = RoleType::orderBy('created_at', 'desc')->paginate(15);

        // Return collection of roleTypes as a resource
        return RoleTypeResource::collection($roleTypes);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $roleType = $request->isMethod('put') ? RoleType::findOrFail($request->id) : new RoleType;
        $roleType->id = $request->input('id');
        $roleType->account_id = $request->input('account_id');
        $roleType->name = $request->input('name');
        $roleType->comment = $request->input('comment');
        $roleType->created_by = $request->input('created_by');
        $roleType->updated_by = $request->input('updated_by');
        if($roleType->save()) {
            return new RoleTypeResource($roleType);
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
        // Get roleTypes
        $roleType = RoleType::findOrFail($id);

        // Return single roleTypes as a resource
        return new RoleTypeResource($roleType);
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
        RoleType::whereIn('id', $ids)->delete();
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
        $roleTypes = RoleType::where(function ($query) use($search) {
                                $query->where('name', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return RoleTypeResource::collection($roleTypes);
    }
}

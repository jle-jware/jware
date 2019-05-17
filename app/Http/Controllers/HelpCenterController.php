<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HelpCenter;
use App\Http\Resources\HelpCenterResource;
use App\Http\Resources\HelpCenterDatatableResource;

class HelpCenterController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get helpCenters
        $helpCenters = HelpCenter::orderBy('created_at', 'desc')->get();

        // Return collection of helpCenters as a resource
        return HelpCenterResource::collection($helpCenters);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $helpCenter = $request->isMethod('put') ? HelpCenter::findOrFail($request->id) : new HelpCenter;

        $helpCenter->id = $request->input('id');
        $helpCenter->account_id = $request->input('account_id');
        $helpCenter->type = $request->input('type');
        $helpCenter->url = $request->input('url');
        $helpCenter->comment = $request->input('comment');
        $helpCenter->created_by = $request->input('created_by');
        $helpCenter->updated_by = $request->input('updated_by');
        
        if($helpCenter->save()) {
            return new HelpCenterResource($helpCenter);
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
        // Get helpCenters
        $helpCenter = HelpCenter::findOrFail($id);

        // Return single helpCenters as a resource
        return new HelpCenterResource($helpCenter);
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
        HelpCenter::whereIn('id', $ids)->delete();
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
        $helpCenters = HelpCenter::where(function ($query) use($search) {
                                $query->where('type', 'like', '%' . $search . '%')
                                    ->orWhere('url', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'asc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return HelpCenterResource::collection($helpCenters);
    }
}

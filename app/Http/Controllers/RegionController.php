<?php

namespace App\Http\Controllers;
use App\Region;
use App\Http\Resources\RegionResource;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get priorities
        $regions = Region::orderBy('created_at', 'desc')->paginate(15);

        // Return collection of priorities as a resource
        return RegionResource::collection($regions);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $region = $request->isMethod('put') ? Region::findOrFail($request->id) : new Region;
        $region->id = $request->input('id');
        $region->account_id = $request->input('account_id');
        $region->name = $request->input('name');
        $region->comment = $request->input('comment');
        $region->created_by = $request->input('created_by');
        $region->updated_by = $request->input('updated_by');
        if($region->save()) {
            return new RegionResource($region);
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
        // Get priorities
        $region = Region::findOrFail($id);

        // Return single priorities as a resource
        return new RegionResource($region);
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
        Region::whereIn('id', $ids)->delete();
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
        $regions = Region::where(function ($query) use($search) {
                                $query->where('account_id', 'like', '%' . $search . '%')
                                    ->orWhere('name', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return RegionResource::collection($regions);
    }
}

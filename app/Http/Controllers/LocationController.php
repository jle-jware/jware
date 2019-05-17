<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Location;
use App\Http\Resources\LocationResource;
use App\Http\Resources\LocationDatatableResource;

class LocationController extends Controller
{
         /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Locations
        $locations = Location::orderBy('created_at', 'desc')->get();

        // Return collection of companies as a resource
        return LocationResource::collection($locations);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $location = $request->isMethod('put') ? Location::findOrFail($request->id) : new Location;

        $location->id = $request->input('id');
        $location->account_id = $request->input('account_id');
        $location->user_id = $request->input('user_id');
        $location->region_id = $request->input('region_id');
        $location->company_id = $request->input('company_id');
        $location->agent_id = $request->input('agent_id');
        $location->address = $request->input('address');
        $location->is_primary = $request->input('is_primary');
        $location->description = $request->input('description');
        $location->phone_number = $request->input('phone_number');
        $location->created_by = $request->input('created_by');
        $location->updated_by = $request->input('updated_by');
        
        if($location->save()) {
            return new LocationResource($location);
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
        // Get locations
        $location = Location::findOrFail($id);

        // Return single locations as a resource
        return new LocationResource($location);
    }
          /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function companyWiseLocation($id)
    {
        // // Get companies
        // $company = User::With('company')->Where('id',$userId)->get();
        $locations= Location::Where('company_id', $id)->get();

        // Return single companies as a resource
        return LocationResource::collection($locations);
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
        Location::whereIn('id', $ids)->delete();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataForDataTable($companyId)
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        // Get companies
        $locations = Location::where(function ($query) use($search) {
                                $query->where('description', 'like', '%' . $search . '%')
                                    ->orWhere('address', 'like', '%' . $search . '%')
                                    ->orWhere('phone_number', 'like', '%' . $search . '%');
                                })
                            ->where('company_id', $companyId)
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return LocationDatatableResource::collection($locations);
    }
}

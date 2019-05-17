<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\MachineSpec;
use App\Http\Resources\MachineSpecResource;
class MachineSpecController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get machineSpecs
        $machineSpecs = MachineSpec::orderBy('created_at', 'desc')->get();

        // Return collection of machineSpecs as a resource
        return MachineSpecResource::collection($machineSpecs);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $machineSpec = $request->isMethod('put') ? MachineSpec::findOrFail($request->id) : new MachineSpec;
        $machineSpec->id = $request->input('id');
        $machineSpec->account_id = $request->input('account_id');
        $machineSpec->asset_model_id = $request->input('asset_model_id');
        $machineSpec->serial = $request->input('serial');
        $machineSpec->bmb = $request->input('bmb');
        $machineSpec->created_by = $request->input('created_by');
        $machineSpec->updated_by = $request->input('updated_by');

        if($machineSpec->save()) {
            return new MachineSpecResource($machineSpec);
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
        // Get machineSpecs
        $machineSpec = MachineSpec::findOrFail($id);

        // Return single machineSpecs as a resource
        return new MachineSpecResource($machineSpec);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assetModelWiseSerial($id)
    {
        // Get machineSpecs
        $machineSpecs= MachineSpec::Where('asset_model_id',$id)->where('is_used', null)->get();

        // Return machineSpecs as a resource
        return MachineSpecResource::collection($machineSpecs);
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
        MachineSpec::whereIn('id', $ids)->delete();
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
        $machineSpecs = MachineSpec::where(function ($query) use($search) {
                                $query->where('asset_model_id', 'like', '%' . $search . '%')
                                    ->orWhere('serial', 'like', '%' . $search . '%')
                                    ->orWhere('bmb', 'like', '%' . $search . '%');
                                })
                            // ->where('asset_id', $jobId)
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of machineSpecs as a resource
        return MachineSpecResource::collection($machineSpecs);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assetModelWiseSerialAll($id)
    {
        // Get machineSpecs
        $machineSpecs= MachineSpec::Where('asset_model_id',$id)->get();

        // Return machineSpecs as a resource
        return MachineSpecResource::collection($machineSpecs);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getBmbNumber()
    {
        $machineSpec = MachineSpec::orderBy('created_at', 'desc')->take(1)->get();
        
        return response()->json(['bmbNumber' => $machineSpec[0]->bmb]);
    }
}

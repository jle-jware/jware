<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MachineOption;
use App\Http\Resources\MachineOptionResource;
use App\Http\Resources\MachineOptionDatatableResource;

class MachineOptionController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get machineOptions
        $machineOptions = MachineOption::orderBy('created_at', 'desc')->get();

        // Return collection of machineOptions as a resource
        return MachineOptionResource::collection($machineOptions);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $machineOption = $request->isMethod('put') ? MachineOption::findOrFail($request->id) : new MachineOption;
        $machineOption->id = $request->input('id');
        $machineOption->account_id = $request->input('account_id');
        $machineOption->asset_model_id = $request->input('asset_model_id');
        $machineOption->option_name = $request->input('option_name');
        $machineOption->value = $request->input('value');
        $machineOption->created_by = $request->input('created_by');
        $machineOption->updated_by = $request->input('updated_by');

        if($machineOption->save()) {
            return new MachineOptionResource($machineOption);
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
        // Get machineOptions
        $machineOption = MachineOption::findOrFail($id);

        // Return single machineOptions as a resource
        return new MachineOptionResource($machineOption);
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
        MachineOption::whereIn('id', $ids)->delete();
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataForDataTable($assetModelId)
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        // Get companies
        $machineOptions = MachineOption::where(function ($query) use($search) {
                                $query->where('asset_model_id', 'like', '%' . $search . '%')
                                    ->orWhere('option_name', 'like', '%' . $search . '%')
                                    ->orWhere('value', 'like', '%' . $search . '%');
                                })
                            ->where('asset_model_id', $assetModelId)
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of machineOptions as a resource
        return MachineOptionResource::collection($machineOptions);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getByAssetModel($id)
    {
        // Get machineOptions
        $machineOptions= MachineOption::Where('asset_model_id',$id)->get();

        // Return collection of machineOptions as a resource
        return MachineOptionResource::collection($machineOptions);
    }
   
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataForMachineOptionDataTable()
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        // Get companies
        $machineOptions = MachineOption::where(function ($query) use($search) {
                                $query->where('asset_model_id', 'like', '%' . $search . '%')
                                    ->orWhere('option_name', 'like', '%' . $search . '%')
                                    ->orWhere('value', 'like', '%' . $search . '%');
                                })
                            // ->where('asset_id', $jobId)
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of Machine Option as a resource
        return MachineOptionResource::collection($machineOptions);
    }
}

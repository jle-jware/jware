<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Asset;
use App\Http\Resources\AssetResource;
use App\Http\Resources\AssetDatatableResource;
use Excel;
use Company;
use Carbon\Carbon;
use App\MachineSpec;
use App\AssetInspection;
use App\Inspection;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         // Get Assets
         $assets = Asset::orderBy('created_at', 'desc')->get();

         // Return collection of Assets as a resource
         return AssetResource::collection($assets);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $asset = $request->isMethod('put') ? Asset::findOrFail($request->id) : new Asset;
        $asset->id = $request->input('id');
        $asset->account_id = $request->input('account_id');
        $asset->company_id = $request->input('company_id');
        $asset->name = $request->input('name');
        $asset->asset_model_id = $request->input('asset_model_id');
        $asset->agent_id = $request->input('agent_id');
        $asset->serial = $request->input('serial');
        $asset->bmb = $request->input('bmb');
        $asset->date = Carbon::parse($request->input('date'));
        $asset->cost = $request->input('cost');
        $asset->is_charged = $request->input('is_charged');
        $asset->location_id = $request->input('location_id');
        $asset->specific_location = $request->input('specific_location');
        $asset->asset_stat_id = $request->input('asset_stat_id');
        $asset->description = $request->input('description');
        $asset->created_by = $request->input('created_by');
        $asset->updated_by = $request->input('updated_by');
        
        if($asset->save()) 
        {
            if($request->isMethod('put'))
            {
                $assetInspections = AssetInspection::where('asset_id', $asset->id)->get();
                if(count($assetInspections))
                {
                    return;
                }
            }

            $inspections = Inspection::where('asset_model_id', $asset->asset_model_id)->get();
            foreach ($inspections as $key => $inspection) 
            {
                $assetInspection = new AssetInspection();
                $assetInspection->account_id = $asset->account_id;
                $assetInspection->asset_id = $asset->id;
                $assetInspection->description = $inspection->description;
                $assetInspection->frequency = $inspection->frequency;
                $assetInspection->group = $inspection->group;
                $assetInspection->inspection_criteria_id = $inspection->inspection_criteria_id;
                $assetInspection->file = $inspection->file;
                $assetInspection->comment = $inspection->comment;
                $assetInspection->created_by = $asset->created_by;
                $assetInspection->updated_by = $asset->updated_by;
                $assetInspection->save();
            }
            
            return new AssetResource($asset);
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
        // Get assets
        $asset = Asset::findOrFail($id);

        // Return single assets as a resource
        return new AssetResource($asset);
    }

    /**
     * Display the specified resource.
     *$
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAssetWiseCompany($id)
    {
        // Get assets
        $asset = Asset::With('company')->findOrFail($id);

        // Return single assets as a resource
        return new AssetResource($asset);
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAssetByCompany($id)
    {
        // // Get Assets
        $assets= Asset::Where('company_id',$id)->get();

        // Return Assets as a resource
        return AssetResource::collection($assets);
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
        Asset::whereIn('id', $ids)->delete();
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
        $regExSearch = $this->buildRegEx($search);
        // Get Asset
        $assets = Asset::where(function ($query) use($search, $regExSearch) {
                                $query->where('name', 'like', '%' . $search . '%')
                                    ->orWhere('company_id', 'like', '%' . $search . '%')
                                    ->orWhereHas('company', function ($query) use ($search, $regExSearch){
                                        $query->where('name', 'REGEXP', $regExSearch);
                                    })
                                    ->orWhereHas('location', function ($query) use ($search){
                                        $query->where('agent_id', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('location', function ($query) use ($search){
                                        $query->where('address', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('location', function ($query) use ($search){
                                        $query->where('description', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('assetStat', function ($query) use ($search){
                                        $query->where('type', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('assetModel', function ($query) use ($search){
                                        $query->where('name', 'like', '%' . $search . '%');
                                    })
                                    ->orWhere('agent_id', 'like', '%' . $search . '%')
                                    ->orWhere('location_id', 'like', '%' . $search . '%')
                                    ->orWhere('date', 'like', '%' . $search . '%')
                                    ->orWhere('asset_model_id', 'like', '%' . $search . '%')
                                    ->orWhere('specific_location', 'like', '%' . $search . '%')
                                    ->orWhere('serial', 'like', '%' . $search . '%')
                                    ->orWhere('bmb', 'like', '%' . $search . '%')
                                    ->orWhere('cost', 'like', '%' . $search . '%')
                                    ->orWhere('asset_stat_id', 'like', '%' . $search . '%')
                                    ->orWhere('description', 'like', '%' . $search . '%');
                                })
                                ->whereHas('assetStat', function ($query){
                                    $query->whereIn('type', ['TO BE REFURBISHED','REFURBISHED','TO BE ASSESSED BY PAUL','NEW MACHINE',
                                    'In Transit','PLACED']);
                                })
                            ->orderBy('name', 'asc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return AssetDatatableResource::collection($assets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeUploadData(Request $request)
    {
        if($request->hasFile('file'))
        {
            $path = $request->file('file')->getRealPath();
            $data = Excel::load($path, function($reader) {},'UTF-8')->get();
            Asset::insert($data->toArray());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkSerial($serial)
    {
        $asset = Asset::Where('serial', $serial)->get();
        $response = count($asset) ? true : null; 
        return response()->json(['serial' => $response]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAssetBystatus()
    {
        // Get assets
        $assets = Asset::whereHas('assetStat', function ($query){
            $query->whereIn('type', ['NEW MACHINE','TO BE REFURBISHED','TO BE ASSESSED BY PAUL','REFURBISHED']);
        })->count();
        // Return list of assets as a resource
        return response()->json(['assetBystatus' => $assets]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAssetByPlacedStatus()
    {
        // Get assets
        $assets = Asset::whereHas('assetStat', function ($query){
            $query->whereIn('type', ['PLACED']);
        })->count();
        // Return list of assets as a resource
        return response()->json(['assetByPlacedStatus' => $assets]);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getAssetBystatusForJobForm()
    {
        // Get assets
        $serials = Asset::whereHas('assetStat', function ($query){
                            $query->whereIn('type', ['NEW MACHINE','REFURBISHED','TO BE REFURBISHED']);
                        })->get();
        // Return collection of assets as a resource
        return AssetResource::collection($serials);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAssetBySearch($searchTerm)
    {
        // Get Assets
        $assets = Asset::Where('name', 'like', '%' . $searchTerm . '%')
        ->orWhere('serial', 'like', '%' . $searchTerm . '%')
        ->orWhere('bmb', 'like', '%' . $searchTerm . '%')
        ->orderBy('created_at', 'desc')
        ->get();

        // Return collection of Assets as a resource
         return AssetResource::collection($assets);
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getWriteOffDataForDataTable()
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        $regExSearch = $this->buildRegEx($search);
        // Get Asset
        $assets = Asset::where(function ($query) use($search, $regExSearch) {
                                $query->where('name', 'like', '%' . $search . '%')
                                    ->orWhere('company_id', 'like', '%' . $search . '%')
                                    ->orWhereHas('company', function ($query) use ($search, $regExSearch){
                                        $query->where('name', 'REGEXP', $regExSearch);
                                    })
                                    ->orWhereHas('location', function ($query) use ($search){
                                        $query->where('agent_id', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('location', function ($query) use ($search){
                                        $query->where('address', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('location', function ($query) use ($search){
                                        $query->where('description', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('assetStat', function ($query) use ($search){
                                        $query->where('type', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('assetModel', function ($query) use ($search){
                                        $query->where('name', 'like', '%' . $search . '%');
                                    })
                                    ->orWhere('agent_id', 'like', '%' . $search . '%')
                                    ->orWhere('location_id', 'like', '%' . $search . '%')
                                    ->orWhere('asset_model_id', 'like', '%' . $search . '%')
                                    ->orWhere('specific_location', 'like', '%' . $search . '%')
                                    ->orWhere('serial', 'like', '%' . $search . '%')
                                    ->orWhere('bmb', 'like', '%' . $search . '%')
                                    ->orWhere('cost', 'like', '%' . $search . '%')
                                    ->orWhere('asset_stat_id', 'like', '%' . $search . '%')
                                    ->orWhere('description', 'like', '%' . $search . '%');
                                })
                                ->whereHas('assetStat', function ($query){
                                    $query->whereIn('type', ['WRITE OFF']);
                                })
                                ->orderBy('created_at', 'desc')
                                ->paginate($perPage);

        // Return collection of companies as a resource
        return AssetDatatableResource::collection($assets);
    }

     /**
     * Converting search term to Regular Expression 
     *
     * @param  string  $search
     * @return string 
     */
    public function buildRegEx(string $search): string
    {
        $searchTerms = explode(' ', $search);
        $regex = '[[:<:]]';
        foreach ($searchTerms as $key => $value) 
        {
            $regex .= $value . '([A-Za-z0-9_])*' . (count($searchTerms) - 1 > $key ? '[[:space:]]+' : '');
        }

        return $regex;
    }
}

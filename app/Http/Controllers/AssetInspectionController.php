<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AssetInspection;
use App\Http\Resources\AssetInspectionResource;
use Storage;
use App\Helpers\ImageProcessing;

class AssetInspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get inspections
        $assetInspections = AssetInspection::orderBy('created_at', 'desc')->get();

        // Return collection of inspections as a resource
        return AssetInspectionResource::collection($assetInspections);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $assetInspection = $request->isMethod('put') ? AssetInspection::findOrFail($request->id) : new AssetInspection;

        $assetInspection->id = $request->input('id');
        $assetInspection->account_id = $request->input('account_id');
        $assetInspection->asset_id = $request->input('asset_id');
        $assetInspection->description = $request->input('description');
        $assetInspection->frequency = $request->input('frequency');
        $assetInspection->inspection_criteria_id = $request->input('inspection_criteria_id');
        $assetInspection->group = $request->input('group');
        $assetInspection->file = $request->input('file');
        $assetInspection->comment = $request->input('comment');
        $assetInspection->created_by = $request->input('created_by');
        $assetInspection->updated_by = $request->input('updated_by');
        
        if($assetInspection->save()) {
            return new AssetInspectionResource($assetInspection);
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
        // Get inspections
        $assetInspection = AssetInspection::findOrFail($id);

        // Return single inspections as a resource
        return new AssetInspectionResource($assetInspection);
    }
    public function assetModelWiseAssetInspection($id)
    {
        // // Get companies
        // $company = User::With('company')->Where('id',$userId)->get();
        $assetInspections= AssetInspection::Where('asset_model_id', $id)->get();

        // Return single companies as a resource
        return AssetInspectionResource::collection($assetInspections);
    }
    public function assetlWiseAssetInspection($id)
    {
        // // Get companies
        // $company = User::With('company')->Where('id',$userId)->get();
        $assetInspections= AssetInspection::Where('asset_id', $id)->get();

        // Return single companies as a resource
        return AssetInspectionResource::collection($assetInspections);
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
        AssetInspection::whereIn('id', $ids)->delete();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataForDataTable($assetId)
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        // Get companies
        $assetInspections = AssetInspection::where(function ($query) use($search) {
                                $query->where('asset_id', 'like', '%' . $search . '%')
                                    ->orWhere('description', 'like', '%' . $search . '%')
                                    ->orWhere('frequency', 'like', '%' . $search . '%')
                                    ->orWhere('group', 'like', '%' . $search . '%')
                                    ->orWhere('inspection_criteria_id', 'like', '%' . $search . '%')
                                    ->orWhere('file', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%');
                                })
                            ->where('asset_id', $assetId)
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return AssetInspectionResource::collection($assetInspections);
    }
     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadFile(Request $request)
    {
        if($request->hasFile('file'))
        {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $fileName = $file->getClientOriginalName();
            $path = public_path().'/uploads';
            $uplaod = $file->move($path, $fileName);
            return ['file' => $fileName];
        }       
    }


    private function generateRandomString($length = 10) 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) 
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

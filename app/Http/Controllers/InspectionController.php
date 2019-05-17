<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inspection;
use App\Http\Resources\InspectionResource;
use Storage;
use App\Helpers\ImageProcessing;

class InspectionController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get inspections
        $inspections = Inspection::orderBy('created_at', 'desc')->get();

        // Return collection of inspections as a resource
        return InspectionResource::collection($inspections);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inspection = $request->isMethod('put') ? Inspection::findOrFail($request->id) : new Inspection;

        $inspection->id = $request->input('id');
        $inspection->account_id = $request->input('account_id');
        $inspection->asset_model_id = $request->input('asset_model_id');
        $inspection->description = $request->input('description');
        $inspection->frequency = $request->input('frequency');
        $inspection->inspection_criteria_id = $request->input('inspection_criteria_id');
        $inspection->group = $request->input('group');
        $inspection->file = $request->input('file');
        $inspection->comment = $request->input('comment');
        $inspection->created_by = $request->input('created_by');
        $inspection->updated_by = $request->input('updated_by');
        
        if($inspection->save()) {
            return new InspectionResource($inspection);
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
        $inspection = Inspection::findOrFail($id);

        // Return single inspections as a resource
        return new InspectionResource($inspection);
    }
    public function assetModelWiseInspection($id)
    {
        // // Get companies
        // $company = User::With('company')->Where('id',$userId)->get();
        $inspections= Inspection::Where('asset_model_id', $id)->get();

        // Return single companies as a resource
        return InspectionResource::collection($inspections);
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
        Inspection::whereIn('id', $ids)->delete();
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
        $inspections = Inspection::where(function ($query) use($search) {
                                $query->where('asset_model_id', 'like', '%' . $search . '%')
                                    ->orWhere('description', 'like', '%' . $search . '%')
                                    ->orWhere('frequency', 'like', '%' . $search . '%')
                                    ->orWhere('group', 'like', '%' . $search . '%')
                                    ->orWhere('inspection_criteria_id', 'like', '%' . $search . '%')
                                    ->orWhere('file', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%');
                                })
                            ->where('asset_model_id', $assetModelId)
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return InspectionResource::collection($inspections);
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

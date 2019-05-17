<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AssetModel;
use App\Http\Resources\AssetModelResource;
use App\Http\Resources\AssetModelDatatableResource;
use Excel;
use Storage;
use App\Helpers\ImageProcessing;

class AssetModelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get assetModels
        $assetModels = AssetModel::orderBy('created_at', 'desc')->get();

        // Return collection of assetModels as a resource
        return AssetModelResource::collection($assetModels);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $assetModel = $request->isMethod('put') ? AssetModel::findOrFail($request->id) : new AssetModel;
        $assetModel->id = $request->input('id');
        $assetModel->account_id = $request->input('account_id');
        $assetModel->name = $request->input('name');
        $assetModel->company_id = $request->input('company_id');
        $assetModel->model_type_id = $request->input('model_type_id');
        $assetModel->rent = $request->input('rent');
        $assetModel->frequency = $request->input('frequency');
        $assetModel->value = $request->input('value');
        $assetModel->unit = $request->input('unit');
        $assetModel->cannister_no = $request->input('cannister_no');
        $assetModel->drink_no = $request->input('drink_no');
        $assetModel->machine_option = $request->input('machine_option');
        $assetModel->minimum_stock = $request->input('minimum_stock');
        $assetModel->current_stock = $request->input('current_stock');
        $assetModel->image = $request->input('image')? ImageProcessing::saveBase64ImagePng($request->input('image')) : NULL;
        $assetModel->description = $request->input('description');
        $assetModel->utilization_type = $request->input('utilization_type');
        $assetModel->comment = $request->input('comment');
        $assetModel->created_by = $request->input('created_by');
        $assetModel->updated_by = $request->input('updated_by');
        if($assetModel->save()) {
            return new AssetModelResource($assetModel);
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
        // Get assetModels
        $assetModel = AssetModel::findOrFail($id);

        // Return single assetModels as a resource
        return new AssetModelResource($assetModel);
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
        AssetModel::whereIn('id', $ids)->delete();
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
            AssetModel::insert($data->toArray());
        }
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
        $assetModels = AssetModel::where(function ($query) use($search) {
                                $query->where('name', 'like', '%' . $search . '%')
                                    ->orWhere('model_type_id', 'like', '%' . $search . '%')
                                    ->orWhere('rent', 'like', '%' . $search . '%')
                                    ->orWhere('utilization_type', 'like', '%' . $search . '%')
                                    ->orWhere('frequency', 'like', '%' . $search . '%')
                                    ->orWhere('minimum_stock', 'like', '%' . $search . '%')
                                    ->orWhere('current_stock', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return AssetModelDatatableResource::collection($assetModels);
    }

}

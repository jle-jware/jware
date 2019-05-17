<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Spare;
use App\Account;
use App\Company;
use App\Asset;
use App\Sparetype;
use App\Http\Resources\SpareResource;
use App\Http\Resources\SpareDatatableResource;
use Excel;

class SpareController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get spares
        $spares = Spare::orderBy('created_at', 'desc')->paginate(15);

        // Return collection of spares as a resource
        return SpareResource::collection($spares);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $spare = $request->isMethod('put') ? spare::findOrFail($request->id) : new spare;
        $spare->id = $request->input('id');
        $spare->account_id = $request->input('account_id');
        $spare->company_id = $request->input('company_id');
        $spare->spare_type_id = $request->input('spare_type_id');
        $spare->asset_model_id = $request->input('asset_model_id');
        $spare->name = $request->input('name');
        $spare->serial = $request->input('serial');
        $spare->cost = $request->input('cost');
        $spare->status = $request->input('status');
        $spare->minimum_stock = $request->input('minimum_stock');
        $spare->current_stock = $request->input('current_stock');
        $spare->image = $request->input('image')? ImageProcessing::saveBase64ImagePng($request->input('image')) : NULL;
        $spare->description = $request->input('description');
        $spare->comment = $request->input('comment');
        $spare->created_by = $request->input('created_by');
        $spare->updated_by = $request->input('updated_by');
        if($spare->save()) {
            return new spareResource($spare);
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
        // Get spares
        $spare = Spare::findOrFail($id);

        // Return single spares as a resource
        return new SpareResource($spare);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getSpareByAssetModel($id)
    {
        // // Get spares
        $spares= Spare::Where('asset_model_id',$id)->get();

        // Return spares as a resource
        return SpareResource::collection($spares);
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
        Spare::whereIn('id', $ids)->delete();
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
        $spares = Spare::where(function ($query) use($search) {
                                $query->where('company_id', 'like', '%' . $search . '%')
                                    ->orWhere('spare_type_id', 'like', '%' . $search . '%')
                                    ->orWhere('name', 'like', '%' . $search . '%')
                                    ->orWhere('serial', 'like', '%' . $search . '%')
                                    ->orWhere('cost', 'like', '%' . $search . '%')
                                    ->orWhere('status', 'like', '%' . $search . '%')
                                    ->orWhere('minimum_stock', 'like', '%' . $search . '%')
                                    ->orWhere('current_stock', 'like', '%' . $search . '%')
                                    ->orWhere('description', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return SpareDatatableResource::collection($spares);
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
            Spare::insert($data->toArray());
        }
    }

}

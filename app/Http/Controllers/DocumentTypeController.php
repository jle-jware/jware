<?php

namespace App\Http\Controllers;

use App\DocumentType;
use Illuminate\Http\Request;
use App\Http\Resources\DocumentTypeResource;

class DocumentTypeController extends Controller
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get companies
        $documentTypes = DocumentType::orderBy('created_at', 'desc')->get();

        // Return collection of companies as a resource
        return DocumentTypeResource::collection($documentTypes);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        

        $documentType = $request->isMethod('put') ? DocumentType::findOrFail($request->id) : new DocumentType;
        $documentType->id = $request->input('id');
        $documentType->account_id = $request->input('account_id');
        $documentType->type = $request->input('type');
        $documentType->comment = $request->input('comment');
        $documentType->created_by = $request->input('created_by');
        $documentType->updated_by = $request->input('updated_by');
        if($documentType->save()) {
            return new DocumentTypeResource($documentType);
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
        // Get companies
        $documentType = DocumentType::findOrFail($id);

        // Return single companies as a resource
        return new DocumentTypeResource($documentType);
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
        DocumentType::whereIn('id', $ids)->delete();
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
        $documentTypes = DocumentType::where(function ($query) use($search) {
                                $query->where('account_id', 'like', '%' . $search . '%')
                                    ->orWhere('type', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return DocumentTypeResource::collection($documentTypes);
    }
}

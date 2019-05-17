<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\DocumentDatatableResource;

use Storage;
use App\Helpers\ImageProcessing;

class DocumentController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get companies
        $documents = Document::orderBy('created_at', 'desc')->get();

        // Return collection of companies as a resource
        return DocumentResource::collection($documents);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 

        $document = $request->isMethod('put') ? Document::findOrFail($request->id) : new Document;
        $document->id = $request->input('id');
        $document->account_id = $request->input('account_id');
        $document->url = $request->input('url');
        $document->document_type_id = $request->input('document_type_id');
        $document->asset_model_id = $request->input('asset_model_id');
        $document->comment = $request->input('comment');
        $document->file = $request->input('file');
        $document->description = $request->input('description');
        $document->created_by = $request->input('created_by');
        $document->updated_by = $request->input('updated_by');
        if($document->save()) {
            return new DocumentResource($document);
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
        $document = Document::findOrFail($id);

        // Return single companies as a resource
        return new DocumentResource($document);
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
        Document::whereIn('id', $ids)->delete();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDocumentByAssetModel($id)
    {
        // // Get spares
        $documents= Document::Where('asset_model_id',$id)->get();

        // Return spares as a resource
        return DocumentResource::collection($documents);
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
        $documents = Document::where(function ($query) use($search) {
                                $query->where('asset_model_id', 'like', '%' . $search . '%')
                                    ->orWhere('url', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%')
                                    ->orWhere('description', 'like', '%' . $search . '%')
                                    ->orWhere('document_type_id', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return DocumentDatatableResource::collection($documents);
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

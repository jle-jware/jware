<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AssetStat;
use App\Http\Resources\AssetStatResource;
class AssetStatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get AssetStats
        $assetStats = AssetStat::orderBy('created_at', 'desc')->get();

        // Return collection of AssetStats as a resource
        return AssetStatResource::collection($assetStats);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $assetStat = $request->isMethod('put') ? AssetStat::findOrFail($request->id) : new AssetStat;
        $assetStat->id = $request->input('id');
        $assetStat->account_id = $request->input('account_id');
        $assetStat->type = $request->input('type');
        $assetStat->comment = $request->input('comment');
        $assetStat->created_by = $request->input('created_by');
        $assetStat->updated_by = $request->input('updated_by');
        if($assetStat->save()) {
            return new AssetStatResource($assetStat);
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
        // Get AssetStats
        $assetStat = AssetStat::findOrFail($id);

        // Return single AssetStats as a resource
        return new AssetStatResource($assetStat);
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
        AssetStat::whereIn('id', $ids)->delete();
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
        $assetStats = AssetStat::where(function ($query) use($search) {
                                $query->where('account_id', 'like', '%' . $search . '%')
                                    ->orWhere('type', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return AssetStatResource::collection($assetStats);
    }
}

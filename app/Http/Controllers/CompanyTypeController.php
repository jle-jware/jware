<?php

namespace App\Http\Controllers;
use App\CompanyType;
use Illuminate\Http\Request;
use App\Http\Resources\CompanyTypeResource;

class CompanyTypeController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get spareJobs
        $companyTypes = CompanyType::orderBy('created_at', 'desc')->get();

        // Return collection of spareJobs as a resource
        return CompanyTypeResource::collection($companyTypes);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $companyType = $request->isMethod('put') ? CompanyType::findOrFail($request->id) : new CompanyType;

        $companyType->id = $request->input('id');
        $companyType->type = $request->input('type');
        $companyType->account_id = $request->input('account_id');
        $companyType->comment = $request->input('comment');
        $companyType->created_by = $request->input('created_by');
        $companyType->updated_by = $request->input('updated_by');
        
        if($companyType->save()) {
            return new CompanyTypeResource($companyType);
        }
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
        CompanyType::whereIn('id', $ids)->delete();
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
        // Get Asset
        $companyTypes = CompanyType::where(function ($query) use($search) {
                                $query->where('type', 'like', '%' . $search . '%')
                                    ->orWhere('comment', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return CompanyTypeResource::collection($companyTypes);
    }
}

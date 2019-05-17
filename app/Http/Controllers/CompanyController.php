<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use App\User;
use App\Location;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyDatatableResource;
use Storage;
use App\Helpers\ImageProcessing;
use Mail;
use Excel;

class CompanyController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        // Get companies
        $companies = Company::orderBy('created_at', 'desc')->get();

        // Return collection of companies as a resource
        return CompanyResource::collection($companies);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company = $request->isMethod('put') ? Company::findOrFail($request->id) : new Company;
        $company->id = $request->input('id');
        $company->account_id = $request->input('account_id');
        $company->name = $request->input('name');
        $company->client_no = $request->input('client_no');
        $company->website = $request->input('website');
        $company->post_code = $request->input('post_code');
        $company->type = $request->input('type');
        $company->phone_number = $request->input('phone_number');
        $company->image = $request->input('image')? ImageProcessing::saveBase64ImagePng($request->input('image')) : NULL;
        $company->is_parent = $request->input('is_parent');
        $company->is_enable = $request->input('is_enable');
        $company->created_by = $request->input('created_by');
        $company->updated_by = $request->input('updated_by');
        
        if($company->save()) {
            return new CompanyResource($company);
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
        $company = Company::with('user','location')->findOrFail($id);

        // Return single companies as a resource
        return new CompanyResource($company);
    }
    public function getCustomers()
    {
        $customers = Company::where('type', 'Customer')->get();
        return CompanyResource::collection($customers);
    }
    public function getDistributors()
    {
        $distributors = Company::where('type', 'Distributor')->get();
        return CompanyResource::collection($distributors);
    }

    public function getManufacturers()
    {
        $manufacturers = Company::where('type', 'Manufacturer')->get();
        return CompanyResource::collection($manufacturers);
    }

    public function getContractors()
    {
        $contractors = Company::where('type', 'Contractor')->get();
        return CompanyResource::collection($contractors);
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
        Company::whereIn('id', $ids)->delete();
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
        $companies = Company::where(function ($query) use($search) {
                                $query->where('name', 'like', '%' . $search . '%')
                                    ->orWhere('type', 'like', '%' . $search . '%')
                                    ->orWhere('phone_number', 'like', '%' . $search . '%');
                                })
                            ->orderBy('name', 'asc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return CompanyDatatableResource::collection($companies);
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
            Company::insert($data->toArray());
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkName($name)
    {
        $company = Company::Where('name', $name)->get();
        $response = count($company) ? true : false; 
        return response()->json(['name' => $response]);
    }

    /**
     *Display a listing of the resource by search.
     *
     * @param  string  $searchTerm
     * @return \Illuminate\Http\Response
     */
    public function getCompanyBySearch($searchTerm)
    {
        
         $companies = Company::where('name', 'like', '%' . $searchTerm . '%')
                        ->orderBy('created_at', 'desc')
                        ->get();

         return CompanyResource::collection($companies);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserDatatableResource;
use Storage;
use App\Helpers\ImageProcessing;

class UserController extends Controller
{
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 100000;
        // Get users
        $users = User::where(function ($query) use($search) {
                                $query->where('first_name', 'like', '%' . $search . '%')
                                    ->orWhere('last_name', 'like', '%' . $search . '%')
                                    ->orWhereHas('company', function ($query) use ($search){
                                        $query->where('name', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('company', function ($query) use ($search){
                                        $query->where('type', 'like', '%' . $search . '%');
                                    })
                                    ->orWhere('username', 'like', '%' . $search . '%')
                                    ->orWhere('email', 'like', '%' . $search . '%')
                                    ->orWhere('phone_number', 'like', '%' . $search . '%')
                                    ->orWhere('contact_type', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of users as a resource
        return UserResource::collection($users);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->isMethod('put') ? User::findOrFail($request->id) : new User;
        $user->id = $request->input('id');
        $user->account_id = $request->input('account_id');
        $user->company_id = $request->input('company_id');
        $user->location_id = $request->input('location_id');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = bcrypt($request['password']);
        $user->phone_number = $request->input('phone_number');
        $user->is_user = $request->input('is_user');
        $user->key_contact = $request->input('key_contact');
        $user->contact_type = $request->input('contact_type');
        $user->image = $request->input('image')? ImageProcessing::saveBase64ImagePng($request->input('image')) : NULL;
        $user->created_by = $request->input('created_by');
        $user->updated_by = $request->input('updated_by');
        if($user->save()) {
            return new UserResource($user);
        }
    }
      /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function userWiseCompany($userId)
    {
        // // Get companies
        // $company = User::With('company')->Where('id',$userId)->get();
        $user = User::With('company')->findOrFail($userId);

        // Return single companies as a resource
        return new UserResource($user);
    }
        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function companyWiseUser($id)
    {
        // // Get companies
        // $company = User::With('company')->Where('id',$userId)->get();
        $users= User::Where('company_id', $id)->get();

        // Return single companies as a resource
        return UserResource::collection($users);
    }
        /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function companyWiseTechnician($id)
    {
        // // Get companies
        // $company = User::With('company')->Where('id',$userId)->get();
        $users= User::Where('company_id', $id)->Where('contact_type','Technician')->get();

        // Return single companies as a resource
        return UserResource::collection($users);
    }
    /**
     * Display the specified resource.
     *$
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get users
        $user = User::findOrFail($id);

        // Return single users as a resource
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get users
        $user = User::findOrFail($id);

        if($user->delete()) {
            return new UserResource($user);
        }    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataForDataTable($companyId)
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        // Get companies
        $users = User::where(function ($query) use($search) {
                                $query->where('first_name', 'like', '%' . $search . '%')
                                    ->orWhere('last_name', 'like', '%' . $search . '%')
                                    ->orWhere('username', 'like', '%' . $search . '%')
                                    ->orWhere('email', 'like', '%' . $search . '%')
                                    ->orWhere('phone_number', 'like', '%' . $search . '%');
                                })
                            ->where('company_id', $companyId)
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return UserDatatableResource::collection($users);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataForUserDataTable()
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        // Get spare types
        $users = User::where(function ($query) use($search) {
                                    $query->where('first_name', 'like', '%' . $search . '%')
                                    ->orWhere('last_name', 'like', '%' . $search . '%')
                                    ->orWhereHas('company', function ($query) use ($search){
                                        $query->where('name', 'like', '%' . $search . '%');
                                    })
                                    ->orWhereHas('company', function ($query) use ($search){
                                        $query->where('type', 'like', '%' . $search . '%');
                                    })
                                    ->orWhere('username', 'like', '%' . $search . '%')
                                    ->orWhere('email', 'like', '%' . $search . '%')
                                    ->orWhere('phone_number', 'like', '%' . $search . '%');
                                })
                            ->orderBy('last_name', 'asc')
                            ->paginate($perPage);

        // Return collection of companies as a resource
        return UserDatatableResource::collection($users);
    }
     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getUsers()
    {
       
        $users= User::Where('contact_type', ['Owner'])->get();

        // Return single companies as a resource
        return UserResource::collection($users);
    }
}

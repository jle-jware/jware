<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Account;
use App\Http\Resources\AccountResource;

class AccountController extends Controller
{
         /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        // Get Accounts
        $accounts = Account::filter($params)->orderBy($request->sort ?? "id", $request->order ?? "DESC")
            ->offset($request->offset ?? 0)
            ->limit($request->limit ?? 1000)->get();
        $totalItems = Account::filter($params)->count();
        // Return collection of Accounts as a resource
        $accountsCollection = AccountResource::collection($accounts);
        $dataResponse = new \stdClass();
        $dataResponse->accounts = $accountsCollection;
        $dataResponse->total = $totalItems;
        return response()->json($dataResponse);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $account = $request->isMethod('put') ? Account::findOrFail($request->id) : new Account;

        $account->id = $request->input('id');
        $account->name = $request->input('name');
        $account->enable = $request->input('enable');
        if($account->save()) {
            return new AccountResource($account);
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
        // Get Accounts
        $account = Account::findOrFail($id);

        // Return single Accounts as a resource
        return new AccountResource($account);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get Accounts
        $account = Account::findOrFail($id);

        if($account->delete()) {
            return new AccountResource($account);
        }    
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\InspectionResult;
use App\Http\Resources\InspectionResultResource;
use App\Http\Resources\InspectionResultDatatableResource;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class InspectionResultController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get Inspection Results
        $inspectionResults = InspectionResult::orderBy('created_at', 'desc')->get();

        // Return collection of results  as a resource
        return InspectionResultResource::collection($inspectionResults);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inspectionResult = $request->isMethod('put') ? InspectionResult::findOrFail($request->id) : new InspectionResult;
        $inspectionResult->id = $request->input('id');
        $inspectionResult->account_id = $request->input('account_id');
        $inspectionResult->job_id = $request->input('job_id');
        $inspectionResult->result_id = $request->input('result_id');
        $inspectionResult->status = $request->input('status');
        $inspectionResult->date =  Carbon::parse($request->input('date'));
        $inspectionResult->file = $request->input('file');
        $inspectionResult->band = $request->input('band');
        $inspectionResult->insulation = $request->input('insulation');
        $inspectionResult->leakage = $request->input('leakage');
        $inspectionResult->comment = $request->input('comment');
        $inspectionResult->created_by = $request->input('created_by');
        $inspectionResult->updated_by = $request->input('updated_by');
        if($inspectionResult->save()) {
            return new InspectionResultResource($inspectionResult);
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
        // Get InspectionResult
        $inspectionResult = InspectionResult::findOrFail($id);

        // Return single priorities as a resource
        return new InspectionResultResource($inspectionResult);
    }
    /**
     * Display the specified resource.
     *$
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getByJob($id)
    {
        // Get inspection result
        $inspectionResult = InspectionResult::where('job_id', $id)->get();

        // Return single inspection result as a resource
        return InspectionResultResource::collection($inspectionResult);
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
        InspectionResult::whereIn('id', $ids)->delete();
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
        // Get inspection criterias
        $inspectionResults = InspectionResult::with('job.asset.assetInspection')->where(function ($query) use($search) {
                                $query->where('account_id', 'like', '%' . $search . '%')
                                ->orWhereHas('job', function ($query) use ($search){
                                    $query->where('description', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('result', function ($query) use ($search){
                                    $query->where('name', 'like', '%' . $search . '%');
                                })
                                ->orWhere('comment', 'like', '%' . $search . '%')
                                ->orWhere('date', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        //dd($inspectionResults->toArray());

        // Return collection of companies as a resource
        return InspectionResultResource::collection($inspectionResults);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataForDataTableInspectionJob()
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;

        //$assetInspection = 
        $inspectionResults = InspectionResult::with('job.asset.assetInspection')->where(function ($query) use($search) {
                                $query->where('account_id', 'like', '%' . $search . '%')
                                ->orWhereHas('job', function ($query) use ($search){
                                    $query->where('description', 'like', '%' . $search . '%');
                                })
                                ->orWhereHas('result', function ($query) use ($search){
                                    $query->where('name', 'like', '%' . $search . '%');
                                })
                                ->orWhere('band', 'like', '%' . $search . '%')
                                ->orWhere('insulation', 'like', '%' . $search . '%')
                                ->orWhere('leakage', 'like', '%' . $search . '%')
                                ->orWhere('comment', 'like', '%' . $search . '%')
                                ->orWhere('date', 'like', '%' . $search . '%');
                                })
                            ->orderBy('created_at', 'desc')->get();

        $new = new Collection;
        foreach ($inspectionResults as $key => $value) 
        {
            $today = \DateTime::createFromFormat('Y-m-d', date("Y-m-d"));
            if(date('Y-m-d', strtotime($value->date)) == $today->modify('-'.$value->job->asset->assetInspection->frequency.' day')->format('Y-m-d'))
            {
                $new->push($value);
            }
        }

        // Return collection of companies as a resource
        return InspectionResultDatatableResource::collection($this->paginate($new, $perPage));
    }

    public function paginate($items, $perPage = 15, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}

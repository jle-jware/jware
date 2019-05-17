<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gallery;
use Storage;
use Carbon\Carbon;
use App\Helpers\ImageProcessing;
use App\Http\Resources\GalleryResource;
use App\Job;

class GalleryController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get galleries
        $galleries = Gallery::orderBy('created_at', 'desc')->get();

        // Return collection of galleries as a resource
        return GalleryResource::collection($galleries);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $gallery = $request->isMethod('put') ? Gallery::findOrFail($request->id) : new Gallery;

        $gallery->id = $request->input('id');
        $gallery->account_id = $request->input('account_id');
        //$gallery->asset_id = (Job::select('asset_id')->find($request->input('job_id')))->asset_id;
        $gallery->asset_id = 1;
        $gallery->job_id = $request->input('job_id');
        // $gallery->date = $request-> Carbon::parse($request->input('date'));
        $gallery->image = $this->manageImages($request->input('image'), $gallery->image);
        $gallery->description = $request->input('description');
        $gallery->created_by = $request->input('created_by');
        $gallery->updated_by = $request->input('updated_by');
        
        if($gallery->save()) 
        {
            return new GalleryResource($gallery);
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
        // Get galleries
        $gallery = Gallery::findOrFail($id);

        // Return single galleries as a resource
        return new GalleryResource($gallery);
    }
    public function jobWiseGallery($id)
    {
        // // Get companies
        // $company = User::With('company')->Where('id',$userId)->get();
        $galleries= Gallery::Where('job_id', $id)->get();

        // Return single companies as a resource
        return GalleryResource::collection($galleries);
    }
    public function assetWiseGallery($id)
    {
        // // Get companies
        // $company = User::With('company')->Where('id',$userId)->get();
        $galleries= Gallery::Where('asset_id', $id)->get();

        // Return single companies as a resource
        return GalleryResource::collection($galleries);
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
        Gallery::whereIn('id', $ids)->delete();
    }

      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataForDataTable($jobId)
    {   
        $search = \Request::get('search') ?: '';
        $perPage = \Request::get('per_page') ?: 5;
        // Get companies
        $galleries = Gallery::where(function ($query) use($search) {
                                $query->where('job_id', 'like', '%' . $search . '%')
                                    ->orWhere('description', 'like', '%' . $search . '%')
                                    ->orWhere('image', 'like', '%' . $search . '%')
                                    ->orWhere('date', 'like', '%' . $search . '%');
                                })
                            ->where('job_id', $jobId)
                            ->orderBy('created_at', 'desc')
                            ->paginate($perPage);

        // Return collection of galleries as a resource
        return GalleryResource::collection($galleries);
    }

    public function manageImages($images, $previousImages)
    {
        if(is_array($images))
        {
            $imagePaths = [];
            foreach ($images as $key => $value) 
            {
                array_push($imagePaths, ImageProcessing::saveBase64ImagePng($value));
            }
            return implode(',', $imagePaths);
        }
        else
        {
            if($previousImages)
            {
                $deletedImages = array_diff(explode(",", $previousImages), explode(",", $images));
                foreach ($deletedImages as $key => $value)
                {
                    if (file_exists(public_path().$value))
                    {
                        unlink(public_path().$value);
                    } 
                }
            }
            
            return $images;
        }
    }


}

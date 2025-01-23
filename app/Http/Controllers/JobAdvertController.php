<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobAdvertRequest;
use App\Http\Resources\JobAdvertResource;
use App\Models\Company;
use App\Models\ContractType;
use App\Models\Country;
use App\Models\JobAdvert;
use App\Models\JobCategory;
use App\Models\Profession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobAdvertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function candidateView(Request $request)
    {
        try {
            $jobs = JobAdvertResource::collection(JobAdvert::where('status',1)->where('is_draft', 0)->get());
            
            if ($request->header('Accept') == 'application/json') {
                return response()->success($jobs, "All jobs", 200);
            } else {
                // toastr()->success("Found {$jobs->count()} jobs."); 
                return view('job_adverts.candidates.index', ['job_adverts' => $jobs]);
            }
        } catch (\Throwable $th) {
            if ($request->header('Accept') == 'application/json') {
                return response()->error($th->getMessage(), 'An error occurred', 500);
            } else {
                // Return HTML error view
                throw($th);
            }
        }
    }

    public function index(Request $request)
    {
        try {
            $jobs = JobAdvertResource::collection(JobAdvert::all());
            
            if ($request->header('Accept') == 'application/json') {
                return response()->success($jobs, "All jobs", 200);
            } else {
                // toastr()->success("Found {$jobs->count()} jobs."); 
                return view('job_adverts.index', ['jobs' => $jobs]);
            }
        } catch (\Throwable $th) {
            if ($request->header('Accept') == 'application/json') {
                return response()->error($th->getMessage(), 'An error occurred', 500);
            } else {
                // Return HTML error view
                throw($th);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('job_adverts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        // return 'creating';
        // dd($request->flyer_url);
        // return $request->flyer_url;

        try {
            $filename = '';
            if ($request->hasFile('flyer_url')) {
                $filename = time(). '-' . $request->name . '.' . $request->flyer_url->extension();
                $request->flyer_url->storeAs('uploads/flyers',  $filename);
            }

            // $flyer_url = $request->file('flyer')->store('uploads/flyers');

            $jd = array_filter(explode("\n", $request['jd']));
            $responsibilities = array_filter(explode("\n", $request['responsibilities']));
            $qualifications = array_filter(explode("\n", $request['qualifications']));
            $tags = array_filter(explode("\n", $request['tags']));

        
            $job = JobAdvert::create([
                'user_id' => Auth::user()->id,
                'company_id' => $request->company_id,
                'job_category_id' => $request->job_category_id,
                'profession_id' => $request->profession_id,
                'contract_type_id' => $request->contract_type_id,
                'country_id' => $request->country_id,
                'city' => $request->city,
                'name' => $request->name,
                'min_salary' => $request->min_salary,
                'max_salary' => $request->max_salary,
                'about_job' => $request->about_job,
                'flyer_url' => $filename,
                'application_deadline' => $request->application_deadline ? Carbon::createFromFormat('d/m/Y', $request->application_deadline) : null,
            //     'tags' => json_encode($request->tags),
            //     'qualifications' => json_encode($request->qualifications),
            //     'responsibilities' => json_encode($request->responsibilities),
            //     'jd' => json_encode($request->jd),

                'tags' => $tags,
                'qualifications' => $qualifications,
                'responsibilities' => $responsibilities,
                'jd' => $jd,
            ]);
           
            if ($request->header('Accept') == 'application/json') {
                return response()->success($job, "Created successfully",200);
            } else {
                toastr()->success('Job created successfully');
                return redirect()->route('job_adverts.index');
            }
                
        } catch (\Throwable $th) {
            if ($request->header('Accept') == 'application/json') {
                return response()->error($th->getMessage(),'Error occured, Try again later', 500);
            } else {
                toastr()->error($th->getMessage());
                return back();
            }
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(JobAdvert $job_advert)
    { 
        abort_if(!$job_advert->id, 404, 'Job not found');
        return view('job_adverts.show',['job_advert' => $job_advert]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobRequest $request, JobAdvert $job)
    {
        try {
            $job->job_category_id = $request->job_category_id;
            $job->name = $request->name;
            $job->about_job = $request->about_job;
            $job->jd = $request->jd;
            $job->tags = $request->tags;
            $job->application_deadline = Carbon::createFromFormat('d/m/Y', $request->application_deadline);
            $job->flyer_url = $request->flyer_url;
      
            if ($job->save()){
                return response()->success($job, "Record updated successfully",200);
            } else return response()->error("Couldnt save changes",'Error occured, Try again later', 500);
        } catch (\Throwable $th) {
            return response()->error($th->getMessage(),'Error occured, Try again later', 500);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobAdvert $job)
    {
        try {
            if($job->delete()){
                return response()->success([], "Deleted record successfully",200);
            } else return response()->error("Could not delete item",'Error occured, Try again later', 500);
        } catch (\Throwable $th) {
            return response()->error($th->getMessage(),'Error occured, Try again later', 500);
        }
    }

    public function getRelatedJobs(JobAdvert $job){
        try {
            $jobs = $job->job_category->jobs->merge($job->contract_type->jobs);
            return response()->success(JobResource::collection($jobs), "Related jobs",200);
        } catch (\Throwable $th) {
            return response()->error($th->getMessage(),'Error occured, Try again later', 500);
        }
    }

    public function download_flyer(JobAdvert $job){
        dd($job);
        try {
            $filePath = $job->flyer_url;
            dd($job);
            if (Storage::disk('public')->exists($filePath)) {
                // Serve the file for download
                return Storage::disk('public')->download($filePath);
            } else {
                return response()->json(['error' => 'File not found.'], 404);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    
}

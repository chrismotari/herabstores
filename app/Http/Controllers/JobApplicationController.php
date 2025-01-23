<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('job_applications.index', [
            'jobApplications' => JobApplication::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
        // job_advert_id
        // applicant_id
        // resume_id
        // status_id
        // is_draft
        // cover_letter
        // additional_info
        // progress_tracker
        $jobApplication = JobApplication::create([
            'job_advert_id' => $request->job_advert_id,
            'applicant_id' => $Auth::user()->id,
            'resume_id' => $request->resume_id,
            'status_id' => $request->status_id,
            'is_draft' => $request->is_draft,
            'cover_letter' => $request->cover_letter,
            'additional_info' => $request->additional_info,
            'progress_tracker' => $request->progress_tracker,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(JobApplication $jobApplication)
    {
        return view('job_application.show', [
            'jobApplication' => $jobApplication,
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobApplication $jobApplication)
    {
        return view('job_application.edit', [
            'jobApplication' => $jobApplication,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobApplication $jobApplication)
    {
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobApplication $jobApplication)
    {
        $jobApplication->delete();
        toastr()->flash('Job application deleted successfully');
        return redirect()->back();
    }
}

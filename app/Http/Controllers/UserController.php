<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\ContractType;
use App\Models\Country;
use App\Models\JobCategory;
use App\Models\Profession;
use App\Models\Role;
use App\Models\State;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index', ['users' => User::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $request->validated();
            
            $dp_filename = null;
            $resume_filename = null;

            if ($request->hasFile('photo_url')) {
                $dp_filename = time(). '-' . $request->fname. '-' .$request->lname . '.' . $request->photo_url->extension();
                $request->photo_url->storeAs('public/images/dps',  $dp_filename);
            }


            if ($request->hasFile('resume_url')) {
                $resume_filename = time() . '.' . $request->resume_url->extension();
                $request->resume_url->storeAs('public/files/resumes/',  $resume_filename);
            }   

            $user = User::create([
                'fname'=> $request->fname,
                'mname'=> $request->mname,
                'lname'=> $request->lname,
                'phone'=> $request->phone,
                'alt_phone'=> $request->alt_phone,
                'email'=> $request->email,
                'alt_email'=> $request->alt_email,
                // 'dob'=> strtotime($request->dob),
                'password' => Hash::make($request->password),
                "address" => $request->address,
                "city" => $request->city,
                "state_id" => $request->state_id,
                "zip_code" => $request->zip_code,
                "country_id" => $request->country_id,
                "photo_url" => $dp_filename,
                "resume_url" => $resume_filename,
                "job_category_id" => $request->job_category_id,
                "profession_id" => $request->profession_id,
                "contract_type_id" => $request->contract_type_id,
            ]);

            event(new Registered($user));
            toastr()->success("User created successfully");
            return redirect()->back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', ['this_user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // dd('updating...');
        try {
            $user->update([
                'fname'=> $request->fname,
                'mname'=> $request->mname,
                'lname'=> $request->lname,
                'phone'=> $request->phone,
                'alt_phone'=> $request->alt_phone,
                'email'=> $request->email,
                'alt_email'=> $request->alt_email,
                // 'dob'=> strtotime($request->dob),
                
                "address" => $request->address,
                "city" => $request->city,
                "state_id" => $request->state_id,
                "zip_code" => $request->zip_code,
                "country_id" => $request->country_id,
                "job_category_id" => $request->job_category_id,
                "profession_id" => $request->profession_id,
                "contract_type_id" => $request->contract_type_id,
            ]);
            toastr()->success("User updated successfully");
            return redirect()->back();
        } catch (\Throwable $th) {
            throw $th;
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if($user->id == auth()->user()->id) {
            toastr()->error("You cannot delete yourself!");
            return redirect()->back();
        }
       
        if ($user->photo_url) {
            unlink(storage_path('app/public/images/dps/' . $user->photo_url));
            
        }
        if ($user->resume_url) {
            unlink(storage_path('app/public/files/resumes/' . $user->resume_url));
        }
        if ($user->delete()) {
            toastr()->success("User deleted successfully");
            return redirect()->back();
        } else {
            toastr()->error("Error deleting user");
            return redirect()->back();
        }
    }
}

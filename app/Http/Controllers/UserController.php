<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            // 'admin',
            new Middleware('admin', except: ['store', 'update', 'show']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index', ['users' => User::where('is_admin', true)->get()]);
    }
    public function customers()
    {
        return view('users.index', ['users' => User::where('is_admin', false)->get()]);
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
            $filepath = null;

            if ($request->hasFile('photo_url')) {
                $dp_filename = time(). '-' . $request->fname. '-' .$request->lname . '.' . $request->photo_url->extension();
                $filepath = $request->photo_url->storeAs('images/dps',  $dp_filename, 'public');
            } 

            $user = User::create([
                'fname'=> $request->fname,
                'mname'=> $request->mname,
                'lname'=> $request->lname,
                'phone'=> $request->phone,
                'alt_phone'=> $request->alt_phone,
                'email'=> $request->email,
                'alt_email'=> $request->alt_email,
                'dob'=> strtotime($request->dob),
                'password' => Hash::make($request->password),
                "address" => $request->address,
                "city" => $request->city,
                "estate" => $request->estate,
                "country_id" => $request->country_id,
                "photo_url" => $filepath,
            ]);

            event(new Registered($user));

            if ($request->role_id) {
                Role::findById($request->role_id)->users()->attach($user);
            }
            if (Auth::user()?->is_admin) {
                flash()->success('User created successfully');
                return redirect()->back();
            } else {
                Auth::login($user);
                return redirect(route('home', absolute: false));
            }
            

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // dd($user);
        return view('users.customer_profile', ['selected_user' => $user]);
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
        dd('updating paused...');
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
            ]);
            flash()->success("User info updated successfully");
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
        if($user->id == auth()->id) {
            flash()->error("You cannot delete yourself!");
            return redirect()->back();
        }
       
        if ($user->photo_url && Storage::disk('public')->exists($user->photo_url)) {
            Storage::disk('public')->delete($user->photo_url);
        }
        if ($user->delete()) {
            flash()->success("User deleted successfully");
            return redirect()->back();
        } else {
            flash()->error("Error deleting user");
            return redirect()->back();
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('users.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $resume_filename = null;
        $dp_filename = null;

        try { 
            $request->validated();

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
            ]);

            event(new Registered($user));

            Auth::login($user);

            return redirect(route('dashboard', absolute: false));
        } catch (\Throwable $th) {
            throw ( $th);
        }
    }
}

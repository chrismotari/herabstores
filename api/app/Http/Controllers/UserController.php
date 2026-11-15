<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * A customer's own profile page (routes guarded by UserPolicy).
 * Staff and customer management is in Filament (/admin).
 */
class UserController extends Controller
{
    public function show(User $user)
    {
        return view('users.customer_profile', ['selected_user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'fname' => ['required', 'string', 'max:255'],
            'mname' => ['nullable', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'alt_phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
        ]);

        $user->fill($data);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        flash()->success('Your details have been updated');

        return back();
    }
}

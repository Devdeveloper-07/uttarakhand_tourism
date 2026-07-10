<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([

            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'mobile' => [
                'required',
                'digits:10',
                'unique:user_details,mobile',
            ],

            'gender' => [
                'required',
                'in:Male,Female,Other',
            ],

            'dob' => [
                'nullable',
                'date',
            ],

            'address' => [
                'nullable',
                'string',
                'max:500',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'country' => [
                'nullable',
                'string',
                'max:100',
            ],

            'pincode' => [
                'nullable',
                'digits:6',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload Image
        |--------------------------------------------------------------------------
        */

        $imageName = null;

        if ($request->hasFile('image')) {

            $imageName = time() . '_' . uniqid() . '.' . $request->image->extension();

            $request->image->move(
                public_path('uploads/users'),
                $imageName
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create User
        |--------------------------------------------------------------------------
        */

        $user = User::create([

            'name' => $request->name,

            'email' => $request->email,

            'password' => Hash::make($request->password),

            'role' => 'user',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Create User Details
        |--------------------------------------------------------------------------
        */

        UserDetail::create([

            'user_id'  => $user->id,

            'mobile'   => $request->mobile,

            'gender'   => $request->gender,

            'dob'      => $request->dob,

            'address'  => $request->address,

            'city'     => $request->city,

            'state'    => $request->state,

            'country'  => $request->country,

            'pincode'  => $request->pincode,

            'image'    => $imageName,

            'status'   => 1,

        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('user.dashboard');
    }
}

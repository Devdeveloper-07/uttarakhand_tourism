<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', 'user')
            ->with('detail')
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'mobile' => ['nullable', 'digits:10', 'unique:user_details,mobile'],
            'gender' => ['nullable', 'in:Male,Female,Other'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'boolean'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = now()->format('YmdHis').'_'.Str::random(10).'.'.$image->extension();
            File::ensureDirectoryExists(public_path('uploads/users'));
            $image->move(public_path('uploads/users'), $imageName);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user',
        ]);

        UserDetail::create([
            'user_id' => $user->id,
            'mobile' => $validated['mobile'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'image' => $imageName,
            'status' => (bool) $validated['status'],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User Added Successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        abort_unless($user->role === 'user', 404);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        abort_unless($user->role === 'user', 404);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        abort_unless($user->role === 'user', 404);

        $request->validate([

            'name'     => 'required|max:255',

            'email'    => 'required|email|unique:users,email,' . $user->id,

            'mobile'   => 'nullable|max:20',

            'gender'   => 'nullable|in:Male,Female,Other',

            'image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'status'   => 'required',

            'password' => 'nullable|confirmed|min:6',

        ]);

        /*
        |--------------------------------------------------------------------------
        | Update Users Table
        |--------------------------------------------------------------------------
        */

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | Update User Details
        |--------------------------------------------------------------------------
        */

        $detail = UserDetail::firstOrCreate([
            'user_id' => $user->id,
        ]);

        $imageName = $detail->image;

        if ($request->hasFile('image')) {

            if (
                $detail->image &&
                File::exists(public_path('uploads/users/' . $detail->image))
            ) {
                File::delete(public_path('uploads/users/' . $detail->image));
            }

            $image = $request->file('image');

            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('uploads/users'), $imageName);
        }

        $detail->mobile = $request->mobile;
        $detail->gender = $request->gender;
        $detail->status = $request->status;
        $detail->image = $imageName;

        $detail->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        abort_unless($user->role === 'user', 404);

        if ($user->detail) {

            if (
                $user->detail->image &&
                File::exists(public_path('uploads/users/' . $user->detail->image))
            ) {
                File::delete(public_path('uploads/users/' . $user->detail->image));
            }

            $user->detail->delete();
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User Deleted Successfully.');
    }
}

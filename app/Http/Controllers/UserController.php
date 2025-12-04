<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::paginate(10);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $allowedRoles = $this->getAllowedRoles();
        $userType = $request->query('type', 'user'); // Default to 'user' if no type specified
        return view('users.create', compact('allowedRoles', 'userType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $allowedRoles = $this->getAllowedRoles();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:' . implode(',', $allowedRoles),
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $allowedRoles = $this->getAllowedRoles();
        return view('users.edit', compact('user', 'allowedRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $allowedRoles = $this->getAllowedRoles();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:' . implode(',', $allowedRoles),
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle active status of the user.
     */
    public function toggleActive(string $id)
    {
        $user = User::findOrFail($id);
        $user->update(['active' => !$user->active]);

        return redirect()->route('users.index')->with('success', 'User status updated successfully.');
    }


    /**
     * Get allowed roles based on current user's role.
     */
    private function getAllowedRoles()
    {
        $user = auth()->user();
        if ($user->role === \App\Enums\UserRole::Admin) {
            return ['admin', 'user'];
        }

        return ['user'];
    }
}

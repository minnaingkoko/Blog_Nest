<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with('role');

        if ($request->filled('role')) { // Check if 'role' is not empty
            $query->whereHas('role', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        $users = $query->latest()->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required|exists:roles,id'
        ]);

        $data['password'] = bcrypt($data['password']);
        $data['email_verified_at'] = now(); // Mark email as verified

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role_id' => 'required|exists:roles,id'
        ]);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => ['required', 'exists:roles,id']
        ]);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot change your own role.');
        }

        $user->update(['role_id' => $request->role_id]);

        return redirect()->back()
            ->with('success', "User role changed successfully to " . $user->fresh()->role->name);
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,promote,demote',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $request->user_ids)
            ->where('id', '!=', auth()->id())
            ->get();

        if ($users->isEmpty()) {
            return redirect()->back()->with('error', 'No valid users selected.');
        }

        switch ($request->action) {
            case 'delete':
                $users->each->delete();
                $message = 'Selected users deleted successfully.';
                break;

            case 'promote':
                $users->each->update(['role_id' => 2]);
                $message = 'Selected users promoted to author successfully.';
                break;

            case 'demote':
                $users->each->update(['role_id' => 3]);
                $message = 'Selected users demoted to regular user successfully.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}

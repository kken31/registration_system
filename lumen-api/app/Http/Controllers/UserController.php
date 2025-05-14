<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Protect all methods in this controller
    }

    public function index() // View all users
    {
        return response()->json(User::all());
    }

    public function store(Request $request) // Create/Add new user (can also be part of AuthController's register)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function show($id) // View a specific user
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function update(Request $request, $id) // Edit a user
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $this->validate($request, [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'sometimes|required|string|min:8',
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function destroy($id) // Delete a user
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Optional: Prevent deleting the currently authenticated user or specific users
        // if (Auth::id() == $id) {
        //     return response()->json(['message' => 'You cannot delete yourself.'], 403);
        // }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}

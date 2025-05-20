<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class FrontendUserController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->middleware('frontend.auth'); // Custom middleware to check session token
        $this->apiBaseUrl = config('app.api_base_url');
    }

    private function getHeaders()
    {
        return [
            'Authorization' => 'Bearer ' . Session::get('api_token'),
            'Accept' => 'application/json',
        ];
    }

    public function index() // View all users
    {
        $response = Http::withHeaders($this->getHeaders())->get("{$this->apiBaseUrl}/users");

        if ($response->successful()) {
            $users = $response->json();
            return view('frontend.users.index', compact('users'));
        } else {
            // Handle error, maybe redirect to login if unauthorized
            if ($response->status() === 401) {
                Session::flush();
                return redirect()->route('frontend.login.form')->withErrors(['session' => 'Session expired. Please login again.']);
            }
            return back()->withErrors(['api_error' => 'Failed to fetch users. API Error: ' . $response->status()]);
        }
    }

    public function create() // Show form to add a user
    {
        return view('frontend.users.create');
    }

    public function store(Request $request) // Store a new user via API
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $response = Http::withHeaders($this->getHeaders())->post("{$this->apiBaseUrl}/users", [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            return redirect()->route('frontend.users.index')->with('success', 'User created successfully!');
        } else {
            $errors = $response->json()['errors'] ?? ['api_error' => ['Email has already been taken!']];
            if (isset($response->json()['message']) && !isset($response->json()['errors'])) {
                $errors = ['api_error' => [$response->json()['message']]];
            }
            return back()->withErrors($errors)->withInput();
        }
    }

    public function show($id) // View a specific user (optional on frontend, or for an admin view)
    {
        $response = Http::withHeaders($this->getHeaders())->get("{$this->apiBaseUrl}/users/{$id}");

        if ($response->successful()) {
            $user = $response->json();
            return view('frontend.users.show', compact('user'));
        } else {
            if ($response->status() === 401) {
                Session::flush();
                return redirect()->route('frontend.login.form')->withErrors(['session' => 'Session expired. Please login again.']);
            }
            return redirect()->route('frontend.users.index')->withErrors(['api_error' => 'User not found or API error.']);
        }
    }


    public function edit($id) // Show form to edit a user
    {
        $response = Http::withHeaders($this->getHeaders())->get("{$this->apiBaseUrl}/users/{$id}");

        if ($response->successful()) {
            $user = $response->json();
            return view('frontend.users.edit', compact('user'));
        } else {
            if ($response->status() === 401) {
                Session::flush();
                return redirect()->route('frontend.login.form')->withErrors(['session' => 'Session expired. Please login again.']);
            }
            return redirect()->route('frontend.users.index')->withErrors(['api_error' => 'User not found or API error.']);
        }
    }

    public function update(Request $request, $id) // Update user via API
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255',
            'password' => 'nullable|string|min:8|confirmed', // Password is optional
        ]);

        $data = $request->only(['name', 'email']);
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $response = Http::withHeaders($this->getHeaders())->put("{$this->apiBaseUrl}/users/{$id}", $data);

        if ($response->successful()) {
            return redirect()->route('frontend.users.index')->with('success', 'User updated successfully!');
        } else {
            $errors = $response->json()['errors'] ?? ['api_error' => ['Failed to update user. API Error: ' . $response->status()]];
            if (isset($response->json()['message']) && !isset($response->json()['errors'])) {
                $errors = ['api_error' => [$response->json()['message']]];
            }
            return back()->withErrors($errors)->withInput();
        }
    }

    public function destroy($id) // Delete user via API
    {
        $response = Http::withHeaders($this->getHeaders())->delete("{$this->apiBaseUrl}/users/{$id}");

        if ($response->successful()) {
            return redirect()->route('frontend.users.index')->with('success', 'User deleted successfully!');
        } else {
            if ($response->status() === 401) {
                Session::flush();
                return redirect()->route('frontend.login.form')->withErrors(['session' => 'Session expired. Please login again.']);
            }
            $errorMessage = $response->json()['message'] ?? 'Failed to delete user. API Error: ' . $response->status();
            return back()->withErrors(['api_error' => $errorMessage]);
        }
    }
}

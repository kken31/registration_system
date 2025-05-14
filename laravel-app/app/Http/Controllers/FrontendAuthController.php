<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // Laravel HTTP Client
use Illuminate\Support\Facades\Session; // To store token

class FrontendAuthController extends Controller
{
    protected $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('app.api_base_url'); // Get API URL from .env or config
    }

    public function showLoginForm()
    {
        if (Session::has('api_token')) {
            return redirect()->route('frontend.users.index');
        }
        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $response = Http::post("{$this->apiBaseUrl}/auth/login", [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful() && isset($response->json()['authorisation']['token'])) {
            Session::put('api_token', $response->json()['authorisation']['token']);
            Session::put('user_name', $response->json()['user']['name']); // Store user info if needed
            return redirect()->route('frontend.users.index')->with('success', 'Logged in successfully!');
        } else {
            $errorMessage = $response->json()['message'] ?? 'Login failed. Please check your credentials.';
            return back()->withErrors(['email' => $errorMessage])->withInput();
        }
    }

    public function showRegistrationForm()
    {
        if (Session::has('api_token')) {
            return redirect()->route('frontend.users.index');
        }
        return view('frontend.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $response = Http::post("{$this->apiBaseUrl}/auth/register", [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        if ($response->successful() && isset($response->json()['authorisation']['token'])) {
            Session::put('api_token', $response->json()['authorisation']['token']);
            Session::put('user_name', $response->json()['user']['name']);
            return redirect()->route('frontend.users.index')->with('success', 'Registration successful! You are now logged in.');
        } else {
            $errors = $response->json()['errors'] ?? ['registration' => ['Registration failed. Please try again.']];
            if (isset($response->json()['message']) && !isset($response->json()['errors'])) {
                $errors = ['registration' => [$response->json()['message']]];
            }
            return back()->withErrors($errors)->withInput();
        }
    }

    public function logout(Request $request)
    {
        $token = Session::get('api_token');
        if ($token) {
            Http::withToken($token)->post("{$this->apiBaseUrl}/auth/logout");
        }
        Session::flush(); // Clear all session data
        return redirect()->route('frontend.login.form')->with('success', 'Logged out successfully!');
    }
}

@extends('layouts.frontend')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2>Register</h2>
        <form method="POST" action="{{ route('frontend.register') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                @error('email') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                @error('password') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
            <p class="mt-3">Already have an account? <a href="{{ route('frontend.login.form') }}">Login here</a></p>
        </form>
    </div>
</div>
@endsection
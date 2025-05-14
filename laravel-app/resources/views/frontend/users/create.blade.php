@extends('layouts.frontend')

@section('title', 'Add New User')

@section('content')

<h2>Add New User</h2>
<form method="POST" action="{{ route('frontend.users.store') }}">
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
    <button type="submit" class="btn btn-primary">Create User</button>
    <a href="{{ route('frontend.users.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection
@extends('layouts.frontend')

@section('title', 'User Details')

@section('content')
<h2>User Details</h2>
@if($user)
<p><strong>ID:</strong> {{ $user['id'] }}</p>
<p><strong>Name:</strong> {{ $user['name'] }}</p>
<p><strong>Email:</strong> {{ $user['email'] }}</p>
<p><strong>Created At:</strong> {{ isset($user['created_at']) ? \Carbon\Carbon::parse($user['created_at'])->format('Y-m-d H:i:s') : 'N/A' }}</p>
<p><strong>Updated At:</strong> {{ isset($user['updated_at']) ? \Carbon\Carbon::parse($user['updated_at'])->format('Y-m-d H:i:s') : 'N/A' }}</p>
@else
<p>User not found.</p>
@endif
<a href="{{ route('frontend.users.index') }}" class="btn btn-primary">Back to Users List</a>
@endsection
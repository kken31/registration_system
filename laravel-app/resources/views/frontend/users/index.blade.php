@extends('layouts.frontend')

@section('title', 'Users List')

@section('content')
<h2>Users</h2>
<a href="{{ route('frontend.users.create') }}" class="btn btn-success mb-3">Add New User</a>

@if(!empty($users))
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
        <tr>
            <td>{{ $user['id'] }}</td>
            <td>{{ $user['name'] }}</td>
            <td>{{ $user['email'] }}</td>
            <td>
                <a href="{{ route('frontend.users.edit', $user['id']) }}" class="btn btn-sm btn-primary">Edit</a>
                <form action="{{ route('frontend.users.destroy', $user['id']) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p>No users found.</p>
@endif
@endsection
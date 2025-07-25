@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Assign Role to User</h2>
    <form method="POST" action="{{ route('users.assign-role.store') }}">
        @csrf
        <div class="form-group">
            <label for="user_id">Select User</label>
            <select name="user_id" class="form-control">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mt-2">
            <label for="role">Select Role</label>
            <select name="role" class="form-control">
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Assign Role</button>
    </form>
</div>
@endsection
@extends('staff.layouts.app')
@section('content')
    <h2>{{ isset($user) ? 'Edit' : 'Tambah' }} User</h2>
    <form method="POST" action="{{ isset($user) ? '/staff/users/' . $user['id'] . '/update' : '/staff/users' }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $user['name'] ?? '' }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ $user['email'] ?? '' }}" required>
        </div>
        @if(!isset($user))
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        @endif
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-select" id="role" name="role" required>
                <option value="user" @if(isset($user) && $user['role'] == 'user') selected @endif>User</option>
                <option value="staff" @if(isset($user) && $user['role'] == 'staff') selected @endif>Staff</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
@endsection 
@extends('staff.layouts.app')
@section('content')
    <h2>Manajemen User</h2>
    <a href="/staff/users/create" class="btn btn-primary mb-3">Tambah User</a>
     @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($users))
                @foreach($users as $user)
                <tr>
                    <td>{{ $user['name'] }}</td>
                    <td>{{ $user['email'] }}</td>
                    <td>{{ $user['role'] }}</td>
                    <td>
                        <a href="/staff/users/{{ $user['id'] }}/edit" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="/staff/users/{{ $user['id'] }}/delete" style="display:inline-block">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
@endsection 
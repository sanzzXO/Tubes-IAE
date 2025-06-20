@extends('staff.layouts.app')
@section('content')
    <h2>Manajemen Kategori</h2>
    <a href="/staff/categories/create" class="btn btn-primary mb-3">Tambah Kategori</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nama Kategori</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category['name'] }}</td>
                <td>
                    <a href="/staff/categories/{{ $category['id'] }}/edit" class="btn btn-sm btn-warning">Edit</a>
                    <form method="POST" action="/staff/categories/{{ $category['id'] }}/delete" style="display:inline-block">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection 
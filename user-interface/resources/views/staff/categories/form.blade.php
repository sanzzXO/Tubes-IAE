@extends('staff.layouts.app')
@section('content')
    <h2>{{ isset($category) ? 'Edit' : 'Tambah' }} Kategori</h2>
    <form method="POST" action="{{ isset($category) ? '/staff/categories/' . $category['id'] . '/update' : '/staff/categories' }}">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nama Kategori</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $category['name'] ?? '' }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
@endsection 
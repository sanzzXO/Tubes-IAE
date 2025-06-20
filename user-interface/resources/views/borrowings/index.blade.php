@extends('layouts.app')
@section('content')
<h2>Daftar Peminjaman</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Judul Buku</th>
            <th>Tanggal Pinjam</th>
            <th>Tanggal Kembali</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @if(is_array($borrowings) && count($borrowings) > 0)
            @foreach($borrowings as $borrowing)
            <tr>
                <td>{{ $borrowing['book_title'] ?? '-' }}</td>
                <td>{{ $borrowing['created_at'] ?? '-' }}</td>
                <td>
                    @if(isset($borrowing['is_returned']) && $borrowing['is_returned'])
                        {{ $borrowing['updated_at'] ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                <td>
                    @if(isset($borrowing['is_returned']) && $borrowing['is_returned'])
                        <span class="badge bg-success">Sudah Dikembalikan</span>
                    @else
                        <span class="badge bg-warning text-dark">Belum Dikembalikan</span>
                    @endif
                </td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" class="text-center text-danger">Tidak ada data peminjaman.</td>
            </tr>
        @endif
    </tbody>
</table>
@endsection 
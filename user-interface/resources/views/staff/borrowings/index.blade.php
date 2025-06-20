@extends('staff.layouts.app')
@section('content')
    <h2>Manajemen Peminjaman</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Book ID</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($borrowings))
                @foreach($borrowings as $borrowing)
                <tr>
                    <td>{{ $borrowing['user_id'] }}</td>
                    <td>{{ $borrowing['book_id'] }}</td>
                    <td>{{ $borrowing['created_at'] }}</td>
                    <td>{{ $borrowing['updated_at'] ?? '-' }}</td>
                    <td>{{ $borrowing['is_returned'] ? 'Sudah Kembali' : 'Dipinjam' }}</td>
                    <td>
                        @if(!$borrowing['is_returned'])
                        <form method="POST" action="/staff/borrowings/{{ $borrowing['id'] }}/return" style="display:inline-block">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Kembalikan</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
@endsection 
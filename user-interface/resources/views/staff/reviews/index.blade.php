@extends('staff.layouts.app')
@section('content')
    <h2>Manajemen Review</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Book ID</th>
                <th>Rating</th>
                <th>Review</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
             @if(!empty($reviews))
                @foreach($reviews as $review)
                <tr>
                    <td>{{ $review['user_id'] }}</td>
                    <td>{{ $review['book_id'] }}</td>
                    <td>{{ $review['rating'] }}</td>
                    <td>{{ $review['comment'] }}</td>
                    <td>
                        <form method="POST" action="/staff/reviews/{{ $review['id'] }}/delete" style="display:inline-block">
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
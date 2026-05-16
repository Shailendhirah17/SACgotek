@extends('backEnd.master')
@section('title') Book Bank @endsection
@section('mainContent')
<section class="sms-breadcrumb mb-20">
    <div class="container-fluid">
        <div class="row justify-content-between">
            <h1>Book Bank</h1>
            <div class="bc-pages">
                <a href="{{ route('admin-dashboard') }}">@lang('common.dashboard')</a>
                <a href="#">Library &amp; Book Bank</a>
                <a href="#">Book Bank</a>
            </div>
        </div>
    </div>
</section>

<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row">
            {{-- Add Book Form --}}
            <div class="col-lg-4">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-book mr-2 text-primary"></i> Add Book to Bank</h4>
                    <form action="{{ route('book-bank.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Book Name <span class="text-danger">*</span></label>
                            <input type="text" name="book_name" class="form-control" placeholder="Enter book title" required>
                        </div>
                        <div class="form-group">
                            <label>Author</label>
                            <input type="text" name="author" class="form-control" placeholder="Author name">
                        </div>
                        <div class="form-group">
                            <label>ISBN</label>
                            <input type="text" name="isbn" class="form-control" placeholder="ISBN number">
                        </div>
                        <div class="form-group">
                            <label>Publisher</label>
                            <input type="text" name="publisher" class="form-control" placeholder="Publisher name">
                        </div>
                        <div class="form-group">
                            <label>Class / Grade</label>
                            <input type="text" name="class" class="form-control" placeholder="e.g. Class 10">
                        </div>
                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" name="subject" class="form-control" placeholder="e.g. Mathematics">
                        </div>
                        <div class="form-group">
                            <label>Total Copies</label>
                            <input type="number" name="total_copies" class="form-control" value="1" min="1">
                        </div>
                        <button type="submit" class="primary-btn fix-gr-bg">
                            <i class="fas fa-plus mr-1"></i> Add Book
                        </button>
                    </form>
                </div>
            </div>

            {{-- Book Bank List --}}
            <div class="col-lg-8">
                <div class="white-box">
                    <h4 class="mb-20"><i class="fas fa-list mr-2 text-primary"></i> Book Bank List</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered data-table" id="bookTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Book Name</th>
                                    <th>Author</th>
                                    <th>Class</th>
                                    <th>Subject</th>
                                    <th>Total</th>
                                    <th>Available</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($books as $i => $book)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td><strong>{{ $book->book_name }}</strong></td>
                                    <td>{{ $book->author ?? '—' }}</td>
                                    <td>{{ $book->class ?? '—' }}</td>
                                    <td>{{ $book->subject ?? '—' }}</td>
                                    <td><span class="badge badge-info">{{ $book->total_copies }}</span></td>
                                    <td>
                                        <span class="badge badge-{{ $book->available_copies > 0 ? 'success' : 'danger' }}">
                                            {{ $book->available_copies }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="primary-btn small bg-primary border-0 text-white" 
                                                data-toggle="modal" data-target="#editBookModal{{ $book->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="{{ route('book-bank.delete', $book->id) }}"
                                           class="primary-btn small bg-danger border-0 text-white"
                                           onclick="return confirm('Delete this book?')">
                                            <i class="fas fa-trash"></i>
                                        </a>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="editBookModal{{ $book->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Book</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="{{ route('book-bank.update') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                                        <div class="modal-body">
                                                            <div class="form-group text-left">
                                                                <label>Book Name <span class="text-danger">*</span></label>
                                                                <input type="text" name="book_name" class="form-control" value="{{ $book->book_name }}" required>
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Author</label>
                                                                <input type="text" name="author" class="form-control" value="{{ $book->author }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>ISBN</label>
                                                                <input type="text" name="isbn" class="form-control" value="{{ $book->isbn }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Publisher</label>
                                                                <input type="text" name="publisher" class="form-control" value="{{ $book->publisher }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Class / Grade</label>
                                                                <input type="text" name="class" class="form-control" value="{{ $book->class }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Subject</label>
                                                                <input type="text" name="subject" class="form-control" value="{{ $book->subject }}">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label>Total Copies <span class="text-danger">*</span></label>
                                                                <input type="number" name="total_copies" class="form-control" value="{{ $book->total_copies }}" min="1" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="primary-btn fix-gr-bg">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i> No books in bank yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#bookTable').DataTable({ responsive: true, pageLength: 15 });
});
</script>
@endpush

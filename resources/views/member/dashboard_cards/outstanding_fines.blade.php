<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">


<div class="container mt-0">
    <h2 class="text-white text-center">Outstanding Fines</h2>
        <div class="card">
            <div class="card-body">
                <table id="users-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>ISBN</th>
                            <th>Library</th>
                            <th>Due Date</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($books as $book)
                            <tr>
                                <td>{{ $book->borrow->book->title }}</td>
                                <td>{{ $book->borrow->book->isbn  }}</td>
                                <td>{{ $book->borrow->library->name }}</td>
                                <td>{{ $book->borrow->due_date }}</td>
                                <td>{{ $book->amount }}</td>
                            </tr>
                        @empty
                            <tr> 
                                <td colspan="5">No payments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
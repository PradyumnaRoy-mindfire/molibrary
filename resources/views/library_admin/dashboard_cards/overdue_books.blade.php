<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">

<div class="container mt-3">
<h4 class="text-center text-white">Overdue Books</h4>
    <div class="card">
        <div class="card-body">
            <table id="users-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">Book Name</th>
                        <th class="text-center">ISBN</th>
                        <th class="text-center">Borrowed By</th>
                        <th class="text-center">Issued Date</th>
                        <th class="text-center">Return Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($overdueBooks as $borrow)
                    <tr>
                        <td class="text-center">{{ $borrow->book->title }}</td>
                        <td class="text-center">{{ $borrow->book->isbn }}</td>
                        <td class="text-center">{{ $borrow->user->name }}</td>
                        <td class="text-center">{{ $borrow->borrow_date }}</td>
                        <td class="text-center"> {{ $borrow->due_date }} </td>
                    </tr>
                    @empty
                    
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
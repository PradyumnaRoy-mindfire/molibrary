<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">

<div class="container mt-3">
<h4 class="text-center text-white">Total Revenue</h4>
        <div class="card">
            <div class="card-body">
                <table id="users-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center align-middle">User Name</th>
                            <th class="text-center align-middle">Email</th>
                            <th class="text-center align-middle">Book</th>
                            <th class="text-center align-middle">Amount</th>
                            <th class="text-center align-middle">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fines as $fine)
                            <tr class="text-center align-middle">
                                <td>{{ $fine->borrow->user->name }}</td>
                                <td>{{ $fine->borrow->user->email }}</td>
                                <td>{{ $fine->borrow->book->title }}</td>
                                <td>{{ $fine->amount }} </td>
                                <td>{{ $fine->status }} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
   
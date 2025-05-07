<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">

<style>
    #users-table_length:first-child,#users-table_info {
        display: flex;
    }
</style>

<div class="container mt-3">
    <h4 class="text-center text-white">Low Stock Books</h4>
    <div class="card">
        <div class="card-body">
            <table id="users-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr class="text-center align-middle">
                        <th class="text-center align-middle">Book</th>
                        <th class="text-center align-middle">ISBN</th>
                        <th class="text-center align-middle">Current Stock</th>
                        <th class="text-center align-middle">Borrow Frequency</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lowStockBooks as $book)
                    <tr class="text-center align-middle">
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->isbn }}</td>
                        <td>{{ $book->total_copies }}</td>
                        <td>{{ $book->borrows_count }}</td>
                    </tr>
                    @empty
                    
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
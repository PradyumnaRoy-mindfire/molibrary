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
                    <tr>
                        <th class="text-center">Book</th>
                        <th class="text-center">ISBN</th>
                        <th class="text-center">Current Stock</th>
                        <th class="text-center">Borrow Frequency</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($lowStockBooks as $book)
                    <tr>
                        <td class="text-center">{{ $book->title }}</td>
                        <td class="text-center">{{ $book->isbn }}</td>
                        <td class="text-center">{{ $book->total_copies }}</td>
                        <td class="text-center">{{ $book->borrows_count }}</td>
                    </tr>
                    @empty
                    
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">


<div class="container mt-0">
    <h2 class="text-white text-center">Active Libraries</h2>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-bordered dt-responsive nowrap" id="library-table">
                <thead class="table-primary">
                    <tr >
                        <th scope="col" class="text-center align-middle">Library Name</th>
                        <th scope="col" class="text-center align-middle">No. of Books</th>
                        <th scope="col" class="text-center align-middle">Location</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($libraries as $library)
                    <tr class="text-center align-middle">
                        <td>{{ $library->name }}</td>
                        <td>{{ $library->books_count }}</td>
                        </td>
                        <td>{{ $library->location }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">No active libraries found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

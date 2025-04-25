<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">

<div class="container mt-3">
    <h4 class="text-center text-white">Popular Libraries</h4>
    <div class="card">
        <div class="card-body">
            <table id="users-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">Library Name</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">No. of Books</th>
                        <th class="text-center">Library Admin</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($libraries as $library)
                    <tr>
                        <td class="text-center">{{ $library->name }}</td>
                        <td class="text-center">{{ $library->location }}</td>
                        <td class="text-center">{{ $library->books_count }}</td>
                        <td class="text-center">{{ $library->admin->name }}</td>
                        <td class="text-center"> @if($library->status == 'open') <span class="badge bg-success">Active</span> @else <span class="badge bg-danger">Inactive</span> @endif </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">No Library found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
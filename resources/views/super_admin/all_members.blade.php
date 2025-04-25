@extends('layout.app')

@section('title','All members')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="d-flex justify-content-between  text-end bg-dark mb-1">
</div>
<div class="container">
    <h3 class=" text-white text-center" style="font-size: 2rem;">Member Statistics</h3>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover sortable" id="memberTable" style="font-size: 1.15rem;">
                    <thead class="table-info">
                        <tr>
                            <th class="text-center">Member Name ⬍</th>
                            <th class="text-center">Total Fines ⬍</th>
                            <th class="text-center">Borrowed Books ⬍</th>
                            <th class="text-center">Preferred Categories</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Responsive DataTables JS -->
    <!-- <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script> -->
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/responsive.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            serverSide: true,
            processing: true,
            ajax: "{{ route('all.members') }}",
            columns: [{
                    data: 'name',
                    name: 'name',
                    className: 'text-center'
                },
                {
                    data: 'total_fine',
                    name: 'total_fine',
                    className: 'text-center'
                },
                {
                    data: 'borrowed_books_count',
                    name: 'borrowed_books_count',
                    className: 'text-center'
                },
                {
                    data: 'preferred_categories',
                    name: 'preferred_categories',
                    className: 'text-center'
                },
            ]
        });
    });
</script>
@endpush
@extends('layout.app')

@section('title','All members')


@section('content')
<div class="container">
    
    <div class="d-flex justify-content-between  text-end bg-dark mb-1">
        <h3 class=" text-white p-1" style="font-size: 2rem;">Member Statistics</h3>
    </div>
    
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover sortable" id="memberTable" style="font-size: 1.15rem;">
            <thead class="table-info">
                <tr>
                    <th class="text-center" >Member Name ⬍</th>
                    <th class="text-center" >Total Fines ⬍</th>
                    <th class="text-center" >Borrowed Books ⬍</th>
                    <th class="text-center">Preferred Categories</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
</div>



@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script>
    $(document).ready(function() {
    $('.table').DataTable({
        serverSide: true,
        processing: true,
        ajax: "{{ route('all.members') }}",
        columns: [
            { data: 'name', name: 'name', className: 'text-center' },
            { data: 'total_fine', name: 'total_fine', className: 'text-center' },
            { data: 'borrowed_books_count', name: 'borrowed_books_count', className: 'text-center' },
            { data: 'preferred_categories', name: 'preferred_categories', className: 'text-center' },
        ]
    });
});
</script>
@endpush


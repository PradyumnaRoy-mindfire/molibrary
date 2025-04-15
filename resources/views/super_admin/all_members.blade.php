@extends('layout.app')

@section('title','All members')


@section('content')
<div class="container">
    
    {{-- Search Bar --}}
    
    <div class="d-flex justify-content-between  text-end bg-dark mb-1">
        <h3 class=" text-white p-1" style="font-size: 2rem;">Member Statistics</h3>
        <input type="text" id="memberSearch" class="form-control form-control-lg" 
               placeholder="Search by member name..." onkeyup="filterMembers()"
               style="max-width: 400px; ">
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover sortable" id="memberTable" style="font-size: 1.15rem;">
            <thead class="table-info">
                <tr>
                    <th class="text-center" onclick="sortTable(0)">Member Name ⬍</th>
                    <th class="text-center" onclick="sortTable(1)">Total Fines ⬍</th>
                    <th class="text-center" onclick="sortTable(2)">Borrowed Books ⬍</th>
                    <th class="text-center">Preferred Categories</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($members as $member)
                <tr>
                    <td class="text-center">{{ $member->name }}</td>
                    <td class="text-center">₹{{ $member->total_fine ?? 0 }}</td>
                    <td class="text-center">{{ $member->borrowed_books_count }}</td>
                    <td class="text-center">
                        @php
                        $categories = $member->preferred_categories;
                        $topCategories = array_slice($categories, 0, 3);
                        @endphp

                        @foreach ($topCategories as $category)
                        <span class="badge bg-warning text-dark">{{ $category }}</span>
                        @endforeach
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 d-flex justify-content-center">
    {{ $members->links('pagination::bootstrap-5') }}
</div>

@endsection

@push('scripts')
<script src=" {{ url('js/super_admin/all_member.js') }} "></script>
@endpush


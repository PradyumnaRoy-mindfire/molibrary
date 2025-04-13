@extends('layout.app')

@section('title','All members')


@section('content')
<div class="container">
    <h3 class="mb-4 text-white text-center" style="font-size: 2rem;">Member Statistics</h3>

    {{-- Search Bar --}}
    <div class="mb-4 text-end">
        <input type="text" id="memberSearch" class="form-control form-control-lg" 
               placeholder="Search by member name..." onkeyup="filterMembers()"
               style="max-width: 400px; margin-left: auto; margin-right: auto;">
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
<script>
    function sortTable(columnIndex) {
        const table = document.querySelector('.sortable');
        let switching = true,
            shouldSwitch, i, x, y;
        let dir = "asc",
            switchcount = 0;
        while (switching) {
            switching = false;
            const rows = table.rows;
            for (i = 1; i < rows.length - 1; i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[columnIndex];
                y = rows[i + 1].getElementsByTagName("TD")[columnIndex];
                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }
    function filterMembers() {
        const input = document.getElementById("memberSearch");
        const filter = input.value.toLowerCase();
        const table = document.getElementById("memberTable");
        const rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {
            const td = rows[i].getElementsByTagName("td")[0];
            if (td) {
                const txtValue = td.textContent || td.innerText;
                rows[i].style.display = txtValue.toLowerCase().includes(filter) ? "" : "none";
            }
        }
    }
</script>

@endspush

<div class="table-responsive mt-4 mx-auto" style="width: 80%;">
    <table class="table table-hover table-bordered align-middle shadow-sm rounded-3 overflow-hidden">
        <thead class="table-primary">
            <tr>
                <th scope="col" class="text-center">Library Name</th>
                <th scope="col" class="text-center">No. of Books</th>
                <th scope="col" class="text-center">Location</th>
            </tr>
        </thead>
        <tbody>
            @forelse($libraries as $library)
            <tr>
                <td class="text-center">{{ $library->name }}</td>
                <td class="text-center">{{ $library->books_count }}</td></td>
                <td class="text-center">{{ $library->location }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">No active libraries found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    
</script>
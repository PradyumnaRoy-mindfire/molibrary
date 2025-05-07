<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">

<style>
    #users-table_length:first-child,#users-table_info {
        display: flex;
    }
</style>

<div class="container mt-3">
    <h4 class="text-center text-white">Most Borrowed Books</h4>
    <div class="card">
        <div class="card-body">
            <table id="users-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th class="text-center">Book Name</th>
                        <th class="text-center">ISBN</th>
                        <th class="text-center">Author</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Times Borrowed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $book)
                    <tr>
                        <td class="text-center">{{ $book->title }}</td>
                        <td class="text-center">{{ $book->isbn }}</td>
                        <td class="text-center">{{ $book->author->name }}</td>
                        <td class="text-center">{{ $book->category->name }}</td>
                        <td class="text-center"> {{ $book->borrows_count }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
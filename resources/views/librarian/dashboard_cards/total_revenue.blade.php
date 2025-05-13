<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<style>
    #users-table_length:first-child,#users-table_info {
        display: flex;
    }
</style>

<div class="container mt-3">
<h4 class="text-center text-white">Total Revenue</h4>
        <div class="card">
            <div class="card-body">
                <table id="users-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th class="text-center">User Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Book</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fines as $fine)
                            <tr>
                                <td>{{ $fine->borrow->user->name }}</td>
                                <td>{{ $fine->borrow->user->email }}</td>
                                <td>{{ $fine->borrow->book->title }}</td>
                                <td>{{ $fine->amount }} </td>
                                <td>
                                    @if($fine->status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-danger">Pending</span>
                                        <i class="fa-solid fa-bell fa-xl fa-shake ms-2 bellIcon" data-userid="{{ $fine->borrow->user->id }}" data-fineid=" {{ $fine->id }} " style="cursor: pointer;" title="Send Notification"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.bellIcon').click(function() {
                console.log($(this).data('userid'));
                let userId = parseInt($(this).data('userid'));
                let fineId = parseInt($(this).data('fineid'));
                
                const url = "{{ route('librarian.fine.notification', ['fine' => ':fineId' ,'user' => ':userId'] ) }}".replace(':userId', userId).replace(':fineId', fineId);
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        let Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Fine Reminder sent successfully'
                        })
                    }
                })
            });
        })
    </script>

    
   